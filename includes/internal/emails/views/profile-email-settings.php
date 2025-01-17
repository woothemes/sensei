<?php
/**
 * The template used to render the email settings in the user profile page.
 *
 * @package sensei
 * @since 1.24.0
 *
 * @var array user_emails The user emails.
 *
 * phpcs:disable WordPress.WP.EnqueuedResources, VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable, WordPress.WP.GlobalVariablesOverride.Prohibited
 */

?>
<h3><?php esc_html_e( 'Sensei Email', 'sensei-lms' ); ?></h3>

<table class="form-table">
	<?php
	foreach ( $user_emails as $type => $emails ) {
		?>
	<tr>
		<th scope="row">
			<?php
			switch ( $type ) {
				case 'student':
					esc_html_e( 'Student Emails', 'sensei-lms' );
					break;
				case 'teacher':
					esc_html_e( 'Teacher Emails', 'sensei-lms' );
					break;
				default:
					esc_html_e( 'Other Emails', 'sensei-lms' );
					break;
			}
			?>
		</th>
		<td>
			<?php
			foreach ( $emails as $identifier => $email ) {
				$this->render_email_subscription_setting( $user->ID, $identifier, $email );
			}
			?>
		</td>
	</tr>
		<?php
	}
	?>
</table>
