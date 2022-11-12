<?php

namespace GoDaddy\WordPress\MWC\Core\Features\Marketplaces\Adapters\Webhooks;

use GoDaddy\WordPress\MWC\Common\DataSources\Contracts\DataSourceAdapterContract;
use GoDaddy\WordPress\MWC\Common\Helpers\ArrayHelper;
use GoDaddy\WordPress\MWC\Common\Traits\CanGetNewInstanceTrait;
use GoDaddy\WordPress\MWC\Core\Features\Marketplaces\Models\Listing;
use GoDaddy\WordPress\MWC\Core\Features\Marketplaces\Models\Webhooks\ListingWebhookPayload;
use GoDaddy\WordPress\MWC\Core\Features\Marketplaces\Repositories\ChannelRepository;

/**
 * Adapts data from a GDM listing webhook payload to a native ListingWebhookPayload object.
 */
class ListingWebhookPayloadAdapter implements DataSourceAdapterContract
{
    use CanGetNewInstanceTrait;

    /** @var array<string, mixed> Listing data from the webhook payload */
    protected $source;

    /**
     * ListingWebhookAdapter constructor.
     *
     * @param array<string, mixed> $decodedWebhookPayload Decoded data from the webhook payload.
     */
    public function __construct(array $decodedWebhookPayload)
    {
        $this->source = $decodedWebhookPayload;
    }

    /**
     * Converts the decoded payload into a ListingWebhookPayload objects.
     *
     * @return ListingWebhookPayload
     */
    public function convertFromSource() : ListingWebhookPayload
    {
        $productId = ArrayHelper::get($this->source, 'payload.details.gdwoo_id');

        return (new ListingWebhookPayload())
            ->setIsExpectedEvent($this->isListingEvent())
            ->setProductId(! empty($productId) ? (int) $productId : null)
            ->setListing($this->adaptListing());
    }

    /**
     * Determines if the webhook received is for a listing event.
     *
     * @return bool
     */
    protected function isListingEvent() : bool
    {
        return in_array(ArrayHelper::get($this->source, 'event_type'), ['webhook_listing_created', 'webhook_listing_updated'], true);
    }

    /**
     * Creates a Listing object from the webhook payload.
     *
     * @return Listing|null
     */
    protected function adaptListing() : ?Listing
    {
        // ensures we have at least one piece of required information
        if (! $listingId = ArrayHelper::get($this->source, 'payload.id')) {
            return null;
        }

        return (new Listing())
            ->setListingId((string) $listingId)
            ->setChannelType($this->adaptChannelType())
            ->setIsPublished('active' === strtolower(ArrayHelper::get($this->source, 'payload.status', '')));
    }

    /**
     * Adapts the channel type from the payload. It's given to us in display format (e.g. "Amazon") and we need to convert it to slug format (e.g. "amazon").
     *
     * @return string
     */
    protected function adaptChannelType() : string
    {
        $channelType = ArrayHelper::get($this->source, 'payload.details.channel_type_display_name', '');
        $channel = ChannelRepository::getByType($channelType);

        return $channel ? $channel->getType() : $channelType;
    }

    /**
     * {@inheritDoc}
     */
    public function convertToSource()
    {
        // Not implemented.
        return [];
    }
}
