<?php

namespace GoDaddy\WordPress\MWC\Core\Features\CartRecoveryEmails\DataProviders;

use Exception;
use GoDaddy\WordPress\MWC\Common\DataSources\WooCommerce\Adapters\CurrencyAmountAdapter;
use GoDaddy\WordPress\MWC\Common\Models\Cart;
use GoDaddy\WordPress\MWC\Common\Models\CurrencyAmount;
use GoDaddy\WordPress\MWC\Common\Models\Orders\LineItem;
use GoDaddy\WordPress\MWC\Common\Models\User;
use GoDaddy\WordPress\MWC\Common\Repositories\WordPressRepository;
use GoDaddy\WordPress\MWC\Core\Features\CartRecoveryEmails\CartRecoveryEmails;
use GoDaddy\WordPress\MWC\Core\Features\CartRecoveryEmails\Contracts\CheckoutEmailNotificationContract;
use GoDaddy\WordPress\MWC\Core\Features\CartRecoveryEmails\DataStores\WooCommerce\CheckoutDataStore;
use GoDaddy\WordPress\MWC\Core\Features\CartRecoveryEmails\Models\CartRecoveryEmailNotification;
use GoDaddy\WordPress\MWC\Core\Features\CartRecoveryEmails\Models\Checkout;
use GoDaddy\WordPress\MWC\Core\Features\EmailNotifications\Contracts\DataProviderContract;
use WC_Product;

/**
 * A provider for email notifications to handle data for recoverable carts.
 */
class CheckoutDataProvider implements DataProviderContract
{
    /** @var CheckoutEmailNotificationContract */
    protected $emailNotification;

    /**
     * Constructor.
     */
    public function __construct(CheckoutEmailNotificationContract $emailNotification)
    {
        $this->emailNotification = $emailNotification;
    }

    /**
     * Gets checkout data.
     *
     * @return array
     * @throws Exception
     */
    public function getData() : array
    {
        $checkout = $this->emailNotification->getCheckout();

        if (! $checkout) {
            return [];
        }

        $customer = $checkout->getCustomer();

        return [
            'customer_first_name' => $customer ? $customer->getFirstName() : _x('there', 'subject of greeting when name is not known', 'mwc-core'),
            'customer_last_name'  => $customer ? $customer->getLastName() : '',
            'internal'            => [
                'cart_details' => $this->getCartDetails($checkout->getCart()),
            ],
        ];
    }

    /**
     * Gets checkout placeholders.
     *
     * @return string[]
     */
    public function getPlaceholders() : array
    {
        return [
            'customer_first_name',
            'customer_last_name',
        ];
    }

    /**
     * Gets the cart details data.
     *
     * @param Cart $cart
     * @return array
     * @throws Exception
     */
    protected function getCartDetails(Cart $cart) : array
    {
        return [
            'cart_recovery_link' => $this->buildCartRecoveryLink(),
            'currency'           => $cart->getTotalAmount()->getCurrencyCode(),
            'total'              => (new CurrencyAmountAdapter(0, 'USD'))->convertToSource($cart->getTotalAmount()),
            'total_formatted'    => $cart->getTotalAmount()->toFormattedString(),
            'line_items'         => $this->getLineItemsData($cart->getLineItems()),
        ];
    }

    /**
     * Builds the cart recovery link.
     *
     * @return string
     * @throws Exception
     */
    protected function buildCartRecoveryLink() : string
    {
        if (! $checkoutId = $this->getCheckoutId()) {
            return '';
        }

        $checkout = CheckoutDataStore::getNewInstance()->read($checkoutId);

        $params = http_build_query([
            'cartHash'   => $checkout->getWcCartHash(),
            'checkoutId' => $checkout->getId(),
        ]);

        return CartRecoveryEmails::getWooCommerceCartRecoveryEndpointUrl().'?'.$params;
    }

    /**
     * Gets the checkout ID, if available.
     *
     * @return int|null
     */
    protected function getCheckoutId() : ?int
    {
        return (
            null !== $this->emailNotification &&
            null !== $this->emailNotification->getCheckout() &&
            null !== $this->emailNotification->getCheckout()->getId()
        ) ? $this->emailNotification->getCheckout()->getId() : null;
    }

    /**
     * Gets the line items data.
     *
     * @param LineItem[] $lineItems
     * @return array
     */
    protected function getLineItemsData(array $lineItems) : array
    {
        $amountAdapter = new CurrencyAmountAdapter(0, 'USD');
        $data = [];

        foreach ($lineItems as $lineItem) {
            $product = $lineItem->getProduct();

            $data[] = [
                'name'            => $lineItem->getName(),
                'variation_id'    => $lineItem->getVariationId(),
                'quantity'        => $lineItem->getQuantity(),
                'price'           => $amountAdapter->convertToSource($lineItem->getSubTotalAmount()),
                'price_formatted' => $lineItem->getSubTotalAmount()->toFormattedString(),
                'tax'             => $amountAdapter->convertToSource($lineItem->getTaxAmount()),
                'tax_formatted'   => $lineItem->getTaxAmount()->toFormattedString(),
                'total'           => $amountAdapter->convertToSource($lineItem->getTotalAmount()),
                'total_formatted' => $lineItem->getTotalAmount()->toFormattedString(),
                'product'         => [
                    'id'        => $product ? $product->get_id() : null,
                    'permalink' => $product ? $product->get_permalink() : null,
                    'name'      => $product ? $product->get_name() : null,
                    'image'     => [
                        'id'  => $product ? $product->get_image_id() : null,
                        'src' => WordPressRepository::getAttachmentUrl((int) ($product ? $product->get_image_id() : null)),
                        /* @TODO implement repository methods to retrieve image from WordPress gallery and corresponding title/alt values {unfulvio 2022-03-10} */
                        'name' => $product ? $product->get_name() : null,
                        'alt'  => $product ? $product->get_name() : null,
                    ],
                ],
            ];
        }

        return $data;
    }

    /**
     * Gets a fake customer to generate preview data.
     *
     * @return User
     */
    protected function getPreviewCustomer() : User
    {
        return (new User())
            ->setFirstName('John')
            ->setLastName('Doe');
    }

    /**
     * Gets a fake cart to generate preview data.
     *
     * @return Cart
     */
    protected function getPreviewCart() : Cart
    {
        $realProducts = wc_get_products([
            'orderby' => 'date',
            'order'   => 'DESC',
            'limit'   => 2,
        ]);

        $lineItems = [];
        $cartTotal = 0;

        /** @var WC_Product $product */
        foreach ($realProducts as $product) {
            $productPrice = $product->get_price('edit');

            $lineItems[] = (new LineItem())
                ->setProduct($product)
                ->setQuantity(2)
                ->setSubTotalAmount(
                    (new CurrencyAmount())
                        ->setAmount($productPrice * 100)
                        ->setCurrencyCode(get_woocommerce_currency()))
                // hardcoded a 7% tax amount just so we have something to show if there are no taxes
                ->setTaxAmount(
                    (new CurrencyAmount())
                        ->setAmount($productPrice * 7)
                        ->setCurrencyCode(get_woocommerce_currency()))
                        ->setTotalAmount(
                            (new CurrencyAmount())
                                ->setAmount($productPrice * 14)
                                ->setCurrencyCode(get_woocommerce_currency()));

            $cartTotal = $productPrice * 214;
        }

        return (new Cart())
            ->setTotalAmount(
                (new CurrencyAmount())
                    ->setAmount($cartTotal)
                    ->setCurrencyCode(get_woocommerce_currency()))
            ->setLineItems($lineItems);
    }

    /**
     * Gets fake preview data.
     *
     * @return array
     * @throws Exception
     */
    public function getPreviewData() : array
    {
        if (empty($this->emailNotification)) {
            $this->emailNotification = new CartRecoveryEmailNotification();
        }

        $this->emailNotification->setCheckout((new Checkout())
            ->setCart($this->getPreviewCart())
            ->setCustomer($this->getPreviewCustomer()));

        return $this->getData();
    }
}
