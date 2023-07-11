<?php

namespace WPD\WPStartUp\Interfaces;

interface StorageInterface {

	/**
	 * Updates the value of a data that was already added.
	 * If the data does not exist, it will be created.
	 *
	 * @param string $name
	 * @param mixed  $data
	 * @return bool
	 */
	public function save_data( string $name, mixed $data ): bool;

	/**
	 * Retrieves a data value based on an data name.
	 *
	 * @param string $name
	 * @param string $default_value
	 * @return mixed
	 */
	public function get_data( string $name, string $default_value = '' );

	/**
	 * Removes data by name
	 *
	 * @param string $name
	 * @return bool True if the data was deleted, false otherwise.
	 */
	public function delete_data( string $name ): bool;
}
