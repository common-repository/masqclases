<?php
function gda_control_pagos(){
    global $wpdb;
    ?>
    <form method="post" class="form-horizontal">
        <select name="clase">
            <?php
                $table_site=$wpdb->prefix.'clases';
                $clases=$wpdb->get_results("SELECT id,nombre_clase FROM $table_site");

                $mes=date('n');
                $nom_mes=array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');
                $mes_nombre=$nom_mes[$mes-1];

                foreach($clases as $clase){
                    ?>
                    <option value="<?php echo esc_html($clase->id); ?>"><?php echo $clase->nombre_clase; ?></option>
                    <?php
                }
                ?>
        </select>

            <select name="mes" id="mes_pago">
            <?php
                foreach($nom_mes as $v=>$nm){
                    if($mes==$v+1){
                    ?>
                        <option value="<?php echo esc_html($nm); ?>" selected="selected"><?php echo esc_html($nm); ?></option>
                    <?php
                    }else{
                    ?>
                        <option value="<?php echo esc_html($nm); ?>"><?php echo esc_html($nm); ?></option>
                    <?php
                    }
                }
                ?>
            </select>
        <input type="submit" value="Seleccionar clase" name="selec_clase" class="btn-primary">
        <?php wp_nonce_field( 'gda_alta_pago', 'gda_alta_pago_nonce' ); ?>
    </form>
    <br>
    <?php
    if(isset($_POST['guarda_pago'])){
        check_admin_referer( 'gda_guarda_pago', 'gda_guarda_pago_nonce' );

        if(isset($_POST['mes'])){
            $mes=sanitize_text_field($_POST['mes']);
            $nom_mes=array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');
            foreach($nom_mes as $c => $n){
                if($n == $mes){
                    $num_mes=$c+1;
                    $nombre_mes=$n;
                }
            }
        }
        if(isset($_POST['nombre'])){
            $nombre=sanitize_text_field($_POST['nombre']);
        }
        if(isset($_POST['apellido'])){
            $apellido=sanitize_text_field($_POST['apellido']);
        }

        if(isset($_POST['precio'])){
            $precio=sanitize_text_field($_POST['precio']);
        }
        
        if(isset($_POST['id_pago'])){
            $id_pago=intval($_POST['id_pago']);
            if(is_int($id_pago)){
                $id_pago=$id_pago;
            }
        }
        if(isset($_POST['comentario'])){
            $comentario=sanitize_text_field($_POST['comentario']);
        }
        if(isset($_POST['nombre_clase'])){
            $nombre_clase=sanitize_text_field($_POST['nombre_clase']);
        }
        if(isset($_POST['email'])){
            $email=sanitize_email($_POST['email']);
        }
        
        global $wpdb;

        $cambio_pago=array(
            'mes'=>$num_mes,
            'precio'=>$precio,
            'pagado'=>1,
            'comentario'=>$comentario
        );
        $table_site=$wpdb->prefix.'pagos';
        $result=$wpdb->update($table_site,$cambio_pago,array('id_pago'=>$id_pago));
        if($result === false){
            echo "<h3>No se han podido modificar los datos en la base de datos.</h3>";
        }else{
            echo "<h3>Clase pagada. Se le enviará un email para avisarle del pago.</h3>";
            $msg="Hola ". esc_html($nombre)." ". esc_html( $apellido) .",\n Se ha pagado la clase de ". esc_html( $nombre_clase)." por ". esc_html( $precio )." € para el mes de". esc_html( $nombre_mes )."\n Comentario extra: ". esc_html($comentario);
            $msg = wordwrap($msg,70);

            $email_options=get_option('gda_options');
            $email_propio=$email_options['email'];
            $msg2="El usuario ". esc_html( $nombre )." ". esc_html($apellido)." ha pagado la clase de ". esc_html($nombre_clase)." por ". esc_html($precio)." € para el mes de ". esc_html($nombre_mes)." \n Comentario extra: ". esc_html($comentario);
            $msg2 = wordwrap($msg,70);

            mail("$email_propio","Pago de clase",$msg2);

            mail("$email","Recibo de pago de clase",$msg);
        }
    }

    if(isset($_POST['selec_clase'])){
        check_admin_referer( 'gda_alta_pago', 'gda_alta_pago_nonce' );

        if(isset($_POST['clase'])){
            $id_clase=sanitize_text_field($_POST['clase']);
        }
        if(isset($_POST['mes'])){
            $mes=sanitize_text_field($_POST['mes']);
            $nom_mes=array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');
            foreach($nom_mes as $c => $n){
                if($n == $mes){
                    $num_mes=$c+1;
                    $nombre_mes=$n;
                }
            }
        }

        global $wpdb;  
        $table_site=$wpdb->prefix.'clases';
        $clases=$wpdb->get_row("SELECT nombre_clase,año FROM $table_site WHERE id='$id_clase'");    
            $clase=$clases->nombre_clase;
            $año=$clases->año;
        echo "<h2>Pagos de la clase de ".esc_html($clase)." para el mes de ".esc_html($nombre_mes)." del año ".esc_html($año)."</h2>";
        $table_site2=$wpdb->prefix.'usuarios';
        $table_site3=$wpdb->prefix.'pagos';
        $usuarios=$wpdb->get_results("SELECT nombre, nombre_clase, dni, apellido1, email, pagado, id_pago, precio_clase, $table_site.año,$table_site3.mes,precio,comentario FROM $table_site2 INNER JOIN $table_site3 ON $table_site2.id = $table_site3.id_usuario INNER JOIN $table_site ON $table_site3.id_clase = $table_site.id WHERE id_clase = '$id_clase'");
        
        ?>
        
        <table class="table-responsive-sm table">
        <tr>
            <td bgcolor="lightblue" width="10%" align="center">DNI</td>
            <td bgcolor="lightblue" width="10%" align="center">Nombre</td>
            <td bgcolor="lightblue" width="10%" align="center">Apellido</td>
            <td bgcolor="lightblue" width="10%" align="center">Email</td>
            <td bgcolor="lightblue" width="10%" align="center">Precio clase</td>
            <td bgcolor="lightblue" width="10%" align="center">Pagado</td>
            <td bgcolor="lightblue" width="10%" align="center">Comentario</td>
            <td bgcolor="lightblue" width="10%" align="center">Pagar</td>
        </tr>
        <?php
        foreach($usuarios as $us){
            ?>
                <tr>
                <form method="post" class="form">
                    <td align="center"><?php echo esc_html($us->dni); ?></td>
                    <td align="center"><?php echo esc_html($us->nombre); ?></td>
                        <input type="hidden" name="nombre" value="<?php echo esc_html($us->nombre); ?>">
                    <td align="center"><?php echo esc_html($us->apellido1); ?></td>
                        <input type="hidden" name="apellido" value="<?php echo esc_html($us->apellido1); ?>">
                    <td align="center"><?php echo esc_html($us->email); ?></td>
                        <input type="hidden" name="email" value="<?php echo esc_html($us->email ); ?>">
                       <input type="hidden" name="mes" value="<?php echo esc_html($nombre_mes); ?>">
                    <td align="center"><input class="form-control" type="number" name="precio" min="0" step=".05" value="<?php if(($us->pagado==1) && ($us->mes==$num_mes)){echo esc_html($us->precio);}else{ echo esc_html($us->precio_clase);}?>"></td>
                    <td align="center"><input type="checkbox" name="pago" value=1 <?php if(($us->pagado==1) && ($us->mes==$num_mes)){echo 'checked="checked" onclick="return false"';} ?> required></td>
                        <input type="hidden" name="id_pago" value="<?php echo esc_html($us->id_pago); ?>">
                        <input type="hidden" name="nombre_clase" value="<?php echo esc_html($us->nombre_clase); ?>">
                    <td align="center"><textarea name="comentario" id="comentario" cols="30" rows="1"><?php if(($us->pagado==1) && ($us->mes==$num_mes)){echo esc_html($us->comentario);} ?></textarea></td>
                    <td align="center"><input type="submit" name="guarda_pago" value="Pagar" <?php if(($us->pagado==1) && ($us->mes==$num_mes)){echo 'disabled=true';} ?>></td>
                    <?php wp_nonce_field( 'gda_guarda_pago', 'gda_guarda_pago_nonce' ); ?>
                </form>
                </tr>
            <?php
        }
        ?>
        </table>
        
        <?php
        
    }


}//end function
add_shortcode('tabla_pagos','gda_control_pagos');

?>
