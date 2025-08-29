

urlkey='customer-ratings';
urlstore='/'+admin_prefix+'/'+urlkey
urledit='/'+admin_prefix+'/'+urlkey+'/'
urlupdate='/'+admin_prefix+'/'+urlkey+'/'


$('#createmodal .submit-btn').on('click', function(event) {
    event.preventDefault();
    let formdata=new FormData(document.querySelector("#createmodal form"));



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

            translate("#name-edit","name", json);
            translate("#position-edit","position", json);
            translate("#content-edit","content", json);



            $("#image-edit").siblings('img').remove();
            $("#image-edit").after('<img width="300px" class="my-3" src="/storage/customerRatings/'+json.image+'">')


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
            resetForm();
            Swal.fire({
                icon:"success",
                text:"Uğurla dəyişdirildi",
                timer:1200,
                showConfirmButton:false,
                willClose:function (e){
                    window.datatable_custom.draw()
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



window.datatable_custom=$('#example').DataTable({
    dom: 'lBfrtip',
    buttons: [
        {
            extend: 'copy',
            text: 'admin.copy',
        },
        {
            extend: 'csv',
            text: 'admin.csv'

        },
        {
            extend: 'excel',
            text: 'admin.excel'

        },
        {
            extend: 'pdf',
            text: 'admin.pdf'

        },
        {
            extend: 'print',
            text: 'admin.print'

        },
        {
            extend: 'colvis',
            text: 'admin.column visibility',

            postfixButtons: ['colvisRestore'],
            postfixButtons: [{
                extend: 'colvisRestore',
                text: 'admin.restore columns',
            }]
        }
    ],
    pagingType: 'simple_numbers',
    language: {
        "url": "https://cdn.datatables.net/plug-ins/1.10.24/i18n/Azerbaijan.json",
        'paginate': {
            'previous': 'Əvvəl',
            'next': 'Sonra'
        }
    },

    "processing": true,
    "serverSide": true,
    "ajax": {
        "url": '/'+admin_prefix+'/'+'customer-ratings/getCustomerRating',
        "type": "POST",
        "data": function(d) {
            d._token = token;
        }
    },
    "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ], // Define the options
    "pageLength": 10 ,

    "columns": [
        { "data": "id" },
        { "data": "name" },
        { "data": "position" },
        { "data": "content" },
        {
            "data": "action",
            "sorting":false,
            sortable:false
        },
    ],
    "columnDefs": [
        { "width": "5%",
            "targets": -1 }
    ],
    "createdRow": function(row, data, dataIndex) {
        if (data.status ==  0) {
            $(row).css('background-color', 'rgb(244 230 230)');
        }
    },
    initComplete: function () {
        // $("#example_wrapper .dt-search").before('<div class="buttons-area"></div>')
        // datatable_custom.buttons().container().appendTo( '#example_wrapper .buttons-area:eq(0)' );
        // $("#example_wrapper .dt-length").after('<div class="centeractions d-flex justify-content-between align-items-center my-4"></div>');
        // $(".dt-search,.buttons-area, .dt-length").appendTo('.centeractions')
    }

})
