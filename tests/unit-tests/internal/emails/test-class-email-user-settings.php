<?php

namespace SenseiTest\Internal\Emails;

use Sensei\Internal\Emails\Email_Customization;
use Sensei\Internal\Emails\Email_List_Table;
use Sensei\Internal\Emails\Email_Repository;
use Sensei\Internal\Emails\Email_Seeder;
use Sensei\Internal\Emails\Email_Seeder_Data;
use Sensei_Test_Login_Helpers;
use Sensei\Internal\Emails\Email_User_Settings;

/**
 * Tests for Sensei\Internal\Emails\Email_User_Settings.
 *
 * @covers \Sensei\Internal\Emails\Email_User_Settings
 */
class Email_User_Settings_Test extends \WP_UnitTestCase {
	use Sensei_Test_Login_Helpers;

	/**
	 * Class under test.
	 *
	 * @var Email_User_Settings
	 */
	protected $instance;

	/**
	 * Email repository instance.
	 *
	 * @var Email_Repository
	 */
	protected $repository;

	/**
	 * List table instance.
	 *
	 * @var Email_List_Table
	 */
	protected $list_table;

	public function setUp(): void {
		parent::setUp();

		$this->repository = Email_Customization::instance()->repository;
		$this->instance   = new Email_User_Settings( $this->repository );
		$this->list_table = new Email_List_Table( $this->repository );
		$seeder           = new Email_Seeder( new Email_Seeder_Data(), $this->repository );

		$seeder->init();
		$seeder->create_all();
	}

	public function testInit_WhenCalled_AddsNecessaryHooks() {
		/* Act. */
		$this->instance->init();

		/* Assert. */
		$this->assertEquals( 10, has_action( 'show_user_profile', [ $this->instance, 'maybe_add_email_settings' ] ) );
		$this->assertEquals( 10, has_action( 'edit_user_profile', [ $this->instance, 'maybe_add_email_settings' ] ) );

		$this->assertEquals( 10, has_action( 'personal_options_update', [ $this->instance, 'save_user_email_opt_in_out_settings' ] ) );
		$this->assertEquals( 10, has_action( 'edit_user_profile_update', [ $this->instance, 'save_user_email_opt_in_out_settings' ] ) );

		$this->assertEquals( 10, has_filter( 'sensei_send_emails', [ $this->instance, 'should_send_email_to_user' ] ) );
	}

	public function testAddOptInOutEmailSettingFieldsInUserProfilePage_WhenCalled_AddsOptInOutEmailSettingFields() {
		/* Arrange. */
		$this->login_as_admin();

		/* Act. */
		$output = $this->get_email_setting_output( wp_get_current_user() );

		/* Assert. */
		$this->assertStringContainsString( 'Sensei Email Subscriptions', $output );
	}

	public function testMaybeAddEmailSettings_WhenUserIsStudent_DoesNotShowTeacherEmails() {
		/* Arrange. */
		$this->login_as_student();
		$teacher_emails = $this->repository->get_all( 'teacher', -1 );
		$user           = wp_get_current_user();

		/* Act. */
		$output = $this->get_email_setting_output( $user );

		/* Assert. */
		foreach ( $teacher_emails->items as $email ) {
			$identifier = get_post_meta( $email->ID, '_sensei_email_identifier', true );
			$this->assertStringNotContainsString( 'value="' . $identifier . '"', $output, 'Student should not see email with ID - ' . $identifier );
		}
	}

	public function testMaybeAddEmailSettings_WhenUserIsStudent_SeesAllAvailableStudentEmails() {
		/* Arrange. */
		$this->login_as_student();
		$student_emails = $this->repository->get_all( 'student', -1 );
		$user           = wp_get_current_user();

		/* Act. */
		$output = $this->get_email_setting_output( $user );

		/* Assert. */
		$this->assertStringContainsString( 'name="sensei-email-subscriptions[]"', $output );
		foreach ( $student_emails->items as $email ) {
			$identifier = get_post_meta( $email->ID, '_sensei_email_identifier', true );

			if ( $this->list_table->is_email_available( $email ) ) {
				$this->assertStringContainsString( 'value="' . $identifier . '"', $output, 'Student should see email with ID - ' . $identifier );
			} else {
				$this->assertStringNotContainsString( 'value="' . $identifier . '"', $output, 'Student should not see unavailable email with ID - ' . $identifier );
			}
		}
	}

	public function testMaybeAddEmailSettings_WhenUserIsTeacher_ShowsAllAvailableEmails() {
		/* Arrange. */
		$this->login_as_teacher();
		$teacher_emails = $this->repository->get_all( null, -1 );
		$user           = wp_get_current_user();

		/* Act. */
		$output = $this->get_email_setting_output( $user );

		/* Assert. */
		$this->assertStringContainsString( 'name="sensei-email-subscriptions[]"', $output );
		foreach ( $teacher_emails->items as $email ) {
			$identifier = get_post_meta( $email->ID, '_sensei_email_identifier', true );

			if ( $this->list_table->is_email_available( $email ) ) {
				$this->assertStringContainsString( 'value="' . $identifier . '"', $output, 'Teacher should see email with ID - ' . $identifier );
			} else {
				$this->assertStringNotContainsString( 'value="' . $identifier . '"', $output, 'Teacher should not see email with ID - ' . $identifier );
			}
		}
	}

	private function get_email_setting_output( $user ) {
		ob_start();
		$this->instance->maybe_add_email_settings( $user );
		return ob_get_clean();
	}
}
