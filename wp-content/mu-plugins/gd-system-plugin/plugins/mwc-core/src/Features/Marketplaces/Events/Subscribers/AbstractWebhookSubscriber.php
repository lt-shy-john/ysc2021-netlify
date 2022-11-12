<?php

namespace GoDaddy\WordPress\MWC\Core\Features\Marketplaces\Events\Subscribers;

use GoDaddy\WordPress\MWC\Common\Configuration\Configuration;
use GoDaddy\WordPress\MWC\Common\DataSources\Contracts\DataSourceAdapterContract;
use GoDaddy\WordPress\MWC\Common\Exceptions\SentryException;
use GoDaddy\WordPress\MWC\Common\Helpers\ArrayHelper;
use GoDaddy\WordPress\MWC\Common\Helpers\StringHelper;
use GoDaddy\WordPress\MWC\Common\Platforms\Exceptions\PlatformRepositoryException;
use GoDaddy\WordPress\MWC\Common\Platforms\PlatformRepositoryFactory;
use GoDaddy\WordPress\MWC\Core\Auth\Providers\Marketplaces\Webhook\Methods\SignatureHeader;
use GoDaddy\WordPress\MWC\Core\Auth\Providers\Marketplaces\Webhook\Models\Credentials;
use GoDaddy\WordPress\MWC\Core\Events\AbstractWebhookReceivedEvent;
use GoDaddy\WordPress\MWC\Core\Events\Subscribers\AbstractWebhookReceivedSubscriber;

/**
 * The base class for Marketplaces webhook subscribers.
 */
abstract class AbstractWebhookSubscriber extends AbstractWebhookReceivedSubscriber
{
    /**
     * Handles the event.
     *
     * @param AbstractWebhookReceivedEvent $event
     * @return bool
     * @throws PlatformRepositoryException
     */
    public function validate(AbstractWebhookReceivedEvent $event) : bool
    {
        $platformRepository = PlatformRepositoryFactory::getNewInstance()->getPlatformRepository();
        $channelId = $platformRepository->getChannelId();
        $ventureId = $platformRepository->getVentureId();

        if (empty($channelId) || empty($ventureId)) {
            return false;
        }

        if (! StringHelper::isJson($event->getPayload())) {
            return false;
        }

        $credentials = (new Credentials())->setChannelId($channelId)->setVentureId($ventureId);

        $signature = SignatureHeader::getNewInstance($credentials)->getSignature($event->getPayload());

        if (empty($header = ArrayHelper::get($event->getHeaders(), SignatureHeader::HEADER_NAME))) {
            return false;
        }

        return hash_equals($signature, $header);
    }

    /**
     * Gets the configured webhook payload adapter for the supplied webhook type.
     *
     * @param array<string, mixed> $payload
     * @param string $webhookType
     * @return DataSourceAdapterContract
     * @throws SentryException
     */
    protected function getWebhookPayloadAdapter(array $payload, string $webhookType) : DataSourceAdapterContract
    {
        $className = Configuration::get("marketplaces.webhooks.adapters.{$webhookType}");
        if (! $className) {
            throw new SentryException("No {$webhookType} webhook adapter configured.");
        }

        if (! class_exists($className)) {
            throw new SentryException("{$webhookType} adapter class {$className} does not exist.");
        }

        $classInterfaces = class_implements($className);

        if (! is_array($classInterfaces) || ! in_array(DataSourceAdapterContract::class, $classInterfaces, true)) {
            throw new SentryException("{$className} must implement DataSourceAdapterContract");
        }

        return $className::getNewInstance($payload);
    }
}
