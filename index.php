<?php 
/*
Plugin name: Team Preview
description: Plugin that preview post content 
author: John Mikhaeil
version: 1.0.0
*/

if(! defined ('ABSPATH')) exit;

require plugin_dir_path(__FILE__) . 'inc/generateFrontEnd.php';
require plugin_dir_path(__FILE__) . 'inc/APIpreview.php';


class TeamPreview{
    function __construct(){
        add_action('init' , [$this , 'enqueueFiles']);
        add_action('rest_api_init' , [$this , 'ourAPI']);
        add_action('wp_ajax_changePreviewAction' ,  [$this , 'getAjaxPreview']);
        add_action('wp_ajax_nopriv_changePreviewAction' ,[$this , 'getAjaxPreview']);
    }

    function ourAPI(){
        register_rest_route('tpblock-plugin/v1' , 'generatePreview' , array(
            'methods' => WP_REST_SERVER::READABLE,
            'callback' => [$this , 'APIcallback'],
        ));
    }

    function APIcallback($data){
        return generateBackEndPreview($data['postID']);
    }

    function getAjaxPreview(){
        $postID = $_POST['postID'];
        $post = get_post($postID);
        echo json_encode(array(
            'title' => $post->post_title,
            'content' => wp_strip_all_tags($post->post_content),
            'imageURL' => get_the_post_thumbnail_url($postID , 'full'),
        ));
        wp_die();
    }


    function enqueueFiles(){
        wp_register_style('tpindexStyle' , plugin_dir_url(__FILE__).'build/index.css');
        wp_register_script('tpindexScript' , plugin_dir_url( __FILE__ ). 'build/index.js' , array('wp-blocks' , 'wp-element' , 'wp-editor') );
        register_block_type('tpblockplugin/team-preview' , array(
            'editor_script' => 'tpindexScript',
            'editor_style' => 'tpindexStyle',
            'render_callback' => [$this , 'frontCallBack'],
        ));
        wp_register_script('tpajaxScript' , plugin_dir_url(__FILE__). 'build/ourAjax.js' );
        wp_localize_script('tpajaxScript' , 'ourAjax' , array( 
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
        ));
    }


    function frontCallBack($attributes){
        if($attributes['postID'] != 'notSelected'){
            wp_enqueue_style('tpindexStyle');
            wp_enqueue_script('tpajaxScript');
            return generateFrontEnd($attributes['postID']);
        }else{
            return null;
        }
    }
}

$teamPreview = new TeamPreview();