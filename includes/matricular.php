<?php
    function gda_matricular(){
        global $wpdb;
        


        if(isset($_POST['matricula_clase'])){
            check_admin_referer( 'gda_alta_final_matricula', 'gda_alta_final_matricula_nonce' );
            if(isset($_POST['id_clase'])){
                $id_clase=intval($_POST['id_clase']);
                if(is_int($id_clase)){
                    $id=$id_clase;
                }
            }
            if(isset($_POST['usuarios'])){
                $usuarios=sanitize_text_field($_POST['usuarios']);
            }
            if(isset($_POST['precio_clase'])){
                $precio=sanitize_text_field($_POST['precio_clase']);
            }
            if(isset($_POST['año'])){
                $año=sanitize_text_field($_POST['año']);
            }
            $null=null;
		
            $usuarios_array=explode("-",$usuarios);
		
            foreach($usuarios_array as $u){
		
                $matricula=array(
                    'id_usuario'=>$u,
                    'id_clase'=>$id_clase,
                );
                $table_site=$wpdb->prefix.'asistencia';
                $wpdb->insert($table_site,$matricula);
                
                $pago=array(
                    'id_usuario'=>$u,
                    'id_clase'=>$id_clase,
                    'precio'=>$precio,
                    'año'=>$año,
                );
                $table_site=$wpdb->prefix.'pagos';
                $wpdb->insert($table_site,$pago);    
            }    
        }
        
        if(isset($_POST['matricula'])){    
            check_admin_referer( 'gda_alta_matricula', 'gda_alta_matricula_nonce' );

            if(isset($_POST['usuarios'])){
		$usuario = $_POST['usuarios'];
		if(is_array($usuario)){
			foreach($usuario as $user){
				$usuarios[] = sanitize_text_field($user);
			}		
		}
			
	    }
		
            $usuarios=implode("-",$usuarios);
                ?>
                <h1>Clases actuales</h1>
                <table>
                    <tr>
                        <td bgcolor="lightblue" width="10%" align="center">Nombre clase</td>
                        <td bgcolor="lightblue" width="10%" align="center">Dias</td>
                        <td bgcolor="lightblue" width="10%" align="center">Año</td>
                        <td bgcolor="lightblue" width="10%" align="center">Hora inicio</td>
                        <td bgcolor="lightblue" width="10%" align="center">Hora fin</td>
                        <td bgcolor="lightblue" width="10%" align="center">Precio (€)</td>
                        <td bgcolor="lightblue" width="10%" align="center">Activa</td>
                        <td bgcolor="lightblue" width="10%" align="center">Matricular</td>
            
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
                                
                                <td width="10%" align="center"><input type="checkbox" name="clase_activa" onclick="return false;" <?php if($clase->activa==1){echo 'checked="checked"';}?>></td>
                                <td width="10%" align="center">
                                    <input type="hidden" name="usuarios" value="<?php echo esc_html($usuarios);?>">
                                    <input type="hidden" name="id_clase" value="<?php echo esc_html($clase->id);?>">
                                    <input type="hidden" name="año" value="<?php echo esc_html($clase->año);?>">
                                    <input type="hidden" name="precio_clase" value="<?php echo esc_html($clase->precio_clase);?>">

                                    <input type="submit" value="Matricular" name="matricula_clase">                
                                </td>
                                <?php wp_nonce_field( 'gda_alta_final_matricula', 'gda_alta_final_matricula_nonce' ); ?>

                                </form> 
                        </tr>
                    <?php
                            }
                        }
                    ?>
                </table>
    <?php
            }else{ 
    ?>
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
                <td bgcolor="lightblue" width="10%" align="center">Matricular</td>
            </tr>
        <?php 
                global $wpdb;
                $table_site=$wpdb->prefix.'usuarios';
                $usuarios=$wpdb->get_results("SELECT * FROM $table_site");
                
                if(count($usuarios)>0){
                    foreach($usuarios as $usuario){            
        ?>
                        <form method="post">
                        
                            <tr>
                                <td width="10%" align="center"><?php echo esc_html($usuario->nombre) ?></td>
                                <td width="10%" align="center"><?php echo esc_html($usuario->apellido1) ?></td>
                                <td width="10%" align="center"><?php echo esc_html($usuario->apellido2) ?></td>
                                <td width="5%" align="center"><?php echo esc_html($usuario->año_nacimiento) ?></td>
                                <td width="10%" align="center"><a href="mailto:<?php echo antispambot($usuario->email,1)?>"><?php echo antispambot($usuario->email) ?></a></td>
                                <td width="10%" align="center"><?php echo esc_html($usuario->dni) ?></td>
                                <td width="10%" align="center"><?php echo esc_html($usuario->telefono) ?></td>
                                <td width="10%" align="center"><?php echo esc_html($usuario->direccion) ?></td>
                                <td width="5%" align="center"><input type="checkbox" name="usuario_activo" onclick="return false;" <?php if($usuario->archivado==1){echo 'checked="checked"';}?>></td>  
                                <td width="5%" align="center"><input type="checkbox" name="usuarios[]" value="<?php echo $usuario->id ?>"></td>       
					
                                </td>
                                
                            </tr>
                            
        <?php
                    }
                    ?>
                        <tr><td><input type="submit" value="Matricular seleccionados" name="matricula"></td></tr>
                        <?php wp_nonce_field( 'gda_alta_matricula', 'gda_alta_matricula_nonce' ); ?>

                        </form>
                    <?php
                }
            ?>
            </table>

        <?php
    }
}
?>
