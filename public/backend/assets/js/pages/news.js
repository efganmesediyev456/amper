$(document).ready(function() {


    urlkey='news';
    urlstore='/'+admin_prefix+'/'+urlkey
    urledit='/'+admin_prefix+'/'+urlkey+'/'
    urlupdate='/'+admin_prefix+'/'+urlkey+'/'

    $('#create_form').on('submit', function(event) {
        event.preventDefault();
        let formdata=new FormData(this);
        formdata=ckeditorGetData('text-create',formdata,'text');
        _this=this;
        $(_this).find('button').text('loading...').addClass('disabled')
        $.ajax({
            url:urlstore,
            type:"post",
            data:formdata,
            processData:false,
            contentType:false,
            success:function (e){
                $('.form-group input').removeClass('error');
                $('.error-message').remove();
                modalcreate.modal('hide');

                $(_this).find('button').text('Yadda saxla').removeClass('disabled')
                resetForm();
                Swal.fire({
                    icon:"success",
                    text:"Uğurla yaradıldı",
                    timer:1200,
                    showConfirmButton:false,
                    willClose:function (e){
                    }
                })
            },
            error:function (e){

                $(_this).find('button').text('Yadda saxla').removeClass('disabled')

                if (e.status === 422) {

                    $.each(e.responseJSON.errors, function (key, value) {
                        toastr.error(value)
                    });

                    var errors = e.responseJSON.errors;
                    $('.form-group input').removeClass('error');
                    $('.error-message').remove();
                    for (var key in errors) {
                        if (errors.hasOwnProperty(key)) {
                            var inputNameParts = key.split('.');
                            if(inputNameParts.length > 1){
                                var inputName = inputNameParts[0];
                                var inputLang = inputNameParts[1];
                                var input = $('[name="' + inputName + '[' + inputLang + ']"]');
                                input.addClass('error');
                                input.after('<div class="error-message">' + errors[key][0] + '</div>');
                            }else{
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







    $('#edit_form').on('submit', function(event) {
        event.preventDefault();
        let formdata=new FormData(this);
        var id=$(this).find('[name="id"]').val();
        formdata.append('id',id)
        formdata.append('_method','PUT')
        formdata=ckeditorGetData('text-edit',formdata,'text');
        _this=this;
        $(_this).find('button').text('loading...').addClass('disabled')

        $.ajax({
            url:urlupdate+id,
            type:"post",
            data:formdata,
            processData:false,
            contentType:false,
            success:function (e){
                $('.form-group input').removeClass('error');
                $('.error-message').remove();
                modaledit.modal("hide");

                $(_this).find('button').text('Yadda saxla').removeClass('disabled')

                Swal.fire({
                    icon:"success",
                    text:"Uğurla dəyişdirildi",
                    timer:1200,
                    showConfirmButton:false,
                    willClose:function (e){
                        window.location.reload()
                    }
                })

            },
            error:function (e){
                $(_this).find('button').text('Yadda saxla').removeClass('disabled')


                if (e.status === 422) {
                    $.each(e.responseJSON.errors, function (key, value) {
                        toastr.error(value)
                    });

                    var errors = e.responseJSON.errors;
                    $('.form-group input').removeClass('error');
                    $('.error-message').remove();
                    for (var key in errors) {
                        if (errors.hasOwnProperty(key)) {
                            var inputNameParts = key.split('.');
                            if(inputNameParts.length > 1){
                                var inputName = inputNameParts[0];
                                var inputLang = inputNameParts[1];
                                var input = $('[name="' + inputName + '[' + inputLang + ']"]');
                                input.addClass('error');
                                input.after('<div class="error-message">' + errors[key][0] + '</div>');
                            }else{
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
