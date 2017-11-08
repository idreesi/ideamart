/* Dash Theme js functions */

jQuery(document).ready(function($) {

    "use strict";

    /* Star rating update */
    $('p.stars span').replaceWith('<span><a href="#" class="star-5">5</a><a href="#" class="star-4">4</a><a href="#" class="star-3">3</a><a href="#" class="star-2">2</a><a href="#" class="star-1">1</a></span>');

    /* Adding slider to woocommerce recently-viewed widget */
    $('.widget_recently_viewed_products').each(function() {
        var slider = $(this).find('.product_list_widget');
        slider.attr("data-owl", "container").attr("data-owl-slides", "1").attr("data-owl-type", "simple").attr("data-owl-transition", "fade").attr("data-owl-navi", "true").attr("data-owl-pagi", "false");
    });

    /* Wrap the selects for extra styling */
    $('.sidebar .widget select, .variations_form select').each(function(){
      $(this).wrap( "<div class='select-wrapper'></div>" );
    });

    /* Add extra element to radiobuttons & checkboxes for extra styling */
    var checkboxes = $('input[type="checkbox"]');
    checkboxes.each(function(){
      if ( $(this).is('#apply_for_vendor') || $(this).is('#agree_to_terms') ) {
        $(this).parent().append('<span class="extra-checkbox"></span>');
      }
    });

    /* Adding Carousel to cross-sells */
    var owl = $(".cross-sells ul.products");
    owl.owlCarousel({
        navigation : false,
        pagination : true,
        autoPlay   : false,
        slideSpeed : 300,
        paginationSpeed : 400,
        singleItem : true,
        transitionStyle : "fade",
    });

    /* Primary navigation animation */
    $('.primary-nav li').has('ul').mouseover(function() {
        $(this).children('ul').css('visibility', 'visible');
    }).mouseout(function() {
        $(this).children('ul').css('visibility', 'hidden');
    });

    /* PT Product tabs switcher */
    $('.pt-product-tabs').each(function() {
        $(this).find('.nav-tab li').click(function(e) {
            e.preventDefault();
            if (!$(this).hasClass("active")) {
                var tabNum = $(this).index();
                var nthChild = tabNum + 1;
                $(this).parent().find('li.active').removeClass("active");
                $(this).addClass("active");
                $(this).parent().parent().find('.nav-contents li.active').removeClass("active");
                $(this).parent().parent().find(".nav-contents li:nth-child(" + nthChild + ")").addClass("active");
            }
        });

        var settings = {
            interval: 100,
            timeout: 200,
            over: mousein_triger,
            out: mouseout_triger
        };
        $(this).find('.link-wrapper a').hoverIntent(settings);

        function mousein_triger() {
            $(this).find('.popup-wrapper').css('visibility', 'visible');
            $(this).addClass('hovered');
        }

        function mouseout_triger() {
            $(this).removeClass('hovered');
            $(this).find('.popup-wrapper').delay(300).queue(function() {
                $(this).css('visibility', 'hidden').dequeue();
            });
        }
    });

    /* Widget Area menu Accordion */
    $('.widget.widget_nav_menu').each(function() {

        var settings = {
            interval: 100,
            timeout: 200,
            over: mousein_triger,
            out: mouseout_triger
        };

        $(this).find('.menu-item-has-children').hoverIntent(settings);

        function mousein_triger() {
            var element = $(this);
            element.addClass('open');
            element.children('ul').slideDown();
            element.siblings('li').children('ul').slideUp();
            element.siblings('li').removeClass('open');
            element.siblings('li').find('li').removeClass('open');
            element.siblings('li').find('ul').slideUp();
        }

        function mouseout_triger() {
            var element = $(this);
            element.removeClass('open');
            element.find('li').removeClass('open');
            element.find('ul').slideUp();
        }
    });

    /* To top button */
    // Scroll (in pixels) after which the "To Top" link is shown
    var offset = 300,
        //Scroll (in pixels) after which the "back to top" link opacity is reduced
        offset_opacity = 1200,
        //Duration of the top scrolling animation (in ms)
        scroll_top_duration = 700,
        //Get the "To Top" link
        $back_to_top = $('.to-top');

    //Visible or not "To Top" link
    $(window).scroll(function() {
        ($(this).scrollTop() > offset) ? $back_to_top.addClass('top-is-visible'): $back_to_top.removeClass('top-is-visible top-fade-out');
        if ($(this).scrollTop() > offset_opacity) {
            $back_to_top.addClass('top-fade-out');
        }
    });

    //Smoothy scroll to top
    $back_to_top.on('click', function(event) {
        event.preventDefault();
        $('body,html').animate({
            scrollTop: 0,
        }, scroll_top_duration);
    });

});

/* Owl Carousels Init */
jQuery(document).ready(function($) {
    'use strict';

    /* Owl Object */
    var owlContainer = $('[data-owl=container]');

    $(owlContainer).each(function() {
        /* Variables */
        var owlSlidesQty = $(this).data('owl-slides');
        var owlType = $(this).data('owl-type');
        var owlTransition = $(this).data('owl-transition');
        if (owlSlidesQty !== 1) {
            owlTransition = false;
        }
        var owlNavigation = $(this).data('owl-navi');
        var owlPagination = $(this).data('owl-pagi');
        if (owlNavigation == 'custom') {
            var owlCustomNext = $(this).find(".next");
            var owlCustomPrev = $(this).find(".prev");
        };

        /* Simple Carousel */
        if (owlType == 'simple') {
            /* One Slide Gallery */
            if (owlSlidesQty == 1) {
                $(this).owlCarousel({
                    navigation: owlNavigation,
                    pagination: owlPagination,
                    slideSpeed: 300,
                    paginationSpeed: 400,
                    singleItem: true,
                    transitionStyle: owlTransition,
                    navigationText: ["<i class='fa fa-chevron-left'></i>", "<i class='fa fa-chevron-right'></i>"],
                    lazyLoad: true,
                });
            };
        };

        /* Carousel for Woo shortcodes */
        if (owlType == 'woo_shortcode') {
            var owl = $(this).find('ul.products');
            owl.owlCarousel({
                navigation: false,
                pagination: false,
                slideSpeed: 300,
                paginationSpeed: 400,
                items: owlSlidesQty,
                itemsDesktop: owlSlidesQty,
                itemsDesktopSmall: [900, (owlSlidesQty - 1)],
                itemsTablet: [600, (owlSlidesQty - 2)],
                itemsMobile: [479, 1],
                rewindNav: false,
                scrollPerPage: false,
            });

            owlCustomNext.click(function() {
                owl.trigger("owl.next");
            })
            owlCustomPrev.click(function() {
                owl.trigger("owl.prev");
            })
        }

        /* Simple Carousel with icon-pagination */
        if (owlType == 'with-icons') {
            $(this).owlCarousel({
                navigation: owlNavigation, // Show next and prev buttons
                pagination: owlPagination,
                slideSpeed: 300,
                paginationSpeed: 400,
                singleItem: true,
                transitionStyle: owlTransition,
                afterInit: afterOWLinit,
                navigationText: ["<i class='fa fa-chevron-left'></i>", "<i class='fa fa-chevron-right'></i>"],
            });
        };

    });

});


/* Magnific Pop-up init */
jQuery(document).ready(function($) {
    "use strict";

    /* Gallery Page init */
    $('.pt-gallery').each(function() {

        $(this).magnificPopup({

            mainClass: 'mfp-with-fade',
            removalDelay: 300,
            delegate: '.quick-view',
            type: 'image',
            closeOnContentClick: false,
            closeBtnInside: true,

            image: {
                verticalFit: true,
                titleSrc: function(item) {
                    var img_desc = item.el.parent().parent().find('h3').html();
                    return img_desc + '<a class="image-source-link" href="' + item.el.attr('href') + '" target="_blank">source</a>';
                }
            },

            gallery: {
                enabled: true,
            },

            callbacks: {
                buildControls: function() {
                    // re-appends controls inside the main container
                    this.contentContainer.append(this.arrowLeft.add(this.arrowRight));
                },
            },

        });

    });

    /* Single image pop-up init */
    var magnificLink = $('[data-magnific=link]');

    magnificLink.magnificPopup({
        removalDelay: 500,
        type: 'image',
        closeOnContentClick: false,
        closeBtnInside: true,

        callbacks: {
            beforeOpen: function() {
                // just a hack that adds mfp-anim class to markup
                this.st.image.markup = this.st.image.markup.replace('mfp-figure', 'mfp-figure mfp-with-anim');
                this.st.mainClass = this.st.el.attr('data-effect');
            }
        },
    });

    /* Single Product Gallery */
    var magnificContainer = $('[data-magnific=container]');

    magnificContainer.each(function() {

        $(this).magnificPopup({

            mainClass: 'mfp-with-fade',
            removalDelay: 300,
            delegate: 'a',
            type: 'image',
            closeOnContentClick: false,
            closeBtnInside: true,

            image: {
                verticalFit: true,
                titleSrc: function(item) {
                    var img_desc = item.el.attr('title');
                    return img_desc + '<a class="image-source-link" href="' + item.el.attr('href') + '" target="_blank">source</a>';
                }
            },

            gallery: {
                enabled: true,
            },

            callbacks: {
                buildControls: function() {
                    // re-appends controls inside the main container
                    if (this.arrowLeft && this.arrowRight) {
                        this.contentContainer.append(this.arrowLeft.add(this.arrowRight));
                    };
                },
            },

        });

    });


    magnificLink.magnificPopup({
        removalDelay: 500,
        type: 'image',
        closeOnContentClick: false,
        closeBtnInside: true,

        callbacks: {
            beforeOpen: function() {
                // just a hack that adds mfp-anim class to markup
                this.st.image.markup = this.st.image.markup.replace('mfp-figure', 'mfp-figure mfp-with-anim');
                this.st.mainClass = this.st.el.attr('data-effect');
            }
        },
    });

});

/* Isotope Init */
jQuery(document).ready(function($) {
    $(window).load(function() {
        "use strict";

        var isotopeContainer = $('[data-isotope=container]');
        var isotopeFilters = $('[data-isotope=filters]');

        isotopeContainer.each(function() {
            var isotopeLayout = $(this).data('isotope-layout').toLowerCase();
            var isotopeElements = $(this).data('isotope-elements');
            var layout = null;
            switch (isotopeLayout) {
                case 'fitrows':
                    layout = 'fitRows';
                    break;
                case 'masonry':
                    layout = 'masonry';
                    break;
                case 'vertical':
                    layout = 'vertical';
                    break;
                default:
                    layout = 'fitRows';
                    break;
            }

            // initialize Isotope after all images have loaded
            var container = $(this).imagesLoaded(function() {
                /* Add isotope special class */
                container.children('.' + isotopeElements).each(function() {
                    $(this).addClass('isotope-item');
                });

                /* Init Isotope */
                container.isotope({
                    itemSelector: '.isotope-item',
                    layoutMode: layout,
                    transitionDuration: '0.6s',
                });
            });
        });

        // Portfolio, Gallery special select trigger
        var select = $('select.filters-group');

        select.change(function() {
            var filters = '';
            if ($(this).val() != '') {
                filters = '.' + $(this).val();
            }
            isotopeContainer.isotope({
                filter: filters
            });
            return false;
        });

    });
});


/* Product animations */
jQuery(document).ready(function($) {
    "use strict";

        /* Products tooltips init */
        function product_tooltips() {
            $('.product-tooltip').hide().empty();
            $('.additional-buttons a, .additional-buttons span').each(function() {
                $(this).hoverIntent(mousein_triger, mouseout_triger);

                function mousein_triger() {
                    if ($(this).hasClass('compare')) {
                        $(this).parent().find('.product-tooltip').css('right', '42px').html(msg_compare).show();
                    };
                    if ($(this).hasClass('compare') && $(this).hasClass('added')) {
                        $(this).parent().find('.product-tooltip').css('right', '42px').html(msg_added).show();
                    };
                    if ($(this).hasClass('add_to_wishlist')) {
                        $(this).parent().parent().parent().find('.product-tooltip').css('right', '70px').html(msg_wish).show();
                    };
                    if ($(this).parent().hasClass('yith-wcwl-wishlistaddedbrowse')) {
                        $(this).parent().parent().parent().find('.product-tooltip').css('right', '72px').html(msg_wish_details).show();
                    };
                    if ($(this).parent().hasClass('yith-wcwl-wishlistexistsbrowse')) {
                        $(this).parent().parent().parent().find('.product-tooltip').css('right', '72px').html(msg_wish_view).show();
                    };
                    if ($(this).hasClass('jckqvBtn')) {
                        $(this).parent().find('.product-tooltip').css('right', '12px').html(msg_quick).show();
                    };
                }

                function mouseout_triger() {
                    $(this).parent().find('.product-tooltip').hide().empty();
                    if ($(this).hasClass('add_to_wishlist') || $(this).parent().hasClass('add_to_wishlist') || $(this).parent().hasClass('yith-wcwl-wishlistexistsbrowse') || $(this).parent().hasClass('yith-wcwl-wishlistaddedbrowse')) {
                        $(this).parent().parent().parent().find('.product-tooltip').hide().css('right', '12px').empty();
                    };
                }
            });
        }
        product_tooltips();

        /* Product Extra Gallery slider */
        function extra_product_gallery() {
          var product_extra_slider_container = $('.pt-extra-gallery-wrapper');

          product_extra_slider_container.each(function() {

            var product_extra_slider = $(this).find('.pt-extra-gallery');
            var slideCount = $(this).find('img').length;
            var sliderWidth = $(this).parent().width();
            var slideHeight = $(this).find('img').height();
            var sliderUlWidth = slideCount * sliderWidth;
            var prevBtn = $(this).find('.prod-prev');
            var nextBtn = $(this).find('.prod-next');

            $(this).find('img').css({
                "width": sliderWidth
            });
            $(this).css({
                "height": slideHeight,
                "width": sliderWidth
            });
            product_extra_slider.css({
                width: sliderUlWidth,
                marginLeft: -sliderWidth
            });
            product_extra_slider.find('img:last-child').prependTo(product_extra_slider);

            function moveLeft() {
              product_extra_slider.animate({
                  left: +sliderWidth
              }, 200, function() {
                  product_extra_slider.find('img:last-child').prependTo(product_extra_slider);
                  product_extra_slider.css('left', '');
              });
            };

            function moveRight() {
                product_extra_slider.animate({
                    left: -sliderWidth
                }, 200, function() {
                    product_extra_slider.find('img:first-child').appendTo(product_extra_slider);
                    product_extra_slider.css('left', '');
                });
            };

            prevBtn.click(function() {
                moveLeft();
            });
            nextBtn.click(function() {
                moveRight();
            });
          });
        }
        extra_product_gallery();

        /* List/Grid Switcher */
        function view_switсher() {
            var container = $('ul.products');
            if ($('.pt-view-switcher span.pt-list').hasClass('active')) {
                container.find('.product').each(function() {
                    if ($(this).not('.list-view')) {
                        $(this).addClass('list-view');
                    };
                });
            };

            $('.pt-view-switcher').on('click', 'span', function(e) {
                e.preventDefault();
                if ((e.currentTarget.className == 'pt-grid active') || (e.currentTarget.className == 'pt-list active')) {
                    return false;
                }

                if ($(this).hasClass('pt-grid') && $(this).not('.active')) {
                    container.animate({
                        opacity: 0
                    }, function() {
                        $('.pt-view-switcher .pt-list').removeClass('active');
                        $('.pt-view-switcher .pt-grid').addClass('active');
                        container.find('.product').each(function() {
                            $(this).removeClass('list-view');
                        });
                        container.stop().animate({
                            opacity: 1
                        });
                    });
                }

                if ($(this).hasClass('pt-list') && $(this).not('.active')) {
                    container.animate({
                        opacity: 0
                    }, function() {
                        $('.pt-view-switcher .pt-grid').removeClass('active');
                        $('.pt-view-switcher .pt-list').addClass('active');
                        container.find('.product').each(function() {
                            $(this).addClass('list-view');
                        });
                        container.stop().animate({
                            opacity: 1
                        });
                    });
                }
            });
        }
        view_switсher();

        /* Product dropdown filters animation */
        var settings = {
            interval: 100,
            timeout: 200,
            over: mousein_triger,
            out: mouseout_triger
        };

        function mousein_triger() {
            if ($(this).hasClass('widget_price_filter')) {
                $(this).find('form').css('visibility', 'visible');
            } else {
                $(this).find('.yith-wcan').css('visibility', 'visible');
            }
            $(this).addClass('hovered');
        }

        function mouseout_triger() {
            $(this).removeClass('hovered');
            if ($(this).hasClass('widget_price_filter')) {
                $(this).find('form').delay(300).queue(function() {
                    $(this).css('visibility', 'hidden').dequeue();
                });
            } else {
                $(this).find('.yith-wcan').delay(300).queue(function() {
                    $(this).css('visibility', 'hidden').dequeue();
                });
            }
        }

        $('#filters-sidebar .widget').hoverIntent(settings);

        /* Re-init js after Yith Ajax Filters */
        var rev_slider = $('.rev_slider_wrapper');
        if(!rev_slider[0]) {
          $(document).ajaxComplete(function(event, xhr, settings) {
            var event_url = settings.url;
            if (~event_url.indexOf("wc-ajax=get_refreshed_fragments")) {
              return false;
            } else {
              product_tooltips();
              extra_product_gallery();
              view_switсher();
            }
          });
        }
});

/* Lazy Sizes Init */
window.lazySizesConfig = {
    addClasses: true
};
