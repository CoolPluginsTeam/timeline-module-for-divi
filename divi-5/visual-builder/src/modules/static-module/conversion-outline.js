export const conversionOutline = {
    advanced: {
      admin_label: 'module.meta.adminLabel',
      animation:   'module.decoration.animation',
      box_shadow: {
        default: 'module.decoration.boxShadow',
      },
      disabled_on:     'module.decoration.disabledOn',
      module:          'module.advanced.htmlAttributes',
      overflow:        'module.decoration.overflow',
      position_fields: 'module.decoration.position',
      scroll:          'module.decoration.scroll',
      sticky:          'module.decoration.sticky',
      text_shadow:     {
        default: 'module.advanced.text.textShadow',
      },
      transform:  'module.decoration.transform',
      transition: 'module.decoration.transition',
      z_index:    'module.decoration.zIndex',
      fonts:      {
        header:     'title.decoration.font',
        body:       'content.decoration.bodyFont.body',
        body_link:  'content.decoration.bodyFont.link',
        body_ul:    'content.decoration.bodyFont.ul',
        body_ol:    'content.decoration.bodyFont.ol',
        body_quote: 'content.decoration.bodyFont.quote',
      },
      background:     'module.decoration.background',
      margin_padding: 'module.decoration.spacing',
      text:           'module.advanced.text',
      borders:        {
        default: 'module.decoration.border',
      },
      max_width: 'module.decoration.sizing',
      height:    'module.decoration.sizing',
      filters:   {
        default: 'module.decoration.filters',
      },
      link_options: 'module.advanced.link',
    },
    css: {
      before:       'css.*.before',
      main_element: 'css.*.mainElement',
      after:        'css.*.after',
      title:        'css.*.title',
      content:      'css.*.content',
    },
    module: {
      title:   'title.innerContent.*',
      content: 'content.innerContent.*',
    },
  };