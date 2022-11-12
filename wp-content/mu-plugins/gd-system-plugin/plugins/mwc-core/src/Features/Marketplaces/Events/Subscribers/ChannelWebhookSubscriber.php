<?php

namespace GoDaddy\WordPress\MWC\Core\Features\Marketplaces\Events\Subscribers;

use Exception;
use GoDaddy\WordPress\MWC\Common\Components\Contracts\ComponentContract;
use GoDaddy\WordPress\MWC\Common\Exceptions\BaseException;
use GoDaddy\WordPress\MWC\Common\Exceptions\SentryException;
use GoDaddy\WordPress\MWC\Core\Events\AbstractWebhookReceivedEvent;
use GoDaddy\WordPress\MWC\Core\Features\Marketplaces\Cache\ConnectedChannelsCache;
use GoDaddy\WordPress\MWC\Core\Features\Marketplaces\Repositories\ChannelRepository;

/**
 * The Marketplaces channel webhook subscriber.
 */
class ChannelWebhookSubscriber extends AbstractWebhookSubscriber implements ComponentContract
{
    /**
     * {@inheritDoc}
     * @throws SentryException|BaseException|Exception
     */
    public function handlePayload(AbstractWebhookReceivedEvent $event) : void
    {
        $webhookPayload = $this->getWebhookPayloadAdapter($event->getPayloadDecoded(), 'channel')->convertFromSource();
        if (! $webhookPayload->getIsExpectedEvent()) {
            return;
        }

        // clear cached channels
        ConnectedChannelsCache::getInstance()->clear();

        // trigger a new API request
        ChannelRepository::getConnected();
    }

    /**
     * {@inheritDoc}
     */
    public function load() : void
    {
    }
}
