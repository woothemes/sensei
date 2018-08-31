<?php
/**
 * Learner completed course email
 *
 * @author  Automattic
 * @package Sensei/Templates/Emails/HTML
 * @version 1.6.0
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php

// Get data for email content
global $sensei_email_data;
extract( $sensei_email_data );

// For gmail compatibility, including CSS styles in head/body are stripped out therefore styles need to be inline. These variables contain rules which are added to the template inline. !important; is a gmail hack to prevent styles being stripped if it doesn't like something.
$small = "text-align: center !important;";

$large = "text-align: center !important;font-size: 350% !important;line-height: 100% !important;";

?>

<?php do_action( 'sensei_before_email_content', $template ); ?>

<p style="<?php echo esc_attr( $small ); ?>"><?php printf( __( 'You have completed and %1$s the course', 'woothemes-sensei' ), $passed ); ?></p>

<h2 style="<?php echo esc_attr( $large ); ?>"><?php echo get_the_title( $course_id ); ?></h2>

<?php do_action( 'sensei_after_email_content', $template ); ?>
