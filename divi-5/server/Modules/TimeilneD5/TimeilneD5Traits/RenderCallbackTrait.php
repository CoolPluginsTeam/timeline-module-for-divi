<?php
namespace TMDIVI\Modules\TimeilneD5\TimeilneD5Traits;

if ( ! defined( 'ABSPATH' ) ) {
    die( 'Direct access forbidden.' );
}

use ET\Builder\Packages\Module\Options\Element\ElementComponents;
use ET\Builder\FrontEnd\BlockParser\BlockParserStore;
use TMDIVI\Modules\TimeilneD5\TimeilneD5;
use ET\Builder\Packages\Module\Module;

trait RenderCallbackTrait {
  	public static function render_callback( $attrs, $content, $block, $elements ) {

		$children_ids = $block->parsed_block['innerBlocks'] ? array_map(
			function( $inner_block ) {
				return $inner_block['id'];
			},
			$block->parsed_block['innerBlocks']
		) : [];	

		$horizontal_auto_play = $attrs['horizontal_settings_autoplay']['advanced']['desktop']['value'] ?? '';
		$horizontal_auto_play_speed = $attrs['horizontal_settings_autoplay_speed']['advanced']['desktop']['value'] ?? '';
		$horizontal_slide_spacing = $attrs['horizontal_settings_slide_spacing']['advanced']['desktop']['value'] ?? '28px';
		$horizontal_auto_loop = $attrs['horizontal_settings_loop']['advanced']['desktop']['value'] ?? '';

		$horizontal_slide_to_show = $attrs['horizontal_settings_slide_to_show']['advanced']['desktop']['value'] ?? '2' ;

		$timeline_layout = $attrs['timeline_layout']['advanced']['layout']['desktop']['value'] ?? 'both-side';

		$timeline_line_filling = $attrs['timeline_fill_setting']['advanced']['desktop']['value'] ?? 'off';

		if($timeline_line_filling === 'on'){
			wp_enqueue_script('d5-timeline-line-filling');
		}
		$timeline_layout_class;
		switch($timeline_layout){
		  case "one-side-left":
			$timeline_layout_class = "tmdivi-vertical-right";
			break;
		case "one-side-right":
			$timeline_layout_class = "tmdivi-vertical-left";
			break;
		case "horizontal":
			$timeline_layout_class = "horizontal";
			break;
		default:
			$timeline_layout_class = "both-side";
		}

		switch($timeline_layout){
			case "one-side-left":
				$timelineLayoutClass = "tmdivi-vertical-right";
				break;
			case "one-side-right":
				$timelineLayoutClass = "tmdivi-vertical-left";
				break;
			case "horizontal":
				wp_enqueue_style('d5-timeline-swiper-css');
				wp_enqueue_script('d5-timeline-swiper');
				wp_enqueue_script('d5-timeline-horizontal');
				$timelineLayoutClass = "horizontal";
				break;
			default:
				$timelineLayoutClass = "both-side";
		}

		$layout_html = '';

		if($timeline_layout === 'horizontal'){
			$layout_html .= 
			sprintf(
            '<div class="tmdivi-horizontal-timeline tmdivi-wrapper tmdivi-horizontal-wrapper style-1 tmdivi-bg-simple">
                <div class="tmdivi-wrapper-inside">
                    <div id="tmdivi-slider-container" class="tmdivi-slider-container swiper-container tmdivi-line-filler swiper-container-horizontal" data-slidetoshow="%2$s" data-autoplay="%3$s" data-autoplay-speed="%4$s" data-loop="%5$s" data-space-between="%6$s">
                        <div class="tmdivi-slider-wrapper swiper-wrapper">
							%1$s 
						</div>
                        <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
                    </div>
                </div>
                <div class="tmdivi-button-prev swiper-button-disabled" tabindex="0" role="button" aria-label="Previous slide" aria-disabled="true"><i class="fas fa-chevron-left"></i></div>
                <div class="tmdivi-button-next" tabindex="0" role="button" aria-label="Next slide" aria-disabled="false"><i class="fas fa-chevron-right"></i></div>
                <div class="tmdivi-h-line"></div>
                <div class="tmdivi-line-fill swiper-pagination-progressbar"><span class="swiper-pagination-progressbar-fill"></span></div>
            </div>',
			$content,
            esc_attr($horizontal_slide_to_show),
            esc_attr($horizontal_auto_play),
            esc_attr($horizontal_auto_play_speed), #4
            esc_attr($horizontal_auto_loop), 
            esc_attr($horizontal_slide_spacing) 
        );
		}else{
			$layout_html .= 
			sprintf(
			'<div id="tmdivi-wrapper" class="tmdivi-vertical tmdivi-wrapper %3$s style-1 tmdivi-bg-simple" data-line-filling="%2$s">
					<div class="tmdivi-start"></div>
					<div class="tmdivi-line tmdivi-timeline"> %1$s
						<div class="tmdivi-inner-line" style="height:0px" data-line-fill="%2$s"></div>
					</div>
					<div class="tmdivi-end"></div>
			</div>',
			$content,
			($timeline_line_filling === 'on') ? 'true' : 'false',
			esc_attr($timelineLayoutClass)
			);
		}


		$parent       = BlockParserStore::get_parent( $block->parsed_block['id'], $block->parsed_block['storeInstance'] );
		$parent_attrs = $parent->attrs ?? [];

		return Module::render(
			[
				// FE only.
				'orderIndex'          => $block->parsed_block['orderIndex'],
				'storeInstance'       => $block->parsed_block['storeInstance'],

				// VB equivalent.
				'id'                  => $block->parsed_block['id'],
				'name'                => $block->block_type->name,
				'moduleCategory'      => $block->block_type->category,
				'attrs'               => $attrs,
				'elements'            => $elements,
				'classnamesFunction'  => [ TimeilneD5::class, 'module_classnames' ],
				'scriptDataComponent' => [ TimeilneD5::class, 'module_script_data' ],
				'stylesComponent'     => [ TimeilneD5::class, 'module_styles' ],
				'parentAttrs'         => $parent_attrs,
				'parentId'            => $parent->id ?? '',
				'parentName'          => $parent->blockName ?? '',
				'children'            => ElementComponents::component(
					[
						'attrs'         => $attrs['module']['decoration'] ?? [],
						'id'            => $block->parsed_block['id'],

						// FE only.
						'orderIndex'    => $block->parsed_block['orderIndex'],
						'storeInstance' => $block->parsed_block['storeInstance'],
					]
				// ) . $content,
				) . $layout_html,
				'childrenIds'         => $children_ids,
			]
		);
	}
}
