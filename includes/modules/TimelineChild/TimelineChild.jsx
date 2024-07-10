import React from "react";
import { StoryYearLabel, StoryContent, StoryIcon, StoryLabels } from "../TimelineModules";
import "./style.css";

class TMDIVI_TimelineChild extends React.Component {

    static slug = "tmdivi_timeline_story";

    returnStoryLayout(){
        const timelineStory = document.querySelectorAll('.tmdivi-vertical');
        let timelineLayout;
        for (let i = 0; i < timelineStory.length; i++) {
            timelineLayout = timelineStory[i].getAttribute('data-layout'); 
        }
        return timelineLayout;
    }
    render() {

        let sided_css = '';
        const timelineStory = document.querySelectorAll('.tmdivi-vertical');
        let story_css = this.props.show_story_icon === 'on' ? 'tmdivi-story-icon' : 'tmdivi-story-icon'; // Check and fix this css class in case of empty icon
        for (let i = 0; i < timelineStory.length; i++) {
            let timelineLayout = timelineStory[i].getAttribute('data-layout'); // Scoped variable within the loop
            if (timelineLayout === "both-side") {
                sided_css = this.props.moduleInfo.order % 2 ? "tmdivi-story-left" : "tmdivi-story-right";
            } 
            // else if (timelineLayout === "one-side-right") {
            //     story_css = "tmdivi-story-right";
            // } else if (timelineLayout === "one-side-left") {
            //     story_css = "tmdivi-story-left";
            // }

            story_css += ' ' + sided_css;

        }

        
        const storyTitle = this.props.story_title;          
        const labelDate = this.props.label_date;         
        const subLabel = this.props.sub_label;         
        const yearLabelText = this.props.label_text;               
        const storyMedia = this.props.media;  
        const storyDescription = this.props.content();  
        const yearLabel = this.props.show_label; 
        const storyIcon = this.props.show_story_icon;  
        const storyIconData = this.props.story_icons;  

        return (

            <>
                <StoryYearLabel
                    isEnabled={yearLabel}
                    label={yearLabelText}
                />
                <div className={`tmdivi-story ${story_css}`}>
                    <StoryLabels
                        label_date={labelDate}
                        sub_label={subLabel}
                    />
                    <StoryIcon
                        isIcon={storyIcon}
                        icon={storyIconData}
                    />
                    <div className="tmdivi-arrow"></div>
                    <StoryContent
                        story_title={storyTitle}
                        media={storyMedia}
                        alt_tag={this.props.media_alt_tag}
                        content={storyDescription}
                    />
                </div>
            </>
        );
    }

    static css(props) {
        const ChildTimelineCss = [];

        const child_story_border_color = props.child_story_border_color 
        const child_story_background_color = props.child_story_background_color 
        const child_story_heading_color = props.child_story_heading_color 
        const child_story_description_color = props.child_story_description_color 

        if (child_story_border_color !== undefined) {
            ChildTimelineCss.push(
                [
                    {
                        selector: "%%order_class%% .tmdivi-story .tmdivi-content",
                        declaration: `border-color: ${child_story_border_color} !important;`,
                    },
                    {
                        selector: "%%order_class%% .tmdivi-story .tmdivi-arrow",
                        declaration: `border-color: ${child_story_border_color} !important;`,
                    },
                ]
            )
        }

        if (child_story_background_color !== undefined) {
            ChildTimelineCss.push(
                [
                {
                    selector: "%%order_class%% .tmdivi-story .tmdivi-content",
                    declaration: `background-color: ${child_story_background_color};`,
                },
                {
                    selector: "%%order_class%% .tmdivi-story > .tmdivi-arrow",
                    declaration: `background: ${child_story_background_color} !important;`,
                },
                ]
            )
        }

        if (child_story_heading_color !== undefined) { 
            ChildTimelineCss.push(
                [{
                    selector: "%%order_class%% .tmdivi-story .tmdivi-content .tmdivi-title",
                    declaration: `--tw-cbx-title-color: ${child_story_heading_color};`,
                }]
            )
        }

        if (child_story_description_color !== undefined) { 
            ChildTimelineCss.push(
                [{
                    selector: "%%order_class%% .tmdivi-story .tmdivi-content .tmdivi-description",
                    declaration: `--tw-cbx-des-color: ${child_story_description_color};`,
                }]
            )
        }
        return ChildTimelineCss;
    }

}

export default TMDIVI_TimelineChild;