<?php
/**
 * File containing the \Sensei\WPML\Slug class.
 *
 * @package sensei
 */

namespace Sensei\WPML;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Slug
 *
 * Compatibility code with WPML.
 *
 * @since $$next-version$$
 *
 * @internal
 */
class Slug {
	use WPML_API;

	/**
	 * Init hooks.
	 */
	public function init() {
		$is_wpml_active = $this->is_wpml_active();
		if ( ! $is_wpml_active ) {
			return;
		}

		add_filter( 'sensei_course_slug', array( $this, 'get_course_slug' ), 10, 1 );
		add_filter( 'sensei_lesson_slug', array( $this, 'get_lesson_slug' ), 10, 1 );
		add_filter( 'sensei_quiz_slug', array( $this, 'get_quiz_slug' ), 10, 1 );
		add_filter( 'sensei_question_slug', array( $this, 'get_question_slug' ), 10, 1 );
	}

	/**
	 * Get course slug.
	 *
	 * @since $$next-version$$
	 *
	 * @internal
	 *
	 * @param string $slug The course slug.
	 * @return string
	 */
	public function get_course_slug( $slug ) {
		$use_wpml_slug_translation = Sensei()->settings->get( 'wpml_slug_translation' );
		if ( $use_wpml_slug_translation ) {
			return 'course';
		} else {
			return $slug;
		}
	}

	/**
	 * Get lesson slug.
	 *
	 * @since $$next-version$$
	 *
	 * @internal
	 *
	 * @param string $slug Lesson slug.
	 * @return string
	 */
	public function get_lesson_slug( $slug ) {
		$use_wpml_slug_translation = Sensei()->settings->get( 'wpml_slug_translation' );
		if ( $use_wpml_slug_translation ) {
			return 'lesson';
		} else {
			return $slug;
		}
	}

	/**
	 * Get question slug.
	 *
	 * @since $$next-version$$
	 *
	 * @internal
	 *
	 * @param string $slug Question slug.
	 * @return string
	 */
	public function get_question_slug( $slug ) {
		$use_wpml_slug_translation = Sensei()->settings->get( 'wpml_slug_translation' );
		if ( $use_wpml_slug_translation ) {
			return 'question';
		} else {
			return $slug;
		}
	}

	/**
	 * Get quiz slug.
	 *
	 * @since $$next-version$$
	 *
	 * @internal
	 *
	 * @param string $slug The quiz slug.
	 * @return string
	 */
	public function get_quiz_slug( $slug ) {
		$use_wpml_slug_translation = Sensei()->settings->get( 'wpml_slug_translation' );
		if ( $use_wpml_slug_translation ) {
			return 'quiz';
		} else {
			return $slug;
		}
	}
}
