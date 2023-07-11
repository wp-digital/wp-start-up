<?php

namespace WPD\WPStartUp\Storages;

use WPD\WPStartUp\Interfaces\StorageInterface;

class WPOption implements StorageInterface {

	/**
	 * @param string $name
	 * @param mixed  $data
	 * @return bool
	 */
	public function save_data( string $name, mixed $data ): bool {
		return update_option( $name, $data );
	}

	/**
	 * @param string $name
	 * @param string $default_value
	 * @return mixed
	 */
	public function get_data( string $name, string $default_value = '' ) {
		return get_option( $name, $default_value );
	}

	/**
	 * @param string $name
	 * @return bool
	 */
	public function delete_data( string $name ): bool {
		return delete_option( $name );
	}
}
