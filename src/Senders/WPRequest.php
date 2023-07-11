<?php

namespace WPD\WPStartUp\Senders;

use WPD\WPStartUp\Interfaces\SenderInterface;

class WPRequest implements SenderInterface {

	/**
	 * @param string $url
	 * @param string $method
	 * @param array  $headers
	 * @param array  $data
	 * @return mixed
	 */
	public function remote_request( string $url, string $method, array $headers, array $data ) {
		$response = wp_remote_request(
			$url,
			[
				'body'    => $data,
				'headers' => $headers,
				'method'  => $method,
			]
		);

		return is_wp_error( $response )
			? $response
			: json_decode( wp_remote_retrieve_body( $response ), true );
	}
}
