import React from 'react';
import { __ } from '@wordpress/i18n';

const {
  AdminLabelGroup,
  BackgroundGroup,
  DraggableChildModuleListContainer,
  LinkGroup
} = window?.divi?.module;

const {
  DraggableListContainer,
}  = window.divi.fieldLibrary;


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