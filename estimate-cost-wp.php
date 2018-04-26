<?php
/**
 * @package Estimate-Cost-Wp
 **/

/** 
 * Plugin Name: Estimate Cost WP
 * Plugin URI: https://github.com/Gen0s-Dev/plugin-wp
 * Description: Outils pour faire des devis sur Word Press
 * Author: Boyer Clément
 * Version: 0.1.1
 * Author URI: https://gokami.fr
 * License: GPLv2 or later
 * Text Domain: estimate-cost-wp
**/

// Pour protéger contre accès direct au ficier
if ( ! defined ( 'ABSPATH' ) )
    {
        die;
    }

class EstimateCostPlugin
{
    function __construct()
    {
        add_action( 'init', array ( $this , 'custom_post_type' ) );

    }

    function activation ()
    {
        // Génerer CPT
        $this->custom_post_type();
        // flush rewrite rules
        flush_rewrite_rules();
    }

    function desactivation ()
    {
        // flush rewrite rules
        flush_rewrite_rules();
    }

    function desinstaller ()
    {
        // Supprimer CPT
        // Supprimer tous les données de l'extension de la bdd.
    }
        
    function custom_post_type ()
    {
        $labels = array(
            'name'                  => 'Devis',
            'singular_name'         => 'Devis',
            'add_new'               => 'Créer un devis',
            'all_items'             => 'Liste des devis',
            'add_new_item'          => 'Créer un devis',
            'edit_item'             => 'Edition devis',
            'new_item'              => 'Nouveau devis',
            'view_item'             => 'Visualiser devis',
            'search_item'           => 'Chercher devis',
            'not_found'             => 'Devis non trouvé',
            'not_found_in_trash'    => 'Devis non trouvé dans la corbeille',
            );

        $args = array (
            'labels'                => $labels,
            'public'                => false,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'has_archive'           => false,
            'publicly_queryable'    => true,
            'query_var'             => true,
            'rewrite'               => false,
            'capability_type'       => 'page',
            'hierarchical'          => false,
            'supports'              => array ('title','editor','revision','custom-fiels'),
            'taxonomies'            => array (''),
            'menu_position'         => 55,
            'menu_icon'             => 'dashicons-clipboard',
            'exclude_from_search'   => true,
            );

        register_post_type('devis', $args );
    }
}
 
if (class_exists('EstimateCostPlugin'))
{
    $estimatecostPlugin = new EstimateCostPlugin();
}

//Activation

register_activation_hook( __FILE__ ,array($estimatecostPlugin,'activation'));

//Désactivation

register_deactivation_hook( __FILE__ ,array($estimatecostPlugin,'desactivation'));

//Désinstaller



// add_action ('admin_menu','Addmenu');

//         function Addmenu()
//             {
//                 add_menu_page('Devis','Devis','4','estimate-cost','BackendPageNewEstimate','
//                 dashicons-clipboard','55');
//                 add_submenu_page('estimate-cost','Liste des devis','Liste des devis','4','list-estimate-cost','BackendPageListEstimate');
//             }

