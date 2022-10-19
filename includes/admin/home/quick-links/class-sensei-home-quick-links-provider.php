<?php
/**
 * File containing Sensei_Home_Quick_Links_Provider class.
 *
 * @package sensei-lms
 * @since   $$next-version$$
 */

/**
 * Class responsible for generating the Quick Links structure for Sensei Home screen.
 */
class Sensei_Home_Quick_Links_Provider {

	const ACTION_INSTALL_DEMO_COURSE = 'sensei://install-demo-course';

	/**
	 * Return a list of categories which each contain multiple quick link items.
	 *
	 * @return array[]
	 */
	public function get(): array {
		return [
			$this->create_category(
				__( 'Courses', 'sensei-lms' ),
				[
					$this->create_item( __( 'Create a Course', 'sensei-lms' ), admin_url( '/post-new.php?post_type=course' ) ),
					$this->create_item( __( 'Install a Demo Course', 'sensei-lms' ), self::ACTION_INSTALL_DEMO_COURSE ),
					$this->create_item( __( 'Import a Course', 'sensei-lms' ), admin_url( '/admin.php?page=sensei-tools&tool=import-content' ) ),
					$this->create_item( __( 'Reports', 'sensei-lms' ), admin_url( '/admin.php?page=sensei_reports' ) ),
				]
			),
			$this->create_category(
				__( 'Settings', 'sensei-lms' ),
				[
					$this->create_item( __( 'Email notifications', 'sensei-lms' ), admin_url( '/admin.php?page=sensei-settings#email-notification-settings' ) ),
					$this->create_item( __( 'Learning Mode', 'sensei-lms' ), admin_url( '/admin.php?page=sensei-settings#course-settings' ) ),
					$this->create_item( __( 'WooCommerce', 'sensei-lms' ), admin_url( '/admin.php?page=sensei-settings#woocommerce-settings' ) ),
					$this->create_item( __( 'Content Drip', 'sensei-lms' ), admin_url( '/admin.php?page=sensei-settings#sensei-content-drip-settings' ) ),
				]
			),
			$this->create_category(
				__( 'Advanced Features', 'sensei-lms' ),
				[
					$this->create_item( __( 'Interactive Blocks', 'sensei-lms' ), 'https://senseilms.com/interactive-blocks' ),
					$this->create_item( __( 'Groups & Cohorts', 'sensei-lms' ), 'https://senseilms.com/groups-cohorts' ),
					$this->create_item( __( 'Quizzes', 'sensei-lms' ), 'https://senseilms.com/quizzes' ),
					$this->create_item( __( 'Integrations', 'sensei-lms' ), 'https://senseilms.com/sensei-lms-integrations/' ),
				]
			),
		];
	}

	/**
	 * Create the structure for a Quick Links category.
	 *
	 * @param string $title The category title.
	 * @param array  $items The category items.
	 *
	 * @return array
	 */
	private function create_category( string $title, array $items ): array {
		return [
			'title' => $title,
			'items' => $items,
		];
	}

	/**
	 * Create the structure for a Quick Links item.
	 *
	 * @param string      $title The item title.
	 * @param string|null $url Optional. The item action URL.
	 *
	 * @return array
	 */
	private function create_item( string $title, ?string $url ): array {
		return [
			'title' => $title,
			'url'   => $url,
		];
	}
}
