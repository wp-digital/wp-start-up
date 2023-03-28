<?php

namespace WPD\WPStartUp\Integrations;

use WPD\WPStartUp\Abstracts\AbstractProject;
use WPD\WPStartUp\Tools;

class Pingdom extends AbstractProject {
	const URL            = 'https://api.pingdom.com/api/3.1/checks';
	const API_KEY_OPTION = 'pingdom_project_id';

	/**
	 * @return bool
	 */
	public function is_project_created(): bool {
		return (bool) get_option( self::API_KEY_OPTION );
	}

	/**
	 * @return void
	 */
	public function create_project(): void {
		if (
			! defined( 'PINGDOM_TOKEN' ) ||
			! defined( 'PINGDOM_PROJECT' )
		) {
			return;
		}

		if ( ! self::is_project_created_in_api() ) {
			self::create_project_in_api();
		}
	}

	/**
	 * @return bool
	 */
	public function is_project_created_in_api(): bool {
		$checks = Tools::remote_request(
			self::URL,
			[
				'headers' => [
					'Authorization' => 'Bearer ' . PINGDOM_TOKEN,
				],
				'method'  => 'GET',
			]
		);

		if ( ! is_wp_error( $checks ) && is_array( $checks ) ) {
			$names = wp_list_pluck( $checks, 'name', 'id' );
			$key   = array_search( PINGDOM_PROJECT, $names, true );

			if ( $key ) {
				update_option( self::API_KEY_OPTION, $names[ $key ] );
			}
		}

		return ! empty( $key );
	}

	/**
	 * @return bool
	 */
	public function create_project_in_api(): bool {
		$check = Tools::remote_request(
			self::URL,
			[
				'headers' => [
					'Authorization' => 'Bearer ' . PINGDOM_TOKEN,
					'Content type'  => 'application/json',
				],
				'method'  => 'POST',
				'body'    => json_encode(
					[
						'name' => PINGDOM_PROJECT,
						'host' => parse_url( WP_HOME, PHP_URL_HOST ),
						'type' => 'http',
					]
				),
			]
		);

		if ( ! empty( $check->id ) ) {
			update_option( self::API_KEY_OPTION, $check->id );
		}

		return ! empty( $check->id );
	}

	/**
	 * @return void
	 */
	public function delete_data(): void {
		delete_option( self::API_KEY_OPTION );
	}
}
