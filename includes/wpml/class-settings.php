<?php
/**
 * File containing the \Sensei\WPML\Settings class.
 *
 * @package sensei
 */

namespace Sensei\WPML;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Settings
 *
 * Compatibility code with WPML.
 *
 * @since $$next-version$$
 *
 * @internal
 */
class Settings {
	use WPML_API;

	/**
	 * Init hooks.
	 */
	public function init() {
		$is_wpml_active = $this->is_wpml_active();
		if ( ! $is_wpml_active ) {
			return;
		}

		add_filter( 'sensei_settings_tabs', array( $this, 'add_tab' ), 10, 1 );
		add_filter( 'sensei_settings_fields', array( $this, 'add_fields' ), 10, 1 );
	}

	/**
	 * Add WPML tab.
	 *
	 * @since $$next-version$$
	 *
	 * @internal
	 *
	 * @param array $sections Settings sections.
	 * @return array
	 */
	public function add_tab( $sections ) {
		$sections['sensei-wpml-settings'] = array(
			'name'        => __( 'WPML', 'sensei-lms' ),
			'description' => __( 'Settings related to WMPL.', 'sensei-lms' ),
		);

		return $sections;
	}

	/**
	 * Add WPML fields.
	 *
	 * @since $$next-version$$
	 *
	 * @internal
	 *
	 * @param array $fields Settings fields.
	 * @return array
	 */
	public function add_fields( $fields ) {
		$fields['wpml_slug_translation'] = array(
			'name'        => __( 'Use WPML slug translation', 'sensei-lms' ),
			'description' => __( 'Enable this option to use WPML for translating Sensei slugs.', 'sensei-lms' ),
			'type'        => 'checkbox',
			'default'     => false,
			'section'     => 'sensei-wpml-settings',
		);

		return $fields;
	}
}
