<?php

namespace GoDaddy\WordPress\MWC\Core\Features\Marketplaces\Models;

use GoDaddy\WordPress\MWC\Common\Models\AbstractModel;

/**
 * Model representing a Marketplaces channel.
 */
class Channel extends AbstractModel
{
    /** @var int Channel ID */
    protected $id;

    /** @var string Channel UUID */
    protected $uuid;

    /** @var string Channel type slug (e.g. "amazon") */
    protected $type;

    /** @var string Name of the channel (e.g. "Store 25") */
    protected $name;

    /**
     * Gets the channel ID.
     *
     * @return int
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * Gets the channel UUID.
     *
     * @return string
     */
    public function getUuid() : string
    {
        return $this->uuid;
    }

    /**
     * Gets the channel type.
     *
     * @return string
     */
    public function getType() : string
    {
        return $this->type;
    }

    /**
     * Gets the name of the channel.
     *
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * Sets the channel ID.
     *
     * @param int $value
     * @return $this
     */
    public function setId(int $value) : Channel
    {
        $this->id = $value;

        return $this;
    }

    /**
     * Sets the channel UUID.
     *
     * @param string $value
     * @return static
     */
    public function setUuid(string $value) : Channel
    {
        $this->uuid = $value;

        return $this;
    }

    /**
     * Sets the channel type.
     *
     * @param string $value
     * @return static
     */
    public function setType(string $value) : Channel
    {
        $this->type = $value;

        return $this;
    }

    /**
     * Sets the channel name.
     *
     * @param string $value
     * @return static
     */
    public function setName(string $value) : Channel
    {
        $this->name = $value;

        return $this;
    }
}
