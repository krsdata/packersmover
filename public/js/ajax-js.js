/* User validation */
$(document).on('blur', '.user_validate', function(e) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': _token
        }
    });
    e.preventDefault();
    $.ajax({
        url: APP_URL + '/validate-user',
        method: 'POST',
        data: $('#manager').serialize(),
        async: false,
        success: function(data) {

            $('label[for="email_val_error"]').text("");
            $('label[for="email"]').text("");
            if (data.success == false) {
                $.each(data.errors, function(key, value) {
                    $('label[for="email_val_error"]').text(value);
                });
            } else {
                $('label[for="email_val_error"]').text("");
            }
        },
        error: function(e) {
            if (XMLHttpRequest.readyState == 4) {
                showDangerToast('HTTP error!!');
            } else if (XMLHttpRequest.readyState == 0) {
                showDangerToast('Your Network connection is lost!!');

            } else {
                showDangerToast('something weird is happening!!');
            }
        }
    });
});

/* common delete */
$(document).on('click', '.delete', function(e) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': _token
        }
    });
    $this = $(this);
    var id = $(this).data("id");
    var model = $(this).data("model");
    e.preventDefault();
    swal({
            title: 'Are you sure?',
            text: "It will be deleted permanently!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            showLoaderOnConfirm: true,
            allowOutsideClick: false,

        })
        .then((willDelete) => {
            if (willDelete.value == true) {
                $.ajax({
                    type: "POST",
                    url: APP_URL + '/admin/common/delete',
                    data: {
                        "id": id,
                        "model": model
                    },
                    success: function(data) {
                        if (data.success == true) {
                            var uri = window.location.toString();
                            if (uri.indexOf("?") > 0) {
                                var clean_uri = uri.substring(0, uri.indexOf("?"));
                                window.history.replaceState({}, document.title, clean_uri);
                            }
                            if (model == 'User') {
                                $('tr#' + id).remove();
                            }
                            if (model == 'CompanyUsers') {
                                $('#tr' + id).remove();
                            }
                            showSuccessToast(data.msg);
                            window.setTimeout(function() { location.reload() }, 3000)
                        } else {
                            showDangerToast(data.msg);
                        }
                    },
                    error: function(data) {
                        if (XMLHttpRequest.readyState == 4) {
                            showDangerToast('HTTP error!!');
                        } else if (XMLHttpRequest.readyState == 0) {
                            showDangerToast('Your Network connection is lost!!');
                        } else {
                            showDangerToast('something weird is happening!!');
                        }
                    }
                });
            }
        });
});

/* change hash value */
$(window).on('hashchange', function() {
    if (window.location.hash) {
        var page = window.location.hash.replace('#', '');
        if (page == Number.NaN || page <= 0) {
            return false;
        } else {
            // getData(page);
        }
    }
});

/**
 * form post call ajax
 * @param {*} url
 * @param {*} data
 * @param {*} callback
 * @param {*} $this
 */
function ajaxCallPOST(url, data, callback, $this) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': _token
        }
    });
    $.ajax({
        url: url,
        method: 'POST',
        data: data,
        async: false,
        beforeSend: function() {},
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            if (XMLHttpRequest.readyState == 4) {
                showDangerToast('HTTP error!!');
            } else if (XMLHttpRequest.readyState == 0) {
                showDangerToast('Your Network connection is lost!!');
            } else {
                showDangerToast('something weird is happening!!');
            }
            setTimeout(function() { $this.attr("disabled", false); }, 3000);
        },
        success: function(data) {
            $(".error").text("");
            if (data.success == false) {
                if (data.msg_type == 'error') {
                    showDangerToast(data.msg);
                    setTimeout(function() { $this.attr("disabled", false); }, 3000);
                } else {
                    $.each(data.errors, function(key, value) {
                        $('#' + key + "-error-server").text(value);
                    });
                }
                setTimeout(function() { $this.attr("disabled", false); }, 3000);
            } else {
                if (data.msg_type == 'success') {
                    window[callback](data, $this);
                } else {
                    $.each(data.errors, function(key, value) {
                        $('#' + key + "-error-server").text(value);
                    });
                    setTimeout(function() { $this.attr("disabled", false); }, 3000);
                }
            }
        },
        complete: function(data) {}
    });
}

/**
 * form get call ajax
 * @param {*} url
 * @param {*} data
 * @param {*} callback
 * @param {*} $this
 */
function ajaxCallGET(url, data, callback, $this) {
    $.ajax({
        type: "GET",
        dataType: 'json',
        url: url,
        data: data,
        async: false,
        cache: false,
        success: function(data) {
            if (data.success == false) {
                if (data.msg_type == 'error') {
                    showDangerToast(data.msg);
                    setTimeout(function() { $this.attr("disabled", false); }, 3000);
                } else {
                    $.each(data.errors, function(key, value) {
                        $('#' + key + "-error-server").text(value);
                    });
                }
                setTimeout(function() { $this.attr("disabled", false); }, 3000);
            } else {
                if (data.msg_type == 'success') {
                    window[callback](data, $this);
                } else {
                    $.each(data.errors, function(key, value) {
                        $('#' + key + "-error-server").text(value);
                    });
                    setTimeout(function() { $this.attr("disabled", false); }, 3000);
                }
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            if (XMLHttpRequest.readyState == 4) {
                showDangerToast('HTTP error!!');
            } else if (XMLHttpRequest.readyState == 0) {
                showDangerToast('Your Network connection is lost!!');
            } else {
                showDangerToast('something weird is happening!!');
            }
            setTimeout(function() { $this.attr("disabled", false); }, 3000);
        }
    });
}

/**
 * ajax call submit
 * @param {*} obj
 */
function ajaxCommonSumitForm(obj) {
    $this = $($(obj));
    $from = $this.closest("form");
    var callback = $this.attr("callback");
    if (!$from.valid()) return false;
    url = $from.attr('action');
    data = $from.serialize();
    $(".server-error").html("");
    ajaxCallPOST(url, data, callback, $this);
}

// ajax call get data from server and append to the div
$(window).scroll(function() {
    if ($("#loadmorecount").length) {
        if ($(window).scrollTop() == $(document).height() - $(window).height()) {
            ajaxLoadMore("false");
        }
    }
});
/**
 * ajax laod more on scrolll
 * @param {*} reset
 */
function ajaxLoadMore(reset) {
    $this = $("#loadmorecount");
    if (reset == "true") {
        $(".ajaxloadmorediv").html("");
        $this.attr("data-disabled", "");
        $this.attr("data-page", "1");
    }
    url = $this.attr("data-url");
    disabled = $this.attr("data-disabled");
    model = $this.attr("data-model");
    page = $this.attr("data-page");
    size = $this.attr("data-size");
    select = $this.attr("data-select");
    wkey = $this.attr("data-wkey");
    wcomp = $this.attr("data-wcomp");
    wval = $this.attr("data-wval");
    callback = $this.attr("data-callback");
    data = { model: model, page: page, size: size, select: select, wkey: wkey, wcomp: wcomp, wval: wval };
    if (disabled != "disabled") {
        ajaxCallPOST(url, data, callback, $this);
    }
    console.log("disabled:" + disabled);
}


// Simple JavaScript Templating
(function() {
    var cache = {};
    this.tmpl = function tmpl(str, data) {
        var fn = !/\W/.test(str) ?
            cache[str] = cache[str] ||
            tmpl(document.getElementById(str).innerHTML) :
            new Function("obj", "var p=[],print=function(){p.push.apply(p,arguments);};" + "with(obj){p.push('" +
                str
                .replace(/[\r\t\n]/g, " ")
                .split("<%").join("\t")
                .replace(/((^|%>)[^\t]*)'/g, "$1\r")
                .replace(/\t=(.*?)%>/g, "',$1,'")
                .split("\t").join("');")
                .split("%>").join("p.push('")
                .split("\r").join("\\'") + "');}return p.join('');");
        return data ? fn(data) : fn;
    };
})();

/**
 * redirect url with id
 */
$(document).on("click", ".redirect_url", function() {
    $this = $(this);
    url = $this.attr('data-url');
    id = $this.attr("data-id");
    surl = url.replace("/0", "/" + id);
    window.location = surl;
});

/**
 * common search on change
 */
function searchmoreaction() {
    var val = $("#searchmoreid").val();
    $("#loadmorecount").attr("data-wval", val);
    ajaxLoadMore("true");
}

/**
 * common post form submit
 * @param {*} data
 * @param {*} $this
 */
function after_create_action(data, $this) {
    showSuccessToast(data.msg);
    $this.attr("disabled", false);
    if (data.op == "create") {
        $("#create_action_reset").trigger("click");
        setTimeout(function(){
            window.location = APP_URL+data.redirect_url;
        },3000);
    }
}


/**
 * comon print div
 * @param {*} divName
 */
function printDiv(divName) {
    var printContents = document.getElementById(divName).innerHTML;
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
}


/**
 * common downlaod pdf
 */
$(document).on("click", ".downloadPDF", function() {
    var filename = $(this).attr("filename");
    var textid = $(this).attr("textid");
    var pdffileurl = $(this).attr("pdffileurl");
    if (pdffileurl) {
        download_file(pdffileurl, filename);
    }
});
/**
 * common close modal
 * @param {*} divid
 */
function closeModal(divid) {
    $("#" + divid).modal("hide");
}


/**
 * common send mail
 */
$(document).on("click", ".sendMail", function() {
    $this = $(this);
    filename = $this.attr("filename");
    url = $this.attr("url");
    data = {};
    surl = url.replace("/0", "/" + filename);
    callback = "send_mail_callback";
    ajaxCallGET(surl, data, callback, $this);
});

function send_mail_callback(data, $this) {
    showSuccessToast(data.msg);
}


/**
 * common submit form button click
 */
$(document).on("click", ".submit_from", function() {
    $this = $(this);
    $from = $this.closest("form");
    var url = $this.closest("form").attr("action");
    if (!$from.valid()) {
        return false;
    }
    callback = "submit_from_callback";
    data = $from.serialize();
    ajaxCallPOST(url, data, callback, $this);
    return false;
});

function submit_from_callback(data, $this) {
    showSuccessToast(data.msg);
    setTimeout(function() {
        $this.attr("disabled", false);
    }, 3000);
    if (data.reset) {
        $this.closest("form").reset();
    }
}

/* Helper function */
function download_file(fileURL, fileName) {
    // for non-IE
    if (!window.ActiveXObject) {
        var save = document.createElement('a');
        save.href = fileURL;
        save.target = '_blank';
        var filename = fileURL.substring(fileURL.lastIndexOf('/') + 1);
        save.download = fileName || filename;
        if (navigator.userAgent.toLowerCase().match(/(ipad|iphone|safari)/) && navigator.userAgent.search("Chrome") < 0) {
            document.location = save.href;
            // window event not working here
        } else {
            var evt = new MouseEvent('click', {
                'view': window,
                'bubbles': true,
                'cancelable': false
            });
            save.dispatchEvent(evt);
            (window.URL || window.webkitURL).revokeObjectURL(save.href);
        }
    }

    // for IE < 11
    else if (!!window.ActiveXObject && document.execCommand) {
        var _window = window.open(fileURL, '_blank');
        _window.document.close();
        _window.document.execCommand('SaveAs', true, fileName || fileURL)
        _window.close();
    }
}

//get subscription expiry date
function get_expiry_date(subscription_id,startDate,selected_company_id){
    if(subscription_id && startDate && selected_company_id){
        $.ajax({
            url: APP_URL + '/get_expiry',
            method: 'GET',
            data: {subscription_id:subscription_id,startDate:startDate,selected_company_id:selected_company_id},
            async: false,
            beforeSend: function() {},
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                if (XMLHttpRequest.readyState == 4) {
                    showDangerToast('HTTP error!!');
                } else if (XMLHttpRequest.readyState == 0) {
                    showDangerToast('Your Network connection is lost!!');
                } else {
                    showDangerToast('something weird is happening!!');
                }
                setTimeout(function() { $this.attr("disabled", false); }, 3000);
            },
            success: function(data) {

                if (data.success == false) {

                } else {
                    if (data.msg_type == 'success') {
                        $('#datepicker2').val(data.end_date);
                    }
                }
            },

        });
    }
}


/**
 * common ajax pagination call
 */
$(document).on('click', '#ajax_pagination a', function(event) {

    var currentab = $(".nav-tabs").find(".active a[data-toggle='tab']").attr('href');
    if (currentab != undefined) {
        var currentActiveTab = currentab.substring(1, currentab.length);
    }


        var page = $(this).attr('href').split('page=')[1];


    if (page == undefined) {

        $('#ajax_pagination li a').removeClass('active');
    } else {

        $('#ajax_pagination li a').removeClass('active');
        $(this).parent('li').addClass('active');
    }
    event.preventDefault();
    var myurl = $(this).attr('href');
    getData(page);
});

/**
 * get pagination data
 * @param {*} page
 */
function getData(page) {

    var currentab = $(".nav-tabs").find(".active a[data-toggle='tab']").attr('href');
    if (currentab != undefined) {
        var currentActiveTab = currentab.substring(1, currentab.length);
    }


        form = $('.search_filter_form').attr('name');
        var url = '?page=' + page;


    //var form=$(this).parents("form").attr('name');


    if (form != undefined && currentActiveTab != undefined) {
        var form_data = $('#' + form).serialize() + "&active_tab=" + currentActiveTab;
    } else if (currentActiveTab != undefined) {
        var form_data = 'active_tab=' + currentActiveTab;

    } else {
        var form_data = $('#' + form).serialize();

    }

    $.ajaxSetup({

        headers: {
            'X-CSRF-TOKEN': _token
        }
    });
    form_data = form_data + '&_token=' + _token;
    $.ajax({
            url: url,
            type: "get",
            datatype: "html",
            data: form_data,
            async: false,
            cache: false,
            // beforeSend: function()
            // {
            //     you can show your loader
            // }
        })
        .done(function(data) {

                $("#table_filter_view").empty();
                $('#table_filter_view').append(data.html);


            location.hash = page;
        })
        .fail(function(jqXHR, ajaxOptions, thrownError) {
            alert('No response from server');
        });
}

/**
 * common submit form button click
 */
$(document).on("click", ".downloadBtn", function() {
    $this = $(this);
    var $type = $this.attr("data-type");
    $from = $this.closest("form");
    var url = $this.attr("data-url");
    callback = "download_from_callback";
    data = $from.serialize()+ "&type="+$type;
    window.open( url+"/?"+data, "_blank");
    return false;
});




// $(".view_notification").on("click", function(){
//         var title = $(this).attr("data-title");
//         var message = $(this).attr("data-message");
//         var company = $(this).attr("data-company");
//         $('#yourModal').modal('show');
//         $('#not_title').text(title);
//         $('#not_message').text(message);
//         $('#not_company_name').text(company);
// });



/**
 * Ajax call for lang
 */

$(document).on("click",".setlang",function(){
    var $lang = $(this).attr("data-lang");
    if($lang == "en" || $lang  == "ar"){
        var serveurl = APP_URL+"/setlang/"+$lang;
        $.get( serveurl, function(data) {
			if( data.success == true ){
                location.reload(); 
            }
        });
    }
});