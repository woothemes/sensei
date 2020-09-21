<?php
/**
 * File containing the Sensei_Course_Outline_Block class.
 *
 * @package sensei
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Sensei_Course_Outline_Block
 */
class Sensei_Course_Outline_Block {

	/**
	 * Attributes for inner blocks.
	 *
	 * @var array[]
	 */
	private $block_attributes = [
		'lesson' => [],
		'module' => [],
	];

	/**
	 * Sensei_Course_Outline_Block constructor.
	 */
	public function __construct() {
		add_action( 'enqueue_block_assets', [ $this, 'enqueue_block_assets' ] );
		add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_block_editor_assets' ] );
		add_action( 'init', [ $this, 'register_course_template' ], 101 );
		add_action( 'init', [ $this, 'register_blocks' ] );
	}

	/**
	 * Enqueue frontend and editor assets.
	 *
	 * @access private
	 */
	public function enqueue_block_assets() {
		if ( 'course' !== get_post_type() ) {
			return;
		}

		Sensei()->assets->enqueue( 'sensei-course-outline', 'blocks/course-outline/style.css' );
	}

	/**
	 * Enqueue editor assets.
	 *
	 * @access private
	 */
	public function enqueue_block_editor_assets() {
		if ( 'course' !== get_post_type() ) {
			return;
		}

		Sensei()->assets->enqueue( 'sensei-course-outline', 'blocks/course-outline/index.js' );
		Sensei()->assets->enqueue( 'sensei-course-outline-editor', 'blocks/course-outline/style.editor.css' );
	}

	/**
	 * Register course template.
	 *
	 * @access private
	 */
	public function register_course_template() {
		$post_type_object = get_post_type_object( 'course' );

		$post_type_object->template = [
			[ 'sensei-lms/course-outline' ],
		];
	}

	/**
	 * Register course outline block.
	 *
	 * @access private
	 */
	public function register_blocks() {
		register_block_type_from_metadata(
			Sensei()->assets->path( 'blocks/course-outline/course-block' ),
			[
				'render_callback' => [ $this, 'render_course_outline_block' ],
			]
		);

		register_block_type(
			'sensei-lms/course-outline-lesson',
			[
				'render_callback' => [ $this, 'process_lesson_block' ],
				'attributes'      => [
					'id' => [
						'type' => 'number',
					],
				],
			]
		);

		register_block_type(
			'sensei-lms/course-outline-module',
			[
				'render_callback' => [ $this, 'process_module_block' ],
				'attributes'      => [
					'id' => [
						'type' => 'number',
					],
				],
				'script'          => 'sensei-course-outline-frontend',
			]
		);
	}

	/**
	 * Extract attributes from module block.
	 *
	 * @param array $attributes
	 *
	 * @access private
	 * @return string
	 */
	public function process_lesson_block( $attributes ) {
		$this->block_attributes['lesson'][ $attributes['id'] ] = $attributes;
		return '';
	}

	/**
	 * Extract attributes from module block.
	 *
	 * @param array $attributes
	 *
	 * @access private
	 * @return string
	 */
	public function process_module_block( $attributes ) {
		$this->block_attributes['module'][ $attributes['id'] ] = $attributes;
		return '';
	}

	/**
	 * Add attributes from matching blocks to modules and lessons in course structure.
	 *
	 * @param array $structure Course structure.
	 */
	private function add_block_attributes( &$structure ) {
		if ( empty( $structure ) ) {
			return;
		}
		foreach ( $structure as &$block ) {
			$block['attributes'] = $this->block_attributes[ $block['type'] ][ $block['id'] ] ?? [];
			if ( ! empty( $block['lessons'] ) ) {
				self::add_block_attributes( $block['lessons'] );
			}
		}
	}

	/**
	 * Render Course Outline block.
	 *
	 * @access private
	 *
	 * @param array $attributes Block attributes.
	 *
	 * @return string Block HTML.
	 */
	public function render_course_outline_block( $attributes ) {

		global $post;

		$structure = Sensei_Course_Structure::instance( $post->ID )->get( 'view' );

		$this->disable_course_legacy_content();

		$this->add_block_attributes( $structure );

		$block_class = 'wp-block-sensei-lms-course-outline';
		if ( isset( $attributes['className'] ) ) {
			$block_class .= ' ' . $attributes['className'];
		}

		return '
			<section class="' . $block_class . '">
				' .
			implode(
				'',
				array_map(
					function( $block ) use ( $post, $attributes ) {
						if ( 'module' === $block['type'] ) {
							return $this->render_module_block( $block, $post->ID, $attributes );
						}

						if ( 'lesson' === $block['type'] ) {
							return $this->render_lesson_block( $block );
						}
					},
					$structure
				)
			)
			. '
			</section>
		';
	}

	/**
	 * Get lesson block HTML.
	 *
	 * @param array $block Block information.
	 *
	 * @return string Lesson HTML
	 */
	protected function render_lesson_block( $block ) {

		$css = Sensei_Block_Helpers::build_styles( $block );

		return '
			<h3 ' . Sensei_Block_Helpers::render_style_attributes( 'wp-block-sensei-lms-course-outline-lesson', $css ) . '>
				<a
				href="' . esc_url( get_permalink( $block['id'] ) ) . '">
					' . $block['title'] . '
				</a>
			</h3>
		';
	}

	/**
	 * Get module block HTML.
	 *
	 * @param array $block              Module block attributes.
	 * @param int   $course_id          The course id.
	 * @param array $outline_attributes Outline block attributes.
	 *
	 * @return string Module HTML
	 */
	private function render_module_block( $block, $course_id, $outline_attributes ) {
		if ( empty( $block['lessons'] ) ) {
			return '';
		}

		$progress_indicator = $this->get_progress_indicator( $block['id'], $course_id );

		$animated = false === $outline_attributes['animationsEnabled'] ? '' : 'animated';

		return '
			<section class="wp-block-sensei-lms-course-outline-module">
				<header class="wp-block-sensei-lms-course-outline-module__name">
					<h2 class="wp-block-sensei-lms-course-outline__clean-heading">' . $block['title'] . '</h2>
					' . $progress_indicator . '
					<div role="button" tabindex="0" class="wp-block-sensei-lms-course-outline__arrow dashicons dashicons-arrow-up-alt2"/>
				</header>
				<div class="wp-block-sensei-lms-collapsible ' . $animated . '">
					<div class="wp-block-sensei-lms-course-outline-module__description">
						' . $block['description'] . '
					</div>
							<div class="wp-block-sensei-lms-course-outline-module__lessons-title">
								<div class="wp-block-sensei-lms-course-outline__clean-heading">' . __( 'Lessons', 'sensei-lms' ) . '</div>
							</div>
						' .
				implode(
					'',
					array_map(
						[ $this, 'render_lesson_block' ],
						$block['lessons']
					)
				)
				. '
				</div>
			</section>
		';
	}

	/**
	 * Get progress indicator HTML.
	 *
	 * @param array $module_id The module id.
	 * @param int   $course_id The course id.
	 *
	 * @return string Module HTML
	 */
	private function get_progress_indicator( $module_id, $course_id ) {

		$module_progress = Sensei()->modules->get_user_module_progress( $module_id, $course_id, get_current_user_id() );

		if ( empty( $module_progress ) ) {
			return '';
		}

		if ( $module_progress < 100 ) {
			$module_status   = __( 'In Progress', 'sensei-lms' );
			$indicator_class = '';
		} else {
			$module_status   = __( 'Completed', 'sensei-lms' );
			$indicator_class = 'completed';
		}

		return '
					<div
						class="wp-block-sensei-lms-course-outline__progress-indicator ' . $indicator_class . '"
					>
						<span class="wp-block-sensei-lms-course-outline__progress-indicator__text"> ' . $module_status . ' </span>
					</div>
		';
	}

	/**
	 * Disable course legacy content.
	 */
	private function disable_course_legacy_content() {
		// TODO: Check the best approach for backwards compatibility.
		remove_action( 'sensei_single_course_content_inside_after', 'course_single_lessons' );
		remove_action( 'sensei_single_course_content_inside_after', [ 'Sensei_Course', 'the_course_lessons_title' ], 9 );
		remove_action( 'sensei_single_course_content_inside_after', [ Sensei()->modules, 'load_course_module_content_template' ], 8 );
	}

}
