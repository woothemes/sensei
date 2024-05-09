<?php

namespace SenseiTest\Internal\Emails;

use Sensei\Internal\Emails\Email_Customization;
use Sensei\Internal\Emails\Email_List_Table;
use Sensei\Internal\Emails\Email_Repository;
use Sensei\Internal\Emails\Email_Seeder;
use Sensei\Internal\Emails\Email_Seeder_Data;
use Sensei_Test_Login_Helpers;
use Sensei\Internal\Emails\Email_User_Profile_Settings;

/**
 * Tests for Sensei\Internal\Emails\Email_User_Profile_Settings.
 *
 * @covers \Sensei\Internal\Emails\Email_User_Profile_Settings
 */
class Email_User_Profile_Settings_Test extends \WP_UnitTestCase {
	use Sensei_Test_Login_Helpers;

	/**
	 * Class under test.
	 *
	 * @var Email_User_Profile_Settings
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

		$this->repository   = Email_Customization::instance()->repository;
		$this->subscription = Email_Customization::instance()->subscription;
		$this->instance     = new Email_User_Profile_Settings( $this->repository, $this->subscription );
		$this->list_table   = new Email_List_Table( $this->repository );
		$seeder             = new Email_Seeder( new Email_Seeder_Data(), $this->repository );

		$seeder->init();
		$seeder->create_all();
	}

	public function testInit_WhenCalled_AddsNecessaryHooks() {
		/* Act. */
		$this->instance->init();

		/* Assert. */
		$this->assertEquals( 10, has_action( 'show_user_profile', [ $this->instance, 'maybe_add_email_settings' ] ) );
		$this->assertEquals( 10, has_action( 'edit_user_profile', [ $this->instance, 'maybe_add_email_settings' ] ) );
		$this->assertEquals( 10, has_action( 'personal_options_update', [ $this->instance, 'save_email_settings' ] ) );
		$this->assertEquals( 10, has_action( 'edit_user_profile_update', [ $this->instance, 'save_email_settings' ] ) );
	}

	public function testMaybeAddEmailSettings_WhenCalled_AddsEmailSettingHeader() {
		/* Arrange. */
		$this->login_as_admin();

		/* Act. */
		$output = $this->get_email_setting_output( wp_get_current_user() );

		/* Assert. */
		$this->assertStringContainsString( '<h3>Sensei Email</h3>', $output );
	}

	public function testMaybeAddEmailSettings_WhenUserIsStudent_DoesNotShowTeacherEmailSettings() {
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
				$this->assertStringNotContainsString( 'value="' . $identifier . '"', $output, 'Teacher should not see unavailable email with ID - ' . $identifier );
			}
		}
	}

	public function testMaybeAddEmailSettings_WhenUserSeesProfileEvenAsAdmin_UnavailableEmailsAreNotShown() {
		/* Arrange. */
		$this->login_as_admin();
		$all_emails = $this->repository->get_all( null, -1 );
		$user       = wp_get_current_user();

		/* Act. */
		$output = $this->get_email_setting_output( $user );

		/* Assert. */
		foreach ( $all_emails->items as $email ) {
			$identifier = get_post_meta( $email->ID, '_sensei_email_identifier', true );

			if ( $this->list_table->is_email_available( $email ) ) {
				$this->assertStringContainsString( 'value="' . $identifier . '"', $output, 'User should see email with ID - ' . $identifier );
			} else {
				$this->assertStringNotContainsString( 'value="' . $identifier . '"', $output, 'User should not see email with ID - ' . $identifier );
			}
		}
	}

	public function testMaybeAddEmailSettings_WhenAllEmailsAreAvailable_ShowsAllEmails() {
		/* Arrange. */
		$this->login_as_admin();
		$all_emails = $this->repository->get_all( null, -1 );
		$user       = wp_get_current_user();
		add_filter( 'sensei_email_is_available', '__return_true' );

		/* Act. */
		$output = $this->get_email_setting_output( $user );

		/* Assert. */
		foreach ( $all_emails->items as $email ) {
			$identifier = get_post_meta( $email->ID, '_sensei_email_identifier', true );

			$this->assertStringContainsString( 'value="' . $identifier . '"', $output, 'User should see email with ID - ' . $identifier );
		}
	}

	public function testMaybeAddEmailSettings_WhenEmailIsUnsubscribed_RendersCheckboxAsUnchecked() {
		/* Arrange. */
		$this->login_as_student();
		$student_emails = $this->repository->get_all( 'student', -1 );
		$user           = wp_get_current_user();

		$available_email_identifiers = [];

		foreach ( $student_emails->items as $email ) {
			if ( ! $this->list_table->is_email_available( $email ) ) {
				continue;
			}

			$identifier                    = get_post_meta( $email->ID, '_sensei_email_identifier', true );
			$available_email_identifiers[] = $identifier;
		}

		update_user_meta( $user->ID, 'sensei_email_unsubscribed_' . $available_email_identifiers[0], 'yes' );

		/* Act. */
		$output = $this->get_email_setting_output( $user );

		/* Assert. */
		$this->assertStringContainsString( '<input name="sensei-email-subscriptions[]" type="checkbox" value="' . $available_email_identifiers[0] . '"', $output, 'Unsubscribed email should be unchecked' );
		$this->assertStringContainsString( '<input name="sensei-email-subscriptions[]" type="checkbox" value="' . $available_email_identifiers[1] . '"  checked=\'checked\'', $output, 'Subscribed Email should be checked' );
	}

	public function testSaveEmailSettings_WhenUserIsStudent_SavesEmailSettings() {
		/* Arrange. */
		$this->login_as_admin();
		$all_emails = $this->repository->get_all( null, -1 );
		$user       = wp_get_current_user();

		$available_email_identifiers = [];
		foreach ( $all_emails->items as $email ) {
			if ( ! $this->list_table->is_email_available( $email ) ) {
				continue;
			}

			$available_email_identifiers[] = get_post_meta( $email->ID, '_sensei_email_identifier', true );
		}

		$subscribed_email_identifiers        = [
			$available_email_identifiers[1],
			$available_email_identifiers[2],
		];
		$_POST['sensei-email-subscriptions'] = $subscribed_email_identifiers;

		/* Act. */
		$this->instance->save_email_settings( $user->ID );

		/* Assert. */
		foreach ( $available_email_identifiers as $identifier ) {
			if ( in_array( $identifier, $subscribed_email_identifiers, true ) ) {
				$this->assertTrue( $this->subscription->is_subscribed( $user->ID, $identifier ), 'Email with ID - ' . $identifier . ' should be subscribed' );
			} else {
				$this->assertFalse( $this->subscription->is_subscribed( $user->ID, $identifier ), 'Email with ID - ' . $identifier . ' should be unsubscribed' );
			}
		}

		/* Reset. */
		unset( $_POST['sensei-email-subscriptions'] );
	}

	private function get_email_setting_output( $user ) {
		ob_start();
		$this->instance->maybe_add_email_settings( $user );
		return ob_get_clean();
	}
}
