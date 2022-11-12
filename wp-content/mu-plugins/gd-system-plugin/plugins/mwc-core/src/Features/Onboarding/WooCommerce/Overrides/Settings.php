<?php

namespace GoDaddy\WordPress\MWC\Core\Features\Onboarding\WooCommerce\Overrides;

use Exception;
use GoDaddy\WordPress\MWC\Common\Helpers\ArrayHelper;
use GoDaddy\WordPress\MWC\Common\Interceptors\AbstractInterceptor;
use GoDaddy\WordPress\MWC\Common\Register\Register;

/**
 * Overrides WooCommerce settings.
 */
class Settings extends AbstractInterceptor
{
    /**
     * Filters WooCommerce settings.
     *
     * @return void
     * @throws Exception
     */
    public function addHooks() : void
    {
        Register::filter()
            ->setGroup('woocommerce_com_integration_settings')
            ->setHandler([$this, 'filterWooCommerceDotComSettings'])
            ->execute();

        Register::filter()
            ->setGroup('default_option_woocommerce_com_integration_settings')
            ->setHandler([$this, 'disableMarketplaceSuggestionsByDefault'])
            ->execute();
    }

    /**
     * Filters settings in WooCommerce Settings > Advanced > WooCommerce.com.
     *
     * The default value of `woocommerce_show_marketplace_suggestions` will be `'no'`.
     *
     * @internal
     *
     * @param array<string, mixed>|mixed $settings
     * @return array<string, mixed>|mixed
     */
    public function filterWooCommerceDotComSettings($settings)
    {
        if (! ArrayHelper::accessible($settings)) {
            return $settings;
        }

        foreach ($settings as $index => $settingGroup) {
            if ('woocommerce_show_marketplace_suggestions' === ArrayHelper::get($settingGroup, 'id')) {
                $settingGroup['default'] = 'no';
                $settings[$index] = $settingGroup;
            }
        }

        return $settings;
    }

    /**
     * Filters the default value of the Marketplaces Suggestions option when not set in database.
     *
     * @internal
     *
     * @return string
     */
    public function disableMarketplaceSuggestionsByDefault() : string
    {
        return 'no';
    }
}
