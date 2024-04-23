<?php
/**
 * File containing the Email_User_Profile_Settings class.
 *
 * @package sensei
 */

namespace Sensei\Internal\Emails;

use Sensei_Teacher;
use WP_Post;
use WP_User;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Email_User_Profile_Settings
 *
 * @internal
 *
 * @since $$next-version$$
 */
class Email_User_Profile_Settings {

	/**
	 * The Email_Repository instance.
	 *
	 * @var Email_Repository
	 */
	private Email_Repository $repository;

	/**
	 * The Email_Subscription instance.
	 *
	 * @var Email_Subscription
	 */
	private Email_Subscription $subscription;

	/**
	 * The constructor.
	 *
	 * @internal
	 *
	 * @param Email_Repository   $repository The Email_Repository instance.
	 * @param Email_Subscription $subscription The Email_Subscription instance.
	 */
	public function __construct( Email_Repository $repository, Email_Subscription $subscription ) {
		$this->repository   = $repository;
		$this->subscription = $subscription;
	}

	/**
	 * Initialize the class and add hooks.
	 *
	 * @internal
	 */
	public function init(): void {
		// Show the email settings in the user profile page.
		add_action( 'show_user_profile', [ $this, 'maybe_add_email_settings' ] );
		add_action( 'edit_user_profile', [ $this, 'maybe_add_email_settings' ] );

		// Save the user email settings.
		add_action( 'personal_options_update', [ $this, 'save_email_settings' ] );
		add_action( 'edit_user_profile_update', [ $this, 'save_email_settings' ] );
	}

	/**
	 * Save user email settings.
	 *
	 * @internal
	 *
	 * @param int $user_id The user ID.
	 */
	public function save_email_settings( $user_id ): void {
		if ( ! current_user_can( 'edit_user', $user_id ) ) {
			return;
		}

		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.NonceVerification.Missing -- Nonce verified before hook is called, Input is sanitized in the next lines.
		$email_subscriptions = $_POST['sensei-email-subscriptions'] ?? [];
		if ( ! is_array( $email_subscriptions ) ) {
			return;
		}

		$email_subscriptions = array_map( 'wp_unslash', $email_subscriptions );
		$email_subscriptions = array_map( 'sanitize_text_field', $email_subscriptions );

		$user_emails = $this->get_emails_for_user( $user_id );

		foreach ( $user_emails as $identifier => $email ) {
			$should_subscribe = in_array( $identifier, $email_subscriptions, true );

			$should_subscribe
				? $this->subscription->subscribe( $user_id, $identifier )
				: $this->subscription->unsubscribe( $user_id, $identifier );
		}
	}

	/**
	 * Add email setting fields in user profile page.
	 *
	 * @internal
	 *
	 * @param WP_User $user The user object.
	 */
	public function maybe_add_email_settings( WP_User $user ): void {
		$user_emails = $this->get_emails_for_user_grouped_by_type( $user->ID );

		if ( empty( $user_emails ) ) {
			return;
		}

		include __DIR__ . '/views/profile-email-settings.php';
	}

	/**
	 * Render email subscription setting.
	 *
	 * @internal
	 *
	 * @param int     $user_id    The user ID.
	 * @param string  $identifier The email identifier.
	 * @param WP_Post $email      The email post object.
	 */
	private function render_email_subscription_setting( int $user_id, string $identifier, WP_Post $email ): void {
		$is_unsubscribed = ! $this->subscription->is_subscribed( $user_id, $identifier );
		$description     = (string) get_post_meta( $email->ID, '_sensei_email_description', true );

		include __DIR__ . '/views/profile-email-settings-subscription.php';
	}

	/**
	 * Get the input field ID.
	 *
	 * @param string $field_name The input field name.
	 *
	 * @return string
	 */
	private function get_field_id( string $field_name ): string {
		return 'sensei-' . sanitize_key( $field_name );
	}

	/**
	 * Get emails for a user.
	 *
	 * @param int $user_id The user ID.
	 *
	 * @return array<string, WP_Post>
	 */
	private function get_emails_for_user( int $user_id ): array {
		$show_teacher_emails = user_can( $user_id, 'manage_sensei_grades' ) || Sensei_Teacher::is_a_teacher( $user_id );
		$all_emails          = $this->repository->get_all( $show_teacher_emails ? null : 'student', -1 );
		$list_table_instance = new Email_List_Table( $this->repository );

		$available_emails = [];

		foreach ( $all_emails->items as $email ) {
			if (
				'publish' === $email->post_status
				&& $list_table_instance->is_email_available( $email )
			) {
				$identifier                      = (string) get_post_meta( $email->ID, '_sensei_email_identifier', true );
				$available_emails[ $identifier ] = $email;
			}
		}

		return $available_emails;
	}

	/**
	 * Get emails for a user grouped by type.
	 *
	 * @param int $user_id The user ID.
	 *
	 * @return array<string, array<string, WP_Post>>
	 */
	private function get_emails_for_user_grouped_by_type( int $user_id ): array {
		$emails = $this->get_emails_for_user( $user_id );

		$grouped_emails = [];
		foreach ( $emails as $identifier => $email ) {
			$type                                   = (string) get_post_meta( $email->ID, '_sensei_email_type', true );
			$grouped_emails[ $type ][ $identifier ] = $email;
		}

		return $grouped_emails;
	}
}
