import React from 'react';

import { __ } from '@wordpress/i18n';

const {
  RichTextContainer,
  TextContainer,
} = window?.divi?.fieldLibrary;
const { GroupContainer } = window?.divi?.modal;
const {
  AdminLabelGroup,
  BackgroundGroup,
  FieldContainer,
  LinkGroup,
} = window?.divi?.module;

/**
 * Content Settings panel for the Static Module.
 */
export const SettingsContent = ({ defaultSettingsAttrs }) => (
  <React.Fragment>
    <GroupContainer
      id="mainContent"
      title={__('Text', 'd5-tutorial-module-conversion')}
    >
      <FieldContainer
        attrName="test_title.innerContent"
        label={__('Test Title', 'd5-tutorial-module-conversion')}
        features={{
          sticky: false,
        }}
      >
        <TextContainer />
      </FieldContainer>

      <FieldContainer
        attrName="title.innerContent"
        label={__('Title', 'd5-tutorial-module-conversion')}
        description={__('Text entered here will appear as title.', 'd5-tutorial-module-conversion')}
        features={{
          sticky: false,
        }}
      >
        <TextContainer />
      </FieldContainer>
      <FieldContainer
        attrName="content.innerContent"
        label={__('Content', 'd5-tutorial-module-conversion')}
        description={__('Content entered here will appear inside the module.', 'd5-tutorial-module-conversion')}
        features={{
          sticky: false,
        }}
      >
        <RichTextContainer />
      </FieldContainer>
    </GroupContainer>
    <LinkGroup />
    <BackgroundGroup />
    <AdminLabelGroup
      defaultGroupAttr={defaultSettingsAttrs?.module?.meta?.adminLabel ?? {}}
    />
  </React.Fragment>
);