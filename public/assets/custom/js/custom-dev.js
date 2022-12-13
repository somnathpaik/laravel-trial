var route = $('#table-data').attr('data-route');
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

$(document).on("submit", ".ajaxPostFormSave", function (e) {
    e.preventDefault();
    $('.load-message').remove();
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
                if(response.is_replace){
                    $('#userid-' + response.uuid).replaceWith(response.data);
                }else{
                    fetchTableData(route, 1);
                }                
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

$(document).on("click", ".delete-client", function (e) {
    Swal.fire({
        title: 'Are you sure to delete this ?',
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
            var uuid = $(this).attr('data-uuid');
            $.ajax({
                url: $(this).attr('data-route'),
                type: 'DELETE',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr("content"),
                },
                success: function (response) {
                    if (response.status === true) {
                        
                        $('#userid-' + uuid).remove();
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

function fetchTableData(route, page) {
    $.ajax({
        url: route + "?page=" + page,
        type: 'GET',
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

function checkTableData(){
    if ($('.table-data').length) {
        $('.no-data-table-row').remove();
    }else{
        $('.list').append('<tr class="no-data-table-row"><td colspan="100%" class="text-center">No data found</td></tr>');
    }
}

$(document).ready(function () {
    fetchTableData(route, 1);

    $("input[name='table_search']").autocomplete({
        source: function (request, response) {
            $.ajax({
                url: $("input[name='table_search']").attr('data-route'),
                type: 'GET',
                dataType: "json",
                data: {
                    search_keyword: request.term
                },
                success: function (data) {
                    if (data.status === true) {
                        response(data.data);
                    } else {
                        toastr.error(data.message);
                        return false;
                    }

                }
            });
        },
        select: function (event, ui) {
            $("input[name='table_search']").val(ui.item.value);
            return false;
        }
    });

    $('.search-data').click(function(){
        var search_data = $("input[name='table_search']").val();
        if(!search_data){
            toastr.error('Search value missing');
            return false;
        }
        $.ajax({
            url: $(this).attr('data-route'),
            type: 'GET',
            data: {search_data: search_data},
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
    });

    $('.reset-data').click(function(){
        $("input[name='table_search']").val('');
        fetchTableData(route, 1);
    });
});

$(document).on('click', '.pagination a', function (event) {
    event.preventDefault();
    var page = $(this).attr('href').split('page=')[1];
    fetchTableData(route, page);
});



