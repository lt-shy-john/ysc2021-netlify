<?php

namespace Wptool\adminDash\services;

use WP_Error;

class SiteHealthService {

	const ASYNC_TESTS_PATTERNS_TO_EXCLUDE = array(
		'/objectcache/',
	);

	/**
	 * This function is copied from wp-admin/includes/class-wp-site-health.php - wp_cron_scheduled_check(),
	 * Return type is modified and cron checks are removed in order to retrieve SiteHealth data in array format.
	 *
	 * @return array
	 */
	public function get_status_tab_data_sync() {

		require_once trailingslashit( ABSPATH ) . 'wp-admin/includes/admin.php';

		$tests    = \WP_Site_Health::get_tests();
		$instance = \WP_Site_Health::get_instance();

		$results = array();

		foreach ( $tests['direct'] as $test ) {

			if ( is_string( $test['test'] ) ) {
				$test_function = sprintf(
					'get_test_%s',
					$test['test']
				);

				if ( method_exists( $instance, $test_function ) && is_callable( array( $instance, $test_function ) ) ) {
					$results[] = $this->perform_test( array( $instance, $test_function ) );
					continue;
				}
			}

			if ( is_callable( $test['test'] ) ) {
				$results[] = $this->perform_test( $test['test'] );
			}
		}

		return $results;

	}

	/**
	 * This function is copied from wp-admin/includes/class-wp-site-health.php - wp_cron_scheduled_check(),
	 * Return type is modified and cron checks are removed in order to retrieve SiteHealth data in array format.
	 *
	 * @param string $test
	 * @return array|null
	 */
	public function get_status_tab_data_async( $test_name ) {

		require_once trailingslashit( ABSPATH ) . 'wp-admin/includes/admin.php';

		if ( 'authorization_header' === $test_name ) {
			$test = array(
				'async_direct_test' => array( \WP_Site_Health::get_instance(), 'get_test_authorization_header' ),
			);

		} else {
			$test = \WP_Site_Health::get_tests()['async'][ $test_name ];
		}

		if ( ! empty( $test['async_direct_test'] ) && is_callable( $test['async_direct_test'] ) ) {
			return call_user_func( $test['async_direct_test'] );
		}

		return null;
	}

	/**
	 * Gets the current directory sizes for this install. This function is copied from class-wp-rest-site-health-controller.php
	 *
	 * @since 5.6.0
	 *
	 * @return array|WP_Error
	 */
	public function get_directory_sizes() {
		if ( ! class_exists( 'WP_Debug_Data' ) ) {
			require_once ABSPATH . 'wp-admin/includes/class-wp-debug-data.php';
		}

		$sizes_data = \WP_Debug_Data::get_sizes();
		$all_sizes  = array( 'raw' => 0 );

		foreach ( $sizes_data as $name => $value ) {
			$name = sanitize_text_field( $name );
			$data = array();

			if ( isset( $value['size'] ) ) {
				if ( is_string( $value['size'] ) ) {
					$data['size'] = sanitize_text_field( $value['size'] );
				} else {
					$data['size'] = (int) $value['size'];
				}
			}

			if ( isset( $value['debug'] ) ) {
				if ( is_string( $value['debug'] ) ) {
					$data['debug'] = sanitize_text_field( $value['debug'] );
				} else {
					$data['debug'] = (int) $value['debug'];
				}
			}

			if ( ! empty( $value['raw'] ) ) {
				$data['raw'] = (int) $value['raw'];
			}

			$all_sizes[ $name ] = $data;
		}

		if ( isset( $all_sizes['total_size']['debug'] ) && 'not available' === $all_sizes['total_size']['debug'] ) {
			return null;
		}

		return $all_sizes;
	}

	/**
	 * This function is copied from wp-admin/includes/class-wp-site-health.php - perform_test($callback),
	 *
	 * @param callable $callback
	 * @return mixed|void
	 */
	private function perform_test( $callback ) {
		return apply_filters( 'site_status_test_result', call_user_func( $callback ) );
	}

	/**
	 * Gets data for info tab in Site health
	 *
	 * @return array
	 * @throws \ImagickException
	 */
	public function get_info_tab_data() {

		if ( ! class_exists( 'WP_Debug_Data' ) ) {
			require_once trailingslashit( ABSPATH ) . 'wp-admin/includes/class-wp-debug-data.php';
		}

		require_once trailingslashit( ABSPATH ) . 'wp-admin/includes/admin.php';

		return \WP_Debug_Data::debug_data();
	}

	/**
	 * Get all available async test names.
	 *
	 * @return string[]
	 */
	public function get_available_async_tests() {

		$async_tests = \WP_Site_Health::get_tests()['async'];

		$async_tests_keys = array_keys( $async_tests );

		return array_filter(
			$async_tests_keys,
			function( $el ) {

				foreach ( self::ASYNC_TESTS_PATTERNS_TO_EXCLUDE as $pattern ) {
					if ( preg_match( $pattern, $el ) ) {
						return false;
					}
				}

				return true;
			}
		);

	}

}
