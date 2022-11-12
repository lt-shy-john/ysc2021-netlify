<?php

namespace GoDaddy\WordPress\MWC\Core\Features\Marketplaces;

use Exception;
use GoDaddy\WordPress\MWC\Common\Components\Contracts\ComponentContract;
use GoDaddy\WordPress\MWC\Common\Components\Contracts\DelayedInstantiationComponentContract;
use GoDaddy\WordPress\MWC\Common\Components\Exceptions\ComponentClassesNotDefinedException;
use GoDaddy\WordPress\MWC\Common\Components\Exceptions\ComponentLoadFailedException;
use GoDaddy\WordPress\MWC\Common\Components\Traits\HasComponentsTrait;
use GoDaddy\WordPress\MWC\Common\Configuration\Configuration;
use GoDaddy\WordPress\MWC\Common\Features\AbstractFeature;
use GoDaddy\WordPress\MWC\Common\Helpers\ArrayHelper;
use GoDaddy\WordPress\MWC\Common\Http\Url;
use GoDaddy\WordPress\MWC\Common\Platforms\Exceptions\PlatformRepositoryException;
use GoDaddy\WordPress\MWC\Common\Platforms\PlatformRepositoryFactory;
use GoDaddy\WordPress\MWC\Common\Register\Register;
use GoDaddy\WordPress\MWC\Common\Repositories\WooCommerceRepository;
use GoDaddy\WordPress\MWC\Common\Repositories\WordPress\SiteRepository;
use GoDaddy\WordPress\MWC\Core\Admin\Notices\Notice;
use GoDaddy\WordPress\MWC\Core\Admin\Notices\Notices;
use GoDaddy\WordPress\MWC\Core\Features\Marketplaces\Events\Subscribers\ChannelWebhookSubscriber;
use GoDaddy\WordPress\MWC\Core\Features\Marketplaces\Events\Subscribers\ListingWebhookSubscriber;
use GoDaddy\WordPress\MWC\Core\Features\Marketplaces\Events\Subscribers\OrderWebhookSubscriber;
use GoDaddy\WordPress\MWC\Core\Features\Marketplaces\Handlers\MerchantProvisioningHandler;
use GoDaddy\WordPress\MWC\Core\Features\Marketplaces\Handlers\SellbritePluginHandler;
use GoDaddy\WordPress\MWC\Core\Features\Marketplaces\Interceptors\CreateDraftListingAjaxInterceptor;
use GoDaddy\WordPress\MWC\Core\Features\Marketplaces\Interceptors\DuplicateProductInterceptor;
use GoDaddy\WordPress\MWC\Core\Features\Marketplaces\Interceptors\EditProductPageInterceptor;
use GoDaddy\WordPress\MWC\Core\Features\Marketplaces\Interceptors\EmailInterceptor;
use GoDaddy\WordPress\MWC\Core\Features\Marketplaces\Interceptors\MerchantProvisionedOptionInterceptor;
use GoDaddy\WordPress\MWC\Core\Features\Marketplaces\Interceptors\OrderInterceptor;
use GoDaddy\WordPress\MWC\Core\Features\Marketplaces\Interceptors\ProductBulkSyncActionInterceptor;
use GoDaddy\WordPress\MWC\Core\Features\Marketplaces\Interceptors\ProductsPageInterceptor;
use GoDaddy\WordPress\MWC\Core\Features\Marketplaces\Pages\EditOrder\Fields\MarketplacesFields as OrderMarketplacesFields;
use GoDaddy\WordPress\MWC\Core\Features\Marketplaces\Pages\EditProduct\Fields\MarketplacesFields as ProductMarketplacesFields;
use GoDaddy\WordPress\MWC\Core\Features\Marketplaces\Pages\EditProduct\Metaboxes\MarketplacesMetabox;
use GoDaddy\WordPress\MWC\Core\Features\Marketplaces\Pages\MarketplacesPage;
use GoDaddy\WordPress\MWC\Core\Features\Marketplaces\Pages\Orders\Columns\SalesChannelColumn;
use GoDaddy\WordPress\MWC\Core\Features\Marketplaces\Repositories\OrderRepository;

/**
 * The GoDaddy Marketplaces feature class.
 */
class Marketplaces extends AbstractFeature implements DelayedInstantiationComponentContract
{
    use HasComponentsTrait;

    /** @var class-string<ComponentContract>[] alphabetically ordered list of components to load */
    protected $componentClasses = [
        ChannelWebhookSubscriber::class,
        CreateDraftListingAjaxInterceptor::class,
        DuplicateProductInterceptor::class,
        EditProductPageInterceptor::class,
        EmailInterceptor::class,
        ListingWebhookSubscriber::class,
        MarketplacesMetabox::class,
        MarketplacesPage::class,
        MerchantProvisioningHandler::class,
        MerchantProvisionedOptionInterceptor::class,
        OrderInterceptor::class,
        OrderMarketplacesFields::class,
        OrderWebhookSubscriber::class,
        ProductBulkSyncActionInterceptor::class,
        ProductMarketplacesFields::class,
        ProductsPageInterceptor::class,
        SalesChannelColumn::class,
        SellbritePluginHandler::class,
    ];

    /**
     * Determines whether the feature should load.
     *
     * @return bool
     * @throws PlatformRepositoryException
     */
    public static function shouldLoad() : bool
    {
        if (! parent::shouldLoad()) {
            return false;
        }

        if (static::areSettingsSupported()) {
            static::maybeEnqueueStagingSiteAdminNotice();
            static::enqueueOrderLimitApproachingAdminNotice();
            static::enqueueOrderLimitReachedAdminNotice();

            // Note: this is a temporary solution to prevent notices from displaying on sites where marketplaces
            // is not enabled. {@see https://jira.godaddy.com/browse/MWC-8615}
            static::maybeEnqueueUnitsAdminNotice();
            static::maybeEnqueueInventoryAdminNotice();
            static::maybeEnqueueCurrencyOrCountryAdminNotice();

            return true;
        }

        return false;
    }

    /**
     * Checks if the site settings match the Marketplaces required settings.
     *
     * @return bool
     */
    protected static function areSettingsSupported() : bool
    {
        return static::isSupportedCurrency()
            && static::isSupportedCountry()
            && static::isSupportedWeightUnit()
            && static::isSupportedDimensionUnit()
            && static::isSupportedStockManagement();
    }

    /**
     * Checks if the site currency matches a Marketplaces supported currency.
     *
     * @return bool
     */
    protected static function isSupportedCurrency() : bool
    {
        return 'usd' === strtolower(WooCommerceRepository::getCurrency());
    }

    /**
     * Checks if the site country matches a supported country.
     *
     * @return bool
     */
    protected static function isSupportedCountry() : bool
    {
        return 'us' === strtolower(WooCommerceRepository::getBaseCountry());
    }

    /**
     * Checks if the site weight unit matches a supported weight unit.
     *
     * @return bool
     */
    protected static function isSupportedWeightUnit() : bool
    {
        return ArrayHelper::contains(['lbs'], get_option('woocommerce_weight_unit'));
    }

    /**
     * Checks if the site dimension unit matches a supported dimension unit.
     *
     * @return bool
     */
    protected static function isSupportedDimensionUnit() : bool
    {
        return ArrayHelper::contains(['in'], get_option('woocommerce_dimension_unit'));
    }

    /**
     * Checks if the site stock management settings are supported.
     *
     * @return bool
     */
    protected static function isSupportedStockManagement() : bool
    {
        return 'yes' === get_option('woocommerce_manage_stock');
    }

    /**
     * {@inheritDoc}
     */
    public static function getName() : string
    {
        return 'marketplaces';
    }

    /**
     * Loads the feature.
     *
     * @return void
     * @throws ComponentClassesNotDefinedException|ComponentLoadFailedException
     */
    public function load()
    {
        $this->loadComponents();

        $this->enqueueNewFeatureAdminNotice();
    }

    /**
     * Schedules the component instantiation.
     *
     * @param $callback
     * @return void
     * @throws Exception
     */
    public static function scheduleInstantiation($callback) : void
    {
        Register::action()
            ->setGroup('wp_loaded')
            ->setHandler($callback)
            ->setPriority(PHP_INT_MAX)
            ->execute();
    }

    /**
     * Gets a URL for the Marketplaces website.
     *
     * @param string $path
     * @return string
     * @throws Exception
     */
    public static function getMarketplacesUrl(string $path) : string
    {
        return Url::fromString((string) Configuration::get('marketplaces.website.url', ''))
            ->setPath($path)
            ->toString();
    }

    /**
     * Displays a dismissible admin notice about the new feature.
     *
     * @return void
     */
    protected function enqueueNewFeatureAdminNotice() : void
    {
        $productsPageUrl = SiteRepository::getAdminUrl('edit.php?post_type=product');

        $content = sprintf(
            /* translators: Placeholders: %1$s - opening HTML link tag <a> to products page, %2$s - closing HTML link tag </a> */
            __('Create your first product listing by adding a new product or editing an existing one in WooCommerce. You can list a product on each of the marketplaces you\'ve added. Once listed, the product info and inventory will sync automatically. Visit %1$sProducts%2$s to get started.', 'mwc-core'),
            '<a href="'.esc_url($productsPageUrl).'">',
            '</a>'
        );

        $notice = (new Notice())
            ->setId('mwc_marketplaces_new_feature')
            ->setType(Notice::TYPE_INFO)
            ->setRestrictedUserCapabilities(['manage_woocommerce'])
            ->setDismissible(true)
            ->setTitle(esc_html__('Welcome to GoDaddy Marketplaces!', 'mwc-core'))
            ->setContent($content);

        Notices::enqueueAdminNotice($notice);
    }

    /**
     * Displays a dismissible admin notice notifying that the currency or country settings are not supported.
     *
     * @return void
     */
    protected static function maybeEnqueueCurrencyOrCountryAdminNotice() : void
    {
        if (static::isSupportedCurrency() && static::isSupportedCountry()) {
            return;
        }

        $notice = (new Notice())
            ->setId('mwc_marketplaces_unsupported_currency_or_country_settings')
            ->setType(Notice::TYPE_WARNING)
            ->setRestrictedUserCapabilities(['manage_woocommerce'])
            ->setDismissible(true)
            ->setContent(__('Your store address must be in the US and currency US dollars in order to sync products, inventory, and orders with Marketplaces & Social sales channels.', 'mwc-core'));

        Notices::enqueueAdminNotice($notice);
    }

    /**
     * Displays a dismissible admin notice notifying that the weight or dimension unit settings are not supported.
     *
     * @return void
     */
    protected static function maybeEnqueueUnitsAdminNotice() : void
    {
        if (static::isSupportedWeightUnit() && static::isSupportedDimensionUnit()) {
            return;
        }

        $notice = (new Notice())
            ->setId('mwc_marketplaces_unsupported_unit_settings')
            ->setType(Notice::TYPE_WARNING)
            ->setRestrictedUserCapabilities(['manage_woocommerce'])
            ->setDismissible(true)
            ->setContent(__('Your store weight and dimensions units must be in "lbs" and "in" in order to sync products, inventory, and orders with Marketplaces & Social sales channels.', 'mwc-core'));

        Notices::enqueueAdminNotice($notice);
    }

    /**
     * Displays a dismissible admin notice notifying that the inventory settings are not supported.
     *
     * @return void
     */
    protected static function maybeEnqueueInventoryAdminNotice() : void
    {
        if (static::isSupportedStockManagement()) {
            return;
        }

        $notice = (new Notice())
            ->setId('mwc_marketplaces_unsupported_inventory_settings')
            ->setType(Notice::TYPE_WARNING)
            ->setRestrictedUserCapabilities(['manage_woocommerce'])
            ->setDismissible(true)
            ->setContent(__('Stock management must be enabled in order to sync products, inventory, and orders with Marketplaces & Social sales channels.', 'mwc-core'));

        Notices::enqueueAdminNotice($notice);
    }

    /**
     * Displays a non-dismissible admin notice in the Marketplaces page if the site is a staging site.
     *
     * @return void
     * @throws PlatformRepositoryException
     */
    protected static function maybeEnqueueStagingSiteAdminNotice() : void
    {
        if (! PlatformRepositoryFactory::getNewInstance()->getPlatformRepository()->isStagingSite()) {
            return;
        }

        Notices::enqueueAdminNotice((new Notice())
            ->setId('mwc_marketplaces_syncing_disabled_on_staging_site')
            ->setType(Notice::TYPE_WARNING)
            ->setRestrictedUserCapabilities(['manage_woocommerce'])
            ->setDismissible(false)
            ->setContent(__('Product, inventory, and order syncing is disabled in Staging to prevent conflicts with your Production site.', 'mwc-core'))
            ->setRenderCondition(function () {
                return MarketplacesPage::isMarketplacesPage();
            })
        );
    }

    /**
     * Displays a non-dismissible admin notice if the order limit is approaching.
     *
     * @return void
     */
    protected static function enqueueOrderLimitApproachingAdminNotice() : void
    {
        $content = sprintf(
            /* translators: Placeholders: %1$s - opening HTML link tag <a> to products page, %2$s - closing HTML link tag </a> */
            __('You\'ve used 90&#37; of your included monthly Marketplaces and Social orders. Upgrade soon to process more orders. %1$sUpgrade Plan%2$s', 'mwc-core'),
            '<a href="https://mwcstores.godaddy.com/my-subscription" target="_blank">',
            '</a>'
        );

        Notices::enqueueAdminNotice((new Notice())
            ->setId('mwc_marketplaces_order_limit_approaching')
            ->setType(Notice::TYPE_WARNING)
            ->setRestrictedUserCapabilities(['manage_woocommerce'])
            ->setDismissible(false)
            ->setContent($content)
            ->setRenderCondition(function () {
                return OrderRepository::isNearMonthlyMarketplacesOrdersLimit();
            })
        );
    }

    /**
     * Displays a non-dismissible admin notice if the order limit was reached.
     *
     * @return void
     */
    protected static function enqueueOrderLimitReachedAdminNotice() : void
    {
        $content = sprintf(
            /* translators: Placeholders: %1$s - opening HTML link tag <a> to products page, %2$s - closing HTML link tag </a> */
            __('You\'ve used all your included monthly Marketplaces and Social orders. Upgrade now to process more orders. %1$sUpgrade Plan%2$s', 'mwc-core'),
            '<a href="https://mwcstores.godaddy.com/my-subscription" target="_blank">',
            '</a>'
        );

        Notices::enqueueAdminNotice((new Notice())
            ->setId('mwc_marketplaces_order_limit_reached')
            ->setType(Notice::TYPE_WARNING)
            ->setRestrictedUserCapabilities(['manage_woocommerce'])
            ->setDismissible(false)
            ->setContent($content)
            ->setRenderCondition(function () {
                return OrderRepository::hasReachedMonthlyMarketplacesOrdersLimit();
            })
        );
    }
}
