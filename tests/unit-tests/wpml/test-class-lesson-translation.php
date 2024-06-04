<?php
namespace SenseiTest\WPML;

use Sensei\WPML\Lesson_Translation;
use Sensei_Factory;

/**
 * Class Lesson_Translation_Test
 *
 * @covers \Sensei\WPML\Lesson_Translation
 */
class Lesson_Translation_Test extends \WP_UnitTestCase {
	/**
	 * Sensei Factory.
	 *
	 * @var Sensei_Factory
	 */
	protected $factory;

	public function set_up(): void {
		parent::set_up();
		$this->factory = new Sensei_Factory();
	}

	public function tear_down(): void {
		parent::tear_down();
		$this->factory->tearDown();
	}

	public function testUpdateLessonTranslationsOnLessonTranslationCreated_WhenCalled_CreatesLessonTranslations() {
		/* Arrange. */
		$question_category   = $this->factory->question_category->create_and_get();
		$course_with_lessons = $this->factory->get_course_with_lessons(
			array(
				'lesson_count'            => 1,
				'question_count'          => 3,
				'multiple_question_count' => 1,
				'multiple_question_args'  => array(
					'question_category_id' => $question_category->term_id,
				),
			)
		);
		$this->factory->question->create_many(
			3,
			array(
				'quiz_id'           => $course_with_lessons['quiz_ids'][0],
				'question_category' => $question_category->term_id,
			)
		);

		$new_course_id = $this->factory->course->create();
		$new_lesson_id = $this->factory->lesson->create();

		$lesson_translation = new Lesson_Translation();

		$element_language_details_filter = function () {
			return array(
				'language_code'        => 'a',
				'source_language_code' => 'c',
			);
		};
		add_filter( 'wpml_element_language_details', $element_language_details_filter, 10, 0 );

		$object_id_fitler = function ( $object_id, $element_type ) use ( $new_lesson_id, $new_course_id, $course_with_lessons ) {
			if ( $new_lesson_id === $object_id && 'lesson' === $element_type ) {
				return $course_with_lessons['lesson_ids'][0];
			}

			if ( $course_with_lessons['course_id'] === $object_id && 'course' === $element_type ) {
				return $new_course_id;
			}

			return 0;
		};
		add_filter( 'wpml_object_id', $object_id_fitler, 10, 2 );

		$created_duplicates           = array();
		$copy_post_to_language_filter = function ( $id ) use ( &$created_duplicates ) {
			$created_duplicates[] = $id;
			return 1;
		};
		add_filter( 'wpml_copy_post_to_language', $copy_post_to_language_filter );

		$post_duplicates_filter = function ( $post_id ) use ( $new_lesson_id, $course_with_lessons ) {
			if ( $post_id === $course_with_lessons['lesson_ids'][0] ) {
				return array(
					'a' => $new_lesson_id,
				);
			}

			return array();
		};

		add_filter( 'wpml_post_duplicates', $post_duplicates_filter, 10, 1 );

		/* Act. */
		$lesson_translation->update_lesson_translations_on_lesson_translation_created( $new_lesson_id );

		/* Clean up & Assert. */
		remove_filter( 'wpml_element_language_details', $element_language_details_filter );
		remove_filter( 'wpml_object_id', $object_id_fitler );
		remove_action( 'wpml_copy_post_to_language', $copy_post_to_language_filter );
		remove_filter( 'wpml_post_duplicates', $post_duplicates_filter );

		// 1 lesson, 3 questions, 1 multiple question, 3 questions inside the multiple question.
		$actual = count( array_unique( $created_duplicates ) );
		$this->assertSame( 8, $actual );
	}
}
