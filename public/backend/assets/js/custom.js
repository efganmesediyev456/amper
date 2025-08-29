$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
});


$('body').on("submit", "#saveForm", function (e) {
    e.preventDefault();
    let formdata = new FormData(this);
    showLoading();
    $.ajax({
        url: $(this).attr('action'),
        type: "POST",
        data: formdata,
        processData: false,
        contentType: false,
        success: function (response) {
            if (response.status === 'success') {
                hideLoading()
                $("#roadPassWithDrawModal").modal("hide");
                Swal.fire({
                    icon: 'success',
                    text: response.message,
                    timer: 1200,
                    showConfirmButton: false,
                    willClose: function (e) {
                        console.log(response)
                        hideLoading();
                        window.location.href = response.route;

                    }
                });
            }
        },
        error: function (xhr, status, error) {
            hideLoading();

            if (xhr.status === 422) {
                let errors = xhr.responseJSON.errors;
                let errorMessages = '';
                for (let key in errors) {
                    errorMessages += errors[key].join('<br/>') + '<br>';
                }
                console.log(errorMessages)
                Swal.fire({
                    icon: 'error',
                    html: errorMessages,
                    showConfirmButton: true,
                    confirmButtonText: "Tamam"
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Server Error',
                    text: xhr.responseJSON.message || 'Something went wrong!',
                    showConfirmButton: false,
                });
            }
        }
    });
});



function showLoading() {
    $(".loading").css({
        'display': "flex"
    });
}

function hideLoading() {
    $(".loading").css({
        'display': "none"
    });
}



function ajaxSend(e, data, url, reload = false, swalMessage = true) {
    return new Promise(function (resolve, reject) {
        e.preventDefault();
        let formdata = new FormData();
        for (let a in data) {
            for (let i in data[a]) {
                formdata.append(i, data[a][i]);
            }
        }
        showLoading();
        $.ajax({
            url: url,
            type: "POST",
            data: formdata,
            processData: false,
            contentType: false,
            success: function (response) {
                hideLoading();
                console.log(response)
                if (response.status === 'success') {
                    if (swalMessage) {
                        Swal.fire({
                            icon: 'success',
                            text: response.message,
                            timer: 1200,
                            showConfirmButton: false,
                            willClose: function () {
                                if (reload) {
                                    window.location.reload();
                                }
                            }
                        });
                    }
                    resolve({ message: response.message, reload: reload, response:response });
                } else {
                    reject(response);
                }
            },
            error: function (xhr, status, error) {
                hideLoading();
                reject(xhr);
                showErrorMessages(xhr);
            }
        });
    });
}


function submitFormAjax(e, reload = false, url = null, noform = false) {
    var form;

    if(noform){
         form = e;
    }else{
        e.preventDefault();
        form = e.target;
    }

    let formdata = new FormData(form);
    showLoading();
    $.ajax({
        url: $(form).attr('action') ,
        type: "POST",
        data: formdata,
        processData: false,
        contentType: false,
        success: function (response) {
            if (response.status === 'success') {
                hideLoading()
                Swal.fire({
                    icon: 'success',
                    text: response.message,
                    timer: 1200,
                    showConfirmButton: false,
                    willClose: function () {
                        if (reload && url == null) {
                            window.location.reload()
                        }else if(reload && url ) {
                            window.location.href = url;
                        }
                    }
                });
            }
        },
        error: function (xhr, status, error) {
            showErrorMessages(xhr);
        }
    });
}


function showErrorMessages(xhr) {
    hideLoading();
    if (xhr.status === 422) {
        let errors = xhr.responseJSON.errors;
        let errorMessages = '';
        for (let key in errors) {
            errorMessages += errors[key].join('<br/>') + '<br>';
        }
        console.log(errorMessages)
        Swal.fire({
            icon: 'error',
            html: errorMessages,
            showConfirmButton: true,
            confirmButtonText: "Tamam"
        });
    } else {
        Swal.fire({
            icon: 'error',
            text: xhr.responseJSON.message || 'Something went wrong!',
            showConfirmButton: true,
            confirmButtonText: "Tamam"

        });
    }
}


$('body').on("submit", "#loginForm", function (e) {
    submitFormAjax(e, true, $(this).attr('data-reload'));
});



$('.tagsview').each(function() {
    new Tagify(this);
});





// $(document).on('click', '.status-toggle', function(e) {
//     $('.dt-scroll-body').css( "overflow", "inherit" );
// });
//
//
// $(document).on('click', function(e) {
//     if (!$(e.target).closest('.status-dropdown').length) {
//         const openMenus = $('.status-menu.show');
//         if (openMenus.length > 0) {
//             openMenus.removeClass('show');
//         }
//
//         $('.dt-scroll-body').css( "overflow", "auto" );
//
//     }
// });
