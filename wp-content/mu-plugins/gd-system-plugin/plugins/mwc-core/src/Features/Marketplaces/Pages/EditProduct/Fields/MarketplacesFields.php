<?php

namespace GoDaddy\WordPress\MWC\Core\Features\Marketplaces\Pages\EditProduct\Fields;

use Exception;
use GoDaddy\WordPress\MWC\Common\Components\Contracts\ComponentContract;
use GoDaddy\WordPress\MWC\Common\Helpers\ArrayHelper;
use GoDaddy\WordPress\MWC\Common\Helpers\SanitizationHelper;
use GoDaddy\WordPress\MWC\Common\Helpers\StringHelper;
use GoDaddy\WordPress\MWC\Common\Register\Register;
use GoDaddy\WordPress\MWC\Common\Repositories\WooCommerce\ProductsRepository;
use GoDaddy\WordPress\MWC\Core\WooCommerce\Adapters\ProductAdapter;
use WC_Product;
use WP_Post;

/**
 * This class is responsible for outputting and handling Marketplaces fields displayed in the Edit Product page.
 */
class MarketplacesFields implements ComponentContract
{
    /**
     * Loads the component.
     *
     * @return void
     * @throws Exception
     */
    public function load()
    {
        Register::action()
            ->setGroup('woocommerce_product_options_general_product_data')
            ->setHandler([$this, 'renderMarketplacesFieldsSimpleProduct'])
            ->execute();

        Register::action()
            ->setGroup('woocommerce_product_after_variable_attributes')
            ->setHandler([$this, 'renderMarketplacesFieldsProductVariation'])
            ->setArgumentsCount(3)
            ->setPriority(20)
            ->execute();

        Register::action()
            ->setGroup('woocommerce_process_product_meta')
            ->setHandler([$this, 'saveSimpleProductFields'])
            ->execute();

        Register::action()
            ->setGroup('woocommerce_save_product_variation')
            ->setHandler([$this, 'saveProductVariationFields'])
            ->execute();
    }

    /**
     * Outputs the Marketplaces fields for simple products.
     *
     * @internal
     *
     * @return void
     */
    public function renderMarketplacesFieldsSimpleProduct() : void
    {
        echo '<div id="gd-marketplaces-simple-product-fields" class="options_group show_if_simple marketplaces-product-fields">';

        woocommerce_wp_text_input([
            'id'    => ProductAdapter::MARKETPLACES_BRAND_META_KEY,
            'label' => __('Product Brand', 'mwc-core'),
        ]);

        woocommerce_wp_text_input([
            'id'    => ProductAdapter::MARKETPLACES_CONDITION_META_KEY,
            'label' => __('Product Condition', 'mwc-core'),
        ]);

        echo '</div>';
    }

    /**
     * Outputs the Marketplaces fields for product variations.
     *
     * @param int|mixed $loop
     * @param array|mixed $variationData
     * @param WP_Post|mixed $variation
     * @return void
     */
    public function renderMarketplacesFieldsProductVariation($loop, $variationData, $variation) : void
    {
        $product = $variation instanceof WP_Post ? ProductsRepository::get($variation->ID) : null;
        $brand = $product ? $product->get_meta(ProductAdapter::MARKETPLACES_BRAND_META_KEY) : '';
        $condition = $product ? $product->get_meta(ProductAdapter::MARKETPLACES_CONDITION_META_KEY) : '';

        echo '<div id="gd-marketplaces-product-variation-fields-'.esc_attr($loop).'" class="marketplaces-product-fields">';

        woocommerce_wp_text_input([
            'id'            => sprintf('%s_%d', ProductAdapter::MARKETPLACES_BRAND_META_KEY, (int) $loop),
            'name'          => sprintf('%s[%d]', ProductAdapter::MARKETPLACES_BRAND_META_KEY, (int) $loop),
            'value'         => $brand,
            'label'         => __('Product Brand', 'mwc-core'),
            'wrapper_class' => 'form-row form-row-full',
        ]);

        woocommerce_wp_text_input([
            'id'            => sprintf('%s_%d', ProductAdapter::MARKETPLACES_CONDITION_META_KEY, (int) $loop),
            'name'          => sprintf('%s[%d]', ProductAdapter::MARKETPLACES_CONDITION_META_KEY, (int) $loop),
            'value'         => $condition,
            'label'         => __('Product Condition', 'mwc-core'),
            'wrapper_class' => 'form-row form-row-full',
        ]);

        echo '</div>';
    }

    /**
     * Saves the simple product fields.
     *
     * @internal
     *
     * @param int|mixed $productId
     * @return void
     */
    public function saveSimpleProductFields($productId) : void
    {
        if ('simple' !== ArrayHelper::get($_POST, 'product-type')) {
            return;
        }

        $product = ProductsRepository::get((int) $productId);

        if (! $product) {
            return;
        }

        $this->updateProductMetadata(
            $product,
            ArrayHelper::get($_POST, ProductAdapter::MARKETPLACES_BRAND_META_KEY),
            ArrayHelper::get($_POST, ProductAdapter::MARKETPLACES_CONDITION_META_KEY)
        );
    }

    /**
     * Saves the variable product fields.
     *
     * @internal
     *
     * @param int|mixed $variationId
     * @return void
     */
    public function saveProductVariationFields($variationId) : void
    {
        $product = ProductsRepository::get((int) $variationId);

        if (! $product) {
            return;
        }

        // find the index for the given variation ID and save the associated cost
        $variationIndex = array_search($variationId, ArrayHelper::get($_POST, 'variable_post_id', []));
        $variationBrand = $_POST[ProductAdapter::MARKETPLACES_BRAND_META_KEY][$variationIndex] ?? null;
        $variationCondition = $_POST[ProductAdapter::MARKETPLACES_CONDITION_META_KEY][$variationIndex] ?? null;

        $this->updateProductMetadata($product, $variationBrand, $variationCondition);
    }

    /**
     * Updates the product Marketplaces metadata.
     *
     * @param WC_Product $product
     * @param string|null $brand
     * @param string|null $condition
     * @return void
     */
    protected function updateProductMetadata(WC_Product $product, ?string $brand, ?string $condition) : void
    {
        if (! empty($brand)) {
            $product->update_meta_data(ProductAdapter::MARKETPLACES_BRAND_META_KEY, SanitizationHelper::input(StringHelper::unslash($brand)));
        } else {
            $product->delete_meta_data(ProductAdapter::MARKETPLACES_BRAND_META_KEY);
        }

        if (! empty($condition)) {
            $product->update_meta_data(ProductAdapter::MARKETPLACES_CONDITION_META_KEY, SanitizationHelper::input(StringHelper::unslash($condition)));
        } else {
            $product->delete_meta_data(ProductAdapter::MARKETPLACES_CONDITION_META_KEY);
        }

        $product->save_meta_data();
    }
}
