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

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
Copyright 2005-2015 Automattic, Inc.
*/

// Pour protéger contre accès direct au ficier
if ( ! defined ( 'ABSPATH' ) )
{
    die;
}

class EstimateCostPlugin
{

    /*
    ===============================
    Gestion Scripts   
    ===============================
    */

    // Permet d'insérer css et js
    //admin
    function register_admin_scripts()
    {
        add_action( 'admin_enqueue_scripts', array ( $this ,'enqueue_admin' ) );

    }
    //front
    function register_front_scripts()
    {
        add_action( 'wp_enqueue_scripts', array ( $this ,'enqueue_front' ) );

    }

    /*--------------------------*/

    // Fonction pour charger scripts dans partie Admin.
    function enqueue_admin ($hook) 
    {
        //css
        wp_enqueue_style( 'plugincustomcss', plugins_url( '/admin/css/custom.css', __FILE__ ), array (),'1.0.0','all' );

        // template custom css
        if ( 'devis_page_options_submenu_css' == $hook)
        {
            //css
            wp_enqueue_style( 'ace', plugins_url( '/admin/css/custom.ace.css', __FILE__ ), array (),'1.0.0','all' );
            //js
            wp_enqueue_script( 'ace', plugins_url( '/admin/js/ace/ace.js', __FILE__ ), array (),'1.2.1', true );
            wp_enqueue_script( 'plugincustomjs', plugins_url( '/admin/js/custom.js', __FILE__ ), array (),'1.0.0', true );

        }
    }

    // Fonction pour charger scripts dans partie Client.
    function enqueue_front ($hook) 
    {
        //css
        wp_enqueue_style( 'bootstrap', 'https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css',array (),'4.1.1', 'all' );
        //wp_enqueue_style( 'custom-css', plugins_url( '/admin/css/custom.css', __FILE__ ),array (),'1.0.0', 'all' );
 
        //js
        wp_enqueue_script( 'bootstrap', 'https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js', array(),'4.1.1', true );
 
    }

    /*
    ===============================
    Custom Post Type   
    ===============================
    */

    //Créer Menu Back End
    function create_post_type ()
    {
        add_action( 'init', array ( $this , 'custom_post_type' ) );
    }

    /*--------------------------*/

    // Insfrastrure pour créer Menu avec Create Modified and Delete de Wordpress
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
            'rewrite'               => array ('slug'=>'devis'),
            'capability_type'       => 'page',
            'hierarchical'          => false,
            'supports'              => array ('title','editor','revision','custom-fields'),
            'taxonomies'            => array (''),
            'menu_position'         => 55,
            'menu_icon'             => 'dashicons-clipboard',
            'exclude_from_search'   => true,
            );

        register_post_type('devis', $args );
    }

    /*
    ===============================
    Menu CSS Custom Post Type   
    ===============================
    */

    //Créer Menu Back End
    function active_submenu_css ()
    {
        add_action( 'admin_menu', array ($this,'add_submenu_css') );
        add_action( 'admin_init', array ($this,'custom_setting') );
    }
    function add_submenu_css ()
    {
        $lien_cpt_devis ='edit.php?post_type=devis';
        add_submenu_page( $lien_cpt_devis, 'Options CSS', 'Custom CSS', 'manage_options', 'options_submenu_css', array ($this,'submenu_css' ) );
    }

    /*--------------------------*/

    //Submenu custom CSS
    function submenu_css()
    {
        $templates_css= dirname(__FILE__).'/admin/templates/custom_css.php';
        require_once($templates_css);
    }
    //Custom setting CSS
    function custom_setting()
    {
        register_setting( 'devis_custom_css_group','devis_css','sanitize_custom_css_callback' );

        add_settings_section( 'custom-css-section', 'Custom CSS', array ($this,'custom_css_section_callback'), 'options_submenu_css' );

        add_settings_field( 'custom-css', 'Insérer votre CSS', array ($this,'custom_css_callback'), 'options_submenu_css', 'custom-css-section' );
    }
    // Callback sanitize register
    function sanitize_custom_css_callback($input)
    {
        $output= esc_textarea( $input );
        return $output;
    }
    // Callback section
    function custom_css_section_callback ()
    {
        echo 'Customiser votre devis avec du CSS';
    }
    // Callback css
    function custom_css_callback()
    {
        $css = get_option('devis_css');
	    $css = ( empty($css) ? '/* Custom CSS */' : $css );
        echo '<div id="CustomCSS">'.$css.'</div><textarea id="devis_css" name="devis_css" style = "display:none; visibility:hidden;">'.$css.'</textarea>';
    }

    /*
    ===============================
    Custom Column 
    ===============================
    */

    // Ajout colonne dans hook
    function columns ()
    {
        add_filter( 'manage_devis_posts_columns', array ($this , 'create_columns' ) );
    }
    // Ajout colonne dans back end
    function rows ()
    {
        add_action( 'manage_devis_posts_custom_column', array ($this,'create_rows'), 10, 2 );
    }

    /*--------------------------*/

    // Création colonne
    function create_rows ( $column, $post_id ) 
    {
        switch ( $column ):
            case 'shortcode':
                // Shortcode view
                $id = get_the_ID();
                $title= get_the_title( );
                echo '[devis id ='.'"'.$id.'"'.' titre ='.'"'.$title.'"'.'] ';
            break;
          default:
            break;
        endswitch;
        
    }
    // Insertion colonne
    function create_columns($column)
    {
        $newColumns = array ();
	    $newColumns['title'] = 'Titre';
	    $newColumns['shortcode'] = 'Shortcode';
	    $newColumns['author'] = 'Auteur';
	    $newColumns['date'] = 'Date';
	    return $newColumns;
    
    }

    /*
    ===============================
    Hide Options Publish Box
    ===============================
    */

    // Cache options publier Meta box
    function not_publish_options_metabox ()
    {
        add_action('admin_head-post.php', array ($this,'hide_publish_metabox') );
        add_action('admin_head-post-new.php', array ($this,'hide_publish_metabox') );
    }

    /*--------------------------*/

    function hide_publish_metabox ()
    {
        $my_post_type= 'devis';
        global $post;
        if($post->post_type == $my_post_type)
        {
            echo '
                <style type="text/css">
                    #misc-publishing-actions,
                    #minor-publishing-actions{
                        display:none;
                    }
                </style>';
        }

    }    

    /*
    ===============================
    Hide Options Editor 
    ===============================
    */

    // Cache buttons et eléments éditor Wordpress
    function not_buttons_editor ()
    {
        add_action('admin_head-post.php', array ($this,'hide_options_editor') );
        add_action('admin_head-post-new.php', array ($this,'hide_options_editor') );
    }

    /*--------------------------*/

    function hide_options_editor ()
    {
        $my_post_type= 'devis';
        global $post;
        if($post->post_type == $my_post_type)
        {
            echo '
                <style type="text/css">
                    #mceu_33,
                    #insert-media-button{
                        display:none;
                    }
                </style>';
        }

    }

    /*
    ===============================
    Shortcode content devis
    ===============================
    */

    // Shortcode content devis
    function shortcode ()
    {
        add_shortcode( 'devis', array ($this,'devis_shortcode' ) );
    }

    /*--------------------------*/

    function devis_shortcode ($atts)
    {
        shortcode_atts( array (
            'id' => '',
        ), $atts );

        $id=$atts['id'];
        $custom_css = esc_attr( get_option( 'devis_css') );
        if (!empty ($custom_css)):
                echo '<style>'.$custom_css.'</style>';
        endif;
        $content = '<div class="container"><form method="post" action="#">'.get_post_field( 'post_content',$id ).'</form></div>';
        return $content;

    }

    /*
    ===============================
    Boutons TinyMCE
    ===============================
    */

    // Boutons TinyMCE
    function bouton_tinymce ()
    {
        add_action( 'admin_head',array ($this,'custom_boutons_tinymce') );
    }

    /*--------------------------*/

    function custom_boutons_tinymce()
    {
        global $typenow ;
        // Verification utilisateur permision
        if (! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ))
        {
            return false;
        }
        //Verification post type
        if (! in_array($typenow,array ('devis') ))
        {
            return;
        }
        // Check tinymce enable
        if ( get_user_option( 'rich_editing' ) == 'true')
        {
            add_filter( 'mce_external_plugins',array ($this,'custom_add_tinymce_plugin'));
            add_filter( 'mce_buttons', array ($this,'custom_add_tinymce_button' ));

        }

    }

    function custom_add_tinymce_plugin($plugin_array)
    {
        $my_post_type = 'devis';
        global $post;
        if($post->post_type == $my_post_type)
        {
            $plugin_array['text_button'] = plugins_url( '/admin/js/text_button.js', __FILE__ );
            $plugin_array['tel_button'] = plugins_url( '/admin/js/tel_button.js', __FILE__ );
            $plugin_array['email_button'] = plugins_url( '/admin/js/email_button.js', __FILE__ );
            $plugin_array['produit_button'] = plugins_url( '/admin/js/produit_button.js', __FILE__ ); 
            $plugin_array['envoyer_button'] = plugins_url( '/admin/js/envoyer_button.js', __FILE__ );  
            return $plugin_array;
        }
    }

    function custom_add_tinymce_button($button)
    {
        array_push ($button,'|','text_button');
        array_push ($button,'tel_button');
        array_push ($button,'email_button');
        array_push ($button,'produit_button');
        array_push ($button,'|','envoyer_button');
        return $button;
    }

    /*
    ===============================
    Post info Mail   
    ===============================
    */

    //Post mail
    function devis_post_mail ()
    {
        add_action( 'template_redirect',array ($this,'recup_post_mail') );
    }

    /*--------------------------*/

    function recup_post_mail()
    {  
        // $arraypost permet de récuperer tableau des names de $_POST
        $arraypost = array_keys($_POST);
        // $countarray permet de connaitre longueur tableau
        $countarray = count ($arraypost);
        $valname[ ]=null;
        $valpost[ ]=null;
        for ($i=0;$i<$countarray;$i++)
        {
            $val=$arraypost[$i];
            $$val=$arraypost[$i];
         

            if (isset ($_POST[${$val}]))
            {
                ${$val} = trim($_POST[${$val}]);
            }
            else
            {
                ${$val}= '';
            }
            array_push ($valname,$val);
            array_push ($valpost,${$val});
        }
        
        if (!empty ($valpost[1]))
        {
            $emailTo = get_option( 'admin_email' );
            //$emailTo='stasteheti@qwfox.com';
            $subject = 'Devis de '.$valpost[1].' '.$valpost[2];
            $body="Fiche contact :";
            for ($j=1;$j<=6;$j++)
            {
                $body=$body."\n $valname[$j]: $valpost[$j]";
            }
            $body=$body."\n\n <h4>Produit :</h4> \n";

            for ($k=7;$k<=$countarray;$k++)
            {
                $body=$body."\n $valname[$k] = $valpost[$k] unités";
            }   
            
            add_filter( 'wp_mail_content_type','set_content_type');

            function set_content_type ($content_type)
            {
                return 'text/html' ;
            }
            $sent_email= wp_mail($emailTo,$subject,$body);
            // var_dump($emailTo);
            // var_dump($subject);
            // var_dump($body);

            if ($sent_email)
            {
                echo 
                    '<div style=z-index:1 class="alert alert-success alert-dismissible fade show" role="alert">
                    Votre demande a bien été prise en compte, vous serez recontactez sous 48 heures.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>';                   
            }
        }
    }
   
    /*
    ===============================
    Meta Box
    ===============================
    */
    // function shortcode_meta_box ()
    // {
    //     add_meta_box( 'shortcode_view', title, callback, screen, context, priority, callback_args );
    // }

    /*
    ===============================
    Activation Plugin  
    ===============================
    */

    function active ()
    {
        require_once plugin_dir_path( __FILE__ ) . 'includes/estimate-cost-plugin-active.php';
        EstimeCostPluginActivate:: activate();
    }  
}
/*
===============================
Demarrage Functions  
===============================
*/
if (class_exists('EstimateCostPlugin'))
{   
    $estimatecostPlugin = new EstimateCostPlugin();
    $estimatecostPlugin->register_admin_scripts();
    $estimatecostPlugin->register_front_scripts();
    $estimatecostPlugin->create_post_type();
    $estimatecostPlugin->active_submenu_css();
    $estimatecostPlugin->columns();
    $estimatecostPlugin->rows();
    $estimatecostPlugin->not_publish_options_metabox(); 
   // $estimatecostPlugin->not_buttons_editor(); 
    $estimatecostPlugin->shortcode();
    $estimatecostPlugin->bouton_tinymce();
    $estimatecostPlugin->devis_post_mail();
}

//Activation
register_activation_hook( __FILE__ ,array($estimatecostPlugin,'active'));

//Désactivation
require_once plugin_dir_path( __FILE__ ) . 'includes/estimate-cost-plugin-deactive.php';
register_deactivation_hook( __FILE__ ,array('EstimeCostPluginDeactivate','deactive'));





