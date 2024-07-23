<?php
/**
 * Sensei REST API: Sensei_REST_API_Sensei_Emails_Controller class.
 *
 * @package sensei-lms
 * @since   $$next-version$$
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * A REST controller for Sensei Emails CPT.
 *
 * @since $$next-version$$
 *
 * @see WP_REST_Posts_Controller
 */
class Sensei_REST_API_Sensei_Emails_Controller extends WP_REST_Posts_Controller {
	/**
	 * Checks if a given request has access to read posts.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @return true|WP_Error True if the request has read access, WP_Error object otherwise.
	 */
	public function get_items_permissions_check( $request ) {
		$parent_check = parent::get_items_permissions_check( $request );

		if ( is_wp_error( $parent_check ) ) {
			return $parent_check;
		}

		if ( ! current_user_can( 'manage_sensei' ) ) {
			return new WP_Error(
				'rest_forbidden_context',
				__( 'Sorry, you are not allowed to view posts in this post type.', 'sensei-lms' ),
				array( 'status' => rest_authorization_required_code() )
			);
		}

		return true;
	}

	/**
	 * Checks if the logged-in user should have access to a specific sensei email.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @return true|WP_Error True if the request has read access, WP_Error object otherwise.
	 */
	public function get_item_permissions_check( $request ) {
		$parent_check = parent::get_item_permissions_check( $request );

		if ( is_wp_error( $parent_check ) ) {
			return $parent_check;
		}

		if ( ! current_user_can( 'edit_post', $request['id'] ) ) {
			return new WP_Error(
				'rest_forbidden_context',
				__( 'Sorry, you are not allowed to view this item.', 'sensei-lms' ),
				array( 'status' => rest_authorization_required_code() )
			);
		}

		return true;
	}
}
