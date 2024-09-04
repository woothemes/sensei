<?php

/**
 * This file contains the Quiz_Actions_Test class.
 *
 * @package sensei
 */

 use Sensei\Blocks\Course_Theme\Quiz_Actions;

 /**
  * Tests for Quiz_Actions class.
  * @covers Sensei\Blocks\Course_Theme\Quiz_Actions
  */
class Quiz_Actions_Test extends WP_UnitTestCase {

	use Sensei_Course_Enrolment_Test_Helpers;
	use Sensei_Course_Enrolment_Manual_Test_Helpers;

	/**
	 * Sensei factory instance.
	 *
	 * @var $factory
	 */
	protected $factory;

	public function setUp(): void {
		parent::setUp();

		$this->factory = new Sensei_Factory();

		self::resetEnrolmentProviders();
	}

	public function tearDown(): void {
		parent::tearDown();
		$this->factory->tearDown();

		// Remove all lessons.
		$lessons = get_posts( 'post_type=lesson' );
		foreach ( $lessons as $index => $lesson ) {
			wp_delete_post( $lesson->ID, true );
		}

		// Remove all quizzes.
		$quizzes = get_posts( 'post_type=quiz' );
		foreach ( $quizzes as $index => $quiz ) {
			wp_delete_post( $quiz->ID, true );
		}
		WP_Block_Supports::$block_to_render = null;

		self::resetEnrolmentProviders();
	}

	public function testRender_WhenPaginationIsShown_AlwaysRendersQuizCompleteButton() {
		/* Arrange */
		global $post, $sensei_question_loop;

		$this->addEnrolmentProvider( Sensei_Test_Enrolment_Provider_Always_Provides::class );
		$this->prepareEnrolmentManager();

		$user_id = $this->factory->user->create();
		wp_set_current_user( $user_id );

		$course_id = $this->factory->course->create();
		$lesson_id = $this->factory->lesson->create(
			[
				'meta_input' => [
					'_lesson_course' => $course_id,
				],
			]
		);
		$quiz_id   = $this->factory->maybe_create_quiz_for_lesson( $lesson_id );
		$post      = get_post( $quiz_id );

		$this->factory->question->create_many( 10, [ 'quiz_id' => $quiz_id ] );

		$course_enrolment = Sensei_Course_Enrolment::get_course_instance( $course_id );
		$course_enrolment->enrol( $user_id );

		update_post_meta(
			$quiz_id,
			'_pagination',
			wp_json_encode( [ 'pagination_number' => 2 ] )
		);

		$this->go_to( get_permalink( $quiz_id ) );

		WP_Block_Supports::$block_to_render = [
			'attrs'     => [],
			'blockName' => 'sensei-lms/quiz-actions',
		];

		Sensei_Quiz::start_quiz_questions_loop();

		/* Act */
		$result_for_other_pages               = ( new Quiz_Actions() )->render();
		$sensei_question_loop['current_page'] = $sensei_question_loop['total_pages'];
		$result_for_last_page                 = ( new Quiz_Actions() )->render();

		/* Assert */
		$this->assertStringContainsString( 'sensei-item-no-display', $result_for_other_pages );
		$this->assertStringNotContainsString( 'sensei-item-no-display', $result_for_last_page );
	}
}
