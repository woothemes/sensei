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
		add_action( 'show_user_profile', [ $this, 'add_opt_in_out_setting_fields_in_user_profile_page' ] );
		add_action( 'edit_user_profile', [ $this, 'add_opt_in_out_setting_fields_in_user_profile_page' ] );
	}

	/**
	 * Add opt-in/out setting fields in user profile page.
	 *
	 * @internal
	 */
	public function add_opt_in_out_setting_fields_in_user_profile_page() {
		$show_teacher_emails = current_user_can( 'manage_options' ) || \Sensei_Teacher::is_a_teacher( get_current_user_id() );
		$all_emails          = $this->repository->get_all( $show_teacher_emails ? null : 'student', -1 );
		$list_table_instance = new Email_List_Table( $this->repository );
		$available_emails    = array_filter(
			$all_emails->items,
			function ( $email ) use ( $list_table_instance ) {
				return 'publish' === $email->post_status
					&& $list_table_instance->is_email_available( $email );
			}
		);

		?>
			<h3><?php esc_html_e( 'Sensei Email Subscriptions', 'sensei-lms' ); ?></h3>

			<table class="form-table">
				<?php
				foreach ( $available_emails as $email ) {
					if ( 'publish' !== $email->post_status ) {
						continue;
					}
					?>
						<tr>
							<th scope="row">
								<?php echo esc_html( get_post_meta( $email->ID, '_sensei_email_description', true ) ); ?>
							</th>
							<td>
								<label for="<?php esc_attr( $email->ID ); ?>">
									<input name="<?php esc_attr( $email->ID ); ?>" type="checkbox" id="<?php esc_attr( $email->ID ); ?>" value="1" checked="checked">
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
}

