$(window).scroll(function () {
    var headTop = $(document).scrollTop();
    //新滑动模式
    if (headTop < 34) {//正常滑动
    	
    } else {//head top正常滑动，head bottom吸顶，head与bottom等高
    	
    }
    
    if (headTop > 10) {
        $('.header').stop().animate({
            height: '80px'
        }, 100);
        $('.header .head-top').stop().animate({
            top: '-34px'
        }, 20);
        $('.header .head-bottom').stop().animate({
            top: '0px'
        }, 20);
        $('.nav-bg').css('top', '80px');
    } else {
        $('.header').stop().animate({
            height: '114px'
        }, 100);
        $('.header .head-top').stop().animate({
            top: '0px'
        }, 10);
        $('.header .head-bottom').stop().animate({
            top: '34px'
        }, 10);
        $('.nav-bg').css('top', '114px');
    }
});

$(function () {
    //顶部导航效果
    $('.head-list .drop').hover(function() {
        $(this).addClass('hover');
    }, function() {
        $(this).removeClass('hover');
    });

    //主菜单效果
    var subid = null;//nva title状态记录器
    var contentid = null;//nav sub状态记录器
    $('.nav .have-sub').hover(function () {
        $(this).addClass('hover');
        $('.nav-bg').addClass('hover');
        subid = $(this).data('subid');
        $('.nav-bg .header-nav-hide').hide();
        $('#'+'sub-'+subid).show();
    }, function() {
        //延时检测重置状态
        var tVar = setTimeout(function() {
            subid = null;
            if(contentid == null) {
                $('.nav .have-sub').removeClass('hover');
                $('.nav-bg .header-nav-hide').hide();
                $('.nav-bg').removeClass('hover');
                contentid = null;
                subid = null;
            }
        }, 20);
        //clearTimeout(tVar);
    });

    $('.nav-bg').hover(function () {
        contentid = subid;
    }, function() {
        //延时检测重置状态
        var tVar = setTimeout(function() {
            contentid = null;
            if(subid == null) {
                $('.nav .have-sub').removeClass('hover');
                $('.nav-bg .header-nav-hide').hide();
                $('.nav-bg').removeClass('hover');
                contentid = null;
                subid = null;
            }
        }, 20);
        //clearTimeout(tVar);
    });

    //所有侧边pin栏
    /*
    $(".pinned").pin({
        containerSelector: ".sidebox"
    });
    */
    //alert($('html').attr('class'));
});

//返回顶部效果
/*
$(window).scroll(function() {//当屏幕滚动，触生 scroll 事件
    var top1 = $(this).scrollTop();//获取相对滚动条顶部的偏移
    if (top1 > 100) {//当偏移大于200px时让图标淡入（css设置为隐藏）
        $(".back-top").stop().fadeIn();
    }else{
        //当偏移小于200px时让图标淡出
        $(".back-top").stop().fadeOut();
    }
});
*/

/*
$(function () {
    $('.nav li').hover(function () {
        var dataNum = $(this).data('nav');
        if (dataNum != undefined) {
            $(this).find('a').css('color', '#ef880c');
            $(this).find('.navIcon').attr('src', '/assets/2017pc/img/head/nav_hover.png');
            $(this).siblings().find('a').removeAttr('style');
            $(this).siblings().find('.navIcon').attr('src', '/assets/2017pc/img/head/nav.png');
            $('.navHover').show(0, function () {
                $(this).find('ul').eq(dataNum).show().siblings().hide();
            })
            $('.navHover,.headNav').mouseleave(function () {
                $('.navHover').hide(0, function () {
                    $('.navHover').find('ul').hide();
                    $('.nav li a').removeAttr('style');
                    $('.nav li a .navIcon').attr('src', '/assets/2017pc/img/head/nav.png');
                })
            })
            $('.navCode').hover(function () {
                $('.navHover').hide(0, function () {
                    $('.navHover').find('ul').hide();
                    $('.nav li a').removeAttr('style');
                    $('.nav li a .navIcon').attr('src', '/assets/2017pc/img/head/nav.png');
                })
            })
        } else {
            $(this).find('a').css('color', '#ef880c');
            $(this).siblings().find('a').removeAttr('style');
            $(this).siblings().find('.navIcon').attr('src', '/assets/2017pc/img/head/nav.png');
            $('.navHover').hide(0, function () {
                $(this).find('ul').hide();
            })
        }
    })
});
*/

window.turen = yii;
var csrfParam = turen.getCsrfParam();
var csrfToken = turen.getCsrfToken();

/*
对应后台，创建以下模块turen.com、turen.cms、turen.ext、turen.shop、turen.sys、turen.tool
*/
turen.com = (function($) {
    var pub = {
        //状态更新
        updateStatus: function(_this) {
            _this = $(_this);
            var url = _this.data('url');
            var callback = function(res, _this) {
                if(res.state) {
                    _this.text(res.msg);
                    //$.notify('已设置的状态为：'+res.msg+'！', 'success');//状态切换成功
                } else {
                    //$.notify(res.msg+'！', 'error');
                }
            };
            commonRemote({
                url: url,
                data: {},
                callback: callback,
                _this: _this
            });
        },
        scrollTop: function(time) {
            $("body, html").animate({scrollTop: 0}, time);
        },
    };

    // 私有方法
    function commonRemote(settings) {
        var defaultSetting = {
            url: null,
            data: null,
            callback: null,
            _this: null,
            type: 'POST'
        };
        $.extend(defaultSetting, settings);
        data[csrfParam] = csrfToken;
        $.ajax({
            url: defaultSetting.url,
            type: defaultSetting.type,
            dataType: 'json',
            context: defaultSetting._this,
            cache: false,
            data: defaultSetting.data,
            success: function(res) {
                if (res['state']) {
                    if(callback) {
                        callback(res, _this);
                    }
                } else {
                    //$.notify(res['msg'], 'warn');
                }
            }
        });
    }

    return pub;
})(jQuery);