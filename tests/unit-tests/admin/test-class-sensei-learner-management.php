<?php
/**
 * Tests student management functionality (formerly known as Learner Management).
 *
 * @package sensei
 */

/**
 * Tests for Sensei_Learner_Management class.
 */
class Sensei_Learner_Management_Test extends WP_UnitTestCase {
	/**
	 * Sensei_Factory instance.
	 *
	 * @var Sensei_Factory
	 */
	protected $factory;

	/**
	 * Sensei_Learner_Management instance.
	 *
	 * @var Sensei_Learner_Management
	 */
	protected $learner_management;

	public function setUp(): void {
		parent::setUp();

		$this->factory            = new Sensei_Factory();
		$this->learner_management = new Sensei_Learner_Management( '' );
	}

	public function tearDown(): void {
		parent::tearDown();

		$this->factory->tearDown();
	}

	/**
	 * Tests that students cannot be added to a course if current user is not the teacher.
	 *
	 * @covers Sensei_Learner_Management::add_new_learners
	 */
	public function testAddNewLearners_ToCourseWhenCurrentUserIsNotTeacher_ReturnsFalse() {
		/* Arrange. */
		$teacher_id      = $this->factory->user->create();
		$student_id      = $this->factory->user->create();
		$current_user_id = $this->factory->user->create();

		wp_set_current_user( $current_user_id );

		$_POST['add_learner_submit'] = 'some_value';
		$_POST['add_learner_nonce']  = wp_create_nonce( 'add_learner_to_sensei' );
		$_POST['add_post_type']      = 'course';
		$_POST['add_user_id']        = [ $student_id ];
		$_POST['add_course_id']      = $this->factory->course->create( [ 'post_author' => $teacher_id ] );
		$_POST['add_lesson_id']      = 0;

		/* Act. */
		$result = $this->learner_management->add_new_learners();

		/* Assert. */
		$this->assertFalse( $result );
	}

	/**
	 * Tests that students cannot be added to a lesson if current user is not the teacher.
	 *
	 * @covers Sensei_Learner_Management::add_new_learners
	 */
	public function testAddNewLearners_ToLessonWhenCurrentUserIsNotTeacher_ReturnsFalse() {
		/* Arrange. */
		$teacher_id      = $this->factory->user->create();
		$student_id      = $this->factory->user->create();
		$current_user_id = $this->factory->user->create();
		$course_id       = $this->factory->course->create( [ 'post_author' => $teacher_id ] );
		$lesson_id       = $this->factory->lesson->create(
			[
				'meta_input' => [
					'_lesson_course' => $course_id,
				],
			]
		);

		wp_set_current_user( $current_user_id );

		$_POST['add_learner_submit'] = 'some_value';
		$_POST['add_learner_nonce']  = wp_create_nonce( 'add_learner_to_sensei' );
		$_POST['add_post_type']      = 'lesson';
		$_POST['add_user_id']        = [ $student_id ];
		$_POST['add_course_id']      = $course_id;
		$_POST['add_lesson_id']      = $lesson_id;

		/* Act. */
		$result = $this->learner_management->add_new_learners();

		/* Assert. */
		$this->assertFalse( $result );
	}
}
