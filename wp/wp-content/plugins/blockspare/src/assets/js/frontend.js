(function ($) {
    "use strict";
    var n = window.AFTHRAMPES_JS || {};

    //Skill Bar
    n.SkillBar = function () {

        if ($(".blockspare_progress-bar-container").length > 0) {
            $(".blockspare_progress-bar-container").waypoint(function () {
                $(this.element).find(".blockspare-skillbar-item").each(function () {
                    var data_percent = $(this).attr("data-percent");
                    $(this).find(".blockspare-skillbar-bar").animate({
                        width: data_percent + "%"
                    }, 20 * data_percent)
                })
            }, {
                offset: "50%",
                triggerOnce: !0
            });

        }
    },

        //CountUP
        n.CountUp = function () {
            if ($('.blockspare-section-counter-bar').length > 0) {
                $('.blockspare-counter').counterUp({
                    delay: 10,
                    time: 1600

                });
            }
        },

        n.Tabs = function(){

            $('.blockspare-tab-title').on('click', function (event) {
                var $blockspareTab = $(this).parent();
                var blockspareIndex = $blockspareTab.index();
                if ($blockspareTab.hasClass('blockspare-active')) {
                    return;
                }

                $blockspareTab.closest('.blockspare-tab-nav').find('.blockspare-active').removeClass('blockspare-active');
                $blockspareTab.addClass('blockspare-active');
                $blockspareTab.closest('.blockspare-block-tab').find('.blockspare-tab-content.blockspare-active').removeClass('blockspare-active');
                $blockspareTab.closest('.blockspare-block-tab').find('.blockspare-tab-content').eq(blockspareIndex).addClass('blockspare-active')

                if ($blockspareTab.hasClass('blockspare-active')) {
                    var tab_bg = $blockspareTab.find('.blockspare-tab-title ').attr('tab-bg');

                    var text = $blockspareTab.find('.blockspare-tab-title ').attr('tab-text');

                    $(this).parents('.blockspare-block-tab').find('.blockspare-tab-title').css('background-color', tab_bg);
                    $(this).parents('.blockspare-block-tab').find('.blockspare-tab-title').css('color', text);

                    var tab_abg = $blockspareTab.find('.blockspare-tab-title ').attr('atab-bg');
                    var atext = $blockspareTab.find('.blockspare-tab-title ').attr('atab-text');
                    $blockspareTab.find('.blockspare-tab-title ').css('background-color',tab_abg);
                    $blockspareTab.find('.blockspare-tab-title ').css('color',atext);

                }

            });


        },

        n.Accordion = function(){
            $('.blockspare-block-accordion:not(.blockspare-accordion-ready)').each(function () {
                const $accordion = $(this);
                const itemToggle = $accordion.attr('data-item-toggle');
                const bgcolor = $accordion.find('.blockspare-accordion-body').attr('data-bg');
                $('.blockspare-accordion-body').css('background-color',bgcolor);
                $accordion.addClass('blockspare-accordion-ready');
                $accordion.on('click', '.blockspare-accordion-item .blockspare-accordion-panel', function (e) {
                    e.preventDefault();

                    const $selectedItem = $(this).parent('.blockspare-accordion-item');
                    const $selectedItemContent = $selectedItem.find('.blockspare-accordion-body');
                    const isActive = $selectedItem.hasClass('blockspare-accordion-active');

                    var _pnl = $accordion.find('.blockspare-type-fill').attr('data-pan');
                    var text_pnl = $accordion.find('.blockspare-type-fill').attr('data-txt-color');

                    $accordion.find('.blockspare-accordion-panel').css('background-color',_pnl);
                    $accordion.find('.blockspare-accordion-panel-handler').css('color',text_pnl);

                    if (isActive) {

                        $selectedItemContent.css('display', 'block').slideUp(150);
                        $selectedItem.removeClass('blockspare-accordion-active');
                    } else {

                        var act_pnl_text= $accordion.find('.blockspare-type-fill').attr('data-act-color');
                        var act_pnl = $accordion.find('.blockspare-type-fill').attr('data-active');
                        $selectedItem.find('.blockspare-accordion-panel').css({"background-color":act_pnl});
                        $selectedItem.find('.blockspare-accordion-panel-handler').css({"color":act_pnl_text});
                        $selectedItemContent.css('display', 'none').slideDown(150);
                        $selectedItem.addClass('blockspare-accordion-active');

                    }

                    if (itemToggle == 'true') {
                        const $collapseItems = $accordion.find('.blockspare-accordion-active').not($selectedItem);
                        if ($collapseItems.length) {
                            $collapseItems.find('.blockspare-accordion-body').css('display', 'block').slideUp(150);
                            $collapseItems.removeClass('blockspare-accordion-active');
                        }
                    }
                });
            });
        };
    n.ImageCarousel = function(){
        var next = $('.blockspare-carousel-items').attr('data-next');
        var prev = $('.blockspare-carousel-items').attr('data-prev');


        $('.blockspare-carousel-items').slick({
            nextArrow: '<span class="slide-next ' + next + '"></span>',
            prevArrow: '<span class="slide-prev ' + prev+' "></span>',
        });
    };

    n.Masonry = function(){
        var container =$('.blockspare-masonry-wrapper ul');
        container.imagesLoaded( function() {
            container.masonry( {
                itemSelector: '.blockspare-gallery-item',
                transitionDuration: '0.2s',
                percentPosition: true,
            } );
        } );
    };
    n.postCarousel = function(){
        var next = $('.blockspare-post-carousel').attr('data-next');
        var prev = $('.blockspare-post-carousel').attr('data-prev');
        $('.blockspare-post-carousel').slick({
            nextArrow: '<span class="slide-next ' + next + '"></span>',
            prevArrow: '<span class="slide-prev ' + prev+' "></span>',
        });
    }


    $(document).ready(function () {
        $('.logo-wrapper').slick();
        $('.slider-wrapper').slick();
        $('.instagram-layout-carousel').slick();
        n.ImageCarousel();
        n.SkillBar();
        n.CountUp();
        n.Tabs();
        n.Masonry();
        n.Accordion();
        n.postCarousel();
    })

})(jQuery);
