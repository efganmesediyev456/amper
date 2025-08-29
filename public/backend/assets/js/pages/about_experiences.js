urlkey='about_experiences';
urledit='/'+admin_prefix+'/'+urlkey+'/save'



$("#add_form").submit(function(e) {
    e.preventDefault();
    const fd = new FormData(this);
    var id   = $("#form_id").val();
    $("#add_btn").addClass('disabled').text('Loading...');
    if (id && !urledit.endsWith('/' + id)) {
        urledit = urledit + "/" + id;
    }
    $.ajax({
        url: urledit,
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
                    willClose:function (e){
                        window.location.reload();
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
