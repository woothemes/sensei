<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Sensei Analysis Lesson List Table Class
 *
 * All functionality pertaining to the Admin Analysis Lesson Data Table in Sensei.
 *
 * @package WordPress
 * @subpackage Sensei
 * @category Core
 * @author WooThemes
 * @since 1.2.0
 *
 * TABLE OF CONTENTS
 *
 * - __construct()
 * - get_columns()
 * - get_sortable_columns()
 * - prepare_items()
 * - single_row()
 * - generate_report()
 * - get_row_data()
 * - get_course_statuses()
 * - no_items()
 * - data_table_header()
 * - data_table_footer()
 * - search_button()
 */
class WooThemes_Sensei_Analysis_Lesson_List_Table extends WooThemes_Sensei_List_Table {
	public $lesson_id;
	public $course_id;
	public $csv_output = false;
	public $page_slug = 'sensei_analysis';

	/**
	 * Constructor
	 * @since  1.2.0
	 * @return  void
	 */
	public function __construct ( $lesson_id = 0 ) {
		$this->lesson_id = intval( $lesson_id );
		$this->course_id = intval( get_post_meta( $this->lesson_id, '_lesson_course', true ) );

		// Load Parent token into constructor
		parent::__construct( 'analysis_lesson' );

		// Actions
		add_action( 'sensei_before_list_table', array( $this, 'data_table_header' ) );
		add_action( 'sensei_after_list_table', array( $this, 'data_table_footer' ) );

		add_filter( 'sensei_list_table_search_button_text', array( $this, 'search_button' ) );
	} // End __construct()

	/**
	 * Define the columns that are going to be used in the table
	 * @since  1.7.0
	 * @return array $columns, the array of columns to use with the table
	 */
	function get_columns() {
		$columns = array(
			'title' => __( 'Learner', 'woothemes-sensei' ),
			'started' => __( 'Date Started', 'woothemes-sensei' ),
			'completed' => __( 'Date Completed', 'woothemes-sensei' ),
			'status' => __( 'Status', 'woothemes-sensei' ),
			'grade' => __( 'Grade', 'woothemes-sensei' ),
		);
		$columns = apply_filters( 'sensei_analysis_lesson_columns', $columns, $this );
		return $columns;
	}

	/**
	 * get_columns Define the columns that are going to be used in the table
	 * @since  1.7.0
	 * @return array $columns, the array of columns to use with the table
	 */
	function get_sortable_columns() {
		$columns = array(
			'title' => array( 'title', false ),
			'started' => array( 'started', false ),
			'completed' => array( 'completed', false ),
			'status' => array( 'status', false ),
			'grade' => array( 'grade', false ),
		);
		$columns = apply_filters( 'sensei_analysis_lesson_columns_sortable', $columns, $this );
		return $columns;
	}

	/**
	 * Prepare the table with different parameters, pagination, columns and table elements
	 * @since  1.7.0
	 * @return void
	 */
	public function prepare_items() {
		global $woothemes_sensei, $per_page;

		// Handle orderby (needs work)
		$orderby = '';
		if ( !empty( $_GET['orderby'] ) ) {
			if ( array_key_exists( esc_html( $_GET['orderby'] ), $this->get_sortable_columns() ) ) {
				$orderby = esc_html( $_GET['orderby'] );
			} // End If Statement
		}

		// Handle order
		$order = 'ASC';
		if ( !empty( $_GET['order'] ) ) {
			$order = ( 'ASC' == strtoupper($_GET['order']) ) ? 'ASC' : 'DESC';
		}

		// Handle search, need 4.1 version of WP to be able to restrict statuses to known post_ids
		$search = false;
		if ( !empty( $_GET['s'] ) ) {
			$search = esc_html( $_GET['s'] );
		} // End If Statement
		$this->search = $search;

		$per_page = $this->get_items_per_page( 'sensei_comments_per_page' );
		$per_page = apply_filters( 'sensei_comments_per_page', $per_page, 'sensei_comments' );

		$paged = $this->get_pagenum();
		$offset = 0;
		if ( !empty($paged) ) {
			$offset = $per_page * ( $paged - 1 );
		} // End If Statement

		$args = array(
			'number' => $per_page,
			'offset' => $offset,
			'orderby' => $orderby,
			'order' => $order,
		);
		if ( $this->search ) {
			$args['search'] = $this->search;
		} // End If Statement

		$this->items = $this->get_lesson_statuses( $args );

		$total_items = $this->total_items;
		$total_pages = ceil( $total_items / $per_page );
		$this->set_pagination_args( array(
			'total_items' => $total_items,
			'total_pages' => $total_pages,
			'per_page' => $per_page
		) );
	}

	/**
	 * Generate a csv report with different parameters, pagination, columns and table elements
	 * @since  1.7.0
	 * @return data
	 */
	public function generate_report( $report ) {

		$data = array();

		$this->csv_output = true;

		// Handle orderby
		$orderby = '';
		if ( !empty( $_GET['orderby'] ) ) {
			if ( array_key_exists( esc_html( $_GET['orderby'] ), $this->get_sortable_columns() ) ) {
				$orderby = esc_html( $_GET['orderby'] );
			} // End If Statement
		}

		// Handle order
		$order = 'ASC';
		if ( !empty( $_GET['order'] ) ) {
			$order = ( 'ASC' == strtoupper($_GET['order']) ) ? 'ASC' : 'DESC';
		}

		// Handle search
		$search = false;
		if ( !empty( $_GET['s'] ) ) {
			$search = esc_html( $_GET['s'] );
		} // End If Statement
		$this->search = $search;

		$args = array(
			'orderby' => $orderby,
			'order' => $order,
		);
		if ( $this->search ) {
			$args['search'] = $this->search;
		} // End If Statement

		// Start the csv with the column headings
		$column_headers = array();
		$columns = $this->get_columns();
		foreach( $columns AS $key => $title ) {
			$column_headers[] = $title;
		}
		$data[] = $column_headers;

		$this->items = $this->get_lesson_statuses( $args );

		// Process each row
		foreach( $this->items AS $item) {
			$data[] = $this->get_row_data( $item );
		}

		return $data;
	}

	/**
	 * Generates the overall array for a single item in the display
	 *
	 * @since  1.7.0
	 * @param object $item The current item
	 */
	protected function get_row_data( $item ) {
		global $woothemes_sensei;

		$user_start_date = get_comment_meta( $item->comment_ID, 'start', true );
		$user_end_date = $item->comment_date;
		$status_class = $grade = '';

		if( 'complete' == $item->comment_approved ) {
			$status = apply_filters( 'sensei_completed_text', __( 'Completed', 'woothemes-sensei' ) );
			$status_class = 'graded';

			$grade = apply_filters( 'sensei_no_grade_text', __( 'No Grade', 'woothemes-sensei' ) );
		}
		elseif( 'graded' == $item->comment_approved ) {
			$status = apply_filters( 'sensei_graded_text', __( 'Graded', 'woothemes-sensei' ) );
			$status_class = 'graded';

			$grade = get_comment_meta( $item->comment_ID, 'grade', true);
		}
		elseif( 'passed' == $item->comment_approved ) {
			$status = apply_filters( 'sensei_passed_text', __( 'Passed', 'woothemes-sensei' ) );
			$status_class = 'graded';

			$grade = get_comment_meta( $item->comment_ID, 'grade', true);
		}
		elseif( 'failed' == $item->comment_approved ) {
			$status = apply_filters( 'sensei_failed_text', __( 'Failed', 'woothemes-sensei' ) );
			$status_class = 'failed';

			$grade = get_comment_meta( $item->comment_ID, 'grade', true);
		}
		elseif( 'ungraded' == $item->comment_approved ) {
			$status = apply_filters( 'sensei_ungraded_text', __( 'Ungraded', 'woothemes-sensei' ) );
			$status_class = 'ungraded';

		}
		else {
			$status = apply_filters( 'sensei_in_progress_text', __( 'In Progress', 'woothemes-sensei' ) );
			$user_end_date = '';
		}

		// Output users data
		$user = get_user_by( 'id', $item->user_id );
		$user_name = $user->display_name;
		if ( !$this->csv_output ) {
			$url = add_query_arg( array( 'page' => $this->page_slug, 'user_id' => $item->user_id, 'course_id' => $this->course_id ), admin_url( 'admin.php' ) );

			$user_name = '<strong><a class="row-title" href="' . $url . '">' . $user_name . '</a></strong>';
			$status = sprintf( '<span class="%s">%s</span>', $item->comment_approved, $status );
			if ( is_numeric($grade) ) {
				$grade .= '%';
			}
		} // End If Statement
		$column_data = apply_filters( 'sensei_analysis_lesson_column_data', array( 'title' => $user_name,
										'started' => $user_start_date,
										'completed' => $user_end_date,
										'status' => $status,
										'grade' => $grade,
									), $item, $this );

		return $column_data;
	}

	/**
	 * Return array of lesson statuses
	 * @since  1.7.0
	 * @return array statuses
	 */
	private function get_lesson_statuses( $args ) {

		$activity_args = array( 
				'post_id' => $this->lesson_id,
				'type' => 'sensei_lesson_status',
				'number' => $args['number'],
				'offset' => $args['offset'],
				'orderby' => $args['orderby'],
				'order' => $args['order'],
				'status' => 'any',
			);

		// Searching users on statuses requires sub-selecting the statuses by user_ids
		if ( $this->search ) {
			$user_args = array(
				'search' => '*' . $this->search . '*',
				'fields' => 'ID',
			);
			// Filter for extending
			$user_args = apply_filters( 'sensei_analysis_lesson_search_users', $user_args );
			if ( !empty( $user_args ) ) {
				$learners_search = new WP_User_Query( $user_args );
				// Store for reuse on counts
				$activity_args['user_id'] = (array) $learners_search->get_results();
			}
		} // End If Statement

		$activity_args = apply_filters( 'sensei_analysis_lesson_filter_statuses', $activity_args );

		// WP_Comment_Query doesn't support SQL_CALC_FOUND_ROWS, so instead do this twice
		$this->total_items = WooThemes_Sensei_Utils::sensei_check_for_activity( array_merge( $activity_args, array('count' => true, 'offset' => 0, 'number' => 0) ) );

		// Ensure we change our range to fit (in case a search threw off the pagination) - Should this be added to all views?
		if ( $this->total_items < $activity_args['offset'] ) {
			$new_paged = floor( $total_statuses / $activity_args['number'] );
			$activity_args['offset'] = $new_paged * $activity_args['number'];
		}
		$statuses = WooThemes_Sensei_Utils::sensei_check_for_activity( $activity_args, true );
		// Need to always return an array, even with only 1 item
		if ( 1 == $this->total_items ) {
			$statuses = array( $statuses );
		}
		return $statuses;
	} // End get_lesson_statuses()

	/**
	 * no_items sets output when no items are found
	 * Overloads the parent method
	 * @since  1.2.0
	 * @return void
	 */
	public function no_items() {
		echo apply_filters( 'sensei_analysis_lessons_no_items_text', __( 'No learners found.', 'woothemes-sensei' ) );
	} // End no_items()

	/**
	 * data_table_header output for table heading
	 * @since  1.2.0
	 * @return void
	 */
	public function data_table_header() {
		echo '<strong>' . __( 'Learners taking this Lesson', 'woothemes-sensei' ) . '</strong>';
	} // End data_table_header()

	/**
	 * data_table_footer output for table footer
	 * @since  1.2.0
	 * @return void
	 */
	public function data_table_footer() {
		$lesson = get_post( $this->lesson_id );
		$report = sanitize_title( $lesson->post_title ) . '-learners-overview';
		$url = add_query_arg( array( 'page' => $this->page_slug, 'lesson_id' => $this->lesson_id, 'sensei_report_download' => $report ), admin_url( 'admin.php' ) );
		echo '<a class="button button-primary" href="' . wp_nonce_url( $url, 'sensei_csv_download-' . $report, '_sdl_nonce' ) . '">' . __( 'Export all rows (CSV)', 'woothemes-sensei' ) . '</a>';
	} // End data_table_footer()

	/**
	 * the text for the search button
	 * @since  1.7.0
	 * @return void
	 */
	public function search_button( $text = '' ) {
		return __( 'Search Learners', 'woothemes-sensei' );;
	}
} // End Class
