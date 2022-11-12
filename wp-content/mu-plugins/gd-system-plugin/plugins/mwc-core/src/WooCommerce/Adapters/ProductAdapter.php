<?php

namespace GoDaddy\WordPress\MWC\Core\WooCommerce\Adapters;

use Exception;
use GoDaddy\WordPress\MWC\Common\DataSources\WooCommerce\Adapters\Product\ProductAdapter as CommonProductAdapter;
use GoDaddy\WordPress\MWC\Common\DataSources\WordPress\Adapters\TaxonomyTermAdapter;
use GoDaddy\WordPress\MWC\Common\Models\Dimensions;
use GoDaddy\WordPress\MWC\Common\Models\Products\Product as CommonProduct;
use GoDaddy\WordPress\MWC\Common\Models\Term;
use GoDaddy\WordPress\MWC\Common\Repositories\WooCommerce\ProductsRepository;
use GoDaddy\WordPress\MWC\Common\Repositories\WordPress\TermsRepository;
use GoDaddy\WordPress\MWC\Common\Traits\CanGetNewInstanceTrait;
use GoDaddy\WordPress\MWC\Core\Features\Marketplaces\Models\Listing;
use GoDaddy\WordPress\MWC\Core\WooCommerce\Models\Products\Product;
use WC_Product;

/**
 * Core product adapter.
 *
 * Converts between a native core product object and a WooCommerce product object.
 *
 * @property WC_Product $source
 * @method static static getNewInstance(WC_Product $product)
 */
class ProductAdapter extends CommonProductAdapter
{
    use CanGetNewInstanceTrait;

    /** @var string the Marketplaces listings meta key */
    const MARKETPLACES_LISTINGS_META_KEY = '_marketplaces_listings';

    /** @var string the Marketplaces brand meta key */
    const MARKETPLACES_BRAND_META_KEY = '_marketplaces_brand';

    /** @var string the Marketplaces condition meta key */
    const MARKETPLACES_CONDITION_META_KEY = '_marketplaces_condition';

    /** @var class-string<Product> the product class name */
    protected $productClass = Product::class;

    /**
     * Adapts the product from source.
     *
     * @return Product
     * @throws Exception
     */
    public function convertFromSource() : CommonProduct
    {
        /** @var Product $product */
        $product = parent::convertFromSource();

        $product->setIsVirtual((bool) $this->source->is_virtual());
        $product->setIsDownloadable((bool) $this->source->is_downloadable());

        $isStockManaged = (bool) $this->source->get_manage_stock();

        $product->setStockManagementEnabled($isStockManaged);

        if ($isStockManaged && ($currentStock = $this->convertNumberToFloat($this->source->get_stock_quantity()))) {
            $product->setCurrentStock($currentStock);
        }

        if ($isStockManaged && ($backordersAllowed = $this->source->get_backorders())) {
            $product->setBackordersAllowed($backordersAllowed);
        }

        $product->setDimensions($this->convertDimensionsFromSource());

        $product->setVariants($this->convertVariantsFromSource());

        $this->convertMarketplacesDataFromSource($product);

        $product->setUrl($this->source->get_permalink());

        $this->convertCategoriesFromSource($product);

        $this->convertImageIdsFromSource($product);

        return $product;
    }

    /**
     * Converts a core native product object into a WooCommerce product object.
     *
     * @param Product|null $product native core product object to convert
     * @param bool $getNewInstance whether to get a fresh instance of a WC_Product
     * @return WC_Product WooCommerce product object
     * @throws Exception
     */
    public function convertToSource($product = null, bool $getNewInstance = true) : WC_Product
    {
        $this->source = parent::convertToSource($product, $getNewInstance);

        if ($product) {
            $this->convertCategoriesToSource($this->source, $product);

            if ($backordersAllowed = $product->getBackordersAllowed()) {
                $this->source->set_backorders($backordersAllowed);
            }
        }

        $this->convertMarketplacesDataToSource($this->source, $product);

        return $this->source;
    }

    /**
     * Converts source category IDs to native product category objects.
     *
     * @param Product $product
     * @return void
     */
    protected function convertCategoriesFromSource(Product $product) : void
    {
        $categories = [];

        foreach ($this->source->get_category_ids() as $categoryId) {
            // NOTE: this is not an N+1 problem, as the terms were already loaded into memory when the WC product was fetched
            if ($term = TermsRepository::getTerm($categoryId, 'product_cat')) {
                $categories[] = TaxonomyTermAdapter::getNewInstance($term)->convertFromSource();
            }
        }

        $product->setCategories($categories);
    }

    /**
     * Converts WooCommerce image IDs to native product image properties.
     *
     * @NOTE The reason why we adapt only image IDs and not whole images is to reduce unnecessary queries to fetch all the data for each image.
     *
     * @param Product $product
     * @return void
     */
    protected function convertImageIdsFromSource(Product $product) : void
    {
        if ($featuredImageId = $this->source->get_image_id()) {
            $product->setMainImageId((int) $featuredImageId);
        }

        if ($galleryImageIds = $this->source->get_gallery_image_ids()) {
            $product->setImageIds(array_map('intval', $galleryImageIds));
        }
    }

    /**
     * Converts product dimensions from source.
     *
     * @return Dimensions
     */
    protected function convertDimensionsFromSource() : Dimensions
    {
        $dimensions = new Dimensions();

        foreach (['width', 'height', 'length'] as $dimension) {
            $getDimension = 'get_'.$dimension;
            $setDimension = 'set'.ucfirst($dimension);

            if ($value = $this->convertNumberToFloat($this->source->{$getDimension}())) {
                $dimensions->{$setDimension}($value);
            }
        }

        if ($unit = (string) get_option('woocommerce_dimension_unit', '')) {
            $dimensions->setUnitOfMeasurement($unit);
        }

        return $dimensions;
    }

    /**
     * Converts source product variations into native product variants, if any.
     *
     * @return Product[]
     * @throws Exception
     */
    protected function convertVariantsFromSource() : array
    {
        $variants = [];

        if ($variations = $this->source->get_children()) {
            foreach ($variations as $variationId) {
                if ($source = ProductsRepository::get($variationId)) {
                    $productVariationAdapter = static::getNewInstance($source);
                    $variant = $productVariationAdapter->convertFromSource();
                    $variant->setAttributes($productVariationAdapter->convertVariantAttributesFromSource());
                    $variants[] = $variant;
                }
            }
        }

        return $variants;
    }

    /**
     * Convert variant attributes to a plain array. Should only be called when $this->source is WC_Product_Variant,
     * otherwise it may throw.
     *
     * @return array<int, array<string, string>>
     */
    protected function convertVariantAttributesFromSource() : array
    {
        $variationAttributes = $this->source->get_attributes();

        return array_map(function (string $variationAttributeKey) {
            /*
             * {llessa 2022-09-28} Only converts necessary attribute data available in events for Marketplaces product sync.
             * If we make changes to this implementation, it should have at least the properties below to keep backward compatibility.
             */
            return [
                'key'   => $variationAttributeKey,
                'label' => $this->source->get_attribute($variationAttributeKey),
            ];
        }, array_keys($variationAttributes));
    }

    /**
     * Ensures that a number will be adapted to a float.
     *
     * @param string|int|float|mixed $number
     * @return float|null
     */
    protected function convertNumberToFloat($number) : ?float
    {
        return is_numeric($number) ? (float) $number : null;
    }

    /**
     * Converts Marketplaces listings' information from a WC Product object to a core product instance.
     *
     * @param Product $product
     */
    protected function convertMarketplacesDataFromSource(Product $product)
    {
        if (! empty($marketplacesListingsMeta = $this->source->get_meta(static::MARKETPLACES_LISTINGS_META_KEY))) {
            $listings = [];

            foreach ($marketplacesListingsMeta as $marketplacesListingMeta) {
                $listing = new Listing();
                $listing->setProperties(array_filter($marketplacesListingMeta));

                $listings[] = $listing;
            }

            $product->setMarketplacesListings($listings);
        }

        $product->setMarketplacesBrand($this->source->get_meta(static::MARKETPLACES_BRAND_META_KEY));
        $product->setMarketplacesCondition($this->source->get_meta(static::MARKETPLACES_CONDITION_META_KEY));
    }

    /**
     * Converts an array of native product categories into an array of WooCommerce product category IDs.
     *
     * @param WC_Product $wcProduct
     * @param Product $product
     * @return void
     */
    protected function convertCategoriesToSource(WC_Product $wcProduct, Product $product)
    {
        $categoryIds = array_map(function (Term $term) {
            return $term->getId();
        }, $product->getCategories());

        $wcProduct->set_category_ids($categoryIds);
    }

    /**
     * Converts Marketplaces listings' information from a core product object to the WC Product metadata.
     *
     * @param WC_Product $wcProduct
     * @param null|Product $product
     */
    protected function convertMarketplacesDataToSource(WC_Product $wcProduct, $product = null)
    {
        if ($product) {
            $listings = $product->getMarketplacesListings();
            $marketplacesListingsMeta = [];

            foreach ($listings as $listing) {
                $marketplacesListingsMeta[] = $listing->toArray();
            }

            $wcProduct->update_meta_data(static::MARKETPLACES_LISTINGS_META_KEY, $marketplacesListingsMeta);

            $wcProduct->update_meta_data(static::MARKETPLACES_BRAND_META_KEY, $product->getMarketplacesBrand());
            $wcProduct->update_meta_data(static::MARKETPLACES_CONDITION_META_KEY, $product->getMarketplacesCondition());
        }
    }
}
