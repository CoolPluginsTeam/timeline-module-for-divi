<?php
namespace TMDIVI\Modules\TimeilneD5\TimeilneD5Traits;

if ( ! defined( 'ABSPATH' ) ) {
    die( 'Direct access forbidden.' );
}

use ET\Builder\FrontEnd\Module\Style;
use ET\Builder\Packages\Module\Layout\Components\StyleCommon\CommonStyle;
use ET\Builder\Packages\Module\Options\Css\CssStyle;

trait ModuleStylesTrait {

  use CustomCssTrait;

  public static function module_styles( $args ) {
		$attrs        = $args['attrs'] ?? [];
		$parent_attrs = $args['parentAttrs'] ?? [];
		$order_class  = $args['orderClass'];
		$elements     = $args['elements'];
		$settings     = $args['settings'] ?? [];

		$icon_selector = "{$order_class} .et-pb-icon";

		Style::add(
			[
				'id'            => $args['id'],
				'name'          => $args['name'],
				'orderIndex'    => $args['orderIndex'],
				'storeInstance' => $args['storeInstance'],
				'styles'        => [
					// Module.
					$elements->style(
						[
							'attrName'   => 'module',
							'styleProps' => [
								'disabledOn' => [
									'disabledModuleVisibility' => $settings['disabledModuleVisibility'] ?? null,
								],
							],
						]
					),
					CssStyle::style(
						[
							'selector'  => $args['orderClass'],
							'attr'      => $attrs['css'] ?? [],
							'cssFields' => self::custom_css(),
						]
					),
					// Timeline Story background color
					CommonStyle::style(
						[
							'selector'            => $order_class . ' .tmdivi-story .tmdivi-content, '.$order_class . ' .tmdivi-story > .tmdivi-arrow',
							'attr'                => $attrs['story_background_color']['advanced'] ?? [],
							'declarationFunction' => function ( $declaration_function_args ) {
								$attr_value = $declaration_function_args['attrValue'] ?? [];
								return "background: {$attr_value};";
							},
						]
					),
					// Timeline Story Border Color
					// CommonStyle::style([
					// 		'selector'            => $order_class . ' .tmdivi-story .tmdivi-content',
					// 		'attr'                => $attrs['story_border_settings']['advanced'] ?? [],
					// 		'declarationFunction' => function ($declaration_function_args) {
					// 			$css = '';
					// 			$attr_value = $declaration_function_args['attrValue'] ?? [];
					// 			// Extract `styles.all`
					// 			$all_styles = $attr_value['styles']['all'] ?? [];
					// 			if (!empty($all_styles)) {
					// 				// Border width and color
					// 				if (isset($all_styles['width'])) {
					// 					$width = $all_styles['width'];
					// 					$color = $all_styles['color'] ?? '#666666';
					// 					$style = $all_styles['style'] ?? 'solid';
										
					// 					if ((int) str_replace('px', '', $width) > 0) {
					// 						$css .= "border-width: {$width}; border-color: {$color}; border-style: {$style};";
					// 					} else {
					// 						$css .= "border-width: {$width}; border-color: transparent; border-style: {$style};";
					// 					}
					// 				}
					// 			}
					// 			// Border radius
					// 			$radius = $attr_value['radius'] ?? [];
					// 			if (!empty($radius)) {
					// 				$top_left = $radius['topLeft'] ?? '0';
					// 				$top_right = $radius['topRight'] ?? '0';
					// 				$bottom_right = $radius['bottomRight'] ?? '0';
					// 				$bottom_left = $radius['bottomLeft'] ?? '0';
									
					// 				$css .= "border-radius: {$top_left} {$top_right} {$bottom_right} {$bottom_left};";
					// 			}
								
					// 			return $css;
					// 		},
					// 	]
					// ),
					CommonStyle::style([
						'selector'            => $order_class . ' .tmdivi-story .tmdivi-content',
						'attr'                => $attrs['story_border_settings']['advanced'] ?? [],
						'declarationFunction' => function ($declaration_function_args) {
							$css = '';
							$attr_value = $declaration_function_args['attrValue'] ?? [];
							
							// Extract `styles.all` and side-specific styles
							$all_styles = $attr_value['styles']['all'] ?? [];
							$top_styles = $attr_value['styles']['top'] ?? [];
							$right_styles = $attr_value['styles']['right'] ?? [];
							$bottom_styles = $attr_value['styles']['bottom'] ?? [];
							$left_styles = $attr_value['styles']['left'] ?? [];
							
							// Default global border styles (styles.all)
							if (!empty($all_styles)) {
								$width = $all_styles['width'] ?? '0px';
								$color = $all_styles['color'] ?? '#666666';
								$style = $all_styles['style'] ?? 'solid';
					
								// Ensure a valid width before applying global styles
								if ((int) str_replace('px', '', $width) > 0) {
									$css .= "border-width: {$width}; border-color: {$color}; border-style: {$style};";
								} else {
									$css .= "border-width: {$width}; border-color: transparent; border-style: {$style};";
								}
							}
					
							// Override global styles with individual side-specific settings
							if (!empty($top_styles) || !empty($right_styles) || !empty($bottom_styles) || !empty($left_styles)) {
								$top_width = $top_styles['width'] ?? $all_styles['width'] ?? '0px';
								$right_width = $right_styles['width'] ?? $all_styles['width'] ?? '0px';
								$bottom_width = $bottom_styles['width'] ?? $all_styles['width'] ?? '0px';
								$left_width = $left_styles['width'] ?? $all_styles['width'] ?? '0px';
					
								$top_color = $top_styles['color'] ?? $all_styles['color'] ?? 'transparent';
								$right_color = $right_styles['color'] ?? $all_styles['color'] ?? 'transparent';
								$bottom_color = $bottom_styles['color'] ?? $all_styles['color'] ?? 'transparent';
								$left_color = $left_styles['color'] ?? $all_styles['color'] ?? 'transparent';
					
								$top_style = $top_styles['style'] ?? $all_styles['style'] ?? 'solid';
								$right_style = $right_styles['style'] ?? $all_styles['style'] ?? 'solid';
								$bottom_style = $bottom_styles['style'] ?? $all_styles['style'] ?? 'solid';
								$left_style = $left_styles['style'] ?? $all_styles['style'] ?? 'solid';
					
								$css .= "
									border-top: {$top_width} {$top_style} {$top_color};
									border-right: {$right_width} {$right_style} {$right_color};
									border-bottom: {$bottom_width} {$bottom_style} {$bottom_color};
									border-left: {$left_width} {$left_style} {$left_color};
								";
							}
					
							// Border radius settings
							$radius = $attr_value['radius'] ?? [];
							if (!empty($radius)) {
								$top_left = $radius['topLeft'] ?? '0';
								$top_right = $radius['topRight'] ?? '0';
								$bottom_right = $radius['bottomRight'] ?? '0';
								$bottom_left = $radius['bottomLeft'] ?? '0';
					
								$css .= "border-radius: {$top_left} {$top_right} {$bottom_right} {$bottom_left};";
							}
					
							return $css;
						},
					]),
					
					// Story Right arrow border
					CommonStyle::style(
						[
							'selector'            => $order_class . ' .tmdivi-story.tmdivi-story-right > .tmdivi-arrow',
							'attr'                => $attrs['story_border_settings']['advanced'] ?? [],
							'declarationFunction' => function ($declaration_function_args) {
								$css = '';
								$attr_value = $declaration_function_args['attrValue'] ?? [];
					
								// Extract `styles.all`
								$all_styles = $attr_value['styles']['all'] ?? [];
					
								if (!empty($all_styles)) {
									$width = $all_styles['width'] ?? '0px';
									$style = $all_styles['style'] ?? 'solid';
									$color = ($width !== '0px') ? ($all_styles['color'] ?? '#666666') : 'transparent';
					
									$css .= "border-width: 0px 0px {$width} {$width}; border-style: {$style}; border-color: {$color};";
								}
					
								return $css;
							},
						]
					),					
					// Story left arrow border 
					CommonStyle::style(
						[
							'selector'            => $order_class . ' .tmdivi-story.tmdivi-story-left > .tmdivi-arrow',
							'attr'                => $attrs['story_border_settings']['advanced'] ?? [],
							'declarationFunction' => function ($declaration_function_args) {
								$css = '';
								$attr_value = $declaration_function_args['attrValue'] ?? [];
					
								// Extract `styles.all`
								$all_styles = $attr_value['styles']['all'] ?? [];
					
								if (!empty($all_styles)) {
									$width = $all_styles['width'] ?? '0px';
									$style = $all_styles['style'] ?? 'solid';
									$color = ($width !== '0px') ? ($all_styles['color'] ?? '#666666') : 'transparent';
					
									$css .= "border-width: {$width} {$width} 0px 0px; border-style: {$style}; border-color: {$color};";
								}
					
								return $css;
							},
						]
					),					
					// Horizontal arrow border
					CommonStyle::style(
						[
							'selector'            => $order_class . ' #tmdivi-slider-container .tmdivi-story > .tmdivi-arrow',
							'attr'                => $attrs['story_border_settings']['advanced'] ?? [],
							'declarationFunction' => function ($declaration_function_args) {
								$css = '';
								$attr_value = $declaration_function_args['attrValue'] ?? [];
					
								// Extract `styles.all`
								$all_styles = $attr_value['styles']['all'] ?? [];
					
								if (!empty($all_styles)) {
									$width = $all_styles['width'] ?? '0px';
									$style = $all_styles['style'] ?? 'solid';
									$color = ($width !== '0px') ? ($all_styles['color'] ?? '#666666') : 'transparent';
					
									$css .= "border-width: {$width} 0px 0px {$width}; border-style: {$style}; border-color: {$color};";
								}
					
								return $css;
							},
						]
					),					
					// Right side story arrow border
					CommonStyle::style(
						[
							'selector'            => $order_class . ' .tmdivi-vertical-right .tmdivi-story > .tmdivi-arrow',
							'attr'                => $attrs['story_border_settings']['advanced'] ?? [],
							'declarationFunction' => function ($declaration_function_args) {
								$css = '';
								$attr_value = $declaration_function_args['attrValue'] ?? [];
					
								// Extract `styles.all`
								$all_styles = $attr_value['styles']['all'] ?? [];
					
								if (!empty($all_styles)) {
									$width = $all_styles['width'] ?? '0px';
									$style = $all_styles['style'] ?? 'solid';
									$color = ($width !== '0px') ? ($all_styles['color'] ?? '#666666') : 'transparent';
					
									$css .= "border-width: 0px 0px {$width} {$width}; border-style: {$style}; border-color: {$color};";
								}
					
								return $css;
							},
						]
					),					
					// Left side story arrow border
					CommonStyle::style(
						[
							'selector'            => $order_class . ' .tmdivi-vertical-left .tmdivi-story > .tmdivi-arrow',
							'attr'                => $attrs['story_border_settings']['advanced'] ?? [],
							'declarationFunction' => function ($declaration_function_args) {
								$css = '';
								$attr_value = $declaration_function_args['attrValue'] ?? [];
					
								// Extract `styles.all`
								$all_styles = $attr_value['styles']['all'] ?? [];
					
								if (!empty($all_styles)) {
									$width = $all_styles['width'] ?? '0px';
									$style = $all_styles['style'] ?? 'solid';
									$color = ($width !== '0px') ? ($all_styles['color'] ?? '#666666') : 'transparent';
					
									$css .= "border-width:{$width} {$width} 0px 0px; border-style: {$style}; border-color: {$color};";
								}
					
								return $css;
							},
						]
					),					
					// Timeline Story padding
					CommonStyle::style(
						[
							'selector'            => $order_class . ' .tmdivi-story .tmdivi-content',
							'attr'                => $attrs['story_padding']['advanced'] ?? [],
							'declarationFunction' => function ($declaration_function_args) {
								$css = '';
								$padding = $declaration_function_args['attrValue']['padding'] ?? [];
								// Default padding values
								$top    = $padding['top'] ?? '0px';
								$right  = $padding['right'] ?? '5px';
								$bottom = $padding['bottom'] ?? '0px';
								$left   = $padding['left'] ?? '5px';
								// Generate CSS
								$css .= "padding: {$top} {$right} {$bottom} {$left};";
					
								return $css;
							},
						]
					),	
					// Timeline Color
					CommonStyle::style(
						[
							'selector'            => $order_class . ' .tmdivi-wrapper',
							'attr'                => $attrs['timeline_color']['advanced'] ?? '',
							'declarationFunction' => function ($declaration_function_args) {
								$data = $declaration_function_args['attrValue'] ?? '';
								$css = '';
								if (!empty($data)) {
									$css = "--tw-line-bg: {$data};";
								}
								return $css;
							},
						]
					),

					// Timeline Line Width
					CommonStyle::style(
						[
							'selector'            => $order_class . ' .tmdivi-wrapper',
							'attr'                => $attrs['timeline_line_width']['advanced'] ?? '',
							'declarationFunction' => function ($declaration_function_args) {
								$data = $declaration_function_args['attrValue'] ?? '';
								$css = '';
								if (!empty($data)) {
									$css = "--tw-line-width: {$data};";
								}
								return $css;
							},
						]
					),

					// Timeline Fill Color
					CommonStyle::style(
						[
							'selector'            => $order_class . ' .tmdivi-wrapper',
							'attr'                => $attrs['timeline_fill_color']['advanced'] ?? '',
							'declarationFunction' => function ($declaration_function_args) {
								$data = $declaration_function_args['attrValue'] ?? '';
								$css = '';
								if (!empty($data)) {
									$css = "--tw-line-filling-color: {$data};";
								}
								return $css;
							},
						]
					),
					// Icon Background Color
					CommonStyle::style(
						[
							'selector'            => $order_class . ' .tmdivi-wrapper',
							'attr'                => $attrs['icon_background_color']['advanced'] ?? '',
							'declarationFunction' => function ($declaration_function_args) {
								$data = $declaration_function_args['attrValue'] ?? '';
								$css = '';
								if (!empty($data)) {
									$css = "--tw-ibx-bg: {$data};";
								}
								return $css;
							},
						]
					),

					// Icon Color
					CommonStyle::style(
						[
							'selector'            => $order_class . ' .tmdivi-wrapper',
							'attr'                => $attrs['icon_color']['advanced'] ?? '',
							'declarationFunction' => function ($declaration_function_args) {
								$data = $declaration_function_args['attrValue'] ?? '';
								$css = '';
								if (!empty($data)) {
									$css = "--tw-ibx-color: {$data};";
								}
								return $css;
							},
						]
					),

					// Labels Position
					CommonStyle::style(
						[
							'selector'            => $order_class . ' .tmdivi-wrapper',
							'attr'                => $attrs['labels_position']['advanced'] ?? '',
							'declarationFunction' => function ($declaration_function_args) {
								$data = $declaration_function_args['attrValue'] ?? '';
								$css = '';
								if (!empty($data)) {
									$data = str_replace('px', '', $data);
									$css = "--tw-ibx-position: {$data};";
								}
								return $css;
							},
						]
					),

					// Labels Spacing Bottom
					CommonStyle::style(
						[
							'selector'            => $order_class . ' .tmdivi-wrapper',
							'attr'                => $attrs['labels_spacing_bottom']['advanced'] ?? '',
							'declarationFunction' => function ($declaration_function_args) {
								$data = $declaration_function_args['attrValue'] ?? '';
								$css = '';
								if (!empty($data)) {
									$css = "--tw-lbl-gap: {$data};";
								}
								return $css;
							},
						]
					),

					// Story Spacing Top
					CommonStyle::style(
						[
							'selector'            => $order_class . ' .tmdivi-wrapper .tmdivi-story',
							'attr'                => $attrs['story_spacing_top']['advanced'] ?? '',
							'declarationFunction' => function ($declaration_function_args) {
								$data = $declaration_function_args['attrValue'] ?? '';
								$css = '';
								if (!empty($data)) {
									$css = "margin-top: {$data};";
								}
								return $css;
							},
						]
					),

					// Story Spacing Bottom
					CommonStyle::style(
						[
							'selector'            => $order_class . ' .tmdivi-wrapper',
							'attr'                => $attrs['story_spacing_bottom']['advanced'] ?? '',
							'declarationFunction' => function ($declaration_function_args) {
								$data = $declaration_function_args['attrValue'] ?? '';
								$css = '';
								if (!empty($data)) {
									$css = "--tw-cbx-bottom-margin: {$data};";
								}
								return $css;
							},
						]
					),

					// Story Title Align
					CommonStyle::style(
						[
							'selector'            => $order_class . ' .tmdivi-wrapper',
							'attr'                => $attrs['story_title']['decoration']['font'] ?? '',
							'declarationFunction' => function ($declaration_function_args) {
								$data = $declaration_function_args['attrValue'] ?? '';
								$css = '';
								if(!empty($data['value']['textAlign'])){
									$title_align_ment = $data['value']['textAlign'];
									$css = "--tw-cbx-text-align: {$title_align_ment};";
								}
								return $css;
							},
						]
					),

					// Label Date.
					$elements->style(
						[
							'attrName' => 'label_date',
						]
					),

					// Sub Label.
					$elements->style(
						[
							'attrName' => 'sub_label',
						]
					),

					// Year Label.
					$elements->style(
						[
							'attrName' => 'label_text',
						]
					),

					// Title.
					$elements->style(
						[
							'attrName' => 'story_title',
						]
					),

					// Content.
					$elements->style(
						[
							'attrName' => 'content',
						]
					),

					// ATTENTION: The code is intentionally added and commented in FE only as an example of expected value format.
					// If you have custom style processing, the style output should be passed as an `array` of style declarations
					// to the `styles` property of the `Style::add` method. For example:
					// [
					// 	[
					// 		'atRules'     => false,
					// 		'selector'    => $icon_selector,
					// 		'declaration' => 'color: red;'
					// 	],
					// 	[
					// 		'atRules'     => '@media only screen and (max-width: 767px)',
					// 		'selector'    => $icon_selector,
					// 		'declaration' => 'color: green;'
					// 	],
					// ],
				],
			]
		);
	}
}