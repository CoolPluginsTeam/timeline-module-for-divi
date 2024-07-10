// This script is loaded both on the frontend page and in the Visual Builder.

jQuery(function($) {
    if (window.ETBuilderBackend && window.ETBuilderBackend.defaults) {
        window.ETBuilderBackend.defaults.tmdivi_timeline_story = {
            sub_label: 'Your sub label here...',
            label_date: 'Date/Step',
            story_title: 'Enter your text here...',
            content:'Your description here...'
        };
    }
    
});
