$(document).ready(function () {





    //4uncu prelodader ucun


    $(".videos").on("click", function() {
        var allVideos = $(".videos");
        var videoIndex = allVideos.index(this);

        if(videoIndex + 1==4){
            $("#preview-edit").parents('.form-group').hide()
            $("#url-edit").parents('.form-group').hide()
            $("[name='autoplay']").parents('.form-group').hide()
            $(".video_title").text('Video yada gif')
        }else{
            $("#preview-edit").parents('.form-group').show()
            $("#url-edit").parents('.form-group').show()
            $("[name='autoplay']").parents('.form-group').show()
            $(".video_title").text('Video')
        }
    });




    //editmodal

    var modaledit = $('#editmodal');
    var btn = $('.add-edit-modal');
    var span = $('.close');

    btn.on('click', function (e) {
        e.preventDefault()
        modaledit.fadeIn();
        formdata = new FormData();
        var id = $(this).attr('data-id')
        formdata.append('id', id)

        $.ajax({
            url: '/'+admin_prefix+'/videos/'+id,
            type: "get",
            data: formdata,
            processData: false,
            contentType: false,
            success: function (json) {

                console.log(json)

                translate("#title-edit", "title", json);
                withoutTranslate("#url-edit", json.url);
                withoutTranslate("#order-edit", json.order);
                if (json.status)
                    $("#editmodal #status-edit").prop("checked", true)
                else
                    $("#editmodal #status-edit").prop("checked", false)

                if (json.autoplay)
                    $("#editmodal #autoplay-edit").prop("checked", true)
                else
                    $("#editmodal #autoplay-edit").prop("checked", false)

                $("#editmodal #preview-edit").siblings('a').remove();
                if(json.preview){
                    $("#editmodal #preview-edit").after('<a target="_blank" href="/storage/preview/'+json.preview+'"><img class="mt-2" width="100px" src="/storage/preview/'+json.preview+'"></a>')
                }

                $("#editmodal .submit-btn").attr('data-id', json.id)

            },
            error: function (e) {
                console.log()
            }
        })
    });

    span.on('click', function () {
        modaledit.fadeOut();
    });

    $(window).on('click', function (event) {
        if ($(event.target).is(modaledit)) {
            modaledit.fadeOut();
        }
    });


    $('#editmodal .tablink').on('click', function () {
        $('#editmodal .tablink').removeClass('active');
        $(this).addClass('active');
        var tabId = $(this).attr('onclick').split("'")[1];
        $('#editmodal .tabcontent').removeClass('active');
        $('#editmodal #' + tabId).addClass('active');
    });

    $('#editmodal .tablink:first').addClass('active');
    $('#editmodal .tabcontent:first').addClass('active');


    $('#editmodal #editForm').on('submit', function (event) {
        event.preventDefault();
        let formdata = new FormData(document.querySelector("#editForm"));
        var id = $("#editmodal .submit-btn").attr('data-id');
        formdata.append('id', id)
        formdata.append('_method', 'PUT')

        $.ajax({
            url: '/'+admin_prefix+"/videos/" + id,
            type: "post",
            data: formdata,
            processData: false,
            contentType: false,
            success: function (e) {
                $('.form-group input').removeClass('error');
                $('.error-message').remove();
                modaledit.fadeOut();


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
