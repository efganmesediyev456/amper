urlkey = 'permissions';
urlstore = '/' + admin_prefix + '/' + urlkey
urledit = '/' + admin_prefix + '/' + urlkey + '/'
urlupdate = '/' + admin_prefix + '/' + urlkey + '/'

$(document).ready(function() {
    $("#createmodal .select2").select2({
        dropdownParent: $("#createmodal"),
        width:'100%'
    });
    $("#updatemodal .select2").select2({
        dropdownParent: $("#updatemodal"),
        width:'100%'
    });
});





$(".create").click(function (){
    $("#createmodal").modal('show');
})
$(".createsave").click(function (){




    var formdata=new FormData($("#createmodal form")[0]);



    $.ajax({
        url:urlstore,
        data:formdata,
        type:'POST',
        contentType: false,
        processData: false,
        success:function (e){
            $(".text-danger").remove();
            $("input").removeClass('border-danger border');
            $('.createform')[0].reset();
            $("#createmodal").modal('hide');
            Swal.fire({
                title: 'Successfully Created',
                icon: 'success',
                showConfirmButton: false,
                timer: 1500
            })
            window.dTable.draw()
        },
        error:function (e){
            $(".text-danger").remove();
            $(".border-danger").removeClass('border-danger border');
            for(a in e.responseJSON.errors){
                $("#createmodal input[name='"+a+"']").addClass('border border-danger')
                $("#createmodal input[name='"+a+"']").after('<p class="text-danger my-1">'+e.responseJSON.errors[a]+'</p>');
            }
        }
    })
})

//edit show

$("body").on("click",'.edit', function (){
    $("#updatemodal").modal("show");
    var id=$(this).attr('data-id');
    $.ajax({
        url:urledit + id ,
        data:{
            '_token':'{{csrf_token()}}',
            'id':id,
        },
        type:'GET',
        success:function (e){


            $(".hidden").remove();
            $("#updatemodal form").append(`<input class="hidden" type="hidden" value="${e.item.id}" name="id">`);


            $('#updatemodal input[name="name"]').val(e.item.name);
            $('#updatemodal input[name="title"]').val(e.item.title);






        }
    })

})

$('.updatesave').click(function (e){

    var id=$('.hidden').val();
    var formdata=new FormData($("#updatemodal form")[0]);
    formdata.append('id',id);
    formdata.append('_method','PUT');

    $.ajax({
        url:urlupdate+id,
        data:formdata,
        contentType: false,
        processData: false,
        type:'POST',
        success:function (e){
            $(".text-danger").remove();
            $("input").removeClass('border-danger border');
            $('.updateform')[0].reset();
            $("#updatemodal").modal('hide');
            Swal.fire({
                title: 'Successfully Updated',
                icon: 'success',
                showConfirmButton: false,
                timer: 1500
            })
            window.dTable.draw()
        },
        error:function (e){
            $(".text-danger").remove();
            $(".border-danger").removeClass('border-danger border');
            for(a in e.responseJSON.errors){
                var name=a.split('.')[0]+'['+a.split('.')[1]+']';
                $("[name='"+name+"']").addClass('border border-danger')
                $("[name='"+name+"']").after('<p class="text-danger my-1">'+e.responseJSON.errors[a]+'</p>');
            }
        }
    })
})
