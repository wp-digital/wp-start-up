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
		if ( ! self::is_project_created() ) {
			self::create_project();
		}
	}

	/**
	 * @return void
	 */
	public function deactivate(): void {
		self::delete_data();
	}

	/**
	 * @return void
	 */
	public function init(): void {
		self::activate();
	}
}
