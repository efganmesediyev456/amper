$(document).ready(function() {


    urlkey='formcontact';
    urlstore='/'+admin_prefix+'/'+urlkey
    urledit='/'+admin_prefix+'/'+urlkey+'/'
    urlupdate='/'+admin_prefix+'/'+urlkey+'/'

    //show
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

                $("#name-show").val(json.name)
                $("#phone-show").val(json.phone)
                $("#email-show").val(json.email)
                $("#topic-show").val(json.topicview)
                $("#company-show").val(json.company)
                $("#message-show").text(json.message)
                $("#date-show").val(json.create)


            },
            error:function (e){
                console.log()
            }
        })
    })






    $('#editmodal .submit-btn').on('click', function(event) {
        event.preventDefault();
        let formdata=new FormData(document.querySelector("#editmodal form"));
        formdata.append("_token",'{{csrf_token()}}')
        var id=$("#editmodal .submit-btn").attr('data-id');
        formdata.append('id',id)
        formdata.append('_method','PUT')
        formdata=ckeditorTranslate('text-edit',json,'text')

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

                // Swal.fire({
                //     icon:"success",
                //     text:"Uğurla dəyişdirildi",
                //     timer:1200,
                //     showConfirmButton:false,
                //     willClose:function (e){
                //     }
                // })

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





});
