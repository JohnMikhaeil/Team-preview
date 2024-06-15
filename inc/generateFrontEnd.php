<?php
function generateFrontEnd($id){
    ob_start();
    $post = get_post($id)
    ?>
    <div class="tp_front_mainContainer">
        <div class="form-container">
            <form action="" method="POST">
                <select class="change-preview-select">
                    <option>Select Another Team Member To Preview</option>
                    <?php 
                        $args = array(
                            'post_type' => 'post',
                            'posts_per_page' => -1,
                        );
                        $postsOptions = get_posts($args);
                        foreach($postsOptions as $postOption){
                            ?>
                            <option <?php selected($postOption->ID , $id) ?> value="<?php echo $postOption->ID ?>"><?php echo $postOption->post_title ?></option>
                            <?php
                        };
                    ?>
                </select>
                <button class="change-preview">Preview</button>
            </form>
        </div>
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