urlkey = 'values';
urlstore = '/' + admin_prefix + '/' + urlkey
urledit = '/' + admin_prefix + '/' + urlkey + '/'
urlupdate = '/' + admin_prefix + '/' + urlkey + '/'


$('#createmodal .submit-btn').on('click', function(event) {
    event.preventDefault();
    let formdata=new FormData(document.querySelector("#createmodal form"));


    $.ajax({
        url: urlstore,
        type:"post",
        data:formdata,
        processData:false,
        contentType:false,
        success:function (e){
            $('.form-group input').removeClass('error');
            $('.error-message').remove();
            modalcreate.modal('hide');
            window.dTable.draw()
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


            if (e.status === 422) {
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

//editmodal

$("body").on("click",'.edit', function (e){
    e.preventDefault()
    modaledit.modal('show');
    formdata=new FormData();
    var id=$(this).attr('data-id')
    formdata.append('id',id)

    $.ajax({
        url:urledit+id,
        type:"get",
        data:formdata,
        processData:false,
        contentType:false,
        success:function (json){

            translate("#title-edit","title", json);
            translate("#text-edit","text", json);

            $("#image-edit").siblings('img').remove();
            $("#image-edit").after('<img width="300px" class="my-3" src="/storage/values/'+json.image+'">')



            $("#editmodal .submit-btn").attr('data-id', json.id)

        },
        error:function (e){
            console.log()
        }
    })
})

$('#editmodal .submit-btn').on('click', function(event) {
    event.preventDefault();
    let formdata=new FormData(document.querySelector("#editmodal form"));
    var id=$("#editmodal .submit-btn").attr('data-id');
    formdata.append('id',id)
    formdata.append('_method','PUT')


    console.log(id)


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

            window.dTable.draw()

            Swal.fire({
                icon:"success",
                text:"Uğurla dəyişdirildi",
                timer:1200,
                showConfirmButton:false,
                willClose:function (e){
                }
            })

        },
        error:function (e){


            if (e.status === 422) {
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
