<?php
/**
 * File containing the Sensei_Block_Quiz_Question class.
 *
 * @package sensei
 * @since 3.9.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Sensei_Block_Quiz_Question is responsible for handling the 'Quiz' block.
 */
class Sensei_Block_Quiz_Question {

	/**
	 * Sensei_Block_Quiz_Question constructor.
	 */
	public function __construct() {
		Sensei_Blocks::register_sensei_block(
			'sensei-lms/quiz-question',
			[
				'render_callback' => '__return_empty_string',
			],
			Sensei()->assets->src_path( 'blocks/quiz/question-block' )
		);

		Sensei_Blocks::register_sensei_block(
			'sensei-lms/question-description',
			[],
			Sensei()->assets->src_path( 'blocks/quiz/question-description-block' )
		);

		Sensei_Blocks::register_sensei_block(
			'sensei-lms/question-answers',
			[],
			Sensei()->assets->src_path( 'blocks/quiz/question-answers-block' )
		);
	}

	/**
	 * Renders the block as an empty string.
	 *
	 * @deprecated 4.24.2
	 *
	 * @param array  $attributes The block attributes.
	 * @param string $content    The block content.
	 *
	 * @return string The block HTML.
	 */
	public function render_quiz_question( array $attributes, string $content ): string {
		// phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
		_deprecated_function( __METHOD__, '4.24.2', '__return_empty_string' );

		return '';
	}
}
