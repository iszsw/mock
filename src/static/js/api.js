$(function () {

    $('.lsm-scroll').slimscroll({
        height: 'auto',
        position: 'right',
        railOpacity: 1,
        size: "5px",
        opacity: .4,
        color: '#fffafa',
        wheelStep: 5,
        touchScrollStep: 50
    });

    $('.lsm-container ul ul').css("display", "none");
    // lsm-sidebar收缩展开
    $('.lsm-sidebar a').on('click', function () {
        $('.lsm-scroll').slimscroll({
            height: 'auto',
            position: 'right',
            size: "8px",
            color: '#9ea5ab',
            wheelStep: 5,
            touchScrollStep: 50
        });
        $(this).parent("li").siblings("li.lsm-sidebar-item").children('ul').slideUp(200);
        if ($(this).next().css('display') == "none") {
            //展开未展开
            $(this).next('ul').slideDown(200);
            $(this).parent('li').addClass('lsm-sidebar-show').siblings('li').removeClass('lsm-sidebar-show');
        } else {
            //收缩已展开
            $(this).next('ul').slideUp(200);
            $(this).parent('li').removeClass('lsm-sidebar-show');
        }
    });

    $(document).on('mouseover', '.second.lsm-popup.lsm-sidebar > div > ul > li', function () {
        if (!$(this).hasClass("lsm-sidebar-item")) {
            $(".lsm-popup.third").hide();
            return;
        }
        $(".lsm-popup.third").length == 0 && ($(".lsm-container").append("<div class='third lsm-popup lsm-sidebar'><div></div></div>"));
        $(".lsm-popup.third>div").html($(this).html());
        $(".lsm-popup.third").show();
        var top = $(this).offset().top;
        var d = $(window).height() - $(".lsm-popup.third").height();
        if (d - top <= 0) {
            top = d >= 0 ? d - 8 : 0;
        }
        $(".lsm-popup.third").stop().animate({"top": top}, 100);
    });

    $(document).on('mouseover', '.third.lsm-popup', function () {
        $(".lsm-popup.second").show();
        $(".lsm-popup.third").show();
    });

    function goTop(jump = false) {
        $('html,body').animate({scrollTop: 0}, jump ? 0 : 'normal');
    }

    $(document).on('click', '.go-top', goTop);

    $(document).scroll(function () {
        if ($(document).scrollTop() > 100) {
            $('.go-top').show()
        } else {
            $('.go-top').hide()
        }
    })

    $(document).on('click', '[data-target]', function () {
        var node = $(this).data('target')
        $(node).toggle()
    });

    $(document).on('click', '.logout', function () {
        location.href = '/login'
    });

    $(document).on('click','.min-menu',function(){
        if ($('.container').hasClass('target-menu')) {
            $('.container').removeClass('target-menu')
        }else{
            $('.container').addClass('target-menu')
        }
    });

    $(document).on('click', '.lsm-sidebar .frame-info', function () {
        if (!$(this).hasClass('active')) {
            $('.lsm-sidebar .frame-info.active').removeClass('active');
            $(this).addClass('active')
            $('.frame-list').hide()
            var hash = $(this).data('frame')
            $(hash).show()
            window.location.hash = hash

            init && $('.container').removeClass('target-menu')

            goTop(true)
        }
    });

    var init = false;
    (function () {
        try {
            if (!location.hash) {throw '';}
            let hash = decodeURI(location.hash)
            if ($('.frame-info[data-frame="'+hash+'"]').length === 0) {throw '';}
            var p = $('.frame-info[data-frame="'+hash+'"]').click().parents('.lsm-sidebar-item').find('a:eq(0)').click()
            _tap_first = false
            $('[data-menu="#'+p.parents('.app-menu-list').attr('id')+'"]').click()
        } catch(err) {
            $('.app-menu-list:eq(0)').find('.lsm-sidebar-item>a').eq(0).click().next().find('.frame-info:eq(0)').eq(0).click()
        }

        $('.target-menu').removeClass('target-menu')
        init = true;
    }())

});
