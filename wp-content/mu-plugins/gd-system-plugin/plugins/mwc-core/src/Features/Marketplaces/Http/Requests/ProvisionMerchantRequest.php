<?php

namespace GoDaddy\WordPress\MWC\Core\Features\Marketplaces\Http\Requests;

use GoDaddy\WordPress\MWC\Common\Platforms\Exceptions\PlatformRepositoryException;
use GoDaddy\WordPress\MWC\Common\Platforms\PlatformRepositoryFactory;
use GoDaddy\WordPress\MWC\Common\Repositories\WooCommerceRepository;
use GoDaddy\WordPress\MWC\Common\Repositories\WordPress\SiteRepository;
use GoDaddy\WordPress\MWC\Common\Traits\CanGetNewInstanceTrait;

/**
 * API request to provision a GDM merchant.
 */
class ProvisionMerchantRequest extends GoDaddyMarketplacesRequest
{
    use CanGetNewInstanceTrait;

    /** @var string request route */
    protected $route = 'merchants';

    public function __construct()
    {
        parent::__construct();

        $this->setMethod('POST');
    }

    /**
     * Builds the request body.
     *
     * @return array<string, mixed>
     * @throws PlatformRepositoryException
     */
    protected function buildBodyData() : array
    {
        $platformRepository = PlatformRepositoryFactory::getNewInstance()->getPlatformRepository();

        return [
            'partner'       => static::PARTNER,
            'website_id'    => $platformRepository->getChannelId(),
            'venture_id'    => $platformRepository->getVentureId(),
            'status'        => 'ACTIVE',
            'currency_code' => strtoupper(WooCommerceRepository::getCurrency()),
            // Currently only one value is supported for `locale` and `measurement_system`.
            'locale'             => 'en-US',
            'measurement_system' => 'imperial',
            // below values are dummy data, and expected by GDM until they make the parameters optional
            'api_key'      => 'Unknown',
            'first_name'   => 'Unknown',
            'last_name'    => 'Unknown',
            'email'        => "{$platformRepository->getPlatformSiteId()}@example.com",
            'telephone'    => '000-000-0000',
            'company_name' => SiteRepository::getTitle(),
            'base_url'     => SiteRepository::getHomeUrl(),
        ];
    }
}
