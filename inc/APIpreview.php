<?php 
function generateBackEndPreview($id){
    ob_start();
    $post = get_post($id)
    ?>
    <div class="tp_front_mainContainer">
        <div class="content-container">
            <div class="content-container-image" style="background-image:url(<?php echo get_the_post_thumbnail_url($id , 'full') ?>)"></div>
            <div class="content-container-text">
                <h5 class="title"><?php echo $post->post_title ?></h5>
                <p class="content"><?php echo wp_trim_words($post->post_content , 50) ?></p>
            </div>
        </div>
    </div>
    <?php
    $output = ob_get_clean();
    return $output;
}