<?php
/**
 * Marketing promo for Contact Form Extender for Divi (Divi 4 & 5).
 *
 * This file is designed to be safely reused across multiple plugins.
 * The class and hooks will only be registered once, even if included
 * from several plugins.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'ContactFormExtender_Marketing' ) ) {

	class ContactFormExtender_Marketing {

		/**
		 * Target plugin slug on WordPress.org.
		 *
		 * @var string
		 */
		private const TARGET_PLUGIN_SLUG = 'contact-form-extender-for-divi-builder';

		/**
		 * Target plugin main file for activation.
		 *
		 * @var string
		 */
		private const TARGET_PLUGIN_INIT = 'contact-form-extender-for-divi-builder/contact-form-extender-for-divi-builder.php';

		public function __construct() {
			add_filter( 'et_builder_get_parent_modules', array( $this, 'add_toggles' ), 10, 2 );
			add_filter( 'et_pb_all_fields_unprocessed_et_pb_contact_form', array( $this, 'add_promo_field' ), 20 );
			add_action( 'divi_visual_builder_assets_before_enqueue_scripts', array( $this, 'enqueue_vb_scripts' ), 999 );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_vb_scripts' ), 999 );

			// AJAX handlers for install & activate.
			// add_action( 'wp_ajax_ecmd_plugin_install', array( $this, 'ajax_install_plugin' ) );
			add_action( 'wp_ajax_ecmd_plugin_install', 'wp_ajax_install_plugin' );
			add_action( 'wp_ajax_ecmd_plugin_activate', array( $this, 'ajax_activate_plugin' ) );
		}

		/**
		 * Enqueue builder scripts/styles and expose config variables.
		 */
		public function enqueue_vb_scripts() {
			if ( ! function_exists( 'et_core_is_fb_enabled' ) || ! et_core_is_fb_enabled() ) {
				return;
			}

			static $done = false;
			if ( $done ) {
				return;
			}
			$done = true;

			$vars = $this->get_js_vars();
			

			if ( function_exists( 'et_core_is_fb_enabled' ) && et_core_is_fb_enabled() ) {
				$this->enqueue_d5_script( $vars );
			} else {
				$this->enqueue_d4_script( $vars );
			}
		}

		/**
		 * Build shared JS config.
		 *
		 * @return array
		 */
		private function get_js_vars() {
			return array(
				'ajaxurl'      => admin_url( 'admin-ajax.php' ),
				'installNonce' => wp_create_nonce( 'updates' ),
				'activateNonce'=> wp_create_nonce( 'ecmd_plugin_activate' ),
				'pluginSlug'   => self::TARGET_PLUGIN_SLUG,
				'pluginInit'   => self::TARGET_PLUGIN_INIT,
				'status'       => $this->get_plugin_status(),
			);
		}

		/**
		 * Enqueue assets for Divi 4 builder.
		 *
		 * @param array $vars
		 */
		public function enqueue_d4_script( $vars ) {
			$base = plugins_url( '', __FILE__ );
			$ver  = defined( 'ECMD_V' ) ? ECMD_V : '1.0';

			wp_enqueue_style(
				'ecmd-marketing-file-upload-d4',
				$base . '/marketing-file-upload-d4.css',
				array(),
				$ver
			);

			wp_enqueue_script(
				'ecmd-marketing-file-upload',
				$base . '/marketing-file-upload.js',
				array( 'jquery' ),
				$ver,
				true
			);

			wp_localize_script( 'ecmd-marketing-file-upload', 'ecmd_plugin_vars', $vars );
		}

		/**
		 * Enqueue assets for Divi 5 builder.
		 *
		 * @param array $vars
		 */
		public function enqueue_d5_script( $vars ) {
			if ( ! class_exists( '\ET\Builder\VisualBuilder\Assets\PackageBuildManager' ) ) {
				return;
			}

			$base = plugins_url( '', __FILE__ );
			$ver  = defined( 'ECMD_V' ) ? ECMD_V : '1.0';

			wp_enqueue_style(
				'ecmd-marketing-file-upload-d4',
				$base . '/marketing-file-upload-d4.css',
				array(),
				$ver
			);

			wp_enqueue_script(
				'ecmd-marketing-file-upload',
				$base . '/marketing-file-upload.js',
				array( 'jquery' ),
				$ver,
				true
			);
			// Provide config before the package script.
			wp_register_script( 'ecmd-marketing-file-upload-config', '', array(), $ver, false );
			wp_enqueue_script( 'ecmd-marketing-file-upload-config' );
			wp_add_inline_script(
				'ecmd-marketing-file-upload-config',
				'window.ecmd_plugin_vars = ' . wp_json_encode( $vars ) . ';',
				'before'
			);

			// \ET\Builder\VisualBuilder\Assets\PackageBuildManager::register_package_build(
			// 	array(
			// 		'name'    => 'ecmd-marketing-file-upload',
			// 		'version' => $ver,
			// 		'script'  => array(
			// 			'src'                => $base . '/marketing-file-upload.js',
			// 			'deps'               => array( 'divi-vendor-wp-hooks', 'ecmd-marketing-file-upload-config' ),
			// 			'enqueue_top_window' => false,
			// 			'enqueue_app_window' => true,
			// 			'args'               => array( 'in_footer' => false ),
			// 		),
			// 	)
			// );
		}

		/**
		 * Add custom toggle in Divi 4 contact form settings.
		 *
		 * @param array  $modules
		 * @param string $post_type
		 *
		 * @return array
		 */
		public function add_toggles( $modules, $post_type ) {
			if ( isset( $modules['et_pb_contact_form'] ) ) {
				$modules['et_pb_contact_form']->settings_modal_toggles['general']['toggles']['ecmd_file_upload_promo'] = array(
					'title'    => esc_html__( 'Save submissions', 'events-calendar-modules-for-divi' ),
					'priority' => 200,
				);
			}

			return $modules;
		}

		/**
		 * Add the promo field into contact form settings (Divi 4).
		 *
		 * Button label and type depend on plugin status.
		 *
		 * @param array $fields_unprocessed
		 *
		 * @return array
		 */
		public function add_promo_field( $fields_unprocessed ) {
			static $field = null;
			if ( $field === null ) {
				$status = $this->get_plugin_status();

				if ( 'active' === $status ) {
					$button_html = sprintf(
						'<span style="display:inline-block;padding:10px 16px;background:#22c55e;border:none;color:#fff;border-radius:4px;font-weight:bold;width:100%%;text-align:center;box-sizing:border-box;">%s</span>',
						esc_html__( 'Contact Form Extender for Divi is active', 'events-calendar-modules-for-divi' )
					);
				} elseif ( 'inactive' === $status ) {
					$button_html = sprintf(
						'<button type="button" class="ecmd-d4-promo__btn ecmd-activate-plugin-btn" data-init="%s" style="display:inline-block;padding:10px 16px;background:#007cba;border:none;color:#fff;border-radius:4px;font-weight:bold;width:100%%;text-align:center;box-sizing:border-box;cursor:pointer;">%s</button>',
						esc_attr( self::TARGET_PLUGIN_INIT ),
						esc_html__( 'Activate Plugin', 'events-calendar-modules-for-divi' )
					);
				} else {
					$button_html = sprintf(
						'<button type="button" class="ecmd-d4-promo__btn ecmd-install-plugin-btn" data-slug="%s" data-init="%s" style="display:inline-block;padding:10px 16px;background:#007cba;border:none;color:#fff;border-radius:4px;font-weight:bold;width:100%%;text-align:center;box-sizing:border-box;cursor:pointer;">%s</button>',
						esc_attr( self::TARGET_PLUGIN_SLUG ),
						esc_attr( self::TARGET_PLUGIN_INIT ),
						esc_html__( 'Install & Activate', 'events-calendar-modules-for-divi' )
					);
				}

				$field = array(
					'label'           => '',
					'type'            => 'text',
					'default'         => '',
					'option_category' => 'basic_option',
					'description'     => sprintf(
						'<div style="text-align:left;color:#666;"><div style="font-weight:700;color:#333;margin-bottom:5px;">%s</div>%s<br><br>%s</div>',
						esc_html__( 'Enhance Your Contact Form', 'events-calendar-modules-for-divi' ),
						esc_html__( 'Want better form management? Save submissions, add file upload, and extend your Divi Form with Contact Form Extender for Divi.', 'events-calendar-modules-for-divi' ),
						$button_html
					),
					'toggle_slug'     => 'ecmd_file_upload_promo',
				);
			}

			$fields_unprocessed['ecmd_marketing_file_upload_promo'] = $field;

			return $fields_unprocessed;
		}

		/**
		 * AJAX: Install the target plugin.
		 */
		public function ajax_install_plugin() {
			check_ajax_referer( 'ecmd_plugin_install', 'security' );

			if ( ! current_user_can( 'install_plugins' ) ) {
				wp_send_json_error( array( 'message' => __( 'You do not have permission to install plugins.', 'events-calendar-modules-for-divi' ) ) );
			}

			if ( empty( $_POST['slug'] ) ) {
				wp_send_json_error( array( 'message' => __( 'Plugin slug missing.', 'events-calendar-modules-for-divi' ) ) );
			}

			$slug = sanitize_text_field( wp_unslash( $_POST['slug'] ) );

			include_once ABSPATH . 'wp-admin/includes/plugin-install.php';
			include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

			$api = plugins_api(
				'plugin_information',
				array(
					'slug'   => $slug,
					'fields' => array(
						'sections' => false,
					),
				)
			);

			if ( is_wp_error( $api ) ) {
				wp_send_json_error( array( 'message' => $api->get_error_message() ) );
			}

			$upgrader = new Plugin_Upgrader( new Automatic_Upgrader_Skin() );
			$result   = $upgrader->install( $api->download_link );

			if ( is_wp_error( $result ) ) {
				wp_send_json_error( array( 'message' => $result->get_error_message() ) );
			}

			if ( ! $result ) {
				wp_send_json_error( array( 'message' => __( 'Installation failed.', 'events-calendar-modules-for-divi' ) ) );
			}

			wp_send_json_success( array( 'message' => __( 'Plugin installed successfully.', 'events-calendar-modules-for-divi' ) ) );
		}

		/**
		 * AJAX: Activate the target plugin.
		 */
		public function ajax_activate_plugin() {
			check_ajax_referer( 'ecmd_plugin_activate', 'security' );

			if ( ! current_user_can( 'activate_plugins' ) ) {
				wp_send_json_error( array( 'message' => __( 'You do not have permission to activate plugins.', 'events-calendar-modules-for-divi' ) ) );
			}

			if ( empty( $_POST['init'] ) ) {
				wp_send_json_error( array( 'message' => __( 'Plugin init file missing.', 'events-calendar-modules-for-divi' ) ) );
			}

			include_once ABSPATH . 'wp-admin/includes/plugin.php';

			$init_file = sanitize_text_field( wp_unslash( $_POST['init'] ) );
			$activate  = activate_plugin( $init_file, '', false, true );

			if ( is_wp_error( $activate ) ) {
				wp_send_json_error( array( 'message' => $activate->get_error_message() ) );
			}

			wp_send_json_success( array( 'message' => __( 'Plugin activated successfully.', 'events-calendar-modules-for-divi' ) ) );
		}

		/**
		 * Determine plugin status.
		 *
		 * @return string not_installed|inactive|active
		 */
		private function get_plugin_status() {
			if ( ! function_exists( 'get_plugins' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}

			$plugin_file = self::TARGET_PLUGIN_INIT;
			$plugins     = get_plugins();
			$installed   = isset( $plugins[ $plugin_file ] );

			if ( $installed && is_plugin_active( $plugin_file ) ) {
				return 'active';
			}

			if ( $installed ) {
				return 'inactive';
			}

			return 'not_installed';
		}
	}

	new ContactFormExtender_Marketing();
}
