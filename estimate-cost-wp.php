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
        function activation ()
            {
                // Génerer CPT
                // flush rewrite rules
            }

        function desactivation ()
            {
                // flush rewrite rules
            }
        
        function desinstaller ()
            {
                // Supprimer CPT
                // Supprimer tous les données de l'extension de la bdd.
            }
        
        function custom_post_type ()
            {
                register_post_type();
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
