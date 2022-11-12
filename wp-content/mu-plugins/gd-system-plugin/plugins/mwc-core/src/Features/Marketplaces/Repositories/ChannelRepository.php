<?php

namespace GoDaddy\WordPress\MWC\Core\Features\Marketplaces\Repositories;

use Exception;
use GoDaddy\WordPress\MWC\Common\Configuration\Configuration;
use GoDaddy\WordPress\MWC\Common\Exceptions\SentryException;
use GoDaddy\WordPress\MWC\Common\Helpers\ArrayHelper;
use GoDaddy\WordPress\MWC\Common\Helpers\SanitizationHelper;
use GoDaddy\WordPress\MWC\Common\Repositories\WordPressRepository;
use GoDaddy\WordPress\MWC\Core\Features\Marketplaces\Adapters\ChannelAdapter;
use GoDaddy\WordPress\MWC\Core\Features\Marketplaces\Cache\ConnectedChannelsCache;
use GoDaddy\WordPress\MWC\Core\Features\Marketplaces\Exceptions\GoDaddyMarketplacesRequestException;
use GoDaddy\WordPress\MWC\Core\Features\Marketplaces\Http\Requests\GetChannelsRequest;
use GoDaddy\WordPress\MWC\Core\Features\Marketplaces\Models\Channel;

/**
 * Repository class for Marketplaces sales channels.
 */
class ChannelRepository
{
    /**
     * Gets all available Marketplaces channel types (connected or not).
     *
     * @return array<string, string>
     */
    public static function getTypes() : array
    {
        return ArrayHelper::wrap(Configuration::get('marketplaces.channels.types', []));
    }

    /**
     * Gets the display name of a channel by given type, optionally with an icon.
     *
     * @param string $type
     * @param bool $withIcon
     * @return string
     */
    public static function getLabel(string $type, bool $withIcon = false) : string
    {
        $displayName = ArrayHelper::get(static::getTypes(), $type, '');

        return $displayName && $withIcon ? esc_html($displayName).' '.static::getIcon($type) : esc_html($displayName);
    }

    /**
     * Gets a list of the connected Marketplaces channels.
     *
     * Whether the request to get connected channels is successful or fails, this will return cached results.
     * @see ChannelRepository::requestConnectedChannels()
     * @see ConnectedChannelsCache
     *
     * @return Channel[]
     */
    public static function getConnected() : array
    {
        $cache = ConnectedChannelsCache::getInstance();

        $channelsData = $cache->get() ?: static::requestConnectedChannels();

        $channels = array_map(function ($channelData) {
            return ChannelAdapter::getNewInstance($channelData)->convertFromSource();
        }, ArrayHelper::get($channelsData, 'connected', []));

        return ArrayHelper::where($channels, function ($channel) {
            return static::isSupportedChannelType($channel->getType());
        });
    }

    /**
     * Issues a request to fetch connected channels and caches it.
     *
     * @return array<string, mixed> response payload or empty array
     */
    protected static function requestConnectedChannels() : array
    {
        $cache = ConnectedChannelsCache::getInstance();
        $channels = [];

        try {
            $response = GetChannelsRequest::getNewInstance()->send();
        } catch (Exception $exception) {
            new GoDaddyMarketplacesRequestException('Failed to retrieve connected Marketplaces channels.', $exception);
        }

        if (isset($response)) {
            if (! $response->isSuccess()) {
                new GoDaddyMarketplacesRequestException(sprintf('Failed to retrieve connected Marketplaces channels: %s', $response->getErrorMessage() ?: $response->getStatus()));
            } else {
                $channels = $response->getBody();
            }
        }

        // we use an associative array to avoid empty array which could result in an ambiguous falsey value when getting from cache
        $payload = ['connected' => $channels];

        $cache->set($payload);

        return $payload;
    }

    /**
     * Gets the first connected channel of the given channel type.
     *
     * @param string $channelType Slug or display name
     * @return Channel|null
     */
    public static function getByType(string $channelType) : ?Channel
    {
        $channelTypeSlug = strtolower($channelType);

        $channels = ArrayHelper::where(static::getConnected(), function ($connectedChannel) use ($channelTypeSlug) {
            return $channelTypeSlug === strtolower($connectedChannel->getType());
        });

        return $channels ? current($channels) : null;
    }

    /**
     * Gets the first connected channel with the given UUID.
     *
     * @param string $channelUuid
     * @return Channel|null
     */
    public static function getByUuid(string $channelUuid) : ?Channel
    {
        $channels = ArrayHelper::where(static::getConnected(), function (Channel $connectedChannel) use ($channelUuid) {
            return $channelUuid === $connectedChannel->getUuid();
        });

        return $channels ? current($channels) : null;
    }

    /**
     * Gets the icon URl for the channel type.
     *
     * @param string $channelType
     * @return string
     */
    public static function getIconUrl(string $channelType) : string
    {
        if (empty($channelType)) {
            return '';
        }

        try {
            return WordPressRepository::getAssetsUrl('images/marketplaces/'.SanitizationHelper::slug(strtolower($channelType)).'-icon.svg');
        } catch (Exception $exception) {
            new SentryException("Channel {$channelType} icon not loaded", $exception);

            return '';
        }
    }

    /**
     * Returns the `<img>` tag for the channel type icon.
     *
     * @param string $channelType
     * @param string $height
     * @param string $width
     * @return string
     */
    public static function getIcon(string $channelType, string $height = '16px', string $width = '16px') : string
    {
        $iconUrl = static::getIconUrl($channelType);

        return ! empty($iconUrl)
            ? '<img src="'.esc_url($iconUrl).'" alt="'.esc_attr($channelType).'" style="height: '.esc_attr($height).'; width: '.esc_attr($width).';" />'
            : '';
    }

    /**
     * Returns true if channel type is supported.
     *
     * @param string $channelType
     * @return bool
     */
    public static function isSupportedChannelType(string $channelType) : bool
    {
        return ArrayHelper::has(static::getTypes(), $channelType);
    }
}
