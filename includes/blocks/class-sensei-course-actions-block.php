<?php
/**
 * File containing the class Sensei_Course_Actions_Block.
 *
 * @package sensei
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Sensei_Course_Actions_Block
 */
class Sensei_Course_Actions_Block {
	/**
	 * Sensei_Course_Actions_Block constructor.
	 */
	public function __construct() {
		Sensei_Blocks::register_sensei_block(
			'sensei-lms/course-actions',
			array(),
			Sensei()->assets->src_path( 'blocks/course-actions-block/course-actions' )
		);
	}
}
