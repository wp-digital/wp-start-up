<?php

namespace WPD\WPStartUp\Abstracts;

use WPD\WPStartUp\Interfaces\IntegrationInterface;

abstract class AbstractProject implements IntegrationInterface {

	/**
	 * @return void
	 */
	abstract public function is_project_created(): bool;

	/**
	 * @return void
	 */
	abstract public function create_project(): void;

	/**
	 * @return void
	 */
	abstract public function delete_data(): void;

	/**
	 * @return void
	 */
	public function activate(): void {
		if ( ! static::is_project_created() ) {
			static::create_project();
		}
	}

	/**
	 * @return void
	 */
	public function deactivate(): void {
		static::delete_data();
	}

	/**
	 * @return void
	 */
	public function init(): void {
		static::activate();
	}
}
