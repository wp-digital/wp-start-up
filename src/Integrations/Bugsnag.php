<?php

namespace WPD\WPStartUp\Integrations;

use WPD\WPStartUp\Abstracts\AbstractProject;

class Bugsnag extends AbstractProject {
	const BASE_URL          = 'https://api.bugsnag.com/';
	const ORGANIZATIONS_URL = 'user/organizations';
	const PROJECTS_URL      = 'organizations/{organization_id}/';
	const PROJECT_URL       = 'projects/{project_id}';
	const ORG_ID_OPTION     = 'bugsnag_organization_id';
	const API_KEY_OPTION    = 'bugsnag_api_key';

	/**
	 * @return bool
	 */
	public function is_project_created(): bool {
		return ( defined( 'BUGSNAG_API_KEY' ) && BUGSNAG_API_KEY )
			|| $this->storage->get_data( self::API_KEY_OPTION );
	}

	/**
	 * @return void
	 */
	public function create_project(): void {
		if (
			! defined( 'BUGSNAG_TOKEN' ) ||
			! defined( 'BUGSNAG_PROJECT' )
		) {
			return;
		}

		$organization_id = $this->get_organization_id();

		if ( $organization_id ) {
			$projects = array_filter(
				$this->get_projects_by_name(),
				function( $project ) {
					return $project['name'] === BUGSNAG_PROJECT;
				}
			);

			if ( $projects ) {
				$project = array_shift( $projects );
			} else {
				$project = $this->create_project_in_api();
			}

			if ( ! empty( $project['api_key'] ) ) {
				$this->storage->save_data( self::API_KEY_OPTION, $project['api_key'] );
				$this->set_project_collaborators( $project );
			}
		}
	}

	/**
	 * @return string
	 */
	private function get_organization_id(): string {
		$organization_id = $this->storage->get_data( self::ORG_ID_OPTION, '' );

		if ( ! $organization_id ) {
			$organizations = $this->request_to_api( self::ORGANIZATIONS_URL );

			if ( $organizations && is_array( $organizations ) ) {
				$organization    = array_shift( $organizations );
				$organization_id = $organization['id'] ?? '';

				if ( $organization_id ) {
					$this->storage->save_data( self::ORG_ID_OPTION, $organization_id );
				}
			}
		}

		return $organization_id;
	}

	/**
	 * @return array
	 */
	private function get_projects_by_name(): array {
		$projects = $this->do_project_request(
			[
				'q' => BUGSNAG_PROJECT,
			]
		);

		return ! empty( $projects ) && is_array( $projects ) ? $projects : [];
	}

	/**
	 * @return array
	 */
	private function create_project_in_api(): array {
		$project = $this->do_project_request(
			[
				'name' => BUGSNAG_PROJECT,
				'type' => 'wordpress',
			],
			'POST'
		);

		return ! empty( $project ) && is_array( $project ) ? $project : [];
	}

	/**
	 * @param array $project
	 *
	 * @return void
	 */
	private function set_project_collaborators( array $project ): void {
		$collaborator_ids = $this->get_collaborator_ids();

		if ( $collaborator_ids ) {
			$url = str_replace(
				'{project_id}',
				$project['id'],
				self::PROJECT_URL
			);

			$this->request_to_api( $url, 'PATCH', [ 'collaborator_ids' => array_values( $collaborator_ids ) ] );
		}
	}

	/**
	 * @return array
	 */
	private function get_collaborator_ids() : array {
		$collaborators = $this->do_project_request( [], 'GET', 'collaborators' );

		if ( $collaborators && is_array( $collaborators ) ) {
			$collaborator_ids = wp_list_pluck( $collaborators, 'id' );
		}

		return $collaborator_ids ?? [];
	}

	/**
	 * @param array  $params
	 * @param string $method
	 * @param string $endpoint
	 *
	 * @return mixed
	 */
	private function do_project_request( array $params, string $method = 'GET', string $endpoint = 'projects' ) {
		$url = str_replace(
			'{organization_id}',
			$this->get_organization_id(),
			self::PROJECTS_URL . $endpoint
		);

		return $this->request_to_api( $url, $method, $params );
	}

	/**
	 * @param string $url
	 * @param string $method
	 * @param array  $params
	 *
	 * @return mixed
	 */
	private function request_to_api( string $url, string $method = 'GET', array $params = [] ) {
		return $this->sender->remote_request(
			self::BASE_URL . $url,
			$method,
			[
				'Accept'        => 'application/json',
				'Authorization' => 'token ' . BUGSNAG_TOKEN,
				'Content-Type'  => 'application/json',
				'X-Version'     => '2',
			],
			$method === 'GET' ? $params : json_encode( $params )
		);
	}

	/**
	 * @return void
	 */
	public function delete_data(): void {
		$this->storage->delete_data( self::ORG_ID_OPTION );
		$this->storage->delete_data( self::API_KEY_OPTION );
	}
}
