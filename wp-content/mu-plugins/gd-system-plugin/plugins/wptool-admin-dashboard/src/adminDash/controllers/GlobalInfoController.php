<?php

namespace Wptool\adminDash\controllers;

use Wptool\adminDash\constants\ToggleStatus;
use Wptool\adminDash\services\AutoUpdateService;
use Wptool\adminDash\services\CacheService;
use Wptool\adminDash\services\CaptchaService;
use Wptool\adminDash\services\container\ServiceContainer;
use Wptool\adminDash\services\ChangeDomainService;
use Wptool\adminDash\utils\BundlesPath;
use Wptool\adminDash\utils\SiteUtils;
use Wptool\adminDash\constants\XmlRpcConstants;

class GlobalInfoController extends BaseController {

	/** @var $auto_updates_service AutoUpdateService */
	private $auto_updates_service;

	/** @var $cache_service CacheService */
	private $cache_service;


	/** @var $change_domain_service ChangeDomainService */
	private $change_domain_service;

	/** @var $captcha_service CaptchaService */
	private $captcha_service;

	/**
	 * @param ServiceContainer $container
	 */
	public function __construct( $container ) {

		parent::__construct( $container );

		$this->auto_updates_service  = $this->container->get( 'auto_updates_service' );
		$this->cache_service         = $this->container->get( 'cache_service' );
		$this->change_domain_service = $this->container->get( 'change_domain_service' );
		$this->captcha_service       = $this->container->get( 'captcha_service' );

	}

	/**
	 * Register routes for AutoUpdates controller.
	 *
	 * @return void
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'global-info',
			array(
				array(
					'methods'             => 'GET',
					'callback'            => array( $this, 'global_info_handler' ),
					'args'                => array(),
					'permission_callback' => array( $this, 'is_authenticated' ),
				),
			)
		);
	}

	/**
	 * Global info handler.
	 *
	 * @return \WP_REST_Response
	 */
	public function global_info_handler() {

		$cache_flush_link              = $this->cache_service->get_flush_cache_url();
		$pl_id                         = defined( 'GD_RESELLER' ) ? GD_RESELLER : null;
		$site_uid                      = defined( 'GD_ACCOUNT_UID' ) ? GD_ACCOUNT_UID : null;
		$auto_updates_status           = $this->auto_updates_service->is_mwp_auto_updates_enabled();
		$change_domain_url             = $this->change_domain_service->get_domain_change_url();
		$wpsec_captcha_enabled         = $this->captcha_service->is_wpsec_captcha_enabled();
		$wpsec_comment_captcha_enabled = $this->captcha_service->is_wpsec_captcha_comment_enabled();
		$wpsec_captcha_login_enabled   = $this->captcha_service->is_wpsec_captcha_login_enabled();
		$site_title                    = SiteUtils::get_site_title();
		$user_email                    = wp_get_current_user()->user_email;
		$xmlrpc_enbled                 = get_option( XmlRpcConstants::OPTION_KEY, ToggleStatus::ENABLED ) === ToggleStatus::ENABLED;

		return new \WP_REST_Response(
			array(
				'data' => array(
					'cache_flush_link'                => $cache_flush_link,
					'pl_id'                           => $pl_id,
					'site_uid'                        => $site_uid,
					'mwp_auto_updates_status_enabled' => $auto_updates_status,
					'change_domain_url'               => $change_domain_url,
					'wpsec_captcha_enabled'           => $wpsec_captcha_enabled,
					'env'                             => BundlesPath::resolve_env(),
					'wpsec_comment_captcha_enabled'   => $wpsec_comment_captcha_enabled,
					'wpsec_login_captcha_enabled'     => $wpsec_captcha_login_enabled,
					'site_title'                      => $site_title,
					'user_email'                      => $user_email,
					'xmlrpc_enabled'                  => $xmlrpc_enbled,
				),
			),
			200
		);
	}
}
