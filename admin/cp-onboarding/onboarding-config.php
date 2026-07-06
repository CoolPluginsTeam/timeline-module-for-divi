<?php
/**
 * Timeline Module for Divi — onboarding wiring.
 *
 * Single-method onboarding via the shared cp-onboarding framework. The CTA
 * creates a pre-filled Divi page (see tmdivi_onboarding_create_page below).
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
			'parent_slug'     => 'cool-plugins-timeline-addon',
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
				'id'       => 'mau6jLJZY1s',
				'title'    => __( 'Create a Timeline in Divi', $td ),
				'duration' => '',
			),
			'steps'         => array(
				array(
					'title' => __( 'Add the Timeline Module', $td ),
					'desc'  => __( 'Create a new page and edit it with the Divi Builder, then add the Timeline module and pick the layout you want.', $td ),
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
			'redirect_url'  => admin_url( 'edit.php?post_type=page' ),
			'fallback_url'  => admin_url( 'edit.php?post_type=page' ),
			'cta'           => array(
				'label' => __( 'Create Sample Timeline', $td ),
			),
		);

		return $arr_method;
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
					'label' => __( 'How to Create Stories', $td ),
					'class' => 'ctl_doc_link',
					'url'   => 'https://cooltimeline.com/doc/create-story-timeline/' . $utm_params,
				),
				array(
					'label' => __( 'FAQs', $td ),
					'class' => 'ctl_doc_link',
					'url'   => 'https://cooltimeline.com/doc/faqs-timeline-module-for-divi/' . $utm_params,
				),
				array(
					'label' => __( 'View All Documentation', $td ),
					'class' => 'ctl_doc_link',
					'url'   => 'https://cooltimeline.com/docs/timeline-module-for-divi/' . $utm_params,
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
		$labels['loading']     = __( 'Creating Timeline…', 'timeline-module-for-divi' );
		$labels['redirecting'] = __( 'Redirecting…', 'timeline-module-for-divi' );
		$labels['error']       = __( 'Something went wrong. Please try again.', 'timeline-module-for-divi' );
		return $labels;
	}
);

add_action(
	'wp_ajax_' . $config->ajax_action( 'track' ),
	static function () use ( $config ) {
		check_ajax_referer( $config->option( 'track' ), 'nonce' );

		if ( current_user_can( $config->capability() ) ) {
			delete_option( $config->new_user_option() );
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

		delete_option( $cfg->new_user_option() );
		delete_option( 'tmdivi_sample_cta_eligible' );

		$existing = (int) get_option( 'tmdivi_onboarding_demo_page_id' );
		if (
			$existing
			&& get_post( $existing )
			&& 'trash' !== get_post_status( $existing )
			&& tmdivi_onboarding_page_has_timeline( $existing )
			&& ! apply_filters( 'tmdivi_onboarding_force_new_page', false )
		) {
			wp_send_json_success(
				array(
					'redirectUrl' => tmdivi_onboarding_divi_edit_url( $existing ),
				)
			);
		}

		$page_id = tmdivi_onboarding_create_timeline_page();
		if ( is_wp_error( $page_id ) || ! $page_id ) {
			wp_send_json_error(
				array( 'message' => __( 'Could not create the page.', 'timeline-module-for-divi' ) ),
				500
			);
		}

		update_option( 'tmdivi_onboarding_demo_page_id', (int) $page_id, false );
		wp_send_json_success(
			array(
				'redirectUrl' => tmdivi_onboarding_divi_edit_url( $page_id ),
			)
		);
	}
);

if ( ! function_exists( 'tmdivi_onboarding_divi_edit_url' ) ) {
	/**
	 * Build the Divi Visual Builder URL for a given post.
	 *
	 * @param int $id Post ID.
	 * @return string
	 */
	function tmdivi_onboarding_divi_edit_url( $id ) {
		return add_query_arg(
			array(
				'post'   => (int) $id,
				'action' => 'edit',
				'et_fb'  => '1',
			),
			admin_url( 'post.php' )
		);
	}
}

if ( ! function_exists( 'tmdivi_onboarding_page_has_timeline' ) ) {
	/**
	 * Whether a page still contains the Timeline module in its Divi content.
	 *
	 * @param int $id Post ID.
	 * @return bool
	 */
	function tmdivi_onboarding_page_has_timeline( $id ) {
		$post = get_post( (int) $id );
		if ( ! $post || empty( $post->post_content ) ) {
			return false;
		}

		return false !== strpos( $post->post_content, 'tmdivi_timeline' )
			&& false !== strpos( $post->post_content, 'tmdivi_timeline_story' );
	}
}

if ( ! function_exists( 'tmdivi_onboarding_story_shortcode' ) ) {
	/**
	 * Build a single tmdivi_timeline_story shortcode.
	 *
	 * @param array $attrs Story field values.
	 * @return string
	 */
	function tmdivi_onboarding_story_shortcode( array $attrs ) {
		$slug      = 'tmdivi_timeline_story';
		$shortcode = sprintf( '[%s', $slug );

		foreach ( $attrs as $key => $value ) {
			if ( '' === $value ) {
				continue;
			}
			$shortcode .= sprintf( ' %s="%s"', $key, $value );
		}

		return $shortcode . sprintf( '][/%s]', $slug );
	}
}

if ( ! function_exists( 'tmdivi_onboarding_build_timeline_shortcode' ) ) {
	/**
	 * Build Divi section/row/column shortcodes with a pre-filled Timeline module.
	 *
	 * @return string
	 */
	function tmdivi_onboarding_build_timeline_shortcode() {
		$td = 'timeline-module-for-divi';

		$stories = array(
			array(
				'story_title' => __( 'Add the Timeline Module', $td ),
				'label_date'  => __( 'Step 1', $td ),
				'sub_label'   => __( 'Get Started', $td ),
				'content'     => __(
					'Create a new page, enable the Divi Builder, then add the Timeline module and pick your layout.',
					$td
				),
			),
			array(
				'story_title' => __( 'Add Timeline Stories', $td ),
				'label_date'  => __( 'Step 2', $td ),
				'sub_label'   => __( 'Add Stories', $td ),
				'content'     => __(
					'Click Add New Story for each story, then set its date, sub-label, title, description and a custom image.',
					$td
				),
			),
			array(
				'story_title' => __( 'Configure Timeline Settings', $td ),
				'label_date'  => __( 'Step 3', $td ),
				'sub_label'   => __( 'Customize', $td ),
				'content'     => __(
					'In the Design tab choose the line color and customize labels, year box and typography, then save and preview.',
					$td
				),
			),
		);

		$children = implode( '', array_map( 'tmdivi_onboarding_story_shortcode', $stories ) );
		$timeline = sprintf( '[tmdivi_timeline timeline_layout="both-side"]%s[/tmdivi_timeline]', $children );

		return sprintf(
			'[et_pb_section fb_built="1"][et_pb_row][et_pb_column type="4_4"]%s[/et_pb_column][/et_pb_row][/et_pb_section]',
			$timeline
		);
	}
}

if ( ! function_exists( 'tmdivi_onboarding_create_timeline_page' ) ) {
	/**
	 * Create a page in Divi builder mode containing the Timeline module.
	 *
	 * @return int|\WP_Error Page ID on success.
	 */
	function tmdivi_onboarding_create_timeline_page() {
		$content = tmdivi_onboarding_build_timeline_shortcode();

		if ( function_exists( 'et_fb_process_shortcode' ) ) {
			$processed = et_fb_process_shortcode( $content );
			if ( ! empty( $processed ) && is_string( $processed ) ) {
				$content = $processed;
			}
		}

		$page_id = wp_insert_post(
			array(
				'post_type'    => 'page',
				'post_title'   => __( 'My Timeline', 'timeline-module-for-divi' ),
				'post_status'  => 'publish',
				'post_content' => $content,
			),
			true
		);

		if ( is_wp_error( $page_id ) || ! $page_id ) {
			return $page_id;
		}

		update_post_meta( $page_id, '_et_pb_use_builder', 'on' );
		update_post_meta( $page_id, '_et_pb_built_for_post_type', 'page' );
		update_post_meta( $page_id, '_et_pb_page_layout', 'et_no_sidebar' );

		if ( defined( 'ET_BUILDER_VERSION' ) ) {
			update_post_meta( $page_id, '_et_pb_builder_version', ET_BUILDER_VERSION );
		}

		return (int) $page_id;
	}
}
