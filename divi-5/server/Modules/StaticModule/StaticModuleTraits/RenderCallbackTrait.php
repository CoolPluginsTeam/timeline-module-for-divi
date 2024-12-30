<?php
namespace DTMC\Modules\StaticModule\StaticModuleTraits;

if ( ! defined( 'ABSPATH' ) ) {
    die( 'Direct access forbidden.' );
}

use DTMC\Modules\StaticModule\StaticModule;
use ET\Builder\FrontEnd\BlockParser\BlockParserStore;
use ET\Builder\Packages\Module\Module;

trait RenderCallbackTrait {
  public static function render_callback( $attrs, $content, $block, $elements ) {

    // Title.
    
    $header = $elements->render(
      [
        'attrName' => 'title',
      ]
    );

    // Content.
    $content = $elements->render(
      [
        'attrName' => 'content',
      ]
    );

    $parent = BlockParserStore::get_parent( $block->parsed_block['id'], $block->parsed_block['storeInstance'] );

    return Module::render(
      [
        // FE only.
        'orderIndex'          => $block->parsed_block['orderIndex'],
        'storeInstance'       => $block->parsed_block['storeInstance'],

        // VB equivalent.
        'attrs'               => $attrs,
        'elements'            => $elements,
        'id'                  => $block->parsed_block['id'],
        'moduleClassName'     => '',
        'name'                => $block->block_type->name,
        'classnamesFunction'  => [ StaticModule::class, 'module_classnames' ],
        'moduleCategory'      => $block->block_type->category,
        'stylesComponent'     => [ StaticModule::class, 'module_styles' ],
        'scriptDataComponent' => [ StaticModule::class, 'module_script_data' ],
        'parentAttrs'         => $parent->attrs ?? [],
        'parentId'            => $parent->id ?? '',
        'parentName'          => $parent->blockName ?? '',
        'children'            => $elements->style_components(
                    [
                        'attrName' => 'module',
                    ]
                ) . $header . $content,
      ]
    );
  }
}
