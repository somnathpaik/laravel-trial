
$(document).on("click", ".open-modal", function (e) {
    $.ajax({
        url: $(this).attr('data-route'),
        type: 'GET',
        dataType: 'json',
        success: function (response) {
            if (response.status === true) {
                $('.ajax-form-render').html(response.data.form);
                $('.ajax-form-title').html(response.data.form_title);
                $('.modal-form').modal('show');
            }
        }
    });
});

function validateName(name) {
    var nameReg = /^[ A-Za-z_[\](){}@]*$/;
    return nameReg.test(name);
}

function validateEmail(email) {
    var emailReg = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return emailReg.test(email);
}

function validateMobile(mobile) {
    var emailMobile = /\+\d{2}[ ]?\d{3}[ ]?\d{3}[ ]?\d{4}\b/;
    return emailMobile.test(mobile);
}

$(document).on("keyup", "#mobile", function (e) {
    
    var phoneNumber = e.target.value.replace(/[^/+/\d]/g, "");
    const phoneNumberLength = phoneNumber.length;
    var finalPhoneNumber = "";
    if (phoneNumberLength < 4) {
        finalPhoneNumber += phoneNumber;
    }
    if (phoneNumberLength >= 4 && phoneNumberLength < 7) {
        finalPhoneNumber += `${phoneNumber.slice(0, 3)} ${phoneNumber.slice(3)}`;
    }
    if(phoneNumberLength >= 7 && phoneNumberLength < 10) {
        finalPhoneNumber += `${phoneNumber.slice(0, 3)} ${phoneNumber.slice(3, 6)} ${phoneNumber.slice(6)}`
    }
    if(phoneNumberLength >= 10 && phoneNumberLength <= 13) {
        finalPhoneNumber += `${phoneNumber.slice(0, 3)} ${phoneNumber.slice(3, 6)} ${phoneNumber.slice(6, 9)} ${phoneNumber.slice(9)}`
    }
    $(this).val(finalPhoneNumber);
});

function errorMessage(message) {
    return '<div class="alert alert-danger alert-block load-message"><button type="button" class="close" data-dismiss="alert">x</button><strong>' + message + '</strong></div>';
}

function clientFormValidation() {
    var name = $('#name').val();
    if (!name) {
        $('#name').after(errorMessage('Name field is required'));
        return false;
    }
    if (!validateName(name)) {
        $('#name').after(errorMessage('Please input proper name'));
        return false;
    }
    var email = $('#email').val();
    if (!email) {
        $('#email').after(errorMessage('Email field is required'));
        return false;
    }
    if (!validateEmail(email)) {
        $('#email').after(errorMessage('Please input proper mail'));
        return false;
    }
    var mobile = $('#mobile').val();
    if (!mobile) {
        $('#mobile').after(errorMessage('Mobile field is required'));
        return false;
    }
    if (!validateMobile(mobile)) {
        $('#mobile').after(errorMessage('Please input proper mobile number'));
        return false;
    }
    return true;
}

var route = $('.page.active').attr('data-route');

$(document).on("submit", ".ajaxPostFormSave", function (e) {
    e.preventDefault();
    $('.load-message').remove();
    if (!clientFormValidation()) {
        return false;
    }
    $.ajax({
        url: $(this).attr('action'),
        type: "POST",
        data: new FormData(this),
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr("content"),
        },
        beforeSend: function () {
            $('.ajaxPostFormSave').before('<div class="text-warning load-message">Please wait...</div>');
        },
        success: function (response) {
            if (response.status === true) {
                $('.load-message').remove();
                $('.ajaxPostFormSave')[0].reset();
                fetchTableData(route, 1);
                checkTableData();
                $('.modal-form').modal('hide');
                toastr.success(response.message);
            } else {
                $('.load-message').remove();
                var output = '';
                $.each(response.message, function (key, value) {
                    var html = '<div class="alert alert-danger alert-block load-message"><button type="button" class="close" data-dismiss="alert">x</button><strong>' + value + '</strong></div>';
                    output += html;
                });
                $('.ajaxPostFormSave').before(output);
                return false;
            }
        }
    });
});

$(document).on("click", ".prompt", function (e) {
    var method = $(this).attr('data-method');
    Swal.fire({
        title: $(this).attr('data-prompt-text'),
        icon: 'warning',
        reverseButtons: true,
        confirmButtonColor: '#ff0000',
        confirmButtonText: 'Yes',
        showCancelButton: true,
        focusCancel: true,
        showCloseButton: true,
        cancelButtonColor: '#4caf50',
        cancelButtonText: 'No',
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: $(this).attr('data-route'),
                type: method,
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr("content"),
                },
                success: function (response) {
                    if (response.status === true) {
                        fetchTableData(route, 1);
                        checkTableData();
                        toastr.success(response.message);
                    } else {
                        toastr.error(response.message);
                        return false;
                    }
                }
            });
        }
    });



});

function checkTableData() {
    if ($('.table-data').length) {
        $('.no-data-table-row').remove();
    } else {
        $('.list').append('<tr class="no-data-table-row"><td colspan="100%" class="text-center">No data found</td></tr>');
    }
}



$(document).on('click', '.page', function (event) {
    event.preventDefault();
    if ($(this).attr("id") == 'dashboard') {
        $('#table-header').show();
    } else {
        $('#table-header').hide();
    }
    $('.page').removeClass('active');
    $(this).addClass('active');
    route = $(this).attr('data-route');
    fetchTableData(route, 1);
});

function fetchTableData(route, page) {
    $.ajax({
        url: route + "?page=" + page,
        type: 'GET',
        dataType: 'json',
        success: function (response) {
            if (response.status === true) {
                $('#page-title').html(response.data.page_title);
                $('#table-data').html(response.data.page_data);
            } else {
                toastr.error(response.message);
                return false;
            }

        }
    });
}

function removeSearch() {
    if ($('.recent-search').length) {
        $('.recent-search').remove();
    }
}

$(document).ready(function () {
    fetchTableData(route, 1);

    $("input[name='table_search']").keyup(function (e) {
        var search_keyword = $(this).val();
        var selector = $("input[name='table_search']");
        removeSearch();
        if (search_keyword) {
            $.ajax({
                url: $(this).attr('data-route'),
                type: 'GET',
                dataType: "json",
                data: {
                    search_keyword: search_keyword
                },
                success: function (response) {
                    if (response.status === true) {
                        selector.after(response.data);
                    } else {
                        toastr.error(response.message);
                        return false;
                    }
                }
            });
        }
    });

    $("input[name='table_search']").click(function () {
        removeSearch();
        var selector = $("input[name='table_search']");
        if (!selector.val()) {
            $.ajax({
                url: selector.attr('data-search-route'),
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    if (response.status === true) {
                        selector.after(response.data);
                    } else {
                        toastr.error(response.message);
                        return false;
                    }

                }
            });
        }
    });

    $('.search-data').click(function () {
        var search_data = $("input[name='table_search']").val();
        if (!search_data) {
            toastr.error('Search value missing');
            return false;
        }
        getSearchResult(search_data);
    });

    $('.reset-data').click(function () {
        $("input[name='table_search']").val('');
        fetchTableData(route, 1);
    });
});

function getSearchResult(search_data) {
    $.ajax({
        url: $('.search-data').attr('data-route'),
        type: 'GET',
        data: { search_data: search_data },
        dataType: 'json',
        success: function (response) {
            if (response.status === true) {
                $('#table-data').html(response.data);
            } else {
                toastr.error(response.message);
                return false;
            }

        }
    });
}

$(document).on('click', '.recent-search-data', function (e) {
    e.preventDefault();
    var search_data = $(this).attr('data-value');
    $("input[name='table_search']").val(search_data);
    removeSearch();
    getSearchResult(search_data);
});

$(document).on('click', '.pagination a', function (e) {
    e.preventDefault();
    var page = $(this).attr('href').split('page=')[1];
    fetchTableData(route, page);
});

$(document).on('click', '.dots_btn', function (e) {
    var selector = $(this).attr('data-id');
    $("#" + selector).slideToggle();
});

var page = 1;
$(document).on('scroll', '#table-ajax', function () {
    //if ($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight()) {
    if ($(this).scrollTop() + $(this).height() >= $(this)[0].scrollHeight()) {
        page++;
        loadMoreData(page);
    }
});
function loadMoreData(page) {
    $.ajax({
        url: '?load_more=true&page=' + page,
        type: 'GET',
        data: { search_data: search_data },
        dataType: 'json',
        beforeSend: function () {
            $('.ajax-load').show();
        },
        success: function (response) {
            if (response.status === true) {
                $("#table-ajax").append(response.page_data);
            } else {
                toastr.error(response.message);
                return false;
            }    
        }
    });
}



