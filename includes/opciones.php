<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

function gda_options(){
    add_options_page("Opciones Gestión de Asistencia", "Gestión de Asistencia", "manage_options", 'gda_options', gda_options_page());

}//cierre gda_options

function gda_options_page(){
    ?>
        <form action="options.php" method="POST">
            <?php
                settings_fields('gda_options');
                do_settings_sections('gda_options');
            ?>
            <input type="submit" name="guardar" value="Guardar">
        </form>
    <?php
}//cierre gda_options_page

//registrar y definir las opciones
add_action('admin_init', 'gda_options_admin_init');

function gda_options_admin_init(){
    register_setting('gda_options', 'gda_options', 'gda_options_sanitize');
    add_settings_section('gda_datos_email', 'Datos de correo', 'gda_datos_email', 'gda_options');
    add_settings_field('gda_options_email', 'Email', 'gda_op_email', 'gda_options', 'gda_datos_email');
}

function gda_datos_email(){
    echo "<strong>Introduce aquí el correo electrónico</strong>";
}

function gda_op_email(){
    $options = get_option('gda_options');
    $email = $options['email'];
    echo "<input id='gda_options_email' name='gda_options[email]' type='text' value='$email'> ";
}

function gda_options_sanitize($input){
    $valid=array();
    $valid['email'] = sanitize_text_field($input['email']);

    return $valid;
}
?>