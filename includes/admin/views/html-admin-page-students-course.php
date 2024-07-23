<?php
/**
 * Students page course view.
 *
 * This view displays the students data in a course context.
 *
 * @package sensei-lms
 * @since 4.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$sensei_list_table = new Sensei_Learners_Main();
$sensei_list_table->prepare_items();

/**
 * Fires before rendering the students container.
 *
 * @hook learners_before_container
 */
do_action( 'learners_before_container' );

/**
 * Fires before rendering the students page container.
 * This hook allows to wrap the container.
 *
 * @hook learners_wrapper_container
 *
 * @param {string} $position The position of the container ('top' here).
 */
do_action( 'learners_wrapper_container', 'top' );

Sensei()->learners->learners_headers();
?>
<?php $sensei_list_table->views(); ?>
<form id="learners-filter" method="get">
	<?php Sensei_Utils::output_query_params_as_inputs( [ 's' ] ); ?>
	<?php $sensei_list_table->table_search_form(); ?>
</form>
<?php $sensei_list_table->display(); ?>
<?php
/**
 * Fires after rendering the students page container.
 * This hook allows to wrap the container.
 *
 * @hook learners_wrapper_container
 *
 * @param {string} $position The position of the container ('bottom' here).
 */
do_action( 'learners_wrapper_container', 'bottom' );

/**
 * Fires after rendering the students container.
 *
 * @hook learners_after_container
 */
do_action( 'learners_after_container' );
