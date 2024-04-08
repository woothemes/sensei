<?php
/**
 * File containing the class Sensei_Course_Pre_Publish_Panel.
 *
 * @package sensei-lms
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class that handles the pre-publish panel for courses.
 *
 * @since 4.22.0
 */
class Sensei_Course_Pre_Publish_Panel {
	/**
	 * Instance of class.
	 *
	 * @var self
	 */
	private static $instance;

	/**
	 * Sensei_Course_Pre_Publish_Panel constructor. Prevents other instances from being created outside of `self::instance()`.
	 */
	private function __construct() {}

	/**
	 * Fetches an instance of the class.
	 *
	 * @return self
	 */
	public static function instance() {
		if ( ! self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Initializes the class.
	 */
	public function init() {
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_pre_publish_panel_assets' ) );
		add_action( 'publish_course', array( $this, 'maybe_publish_lessons' ), 10, 3 );
	}

	/**
	 * Enqueue pre-publish panel assets.
	 */
	public function enqueue_pre_publish_panel_assets() {
		if ( 'course' !== get_post_type() ) {
			return;
		}

		Sensei()->assets->enqueue( 'sensei-course-pre-publish-panel-script', 'admin/course-pre-publish-panel/index.js' );
	}

	/**
	 * Maybe publish associated lessons when the course is published.
	 *
	 * @internal
	 *
	 * @param int     $course_id  Course ID.
	 * @param WP_Post $post       Post object.
	 * @param string  $old_status Old post status.
	 */
	public function maybe_publish_lessons( $course_id, $post, $old_status ) {
		if ( ! current_user_can( 'publish_post', $course_id ) ) {
			return;
		}

		$publish_lessons = get_post_meta( $course_id, 'sensei_course_publish_lessons', true );

		if ( ! $publish_lessons ) {
			return;
		}

		$publishing_meta_key = '_sensei_course_publishing_started';

		// Even if the course is already published, each subsequent updates also triggers this hook anyway
		// which caused the bug https://github.com/Automattic/sensei/issues/7555.
		// So we need to check if it's an actual publish call.
		$is_main_publish_call = 'publish' !== $old_status;

		if ( $is_main_publish_call ) {
			// This is the first call made, it's not the structure saving call, so the added/updated lessons are not yet saved at this point.
			// So we set the flag to publish lessons on the next call, which is made after the structure is saved.
			update_post_meta( $course_id, $publishing_meta_key, true );
		}

		$is_publishing_started = get_post_meta( $course_id, $publishing_meta_key, true );

		if ( ! $is_main_publish_call && ! $is_publishing_started ) {
			// If its not the "Publish" call and the flag is not set, then we don't need to publish lessons.
			// Because it that case it's just a normal "Update" call.
			return;
		}

		$uri                  = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
		$is_metabox_save_call = strpos( $uri, 'meta-box-loader=1' ) > 0;

		if ( ! $is_main_publish_call && ! $is_metabox_save_call ) {
			// If it's not the main publish call, then it's the structure saving call that comes immediately after the main publish call.
			// So we can remove the flag now, because after this iteraction, the whole publishing cycle is complete.
			delete_post_meta( $course_id, $publishing_meta_key );
		}

		// Publish all draft lessons for this course.
		$lesson_ids = Sensei()->course->course_lessons( $course_id, 'draft', 'ids' );

		foreach ( $lesson_ids as $lesson_id ) {
			wp_update_post(
				array(
					'ID'          => (int) $lesson_id,
					'post_status' => 'publish',
				)
			);
		}
	}
}
