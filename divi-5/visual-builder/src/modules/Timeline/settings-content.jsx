import React from 'react';
import { __ } from '@wordpress/i18n';

const {
  AdminLabelGroup,
  BackgroundGroup,
  DraggableChildModuleListContainer,
  FieldContainer,
  LinkGroup
} = window?.divi?.module;

const {
  DraggableListContainer,
  IconPickerContainer,
}  = window.divi.fieldLibrary;

const { GroupContainer } = window.divi.modal;

export const TimelineSettingsContent = () => (
  <React.Fragment>
    <DraggableChildModuleListContainer
      childModuleName="tmdivi/timeline-story"
      addTitle={__('Add New Story', 'timeline-module-for-divi')}
    >
    <DraggableListContainer />
    </DraggableChildModuleListContainer>

    <BackgroundGroup />
    <LinkGroup />
    <AdminLabelGroup />
  </React.Fragment>
);