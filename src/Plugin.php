<?php

namespace WPD\WPStartUp;

use WPD\WPStartUp\Interfaces\SenderInterface;
use WPD\WPStartUp\Interfaces\StorageInterface;
use WPD\WPStartUp\Senders\WPRequest;
use WPD\WPStartUp\Storages\WPOption;

final class Plugin {

	/**
	 * @var Interfaces\IntegrationInterface[]
	 */
	private array $integrations;

	/**
	 * @var StorageInterface
	 */
	private StorageInterface $storage;

	/**
	 * @var SenderInterface
	 */
	private SenderInterface $sender;

	/**
	 * Plugin constructor
	 */
	public function __construct() {
		$this->storage = apply_filters( 'wp_start_up_default_storage', new WPOption() );
		$this->sender  = apply_filters( 'wp_start_up_default_sender', new WPRequest() );
	}

	/**
	 * @return void
	 */
	public function run(): void {
		$this->integrations = apply_filters(
			'wp_start_up_integrations',
			[
				new Integrations\Bugsnag(),
				new Integrations\Pingdom(),
			]
		);

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

	/**
	 * @return StorageInterface
	 */
	public function get_storage(): StorageInterface {
		return $this->storage;
	}

	/**
	 * @return SenderInterface
	 */
	public function get_sender(): SenderInterface {
		return $this->sender;
	}
}
