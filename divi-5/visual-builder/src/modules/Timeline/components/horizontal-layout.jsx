const { ChildModulesContainer } = window?.divi?.module;

class HorizontalLayoutComponent extends React.Component{

  constructor({props}){
    super({props})
  }
  componentDidMount() {
    this.initializeSwipers();
  }

  componentDidUpdate(prevProps) {
    if (prevProps.props !== this.props.props) {
      this.initializeSwipers();
    }
  } 

  initializeSwipers() {
    const {
      attrs = {},
      elements = {},
      id = "",
      name = "",
      childrenIds = [],
    } = this.props.props || {};  

    const HorizontalAutoPlay = attrs?.horizontal_settings_autoplay?.advanced?.desktop?.value === "on";
    const HorizontalAutoPlaySpeed = attrs?.horizontal_settings_autoplay_speed?.advanced?.desktop?.value || "";

    const HorizontalSlideSpacing = attrs?.horizontal_settings_slide_spacing?.advanced?.desktop?.value || '28px';

    const HorizontalAutoLoop = attrs?.horizontal_settings_loop?.advanced?.desktop?.value === "on"
    const HorizontalSlideToShow = attrs?.horizontal_settings_slide_to_show?.advanced?.desktop?.value || "2"

    const autoplaySettings = HorizontalAutoPlay ? {
      delay: HorizontalAutoPlaySpeed,
      disableOnInteraction: false,
    } : false;

    const timelineWrapper = document.querySelector(`[data-id="${id}"]`);
    
    if (!timelineWrapper) return;

    const swiperContainer = timelineWrapper.querySelector('.swiper-container-horizontal')

    const Swiper = window.Swiper

    if(typeof Swiper !== 'undefined'){

      if(swiperContainer.swiper){
        swiperContainer.swiper.destroy();
      }
  
      const swiper = new Swiper(swiperContainer, {
        slidesPerView: HorizontalSlideToShow,
        spaceBetween: HorizontalSlideSpacing,
        loop: HorizontalAutoLoop && childrenIds.length > HorizontalSlideToShow,
        breakpoints: {
            640: {
                slidesPerView: 1,
                spaceBetween: 20,
            },
            768: {
                slidesPerView: HorizontalSlideToShow,
                spaceBetween: 40,
            },
            1024: {
                slidesPerView: HorizontalSlideToShow,
                spaceBetween: HorizontalSlideSpacing,
            },
        },
        navigation: {
            nextEl: timelineWrapper.querySelector(".tmdivi-button-next"),
            prevEl: timelineWrapper.querySelector(".tmdivi-button-prev"),
        },
        slideClass: "tmdivi_timeline_story",
        wrapperClass: "tmdivi-slider-wrapper",
        parallax: true,
        autoplay: autoplaySettings,
        speed: 800,
        autoHeight: true,
        // initialSlide: this.state.newStoryIndex !== null ? this.state.newStoryIndex : 0,
      });
      setTimeout(() => {
        swiper.update();
      }, 1000);
    }
  }

  render(){
    const {
      attrs = {},
      elements = {},
      id = "",
      name = "",
      childrenIds = [],
    } = this.props.props || {};  

    const horizontalLayoutClass = this.props.horizontalLayoutClass;
    return(
      <>
        <div id="tmdivi-wrapper" className={horizontalLayoutClass}>
          <div className="tmdivi-wrapper-inside">
            <div 
              id="tmdivi-slider-container" 
              className="tmdivi-slider-container swiper-container tmdivi-line-filler swiper-container-horizontal"
            >
              <div className="tmdivi-slider-wrapper swiper-wrapper equal-height-slides">
                {childrenIds.map((childId, i) => (
                  <div key={i} className="tmdivi_timeline_story swiper-slide">
                    <ChildModulesContainer ids={[childId]} />
                  </div>
                ))}
              </div>
              <span className="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
            </div>
          </div>
          <div 
            className="tmdivi-button-prev swiper-button-disabled" 
            tabIndex="0" 
            role="button" 
            aria-label="Previous slide" 
            aria-disabled="true"
          >
            <i className="fas fa-chevron-left"></i>
          </div>
          <div 
            className="tmdivi-button-next" 
            tabIndex="0" 
            role="button" 
            aria-label="Next slide" 
            aria-disabled="false"
          >
            <i className="fas fa-chevron-right"></i>
          </div>
          <div className="tmdivi-h-line"></div>
          <div className="tmdivi-line-fill swiper-pagination-progressbar">
            <span className="swiper-pagination-progressbar-fill"></span>
          </div>
        </div>
      </>

    )
  }
}

export default HorizontalLayoutComponent