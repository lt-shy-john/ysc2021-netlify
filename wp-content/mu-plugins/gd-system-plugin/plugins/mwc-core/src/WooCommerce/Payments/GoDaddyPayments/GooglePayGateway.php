<?php

namespace GoDaddy\WordPress\MWC\Core\WooCommerce\Payments\GoDaddyPayments;

use Exception;
use GoDaddy\WordPress\MWC\Common\Configuration\Configuration;
use GoDaddy\WordPress\MWC\Common\Traits\CanGetNewInstanceTrait;
use GoDaddy\WordPress\MWC\Core\Payments\Poynt\Onboarding;
use GoDaddy\WordPress\MWC\Core\WooCommerce\Payments\Frontend\ExternalCheckout;
use GoDaddy\WordPress\MWC\Core\WooCommerce\Payments\GoDaddyPaymentsGateway;

class GooglePayGateway extends AbstractWalletGateway
{
    use CanGetNewInstanceTrait;

    /** @var string "Subscribe with G Pay" button type */
    const BUTTON_TYPE_SUBSCRIBE = 'SUBSCRIBE';

    /** @var string default button style */
    const BUTTON_STYLE_DEFAULT = 'DEFAULT';

    /** @var int default button width */
    const BUTTON_WIDTH_DEFAULT = 240;

    /** @var int max button width */
    const BUTTON_WIDTH_MAX = 800;

    /** @var int min button width */
    const BUTTON_WIDTH_MIN = 160;

    /**
     * Google Pay gateway constructor.
     */
    public function __construct()
    {
        $this->walletId = 'google-pay';
        $this->method_title = $this->title = 'GoDaddy Payments - Google Pay';
        $this->method_description = sprintf(
            __('Securely accept card payments and make checkout faster for customers with Google Pay™ on supported devices. Pay the industry\'s lowest fees at 2.3&#37; + 30&#162; per online transaction. %1$sGoDaddy Payments Terms apply%2$s.', 'mwc-core'),
            '<a href="https://www.godaddy.com/legal/agreements/commerce-services-agreement" target="_blank">',
            ' <span class="dashicons dashicons-external"></span></a>'
        );

        parent::__construct();
    }

    /**
     * Determines whether the gateway is active.
     *
     * @return bool
     * @throws Exception
     */
    public static function isActive() : bool
    {
        $isGDPConnected = Onboarding::STATUS_CONNECTED === Onboarding::getStatus() && GoDaddyPaymentsGateway::isActive();

        return true === $isGDPConnected && Configuration::get('features.google_pay');
    }

    /**
     * Initializes the gateway settings form fields.
     *
     * @see WC_Payment_Gateway::init_settings()
     * @see WC_Payment_Gateway::get_form_fields()
     * @see WC_Payment_Gateway::generate_settings_html()
     */
    public function init_form_fields() : void
    {
        $this->form_fields = [
            'godaddy_payments_settings' => [
                'type' => 'parent_gateway_settings',
            ],
            'google_pay_settings' => [
                'type'  => 'title',
                'title' => __('Google Pay Settings', 'mwc-core'),
            ],
            'enabled' => [
                'title'       => __('Enable', 'mwc-core'),
                'label'       => __('Enable to add the payment method to your checkout.', 'mwc-core'),
                'description' => sprintf(
                    __('Google Pay shows to %1$ssupported browsers%2$s.', 'mwc-core'),
                    '<a href="https://developers.google.com/pay/api/web/guides/setup" target="_blank">',
                    ' <span class="dashicons dashicons-external"></span></a>'
                ),
                'type'    => 'checkbox',
                'default' => 'no',
            ],
            'enabled_pages' => [
                'title'   => __('Pages to enable Google Pay on', 'mwc-core'),
                'type'    => 'multiselect',
                'class'   => 'wc-enhanced-select',
                'default' => [
                    ExternalCheckout::BUTTON_PAGE_CART,
                    ExternalCheckout::BUTTON_PAGE_CHECKOUT,
                ],
                'options' => [
                    ExternalCheckout::BUTTON_PAGE_CART           => __('Cart', 'mwc-core'),
                    ExternalCheckout::BUTTON_PAGE_CHECKOUT       => __('Checkout', 'mwc-core'),
                    ExternalCheckout::BUTTON_PAGE_SINGLE_PRODUCT => __('Single Product', 'mwc-core'),
                ],
            ],
            /* @link https://developers.google.com/pay/api/web/guides/brand-guidelines#style */
            'button_type' => [
                'title'       => __('Button label', 'mwc-core'),
                'description' => '<a href="https://developers.google.com/pay/api/web/guides/brand-guidelines#style" target="_blank">'.__('Check button labels here', 'mwc-core').' <span class="dashicons dashicons-external"></span></a>',
                'type'        => 'select',
                'class'       => 'wc-enhanced-select',
                'default'     => static::BUTTON_TYPE_BUY,
                'options'     => [
                    static::BUTTON_TYPE_BOOK      => __('Book with', 'mwc-core'),
                    static::BUTTON_TYPE_BUY       => __('Buy with', 'mwc-core'),
                    static::BUTTON_TYPE_CHECKOUT  => __('Check out with', 'mwc-core'),
                    static::BUTTON_TYPE_DONATE    => __('Donate with', 'mwc-core'),
                    static::BUTTON_TYPE_ORDER     => __('Order with', 'mwc-core'),
                    static::BUTTON_TYPE_PAY       => __('Pay with', 'mwc-core'),
                    static::BUTTON_TYPE_PLAIN     => __('Plain (logo only)', 'mwc-core'),
                    static::BUTTON_TYPE_SUBSCRIBE => __('Reload with', 'mwc-core'),
                ],
            ],
            /* @link https://developers.google.com/pay/api/web/guides/brand-guidelines#style */
            'button_style' => [
                'title'       => __('Button style', 'mwc-core'),
                'description' => '<a href="https://developers.google.com/pay/api/web/guides/brand-guidelines#style" target="_blank">'.__('Check button style here', 'mwc-core').' <span class="dashicons dashicons-external"></span></a>',
                'type'        => 'select',
                'class'       => 'wc-enhanced-select',
                'default'     => 'BLACK',
                'options'     => [
                    static::BUTTON_STYLE_DEFAULT => __('Default', 'mwc-core'),
                    static::BUTTON_STYLE_BLACK   => __('Black', 'mwc-core'),
                    static::BUTTON_STYLE_WHITE   => __('White', 'mwc-core'),
                ],
            ],
            /* @link https://developers.google.com/pay/api/web/guides/brand-guidelines#style */
            'button_height' => [
                'type'              => 'number',
                'title'             => __('Button height', 'mwc-core'),
                'description'       => __('Google requests the button size match your cart/checkout button and be 40 to 100 pixels tall.', 'mwc-core').'<br><a href="https://developers.google.com/pay/api/web/guides/brand-guidelines#style" target="_blank">'.__('Check button size here', 'mwc-core').' <span class="dashicons dashicons-external"></span></a>',
                'css'               => 'max-width: 105px',
                'default'           => static::BUTTON_HEIGHT_DEFAULT,
                'custom_attributes' => [
                    'step' => 1,
                    'min'  => static::BUTTON_HEIGHT_MIN,
                    'max'  => static::BUTTON_HEIGHT_MAX,
                ],
            ],
            'button_width' => [
                'type'              => 'number',
                'title'             => __('Button width', 'mwc-core'),
                'description'       => __('Google requests the button size match your cart/checkout button and be 160 to 800 pixels wide.', 'mwc-core').'<br><a href="https://developers.google.com/pay/api/web/guides/brand-guidelines#style" target="_blank">'.__('Check button size here', 'mwc-core').' <span class="dashicons dashicons-external"></span></a>',
                'css'               => 'max-width: 105px',
                'default'           => static::BUTTON_WIDTH_DEFAULT,
                'custom_attributes' => [
                    'step'    => 1,
                    'default' => static::BUTTON_WIDTH_DEFAULT,
                    'min'     => static::BUTTON_WIDTH_MIN,
                    'max'     => static::BUTTON_WIDTH_MAX,
                ],
            ],
        ];
    }
}
