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
		$all_emails = $this->repository->get_all( null, -1 );

		?>
			<h3><?php esc_html_e( 'Sensei Emails', 'sensei-lms' ); ?></h3>

			<table class="form-table">
				<?php
				foreach ( $all_emails->items as $email ) {
					?>
						<tr>
							<th scope="row">
								<?php echo esc_html( get_post_meta( $email->ID, '_sensei_email_description', true ) ); ?>
							</th>
							<td>
								<label for="admin_bar_front">
									<input name="admin_bar_front" type="checkbox" id="admin_bar_front" value="1" checked="checked">
									<?php echo esc_attr( $email->post_title ); ?>"><?php echo esc_html( $email->post_title ); ?>
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

