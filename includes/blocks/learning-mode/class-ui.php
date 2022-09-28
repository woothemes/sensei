<?php
/**
 * Learning Mode Ui block.
 *
 * @package sensei
 * @since
 */

namespace Sensei\Blocks\Learning_Mode;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \Sensei_Blocks;

/**
 * User interface block for Learning mode layout elements.
 */
class Ui {
	/**
	 * Block JSON file.
	 */
	const BLOCK_JSON_FILE = '/ui/ui.block.json';

	/**
	 * Course_Title constructor.
	 */
	public function __construct() {
		$block_json_path = Sensei()->assets->src_path( 'learning-mode/blocks' ) . self::BLOCK_JSON_FILE;
		Sensei_Blocks::register_sensei_block(
			'sensei-lms/ui',
			[
				'style'       => 'sensei-learning-mode',
				'editorStyle' => 'sensei-learning-mode-editor',
			],
			$block_json_path
		);
	}
}
