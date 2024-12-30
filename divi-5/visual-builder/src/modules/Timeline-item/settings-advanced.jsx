import React from 'react';

import { __ } from '@wordpress/i18n';

const {
    AttributesGroup,
    CssGroup,
    IdClassesGroup,
    PositionSettingsGroup,
    ScrollSettingsGroup,
    TransitionGroup,
    VisibilitySettingsGroup
} = window?.divi?.module;

const TimelineItemSettingsAdvaced = () => (
    <React.Fragment>
    <IdClassesGroup />
    <CssGroup
      mainSelector=""
    />
    <AttributesGroup />
    <VisibilitySettingsGroup />
    <TransitionGroup />
    <PositionSettingsGroup />
    <ScrollSettingsGroup />
  </React.Fragment>
);
export default TimelineItemSettingsAdvaced;
