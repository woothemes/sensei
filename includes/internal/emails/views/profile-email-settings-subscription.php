<?php
/**
 * The template used to render a single email subscription setting in the user profile page.
 *
 * @package sensei
 * @since 1.24.0
 *
 * @var string $identifier The email identifier.
 * @var string $description The email description.
 * @var bool   $is_unsubscribed Whether the user is unsubscribed from the email.
 *
 * phpcs:disable WordPress.WP.EnqueuedResources, VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
 */

?>
<fieldset>
	<label for="<?php echo esc_attr( $this->get_field_id( $identifier ) ); ?>">
		<input name="sensei-email-subscriptions[]" type="checkbox" value="<?php echo esc_attr( $identifier ); ?>" <?php checked( false, $is_unsubscribed ); ?> id="<?php echo esc_attr( $this->get_field_id( $identifier ) ); ?>">
		<?php echo esc_html( $description ); ?>
	</label>
</fieldset>
