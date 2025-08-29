urlkey='languages';
urlstore='/'+admin_prefix+'/'+urlkey
urledit='/'+admin_prefix+'/'+urlkey+'/'
urlupdate='/'+admin_prefix+'/'+urlkey+'/'


$(".create").click(function (){
    $("#createmodal").modal('show');
})
$(".createsave").click(function (){
    var name=$('#createmodal [name="name"]').val();
    var code=$('#createmodal [name="code"]').val();
    var country_name=$('#createmodal [name="country_name"]').val();
    $.ajax({
        url:urlstore,
        data:{
            'name':name,
            'code':code,
            'country_name':country_name
        },
        type:'POST',
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
            $(".border-danger").remove();
            for(a in e.responseJSON.errors){
                $("[name='"+a+"']").addClass('border border-danger')
                $("[name='"+a+"']").after('<p class="text-danger my-1">'+e.responseJSON.errors[a]+'</p>');
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
            'id':id,
    },
    type:'GET',
        success:function (e){

        $(".hidden").remove();
        $("#updatemodal form").append(`<input class="hidden" type="hidden" value="${e.item.id}" name="id">`);
        $('#updatemodal [name="name"]').val(e.item.title)
        $('#updatemodal [name="code"]').val(e.item.locale)
        $('#updatemodal [name="country_name"]').val(e.item.country_name)
    }
})

})

$('.updatesave').click(function (e){

    var id=$('.hidden').val();
    var name=$('#updatemodal [name="name"]').val();
    var code=$('#updatemodal [name="code"]').val();
    var country_name=$('#updatemodal [name="country_name"]').val();

    $.ajax({
        url:urlupdate+id,
        data:{
            'name':name,
            'code':code,
            'country_name':country_name,
            '_method':'PUT'
        },
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
            $(".border-danger").remove();
            for(a in e.responseJSON.errors){
                $("[name='"+a+"']").addClass('border border-danger')
                $("[name='"+a+"']").after('<p class="text-danger my-1">'+e.responseJSON.errors[a]+'</p>');
            }
        }
    })
})
