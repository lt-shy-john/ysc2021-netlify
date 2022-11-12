<?php

namespace GoDaddy\WordPress\MWC\Core\WooCommerce\Payments\Frontend\Views\ExternalCheckout;

use Exception;
use GoDaddy\WordPress\MWC\Common\Configuration\Configuration;
use GoDaddy\WordPress\MWC\Common\Helpers\ArrayHelper;
use GoDaddy\WordPress\MWC\Common\Repositories\WordPressRepository;
use GoDaddy\WordPress\MWC\Core\Payments\Poynt;
use GoDaddy\WordPress\MWC\Core\WooCommerce\Payments\GoDaddyPayments\AbstractWalletGateway;
use GoDaddy\WordPress\MWC\Core\WooCommerce\Payments\GoDaddyPayments\ApplePayGateway;

/**
 * View for displaying an Apple Pay button.
 */
class ApplePayButtonView extends AbstractButtonView
{
    /**
     * Determines whether the button is available for the given context.
     *
     * @param string $context one of the configured Apple Pay enabled pages
     * @return bool
     * @throws Exception
     */
    public function isAvailable(string $context) : bool
    {
        return true === Configuration::get('payments.applePay.enabled')
            && ArrayHelper::contains(ArrayHelper::wrap(Configuration::get('payments.applePay.enabledPages', [])), $context)
            && Poynt::isConnected()
            && ApplePayGateway::isActive()
            && ApplePayGateway::isDomainRegisteredWithApple();
    }

    /**
     * Outputs the Apple Pay button element.
     *
     * @link https://developer.apple.com/documentation/apple_pay_on_the_web
     */
    public function render()
    {
        ?>
        <div id="mwc-payments-apple-pay-hosted" data-button-options="<?php echo esc_attr(json_encode($this->getButtonOptions())); ?>"></div>
        <?php
    }

    /**
     * Gets options for the Apple Pay button.
     *
     * @link https://confluence.godaddy.com/display/CPPE/Wallet+Integration+with+Poynt+Collect#WalletIntegrationwithPoyntCollect-Mount(domElement,document,mountOptions)
     *
     * @return array
     */
    protected function getButtonOptions() : array
    {
        /*
         * Filters the Apple Pay button options.
         *
         * @param array $options
         */
        return apply_filters('mwc_payments_apple_pay_button_options', [
            'color'  => $this->getButtonStyle(),
            'type'   => $this->getButtonType(),
            'locale' => $this->getButtonLanguage(),
            'height' => $this->getButtonHeight().'px',
            'width'  => '100%',
            'margin' => '0px', // passing integer 0 won't work, unit has to be provided
        ]);
    }

    /**
     * Gets the style (color) for the Apple Pay button.
     *
     * @link https://developer.apple.com/documentation/apple_pay_on_the_web/displaying_apple_pay_buttons_using_css
     *
     * @return string
     */
    protected function getButtonStyle() : string
    {
        return str_replace('_', '-', strtolower(Configuration::get('payments.applePay.buttonStyle', 'black')));
    }

    /**
     * Gets the type for the Apple Pay button.
     *
     * @link https://developer.apple.com/documentation/apple_pay_on_the_web/displaying_apple_pay_buttons_using_css
     *
     * @return string
     */
    protected function getButtonType() : string
    {
        return str_replace('_', '-', strtolower(Configuration::get('payments.applePay.buttonType', 'buy')));
    }

    /**
     * Gets the locale for the Apple Pay button.
     *
     * @link https://developer.apple.com/documentation/apple_pay_on_the_web/displaying_apple_pay_buttons_using_css/localizing_apple_pay_buttons_using_css
     * @NOTE if the locale is invalid or unsupported by Apple, it should automatically have the browser default to English
     *
     * @return string
     */
    protected function getButtonLanguage() : string
    {
        return substr(WordPressRepository::getLocale(), 0, 2);
    }

    /**
     * Gets the button height.
     *
     * @return int
     */
    protected function getButtonHeight() : int
    {
        // Apple Pay wants a minimum of 30 and a maximum of 64 pixels for the button height; the default value is as per setting default
        return max(AbstractWalletGateway::BUTTON_HEIGHT_MIN, min(AbstractWalletGateway::BUTTON_HEIGHT_MAX, (int) Configuration::get('payments.applePay.buttonHeight', AbstractWalletGateway::BUTTON_HEIGHT_DEFAULT)));
    }
}
