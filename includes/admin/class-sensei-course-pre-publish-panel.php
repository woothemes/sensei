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
		/**
		 * When Saving/Publishing a Course from the Course editor, usually three primary network calls are made from the Gutenberg editor serially -
		 *
		 * 1. The first call is initiated automatically by GB the moment we click the Publish/Update button, it saves the whole block `markup` of the course, but doesn't save the structure of the course, so no lessons or modules.
		 * 2. After first call is successful, Sensei explicitly makes a second call to save the structure of the course, i.e. the lessons and modules.
		 * 3. After the second call is successful, Sensei triggers the Post save call again, similar to the first call.
		 *
		 * When we click on the Publish button for a new Course containing new unsaved lessons, the first call (1) publishes the Draft course, new lessons are not saved yet.
		 * So if we try to find lessons under this Course at this point with this `publish_course` hook, we'll only get existing lessons, not the new ones.
		 *
		 * The second call (2) saves the new lessons and modules of the course. This is not a Post save/publish call, so it doesn't trigger the `publish_course` hook.
		 *
		 * When the second call is successful, Sensei triggers the Post save call again, which invokes this `publish_course`. By this time, the new lessons are saved and we can find and publish them.
		 * An important point is `publish_course` hook is invoked even when 'Update' is clicked on an already published course. So before publishing the lessons using this hook, we need to check if the course is being published or just being updated.
		 */
		if ( ! current_user_can( 'publish_post', $course_id ) ) {
			return;
		}

		// In parallel to the 3 calls mentioned above, GB also initiates some calls to save metabox data, they also trigger this hook. But we don't want to process anything for them.
		$uri                  = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
		$is_metabox_save_call = strpos( $uri, 'meta-box-loader=1' ) > 0;

		if ( $is_metabox_save_call ) {
			return;
		}

		$publish_lessons = get_post_meta( $course_id, 'sensei_course_publish_lessons', true );

		if ( ! $publish_lessons ) {
			return;
		}

		$publishing_meta_key = '_sensei_course_publishing_started';

		// This is how we can determine if the current call is the main publish call or not. This should be true when call (1) is made to publish the course.
		$is_main_publish_call = 'publish' !== $old_status;

		if ( $is_main_publish_call ) {
			// If it's the main publish call, we set this flag to use in the call (3) which will come later to determine if the call is made as part of the publishing sequence or just a normal update sequence.
			update_post_meta( $course_id, $publishing_meta_key, true );
			// We don't return early here, because we still need to publish the lessons, in case we are publishing a course with existing draft lessons.
		}

		$is_publishing_started = get_post_meta( $course_id, $publishing_meta_key, true );

		if ( ! $is_main_publish_call && ! $is_publishing_started ) {
			// If it's not the main publish call and the flag is not set, then it's just an update call sequence, We don't publish anything in normal update sequence, so we return early.
			return;
		}

		if ( ! $is_main_publish_call ) {
			// If it has reached here, this means it is call (3), end of the sequence has been reached by the Publishing cycle. So we just delete the flag.
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
