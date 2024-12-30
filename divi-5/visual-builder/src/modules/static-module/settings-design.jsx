import React from 'react';

import { __ } from '@wordpress/i18n';

const {
  AnimationGroup,
  BorderGroup,
  BoxShadowGroup,
  FiltersGroup,
  FontGroup,
  FontBodyGroup,
  SizingGroup,
  SpacingGroup,
  TextGroup,
  TransformGroup,
} = window?.divi?.module;


/**
 * Design Settings panel for the Static Module.
 */
export const SettingsDesign = (props) => {
  return(
  <React.Fragment>
    <TextGroup
      defaultGroupAttr={props.defaultSettingsAttrs?.module?.advanced?.text}
    />
    <FontGroup
      attrName="title.decoration.font"
      groupLabel={__('Title Text', 'd5-tutorial-module-conversion')}
      fieldLabel={__('Title', 'd5-tutorial-module-conversion')}
      fields={{
        headingLevel: {
          render: true,
        },
      }}
      defaultGroupAttr={props.defaultSettingsAttrs?.title?.decoration?.font}
    />

    <FontGroup
      attrName="test_title.decoration.font"
      groupLabel={__('Test Title Text', 'd5-tutorial-module-conversion')}
      fieldLabel={__('Test Title', 'd5-tutorial-module-conversion')}
    />

    <FontBodyGroup
      attrName="content.decoration.bodyFont"
    />
    <SizingGroup />
    <SpacingGroup />
    <BorderGroup />
    <BoxShadowGroup />
    <FiltersGroup />
    <TransformGroup />
    <AnimationGroup />
  </React.Fragment>
  )
};
