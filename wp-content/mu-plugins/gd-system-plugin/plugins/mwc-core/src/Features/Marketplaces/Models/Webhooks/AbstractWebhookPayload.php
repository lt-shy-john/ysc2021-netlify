<?php

namespace GoDaddy\WordPress\MWC\Core\Features\Marketplaces\Models\Webhooks;

use GoDaddy\WordPress\MWC\Common\Models\AbstractModel;

/**
 * Abstract class representing a webhook payload.
 */
abstract class AbstractWebhookPayload extends AbstractModel
{
    /** @var bool if this is the webhook event we are expecting it to be */
    protected $isExpectedEvent = false;

    /**
     * Gets whether this is the expected webhook event.
     *
     * @return bool
     */
    public function getIsExpectedEvent() : bool
    {
        return $this->isExpectedEvent;
    }

    /**
     * Sets whether this is the expected webhook event.
     *
     * @param bool $value
     * @return $this
     */
    public function setIsExpectedEvent(bool $value) : AbstractWebhookPayload
    {
        $this->isExpectedEvent = $value;

        return $this;
    }
}
