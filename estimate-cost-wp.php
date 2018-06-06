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

    //Créer Menu Back End
    function create_post_type ()
    {
        add_action( 'init', array ( $this , 'custom_post_type' ) );
    }
    //Créer Menu Back End
    function active_submenu_css ()
    {
        add_action( 'admin_menu', array ($this,'add_submenu_css') );
        add_action( 'admin_init', array ($this,'custom_setting') );
    }
    function add_submenu_css ()
    {
        add_submenu_page( 'edit.php?post_type=devis', 'Options CSS', 'Custom CSS', 'manage_options', 'options-submenu-css', array ($this,'submenu_css' ) );
    }
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
    // Cache options publier Meta box
    
    function not_publish_options_metabox ()
    {
        add_action('admin_head-post.php', array ($this,'hide_publish_metabox') );
        add_action('admin_head-post-new.php', array ($this,'hide_publish_metabox') );
    }

    // Cache buttons et eléments éditor Wordpress

    function not_buttons_editor ()
    {
        add_action('admin_head-post.php', array ($this,'hide_options_editor') );
        add_action('admin_head-post-new.php', array ($this,'hide_options_editor') );
    }

    // Shortcode content devis

    function shortcode ()
    {
        add_shortcode( 'devis', array ($this,'devis_shortcode' ) );
    }
   
    // Boutons TinyMCE

    function bouton_tinymce ()
    {
        add_action( 'admin_head',array ($this,'custom_boutons_tinymce') );
    }

    /*
    ===============================
    Boutons TinyMCE
    ===============================
    */
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
            $plugin_array['submit_button'] = plugins_url( '/admin/js/submit_button.js', __FILE__ );  

            return $plugin_array;
        }
    }

    function custom_add_tinymce_button($button)
    {
        array_push ($button,'|','text_button');
        array_push ($button,'tel_button');
        array_push ($button,'email_button');
        array_push ($button,'produit_button');
        array_push ($button,'|','|','submit_button');
        return $button;
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
    Shortcode content devis
    ===============================
    */
    function devis_shortcode ($atts)
    {
       
        shortcode_atts( array (
            'id' => '',
        ), $atts );

        $id=$atts['id'];

        $content = '<div class="container"><form>'.get_post_field( 'post_content',$id ).'</form></div>';

        return $content;

    }
    
    /*
    ===============================
    Hide Options Publish Box
    ===============================
    */
    
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
    Custom Column 
    ===============================
    */

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

    function create_columns($column)
    {
        // Création colonne

        $newColumns = array ();
	    $newColumns['title'] = 'Titre';
	    $newColumns['shortcode'] = 'Shortcode';
	    $newColumns['author'] = 'Auteur';
	    $newColumns['date'] = 'Date';
	    return $newColumns;
    
    }

    /*
    ===============================
    Activation Plugin  
    ===============================
    */

    function activation ()
    {
        // Génerer CPT
        $this->custom_post_type();
        // flush rewrite rules
        flush_rewrite_rules();
    }

    /*
    ===============================
    Désactivation Plugin  
    ===============================
    */
    function desactivation ()
    {
        // flush rewrite rules
        flush_rewrite_rules();
    }
      /*
    ===============================
    Menu CSS Custom Post Type   
    ===============================
    */
    //Submenu custom CSS
    function submenu_css()
    {
        $templates_css= dirname(__FILE__).'/admin/templates/custom_css.php';
        require_once($templates_css);
    }
    //Custom setting CSS
    function custom_setting()
    {
        register_setting( 'custom-css-options', 'devis_css' );

        add_settings_section( 'custom-css-section', 'Custom CSS', array ($this,'custom_css_section_callback'), 'options-submenu-css' );

        add_settings_field( 'custom-css', 'Insérer votre CSS', array ($this,'custom_css_callback'), 'options-submenu-css', 'custom-css-section' );
    }
    // Callback sanitize register
    // function sanitize_custom_css_callback($input)
    // {
    //     $output= esc_textarea( $input );
    //     return $output;
    // }
    // Callback section
    function custom_css_section_callback ()
    {
        echo 'Customiser votre devis avec du CSS';
    }

    function custom_css_callback()
    {
        $css = get_option('devis_css');
	    $css = ( empty($css) ? '/* Custom CSS */' : $css );
        echo '<div id="CustomCSS">'.$css.'</div>';
    }
    /*
    ===============================
    Custom Post Type   
    ===============================
    */
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
    Gestion Scripts   
    ===============================
    */

    // Fonction pour charger scripts dans partie Admin.

    function enqueue_admin ($hook) 
    {
        //css
        wp_enqueue_style( 'plugincustomcss', plugins_url( '/admin/css/custom.css', __FILE__ ), array (),'1.0.0','all' );

        // template custom css
        if ( 'devis_page_options-submenu-css' == $hook)
        {
            //css
            wp_enqueue_style( 'ace', plugins_url( '/admin/css/custom.ace.css', __FILE__ ), array (),'1.0.0','all' );
            //js
            wp_enqueue_script( 'ace', plugins_url( '/admin/js/ace.js', __FILE__ ), array ('jquery'),'1.2.1', true );
            wp_enqueue_script( 'plugincustomjs', plugins_url( '/admin/js/custom.js', __FILE__ ), array (),'1.0.0', true );

        }
    }

    // Fonction pour charger scripts dans partie Client.

    function enqueue_front ($hook) 
    {
        //css
        wp_enqueue_style( 'bootstrap', 'https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css',array (),'4.1.1', 'all' );

        //js
        wp_enqueue_script( 'bootstrap', 'https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js', array(),'4.1.1', true );

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
}

//Activation

register_activation_hook( __FILE__ ,array($estimatecostPlugin,'activation'));

//Désactivation

register_deactivation_hook( __FILE__ ,array($estimatecostPlugin,'desactivation'));





