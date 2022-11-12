<?php

namespace GoDaddy\WordPress\MWC\Core\WooCommerce\Payments\Frontend\Wallets;

use Exception;
use GoDaddy\WordPress\MWC\Common\Configuration\Configuration;
use GoDaddy\WordPress\MWC\Common\Helpers\ArrayHelper;
use GoDaddy\WordPress\MWC\Core\Payments\Poynt;
use GoDaddy\WordPress\MWC\Core\WooCommerce\Payments\GoDaddyPayments\GooglePayGateway;

class GooglePayButton extends AbstractWalletButton
{
    /**
     * {@inheritDoc}
     */
    public function getWalletId() : string
    {
        return 'google-pay';
    }

    /**
     * {@inheritDoc}
     * @throws Exception
     */
    public function isAvailable(string $context) : bool
    {
        return true === Configuration::get('payments.googlePay.enabled')
            && ArrayHelper::contains(ArrayHelper::wrap(Configuration::get('payments.googlePay.enabledPages', [])), $context)
            && Poynt::isConnected()
            && GooglePayGateway::isActive();
    }
}
