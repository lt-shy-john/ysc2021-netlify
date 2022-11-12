<?php

return [
    /*
     *--------------------------------------------------------------------------
     * GoDaddy Marketplaces API
     *--------------------------------------------------------------------------
     */
    'api' => [
        'url' => defined('MWC_GDM_API_URL') ? MWC_GDM_API_URL : 'https://marketplaces.godaddy.com/api',
    ],

    /*
     *--------------------------------------------------------------------------
     * Marketplaces orders quota for plans
     *--------------------------------------------------------------------------
     */
    'plan_limits' => [
        'flex'    => 1000,
        'expand'  => 2500,
        'premier' => 5000,
    ],

    /*
     *--------------------------------------------------------------------------
     * Available Sales Channels
     *--------------------------------------------------------------------------
     */
    'channels' => [
        'types' => defined('MWC_GDM_CHANNEL_TYPES')
            ? (array) MWC_GDM_CHANNEL_TYPES
            : [
                'amazon' => 'Amazon',
                'ebay'   => 'eBay',
                // 'facebook' => 'Facebook', // @TODO uncomment when available {unfulvio 2022-08-17}
                // 'walmart' => 'Walmart', // @TODO uncomment when available {agibson 2022-10-04}
                'etsy' => 'Etsy',
                // 'google'   => 'Google', // @TODO uncomment when available and Google Analytics integration is complete {unfulvio 2022-08-17}
            ],
    ],

    /*
     *--------------------------------------------------------------------------
     * Webhooks
     *--------------------------------------------------------------------------
     */
    'webhooks' => [
        'adapters' => [
            'listing' => \GoDaddy\WordPress\MWC\Core\Features\Marketplaces\Adapters\Webhooks\ListingWebhookPayloadAdapter::class,
            'channel' => \GoDaddy\WordPress\MWC\Core\Features\Marketplaces\Adapters\Webhooks\ChannelWebhookPayloadAdapter::class,
            'order'   => \GoDaddy\WordPress\MWC\Core\Features\Marketplaces\Adapters\Webhooks\OrderWebhookPayloadAdapter::class,
        ],
    ],

    /*
     *--------------------------------------------------------------------------
     * GoDaddy Marketplaces website
     *--------------------------------------------------------------------------
     */
    'website' => [
        'url' => 'https://marketplaces.godaddy.com',
    ],
];
