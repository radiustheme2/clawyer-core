<?php
/**
 * Demo Importer Core Class
 *
 * This file contains the core functionality of the demo importer.
 *
 * @package RT\CLawyerCore\DemoImporter
 */

namespace RT\CLawyerCore\DemoImporter;

use RT\CLawyerCore\DemoImporter\Utils;
use RT\CLawyerCore\DemoImporter\Handlers\RtclHandler;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Demo Importer Core Class.
 */
class Core {
	/**
	 * Theme configuration.
	 *
	 * @var array
	 */
	private $config = [];

	/**
	 * Configuration cache.
	 *
	 * @var array
	 */
	private $config_cache = null;

	/**
	 * Utilities instance.
	 *
	 * @var Utils
	 */
	private $utils;

	/**
	 * RTCL handler instance.
	 *
	 * @var RtclHandler|null
	 */
	private $rtcl = null;

	/**
	 * Class Constructor.
	 *
	 * @param array $config Theme configuration array.
	 */
	public function __construct( array $config = [] ) {
		$this->config = $config;
		$this->utils  = new Utils( $config );

		if ( $this->is_feature_enabled( 'rtcl_support' ) ) {
			$this->rtcl = new RtclHandler( $config, $this->utils );
		}

		$this->load_user_importer();
		$this->setup_hooks();
	}

	/**
	 * Check if a feature is enabled.
	 *
	 * @param string $feature Feature name.
	 * @return bool
	 */
	private function is_feature_enabled( string $feature ): bool {
		return isset( $this->config['features'][ $feature ] ) && $this->config['features'][ $feature ];
	}

	/**
	 * Load the user importer file if it exists.
	 *
	 * @return void
	 */
	private function load_user_importer(): void {
		if ( ! $this->is_feature_enabled( 'user_import' ) ) {
			return;
		}

		$user_importer_path = $this->config['core_base_dir'] . $this->config['user_importer'];

		if ( file_exists( $user_importer_path ) ) {
			require_once $user_importer_path;
		}
	}

	/**
	 * Setup WordPress hooks.
	 *
	 * @return void
	 */
	private function setup_hooks(): void {
		/**
		 * Filters.
		 */
		add_filter( 'sd/edi/importer/config', [ $this, 'import_config' ] );
		add_filter( 'sd/edi/activated_plugin_actions', [ $this, 'plugin_actions' ] );

		/**
		 * Actions.
		 */
		add_action( 'sd/edi/after_plugins_activation', [ $this, 'before_import_actions' ] );
		add_action( 'sd/edi/before_widgets_import', [ $this, 'remove_default_widgets' ] );
		add_action( 'sd/edi/after_import', [ $this, 'after_import_actions' ] );
	}

	/**
	 * Generate import configuration from theme config.
	 *
	 * @return array
	 */
	public function import_config(): array {
		if ( null !== $this->config_cache ) {
			return $this->config_cache;
		}

		$this->config_cache = [
			'themeName'             => $this->config['theme_name'],
			'themeSlug'             => $this->config['theme_slug'],
			'multipleZip'           => false,
			'demoZip'               => $this->utils->get_demo_asset_url( $this->config['demo_content_zip'] ),
			'blogSlug'              => $this->config['blog_slug'],
			'urlToReplace'          => $this->config['demo_url'],
			'replaceCommenterEmail' => $this->config['commenter_email'] ?? '',
			'elementor_data_fix'    => $this->is_feature_enabled( 'elementor_fixes' ) ? $this->config['elementor_fixes'] : [],
			'settingsJson'          => $this->config['settings_json'],
			'menus'                 => $this->config['menus'],
			'demoData'              => $this->build_demo_data(),
			'plugins'               => $this->build_plugins_config(),
		];

		if ( ! empty( $this->config['fluent_forms_json'] ) ) {
			$this->config_cache['fluentFormsJson'] = $this->config['fluent_forms_json'];
		}

		return $this->config_cache;
	}

	/**
	 * Run plugin activation actions after activation.
	 *
	 * @param array $actions Existing plugin activation actions.
	 *
	 * @return array
	 */
	public function plugin_actions( array $actions ): array {
		$actions['classified-listing/classified-listing.php'] = [
			'class'  => '\Rtcl\Helpers\Installer',
			'action' => 'install',
		];

		$actions['classified-listing-pro/classified-listing-pro.php'] = [
			'class'  => '\RtclPro\Helpers\Installer',
			'action' => 'install',
		];

		$actions['classified-listing-store/classified-listing-store.php'] = [
			'class'  => '\RtclStore\Helpers\Install',
			'action' => 'install',
		];

		return $actions;
	}

	/**
	 * Build demo data configuration.
	 *
	 * @return array
	 */
	private function build_demo_data(): array {
		return array_map(
			function ( $variant ) {
				return [
					'name'         => $variant['name'],
					'previewImage' => $this->utils->get_demo_asset_url( $variant['preview'] ),
					'previewUrl'   => $this->config['demo_url'] . $variant['url'],
				];
			},
			$this->config['demo_variants']
		);
	}

	/**
	 * Build plugins configuration.
	 *
	 * @return array
	 */
	private function build_plugins_config(): array {
		return array_merge(
			$this->build_wp_plugins(),
			$this->build_bundled_plugins()
		);
	}

	/**
	 * Build WordPress repository plugins configuration.
	 *
	 * @return array
	 */
	private function build_wp_plugins(): array {
		$plugins = [];

		foreach ( $this->config['wp_plugins'] as $slug => $plugin ) {
			$name     = is_array( $plugin ) ? $plugin['name'] : $plugin;
			$filePath = is_array( $plugin ) && ! empty( $plugin['file'] ) ? $plugin['file'] : $slug . '/' . $slug . '.php';

			$plugins[ $slug ] = [
				'name'     => $name,
				'source'   => 'wordpress',
				'filePath' => $filePath,
			];
		}

		return $plugins;
	}

	/**
	 * Build bundled plugins configuration.
	 *
	 * @return array
	 */
	private function build_bundled_plugins(): array {
		$plugins = [];

		foreach ( $this->config['bundled_plugins'] as $slug => $plugin ) {
			$plugins[ $slug ] = [
				'name'     => $plugin['name'],
				'source'   => 'bundled',
				'filePath' => $slug . '/' . $slug . '.php',
				'location' => get_parent_theme_file_uri( $plugin['file'] ),
			];
		}

		return $plugins;
	}

	/**
	 * Execute before import actions.
	 *
	 * @return void
	 */
	public function before_import_actions(): void {
		if ( $this->rtcl ) {
			$this->rtcl->prepare_environment();
			flush_rewrite_rules();
		}

		$this->set_import_options();
	}

	/**
	 * Remove default widgets.
	 *
	 * @return void
	 */
	public function remove_default_widgets(): void {
		delete_option( 'sidebars_widgets' );
	}

	/**
	 * Execute after import actions.
	 *
	 * @return void
	 */
	public function after_import_actions(): void {
		if ( $this->is_feature_enabled( 'user_import' ) ) {
			$this->import_users();
		}

		if ( $this->rtcl ) {
			$this->rtcl->rebuild_environment();
		}

		$this->cleanup_after_import();

		// Booking Demo Setup.
		$this->radius_booking();
		$this->sync_listing_bookings();
		$this->sync_booking_employee_roles();
	}

	public function radius_booking() {
		if ( ! class_exists( '\RadiusTheme\RadiusBooking\Facades\Import' ) ) {
			return $this;
		}
		$json_file_path = CLAWYER_CORE_DEMO_CONTENT . 'demo-files/radius-booking.json';
		if ( file_exists( $json_file_path ) ) {
			\RadiusTheme\RadiusBooking\Facades\Import::fromFile($json_file_path)->import();
		}
		return $this;
	}


	/**
	 * After radius-booking.json import, link imported data to their local listings.
	 *
	 * radius-booking.json stores services/profiles with the original demo site's
	 * listing IDs. After XML content import, listings get new IDs. This step
	 * matches each item to its listing by title and updates listing_id accordingly.
	 *
	 * Handles both integration modes:
	 *  - "Listing as Service": updates radius_booking_services.listing_id
	 *  - "Listing as Staff":   updates radius_booking_user_profiles.listing_id
	 *
	 * @return void
	 */
	private function sync_listing_bookings(): void {
		global $wpdb;

		$services_table = $wpdb->prefix . 'radius_booking_services';

		// --- Service mode: match services to listings by title ---
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		$services = $wpdb->get_results(
			"SELECT id, name FROM `{$services_table}` WHERE source_type = 'classified_listing'",
			ARRAY_A
		);

		foreach ( (array) $services as $service ) {
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
			$listing_id = (int) $wpdb->get_var( $wpdb->prepare(
				"SELECT ID FROM {$wpdb->posts} WHERE post_title = %s AND post_type = 'rtcl_listing' LIMIT 1",
				$service['name']
			) );
			if ( ! $listing_id ) {
				continue;
			}
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
			$wpdb->update( $services_table, [ 'listing_id' => $listing_id ], [ 'id' => (int) $service['id'] ], [ '%d' ], [ '%d' ] );
			update_post_meta( $listing_id, '_rtrb_booking_enabled', '1' );
		}

		// --- Fix rtrb_cl_default_service_id for Staff mode ---
		// In Staff mode, ListingFrontendHooks reads get_option('rtrb_cl_default_service_id')
		// to find the shared "Consultation" service. The JSON import carries the demo site's
		// old service ID in that option; the service itself gets a new local ID after import.
		// Re-point the option to the correct local ID so the Book Now button can appear.
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		$default_service_id = (int) $wpdb->get_var(
			"SELECT id FROM `{$services_table}` WHERE source_type = 'classified_listing_default' LIMIT 1"
		);
		if ( $default_service_id ) {
			update_option( 'rtrb_cl_default_service_id', $default_service_id );
		}

		// --- Staff mode: create employee profiles for all published listings ---
		//
		// The JSON export only carries 'manual' type profiles (listing_id = null).
		// No 'classified_listing' profiles exist after import, so name-matching
		// against the profiles table can never succeed.
		//
		// Instead, replicate exactly what save_post_rtcl_listing does:
		// call ensureEmployeeForListing() for every published listing. This creates
		// a proper profile with source_type='classified_listing' and the correct
		// local listing_id — making the Book Now button appear without a manual save.
		if ( ! $default_service_id ) {
			return; // No default service found — not in staff mode.
		}

		if ( ! class_exists( '\RadiusTheme\RadiusBooking\Integrations\ClassifiedListing\ListingEmployeeSync' ) ) {
			return;
		}

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		$listings = $wpdb->get_results(
			"SELECT p.ID FROM {$wpdb->posts} p
			 INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id AND pm.meta_key = 'ad_type' AND pm.meta_value = 'doctor'
			 WHERE p.post_type = 'rtcl_listing' AND p.post_status = 'publish'",
			ARRAY_A
		);

		$employee_sync = new \RadiusTheme\RadiusBooking\Integrations\ClassifiedListing\ListingEmployeeSync();

		foreach ( (array) $listings as $row ) {
			$listing_id = (int) $row['ID'];
			if ( ! $listing_id ) {
				continue;
			}

			// Mark as bookable so future saves and isListingBookable() checks pass.
			update_post_meta( $listing_id, '_rtrb_booking_enabled', '1' );

			// Create or link the employee profile — mirrors onListingSave() behaviour.
			$employee_sync->ensureEmployeeForListing( $listing_id, $default_service_id );
		}
	}


	/**
	 * After demo import, link all radius-booking staff profiles (wp_user_id = NULL)
	 * to the current admin and assign the employee WordPress role.
	 *
	 * StaffImporter intentionally nulls wp_user_id because demo site user IDs
	 * are site-specific. import_users() sets all post authors to the current
	 * admin, so the admin becomes the owner of every listing. This step closes
	 * the gap by pointing every orphaned profile at that same admin and granting
	 * the radius_booking_employee role — making My Account → Bookings work
	 * immediately after demo import without requiring listing re-saves.
	 *
	 * @return void
	 */
	private function sync_booking_employee_roles(): void {
		if ( ! class_exists( '\RadiusTheme\RadiusBooking\Models\UserProfile' ) ) {
			return;
		}

		$current_user_id = get_current_user_id();
		if ( ! $current_user_id ) {
			return;
		}

		global $wpdb;
		$profiles_table = $wpdb->prefix . 'radius_booking_user_profiles';

		// Assign the current admin to all orphaned profiles (wp_user_id IS NULL).
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		$wpdb->query(
			$wpdb->prepare(
				"UPDATE `{$profiles_table}` SET wp_user_id = %d WHERE wp_user_id IS NULL",
				$current_user_id
			)
		);

		// Grant the WordPress role so dashboard.php can detect the user as an employee.
		$wp_user = get_userdata( $current_user_id );
		if ( $wp_user && ! in_array( 'radius_booking_employee', (array) $wp_user->roles, true ) ) {
			$wp_user->add_role( 'radius_booking_employee' );
		}

		// Mark the Setup Wizard as completed so the admin panel loads normally
		// instead of redirecting to the wizard on every visit.
		update_option( 'radius_booking_setup_completed', true );
	}

	/**
	 * Set import-specific options.
	 *
	 * @return void
	 */
	private function set_import_options(): void {
		foreach ( $this->config['pre_import_options'] as $option => $value ) {
			update_option( $option, $value );
		}
	}

	/**
	 * Import users and update post authors.
	 *
	 * @return void
	 */
	private function import_users(): void {
		global $wpdb;

		$current_user_id = get_current_user_id();

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		$wpdb->query(
			$wpdb->prepare(
				"UPDATE {$wpdb->posts} SET post_author = %d",
				$current_user_id
			)
		);

		if ( class_exists( $this->config['user_class'] ) ) {
			$class_name = $this->config['user_class'];
			new $class_name();
		}
	}

	/**
	 * Cleanup after import completion.
	 *
	 * @return void
	 */
	private function cleanup_after_import(): void {
		foreach ( $this->config['post_import_options'] as $option => $value ) {
			update_option( $option, $value );
		}

		flush_rewrite_rules();
	}
}
