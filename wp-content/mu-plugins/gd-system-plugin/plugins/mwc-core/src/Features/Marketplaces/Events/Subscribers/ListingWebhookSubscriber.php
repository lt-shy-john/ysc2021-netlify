<?php

namespace GoDaddy\WordPress\MWC\Core\Features\Marketplaces\Events\Subscribers;

use Exception;
use GoDaddy\WordPress\MWC\Common\Components\Contracts\ComponentContract;
use GoDaddy\WordPress\MWC\Common\Exceptions\BaseException;
use GoDaddy\WordPress\MWC\Common\Exceptions\SentryException;
use GoDaddy\WordPress\MWC\Core\Events\AbstractWebhookReceivedEvent;
use GoDaddy\WordPress\MWC\Core\Features\Marketplaces\Services\ProductListingService;

/**
 * The Marketplaces listing webhook subscriber.
 */
class ListingWebhookSubscriber extends AbstractWebhookSubscriber implements ComponentContract
{
    /**
     * {@inheritDoc}
     * @throws SentryException|BaseException|Exception
     */
    public function handlePayload(AbstractWebhookReceivedEvent $event)
    {
        $webhookPayload = $this->getWebhookPayloadAdapter($event->getPayloadDecoded(), 'listing')->convertFromSource();
        if (! $webhookPayload->getIsExpectedEvent()) {
            return;
        }

        ProductListingService::getNewInstance($webhookPayload)->saveListing();
    }

    /**
     * {@inheritDoc}
     */
    public function load()
    {
    }
}
