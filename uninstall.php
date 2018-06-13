<?php
/**
 * @package Estimate-Cost-Wp
 **/


if ( ! defined ( 'WP_UNINSTALL_PLUGIN' )) 
{
    die;
}

// Supression des données dans le bdd

function delete_data_devis () 
{
    $devis = get_posts( array (
        'post_type'    => 'devis',
        'numberpost'    => -1,
        'post_status'   => array ('any','auto-draft'),
    
    ));

    foreach ($devis as $data)
    {
        wp_delete_post( $data->ID, true );
    }
}

delete_data_devis();

// function wp_delete_post( $postid = 0, $force_delete = false ) 
// {
//     global $wpdb;
//     $post = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->posts WHERE ID = %d", $postid ) );
//     if ( ! $post ) 
//     {
//     	return $post;
//     }
//     $post = get_post( $post );
//     if ( ! $force_delete && ( 'post' === $post->post_type || 'page' === $post->post_type ) && 'trash' !== get_post_status( $postid ) && EMPTY_TRASH_DAYS ) 
//     {
//         return wp_trash_post( $postid );
//     }
//     if ( 'attachment' === $post->post_type ) 
//     {
//         return wp_delete_attachment( $postid, $force_delete );
//     }
// }
