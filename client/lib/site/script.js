// JavaScript Document
var pkcaller = false;
var pkname = false;

function scrollup() {
    $('.inbody').animate({scrollTop: 0}, 'slow');
}

function showUrlInDialog(url, func, title, extend_class, setWidth, setHeight) {
    setWidth = typeof setWidth !== 'undefined' ? setWidth : 650;
    setHeight = typeof setHeight !== 'undefined' ? setHeight : 350;

    var tag = $('<div></div>');
    $.ajax({
        url: url,
        success: function(data) {
            tag.html(data).dialog({
                autoOpen: false,
                height: setHeight,
                width: setWidth,
                modal: true,
                title: title,
                close: function() {
                    $('div.' + extend_class).dialog("destroy").remove();
                }
            }).dialog('open');
            tag.html(data).addClass(extend_class);
        }
    });
    pkcaller = self[func];
}

$("a#gantidomain").click(function() {
    showUrlInDialog(root + 'mod_user/gantidomain', "getdomain", "Ganti Domain", "form_ganti_domain");
});

function createAutoClosingAlert(selector, delay) {
    var alert = $(selector).alert();
    window.setTimeout(function() {
        alert.alert('close')
    }, delay);
}

$(window).resize(function() {
    resizeH();
});

function getdomain() {
    $.ajax({
        url: root + "mod_user/getdomain",
        type: "POST",
        dataType: "json",
        success: function(json) {
            $('span[id="label_domain_program"]').prepend(json['domain']['nama_proyek']);
        }
    });
}

$(document).ready(function() {

    $(".tooltips").tooltip();
    //    $.ajaxSetup({
    //        error: function(jqXHR, exception) {
    //            if (jqXHR.status === 0) {
    //                alert('Not connect.\n Verify Network.');
    //            } else if (jqXHR.status == 404) {
    //                alert('Requested page not found. [404]');
    //            } else if (jqXHR.status == 401) {
    //                window.location.href = 'mod_user/user_auth';
    //            } else if (jqXHR.status == 500) {
    //                alert('Internal Server Error [500].');
    //            } else if (exception === 'parsererror') {
    //                window.location.href = 'mod_user/user_auth';
    //            } else if (exception === 'timeout') {
    //                alert('Time out error.');
    //            } else if (exception === 'abort') {
    //                alert('Ajax request aborted.');
    //            } else {
    //                alert('Uncaught Error.\n' + jqXHR.responseText);
    //            }
    //        }
    //    });

    $(document).ajaxError(function(xhr, status, err) {
        if (status.status == 401)
            window.location.href = 'mod_user/user_auth';
    });

    getdomain();
    $(".alert").alert();
    resizeH();
});

$(".ajax").ajaxStart(function() {
    $(this).show();
});

$(".ajax").ajaxComplete(function() {
    $(this).hide();
});


function resizeH() {
    var h = $('body').height();
    $('.body,.sidenav').height(h - 160);
}

var cNavs = $.cookie('Navs');
if (cNavs) {
    cNavs = cNavs.split('|');
    for (var cn = 0; cn < cNavs.length; cn++) {
        $('.togsub:eq(' + cNavs[cn] + ')').show();
    }
}

var cTop = $.cookie('sTop');
if (cTop)
    $('.sidenav').each(function() {
        this.scrollTop = parseInt(cTop)
    });

$('.tognav').click(function() {
    var obj = this;
    $(this).next('div').slideToggle(function() {
        saveNavs();
        $('.sidenav').animate({
            scrollTop: obj.offsetTop
        });
    });
    this.blur();
    return false;
});

$('#expandb').click(function() {
    $('.togsub').slideDown(function() {
        saveNavs();
    });
    this.blur();
    return false;
});

$('#collapseb').click(function() {
    $('.togsub').slideUp(function() {
        saveNavs();
    });
    this.blur();
    return false;
});


$('.subtabs').click(function() {
    var maxh = $('.body').height() + 28;
    $('.tabdroplist').toggle('fast');
    if ($('.tabdroplist').height() >= (maxh))
        $('.tabdroplist').css({
            height: maxh
        });
    else
        $('.tabdroplist').css('height', 'auto');

    this.blur();
    return false;
});

$('.header').hover(function() {

}, function() {
    $('.tabdroplist').hide();
});

$('.closenav').click(function() {
    $('.sidenav, .sidenavtop').hide();
    $('.bodytop, .body').css('margin-left', 6);
    $('.sidetog').show();
    $.cookie('closeNav', 1);
    var panjang = $('.content').width() - 20;
    $("#list2").jqGrid("setGridWidth", panjang);
});

$('.sidetog').click(function() {
    $('.sidenav, .sidenavtop').show();
    $('.bodytop, .body').css('margin-left', 255);
    $('.sidetog').hide();
    $.cookie('closeNav', null);
    if (cTop)
        $('.sidenav').each(function() {
            this.scrollTop = parseInt(cTop)
        });
    var panjang = $('.content').width() - 20;
    $("#list2").jqGrid("setGridWidth", panjang);
    return false;
});

$('.sidenav').scroll(function() {
    $.cookie('sTop', this.scrollTop);
});

if ($('.togsub:visible').length == $('.togsub').length)
    $('#expandb').attr('class', 'nleftntoggled');
if (!$('.togsub:visible').length)
    $('#collapseb').attr('class', 'nrightntoggled');

function saveNavs()
{
    var nNavs = '';
    $('.togsub:visible').each
            (
                    function()
                    {
                        var n = $('.togsub').index(this);
                        if (nNavs != '')
                            nNavs += '|';
                        nNavs += n;
                    }
            );
    $('#expandb').attr('class', 'nleft');
    $('#collapseb').attr('class', 'nright');
    if ($('.togsub:visible').length == $('.togsub').length)
        $('#expandb').attr('class', 'nleftntoggled');
    if (!$('.togsub:visible').length)
        $('#collapseb').attr('class', 'nrightntoggled');
    $.cookie('Navs', nNavs);
}

$('a.goright').click(function() {
    this.blur();
    return false;
}).mousedown(function() {
    var tabswidth = $('.tab_pane').width() - $('.body_tabs').width() + 50;
    $('.body_tabs').animate({
        scrollLeft: tabswidth
    }, 2000);
}).mouseup(function() {
    $('.body_tabs').stop();
});

$('a.goleft').click(function() {
    this.blur();
    return false;
}).mousedown(function() {
    $('.body_tabs').animate({
        scrollLeft: 0
    }, 2000);
}).mouseup(function() {
    $('.body_tabs').stop();
});

$('body_tabs a.sel').each(function() {
    var tabslide = $('.body_tabs').get(0);
    var tabsview = $('.body_tabs').width() - 52;
    //alert(this.offsetLeft + ' ' + tabsview + ' ' + tabslide.scrollLeft);
    if ((this.offsetLeft + $(this).width()) >= tabsview)
        tabslide.scrollLeft = this.offsetLeft - tabsview + $(this).width();
    //return true;
    //if (this.offsetLeft>tabsview)
    //	tabslide.scrollLeft = (tabslide.scrollLeft + this.offsetLeft) - $('.body_tabs').width() + $(this).width() + 52;
    //else 
    if (tabslide.scrollLeft > this.offsetLeft)
        tabslide.scrollLeft = this.offsetLeft + 1;
});

//$('.body_sub a').click(function(){
//    return false;
//});

$('a.tab_close').click(function() {
    if ($(this).prev().hasClass('sel'))
        return true;
    else
        $.get(this.href + '/1');
    $('.tabdroplist a[href=' + $(this).prev().attr('href') + ']').remove();
    $(this).prev().remove();
    $(this).remove();
    return false;
});

function onError()
{
    //self.location.reload();
    alert('error');
}

$('.ptabs a').click(function() {
    $(this).addClass('sel').siblings().removeClass('sel');
    var n = $('.ptabs a').index(this);
    $('.fpane:eq(' + n + ')').show().siblings('div.fpane').hide();
    this.blur();
    return false;
});



$('button.pklist').each(function() {
    var n = $('button.pklist').index(this);
    //this.innerHTML += ' ' + n;
    this.idx = n;
}).click(function() {
    var nvalue = $(this).attr('rel');
    var vals = nvalue.split('&');
    var dg = {};
    var el = false;
    var n = this.idx; //$('button.pklist').index(this);
    var func = false;

    if (!$('#pklist' + n).length) {
        if ($(this).hasClass('disabled'))
            return false;

        for (i = 0; i < vals.length; i++) {
            val = vals[i].split('=');
            if (val.length < 2)
                continue;
            switch (val[0]) {
                case 'func':
                    el.func = val[1];
                    break;
                case 'type':
                    el = document.createElement(val[1]);
                    el.id = 'pklist' + n;
                    if (val[1] == 'iframe') {
                        el.frameBorder = "0";
                    }
                    break;

                case 'src':
                    el.src = val[1];
                    break;

                case 'width':
                    dg.width = +val[1];
                    el.style.width = val[1] + 'px';
                    break;

                case 'height':
                    dg.height = +val[1];
                    el.style.height = val[1] + 'px';
                    break;

                case 'id_proyek':
                    if (val[1] == 'true') {
                        var id_proyek = $('select[name="subunit_proyek"]').val();
                        el.src = el.src + "/" + id_proyek;
                    }
                    break;
            }
        }

        $('body').append(el);
        dg.title = this.title;
        dg.autoOpen = false;
        dg.close = function(event, ui) {
            $('#pklist' + n).remove();
        }
        dg.closeOnEscape = true;
        dg.modal = true;
        dg.overlay = {
            opacity: 0.4,
            background: "#000"
        };
        dg.resizable = false;
        $('#pklist' + n).dialog(dg);

    } else {
        el = $('#pklist' + n).get(0);
    }
    pkcaller = self[el.func];
    pkname = '#pklist' + n;
    $('#pklist' + n).dialog('open');
});

$('.content a.uppane').click(function() {
    $('.headpane').slideToggle('slow');
    $(this).toggleClass('downpane');
});

//Cookies
$(".topfields select, .topfields input").change(function() {
    $.cookie(mod + this.name, this.value);
});

$(".topfields select, .topfields input").each(function() {

    var nv = $.cookie(mod + this.name);
    if (nv)
        this.value = nv;
});
