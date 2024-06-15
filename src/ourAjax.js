$(document).ready(function(){
    $('.change-preview').on('click' , function(e){
        e.preventDefault();
        const postID = $('.change-preview-select').val();
        $.ajax({
            url: ourAjax.ajaxurl,
            type:"POST",
            data:{
                action: 'changePreviewAction',
                postID:postID,
            },
            
            success: function(response){
                const data = JSON.parse(response)
                const URL = data.imageURL
                const text = data.content
                const title = data.title
                $('.content-container-text h5.title ').html(title)
                $('.content-container-image').css('background-image' , `url(${URL})` )
                $('.content-container-text p ').html(text)
            }
        })
    })
})