<?php

function gda_control_asistencias(){
    global $wpdb;
    if(!current_user_can("manage_options")){
        echo "Tiene que estar logeado para poder ver esto";

    }else{
?>

<h1>Control de asistencias</h1>
<form method="post" class="form">
<table class="table-responsive table">
    <tr>
        <td>Clase:</td>
        <td>
            <select name="lista_clases">
            <?php
            global $wpdb;
            $table_site=$wpdb->prefix.'clases';
                $clases=$wpdb->get_results("SELECT * FROM $table_site");
                if(count($clases)>0){
                    foreach($clases as $clase){    
                        ?>
                        <option value="<?php echo esc_html($clase->id); ?>"><?php echo esc_html($clase->nombre_clase); ?></option>
                        <?php
                    }
                }
                ?>
            </select>
        </td>
    </tr>
    <tr>
        <td>Dia:</td>
        <td><input type="date" name="dia" value="<?php echo date("Y-m-d");?>"></td>
    </tr>  
    <tr>  
        <td class="col-xs-12"><input type="submit" value="Mostrar" name="muestra_clases"></td>
    </tr>
</table>
</form>
<?php
//guardar asistencia
    if(isset($_POST['guarda_asistencia'])){
        check_admin_referer( 'gda_alta_asistencia', 'gda_alta_asistencia_nonce' );
        if(isset($_POST['id_clase'])){
            $id_clase=intval($_POST['id_clase']);
            if(is_int($id_clase)){
                $id=$id_clase;
            }
        }
        if(isset($_POST['dia'])){
            $dia_clase=sanitize_text_field($_POST['dia']);
        }
        if(isset($_POST['usuario'])){
            $usuarios=$_POST['usuario'];
        }
        global $wpdb;
        $dia_clase_formateado=str_replace("/","-",$dia_clase);
        $table_site = $wpdb->prefix.'asistencia';

        
        
        foreach($usuarios as $usuario){
            $sql='SELECT asistencia,id_asistencia,dia FROM '.$table_site.' WHERE id_usuario='.$usuario.' AND id_clase='.$id_clase;
            $resultados = $wpdb->get_row($sql);
            
            if($resultados->dia == $dia_clase_formateado){
                if($resultados['asistencia']=="1"){
                    $asistencia_modificada=array(
                        'asistencia'=>0
                        
                    );
                }else{
                    $asistencia_modificada=array(
                        'asistencia'=>1
                        
                    );
                }
            }else{
                if($resultados->asistencia=="1"){
                    $asistencia_modificada=array(
                        'asistencia'=>0,
                        'dia'=>$dia_clase_formateado
                    );
                }else{
                    $asistencia_modificada=array(
                        'asistencia'=>1,
                        'dia'=>$dia_clase_formateado
                    );
                }
            }
            
            $msg = $wpdb->update($table_site,$asistencia_modificada, array('id_asistencia'=>$resultados->id_asistencia));     
            if($msg == false){
                echo "<h1>FALLO AL ACTUALIZAR</h1>";
            } else {
                echo "<h1>ACTUALIZADO CORRECTAMENTE</h1>";
            }
        } 
            
    }
//mostrar clases  
    if(isset($_POST['muestra_clases'])){
        global $wpdb;
        if(isset($_POST['lista_clases'])){
            $clase_sel=sanitize_text_field($_POST['lista_clases']);
        }
        //$clase_sel devuelve el id de la clase
        $table_site = $wpdb->prefix.'clases';
        $clase_seleccionada=$wpdb->get_row("SELECT * FROM $table_site WHERE id=$clase_sel");
        if(isset($_POST['dia'])){
            $dia_clase=sanitize_text_field($_POST['dia']);
        }

        $fechaOriginal = $dia_clase;
        $fechaModificada = date("d/m/Y", strtotime($fechaOriginal));

        ?>
            <h2>Listado usuarios de <?php echo esc_html($clase_seleccionada->nombre_clase);?> de <?php echo esc_html($clase_seleccionada->hora_inicio);?> a <?php echo esc_html($clase_seleccionada->hora_fin);?> para el dia <?php echo esc_html($fechaModificada);?></h2>
            <table class="table-responsive table">
        <tr>
            <td bgcolor="lightblue" width="10%" align="center">Nombre usuario</td>
            <td bgcolor="lightblue" width="10%" align="center">Primer Apellido</td>
            <td bgcolor="lightblue" width="10%" align="center">Asistencia</td>
        </tr>
        <?php 
            global $wpdb;
            $table_site = $wpdb->prefix.'usuarios';
            $table_site2 = $wpdb->prefix.'asistencia';
            $usuarios=$wpdb->get_results("SELECT id,nombre,apellido1,asistencia,dia FROM $table_site INNER JOIN $table_site2 ON $table_site.id=$table_site2.id_usuario WHERE id_clase=$clase_sel");
        ?>
            <form method="post" >
        <?php
            if(count($usuarios)>0){
                foreach($usuarios as $usuario){                       
        ?>
            <tr>
                    <td width="10%" align="center"><?php echo esc_html($usuario->nombre); ?></td>
                    <td width="10%" align="center"><?php echo esc_html($usuario->apellido1); ?></td>
                    <td width="10%" align="center"><input type="checkbox" class="checkbox" name='usuario[]' value='<?php echo $usuario->id; ?>' <?php if($fechaOriginal==$usuario->dia){if($usuario->asistencia==1){echo "checked='checked'";}}  ?>></td>        
            </tr>
                    
        <?php
                }
            }
        ?>
        </table>
            <input type="hidden" name="id_clase" value="<?php echo esc_html($clase_sel); ?>">
            <input type="hidden" name="dia" value="<?php echo esc_html($fechaOriginal); ?>">

            <input type="submit" value="Guardar" name="guarda_asistencia" class="col-xs-12 col-md-3 btn-primary">
            <input type="button" value="Marcar Todos" name="marca_todos" class="col-xs-12 col-md-3 btn-info">
            <input type="button" value="Desmarcar Todos" name="desmarca_todos" class="col-xs-12 col-md-3     btn-info">
            <?php wp_nonce_field( 'gda_alta_asistencia', 'gda_alta_asistencia_nonce' ); ?>

            </form>

<?php
    }
}
}//cierre function
add_shortcode('tabla_asistencia','gda_control_asistencias');
?>