<?php
//Dar de alta a clases en la base de datos
function gda_control_clases(){
    global $wpdb;
    if(isset($_POST['guardar_clase'])){
        check_admin_referer( 'gda_alta_clase', 'gda_alta_clase_nonce' );
        if(isset($_POST['nombre_clase'])){
            $nombre_clase=sanitize_text_field($_POST['nombre_clase']);
        }
        if(isset($_POST['dias'])){
            $dias=sanitize_text_field($_POST['dias']);
        }
        if(isset($_POST['anyo'])){
            $anyo=intval($_POST['anyo']);
            if ( ! is_int($anyo) ) {
                $anyo = '';
            }
              
            if ( strlen( $anyo ) > 4 ) {
                $anyo = substr( $anyo, 0, 4 );
            }
        }
        if(isset($_POST['hora_inicio'])){
            $hora_inicio=sanitize_text_field($_POST['hora_inicio']);
             
            if ( strlen( $hora_inicio ) > 5) {
                $hora_inicio = substr( $hora_inicio, 0, 5 );
                $hora_inicio=sanitize_text_field($hora_inicio);
            }
            
        }
        if(isset($_POST['hora_fin'])){
            $hora_fin=sanitize_text_field($_POST['hora_fin']);
            
            if ( strlen( $hora_fin ) > 5) {
                $hora_fin = substr( $hora_fin, 0, 5 );
                $hora_fin = sanitize_text_field($hora_fin);
            }
        }
        if(isset($_POST['precio_clase'])){
            $precio_clase=sanitize_text_field($_POST['precio_clase']);
        }


        $clase=array(
            'nombre_clase'=>$nombre_clase,
            'dias'=>$dias,
            'año'=>$anyo,
            'hora_inicio'=>$hora_inicio,
            'hora_fin'=>$hora_fin,
            'precio_clase'=>$precio_clase,
            'activa'=>1
         );
         $table_site=$wpdb->prefix.'clases';
         $wpdb->insert($table_site,$clase); 

    }

    //accede si se pulsa el boton de confirmar modificado
    if(isset($_POST['confirma_modificado'])){
        check_admin_referer( 'gda_modifica_clase', 'gda_modifica_clase_nonce' );

        if(isset($_POST['id_clase'])){
            $id_clase=intval($_POST['id_clase']);
            if(is_int($id_clase)){
                $id=$id_clase;
            }
        
        }
        if(isset($_POST['nombre'])){
            $nombre_clase=sanitize_text_field($_POST['nombre']);
        }
        if(isset($_POST['dias'])){
            $dias=sanitize_text_field($_POST['dias']);
        }
        if(isset($_POST['anyo'])){
            $anyo=intval($_POST['anyo']);
            if ( ! is_int($anyo) ) {
                $anyo = '';
            }else{
 
                if ( strlen( $anyo ) > 4 ) {
                    $anyo = substr( $anyo, 0, 4 );
                }
            }
             
        }
        if(isset($_POST['hora_inicio'])){
            $hora_inicio=intval($_POST['hora_inicio']);
            if ( ! is_int($hora_inicio) ) {
                $hora_inicio = '';
            }else{
                if ( strlen( $hora_inicio ) > 5) {
                    $hora_inicio = substr( $hora_inicio, 0, 5 );
                    $hora_inicio=sanitize_text_field($hora_inicio);
                }
            }
              
            
            
        }
        if(isset($_POST['hora_fin'])){
            $hora_fin=intval($_POST['hora_fin']);
            if ( ! is_int($hora_fin) ) {
                $hora_fin = '';
            }else{
                if ( strlen( $hora_fin ) > 5) {
                    $hora_fin = substr( $hora_fin, 0, 5 );
                    $hora_fin = sanitize_text_field($hora_fin);
                }
            }
              
            
        }
        if(isset($_POST['precio_clase'])){
            $precio_clase=sanitize_text_field($_POST['precio_clase']);
        }
        if(isset($_POST['clase_activa'])){$activa=1;}else{$activa=0;}
        $clase_modificada=array(
            'nombre_clase'=>$nombre_clase,
            'dias'=>$dias,
            'año'=>$anyo,
            'hora_inicio'=>$hora_inicio,
            'hora_fin'=>$hora_fin,
            'precio_clase'=>$precio_clase,
            'activa'=>$activa
        );
        $table_site=$wpdb->prefix.'clases';
        $wpdb->update($table_site,$clase_modificada, array('id'=>$id));

    }
    //accede al confirmar baja
    if(isset($_POST['confirma_baja_clase'])){
        check_admin_referer( 'gda_baja_clase', 'gda_baja_clase_nonce' );

        if(isset($_POST['id_clase'])){
            $id_clase=intval($_POST['id_clase']);
            if(is_int($id_clase)){
                $id=$id_clase;
            }
        
        }
        $table_site=$wpdb->prefix.'clases';
        $wpdb->query( 'SET foreign_key_checks=0' );
        $wpdb->delete($table_site,array('id'=>intval($id)));
        $wpdb->query( 'SET foreign_key_checks=1' );
    }
    //accede al pulsar dar de baja clase
    if(isset($_POST['baja_clase'])){
        if(isset($_POST['id_clase'])){
            $id_clase=intval($_POST['id_clase']);
            if(is_int($id_clase)){
                $id=$id_clase;
            }
        }
        $table_site=$wpdb->prefix.'clases';
        $baja_clase=$wpdb->get_row("SELECT * FROM $table_site WHERE id=$id");
        ?>
        <h1>Baja clase</h1>
        <h2>Va a eliminar esta clase de la base de datos</h2>
        <table>
        <tr>
            <td bgcolor="lightblue" width="10%" align="center">Nombre clase</td>
            <td bgcolor="lightblue" width="10%" align="center">Dias</td>
            <td bgcolor="lightblue" width="10%" align="center">Año</td>
            <td bgcolor="lightblue" width="10%" align="center">Hora inicio</td>
            <td bgcolor="lightblue" width="10%" align="center">Hora fin</td>
            <td bgcolor="lightblue" width="10%" align="center">Precio (€)</td>
            <td bgcolor="lightblue" width="10%" align="center">Activa</td>
        </tr>
        <tr>
            <td width="10%" align="center"><?php echo esc_html($baja_clase->nombre_clase) ?></td>
            <td width="10%" align="center"><?php echo esc_html($baja_clase->dias) ?></td>
            <td width="10%" align="center"><?php echo esc_html($baja_clase->año) ?></td>
            <td width="10%" align="center"><?php echo esc_html($baja_clase->hora_inicio) ?></td>
            <td width="10%" align="center"><?php echo esc_html($baja_clase->hora_fin) ?></td>
            <td width="10%" align="center"><?php echo esc_html($baja_clase->precio_clase) ?></td>
            <form method="post">
            <td width="10%" align="center"><input type="checkbox" name="clase_activa" onclick="return false;"  <?php if(esc_html($baja_clase->activa)==1){echo 'checked="checked"';}?>></td>
                <input type="hidden" name="id_clase" value="<?php echo $baja_clase->id?>">                  
        </tr>
        <tr>
            <td width="10%" align="center"><input type="submit" value="Confirmar baja" name="confirma_baja_clase"></td>
            <td width="10%" align="center"><input type="submit" value="Cancelar" name="cancelar_baja_clase"></td>
        </tr>
            <?php wp_nonce_field( 'gda_baja_clase', 'gda_baja_clase_nonce' ); ?>

            </form> 
        <?php
    }else{
    //accede si se quiere modificar una clase
    if(isset($_POST['modifica'])){
        if(isset($_POST['id_clase'])){
            $id_clase=intval($_POST['id_clase']);
            if(is_int($id_clase)){
                $id=$_POST['id_clase'];
            }
        }
        $table_site=$wpdb->prefix.'clases';
        $modifica_clase=$wpdb->get_row("SELECT * FROM $table_site WHERE id=$id");
        
        ?>
        <h1>Modificar clase</h1>
        <table>
        <tr>
            <td bgcolor="lightblue" width="10%" align="center">Nombre clase</td>
            <td bgcolor="lightblue" width="10%" align="center">Dias</td>
            <td bgcolor="lightblue" width="10%" align="center">Año</td>
            <td bgcolor="lightblue" width="10%" align="center">Hora inicio</td>
            <td bgcolor="lightblue" width="10%" align="center">Hora fin</td>
            <td bgcolor="lightblue" width="10%" align="center">Precio (€)</td>
            <td bgcolor="lightblue" width="10%" align="center">Activa</td>
            <td bgcolor="lightblue" width="10%" align="center">Modificar</td>
        </tr>
        <tr>
            <form method="post">
                <td width="10%" align="center"><input type="text" name="nombre" size="15" maxlength="30" value="<?php echo esc_html($modifica_clase->nombre_clase) ?>"/></td>
                <td width="10%" align="center"><input type="text" name="dias" size="15" maxlength="30" value="<?php echo esc_html($modifica_clase->dias) ?>"/></td>
                <td width="10%" align="center"><input type="text" size="5" maxlength="4" name="anyo" value="<?php echo esc_html($modifica_clase->año) ?>"/></td>
                <td width="10%" align="center"><input type="text" name="hora_inicio"  value="<?php echo esc_html($modifica_clase->hora_inicio) ?>"/></td>
                <td width="10%" align="center"><input type="text" name="hora_fin"  value="<?php echo esc_html($modifica_clase->hora_fin) ?>"/></td>
                <td width="10%" align="center"><input type="number" name="precio_clase"  value="<?php echo esc_html($modifica_clase->precio_clase) ?>" step=".05" min="0"/></td>
                <td width="10%" align="center"><input type="checkbox" name="clase_activa" <?php if(esc_html($modifica_clase->activa)==1){echo 'checked="checked"';}?>></td>   
                <td width="10%" align="center">
                    <input type="hidden" name="id_clase" value="<?php echo esc_html($modifica_clase->id)?>">
                    <input type="submit" value="Confirmar modificado" name="confirma_modificado">
                </td>
                <?php wp_nonce_field( 'gda_modifica_clase', 'gda_modifica_clase_nonce' ); ?>

            </form>
        </tr>

        <?php
    //si no se ha pulsado el boton de modificar se muestra el formulario de altas
    }else{
  ?>
    <h1>Añadir clase</h1>
    <h2>Los campos con * son obligatorios</h2>
    <form  method="post">     
        <table>
            <tr><td align="right">Nombre clase (*):</td><td align="left"><input type="text" name="nombre_clase" size="30" maxlength="20" required/></td></tr>
            <tr><td align="right">Dias: </td><td align="left"><input type="text" name="dias" size="30" maxlength="20" /></td></tr>
            <tr><td align="right">Año: </td><td align="left"><input type="text" name="anyo" size="5" maxlength="4" /></td></tr>
            <tr><td align="right">Hora inicio (*):</td><td align="left"><input  type="time" name="hora_inicio" size="30" maxlength="30" required /></td></tr>
            <tr><td align="right">Hora fin (*):</td><td align="left"><input  type="time" name="hora_fin" size="30" maxlength="30" required /></td></tr>
            <tr><td align="right">Precio clase (€)(*): </td><td align="left"><input  type="number" name="precio_clase" step=".05" min="0" required /></td></tr>
            <tr><td align="center" colspan="2"><input type="submit" value="AÑADIR" name="guardar_clase"></td></tr>	
            <?php wp_nonce_field( 'gda_alta_clase', 'gda_alta_clase_nonce' ); ?>
        </table>
    </form>
    <h1>Clases actuales</h1>
    <table>
        <tr>
            <td bgcolor="lightblue" width="10%" align="center"> Nombre clase</td>
            <td bgcolor="lightblue" width="10%" align="center">Dias</td>
            <td bgcolor="lightblue" width="10%" align="center">Año</td>
            <td bgcolor="lightblue" width="10%" align="center">Hora inicio</td>
            <td bgcolor="lightblue" width="10%" align="center">Hora fin</td>
            <td bgcolor="lightblue" width="10%" align="center">Precio (€)</td>
            <td bgcolor="lightblue" width="10%" align="center">Activa</td>
            <td bgcolor="lightblue" width="10%" align="center">Modificar</td>
            <td bgcolor="lightblue" width="10%" align="center">Dar de baja</td>

        </tr>
        <?php 
            global $wpdb;
            $table_site=$wpdb->prefix.'clases';
            $clases=$wpdb->get_results("SELECT * FROM $table_site");
            if(count($clases)>0){
                foreach($clases as $clase){  
                            
        ?>
            <tr>
                    <td width="10%" align="center"><?php echo esc_html($clase->nombre_clase) ?></td>
                    <td width="10%" align="center"><?php echo esc_html($clase->dias) ?></td>
                    <td width="10%" align="center"><?php echo esc_html($clase->año) ?></td>
                    <td width="10%" align="center"><?php echo esc_html($clase->hora_inicio) ?></td>
                    <td width="10%" align="center"><?php echo esc_html($clase->hora_fin) ?></td>
                    <td width="10%" align="center"><?php echo esc_html($clase->precio_clase) ?></td>                    
                    <form method="post">
                    <td width="10%" align="center"><input type="checkbox" name="clase_activa" onclick="return false;" <?php if(esc_html($clase->activa)==1){echo 'checked="checked"';}?>></td>
                    <td width="10%" align="center">
                        <input type="hidden" name="id_clase" value="<?php echo esc_html($clase->id)?>">
                        <input type="submit" value="Modificar" name="modifica">                
                    </td>
                    <td width="10%" align="center"><input type="submit" value="Dar de baja" name="baja_clase"></td>        
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