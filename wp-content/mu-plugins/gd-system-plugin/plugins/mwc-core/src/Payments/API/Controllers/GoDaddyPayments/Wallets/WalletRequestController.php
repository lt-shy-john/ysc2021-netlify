<?php

namespace GoDaddy\WordPress\MWC\Core\Payments\API\Controllers\GoDaddyPayments\Wallets;

use Exception;
use GoDaddy\WordPress\MWC\Common\Components\Contracts\ConditionalComponentContract;
use GoDaddy\WordPress\MWC\Common\Helpers\ArrayHelper;
use GoDaddy\WordPress\MWC\Common\Repositories\WooCommerce\ProductsRepository;
use GoDaddy\WordPress\MWC\Common\Repositories\WooCommerceRepository;
use GoDaddy\WordPress\MWC\Core\Payments\API\Traits\InitializesCartTrait;
use GoDaddy\WordPress\MWC\Core\Payments\API\Traits\VerifiesNonceTrait;
use GoDaddy\WordPress\MWC\Core\Payments\Exceptions\InvalidProductException;
use GoDaddy\WordPress\MWC\Core\Payments\Poynt\Http\Adapters\SessionWalletRequestAdapter;
use GoDaddy\WordPress\MWC\Core\Payments\Poynt\Http\ProductLineObject;
use GoDaddy\WordPress\MWC\Core\WooCommerce\Exceptions\GatewayNotFoundException;
use GoDaddy\WordPress\MWC\Core\WooCommerce\Payments\CorePaymentGateways;
use GoDaddy\WordPress\MWC\Core\WooCommerce\Payments\GoDaddyPayments\ApplePayGateway;
use GoDaddy\WordPress\MWC\Core\WooCommerce\Payments\GoDaddyPayments\GooglePayGateway;
use GoDaddy\WordPress\MWC\Dashboard\API\Controllers\AbstractController;
use WP_Error;
use WP_REST_Request;
use WP_REST_Response;

/**
 * Payment request controller.
 */
class WalletRequestController extends AbstractController implements ConditionalComponentContract
{
    use InitializesCartTrait;
    use VerifiesNonceTrait;

    /** @var ProductLineObject[] product lines passed in by the request */
    protected $products = [];

    /**
     * Sets the endpoint route.
     */
    public function __construct()
    {
        $this->route = 'payments/godaddy-payments/wallets';
    }

    /**
     * Loads the component and registers the endpoint routes.
     */
    public function load() : void
    {
        $this->registerRoutes();
    }

    /**
     * Registers the endpoint routes.
     */
    public function registerRoutes() : void
    {
        register_rest_route($this->namespace, '/'.$this->route.'/request', [
            [
                'methods'             => 'GET',
                'callback'            => [$this, 'getPaymentRequest'],
                'permission_callback' => '__return_true',
                'schema'              => [$this, 'getItemSchema'],
            ],
        ]);
    }

    /**
     * Gets the payment request for the current customer.
     *
     * @return WP_Error|WP_REST_Response
     * @internal
     */
    public function getPaymentRequest(WP_REST_Request $request)
    {
        try {
            $this->initializeProducts($request);
            $this->initializeCart();
            $this->validateProducts();

            $response = SessionWalletRequestAdapter::getNewInstance(WooCommerceRepository::getCartInstance(), $this->products)
                ->convertFromSource()
                ->toArray();
        } catch (Exception $exception) {
            $response = $this->getPaymentRequestError($exception);
        }

        return rest_ensure_response($response);
    }

    /**
     * Gets a payment request error.
     *
     * @param Exception $exception
     * @return WP_Error
     */
    protected function getPaymentRequestError(Exception $exception) : WP_Error
    {
        return new WP_Error(
            'UNKNOWN',
            $exception->getMessage(),
            [
                'status' => $exception instanceof InvalidProductException ? 400 : 500,
                'field'  => null,
            ]
        );
    }

    /**
     * Gets the item schema.
     *
     * @internal
     *
     * @return array<mixed>
     */
    public function getItemSchema() : array
    {
        return [
            '$schema'    => 'http://json-schema.org/draft-04/schema#',
            'title'      => 'status',
            'type'       => 'object',
            'properties' => [
                'total' => [
                    'description' => __('Order total, based on cart total.', 'mwc-core'),
                    'type'        => 'object',
                    'context'     => ['view', 'edit'],
                    'readonly'    => true,
                    'properties'  => [
                        'amount' => [
                            'type' => 'float',
                        ],
                        'label' => [
                            'type' => 'string',
                        ],
                    ],
                ],
                'country' => [
                    'description' => __('2-letter ISO 3166 country code.', 'mwc-core'),
                    'type'        => 'string',
                    'context'     => ['view', 'edit'],
                    'readonly'    => true,
                ],
                'currency' => [
                    'description' => __('3-letter ISO 4217 currency code.', 'mwc-core'),
                    'type'        => 'string',
                    'context'     => ['view', 'edit'],
                    'readonly'    => true,
                ],
                'merchantName' => [
                    'description' => __('Name of the merchant.', 'mwc-core'),
                    'type'        => 'string',
                    'context'     => ['view', 'edit'],
                    'readonly'    => true,
                ],
                'shippingType' => [
                    'description' => __('The shipping type based on the chosen shipping method.', 'mwc-core'),
                    'type'        => 'string',
                    'context'     => ['view', 'edit'],
                    'readonly'    => true,
                    'enum'        => [
                        'delivery',
                        'pickup',
                        'shipping',
                    ],
                ],
                'shippingMethods' => [
                    'description' => __('Shipping methods for the payment request.', 'mwc-core'),
                    'type'        => 'array',
                    'context'     => ['view', 'edit'],
                    'readonly'    => true,
                    'items'       => [
                        'type' => 'string',
                    ],
                ],
                'lineItems' => [
                    'description' => __('Items in the order.', 'mwc-core'),
                    'type'        => 'array',
                    'context'     => ['view', 'edit'],
                    'readonly'    => true,
                    'items'       => [
                        'type'       => 'object',
                        'properties' => [
                            'amount' => [
                                'type' => 'float',
                            ],
                            'label' => [
                                'type' => 'string',
                            ],
                        ],
                    ],
                ],
                'requireEmail' => [
                    'description' => __('Whether to require customer email.', 'mwc-core'),
                    'type'        => 'boolean',
                    'context'     => ['view', 'edit'],
                    'readonly'    => true,
                ],
                'requirePhone' => [
                    'description' => __('Whether to require customer phone.', 'mwc-core'),
                    'type'        => 'boolean',
                    'context'     => ['view', 'edit'],
                    'readonly'    => true,
                ],
                'requireShippingAddress' => [
                    'description' => __('Whether to require customer shipping address.', 'mwc-core'),
                    'type'        => 'boolean',
                    'context'     => ['view', 'edit'],
                    'readonly'    => true,
                ],
                'supportCouponCode' => [
                    'description' => __('Whether the customer should be allowed to enter coupons.', 'mwc-core'),
                    'type'        => 'boolean',
                    'context'     => ['view', 'edit'],
                    'readonly'    => true,
                ],
                'disableWallets' => [
                    'description' => __('A list of wallets to disable.', 'mwc-core'),
                    'type'        => 'object',
                    'context'     => ['view', 'edit'],
                    'readonly'    => true,
                    'properties'  => [
                        'applePay' => [
                            'type' => 'boolean',
                        ],
                        'googlePay' => [
                            'type' => 'boolean',
                        ],
                    ],
                ],

            ],
        ];
    }

    /**
     * Determines whether the component should load.
     *
     * @return bool
     * @throws Exception
     */
    public static function shouldLoad() : bool
    {
        return ApplePayGateway::isActive() || GooglePayGateway::isActive();
    }

    /**
     * Initialized products passed in by the request.
     *
     * @param WP_REST_Request $request
     * @return ProductLineObject[]
     * @throws Exception
     */
    protected function initializeProducts(WP_REST_Request $request) : array
    {
        $products = $request->get_param('products');

        if (empty($products)) {
            return [];
        }

        if (! ArrayHelper::accessible($products)) {
            throw new Exception(__('Invalid products data.', 'mwc-core'));
        }

        foreach ($products as $productData) {
            $product = ProductsRepository::get((int) ArrayHelper::get($productData, 'id'));

            if (! $product || ! $product->is_purchasable() || ! $product->is_in_stock()) {
                continue;
            }

            $this->products[] = ProductLineObject::getNewInstance()
                ->setProduct($product)
                ->setQuantity((float) ArrayHelper::get($productData, 'quantity', 1));
        }

        return $this->products;
    }

    /**
     * Checks whether we have products passed in by the request.
     *
     * @return bool
     */
    protected function hasProductsFromRequest() : bool
    {
        return ! empty($this->products);
    }

    /**
     * Validates products for the current payment request.
     *
     * @throws Exception|InvalidProductException
     */
    protected function validateProducts() : void
    {
        if (! ($gateway = CorePaymentGateways::getWalletGatewayInstance('godaddy-payments-apple-pay'))) {
            throw new GatewayNotFoundException(__('Cannot load Apple Pay gateway.', 'mwc-core'));
        }

        if ($this->hasProductsFromRequest()) {
            foreach ($this->products as $productLine) {
                if (! $gateway->isProductSupported($productLine->getProduct())) {
                    throw new InvalidProductException('Product not supported.');
                }
            }
        } elseif (! $gateway->isCartSupported()) {
            throw new InvalidProductException('Cart contents not supported.');
        }
    }
}
