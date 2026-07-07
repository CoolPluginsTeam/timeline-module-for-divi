<?php
/**
 * Timeline Module for Divi — onboarding wiring.
 *
 * Single-method onboarding via the shared cp-onboarding framework. The CTA
 * creates a draft page with an empty Timeline module and opens the Divi Visual
 * Builder (see tmdivi_onboarding_create_page below).
 *
 * @package TimelineModuleForDivi
 */

use CoolPlugins\Onboarding\Config;
use CoolPlugins\Onboarding\Framework;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Builds the onboarding Config array for Timeline Module for Divi.
 */
final class TMDIVI_Onboarding_Config {

	/**
	 * Plugin text domain.
	 *
	 * @var string
	 */
	private const TEXT_DOMAIN = 'timeline-module-for-divi';

	/**
	 * Build the full config array passed to CoolPlugins\Onboarding\Config.
	 *
	 * @param int  $telemetry_data CTA click count.
	 * @param bool $is_onboarding  Whether onboarding mode is active.
	 * @return array
	 */
	public function build( $telemetry_data, $is_onboarding ) {
		return array_merge(
			$this->identity(),
			array(
				'methods' => array( 'module' => $this->method_divi( $telemetry_data, $is_onboarding ) ),
				'addons'  => $this->addons(),
				'links'   => array( 'footer' => $this->footer_cards( $telemetry_data, $is_onboarding ) ),
			)
		);
	}

	/**
	 * Core plugin identity and page copy.
	 *
	 * @return array
	 */
	private function identity() {
		$td = self::TEXT_DOMAIN;

		return array(
			'slug'            => 'tmdivi',
			'prefix'          => 'tmdivi',
			'text_domain'     => $td,
			'version'         => defined( 'TMDIVI_V' ) ? TMDIVI_V : '1.0.0',
			'plugin_dir'      => defined( 'TMDIVI_DIR' ) ? TMDIVI_DIR : plugin_dir_path( __FILE__ ),
			'plugin_url'      => defined( 'TMDIVI_URL' ) ? TMDIVI_URL : plugin_dir_url( __FILE__ ),
			'parent_slug'     => 'options-general.php',
			'edition'         => 'full',
			'tier'            => 'free',
			'new_user_option' => 'tmdivi_is_new_user',
			'show_chooser'    => false,
			'colors'          => array(
				'primary'      => '#2e9e9d',
				'primary_dark' => '#257f7e',
			),
			'page'            => array(
				'menu_title' => __( 'Getting Started', $td ),
				'heading'    => __( 'Welcome to Timeline Module for Divi!', $td ),
				'subheading' => __( 'Create beautiful timeline layouts in Divi in just a few minutes.', $td ),
				'chooser'    => '',
			),
		);
	}

	/**
	 * Single Divi module onboarding method.
	 *
	 * @param int  $telemetry_data CTA click count.
	 * @param bool $is_onboarding  Whether onboarding mode is active.
	 * @return array
	 */
	private function method_divi( $telemetry_data, $is_onboarding ) {
		$td = self::TEXT_DOMAIN;

		$utm_params = $is_onboarding
			? '?utm_source=tmdivi_plugin&utm_medium=inside&utm_campaign=demo&utm_content=onboarding'
			: '?utm_source=tmdivi_plugin&utm_medium=inside&utm_campaign=demo&utm_content=dashboard';

		$arr_method = array(
			'type'          => 'divi-based',
			'title'         => __( 'Divi Module', $td ),
			'badge'         => __( 'Recommended', $td ),
			'content_badge' => __( 'Best for Divi Users', $td ),
			'description'   => __( 'Create timeline layouts in Divi.', $td ),
			'best_for'      => __( 'Sites built with Divi', $td ),
			'editions'      => array( 'full' ),
			'video'         => array(
				'id'       => 'V9dEoN0PvFI',
				'title'    => __( 'Create a Timeline in Divi', $td ),
				'duration' => '',
			),
			'steps'         => array(
				array(
					'title' => __( 'Add the Timeline Module', $td ),
					'desc'  => __( 'Create a new page — Divi Builder opens automatically and the Timeline module is inserted for you. Pick the layout you want.', $td ),
				),
				array(
					'title' => __( 'Add Timeline Stories', $td ),
					'desc'  => __( 'Click "Add New Story" for each story, then set its date, sub-label, title, description, and a custom image.', $td ),
				),
				array(
					'title' => __( 'Configure Timeline Settings', $td ),
					'desc'  => __( 'In the Design tab, choose the line color and customize labels, year box, and typography — then save and preview your page.', $td ),
				),
			),
			'secondary'     => array(
				'label' => __( 'View Demo', $td ),
				'url'   => 'https://cooltimeline.com/divi/' . $utm_params,
			),
		);

		if ( $this->is_sample_cta_visible( $telemetry_data ) ) {
			$arr_method['cta'] = array(
				'label' => __( 'Create Sample Timeline', $td ),
			);
		}

		return $arr_method;
	}

	/**
	 * Whether the one-time "Create Sample Timeline" CTA should render.
	 *
	 * Shown only on fresh install (`tmdivi_sample_cta_eligible` set to `yes` in
	 * activation). Hidden after the first click (telemetry or AJAX handler).
	 *
	 * @param int $cta_click_count Times the CTA was clicked.
	 * @return bool
	 */
	private function is_sample_cta_visible( $cta_click_count ) {
		if ( $cta_click_count > 0 ) {
			return false;
		}

		return 'yes' === get_option( 'tmdivi_sample_cta_eligible', 'no' );
	}

	/**
	 * Cross-sell addon cards.
	 *
	 * @return array
	 */
	private function addons() {
		return array();
	}

	/**
	 * Footer link cards for the onboarding page.
	 *
	 * @param int  $telemetry_data CTA click count.
	 * @param bool $is_onboarding  Whether onboarding mode is active.
	 * @return array
	 */
	private function footer_cards( $telemetry_data, $is_onboarding ) {
		$td = self::TEXT_DOMAIN;

		$utm_params = $is_onboarding
			? '?utm_source=tmdivi_plugin&utm_medium=inside&utm_campaign=docs&utm_content=onboarding'
			: '?utm_source=tmdivi_plugin&utm_medium=inside&utm_campaign=docs&utm_content=dashboard';

		$cards   = array();
		$cards[] = $this->card(
			'<span class="dashicons dashicons-sos"></span>',
			__( 'Support', $td ),
			__( 'Need help? Our team can assist with setup and troubleshooting.', $td ),
			array(
				array(
					'label' => __( 'Get Support', $td ),
					'class' => 'cpo-button cpo-button-secondary cpo-button-small',
					'url'   => 'https://coolplugins.net/support/' . $utm_params,
				),
			)
		);
		$cards[] = $this->card(
			'<span class="dashicons dashicons-book"></span>',
			__( 'Documentation', $td ),
			'',
			array(
				array(
					'label' => __( 'How to Add Timeline Module', $td ),
					'class' => 'ctl_doc_link',
					'url'   => 'https://cooltimeline.com/doc/add-timeline-module/' . $utm_params,
				),
				array(
					'label' => __( 'FAQs', $td ),
					'class' => 'ctl_doc_link',
					'url'   => 'https://cooltimeline.com/doc/faqs-timeline-module-for-divi/' . $utm_params,
				),
				array(
					'label' => __( 'View All Documentation', $td ),
					'class' => 'ctl_doc_link',
					'url'   => 'https://cooltimeline.com/docs/timeline-module-pro-for-divi/' . $utm_params,
				),
			)
		);
		$cards[] = $this->card(
			'<span class="dashicons dashicons-star-filled"></span>',
			__( 'Your Feedback Matters', $td ),
			__( 'If you\'re happy with the plugin, we\'d greatly appreciate a quick review. Your feedback helps us continue improving it.', $td ),
			array(
				array(
					'label' => __( 'Leave a Review', $td ),
					'url'   => 'https://wordpress.org/support/plugin/timeline-module-for-divi/reviews/#new-post',
					'class' => 'cpo-button cpo-button-secondary cpo-button-small',
				),
			)
		);

		return $cards;
	}

	/**
	 * Build a single footer card.
	 *
	 * @param string $icon  Icon HTML.
	 * @param string $title Card title.
	 * @param string $text  Card body text.
	 * @param array  $links Link rows.
	 * @return array
	 */
	private function card( $icon, $title, $text, array $links ) {
		return array(
			'icon'  => $icon,
			'title' => $title,
			'text'  => $text,
			'links' => $links,
		);
	}
}

$telemetry_data = get_option( 'tmdivi_onboarding_telemetry', array() );
$telemetry_data = isset( $telemetry_data['counters']['cta_clicked.divi-based'] )
	? $telemetry_data['counters']['cta_clicked.divi-based']
	: 0;

// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only screen mode.
$is_onboarding = isset( $_GET['mode'] ) && 'onboarding' === $_GET['mode'];

$builder   = new TMDIVI_Onboarding_Config();
$config    = new Config( $builder->build( $telemetry_data, $is_onboarding ) );
$framework = new Framework( $config );
$framework->init();

add_action(
	'admin_init',
	static function () use ( $framework ) {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only screen detection.
		$page = isset( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : '';
		if ( $framework->page_slug() !== $page ) {
			return;
		}

		global $title;

		if ( ! empty( $title ) ) {
			return;
		}

		$menu_title = $framework->config()->page( 'menu_title' );
		if ( empty( $menu_title ) ) {
			$menu_title = __( 'Getting Started', 'timeline-module-for-divi' );
		}

		$title = $menu_title;
	}
);

add_filter(
	$config->prefix() . '_onboarding_script_data',
	static function ( $data ) {
		$data['action'] = 'tmdivi_onboarding_create_page';

		if ( isset( $data['install']['labels'] ) ) {
			$data['install']['labels'] = array(
				'installing' => __( 'Installing…', 'timeline-module-for-divi' ),
				'activating' => __( 'Activating…', 'timeline-module-for-divi' ),
				'activated'  => __( 'Activated', 'timeline-module-for-divi' ),
				'setupGuide' => __( 'Check Setup Guide', 'timeline-module-for-divi' ),
				'error'      => __( 'Plugin could not be installed. Please try again.', 'timeline-module-for-divi' ),
			);
		}

		return $data;
	}
);

add_filter(
	$config->prefix() . '_onboarding_labels',
	static function ( $labels ) {
		$labels['loading']     = __( 'Please wait…', 'timeline-module-for-divi' );
		$labels['redirecting'] = __( 'Redirecting…', 'timeline-module-for-divi' );
		$labels['error']       = __( 'Something went wrong. Please try again.', 'timeline-module-for-divi' );
		return $labels;
	}
);

add_action(
	'wp_ajax_' . $config->ajax_action( 'track' ),
	static function () use ( $config ) {
		check_ajax_referer( $config->option( 'track' ), 'nonce' );

		if ( ! current_user_can( $config->capability() ) ) {
			return;
		}

		delete_option( $config->new_user_option() );

		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- verified above.
		$event = isset( $_POST['event'] ) ? sanitize_key( wp_unslash( $_POST['event'] ) ) : '';
		if ( 'cta_clicked' === $event ) {
			update_option( 'tmdivi_sample_cta_eligible', 'no', false );
		}
	},
	5
);

add_action(
	'wp_ajax_tmdivi_onboarding_create_page',
	static function () use ( $framework ) {
		$cfg = $framework->config();

		check_ajax_referer( $cfg->option( 'prepare' ), 'nonce' );

		if ( ! current_user_can( $cfg->capability() ) ) {
			wp_send_json_error( array( 'message' => __( 'Unauthorized.', 'timeline-module-for-divi' ) ), 403 );
		}

		$ver     = defined( 'ET_BUILDER_VERSION' ) ? ET_BUILDER_VERSION : '4.0.0';
		$attr    = '_builder_version="' . $ver . '"';
		$content = sprintf(
			'[et_pb_section fb_built="1" %1$s][et_pb_row %1$s][et_pb_column type="4_4" %1$s][/et_pb_column][/et_pb_row][/et_pb_section]',
			$attr
		);

		$page_id = wp_insert_post(
			array(
				'post_type'    => 'page',
				'post_status'  => 'draft',
				'post_title'   => __( 'My Timeline', 'timeline-module-for-divi' ),
				'post_content' => $content,
				'post_author'  => get_current_user_id(),
			),
			true
		);

		if ( is_wp_error( $page_id ) || ! $page_id ) {
			wp_send_json_error(
				array( 'message' => __( 'Could not create the page.', 'timeline-module-for-divi' ) ),
				500
			);
		}

		update_post_meta( $page_id, '_et_pb_use_builder', 'on' );
		update_post_meta( $page_id, '_et_pb_built_for_post_type', 'page' );

		$page_url = get_permalink( $page_id ) ?: get_preview_post_link( $page_id );
		$redirect = function_exists( 'et_fb_get_vb_url' )
			? et_fb_get_vb_url( $page_url )
			: add_query_arg( array( 'post' => $page_id, 'action' => 'edit', 'et_fb' => '1' ), admin_url( 'post.php' ) );

		if ( function_exists( 'et_fb_prepare_ssl_link' ) ) {
			$redirect = et_fb_prepare_ssl_link( $redirect );
		}

		$redirect = add_query_arg( 'tmdivi_onboarding', '1', $redirect );

		update_option( 'tmdivi_sample_cta_eligible', 'no', false );
		delete_option( $cfg->new_user_option() );

		wp_send_json_success(
			array(
				'redirectUrl' => esc_url_raw( $redirect ),
			)
		);
	}
);

add_action(
	'divi_visual_builder_assets_before_enqueue_scripts',
	static function () {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- VB onboarding trigger.
		if ( ! isset( $_GET['tmdivi_onboarding'] ) || '1' !== $_GET['tmdivi_onboarding'] ) {
			return;
		}

		wp_enqueue_script(
			'tmdivi-vb-inserter',
			( defined( 'TMDIVI_URL' ) ? TMDIVI_URL : plugin_dir_url( dirname( __DIR__ ) . '/onboarding-config.php' ) ) . 'admin/cp-onboarding/assets/vb-inserter.js',
			array(),
			defined( 'TMDIVI_V' ) ? TMDIVI_V : '1.0.0',
			true
		);
	}
);
