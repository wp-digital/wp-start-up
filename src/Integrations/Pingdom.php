<?php

namespace WPD\WPStartUp\Integrations;

use WPD\WPStartUp\Abstracts\AbstractProject;

class Pingdom extends AbstractProject {
	const URL            = 'https://api.pingdom.com/api/3.1/checks';
	const API_KEY_OPTION = 'pingdom_project_id';
	private $checks;
	private $option_value;

	/**
	 * @return bool
	 */
	public function is_project_created(): bool {
		$this->option_value = (bool) $this->storage->get_data( self::API_KEY_OPTION );

		return $this->option_value;
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

		if ( $this->should_create_project() ) {
			$this->create_project_in_api();
		}
	}

	/**
	 * @return bool
	 */
	public function should_create_project(): bool {
		$checks       = $this->request_to_api();
		$this->checks = $checks;

		$plugin_directory = plugin_dir_path( WPSTARTUP_FILE );
		$log_file         = $plugin_directory . 'logs.log';

		if ( ! file_exists( $log_file ) ) {
			$handle = fopen( $log_file, 'w' );
			fclose( $handle );
			chmod( $log_file, 0644 );
		}

		file_put_contents(
			$log_file,
			sprintf(
				"Option value 1: %s Option value 2: %s\n",
				json_encode( $this->option_value ),
				json_encode( get_option( self::API_KEY_OPTION ) ),
				gmdate( 'd m Y H:i' )
			),
			FILE_APPEND
		);

		if ( is_array( $checks ) && ! empty( $checks['checks'] ) ) {
			$names = wp_list_pluck( $checks['checks'], 'name', 'id' );
			$key   = array_search( PINGDOM_PROJECT, $names, true );

			if ( $key ) {
				$this->storage->save_data( self::API_KEY_OPTION, $key );
			}
		}

		if ( is_wp_error( $checks ) ) {
			file_put_contents(
				$log_file,
				sprintf(
					"Error: %s\nTime: %s\n",
					json_encode( $checks ),
					gmdate( 'd m Y H:i' )
				),
				FILE_APPEND
			);
		}

		return ! is_wp_error( $checks ) && empty( $key );
	}

	/**
	 * @return void
	 */
	public function create_project_in_api(): void {
		$plugin_directory = plugin_dir_path( WPSTARTUP_FILE );
		$log_file         = $plugin_directory . 'logs.log';

		if ( ! file_exists( $log_file ) ) {
			$handle = fopen( $log_file, 'w' );
			fclose( $handle );
			chmod( $log_file, 0644 );
		}

		file_put_contents(
			$log_file,
			sprintf(
				"Checks: %s\nOption value: %s Time: %s\n",
				json_encode( $this->checks ),
				json_encode( $this->option_value ),
				gmdate( 'd m Y H:i' )
			),
			FILE_APPEND
		);

		$check = $this->request_to_api(
			'POST',
			[
				'name' => PINGDOM_PROJECT,
				'host' => parse_url( WP_HOME, PHP_URL_HOST ),
				'type' => 'http',
			]
		);

		if ( is_array( $check ) && ! empty( $check['id'] ) ) {
			$this->storage->save_data( self::API_KEY_OPTION, $check['id'] );
		}
	}

	/**
	 * @param string $method
	 * @param array  $params
	 *
	 * @return mixed
	 */
	private function request_to_api( string $method = 'GET', array $params = [] ) {
		return $this->sender->remote_request(
			self::URL,
			$method,
			[
				'Accept'        => 'application/json',
				'Authorization' => 'Bearer ' . PINGDOM_TOKEN,
				'Content type'  => 'application/json',
			],
			$params
		);
	}

	/**
	 * @return void
	 */
	public function delete_data(): void {
		$this->storage->delete_data( self::API_KEY_OPTION );
	}
}
