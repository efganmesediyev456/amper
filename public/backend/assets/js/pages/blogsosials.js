$(function () {
    // URL'ler ve form seçimleri
    const urlKey = 'blogsosials';
    const urlStore = `/${admin_prefix}/${urlKey}`;
    const urlEdit = `/${admin_prefix}/${urlKey}/`;
    const urlUpdate = `/${admin_prefix}/${urlKey}/`;

    // Yeni blog sosyal formunu gönder
    $("#add_form").submit(function(e) {
        e.preventDefault();
        const fd = new FormData(this);
        $("#add_btn").addClass('disabled').text('Loading...');

        $.ajax({
            url: urlStore,
            method: 'post',
            data: fd,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                if (response.status === 200) {
                    Swal.fire({
                        title: '',
                        text: response.message,
                        icon: 'success',
                        timer: 1200,
                        showConfirmButton: false
                    });
                    $("#add_form")[0].reset();
                    $('.select2').trigger('change');
                    $(".buttonlar button").remove();
                    $("#add_btn").removeClass('disabled').text('Yadda Saxla');
                    $(".choose_model").addClass('d-none');
                }
                $("#addEmployeeModal").modal('hide');
            },
            error: function(json) {
                $("#add_btn").removeClass('disabled').text('Yadda Saxla');
                if (json.status === 422) {
                    const errors = json.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        toastr.error(value);
                    });
                } else {
                    toastr.error('Incorrect credentials. Please try again.');
                }
            }
        });
    });

    // Mevcut blog sosyal formunu güncelle
    $("#edit_form").submit(function(e) {
        e.preventDefault();
        const fd = new FormData(this);
        $("#add_btn").addClass('disabled').text('Loading...');
        fd.append('_method', 'PUT');
        const id = $(this).data('id');
        $.ajax({
            url: urlEdit + id,
            method: 'post',
            data: fd,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                if (response.status === 200) {
                    Swal.fire({
                        title: '',
                        text: response.message,
                        icon: 'success',
                        timer: 1200,
                        showConfirmButton: false,
                        willClose: function() {
                            window.location.reload();
                        }
                    });
                    $('.select2').trigger('change');
                    $(".buttonlar button").remove();
                    $("#add_btn").removeClass('disabled').text('Yadda Saxla');
                    $(".choose_model").addClass('d-none');
                }
                $("#addEmployeeModal").modal('hide');
            },
            error: function(json) {
                $("#add_btn").removeClass('disabled').text('Yadda Saxla');
                if (json.status === 422) {
                    const errors = json.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        toastr.error(value);
                    });
                } else {
                    toastr.error('Incorrect credentials. Please try again.');
                }
            }
        });
    });
});
