<?php

namespace SenseiTest\Internal\Emails;

use Sensei\Internal\Emails\Email_Subscription;
use Sensei_Factory;

/**
 * Tests for Sensei\Internal\Emails\Email_Subscription.
 *
 * @covers \Sensei\Internal\Emails\Email_Subscription
 */
class Email_Subscription_Test extends \WP_UnitTestCase {
	/**
	 * Class under test.
	 *
	 * @var Email_Subscription
	 */
	protected $instance;

	public function setUp(): void {
		parent::setUp();

		$this->factory  = new Sensei_Factory();
		$this->instance = new Email_Subscription();
	}

	public function testInit_WhenCalled_AddsNecessaryHooks() {
		/* Act. */
		$this->instance->init();

		/* Assert. */
		$this->assertEquals( 10, has_filter( 'sensei_send_emails', [ $this->instance, 'send_email_if_user_is_subscribed' ] ) );
	}

	public function testSubscribe_WhenCalled_DeletesUnsubscribedUserMeta() {
		/* Arrange. */
		$user = $this->factory->user->create_and_get();

		update_user_meta( $user->ID, 'sensei_email_unsubscribed_test_email', 'yes' );

		/* Act. */
		$this->instance->subscribe( $user->ID, 'test_email' );

		/* Assert. */
		$this->assertEmpty( get_user_meta( $user->ID, 'sensei_email_unsubscribed_test_email', true ) );
	}

	public function testUnsubscribe_WhenCalled_UpdatesUserMeta() {
		/* Arrange. */
		$user = $this->factory->user->create_and_get();

		/* Act. */
		$this->instance->unsubscribe( $user->ID, 'test_email' );

		/* Assert. */
		$this->assertEquals( 'yes', get_user_meta( $user->ID, 'sensei_email_unsubscribed_test_email', true ) );
	}

	public function testIsSubscribed_WhenSubscribed_ReturnsTrue() {
		/* Arrange. */
		$user = $this->factory->user->create_and_get();

		$this->instance->subscribe( $user->ID, 'test_email' );

		/* Act. */
		$is_subscribed = $this->instance->is_subscribed( $user->ID, 'test_email' );

		/* Assert. */
		$this->assertTrue( $is_subscribed );
	}

	public function testIsSubscribed_WhenUnsubscribed_ReturnsFalse() {
		/* Arrange. */
		$user = $this->factory->user->create_and_get();

		$this->instance->unsubscribe( $user->ID, 'test_email' );

		/* Act. */
		$is_subscribed = $this->instance->is_subscribed( $user->ID, 'test_email' );

		/* Assert. */
		$this->assertFalse( $is_subscribed );
	}

	public function testSendEmailIfUserIsSubscribed_WhenUnsubscribed_ReturnsFalse() {
		/* Arrange. */
		$user = $this->factory->user->create_and_get();

		update_user_meta( $user->ID, 'sensei_email_unsubscribed_test_email', 'yes' );

		/* Act. */
		$should_send_email = $this->instance->send_email_if_user_is_subscribed( true, $user->user_email, '', '', 'test_email' );

		/* Assert. */
		$this->assertFalse( $should_send_email );
	}

	public function testSendEmailIfUserIsSubscribed_WhenSubscribed_ReturnsSameValueAsCalledWith() {
		/* Arrange. */
		$user = $this->factory->user->create_and_get();

		/* Act. */
		$should_send_email_called_true  = $this->instance->send_email_if_user_is_subscribed( true, $user->user_email, '', '', 'test_email' );
		$should_send_email_called_false = $this->instance->send_email_if_user_is_subscribed( false, $user->user_email, '', '', 'test_email' );

		/* Assert. */
		$this->assertTrue( $should_send_email_called_true, 'Should return true when called with true' );
		$this->assertFalse( $should_send_email_called_false, 'Should return false when called with false' );
	}
}
