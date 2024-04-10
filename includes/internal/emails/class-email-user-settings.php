<?php
/**
 * File containing the Email_User_Settings class.
 *
 * @package sensei
 */

namespace Sensei\Internal\Emails;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Email_User_Settings
 *
 * @internal
 *
 * @since $$next-version$$
 */
class Email_User_Settings {

	/**
	 * The Email_Repository instance.
	 *
	 * @var Email_Repository
	 */
	private $repository;

	/**
	 * The constructor.
	 *
	 * @internal
	 *
	 * @param Email_Repository $repository The Email_Repository instance.
	 */
	public function __construct( Email_Repository $repository ) {
		$this->repository = $repository;
	}

	/**
	 * Initialize the class and add hooks.
	 *
	 * @internal
	 */
	public function init() {
		// Show the opt-in/out email settings in the user profile page.
		add_action( 'show_user_profile', [ $this, 'maybe_add_email_settings' ] );
		add_action( 'edit_user_profile', [ $this, 'maybe_add_email_settings' ] );

		// Save the user email opt-in/out settings.
		add_action( 'personal_options_update', [ $this, 'save_user_email_opt_in_out_settings' ] );
		add_action( 'edit_user_profile_update', [ $this, 'save_user_email_opt_in_out_settings' ] );

		// Filter to determine if an email should be sent to a user.
		add_filter( 'sensei_send_emails', [ $this, 'should_send_email_to_user' ], 10, 5 );
	}

	/**
	 * Save user email opt-in/out settings.
	 *
	 * @internal
	 *
	 * @param int $user_id The user ID.
	 */
	public function save_user_email_opt_in_out_settings( $user_id ) {
		if ( ! current_user_can( 'edit_user', $user_id ) ) {
			return false;
		}

		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.NonceVerification.Missing -- Nonce verified before hook is called, Input is sanitized in the next lines.
		$opt_in_out_emails = $_POST['sensei-email-subscriptions'] ?? [];

		if ( ! is_array( $opt_in_out_emails ) ) {
			return;
		}

		$opt_in_out_emails = array_map( 'wp_unslash', $opt_in_out_emails );
		$opt_in_out_emails = array_map( 'sanitize_text_field', $opt_in_out_emails );

		$user_emails = $this->get_emails_for_user( $user_id );

		foreach ( $user_emails as $identifier => $email ) {
			$should_subscribe = in_array( $identifier, $opt_in_out_emails, true );

			if ( $should_subscribe ) {
				delete_user_meta( $user_id, 'sensei_email_unsubscribed_' . $identifier );
			} else {
				update_user_meta( $user_id, 'sensei_email_unsubscribed_' . $identifier, 'yes' );
			}
		}
	}

	/**
	 * Add opt-in/out setting fields in user profile page.
	 *
	 * @param \WP_User $profile_user The user object.
	 *
	 * @internal
	 */
	public function maybe_add_email_settings( $profile_user ) {
		$user_emails = $this->get_emails_for_user( $profile_user->ID );

		if ( empty( $user_emails ) ) {
			return;
		}

		?>
			<h3><?php esc_html_e( 'Sensei Email Subscriptions', 'sensei-lms' ); ?></h3>

			<table class="form-table">
				<?php
				foreach ( $user_emails as $identifier => $email ) {
					if ( 'publish' !== $email->post_status ) {
						continue;
					}
					$is_unsubscribed = get_user_meta( $profile_user->ID, 'sensei_email_unsubscribed_' . $identifier, true );
					?>
						<tr>
							<th scope="row">
								<?php echo esc_html( get_post_meta( $email->ID, '_sensei_email_description', true ) ); ?>
							</th>
							<td>
								<label for="<?php esc_attr( $email->ID ); ?>">
									<input name="sensei-email-subscriptions[]" type="checkbox" value="<?php echo esc_attr( $identifier ); ?>" <?php checked( false, $is_unsubscribed ); ?>>
									<?php echo esc_html( $email->post_title ); ?>
								</label>
							</td>
						</tr>
					<?php
				}
				?>
			</table>
		<?php
	}

	/**
	 * Get emails for a user.
	 *
	 * @param int $user_id The user ID.
	 *
	 * @return array
	 */
	private function get_emails_for_user( $user_id ) {
		$show_teacher_emails = user_can( $user_id, 'manage_sensei_grades' ) || \Sensei_Teacher::is_a_teacher( $user_id );
		$all_emails          = $this->repository->get_all( $show_teacher_emails ? null : 'student', -1 );
		$list_table_instance = new Email_List_Table( $this->repository );

		$available_emails = [];

		foreach ( $all_emails->items as $email ) {
			if ( 'publish' === $email->post_status && $list_table_instance->is_email_available( $email ) ) {
				$identifier                      = get_post_meta( $email->ID, '_sensei_email_identifier', true );
				$available_emails[ $identifier ] = $email;
			}
		}

		return $available_emails;
	}

	/**
	 * Check if an email should be sent to a user.
	 *
	 * @param bool   $should_send Whether the email should be sent.
	 * @param string $user_email  The user email.
	 * @param string $subject     The email subject.
	 * @param string $message      The email message.
	 * @param string $identifier   The email identifier.
	 *
	 * @return bool
	 */
	public function should_send_email_to_user( $should_send, $user_email, $subject, $message, $identifier ) {
		$user_id = email_exists( $user_email );

		if ( false === $user_id || ! $should_send ) {
			return $should_send;
		}

		return ! ( 'yes' === get_user_meta( $user_id, 'sensei_email_unsubscribed_' . $identifier, true ) );
	}
}
