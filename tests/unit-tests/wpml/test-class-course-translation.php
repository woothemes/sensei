<?php
namespace SenseiTest\WPML;

use Sensei\WPML\Course_Translation;
use Sensei_Factory;

/**
 * Class Course_Translation_Test
 *
 * @covers \Sensei\WPML\Course_Translation
 */
class Course_Translation_Test extends \WP_UnitTestCase {
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

	public function testUpdateLessonPropertiesOnCourseTranslationCreated_WhenCalled_CreatesLessonTranslations() {
		/* Arrange. */
		$new_course_id  = $this->factory->course->create();
		$new_lesson1_id = $this->factory->lesson->create();
		$new_lesson2_id = $this->factory->lesson->create();
		$old_course     = $this->factory->get_course_with_lessons( array( 'lesson_count' => 2 ) );

		$course_translation = new Course_Translation();

		$element_language_details_filter = function () {
			return array(
				'language_code'        => 'a',
				'source_language_code' => 'c',
			);
		};
		add_filter( 'wpml_element_language_details', $element_language_details_filter, 10, 2 );

		$object_id_fitler = function ( $object_id, $element_type ) use ( $new_course_id, $old_course ) {
			if ( $new_course_id === $object_id && 'course' === $element_type ) {
				return $old_course['course_id'];
			}

			return 0;
		};
		add_filter( 'wpml_object_id', $object_id_fitler, 10, 2 );

		$get_element_translations_filter = function () {
			return array();
		};
		add_filter( 'wpml_get_element_translations', $get_element_translations_filter, 10, 0 );

		$created_duplicates           = 0;
		$new_lessons_for_copy         = array( $new_lesson1_id, $new_lesson2_id );
		$copy_post_to_language_filter = function ( $post_id ) use ( &$created_duplicates, $old_course, &$new_lessons_for_copy ) {
			if ( in_array( $post_id, $old_course['lesson_ids'], true ) ) {
				++$created_duplicates;
			}
			return array_shift( $new_lessons_for_copy );
		};
		add_filter( 'wpml_copy_post_to_language', $copy_post_to_language_filter );

		$new_lessons_for_duplicates = array( $new_lesson1_id, $new_lesson2_id );
		$post_duplicates_filter     = function ( $post_id ) use ( &$new_lessons_for_duplicates, $old_course ) {
			if ( in_array( $post_id, $old_course['lesson_ids'], true ) ) {
				$lesson_id = array_shift( $new_lessons_for_duplicates );
				return array(
					'a' => $lesson_id,
				);
			}

			return array();
		};
		add_filter( 'wpml_post_duplicates', $post_duplicates_filter );

		/* Act. */
		$course_translation->update_lesson_properties_on_course_translation_created( $new_course_id );

		/* Clean up & Assert. */
		remove_filter( 'wpml_element_language_details', $element_language_details_filter );
		remove_filter( 'wpml_object_id', $object_id_fitler );
		remove_filter( 'wpml_get_element_translations', $get_element_translations_filter );
		remove_filter( 'wpml_copy_post_to_language', $copy_post_to_language_filter );
		remove_filter( 'wpml_post_duplicates', $post_duplicates_filter );

		$this->assertSame( 2, $created_duplicates );

		$expected = array( $new_course_id, $new_course_id );
		$actual   = array(
			(int) get_post_meta( $new_lesson1_id, '_lesson_course', true ),
			(int) get_post_meta( $new_lesson2_id, '_lesson_course', true ),
		);
		$this->assertSame( $expected, $actual, 'Lesson course should be set to the new course in lesson translations' );
	}
}
