<?php
/**
 * @package Estimate-Cost-Wp
 */

/** 
 * Plugin Name: Estimate Cost WP
 * Plugin URI: https://github.com/Gen0s-Dev/plugin-wp
 * Description: Outils pour faire des devis sur Word Press
 * Author: Boyer Clément
 * Version: 0.1.1
 * Author URI: https://gokami.fr
 * Text Domain: Estimate-Cost-Wp
*/

if ( !defined ('ABSPATH'))
    {
        die;
    }

class EstimateCostPlugin
    {
        function activation ()
            {

            }

        function desactivation ()
            {

            }
        
        function desinstaller ()
            {

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


add_shortcode('exemple','exemple_plugin');

    function exemple_plugin()
        {
           $info = "Bonjour, je suis un plugin";
            $info .= '<div class= "container">' ;
            $info .= '<div class= "aligncenter"> Ceci est une div </div> </div>';
          return $info;
        }

/**
 * 
 * 
 * 
 * 
 **/
add_action ('admin_menu','Addmenu');

        function Addmenu()
            {
                add_menu_page('Devis','Devis','4','estimate-cost','BackendPageNewEstimate','
                dashicons-clipboard','55');
                add_submenu_page('estimate-cost','Liste des devis','Liste des devis','4','list-estimate-cost','BackendPageListEstimate');
            }

            function BackendPageNewEstimate ()
            {
                ?> 
                <!-- Back end Page Nouveau Devis -->
                <h2>Bienvenue sur votre page pour faire un devis.</h2>
                
                
                <?php
            }

            function BackendPageListEstimate ()
            {
                ?> 
                 <!-- Back end Page Liste des Devis -->

                <h3>Liste de devis.</h3>
                
                
                <?php
            }

?>