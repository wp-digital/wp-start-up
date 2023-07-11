<?php

namespace WPD\WPStartUp\Interfaces;

interface SenderInterface {

	/**
	 * Performs an HTTP request and returns its response.
	 *
	 * @param string $url
	 * @param string $method
	 * @param array  $headers
	 * @param array  $data
	 * @return mixed
	 */
	public function remote_request( string $url, string $method, array $headers, array $data );
}
