$(document).ready(function () {

    //create modal
    urlkey='product-abouts';
    urlstore='/'+admin_prefix+'/'+urlkey
    urledit='/'+admin_prefix+'/'+urlkey+'/'
    urlupdate='/'+admin_prefix+'/'+urlkey+'/'




    $('#createmodal #addForm').on('submit', function (event) {
        event.preventDefault();
        let formdata = new FormData(document.querySelector("#addForm"));
        $.ajax({
            url: urlstore,
            type: "post",
            data: formdata,
            processData: false,
            contentType: false,
            success: function (e) {
                $('.form-group input').removeClass('error');
                $('.error-message').remove();
                modalcreate.fadeOut();
                Swal.fire({
                    icon: "success",
                    text: "Uğurla yaradıldı",
                    timer: 1200,
                    showConfirmButton: false,
                    willClose: function (e) {
                        window.location.reload()
                    }
                })

            },
            error: function (e) {


                if (e.status === 422) {
                    var errors = e.responseJSON.errors;
                    $('.form-group input').removeClass('error');
                    $('.error-message').remove();
                    for (var key in errors) {
                        if (errors.hasOwnProperty(key)) {
                            var inputNameParts = key.split('.');
                            if (inputNameParts.length > 1) {
                                var inputName = inputNameParts[0];
                                var inputLang = inputNameParts[1];
                                var input = $('[name="' + inputName + '[' + inputLang + ']"]');
                                input.addClass('error');
                                input.after('<div class="error-message">' + errors[key][0] + '</div>');
                            } else {
                                var input = $('[name="' + key + '"]');
                                input.addClass('error');
                                input.after('<div class="error-message">' + errors[key][0] + '</div>');
                            }

                        }
                    }
                } else {

                }

            }
        })
    });


    //editmodal
    btnedit.on('click', function (e) {
        e.preventDefault()
        modaledit.modal('show');
        formdata = new FormData();
        var id = $(this).attr('data-id')
        formdata.append('id', id)

        $.ajax({
            url: urledit+id,
            type: "get",
            data: formdata,
            processData: false,
            contentType: false,
            success: function (json) {

                translate("#title-edit", "title", json);
                translate("#subtitle-edit", "subtitle", json);
                translate("#url-edit", "url", json);
                withoutTranslate("#date-edit", json.date)
                $("#image-edit").siblings('img').remove();
                $("#image-edit").after('<img width="300px" class="my-3" src="/storage/productAbouts/' + json.image + '">')

                $("#editmodal .submit-btn").attr('data-id', json.id)

            },
            error: function (e) {
                console.log()
            }
        })
    });


    //update modal

    $('#editmodal .submit-btn').on('click', function (event) {
        event.preventDefault();
        let formdata = new FormData(document.querySelector("#editForm"));
        var id = $("#editmodal .submit-btn").attr('data-id');
        formdata.append('id', id)
        formdata.append('_method', 'PUT')


        $.ajax({
            url:urlupdate + id,
            type: "post",
            data: formdata,
            processData: false,
            contentType: false,
            success: function (e) {
                $('.form-group input').removeClass('error');
                $('.error-message').remove();
                modaledit.modal("hide");


                Swal.fire({
                    icon: "success",
                    text: "Uğurla dəyişdirildi",
                    timer: 1200,
                    showConfirmButton: false,
                    willClose: function (e) {
                        window.location.reload()
                    }
                })

            },
            error: function (e) {


                if (e.status === 422) {
                    var errors = e.responseJSON.errors;
                    $('.form-group input').removeClass('error');
                    $('.error-message').remove();
                    for (var key in errors) {
                        if (errors.hasOwnProperty(key)) {
                            var inputNameParts = key.split('.');
                            if (inputNameParts.length > 1) {
                                var inputName = inputNameParts[0];
                                var inputLang = inputNameParts[1];
                                var input = $('[name="' + inputName + '[' + inputLang + ']"]');
                                input.addClass('error');
                                input.after('<div class="error-message">' + errors[key][0] + '</div>');
                            } else {
                                var input = $('[name="' + key + '"]');
                                input.addClass('error');
                                input.after('<div class="error-message">' + errors[key][0] + '</div>');
                            }

                        }
                    }
                } else {

                }

            }
        })
    });


});
