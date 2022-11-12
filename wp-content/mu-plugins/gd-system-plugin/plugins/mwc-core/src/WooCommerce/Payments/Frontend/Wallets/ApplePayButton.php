<?php

namespace GoDaddy\WordPress\MWC\Core\WooCommerce\Payments\Frontend\Wallets;

use Exception;
use GoDaddy\WordPress\MWC\Common\Configuration\Configuration;
use GoDaddy\WordPress\MWC\Common\Helpers\ArrayHelper;
use GoDaddy\WordPress\MWC\Core\Payments\Poynt;
use GoDaddy\WordPress\MWC\Core\WooCommerce\Payments\GoDaddyPayments\ApplePayGateway;

class ApplePayButton extends AbstractWalletButton
{
    /**
     * {@inheritDoc}
     */
    public function getWalletId() : string
    {
        return 'apple-pay';
    }

    /**
     * {@inheritDoc}
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
}
