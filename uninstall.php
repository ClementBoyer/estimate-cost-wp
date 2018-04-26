<?php
/**
 * @package Estimate-Cost-Wp
 **/


if ( ! defined ( 'WP_UNINSTALL_PLUGIN' )) 
{
    die;
}

// Supression des données dans le bdd

$devis = get_posts( array (
    'post_types'    => 'devis',
    'numberpost'    => -1,
    'post_status'   => 'any',
    
));

foreach ($devis as $data)
{
    wp_delete_post( $data->ID, true );
}

