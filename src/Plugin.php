<?php

namespace WPD\WPStartUp;

final class Plugin {

	/**
	 * @var Interfaces\IntegrationInterface[]
	 */
	private $integrations;

	/**
	 * Plugin constructor
	 */
	public function __construct() {
		$this->integrations = [
			new Integrations\Bugsnag(),
			new Integrations\Pingdom(),
		];
	}

	/**
	 * @return void
	 */
	public function run(): void {
		register_activation_hook( WPSTARTUP_FILE, [ $this, 'activate' ] );
		register_deactivation_hook( WPSTARTUP_FILE, [ $this, 'deactivate' ] );

		add_action( 'admin_init', [ $this, 'init' ] );
	}

	/**
	 * @return void
	 */
	public function init(): void {
		foreach ( $this->integrations as $integration ) {
			$integration->init();
		}
	}

	/**
	 * @return void
	 */
	public function activate(): void {
		foreach ( $this->integrations as $integration ) {
			$integration->activate();
		}
	}

	/**
	 * @return void
	 */
	public function deactivate(): void {
		foreach ( $this->integrations as $integration ) {
			$integration->deactivate();
		}
	}
}
