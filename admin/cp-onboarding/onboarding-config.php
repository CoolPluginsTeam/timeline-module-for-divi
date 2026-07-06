<?php
/**
 * Timeline Widget for Elementor — onboarding wiring.
 *
 * A single-method ("widget") onboarding flow that reuses the SAME shared
 * cp-onboarding framework as Cool Timeline. There is no method chooser
 * (`show_chooser => false`): the page renders the video + steps for the one
 * Elementor widget method, and the CTA generates a pre-filled Elementor draft
 * page (see twae_onboarding_create_page below). No framework code changes.
 *
 * @package TimelineWidget
 */

use CoolPlugins\Onboarding\Config;
use CoolPlugins\Onboarding\Framework;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Builds the onboarding Config array for Timeline Widget for Elementor.
 */
final class TWAE_Onboarding_pro_Config {

	/**
	 * Plugin text domain.
	 *
	 * @var string
	 */
	private const TEXT_DOMAIN = 'timeline-widget-addon-for-elementor';

	/**
	 * Build the full config array passed to CoolPlugins\Onboarding\Config.
	 *
	 * @return array
	 */
	public function build($telemetry_data, $is_onboarding) {
	
		return array_merge(
			$this->identity(),
			array(
				'methods' => array( 'widget' => $this->method_widget($telemetry_data, $is_onboarding) ),
				'addons'  => $this->addons(),
				'links'   => array( 'footer' => $this->footer_cards($telemetry_data, $is_onboarding	) ),
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
			'slug'            => 'twae',
			'prefix'          => 'twae',
			'text_domain'     => $td,
			'version'         => '1.0.0',
			// Plugin ROOT path/URL (trailing-slashed). The framework appends
			// 'admin/cp-onboarding/framework/assets/' itself, so passing the root is required.
			'plugin_dir'      => defined( 'TWAE_PRO_PATH' ) ? TWAE_PRO_PATH : plugin_dir_path( __FILE__ ),
			'plugin_url'      => $this->plugin_url(),
			'parent_slug'     => 'options-general.php',
			'edition'         => 'full',
			//'edition'       => 'liter',   // <- the only structural difference.
			'tier'            => 'pro',
			// Free addon: show the "Getting Started" submenu only for fresh installs.
			'only_new_user'   => true,
			'new_user_option' => 'twae_is_new_user',
			// Single method — no picker: just video + steps for the one widget flow.
			'show_chooser'    => false,
			'colors'          => array(
				'primary'      => '#2e9e9d',
				'primary_dark' => '#257f7e',
			),
			'page'            => array(
				'menu_title' => __( 'Timeline Addons', $td ),
				'heading'    => __( 'Welcome to Timeline Widget for Elementor Pro !', $td ),
				'subheading' => __( 'Create beautiful timeline layouts in Elementor in just a few minutes.', $td ),
				'chooser'    => '',
			),
		);
	}

	/**
	 * Plugin asset base URL.
	 *
	 * @return string
	 */
	private function plugin_url() {
		return defined( 'TWAE_PRO_URL' ) ? TWAE_PRO_URL : plugin_dir_url( __FILE__ );
	}

	/**
	 * Single Elementor widget onboarding method.
	 *
	 * @param int  $telemetry_data CTA click count.
	 * @param bool $is_onboarding  Whether onboarding mode is active.
	 * @return array
	 */
	private function method_widget($telemetry_data, $is_onboarding) {
		$td = self::TEXT_DOMAIN;
	
		$utm_params = '';
		if($is_onboarding === false){
			$utm_params = '?utm_source=twae_plugin&utm_medium=inside&utm_campaign=demo&utm_content=dashboard';
		}else{
			$utm_params = '?utm_source=twae_plugin&utm_medium=inside&utm_campaign=demo&utm_content=onboarding';
		}

		$arr_method = array(
			'type'          => 'elementor-based',
			'title'         => __( 'Elementor Widget', $td ),
			'badge'         => __( 'Recommended', $td ),
			'content_badge' => __( 'Best for Elementor Users', $td ),
			'description'   => __( 'Create Timeline Layouts in Elementor.', $td ),
			'best_for'      => __( 'Sites built with Elementor', $td ),
			//'time_estimate' => __( '~1 min', $td ),
			'editions'      => array( 'full' ),
			'video'         => array(
				'id'       => 'mau6jLJZY1s',
				'title'    => __( 'Create a Timeline in Elementor', $td ),
				'duration' => __( '', $td ),
			),
			'steps'         => array(
				array(
					'title' => __( 'Add the Timeline Widget', $td ),
					'desc'  => __( 'Create a new page and edit it with Elementor, then drag in the Story Timeline widget and pick the layout you want.', $td ),
				),
				array(
					'title' => __( 'Add Timeline Stories', $td ),
					'desc'  => __( 'Click "Add Item" for each story, then set its date, sub-label, title, description, and a custom image.', $td ),
				),
				array(
					'title' => __( 'Configure Timeline Settings', $td ),
					'desc'  => __( 'In the Style tab, choose the line color and customize the Label, Year Box, and typography — then save and preview your page.', $td ),
				),
			),
			
			'secondary'    => array(
				'label' => __( 'View Demo', $td ),
				'url'   => 'https://cooltimeline.com/demo/elementor-timeline-widget/'.$utm_params,
			),
			'redirect_url' => admin_url( 'edit.php?post_type=page' ),
			'fallback_url' => admin_url( 'edit.php?post_type=page' ),
		);
		if ( 'yes' === get_option( 'twae_sample_cta_eligible' ) ) {
			$arr_method['cta'] = array( 'label' => __( 'Create Sample Timeline', $td ) );
		}

		return $arr_method;
	}

	/**
	 * Cross-sell addon cards for the bottom section.
	 *
	 * @return array
	 */
	private function addons() {
		return array();
	}

	/**
	 * Free Cool Timeline cross-sell card.
	 *
	 * @return array
	 */
/*	private function addon_cool_timeline() {
		$td = self::TEXT_DOMAIN;

		return array(
			'slug'           => 'cool-timeline',
			'type'           => 'free',
			'group'         => 'elementor-based',
			'install_method' => 'manually',
			'title'          => __( 'Cool Timeline', $td ),
			'description'    => __( 'Create beautiful timeline directly in the Block Editor.Perfect for company history, roadmaps, stories.', $td ),
			'icon'           => $this->plugin_url() . 'assets/images/cool-timeline.png',
			'setup_url'      => admin_url( 'admin.php?page=ctl-getting-started' ),
			'learn_more'     => 'https://cooltimeline.com/docs/cool-timeline-pro/?utm_source=twae_plugin&utm_medium=inside&utm_campaign=get_pro&utm_content=onboarding',
		);
	}	*/

	/**
	 * Footer link cards for the onboarding page.
	 *
	 * @return array
	 */
	private function footer_cards($telemetry_data, $is_onboarding) {
		$td = self::TEXT_DOMAIN;

		
		$utm_params = '';
		if($is_onboarding === false){
			$utm_params = '?utm_source=twae_plugin&utm_medium=inside&utm_campaign=docs&utm_content=dashboard	';
		}else{
			$utm_params = '?utm_source=twae_plugin&utm_medium=inside&utm_campaign=docs&utm_content=onboarding';
		}

		$cards = array();

		$cards[] = $this->card(
			'<span class="dashicons dashicons-sos"></span>',
			__( 'Support', $td ),
			__( 'Need help? Our team can assist with setup and troubleshooting.', $td ),
			array(
				array(
					'label' => __( 'Get Support', $td ),
					'class' => 'cpo-button cpo-button-secondary cpo-button-small',
					'url'   => 'https://coolplugins.net/support/'.$utm_params,
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
					'url'   => 'https://cooltimeline.com/doc/create-story-timeline/'.$utm_params,
				),
				array(
					'label' => __( 'How to create Horizontal Timeline', $td ),
					'class' => 'ctl_doc_link',
					'url'   => 'https://cooltimeline.com/doc/horizontal-timeline-story/'.$utm_params,
				),
				array(
					'label' => __( 'FAQs', $td ),
					'class' => 'ctl_doc_link',
					'url'   => 'https://cooltimeline.com/doc/faqs-timeline-widget-for-elementor/'.$utm_params,
				),
				array(
					'label' => __( 'View All Documentation', $td ),
					'class' => 'ctl_doc_link',
					'url'   => 'https://cooltimeline.com/docs/timeline-widget-pro-addon-for-elementor/'.$utm_params,
				),
			)
		);

		$cards[] = $this->card(
			'<span class="dashicons dashicons-star-filled"></span>',
			__( 'Your Feedback Matters', $td ),
			__( 'If you \'re happy with the plugin, we \'d greatly appreciate a quick review. Your feedback helps us continue improving it', $td ),
			array(
				array(
					'label' => __( 'Leave a Review', $td ),
					'url'   => 'https://wordpress.org/support/plugin/timeline-widget-addon-for-elementor/reviews/#new-post',
					'class' => 'cpo-button cpo-button-secondary cpo-button-small',
				),
			)
		);

		return $cards;
	}

	/**
	 * Build a single footer card.
	 *
	 * @param string $icon  Emoji or icon character.
	 * @param string $title Card title.
	 * @param string $text  Card body text.
	 * @param array  $links Link rows (label + url).
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

$telemetry_data   = get_option( 'twae_onboarding_telemetry', array() );

$telemetry_data = isset( $telemetry_data['counters']['cta_clicked.widget-based'] )
	? $telemetry_data['counters']['cta_clicked.widget-based']
	: 0;	

$is_onboarding = isset($_GET['mode']) && $_GET['mode'] === 'onboarding' ? true : false;

$builder   = new TWAE_Onboarding_pro_Config();
$config    = new Config( $builder->build($telemetry_data, $is_onboarding) );
$framework = new Framework( $config );
$framework->init();

// Ensure $title is set before admin-header.php even if another plugin's framework
// copy wins the shared versioned loader at the same semver.
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
			$menu_title = __( 'Timeline Addons','twae' );
		}

		$title = $menu_title;
	}
);

/*
 * CTA action: create a draft Elementor page with the Timeline widget pre-inserted,
 * then open it in the Elementor editor.
 **/

add_filter(
	$config->prefix() . '_onboarding_script_data',
	function ( $data ) {
		$data['action'] = 'twae_onboarding_create_page';

		if ( isset( $data['install']['labels'] ) ) {
			$data['install']['labels'] = array(
				'installing' => __( 'Installing…','twae' ),
				'activating' => __( 'Activating…','twae' ),
				'activated'  => __( 'Activated','twae' ),
				'setupGuide' => __( 'Check Setup Guide','twae' ),
				'error'      => __( 'Plugin could not be installed. Please try again.','twae' ),
			);
		}

		return $data;
	}
);

add_filter(
	$config->prefix() . '_onboarding_labels',
	static function ( $labels ) {
		$labels['loading']     = __( 'Creating Timeline…','twae' );
		$labels['redirecting'] = __( 'Redirecting…','twae' );
		$labels['error']       = __( 'Something went wrong. Please try again.','twae' );
		return $labels;
	}
);

// When the user performs any action on the Getting Started page, they are no
// longer a "new user" — drop the flag so the onboarding submenu stops showing.
// Runs before the framework's ajax_track (priority 5 < 10) because that handler
// ends in wp_send_json_success() and exits.
add_action(
	'wp_ajax_' . $config->ajax_action( 'track' ),
	static function () use ( $config ) {
		check_ajax_referer( $config->option( 'track' ), 'nonce' );

		if ( current_user_can( $config->capability() ) ) {
			delete_option( $config->new_user_option() );
		}
		// No response here — let the framework's ajax_track send the JSON.
	},
	5
);

add_action(
	'wp_ajax_twae_onboarding_create_page',
	function () use ( $framework ) {
		$cfg = $framework->config();

		check_ajax_referer( $cfg->option( 'prepare' ), 'nonce' );

		if ( ! current_user_can( $cfg->capability() ) ) {
			wp_send_json_error( array( 'message' => __( 'Unauthorized.','twae' ) ), 403 );
		}

			delete_option($cfg->new_user_option());
			delete_option('twae_sample_cta_eligible');
		// Reuse a previously created draft only if it still exists AND still contains the
		// timeline widget (avoid clutter on repeat clicks). A page whose Elementor content
		// was emptied/deleted is treated as stale and regenerated, so the user is never sent
		// to a blank page. (Same staleness rule as the Cool Timeline demo generator.)
		$existing = (int) get_option( 'twae_onboarding_demo_page_id' );
		if ( $existing && get_post( $existing ) && 'trash' !== get_post_status( $existing )
			&& twae_onboarding_page_has_timeline( $existing )
			&& ! apply_filters( 'twae_onboarding_force_new_page', false ) ) {
			wp_send_json_success( array( 'redirectUrl' => twae_onboarding_elementor_edit_url( $existing ) ) );
		}

		$page_id = twae_onboarding_create_timeline_page();
		if ( is_wp_error( $page_id ) || ! $page_id ) {
			wp_send_json_error( array( 'message' => __( 'Could not create the page.','twae' ) ), 500 );
		}

		update_option( 'twae_onboarding_demo_page_id', (int) $page_id, false );
		wp_send_json_success( array( 'redirectUrl' => twae_onboarding_elementor_edit_url( $page_id ) ) );
	}
);

if ( ! function_exists( 'twae_onboarding_elementor_edit_url' ) ) {
	/**
	 * Build the Elementor editor URL for a given post.
	 *
	 * @param int $id Post ID.
	 * @return string
	 */
	function twae_onboarding_elementor_edit_url( $id ) {
		return add_query_arg(
			array(
				'post'   => (int) $id,
				'action' => 'elementor',
			),
			admin_url( 'post.php' )
		);
	}
}

if ( ! function_exists( 'twae_onboarding_page_has_timeline' ) ) {
	/**
	 * Whether a page still contains the Story Timeline widget in its Elementor data.
	 *
	 * Used to detect a "stale" demo page (content emptied/deleted) so a fresh one is
	 * created instead of redirecting the user to a blank page.
	 *
	 * @param int $id Post ID.
	 * @return bool True when the page's Elementor data references the timeline widget.
	 */
	function twae_onboarding_page_has_timeline( $id ) {
		$data = get_post_meta( (int) $id, '_elementor_data', true );

		if ( empty( $data ) || ! is_string( $data ) ) {
			return false;
		}

		return false !== strpos( $data, 'timeline-widget-addon' );
	}
}

if ( ! function_exists( 'twae_onboarding_build_timeline_data' ) ) {
	/**
	 * Build the Elementor element tree (section > column > Timeline widget) pre-filled
	 * with a few sample stories so the page renders fully populated.
	 *
	 * @return array
	 */
	function twae_onboarding_build_timeline_data() {
		$rid = static function () {
			return wp_generate_password( 8, false, false );
		};

		$icon = array(
			'value'   => 'far fa-clock',
			'library' => 'solid',
		);

		$stories = array(
			array(
				'_id'              => $rid(),
				'twae_date_label'  => __( 'Step 1','twae' ),
				'twae_extra_label' => __( 'Get Started','twae' ),
				'twae_story_title' => __( 'Add the Timeline Widget','twae' ),
				'twae_description' => __( 'Create a new page, edit it with Elementor, then drag in the Story Timeline widget and pick your layout.','twae' ),
				'twae_media'       => 'image',
				'twae_image'       => array(
					'url' => TWAE_PRO_URL . 'assets/images/amazon1.jpg',
					'id'  => '',
				),
				'twae_icon_type'   => 'icon',
				'twae_story_icon'  => $icon,
			),
			array(
				'_id'              => $rid(),
				'twae_date_label'  => __( 'Step 2','twae' ),
				'twae_extra_label' => __( 'Add Stories','twae' ),
				'twae_story_title' => __( 'Add Timeline Stories','twae' ),
				'twae_description' => __( 'Click "Add Item" for each story, then set its date, sub-label, title, description and a custom image.','twae' ),
				'twae_media'       => 'image',
				'twae_image'       => array(
					'url' => TWAE_PRO_URL . 'assets/images/amazon2.jpg',
					'id'  => '',
				),
				'twae_icon_type'   => 'icon',
				'twae_story_icon'  => $icon,
			),
			array(
				'_id'              => $rid(),
				'twae_date_label'  => __( 'Step 3','twae' ),
				'twae_extra_label' => __( 'Customize','twae' ),
				'twae_story_title' => __( 'Configure Timeline Settings','twae' ),
				'twae_description' => __( 'In the Style tab choose the line color and customize the Label, Year Box and typography, then save and preview.','twae' ),
				'twae_media'       => 'image',
				'twae_image'       => array(
					'url' => TWAE_PRO_URL . 'assets/images/amazon3.png',
					'id'  => '',
				),
				'twae_icon_type'   => 'icon',
				'twae_story_icon'  => $icon,
			),
		);

		$widget = array(
			'id'         => $rid(),
			'elType'     => 'widget',
			'widgetType' => 'timeline-widget-addon',
			'settings'   => array(
				'twae_layout' => 'centered',
				'twae_list'   => $stories,
			),
			'elements'   => array(),
		);

		$column = array(
			'id'       => $rid(),
			'elType'   => 'column',
			'settings' => array( '_column_size' => 100 ),
			'elements' => array( $widget ),
		);

		$section = array(
			'id'       => $rid(),
			'elType'   => 'section',
			'settings' => array(),
			'elements' => array( $column ),
		);

		return array( $section );
	}
}

if ( ! function_exists( 'twae_onboarding_create_timeline_page' ) ) {
	/**
	 * Create a draft page in Elementor builder mode containing the Timeline widget.
	 *
	 * @return int|\WP_Error Page ID on success.
	 */
	function twae_onboarding_create_timeline_page() {
		// wp_slash because wp_insert_post -> update_post_meta unslashes once
		// (same pattern as includes/migration/twae-migration.php).
		$data = wp_slash( wp_json_encode( twae_onboarding_build_timeline_data() ) );

		return wp_insert_post(
			array(
				'post_type'    => 'page',
				'post_title'   => __( 'My Timeline','twae' ),
				'post_status'  => 'draft',
				'post_content' => '',
				'meta_input'   => array(
					'_elementor_edit_mode'     => 'builder',
					'_elementor_template_type' => 'wp-page',
					'_elementor_version'       => defined( 'ELEMENTOR_VERSION' ) ? ELEMENTOR_VERSION : '',
					'_elementor_data'          => $data,
				),
			),
			true
		);
	}
}

