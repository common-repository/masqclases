<?php
/*
Plugin Name: Gestión de Asistencia
Plugin URI: 
Description:Plugin de gestión de datos de clases, asistencias, pagos y alumnos.
Author: Fran Perez
Version: 1.3.1
Author URI: 
*/

//Scripts

function gda_scripts() {
    
    wp_deregister_script('script');
    wp_register_script('script', plugin_dir_url( __FILE__ ) .'js/check.js', '', '', true );
    wp_enqueue_script('script');    


}

add_action( 'admin_enqueue_scripts', 'gda_scripts' );
add_action( 'wp_enqueue_scripts', 'gda_scripts' );


//Includes
require_once( plugin_dir_path( __FILE__ ) . 'includes/manejo-asistencias.php');
require_once( plugin_dir_path( __FILE__ ) . 'includes/manejo-clases.php');
require_once( plugin_dir_path( __FILE__ ) . 'includes/manejo-usuarios.php');
require_once( plugin_dir_path( __FILE__ ) . 'includes/matricular.php');
require_once( plugin_dir_path( __FILE__ ) . 'includes/opciones.php');
require_once( plugin_dir_path( __FILE__ ) . 'includes/pagos-front.php');
require_once( plugin_dir_path( __FILE__ ) . 'includes/pagos.php');




//Crear menu 
add_action('admin_menu','gda_create_menu');


function gda_create_menu(){
	//menu nivel superior
	add_menu_page('Gestión de Asistencia','Gestión de Asistencia','manage_options','main_menu','main_plugin_page');
	add_submenu_page('main_menu','Gestión de Asistencia Alta Usuarios','Alta Usuarios','manage_options','gda_altaUsuario','gda_control_usuarios'); 
    add_submenu_page('main_menu','Gestión de Asistencia Alta Clases','Alta Clases','manage_options','gda_altaClase','gda_control_clases');
    add_submenu_page('main_menu','Gestión de Asistencia Matricular','Matricular','manage_options','gda_matricular','gda_matricular');
    add_submenu_page('main_menu','Gestión de Asistencia Asistencias','Asistencias','manage_options','gda_asistencias','gda_control_asistencias');
    add_submenu_page('main_menu','Gestión de Asistencia Pagos','Pagos','manage_options','gda_pagos','gda_control_pagos');
    add_submenu_page('main_menu','Gestión de Asistencia Opciones','Opciones','manage_options','gda_opciones','gda_options');

    add_action('admin_init','gda_register_settings');
}
function gda_register_settings(){
    //registrar ajustes
    register_setting('settings_group','options','gda_sanitize_options');
    
}

function gda_sanitize_options($input){
    $input['option_name']=sanitize_text_field($input['option_name']);
    $input['option_email']=sanitize_email($input['option_email']);
    $input['option_url']=esc_url($input['option_url']);

    return $input;
} 

function gda_install(){
    global $wpdb;

    $table_site = $wpdb->prefix.'usuarios';
    if( $wpdb->get_var("SHOW tables LIKE `$table_site`") != $table_site ){
        $sql = "CREATE TABLE `$table_site` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `nombre` varchar(50) DEFAULT NULL,
            `email` varchar(50) DEFAULT NULL,
            `apellido1` varchar(50) DEFAULT NULL,
            `apellido2` varchar(50) DEFAULT NULL,
            `año_nacimiento` year(4) DEFAULT NULL,
            `dni` varchar(9) DEFAULT NULL,
            `telefono` int(9) DEFAULT NULL,
            `direccion` varchar(40) DEFAULT NULL,
            `archivado` tinyint(1) DEFAULT NULL,
            PRIMARY KEY (`id`)
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          ";

        require_once(ABSPATH."wp-admin/includes/upgrade.php");
        dbDelta($sql);

    }

    $table_site = $wpdb->prefix.'clases';
    if( $wpdb->get_var("SHOW tables LIKE `$table_site`") != $table_site ){
        $sql = "CREATE TABLE `$table_site` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `nombre_clase` varchar(30) DEFAULT NULL,
            `año` int(4) DEFAULT NULL,
            `dias` varchar(20) DEFAULT NULL,
            `hora_inicio` varchar(10) DEFAULT NULL,
            `hora_fin` varchar(10) DEFAULT NULL,
            `activa` tinyint(1) DEFAULT NULL,
            `precio_clase` varchar(10) DEFAULT NULL,
            PRIMARY KEY (`id`)
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

        require_once(ABSPATH."wp-admin/includes/upgrade.php");
        dbDelta($sql); 
       
    }

    $table_site = $wpdb->prefix.'asistencia';
    if ( $wpdb->get_var("SHOW tables LIKE `$table_site`") != $table_site ){
        $sql = "CREATE TABLE `$table_site` (
                `id_asistencia` int(11) NOT NULL AUTO_INCREMENT,
                `id_usuario` int(11) NOT NULL,
                `id_clase` int(11) NOT NULL,
                `asistencia` tinyint(1) DEFAULT '0',
                `dia` date DEFAULT NULL,
                PRIMARY KEY (`id_asistencia`),
                FOREIGN KEY (`id_usuario`) REFERENCES `".$wpdb->prefix."usuarios` (`id`),
                FOREIGN KEY (`id_clase`) REFERENCES `".$wpdb->prefix."clases` (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;"
          ;
        require_once(ABSPATH."wp-admin/includes/upgrade.php");
        dbDelta($sql);
    }

    $table_site = $wpdb->prefix.'pagos';
    if( $wpdb->get_var("SHOW tables LIKE `$table_site`") != $table_site ){
        $sql = "CREATE TABLE `$table_site` (
            `id_pago` int(11) NOT NULL AUTO_INCREMENT,
            `id_usuario` int(11) NOT NULL,
            `id_clase` int(11) NOT NULL,
            `mes` varchar(10) DEFAULT NULL,
            `año` year(4) DEFAULT NULL,
            `pagado` tinyint(1) DEFAULT NULL,
            `comentario` varchar(50) DEFAULT NULL,
            `precio` varchar(10) DEFAULT NULL,
            PRIMARY KEY (`id_pago`),
            FOREIGN KEY (`id_usuario`) REFERENCES `".$wpdb->prefix.usuarios."` (`id`),
            FOREIGN KEY (`id_clase`) REFERENCES `".$wpdb->prefix.clases."` (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
            ";
        require_once(ABSPATH."wp-admin/includes/upgrade.php");
        dbDelta($sql); 

    }

    
}
register_activation_hook( __FILE__, 'gda_install' );

function gda_unistall(){
    global $wpdb;

    $table_site = $wpdb->prefix.'asistencia';

    if ( $wpdb->get_var("SHOW tables LIKE `$table_site`") == $table_site ){
        $sql = "DROP TABLE `$table_site`";
        $wpdb->query($sql);
    }    
    
    $table_site = $wpdb->prefix.'clases';

    if ( $wpdb->get_var("SHOW tables LIKE `$table_site`") == $table_site ){
        $sql = "DROP TABLE `$table_site`";
        $wpdb->query($sql);
    }

    $table_site = $wpdb->prefix.'pagos';

    if ( $wpdb->get_var("SHOW tables LIKE `$table_site`") == $table_site ){
        $sql = "DROP TABLE `$table_site`";
        $wpdb->query($sql);
    }

    $table_site = $wpdb->prefix.'usuarios';

    if ( $wpdb->get_var("SHOW tables LIKE `$table_site`") == $table_site ){
        $sql = "DROP TABLE `$table_site`";
        $wpdb->query($sql);
    }
    
}
register_uninstall_hook( __FILE__, 'gda_unistall' );
