Dropzone.options.myAwesomeDropzone = {
    url: "/admin/content/images/create",
    paramName: "file", // The name that will be used to transfer the file
    maxFilesize: 10, // MB
    accept : function(file, done){
        done();
    },
    sending : function(file, xhr, formData) {
        var title = "Image Title";
        var is_main = "0";
        formData.append("images_title", title);
        formData.append("images_is_main", is_main);
        formData.append('images_ext', file.name);
    },
    success : function(file, res) {
        res = $.parseJSON(res);
        if(!res.success){
            alert(res.msg);
        }else{
            $('#images_list').append(res.new_image_row);
            $('#images_empty').remove();
            window.onbeforeunload = function(){ return "Please save the post before leaving. Images may not be properly attached."; }
            init_stuff();
        }
    },
    error : function(file, err) {
        console.log(err);
    }
};

init_stuff();

function init_stuff(){
    $('.delete-image').unbind('click');
    $('.delete-image').click(function(){
        if(!confirm("Are you sure you want to delete this image?")) return false;
        id = $(this).parents('tr').attr('data-id');
        $.post('/admin/content/images/edit/' + id, 
            {
                'delete' : true, 
                'ci_csrf_token' : $('input[name="ci_csrf_token"]').val()
            }
        )
        .success(function(res){
            res = $.parseJSON(res);
            if(res.success){
                $('#images_list tr[data-id="'+id+'"]').remove();
            }else{
                alert(res.msg);
            }
        })
        .error(function(err){
            console.log(err);
            alert("An error occurred saving your image");
        })
        return false;
    });
    
    $('.save-image').unbind('click');
    $('.save-image').click(function(){
        id = $(this).parents('tr').attr('data-id');
        if($('input[name="images_is_main_'+id+'"]').prop('checked')){
            is_main = 1;
        }else{
            is_main = 0;
        }
        $.post('/admin/content/images/edit/' + id, 
            {
                'save' : true,
                'images_title' : $('input[name="images_title_'+id+'"]').val(),
                'images_is_main' : is_main,
                'ci_csrf_token' : $('input[name="ci_csrf_token"]').val()
            }
        )
        .success(function(res){
            res = $.parseJSON(res);
            if(res.success){
                $('#images_list tr[data-id="'+id+'"]').css('background-color', '#dff0d8');
            }else{
                alert(res.msg);
            }
        })
        .error(function(err){
            console.log(err);
            alert("An error occurred saving your image");
        })
        return false;
    });
    
    $('form').unbind('submit');
    $('form').submit(function(){
        console.log('beforeunload');
        window.onbeforeunload = function(){ return undefined; }
    });
}

