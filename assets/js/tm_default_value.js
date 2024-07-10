jQuery(function($) {

    var is_vb = $("body").hasClass("et-fb");
    

    // $(document).on('click','.et-fb-settings-options-wrap ul li.tmdivi_timeline',(e)=>{        
    //     setTimeout(()=>{
    //         const settingModal = $('body').find('.et-fb-modal')
    //         const storyListItem = settingModal.find('form .et-fb-settings-module-items')
    //         if(storyListItem.length === 0){
    //             settingModal.find('form .et-fb-settings-module-items-wrap .et-fb-item-addable-button .et-fb-button')
    //             .trigger('click')
    //             .end()
    //             .find('button.et-fb-button--success')
    //             .trigger('click');
    //             setTimeout(()=>{
    //                 if(storyListItem.length === 0){
    //                     const newDataVar = storyListItem.prevObject.find('form');
    //                     newDataVar.find('button.et-fb-settings-module-item-button--copy').each(function() {
    //                         $(this).trigger('click').trigger('click');
    //                     });
    //                 }
    //             },300)
    //         }
    //     },500)
    // })
    
    // $(document).on('click', 'form .et-fb-settings-module-items-wrap .et-fb-item-addable-button .et-fb-button', (e) => {
    //     setTimeout(() => {
    //         var iframe = document.querySelector('iframe');
    //         if (iframe) {
    //             var iframeDocument = iframe.contentDocument || iframe.contentWindow.document;
    //             if (iframeDocument) {
    //                 var $tmdiviVertical = $(iframeDocument).find('.tmdivi-vertical .tmdivi-story');
    //             } 
    //         } 
    //     }, 1000);
    // });

    $(window).on('load',()=>{

        is_vb &&
            window.ETBuilderBackend &&
            window.ETBuilderBackend.defaults && 
            ((window.ETBuilderBackend.defaults.tmdivi_timeline_story = {
                sub_label: 'Your sub label here...',
                label_date: 'Date/Step',
                story_title: 'Enter your text here...',
                content:'Your description here...'
            })
        );

    });

});
