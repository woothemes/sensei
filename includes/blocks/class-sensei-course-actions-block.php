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
			[
				'render_callback' => [ $this, 'render_course_actions' ],
			],
			Sensei()->assets->src_path( 'blocks/course-actions-block/course-actions' )
		);
	}

	/**
	 * Renders the block as an empty string.
	 *
	 * @param array  $attributes The block attributes.
	 * @param string $content    The block content.
	 *
	 * @return string The block HTML.
	 */
	public function render_course_actions( array $attributes, string $content ): string { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
		return '';
	}
}
