<?php
/**
 * The LiveSiteControlProvider class.
 *
 * @package GoDaddy
 */

namespace GoDaddy\WordPress\Plugins\Launch\LiveSiteControl;

use GoDaddy\WordPress\Plugins\Launch\Helper;
use GoDaddy\WordPress\Plugins\Launch\PublishGuide\GuideItems;
use GoDaddy\WordPress\Plugins\Launch\ServiceProvider;
use function GoDaddy\WordPress\Plugins\Launch\gdl;

/**
 * The LiveSiteControlProvider class.
 */
class LiveSiteControlProvider extends ServiceProvider {
	const ASSET_SLUG = 'live-site-control';

	const APP_CONTAINER_CLASS      = 'gdl-live-site-control';
	const PORTAL_CONTAINER_CLASS   = 'gdl-live-site-control-portal';
	const LIVE_CONTROL_PREVIEW_ARG = 'gdl-live-control-preview';
	const LIVE_CONTROL_EVENT_NAME  = 'gdl-live-control-go-live';

	const SETTINGS = array(
		'publishState'          => 'gdl_site_published',
		'liveSiteDismiss'       => 'gdl_live_site_dismiss',
		'blogPublic'            => 'blog_public',
	);

	const SETTINGS_OVERRIDE = array(
		'publishState'    => array(
			'default'           => false,
			'true_as_timestamp' => true,
			'sanitize_callback' => 'milestone_published_nux_api', // This callback should only be used for `publishState`.
		),
		'liveSiteDismiss' => array(
			'default'           => false,
			'true_as_timestamp' => true,
			'sanitize_callback' => 'milestone_publish_later_nux_api', // This callback should only be used for `liveSiteDismiss`.
		),
	);

	/**
	 * Sanitize callback that reports data to the NUX API.
	 *
	 * This should only be sent ONCE when the `publishState` option is
	 * saved. Subsequent milestone requests from the same domain will
	 * be ignored by the API so this is our only chance to send the
	 * right data.
	 *
	 * @param  mixed $value This is the sanitized data from the API.
	 */
	public function milestone_published_nux_api( $value ) {
		if ( ! $value || ! Helper::is_rum_enabled() ) {
			return $value;
		}

		$domain = defined( 'GD_TEMP_DOMAIN' ) ? GD_TEMP_DOMAIN : Helper::domain();
		$url    = Helper::wpnux_api_base() . '/milestones/site-publish?domain=' . $domain;

		// Remove option filters so we get the raw option value.
		remove_filter( 'option_' . self::SETTINGS['liveSiteDismiss'], array( Helper::class, 'get_option_convert_timestamp_to_true' ) );
		remove_filter( 'option_' . self::SETTINGS['publishState'], array( Helper::class, 'get_option_convert_timestamp_to_true' ) );

		$body = Helper::get_default_nux_api_request_body();

		// This is the timestamp of when the user clicked "launch later".
		// If the user clicked "launch now" this option will not exist.
		$launch_later_at = get_option( self::SETTINGS['liveSiteDismiss'] );

		$body['is_launch_now']   = empty( $launch_later_at );
		$body['launch_later_at'] = $launch_later_at ? (int) $launch_later_at : null;

		if ( $launch_later_at ) {
			list( $enabled, $complete, $complete_timestamps, $incomplete ) = $this->get_guide_items_state();

			$body['guide_items']                    = $enabled;
			$body['guide_items_count']              = count( $enabled );
			$body['guide_items_complete']           = $complete;
			$body['guide_items_complete_count']     = count( $complete );
			$body['guide_items_complete_percent']   = $enabled ? (int) round( count( $complete ) / count( $enabled ) * 100 ) : 0;
			$body['guide_items_incomplete']         = $incomplete;
			$body['guide_items_incomplete_count']   = count( $incomplete );
			$body['guide_items_incomplete_percent'] = $enabled ? (int) round( count( $incomplete ) / count( $enabled ) * 100 ) : 0;

			foreach ( $complete_timestamps as $item => $timestamp ) {
				if ( $timestamp ) {
					$body[ $item . '_completed_at' ] = (int) $timestamp;
				}
			}
		}

		ksort( $body );

		wp_remote_post(
			$url,
			array(
				'method'   => 'POST',
				'blocking' => false,
				'body'     => $body,
			)
		);

		// Restore the option filters.
		add_filter( 'option_' . self::SETTINGS['liveSiteDismiss'], array( Helper::class, 'get_option_convert_timestamp_to_true' ) );
		add_filter( 'option_' . self::SETTINGS['publishState'], array( Helper::class, 'get_option_convert_timestamp_to_true' ) );

		return $value;
	}

	/**
	 * Sanitize callback that reports data to the NUX API.
	 *
	 * This should only be sent ONCE when the `liveSiteDismiss` option is
	 * saved. Subsequent milestone requests from the same domain will
	 * be ignored by the API so this is our only chance to send the
	 * right data.
	 *
	 * @param  mixed $value This is the sanitized data from the API.
	 */
	public function milestone_publish_later_nux_api( $value ) {
		if ( ! $value || ! Helper::is_rum_enabled() ) {
			return $value;
		}

		$domain = defined( 'GD_TEMP_DOMAIN' ) ? GD_TEMP_DOMAIN : Helper::domain();
		$url    = Helper::wpnux_api_base() . '/milestones/site-publish-later?domain=' . $domain;

		wp_remote_post(
			$url,
			array(
				'method'   => 'POST',
				'blocking' => false,
				'body'     => Helper::get_default_nux_api_request_body(),
			)
		);

		return $value;
	}

	/**
	 * Function boot used register settings, add options, add actions, enqueue scripts, localize scripts.
	 */
	public function boot() {
		// We need the settings registered to use with the REST API.
		foreach ( self::SETTINGS as $key => $settings_key ) {
			register_setting(
				$settings_key,
				$settings_key,
				array(
					'show_in_rest'      => true,
					'default'           => self::SETTINGS_OVERRIDE[ $key ]['default'] ?? true,
					'type'              => 'boolean',
					'sanitize_callback' => ! empty( self::SETTINGS_OVERRIDE[ $key ]['sanitize_callback'] )
						? array( $this, self::SETTINGS_OVERRIDE[ $key ]['sanitize_callback'] )
						: null,
				)
			);

			// Initialize the option.
			add_option( $settings_key );

			if ( ! empty( self::SETTINGS_OVERRIDE[ $key ]['true_as_timestamp'] ) ) {
				// If the value passed is boolean true, change the value to a timestamp before it's saved.
				add_filter( "pre_update_option_{$settings_key}", array( Helper::class, 'update_option_convert_true_to_timestamp' ) );
				// When pulling the value, convert back to boolean true.
				add_filter( "option_{$settings_key}", array( Helper::class, 'get_option_convert_timestamp_to_true' ) );
			}
		}

		$do_action_on_option_update = function( $value ) {
			do_action( 'gdl_splash_page_set' );

			return $value;
		};

		/**
		 * Hook into the pre_update filter for the publish state to trigger a clearing of the cache.
		 *
		 * @param mixed  $value  The new, unserialized option value.
		 */
		add_filter( 'pre_update_option_' . self::SETTINGS['publishState'], $do_action_on_option_update );
		add_filter( 'pre_update_option_' . self::SETTINGS['liveSiteDismiss'], $do_action_on_option_update );

		if ( ! $this->is_restricted() ) {
			return;
		}

		// This is to remove the toobar when previewing the website.
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET[ self::LIVE_CONTROL_PREVIEW_ARG ] ) ) {
			add_action(
				'after_setup_theme',
				function() {
					show_admin_bar( false );
				}
			);

			return;
		}

		add_action(
			is_admin() ? 'admin_enqueue_scripts' : 'wp_enqueue_scripts',
			function( $hook_suffix ) {
				$build_file_path = $this->app->basePath( 'build/' . self::ASSET_SLUG . '.asset.php' );

				$asset_file = file_exists( $build_file_path )
					? include $build_file_path
					: array(
						'dependencies' => array(),
						'version'      => $this->app->version(),
					);

				wp_enqueue_script(
					self::ASSET_SLUG,
					$this->app->baseUrl( 'build/' . self::ASSET_SLUG . '.js' ),
					$asset_file['dependencies'],
					$asset_file['version'],
					true
				);

				wp_localize_script(
					self::ASSET_SLUG,
					'gdlLiveSiteControlData',
					array(
						'page'                 => $hook_suffix,
						'appContainerClass'    => self::APP_CONTAINER_CLASS,
						'portalContainerClass' => self::PORTAL_CONTAINER_CLASS,
						'settings'             => self::SETTINGS,
						'previewArg'           => self::LIVE_CONTROL_PREVIEW_ARG,
						'eventName'            => self::LIVE_CONTROL_EVENT_NAME,
						'isReseller'           => defined( 'GD_RESELLER' ) ? GD_RESELLER : null,
					)
				);

				wp_set_script_translations(
					self::ASSET_SLUG,
					'godaddy-launch',
					$this->app->basePath( 'languages' )
				);

				wp_enqueue_style(
					self::ASSET_SLUG,
					$this->app->baseUrl( 'build/' . self::ASSET_SLUG . '.css' ),
					array( 'wp-components' ),
					$asset_file['version']
				);

				if ( is_admin() ) {
					add_action(
						'all_admin_notices',
						function() {
							printf( '<div id="%s"></div>', esc_attr( self::PORTAL_CONTAINER_CLASS ) );
						}
					);
				}

				add_action(
					is_admin() ? 'admin_footer' : 'wp_footer',
					function() {
						printf( '<div id="%s"></div>', esc_attr( self::APP_CONTAINER_CLASS ) );
					}
				);
			}
		);

		/**
		 * Show coming soon template if site is restricted.
		 */
		add_action(
			'parse_request',
			function() {
				if ( $this->is_restricted() && ! $this->user_can_access() ) {
					include __DIR__ . '/template-coming-soon.php';
					status_header( 403 );
					nocache_headers();
					die();
				}
			},
			1
		);

		add_action( 'wp_before_admin_bar_render', array( $this, 'wp_before_admin_bar_render' ) );

		add_action( 'admin_notices', array( $this, 'gdl_launch_site_notice' ) );
	}

	/**
	 * Function producing the admin screen notice that site is not live.
	 */
	public function gdl_launch_site_notice() {
		$notice_class         = 'notice notice-warning is-dismissible gdl-notice';
		$button_class         = 'components-button components-notice__action is-link is-secondary gdl-notice-button';
		$message              = __(
			'Your site is not launched yet. Visitors will see a temporary Coming Soon page until you launch your site.
		',
			'godaddy-launch'
		);
		$button_text          = __( 'Launch Now', 'godaddy-launch' );
		$publish_state_string = self::LIVE_CONTROL_EVENT_NAME;

		// Hide notice when site is launched.
		wp_add_inline_script(
			self::ASSET_SLUG,
			"jQuery( document ).ready( () => {
			const notice = jQuery( \".notice.is-dismissible\" );
			notice.on( 'click', () => {
				window.dispatchEvent( new Event( \"$publish_state_string\" ) );
				const launchButton = jQuery( \".live-site-confirm-modal-success\" );
				launchButton.on( 'click', () => {
					notice.fadeTo( 100, 0, function() {
						notice.slideUp( 100, function() {
							notice.remove();
						} );
					} );
				} );
			} );
		} );"
		);

		printf( '<div class="%1$s"><p>%2$s</p><button data-eid="wp.wpadmin.notice.launch.click" class="%3$s">%4$s</button></div>', esc_attr( $notice_class ), esc_html( $message ), esc_html( $button_class ), esc_html( $button_text ) );
	}

	/**
	 * Determine if site should be restricted
	 *
	 * @return bool
	 */
	public function is_restricted() {
		return ! get_option( self::SETTINGS['publishState'], false );
	}

	/**
	 * Determine if the current user has access.
	 *
	 * @return bool
	 */
	public function user_can_access() {
		return is_user_logged_in() || is_admin();
	}

	/**
	 * Render a simple notice in the admin bar when viewing the site as an admin when the site is restricted.
	 */
	public function wp_before_admin_bar_render() {
		global $wp_admin_bar;

		// Only show notice when viewing the website normally.
		if ( is_admin() ) {
			return;
		}

		$wp_admin_bar->add_menu(
			array(
				'parent' => 'top-secondary',
				'id'     => 'gdl-live-site',
				'title'  => __( 'Your website is in Coming Soon mode ', 'godaddy-launch' ),
			)
		);
	}

	/**
	 * Get the current state of guide items.
	 *
	 * @return array
	 */
	public function get_guide_items_state() {
		$items = array(
			GuideItems\AddDomain::class,
			GuideItems\SiteContent::class,
			GuideItems\SiteDesign::class,
			GuideItems\SiteInfo::class,
			GuideItems\SiteMedia::class,
		);

		$enabled             = array();
		$complete            = array();
		$complete_timestamps = array();
		$incomplete          = array();

		foreach ( $items as $item ) {
			$item_object = $this->app->make( $item );
			$item_slug   = str_replace( 'gdl_pgi_', '', $item_object->option_name() );

			if ( $item_object->is_enabled() ) {
				$enabled[] = $item_slug;

				if ( $item_object->is_complete() ) {
					$complete[] = $item_slug;

					remove_filter(
						"option_{$item_object->option_name()}",
						array( Helper::class, 'get_option_convert_timestamp_to_true' )
					);

					$complete_timestamps[ $item_slug ] = get_option( $item_object->option_name() );

					add_filter(
						"option_{$item_object->option_name()}",
						array( Helper::class, 'get_option_convert_timestamp_to_true' )
					);
				} else {
					$incomplete[] = $item_slug;
				}
			}
		}

		return array(
			$enabled,
			$complete,
			$complete_timestamps,
			$incomplete,
		);
	}
}
