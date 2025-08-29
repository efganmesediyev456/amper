urlkey='pages';
urlstore='/'+admin_prefix+'/'+urlkey
urledit='/'+admin_prefix+'/'+urlkey+'/'
urlupdate='/'+admin_prefix+'/'+urlkey+'/'

$("#add_form").submit(function(e) {
    e.preventDefault();
    var fd = new FormData(this);
    $("#add_btn").addClass('disabled').text('Loading...');

    fd = ckeditorGetData('description-create', fd, 'description')

    fd = ckeditorGetData('mobile-create', fd, 'mobile')

    $.ajax({
        url: urlstore,
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




$("#edit_form").submit(function(e) {
    e.preventDefault();
    var fd = new FormData(this);
    $("#add_btn").addClass('disabled').text('Loading...');
    fd = ckeditorGetData('description-edit', fd, 'description')
    fd = ckeditorGetData('mobile-edit', fd, 'mobile')
    fd.append('_method','put')
    var id=$(this).data('id')
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
                $("#add_form")[0].reset();
                $('.select2').trigger('change')

                $(".buttonlar button").remove();
                $("#add_btn").removeClass('disabled').text('Yadda Saxla');
                $(".choose_model").addClass('d-none')

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
