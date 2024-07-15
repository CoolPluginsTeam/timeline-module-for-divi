(function($){
    $(document).on('click', '.et-fb-item-addable-button button', (e) => {
        setTimeout(()=>{
            const mainData = $('form.et-fb-form .et-fb-form__toggle .et-fb-form__toggle-title')[0]
            $(mainData).trigger('click')
        },500)
    });

})(jQuery)