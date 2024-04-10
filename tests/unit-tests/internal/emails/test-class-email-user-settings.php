<?php

namespace SenseiTest\Internal\Emails;

use Sensei\Internal\Emails\Email_Repository;
use Sensei_Test_Login_Helpers;

/**
 * Tests for Sensei\Internal\Emails\Email_User_Settings.
 *
 * @covers \Sensei\Internal\Emails\Email_User_Settings
 */
class Email_User_Settings_Test extends \WP_UnitTestCase {
	use Sensei_Test_Login_Helpers;

	/**
	 * Factory for creating test data.
	 *
	 * @var Sensei_Factory
	 */
	protected $factory;

	/**
	 * Email repository instance.
	 *
	 * @var Email_Repository
	 */
	protected $email_repository;

	public function setUp(): void {
		parent::setUp();

		$this->email_repository = $this->createMock( Email_Repository::class );
	}

	public function testInit_WhenCalled_AddsNecessaryHooks() {
		/* Arrange. */
		$instance = new \Sensei\Internal\Emails\Email_User_Settings( $this->email_repository );

		/* Act. */
		$instance->init();

		/* Assert. */
		$this->assertEquals( 10, has_action( 'show_user_profile', [ $instance, 'add_opt_in_out_email_setting_fields_in_user_profile_page' ] ) );
		$this->assertEquals( 10, has_action( 'edit_user_profile', [ $instance, 'add_opt_in_out_email_setting_fields_in_user_profile_page' ] ) );

		$this->assertEquals( 10, has_action( 'personal_options_update', [ $instance, 'save_user_email_opt_in_out_settings' ] ) );
		$this->assertEquals( 10, has_action( 'edit_user_profile_update', [ $instance, 'save_user_email_opt_in_out_settings' ] ) );

		$this->assertEquals( 10, has_filter( 'sensei_send_emails', [ $instance, 'should_send_email_to_user' ] ) );
	}
}
