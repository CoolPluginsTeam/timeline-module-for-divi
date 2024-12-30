import React from 'react';
const {
  StyleContainer,
  CssStyle,
  TextStyle,
  CommonStyle
} = window?.divi?.module;

const TimelineItemStyles = (props) => {

  const {attrs,
    elements,
    settings,
    orderClass,
    mode,
    state,
    noStyleTag,
    parentAttrs
  } = props

  const contentContainerSelector = `${orderClass} .example_child_module__content`;


  return(
    <StyleContainer mode={mode} state={state} noStyleTag={noStyleTag}>
      {/* Module */}
      {elements.style({
        attrName: 'module',
        styleProps: {
          disabledOn: {
            disabledModuleVisibility: settings?.disabledModuleVisibility,
          },
        },
      })}
      <TextStyle
        selector={contentContainerSelector}
        attr={attrs?.module?.advanced?.text}
      />
      <CssStyle
        selector={orderClass}
        attr={attrs.css}
      />

      <CommonStyle
        selector={`${orderClass} .tmdivi-story .tmdivi-content, ${orderClass} .tmdivi-story > .tmdivi-arrow`}
        attr={attrs?.child_story_background_color?.advanced ?? parentAttrs?.story_background_color?.advanced}
        declarationFunction={(attrs)=>{
          const data = attrs?.attrValue
          return `background:${data} !important;`;
        }}
      />

      <CommonStyle
        selector={`${orderClass} .tmdivi-story .tmdivi-content .tmdivi-title`}
        attr={attrs?.child_story_heading_color?.advanced}
        declarationFunction={(attrs)=>{
          const data = attrs?.attrValue
          return `color:${data} !important;`;
        }}
      />

      <CommonStyle
        selector={`${orderClass} .tmdivi-story .tmdivi-content .tmdivi-description,${orderClass} .tmdivi-story .tmdivi-content .tmdivi-description p`}
        attr={attrs?.child_story_description_color?.advanced}
        declarationFunction={(attrs)=>{
          const data = attrs?.attrValue
          return `color:${data} !important;`;
        }}
      />

      <CommonStyle
        selector={`${orderClass} .tmdivi-story div.tmdivi-label-big`}
        attr={attrs?.child_story_label_color?.advanced}
        declarationFunction={(attrs)=>{
          const data = attrs?.attrValue
          return `color:${data} !important;`;
        }}
      />

      <CommonStyle
        selector={`${orderClass} .tmdivi-story div.tmdivi-label-small`}
        attr={attrs?.child_story_sub_label_color?.advanced}
        declarationFunction={(attrs)=>{
          const data = attrs?.attrValue
          return `color:${data} !important;`;
        }}
      />

      <CommonStyle
        selector={`${orderClass} .tmdivi-story .tmdivi-icon`}
        attr={attrs?.child_story_icon_background_color?.advanced}
        declarationFunction={(attrs)=>{
          const data = attrs?.attrValue
          return `background-color:${data} !important;`;
        }}
      />

      {/* Title */}
      {elements.style({
        attrName: 'story_title',
      })}

      {/* Content */}
      {elements.style({
        attrName: 'content',
      })}

    </StyleContainer>
  );
};

export default TimelineItemStyles;