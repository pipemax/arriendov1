<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div id="all">
        <div id="content">
            <div class="row" style="padding-left: 10px; padding-right: 10px; margin-left: 0px; margin-right: 0px;">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li><a href="<?php echo base_url(); ?>">Inicio</a>
                        </li>
                        <li>Mis datos personales</li>
                    </ul>
                </div>
                <div class="col-md-3 col-xs-12">
                    <div class="panel panel-default sidebar-menu">
                        <div class="panel-heading">
                            <h3 class="panel-title">Sección del Cliente</h3>
                        </div>
                        <div class="panel-body">
                            <ul class="nav nav-pills nav-stacked category-menu">
                                <li>
                                    <a href="<?php echo base_url();?>mi-cuenta" class="nav-link active"><i class="fa fa-list"></i> Mis arriendos</a>
                                </li>
                                <li>
                                    <a href="<?php echo base_url();?>mis-datos" class="nav-link"><i class="fa fa-user"></i> Mis datos</a>
                                </li>
                                <li>
                                    <a href="<?php echo base_url(); ?>salir" class="nav-link"><i class="fa fa-sign-out"></i> Salir</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-9 col-xs-12" id="checkout">
                    <div class="box">
                        <?php if($datos!=FALSE)
                        {
                        ?>              
                        <h2>Mis datos personales</h2>                  
                        <h3>Cambiar contraseña</h3>
                        <div class="alert alert-info">Ambas contraseñas deben coincidir para habilitar el botón</div>
                        <form id="cambiar_contrasena">
                            <div class="row">
                                <div class="col-md-4 col-xs-12">
                                    <div class="form-group">
                                        <label for="password_old">Contraseña Antigua</label>
                                        <input id="password_old" type="password" name="password_old" class="form-control" placeholder="Ingrese contraseña antigua" required>
                                    </div>
                                </div>
                            
                                <div class="col-md-4 col-xs-12">
                                    <div class="form-group">
                                        <label for="password_1">Nueva Contraseña</label>
                                        <input id="password_1" type="password" name="password_1" class="form-control" placeholder="Ingrese nueva contraseña" required>
                                    </div>
                                </div>
                                <div class="col-md-4 col-xs-12">
                                    <div class="form-group">
                                        <label for="password_2">Repetir Nueva Contraseña</label>
                                        <input id="password_2" type="password" class="form-control" placeholder="Repetir nueva contraseña" required>
                                    </div>
                                </div>
                                <div class="col-md-12 text-center">
                                    <button type="submit" id="boton_contrasena" class="btn btn-success" disabled><i class="fa fa-save"></i> Cambiar Contraseña</button>
                                </div>
                            </div>
                        </form>
                        <h2 class="mt-5">Datos personales</h2>
                        <form id="actualizar_usuario">
                            <div class="row">
                                <div class="col-md-4 col-xs-12">
                                    <div class="form-group">
                                        <label for="rut">Rut</label>
                                        <input id="rut" type="text" name="rut" class="form-control" value="<?php echo $datos->rut;?>" disabled>
                                    </div>
                                </div>
                                <div class="col-md-4 col-xs-12">
                                    <div class="form-group">
                                        <label for="nombres">Nombres</label>
                                        <input id="nombres" type="text" name="nombres" value="<?php echo $datos->nombres;?>" placeholder="Ingrese los nombres" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-4 col-xs-12">
                                    <div class="form-group">
                                        <label for="apellidos">Apellidos</label>
                                        <input id="apellidos" type="text" name="apellidos" value="<?php echo $datos->apellidos;?>" placeholder="Ingrese los apellidos" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 col-xs-12">
                                    <div class="form-group">
                                        <label for="correo">Correo</label>
                                        <input id="correo" type="email" name="correo" placeholder="Ingrese su correo" class="form-control" value="<?php echo $datos->correo;?>" required>
                                    </div>
                                </div>
                                <div class="col-md-4 col-xs-12">
                                    <div class="form-group">
                                        <label for="direccion">Dirección</label>
                                        <input id="direccion" type="text" name="direccion" value="<?php echo $datos->direccion;?>" placeholder="Ingrese su direccion" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-4 col-xs-12">
                                    <div class="form-group">
                                        <label for="telefono">Telefono o Celular</label>
                                        <input id="telefono" type="number" name="telefono" value="<?php echo $datos->celular;?>" placeholder="Ej: 98675432" class="form-control" min="900000000" max="999999999" required>
                                    </div>
                                </div>
                                <div class="col-md-12 text-center">
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Actualizar</button>
                                </div>
                            </div>
                        </form>
                        <?php 
                        }
                        else
                        {
                        ?>
                            <div id="error-page" class="row">
                                <div class="col-md-12 mx-auto">
                                    <div class="box text-center py-5">
                                        <h1><i class="fa fa-warning"></i></h1>
                                        <h2 class="text-muted">Lo sentimos - ha ocurrido un error</h2>
                                        <p class="text-center">Por favor, comuníquese con nosotros <a href="<?php echo base_url();?>contacto">contacto</a>.</p>
                                        <p class="buttons"><a href="<?php echo base_url();?>mi-cuenta/" class="btn btn-primary"><i class="fa fa-home"></i> Volver mis arriendos</a></p>
                                    </div>
                                </div>
                            </div>   
                        <?php
                        }
                        ?>
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col-md-9 -->
            </div>
        </div>
        <!-- /.container -->
    </div>
        <!-- /#content -->
    <script>
         $(document).ready(function(){
            $('#tabla_arriendos').DataTable({
                responsive: true,
                "columns": [
                    { "data": "Orden" },
                    { "data": "Fecha_Transaccion" },
                    { "data": "Total" },  
                    { "data": "Estado" },
                    { "data": "Fecha_Inicio_Arriendo" },
                    { "data": "Fecha_Fin_Arriendo"},
                    { "data": "Accion" }
                ],
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.10.12/i18n/Spanish.json"
                },
                columnDefs: [
                    { "searchable": false, "targets": [6] }
                ],    
                bSort: false,
                bInfo: true
            });

            $("#password_1").change(function(){
                if(this.value == $("#password_2").val()){
                    document.getElementById("boton_contrasena").disabled = false;
                }else{
                    document.getElementById("boton_contrasena").disabled = true;
                }
            });

            $("#password_2").change(function(){
                if(this.value == $("#password_1").val()){
                    document.getElementById("boton_contrasena").disabled = false;
                }else{
                    document.getElementById("boton_contrasena").disabled = true;
                }
            });

            $("#cambiar_contrasena").submit(function(e){
                var pass1 = $("#password_1").val();
                var pass2 = $("#password_2").val();
                if(pass1 == pass2){
                    $.ajax({
                        url: "<?php echo base_url(); ?>actualizar-contrasena",
                        data: $("#cambiar_contrasena").serialize(),
                        type: "post",
                        success: function(data){
                            var conversion = JSON.parse(data);
                            if(conversion.estado=='TRUE'){
                                swal("Actualización Exitosa",conversion.mensaje,"success").then(function(){
                                    location.reload();
                                });
                            }else if(conversion.estado=='ERROR'){
                                swal("Atención!",conversion.mensaje,"warning").then(function(){
                                    location.reload();
                                });
                            }else{
                                swal("Atención!",conversion.mensaje,"warning");
                            }
                        }
                    });
                }else{
                    swal("Ha ocurrido un error","Las contraseñas no coinciden","warning");
                    document.getElementById("boton_contrasena").disabled = true;
                }
                e.preventDefault();
            });

            $("#actualizar_usuario").submit(function(e){
                $.ajax({
                    url: "<?php echo base_url(); ?>actualizar-datos",
                    data: $("#actualizar_usuario").serialize(),
                    type: "post",
                    success: function(data){
                        var conversion = JSON.parse(data);
                        if(conversion.estado=='TRUE'){
                            swal("Actualización de datos exitosa!",conversion.mensaje,"success").then(function(){
                                location.reload();
                            });
                        }else{
                            swal("Atención!",conversion.mensaje,"warning");
                        }
                    }
                });
                e.preventDefault();
            });
        });
    </script>