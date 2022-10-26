!function(n){"use strict";var i,a={Android:function(){return navigator.userAgent.match(/Android/i)},BlackBerry:function(){return navigator.userAgent.match(/BlackBerry/i)},iOS:function(){return navigator.userAgent.match(/iPhone|iPad|iPod/i)},Opera:function(){return navigator.userAgent.match(/Opera Mini/i)},Windows:function(){return navigator.userAgent.match(/IEMobile/i)},any:function(){return a.Android()||a.BlackBerry()||a.iOS()||a.Opera()||a.Windows()}},s={iOS:function(){return navigator.userAgent.match(/iPad/i)},any:function(){return s.iOS()}};n(function(){var o,t,e;n(".slides-container .slide-item").addClass("sliderFix"),setTimeout(function(){n(".slides-container .slide-item").removeClass("sliderFix")},200),function(){function e(){n("#slideshow").imagesLoaded(function(){if(n(window).width()<=1024){var e=n(".slide-item:first-of-type").height();n(".sydney-hero-area, #slideshow").height(e)}else n(".sydney-hero-area").css("height","auto")})}n("#slideshow").length&&n("#slideshow").superslides({play:n("#slideshow").data("speed"),animation:"fade",pagination:!1}),"responsive"===n("#slideshow").data("mobileslider")&&(n(document).ready(e),n(window).resize(function(){setTimeout(function(){e()},50)})),n(function(){n('.mainnav a[href*="#"], a.roll-button[href*="#"], .smoothscroll[href*="#"], .smoothscroll a[href*="#"]').on("click",function(e){var i=this.hash,t=n(i);if(t.length)return e.preventDefault(),n("html, body").stop().animate({scrollTop:t.offset().top-70},900,"swing"),n("#mainnav-mobi").length&&n("#mainnav-mobi").hide(),!1})})}(),function(){if(n(".site-header").length){var i=n(".site-header").offset().top;n(window).on("load scroll",function(){var e=n(this).scrollTop();i<=e?(n(".site-header").addClass("fixed"),n("body").addClass("siteScrolled")):(n(".site-header").removeClass("fixed"),n("body").removeClass("siteScrolled")),107<=e?n(".site-header").addClass("float-header"):n(".site-header").removeClass("float-header")})}}(),n().owlCarousel&&n(".roll-testimonials").owlCarousel({navigation:!1,pagination:!0,responsive:!0,items:1,itemsDesktop:[3e3,1],itemsDesktopSmall:[1400,1],itemsTablet:[970,1],itemsTabletSmall:[600,1],itemsMobile:[360,1],touchDrag:!0,mouseDrag:!0,autoHeight:!0,autoPlay:n(".roll-testimonials").data("autoplay")}),n().owlCarousel&&n(".roll-team:not(.roll-team.no-carousel)").owlCarousel({navigation:!1,pagination:!0,responsive:!0,items:3,itemsDesktopSmall:[1400,3],itemsTablet:[970,2],itemsTabletSmall:[600,1],itemsMobile:[360,1],touchDrag:!0,mouseDrag:!0,autoHeight:!1,autoPlay:!1}),n(".roll-counter").on("on-appear",function(){n(this).find(".numb-count").each(function(){var e=parseInt(n(this).attr("data-to"));n(this).countTo({to:e})})}),n(".progress-bar").on("on-appear",function(){n(this).each(function(){var e=n(this).data("percent");n(this).find(".progress-animate").animate({width:e+"%"},3e3),n(this).parent(".roll-progress").find(".perc").addClass("show").animate({width:e+"%"},3e3)})}),n('[data-waypoint-active="yes"]').waypoint(function(){n(this).trigger("on-appear")},{offset:"90%",triggerOnce:!0}),n(window).on("load",function(){setTimeout(function(){n.waypoints("refresh")},100)}),o="desktop",n(window).on("load resize",function(){var e="desktop";if(matchMedia("only screen and (max-width: 1024px)").matches&&(e="mobile"),e!==o)if("mobile"===(o=e)){var i=n("#mainnav").attr("id","mainnav-mobi").hide(),t=n("#mainnav-mobi").find("li:has(ul)");n("#header").find(".header-wrap").after(i),t.children("ul").hide(),t.children("a").after('<span class="btn-submenu"></span>'),n(".btn-menu").removeClass("active")}else{var a=n("#mainnav-mobi").attr("id","mainnav").removeAttr("style");a.find(".submenu").removeAttr("style"),n("#header").find(".col-md-10").append(a),n(".btn-submenu").remove()}}),n(".btn-menu").on("click",function(){n("#mainnav-mobi").slideToggle(300),n(this).toggleClass("active")}),n(document).on("click","#mainnav-mobi li .btn-submenu",function(e){n(this).toggleClass("active").next("ul").slideToggle(300),e.stopImmediatePropagation()}),function(){function i(){if(n(".sydney-video.vid-lightbox .video-overlay").hasClass("popup-show")){var e=n(".sydney-video.vid-lightbox .video-overlay.popup-show");if(e.find("iframe").hasClass("yt-video"))var i=e.find("iframe").attr("src").replace("&autoplay=1","");else i=e.find("iframe").attr("src").replace("?autoplay=1","");e.find("iframe").attr("src",i),e.removeClass("popup-show")}}n(".toggle-popup").on("click",function(e){e.preventDefault(),n(this).siblings().addClass("popup-show");var i=n(this).siblings().find("iframe").attr("src");-1!==i.indexOf("youtube.com")?(n(this).siblings().find("iframe")[0].src+="&autoplay=1",n(this).siblings().find("iframe").addClass("yt-video")):-1!==i.indexOf("vimeo.com")&&(n(this).siblings().find("iframe")[0].src+="?autoplay=1",n(this).siblings().find("iframe").addClass("vimeo-video"))}),n(document).keyup(function(e){27==e.keyCode&&i()}),n(".sydney-video.vid-lightbox .video-overlay").on("click",function(){i()}),n(".sydney-video.vid-lightbox").parents(".panel-row-style").css({"z-index":"12",overflow:"visible"})}(),n("body").fitVids({ignore:".crellyslider-slider"}),n(".orches-animation").each(function(){var e=n(this),i=e.data("animation"),t=e.data("animation-delay"),a=e.data("animation-offset");e.css({"-webkit-animation-delay":t,"-moz-animation-delay":t,"animation-delay":t}),e.waypoint(function(){e.addClass("animated").addClass(i)},{triggerOnce:!0,offset:a})}),null!=s.any()&&n(".slides-container .slide-item").css("background-attachment","scroll"),n(".panel-row-style").each(function(){if(n(this).data("hascolor")&&n(this).find("h1,h2,h3,h4,h5,h6,a,.fa, div, span").css("color","inherit"),n(this).data("hasbg")&&n(this).data("overlay")){n(this).append('<div class="overlay"></div>');var e=n(this).data("overlay-color");n(this).find(".overlay").css("background-color",e)}}),n(".panel-grid .panel-widget-style").each(function(){var e=n(this).data("title-color"),i=n(this).data("headings-color");e&&n(this).find(".widget-title").css("color",e),i&&n(this).find("h1,h2,h3:not(.widget-title),h4,h5,h6,h3 a").css("color",i)}),null==(i=a.any())&&n(".panel-row-style, .slide-item").parallax("50%",.3),t=n(".project-wrap").data("portfolio-effect"),n(".project-item").children(".item-wrap").addClass("orches-animation"),n(".project-wrap").waypoint(function(e){n(".project-item").children(".item-wrap").each(function(e,i){setTimeout(function(){n(i).addClass("animated "+t)},150*e)})},{offset:"75%"}),n(".widget_fp_social a").attr("target","_blank"),n(window).scroll(function(){800<n(this).scrollTop()?n(".go-top").addClass("show"):n(".go-top").removeClass("show")}),n(".go-top").on("click",function(){return n("html, body").animate({scrollTop:0},1e3),!1}),n(".project-wrap").length&&n(".project-wrap").each(function(){var i=n(this),t=i.find(".project-filter").find("a"),e=function(e){e.isotope({filter:"*",itemSelector:".project-item",percentPosition:!0,animationOptions:{duration:750,easing:"liniar",queue:!1}})};i.children().find(".isotope-container").imagesLoaded(function(){e(i.children().find(".isotope-container"))}),n(window).load(function(){e(i.children().find(".isotope-container"))}),t.click(function(){var e=n(this).attr("data-filter");return t.removeClass("active"),n(this).addClass("active"),i.find(".isotope-container").isotope({filter:e,animationOptions:{duration:750,easing:"liniar",queue:!1}}),!1})}),i=a.iOS(),n(window).on("load",function(){n("#wp-custom-header").fitVids(),n(".fluid-width-video-wrapper + #wp-custom-header-video-button").find("i").removeClass("fa-play").addClass("fa-pause"),n(".fluid-width-video-wrapper + #wp-custom-header-video-button").on("click",function(){n(this).find("i").toggleClass("fa-play fa-pause")}),null!=i&&(n("#wp-custom-header-video-button").css("opacity","0"),n("#wp-custom-header-video").prop("controls",!0))}),e=n(".site-header").outerHeight(),n(".header-clone").css("height",e),n(window).resize(function(){var e=n(".site-header").outerHeight();n(".header-clone").css("height",e)}),n(".preloader").css("opacity",0),setTimeout(function(){n(".preloader").hide()},600)})}(jQuery);