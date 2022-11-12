<?php

namespace GoDaddy\WordPress\MWC\Core\Features\Marketplaces\Events\Subscribers;

use GoDaddy\WordPress\MWC\Common\Components\Contracts\ComponentContract;
use GoDaddy\WordPress\MWC\Common\Exceptions\AdapterException;
use GoDaddy\WordPress\MWC\Common\Exceptions\SentryException;
use GoDaddy\WordPress\MWC\Core\Events\AbstractWebhookReceivedEvent;
use GoDaddy\WordPress\MWC\Core\Features\Marketplaces\Services\OrderUpsertService;

/**
 * The Marketplaces order webhook subscriber.
 */
class OrderWebhookSubscriber extends AbstractWebhookSubscriber implements ComponentContract
{
    /**
     * Maybe creates an order in WooCommerce from a new order placed through GDM.
     *
     * @param AbstractWebhookReceivedEvent $event
     * @return void
     * @throws AdapterException|SentryException
     */
    public function handlePayload(AbstractWebhookReceivedEvent $event)
    {
        $webhookPayload = $this->getWebhookPayloadAdapter($event->getPayloadDecoded(), 'order')->convertFromSource();
        if (! $webhookPayload->getIsExpectedEvent()) {
            return;
        }

        OrderUpsertService::getNewInstance($webhookPayload)->saveOrder();
    }

    /**
     * {@inheritDoc}
     */
    public function load()
    {
        // no-op
    }
}
