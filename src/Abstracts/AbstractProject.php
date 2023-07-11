<?php

namespace WPD\WPStartUp\Abstracts;

use WPD\WPStartUp\Interfaces\IntegrationInterface;
use WPD\WPStartUp\Interfaces\SenderInterface;
use WPD\WPStartUp\Interfaces\StorageInterface;

abstract class AbstractProject implements IntegrationInterface {

	/**
	 * @var StorageInterface
	 */
	protected StorageInterface $storage;
	/**
	 * @var SenderInterface
	 */
	protected SenderInterface $sender;

	/**
	 * @param StorageInterface|null $storage
	 * @param SenderInterface|null  $sender
	 */
	public function __construct( ?StorageInterface $storage = null, ?SenderInterface $sender = null ) {
		$this->storage = $storage ?: wp_start_up()->get_storage();
		$this->sender  = $sender ?: wp_start_up()->get_sender();
	}

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
