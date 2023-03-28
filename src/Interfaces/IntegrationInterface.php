<?php

namespace WPD\WPStartUp\Interfaces;

interface IntegrationInterface {

	/**
	 * @return void
	 */
	public function activate(): void;

	/**
	 * @return void
	 */
	public function deactivate(): void;

	/**
	 * @return void
	 */
	public function init(): void;
}
