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

if ( ! class_exists( 'CFE_Marketing' ) ) {

	class CFE_Marketing {

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
			add_action( 'wp_ajax_cfe_plugin_install', 'wp_ajax_install_plugin' );
			add_action( 'wp_ajax_cfe_plugin_activate', array( $this, 'ajax_activate_plugin' ) );
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
				$this->enqueue_editor_script( $vars );
			} 
		}

		/**
		 * Build shared JS config.
		 *
		 * @return array
		 */
		private function get_js_vars() {
			return array(
				'ajaxurl'       => admin_url( 'admin-ajax.php' ),
				'installNonce'  => wp_create_nonce( 'updates' ),
				'activateNonce' => wp_create_nonce( 'cfe_plugin_activate' ),
				'pluginSlug'    => self::TARGET_PLUGIN_SLUG,
				'pluginInit'    => self::TARGET_PLUGIN_INIT,
				'status'        => $this->get_plugin_status(),
			);
		}

		/**
		 * Enqueue assets for Divi 4 builder.
		 *
		 * @param array $vars
		 */
		public function enqueue_editor_script( $vars ) {
			$base = plugins_url( '', __FILE__ );
			$ver  = defined( 'CFE_V' ) ? CFE_V : '1.0';

			wp_enqueue_style(
				'cfe-marketing-contact-form-extender',
				$base . '/marketing-contact-form-extender.css',
				array(),
				$ver
			);

			wp_enqueue_script(
				'cfe-marketing-contact-form-extender',
				$base . '/marketing-contact-form-extender.js',
				array( 'jquery' ),
				$ver,
				true
			);

			wp_localize_script( 'cfe-marketing-contact-form-extender', 'cfe_plugin_vars', $vars );
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
			// Do not show marketing section if plugin is already active.
			if ( 'active' === $this->get_plugin_status() ) {
				return $modules;
			}

			if ( isset( $modules['et_pb_contact_form'] ) ) {
				$modules['et_pb_contact_form']->settings_modal_toggles['general']['toggles']['cfe_marketing_promo'] = array(
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
			$status       = $this->get_plugin_status();

			// If already active, do not show the marketing field at all.
			if ( 'active' === $status ) {
				return $fields_unprocessed;
			}

			if ( $field === null ) {
				if ( 'inactive' === $status ) {
					$button_html = sprintf(
						'<button type="button" class="cfe-d4-promo__btn cfe-activate-plugin-btn" data-init="%s" style="display:inline-block;padding:10px 16px;background:#007cba;border:none;color:#fff;border-radius:4px;font-weight:bold;width:100%%;text-align:center;box-sizing:border-box;cursor:pointer;">%s</button>',
						esc_attr( self::TARGET_PLUGIN_INIT ),
						esc_html__( 'Activate Plugin', 'events-calendar-modules-for-divi' )
					);
				} else {
					$button_html = sprintf(
						'<button type="button" class="cfe-d4-promo__btn cfe-install-plugin-btn" data-slug="%s" data-init="%s" style="display:inline-block;padding:10px 16px;background:#007cba;border:none;color:#fff;border-radius:4px;font-weight:bold;width:100%%;text-align:center;box-sizing:border-box;cursor:pointer;">%s</button>',
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
					'toggle_slug'     => 'cfe_marketing_promo',
				);
			}

			// Internal option key, used only for this marketing field.
			$fields_unprocessed['cfe_marketing_promo_field'] = $field;

			return $fields_unprocessed;
		}

		/**
		 * AJAX: Activate the target plugin.
		 */
		public function ajax_activate_plugin() {
			check_ajax_referer( 'cfe_plugin_activate', 'security' );

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

	new CFE_Marketing();
}
