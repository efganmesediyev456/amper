
urlkey = 'products';
urlstore = '/' + admin_prefix + '/' + urlkey
urledit = '/' + admin_prefix + '/' + urlkey + '/'
urlupdate = '/' + admin_prefix + '/' + urlkey + '/'
urlmodel='/'+admin_prefix+'/'+urlkey+'/'+'models'

$('[name="marka_id"]').select2();
$('[name="model_id"]').select2();

$('#marka_id').change(function () {
    var markaId = $(this).val();
    $.ajax({
        url: urlmodel,
        type: 'post',
        data: {marka_id: markaId},
        success: function (response) {
            var modelSelect = $('#model_id');
            modelSelect.empty();
            modelSelect.append(new Option('Model seçin', null));

            $.each(response.data, function (index, model) {
                modelSelect.append(new Option(model.title, model.id));
            });
            modelSelect.trigger('change');
        },
        error: function () {
        }
    });
});

$("#model_id").change(function (e) {
    val_id = $(this).val();


    if (val_id != 'null') {
        $(".choose_model").removeClass('d-none')

        var selectedText = $("#model_id option:selected").text();

        if ($("button input[value='" + val_id + "']").length) {
            $("button input[value='" + val_id + "']").parents('button').remove();
        } else {
            $(".buttonlar").append(`<button  class="btn btn-primary">
                                        ${selectedText}

                                            <input type="hidden" name='models[]' value="${val_id}">

                                           <i class="fa fa-trash"></i>
                                       </button> `)
        }
    }

})

$("body").on("click", '.buttonlar button', 'click', function () {
    $(this).remove();
})





$("#add_form").submit(function (e) {
    e.preventDefault();
    var fd = new FormData(this);
    $("#add_btn").addClass('disabled').text('Loading...');
    fd = ckeditorGetData('description-create', fd, 'description')

    $.ajax({
        url: urlstore,
        method: 'post',
        data: fd,
        cache: false,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function (response) {

            if (response.status == 200) {
                Swal.fire({
                    title: '',
                    text: response.message,
                    icon: 'success',
                    timer: 1200,
                    showConfirmButton: false
                })
                $("#add_form")[0].reset();
                $('.select2').trigger('change')

                $(".buttonlar button").remove();
                $("#add_btn").removeClass('disabled').text('Yadda Saxla');
                $(".choose_model").addClass('d-none')

            }


            $("#addEmployeeModal").modal('hide');
        },
        error: function (json) {
            $("#add_btn").removeClass('disabled').text('Yadda Saxla');

            if (json.status === 422) {
                var errors = json.responseJSON;
                $.each(json.responseJSON.errors, function (key, value) {
                    toastr.error(value)
                });
            } else {
                toastr.error('Incorrect credentials. Please try again.')
            }
        }
    });
});







$("#edit_form").submit(function(e) {
    e.preventDefault();
    let fd = new FormData(this);
    $("#add_btn").addClass('disabled').text('Loading...');
    fd.append('_method','PUT')
    let id=$(this).data('id')
    fd = ckeditorGetData('description-edit', fd, 'description')



    $.ajax({
        url: urlupdate+id,
        method: 'post',
        data: fd,
        cache: false,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function(response) {

            if (response.status == 200) {
                Swal.fire({
                    title: '',
                    text: response.message,
                    icon: 'success',
                    timer: 1200,
                    showConfirmButton: false,
                    willClose:function (){
                        window.location.reload()
                    }
                })
                $("#add_btn").removeClass('disabled').text('Yadda Saxla');


            }


            $("#addEmployeeModal").modal('hide');
        },
        error: function( json )
        {
            $("#add_btn").removeClass('disabled').text('Yadda Saxla');

            if(json.status === 422) {
                var errors = json.responseJSON;
                $.each(json.responseJSON.errors, function (key, value) {
                    toastr.error(value)
                });
            } else {
                toastr.error('Incorrect credentials. Please try again.')
            }
        }
    });
});
