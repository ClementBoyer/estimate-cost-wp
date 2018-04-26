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
    'post_type'    => 'devis',
    'numberpost'    => -1,
    'post_status'   => array ('any','auto-draft'),
    
));

foreach ($devis as $data)
{
    wp_delete_post( $data->ID, true );
}

