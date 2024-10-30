<?php

//Dar de alta a usuarios en la base de datos
function gda_control_usuarios(){
    global $wpdb;
    if(isset($_POST['guardar_usuario'])){
        check_admin_referer( 'gda_alta_usuario', 'gda_alta_usuario_nonce' );
        if(isset($_POST['nombre'])){
            $nombre=sanitize_text_field( $_POST['nombre']);
        }
        if(isset($_POST['apellido1'])){
            $apellido1=sanitize_text_field($_POST['apellido1']);
        }
        if(isset($_POST['apellido2'])){
            $apellido2=sanitize_text_field($_POST['apellido2']);
        }
        if(isset($_POST['año_nacimiento'])){
            $año_nacimiento=intval($_POST['año_nacimiento']);
            if ( ! is_int($año_nacimiento) ) {
                $año_nacimiento = '';
            }
              
            if ( strlen( $año_nacimiento ) > 4 ) {
                $año_nacimiento = substr( $año_nacimiento, 0, 4 );
            }
        }
        if(isset($_POST['email'])){
            $email=sanitize_email($_POST['email']);
        }
        if(isset($_POST['dni'])){
            $dni=sanitize_text_field($_POST['dni']);
            if ( ! $dni ) {
                $dni = '';
            }
              
            if ( strlen( $dni ) > 9 ) {
                $dni = substr( $dni, 0, 9 );
            }
        }
        if(isset($_POST['telefono'])){
            $telefono=sanitize_text_field($_POST['telefono']);
            $telefono=intval($telefono);
            if ( ! is_int($telefono) ) {
                $telefono = '';
            }
              
            if ( strlen( $telefono ) > 9 ) {
                $telefono = substr( $telefono, 0, 9 );
                $telefono = int_val($telefono);
                if(!is_int($telefono)){
                    $telefono = '';
            }
        }
        if(isset($_POST['direccion'])){
            $direccion=sanitize_text_field($_POST['direccion']);
        }
        $user=array(
            'nombre'=>$nombre,
            'email'=>$email,
            'apellido1'=>$apellido1,
            'apellido2'=>$apellido2,
            'año_nacimiento'=>$año_nacimiento,
            'dni'=>$dni,
            'telefono'=>$telefono,
            'direccion'=>$direccion,
            'archivado'=>0
         );
         $table_site=$wpdb->prefix.'usuarios';
         $wpdb->insert($table_site,$user);
    }
    }
    //accede si se pulsa el boton de confirmar modificado
    if(isset($_POST['confirma_modificado'])){
        if(isset($_POST['id_usuario_confirmado'])){
            check_admin_referer( 'gda_modifica_usuario', 'gda_modifica_usuario_nonce' );
            $id_usuario=intval($_POST['id_usuario_confirmado']);
            if(is_int($id_usuario)){
                $id=$id_usuario;
            }
            
        }
        if(isset($_POST['nombre'])){
            $nombre=sanitize_text_field( $_POST['nombre']);
        }
        if(isset($_POST['apellido1'])){
            $apellido1=sanitize_text_field($_POST['apellido1']);
        }
        if(isset($_POST['apellido2'])){
            $apellido2=sanitize_text_field($_POST['apellido2']);
        }
        if(isset($_POST['año_nacimiento'])){
            $año_nacimiento=sanitize_text_field($_POST['año_nacimiento']);
            if ( ! $año_nacimiento ) {
                $año_nacimiento = '';
            }
              
            if ( strlen( $año_nacimiento ) > 4 ) {
                $año_nacimiento = substr( $año_nacimiento, 0, 4 );
            }
        }
        if(isset($_POST['email'])){
            $email=sanitize_email($_POST['email']);
        }
        if(isset($_POST['dni'])){
            $dni=sanitize_text_field($_POST['dni']);
            if ( ! $dni ) {
                $dni = '';
            }
              
            if ( strlen( $dni ) > 9 ) {
                $dni = substr( $dni, 0, 9 );
            }
        }
        if(isset($_POST['telefono'])){
            $telefono=sanitize_text_field($_POST['telefono']);
            $telefono = intval($telefono);
            if ( ! is_int($telefono) ) {
                $telefono = '';
            }else{
                if ( strlen( $telefono ) > 9 ) {
                    $telefono = substr( $telefono, 0, 9 );
                }
            }
              
            
        }
        if(isset($_POST['direccion'])){
            $direccion=sanitize_text_field($_POST['direccion']);
        }
        if(isset($_POST['usuario_activo'])){$activo=1;}else{$activo=0;}

        $usuario_modificado=array(
            'nombre'=>$nombre,
            'email'=>$email,
            'apellido1'=>$apellido1,
            'apellido2'=>$apellido2,
            'año_nacimiento'=>$año_nacimiento,
            'dni'=>$dni,
            'telefono'=>$telefono,
            'direccion'=>$direccion,
            'archivado'=>$activo

        );
        $table_site=$wpdb->prefix.'usuarios';
        $wpdb->update($table_site,$usuario_modificado, array('id'=>$id));

    }
    //accede si se confirma la baja de usuario
    if(isset($_POST['confirma_baja_usuario'])){
        check_admin_referer( 'gda_baja_usuario', 'gda_baja_usuario_nonce' );
        $id_usuario=intval($_POST['id_usuario']);
        if(is_int($id_usuario)){
            $id=$id_usuario;
        }
        $table_site=$wpdb->prefix.'usuarios';
        $wpdb->query( 'SET foreign_key_checks=0' );
        $wpdb->delete($table_site,array('id'=>intval($id)));
        $wpdb->query( 'SET foreign_key_checks=1' );
    }

    //accede al dar de baja al usuario
    if(isset($_POST['baja_usuario'])){
        if(isset($_POST['id_usuario'])){
            $id_usuario=intval($_POST['id_usuario']);
            if(is_int($id_usuario)){
                $id=$id_usuario;
            }
        
        }
        global $wpdb;
        $table_site=$wpdb->prefix.'usuarios';
        $baja_usuario=$wpdb->get_row("SELECT * FROM $table_site WHERE id=$id");
        ?>
        <h1>Baja usuario</h1>
        <h2>Va a eliminar este usuario de la base de datos</h2>
        <table>
        <tr>
            <td bgcolor="lightblue" width="10%" align="center">Nombre usuario</td>
            <td bgcolor="lightblue" width="10%" align="center">Primer Apellido</td>
            <td bgcolor="lightblue" width="10%" align="center">Segundo Apellido</td>
            <td bgcolor="lightblue" width="10%" align="center">Año de nacimiento</td>
            <td bgcolor="lightblue" width="10%" align="center">Email</td>
            <td bgcolor="lightblue" width="10%" align="center">DNI</td>
            <td bgcolor="lightblue" width="10%" align="center">Telefono</td>
            <td bgcolor="lightblue" width="10%" align="center">Direccion</td>
            <td bgcolor="lightblue" width="5%" align="center">Archivado</td>
            
        </tr>
        <tr>
            <td width="10%" align="center"><?php echo esc_html($baja_usuario->nombre) ?></td>
            <td width="10%" align="center"><?php echo esc_html($baja_usuario->apellido1) ?></td>
            <td width="10%" align="center"><?php echo esc_html($baja_usuario->apellido2) ?></td>
            <td width="10%" align="center"><?php echo esc_html($baja_usuario->año_nacimiento) ?></td>
            <td width="10%" align="center"><a href="mailto:<?php echo antispambot($baja_usuario->email,1) ?>"><?php echo antispambot($baja_usuario->email) ?></a></td>
            <td width="10%" align="center"><?php echo esc_html($baja_usuario->dni) ?></td>
            <td width="10%" align="center"><?php echo esc_html($baja_usuario->telefono) ?></td>
            <td width="10%" align="center"><?php echo esc_html($baja_usuario->direccion) ?></td>
            <form method="post">
            <td width="5%" align="center"><input type="checkbox" name="usuario_activo" onclick="return false;" <?php if($baja_usuario->archivado==1){echo 'checked="checked"';}?>></td>
            <td width="10%" align="center">   
                <input type="hidden" name="id_usuario" value="<?php echo esc_html($baja_usuario->id)?>">                           
            </td>                   
        </tr>
        <tr>
            <td width="10%" align="center"><input type="submit" value="Confirmar" name="confirma_baja_usuario"></td> 
            <td width="10%" align="center"><input type="submit" value="Cancelar" name="cancelar_baja_usuario"></td>       
            <?php wp_nonce_field( 'gda_baja_usuario', 'gda_baja_usuario_nonce' ); ?>
            </form>
        </tr>
        <?php
    }else{
    
    

    //accede si se quiere modificar un usuario
    if(isset($_POST['modifica'])){
        if(isset($_POST['id_usuario'])){
            $id_usuario=intval($_POST['id_usuario']);
            if(is_int($id_usuario)){
                $id=$id_usuario;
            }
        
        }
        $table_site=$wpdb->prefix.'usuarios';
        $modifica_usuario=$wpdb->get_row("SELECT * FROM $table_site WHERE id=$id");

        ?>
        <h1>Modificar usuario</h1>
        <table>
        <tr>
            <td bgcolor="lightblue" width="10%" align="center">Nombre usuario</td>
            <td bgcolor="lightblue" width="10%" align="center">Primer Apellido</td>
            <td bgcolor="lightblue" width="10%" align="center">Segundo Apellido</td>
            <td bgcolor="lightblue" width="5%" align="center">Año de nacimiento</td>
            <td bgcolor="lightblue" width="10%" align="center">Email</td>
            <td bgcolor="lightblue" width="10%" align="center">DNI</td>
            <td bgcolor="lightblue" width="10%" align="center">Telefono</td>
            <td bgcolor="lightblue" width="10%" align="center">Direccion</td>
            <td bgcolor="lightblue" width="5%" align="center">Archivado</td>
            <td bgcolor="lightblue" width="10%" align="center">Modificar</td>
        </tr>
        <tr>
            <form method="post">
                <td width="10%" align="center"><input type="text" name="nombre" size="15" maxlength="30" value="<?php echo esc_html($modifica_usuario->nombre) ?>" required/></td>
                <td width="10%" align="center"><input type="text" name="apellido1" size="15" maxlength="30" value="<?php echo esc_html($modifica_usuario->apellido1) ?>" required/></td>
                <td width="10%" align="center"><input type="text" name="apellido2" size="15" maxlength="30" value="<?php echo esc_html($modifica_usuario->apellido2) ?>"/></td>
                <td width="5%" align="center"><input type="number" name="año_nacimiento" min="0" size="4" maxlength="4" value="<?php echo esc_html($modifica_usuario->año_nacimiento) ?>"/></td>
                <td width="10%" align="center"><input type="text" name="email" size="15" maxlength="30" value="<?php echo antispambot($modifica_usuario->email)?>" pattern="^[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$" required/></td>    
                <td width="10%" align="center"><input type="text" name="dni" size="10" maxlength="9" value="<?php echo esc_html($modifica_usuario->dni) ?>" pattern='^\d{8}[a-zA-Z]$' required/></td>    
                <td width="10%" align="center"><input type="text" name="telefono" size="10" maxlength="9" value="<?php echo esc_html($modifica_usuario->telefono) ?>" pattern="^[6|7|9][0-9]{8}$" /></td>
                <td width="10%" align="center"><input type="text" name="direccion" size="30" maxlength="30" value="<?php echo esc_html($modifica_usuario->direccion) ?>"/></td>    
                <td width="5%" align="center"><input type="checkbox" name="usuario_activo" <?php if($modifica_usuario->archivado==1){echo 'checked="checked"';}?>></td>


                <td width="10%" align="center">
                    <input type="hidden" name="id_usuario_confirmado" value="<?php echo esc_html($modifica_usuario->id)?>">
                    <input type="submit" value="Confirmar modificado" name="confirma_modificado">
                </td>
                <?php wp_nonce_field( 'gda_modifica_usuario', 'gda_modifica_usuario_nonce' ); ?>
            </form>
        </tr>
        
        <?php
    //si no se ha pulsado el boton de modificar se muestra el formulario de altas
    }else{
  ?>
    <h1>Añadir usuario</h1>
    <h2>Los campos con * son obligatorios</h2>

    <form  method="post">
	
    <table>
		<tr><td align="right">Nombre (*): </td><td align="left"><input type="text" name="nombre" size="40" maxlength="40" required/></td></tr>
		<tr><td align="right">Primer Apellido (*): </td><td align="left"><input type="text" name="apellido1" size="40" maxlength="40" requiered/></td></tr>
		<tr><td align="right">Segundo Apellido: </td><td align="left"><input type="text" name="apellido2" size="40" maxlength="40"/></td></tr>
        <tr><td align="right">Email (*): </td><td align="left"><input  type="text" name="email" pattern="^[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$" size="40" maxlength="40" required /></td></tr>
        <tr><td></td><td style="color:gray">Introduce email valido: alguien@algunlugar.es  </td></tr>
        <tr><td align="right">Año de nacimiento: </td><td align="left"><input type="number" name="año_nacimiento" size="4" maxlength="4" min="0"/></td></tr>
        <tr><td align="right">DNI (*): </td><td align="left"><input type="text" pattern='^\d{8}[a-zA-Z]$' name="dni" id="dni" size="10" maxlength="9" required/></td></tr>
        <tr><td></td><td style="color:gray">Introduce un NIF/CIF sin espacios ni - o .: 11111111B </td></tr>
        <tr><td align="right">Telefono: </td><td align="left"><input type="text" name="telefono" pattern="^[6|7|9][0-9]{8}$" size="10" maxlength="9"/></td></tr>
        <tr><td></td><td style="color:gray">Introduce telefono sin espacios que inicie en 6, 7, 8 o 9: 111111111 </td></tr>
        <tr><td align="right">Direccion: </td><td align="left"><input type="text" name="direccion" size="40" maxlength="40"/></td></tr>
		<tr><td align="center" colspan="2"><input type="submit" value="AÑADIR" name="guardar_usuario"></td></tr>	
	</table>
        <?php wp_nonce_field( 'gda_alta_usuario', 'gda_alta_usuario_nonce' ); ?>
    </form>

    <h1>Usuarios actuales</h1>
    <table>
        <tr>
            <td bgcolor="lightblue" width="10%" align="center">Nombre usuario</td>
            <td bgcolor="lightblue" width="10%" align="center">Primer Apellido</td>
            <td bgcolor="lightblue" width="10%" align="center">Segundo Apellido</td>
            <td bgcolor="lightblue" width="5%" align="center">Año de nacimiento</td>
            <td bgcolor="lightblue" width="10%" align="center">Email</td>
            <td bgcolor="lightblue" width="10%" align="center">DNI</td>
            <td bgcolor="lightblue" width="10%" align="center">Telefono</td>
            <td bgcolor="lightblue" width="10%" align="center">Direccion</td>
            <td bgcolor="lightblue" width="5%" align="center">Archivado</td>
            <td bgcolor="lightblue" width="10%" align="center">Modificar</td>
            <td bgcolor="lightblue" width="10%" align="center">Dar de baja</td>

        </tr>
        <?php 
            global $wpdb;
            $table_site=$wpdb->prefix.'usuarios';
            $usuarios=$wpdb->get_results("SELECT * FROM $table_site");
            if(count($usuarios)>0){
                foreach($usuarios as $usuario){    
                           
        ?>
            <tr>
            
                    <td width="10%" align="center"><?php echo esc_html($usuario->nombre) ?></td>
                    <td width="10%" align="center"><?php echo esc_html($usuario->apellido1) ?></td>
                    <td width="10%" align="center"><?php echo esc_html($usuario->apellido2) ?></td>
                    <td width="5%" align="center"><?php echo esc_html($usuario->año_nacimiento) ?></td>
                    <td width="10%" align="center"><a href="mailto:<?php echo antispambot($usuario->email) ?>"><?php echo antispambot($usuario->email) ?></a></td>
                    <td width="10%" align="center"><?php echo esc_html($usuario->dni) ?></td>
                    <td width="10%" align="center"><?php echo esc_html($usuario->telefono) ?></td>
                    <td width="10%" align="center"><?php echo esc_html($usuario->direccion) ?></td>
                    <form method="post">
                    <td width="5%" align="center"><input type="checkbox" name="usuario_activo" onclick="return false;" <?php if($usuario->archivado==1){echo 'checked="checked"';}?>></td>
                    <td width="10%" align="center">   
                            <input type="hidden" name="id_usuario" value="<?php echo esc_html($usuario->id)?>">
                            <input type="submit" value="Modificar" name="modifica">    
                    </td>
                    <td width="10%" align="center"><input type="submit" value="Dar de baja" name="baja_usuario"></td>        
                    </form>
            </tr>
    
        <?php
                }
            }
        ?>
        </table>

<?php
    }
}
}
?>