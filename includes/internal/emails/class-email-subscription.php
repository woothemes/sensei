<?php
/**
 * File containing the Email_Subscription class.
 *
 * @package sensei
 */

namespace Sensei\Internal\Emails;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class responsible for the email subscription.
 *
 * @internal
 *
 * @since $$next-version$$
 */
class Email_Subscription {

	/**
	 * Email identifier meta key.
	 *
	 * @var string
	 */
	private const META_PREFIX_UNSUBSCRIBED = 'sensei_email_unsubscribed_';

	/**
	 * Initialize the class and add hooks.
	 *
	 * @internal
	 */
	public function init(): void {
		add_filter( 'sensei_send_emails', [ $this, 'send_email_if_user_is_subscribed' ], 10, 5 );
	}

	/**
	 * Subscribe a user to an email.
	 *
	 * @internal
	 *
	 * @param int    $user_id          The user ID.
	 * @param string $email_identifier The email identifier.
	 */
	public function subscribe( int $user_id, string $email_identifier ): void {
		delete_user_meta( $user_id, self::META_PREFIX_UNSUBSCRIBED . $email_identifier );
	}

	/**
	 * Unsubscribe a user from an email.
	 *
	 * @internal
	 *
	 * @param int    $user_id          The user ID.
	 * @param string $email_identifier The email identifier.
	 */
	public function unsubscribe( int $user_id, string $email_identifier ): void {
		update_user_meta( $user_id, self::META_PREFIX_UNSUBSCRIBED . $email_identifier, 'yes' );
	}

	/**
	 * Check if a user is subscribed to an email.
	 *
	 * @internal
	 *
	 * @param int    $user_id          The user ID.
	 * @param string $email_identifier The email identifier.
	 *
	 * @return bool
	 */
	public function is_subscribed( int $user_id, string $email_identifier ): bool {
		return 'yes' !== get_user_meta( $user_id, self::META_PREFIX_UNSUBSCRIBED . $email_identifier, true );
	}

	/**
	 * Only send an email if the user is subscribed to it.
	 *
	 * @intenal
	 *
	 * @param bool   $should_send Whether the email should be sent.
	 * @param string $user_email  The user email.
	 * @param string $subject     The email subject.
	 * @param string $message     The email message.
	 * @param string $identifier  The email identifier.
	 *
	 * @return bool
	 */
	public function send_email_if_user_is_subscribed( $should_send, $user_email, $subject, $message, $identifier ) {
		$user_id = email_exists( $user_email );

		if ( false === $user_id || ! $should_send ) {
			return $should_send;
		}

		return $this->is_subscribed( $user_id, $identifier );
	}
}
