<?php

namespace WPD\WPStartUp;

final class Tools {

	/**
	 * @return bool
	 */
	public static function is_production(): bool {
		return defined( 'ENVIRONMENT' ) & ENVIRONMENT === 'production';
	}

	/**
	 * @param string $url
	 * @param array  $args
	 *
	 * @return mixed
	 */
	public static function remote_request( string $url, array $args ) {
		$response = wp_remote_request( $url, $args );

		return is_wp_error( $response )
			? $response
			: json_decode( wp_remote_retrieve_body( $response ), true );
	}
}
