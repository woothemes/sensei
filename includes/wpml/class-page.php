<?php
/**
 * File containing the \Sensei\WPML\Page class.
 *
 * @package sensei
 */

namespace Sensei\WPML;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Page
 *
 * Compatibility code with WPML.
 *
 * @since 4.23.1
 *
 * @internal
 */
class Page {
	use WPML_API;

	/**
	 * Init hooks.
	 */
	public function init() {
		add_filter( 'sensei_course_completed_page_id', array( $this, 'get_translated_course_completed_page_id' ), 10, 1 );
		add_filter( 'sensei_settings_my_course_page_id', array( $this, 'get_translated_my_course_page_id' ), 10, 1 );
	}

	/**
	 * Get translated course completed page ID.
	 *
	 * @since 4.23.1
	 *
	 * @internal
	 *
	 * @param int $page_id Page ID.
	 * @return int Translated page ID.
	 */
	public function get_translated_course_completed_page_id( $page_id ) {
		return $this->get_object_id( $page_id, 'page', true );
	}

	/**
	 * Get translated my course page ID.
	 *
	 * @since 4.23.1
	 *
	 * @internal
	 *
	 * @param int $page_id Page ID.
	 * @return int Translated page ID.
	 */
	public function get_translated_my_course_page_id( $page_id ) {
		return $this->get_object_id( $page_id, 'page', true );
	}
}
