<?php
/**
 * Sensei Home REST API.
 *
 * @package Sensei\Admin
 * @since   $$next-version$$
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Sensei Home REST API endpoints.
 *
 * @since $$next-version$$
 */
class Sensei_REST_API_Home_Controller extends \WP_REST_Controller {

	/**
	 * Routes namespace.
	 *
	 * @var string
	 */
	protected $namespace;

	/**
	 * Routes prefix.
	 *
	 * @var string
	 */
	protected $rest_base = 'home';

	/**
	 * Mapper.
	 *
	 * @var Sensei_REST_API_Home_Controller_Mapper
	 */
	private $mapper;

	/**
	 * Quick Links provider.
	 *
	 * @var Sensei_Home_Quick_Links_Provider
	 */
	private $quick_links_provider;

	/**
	 * Home data provider.
	 *
	 * @var Sensei_Home_Data_Provider
	 */
	private $home_data_provider;

	/**
	 * Sensei_REST_API_Home_Controller constructor.
	 *
	 * @param string                                 $namespace            Routes namespace.
	 * @param Sensei_REST_API_Home_Controller_Mapper $mapper               Sensei Home REST API mapper.
	 * @param Sensei_Home_Data_Provider              $home_data_provider   Fetch home data helper.
	 * @param Sensei_Home_Quick_Links_Provider       $quick_links_provider Quick Links provider.
	 */
	public function __construct(
		$namespace,
		Sensei_REST_API_Home_Controller_Mapper $mapper,
		Sensei_Home_Data_Provider $home_data_provider,
		Sensei_Home_Quick_Links_Provider $quick_links_provider
	) {
		$this->namespace            = $namespace;
		$this->mapper               = $mapper;
		$this->home_data_provider   = $home_data_provider;
		$this->quick_links_provider = $quick_links_provider;
	}

	/**
	 * Register the REST API endpoints for Home.
	 */
	public function register_routes() {
		$this->register_get_data_route();
	}

	/**
	 * Check user permission for REST API access.
	 *
	 * @return bool Whether the user can access the Sensei Home REST API.
	 */
	public function can_user_access_rest_api() {
		return current_user_can( 'manage_sensei' );
	}

	/**
	 * Register GET / endpoint.
	 */
	public function register_get_data_route() {
		register_rest_route(
			$this->namespace,
			$this->rest_base,
			[
				[
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => [ $this, 'get_data' ],
					'permission_callback' => [ $this, 'can_user_access_rest_api' ],
				],
			]
		);
	}

	/**
	 * Get data for Sensei Home frontend.
	 *
	 * @return array Setup Wizard data
	 */
	public function get_data() {
		$home_data = $this->home_data_provider->fetch( HOUR_IN_SECONDS );
		$guides    = $home_data['guides'] ?? [];
		$news      = $home_data['news'] ?? [];

		return [
			'tasks_list'            => [
				'tasks' => [
					// TODO: Generate based on Setup Wizard data + site info.
					[
						'title' => 'Set up Course Site',
						'done'  => true,
						'url'   => null,
						'image' => 'http://...', // Optional image to be used by the frontend.
					],
					[
						'title' => 'Create your first Course',
						'done'  => false,
						'url'   => '/wp-admin/edit.php?post_type=course',
						'image' => 'http://...', // Optional image to be used by the frontend.
					],
					[
						'title' => 'Configure Learning Mode',
						'done'  => false,
						'url'   => '/wp-admin/edit.php?post_type=course&page=sensei-settings#course-settings',
						'image' => 'http://...', // Optional image to be used by the frontend.
					],
					[
						'title' => 'Publish your first Course',
						'done'  => false,
						'url'   => '???',
						'image' => 'http://...', // Optional image to be used by the frontend.
					],
				],
			],
			'quick_links'           => $this->mapper->map_quick_links( $this->quick_links_provider->get() ),
			'help'                  => [
				// TODO: Replace with real implementation.
				[
					'title' => 'Get the most out of Sensei',
					'items' => [
						[
							'title' => 'Sensei Documentation',
							'url'   => 'http://...',
							'icon'  => null,
						],
						[
							'title' => 'Support forums',
							'url'   => 'http://...',
							'icon'  => null,
						],
						[
							'title'      => 'Create a support ticket',
							'url'        => null,
							'extra_link' => [
								'label' => 'Upgrade to Sensei Pro',
								'url'   => 'https://...',
							],
							'icon'       => 'lock',
						],
					],
				],
			],
			'guides'                => $guides,
			'news'                  => $news,
			'extensions'            => [
				// TODO: Load from https://senseilms.com/wp-json/senseilms-home/1.0/{sensei-lms|sensei-pro}.json.
				[
					'title'        => 'Sensei LMS Post to Course Creator',
					'image'        => 'http://senseilms.com/wp-content/uploads/2022/02/sensei-post-to-course-80x80.png',
					'description'  => 'Turn your blog posts into online courses.',
					'price'        => 0,
					'product_slug' => 'sensei-post-to-course', // To be used with the installation function `Sensei_Setup_Wizard::install_extensions`.
					'more_url'     => 'http://senseilms.com/product/sensei-lms-post-to-course-creator/',
				],
			],
			'show_sensei_pro_promo' => false, // Whether we should show the promotional banner for Sensei Pro or not.
			'notifications'         => [
				[
					'heading'     => null, // Not needed for the moment.
					'message'     => 'Your Sensei Pro license expires on 12.09.2022.',
					'actions'     => [
						[
							'label' => 'Update now',
							'url'   => 'https://...',
						],
					],
					'info_link'   => [
						'label' => 'What\'s new',
						'url'   => 'https://...',
					],
					'level'       => 'error', // One of: info, warning, error.
					'dismissible' => false, // The default value is true.
				],
				[
					'heading'     => null, // Not needed for the moment.
					'message'     => 'Good news, reminder to update to latest version',
					'actions'     => [
						[
							'label' => 'Update now',
							'url'   => 'https://...',
						],
					],
					'info_link'   => [
						'label' => 'Link for more information',
						'url'   => 'https://...',
					],
					'level'       => 'info', // One of: info, warning, error.
					'dismissible' => true, // The default value is true.
				],
			],
		];
	}
}
