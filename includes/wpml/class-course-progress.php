<?php
/**
 * File containing \Sensei\WPML\Course_Progress class.
 *
 * @package sensei
 */

namespace Sensei\WPML;

use Sensei_Course_Enrolment_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Course_Progress
 *
 * Compatibility code with WPML.
 *
 * @since $$next-version$$
 *
 * @internal
 */
class Course_Progress {
	use WPML_API;

	/**
	 * Init hooks.
	 */
	public function init() {
		add_filter( 'sensei_course_is_user_enrolled_course_id', array( $this, 'translate_course_id' ) );
		add_filter( 'sensei_block_take_course_course_id', array( $this, 'translate_course_id' ) );
		add_filter( 'sensei_course_progress_create_course_id', array( $this, 'translate_course_id' ) );
		add_filter( 'sensei_course_progress_get_course_id', array( $this, 'translate_course_id' ) );
		add_filter( 'sensei_course_progress_has_course_id', array( $this, 'translate_course_id' ) );
		add_filter( 'sensei_course_progress_delete_for_course_course_id', array( $this, 'translate_course_id' ) );
		add_filter( 'sensei_course_progress_find_course_id', array( $this, 'translate_course_id' ) );
		add_filter( 'sensei_lesson_progress_count_course_id', array( $this, 'translate_course_id' ) );
		add_filter( 'sensei_course_start_course_id', array( $this, 'translate_course_id' ) );
		add_filter( 'sensei_learner_enrolled_courses_query_by_progress_status_course_ids', array( $this, 'translate_course_ids' ) );

		add_action( 'sensei_manual_enrolment_learner_enrolled', array( $this, 'enrol_learner' ), 10, 2 );
		add_action( 'sensei_manual_enrolment_learner_withdrawn', array( $this, 'withdraw_learner' ), 10, 2 );
	}

	/**
	 * Enroll learner.
	 *
	 * @since $$next-version$$
	 *
	 * @internal
	 *
	 * @param int $user_id User ID.
	 * @param int $course_id Course ID.
	 */
	public function enrol_learner( $user_id, $course_id ) {
		remove_action( 'sensei_manual_enrolment_learner_enrolled', array( $this, 'enrol_learner' ) );
		remove_filter( 'sensei_course_is_user_enrolled_course_id', array( $this, 'translate_course_id' ) );

		$enrolment_manager         = Sensei_Course_Enrolment_Manager::instance();
		$manual_enrolment_provider = $enrolment_manager->get_manual_enrolment_provider();
		$course_translations       = $this->get_element_translations( $course_id, 'post_course' );
		foreach ( $course_translations as $course_translation ) {
			$course_id = $course_translation->element_id;
			$manual_enrolment_provider->enrol_learner( $user_id, $course_id );
		}

		add_action( 'sensei_manual_enrolment_learner_enrolled', array( $this, 'enrol_learner' ), 10, 2 );
		add_filter( 'sensei_course_is_user_enrolled_course_id', array( $this, 'translate_course_id' ) );
	}

	/**
	 * Withdraw learner.
	 *
	 * @since $$next-version$$
	 *
	 * @internal
	 *
	 * @param int $user_id User ID.
	 * @param int $course_id Course ID.
	 */
	public function withdraw_learner( $user_id, $course_id ) {
		remove_action( 'sensei_manual_enrolment_learner_withdrawn', array( $this, 'withdraw_learner' ), 10, 2 );
		remove_filter( 'sensei_course_is_user_enrolled_course_id', array( $this, 'translate_course_id' ) );

		$enrolment_manager         = Sensei_Course_Enrolment_Manager::instance();
		$manual_enrolment_provider = $enrolment_manager->get_manual_enrolment_provider();
		$course_translations       = $this->get_element_translations( $course_id, 'post_course' );
		foreach ( $course_translations as $course_translation ) {
			$course_id = $course_translation->element_id;
			$manual_enrolment_provider->withdraw_learner( $user_id, $course_id );
		}

		add_action( 'sensei_manual_enrolment_learner_withdrawn', array( $this, 'withdraw_learner' ), 10, 2 );
		add_filter( 'sensei_course_is_user_enrolled_course_id', array( $this, 'translate_course_id' ) );
	}

	/**
	 * Translate course ID.
	 *
	 * @since $$next-version$$
	 *
	 * @internal
	 *
	 * @param int $course_id Course ID.
	 * @return int
	 */
	public function translate_course_id( $course_id ): int {
		$course_id = (int) $course_id;
		$details   = $this->get_element_language_details( $course_id, 'course' );

		$original_language_code = $details['source_language_code'] ?? $details['language_code'] ?? null;

		return $this->get_object_id( $course_id, 'course', true, $original_language_code );
	}

	/**
	 * Translate course IDs.
	 *
	 * @since $$next-version$$
	 *
	 * @internal
	 *
	 * @param array $course_ids Course IDs.
	 * @return array
	 */
	public function translate_course_ids( array $course_ids ): array {
		foreach ( $course_ids as $key => $course_id ) {
			$course_ids[ $key ] = $this->get_object_id( $course_id, 'course', true );
		}

		return $course_ids;
	}
}
