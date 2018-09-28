<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div id="all">
        <div id="content">
            <div class="row" style="padding-left: 10px; padding-right: 10px; margin-left: 0px; margin-right: 0px;">

                <div class="col-md-12">

                    <ul class="breadcrumb">
                        <li><a href="<?=base_url()?>">Inicio</a>
                        </li>
                        <li> Registrarse/Iniciar Sesion</li>
                    </ul>

                </div>

                <div class="col-md-8">
                    <div class="box">
                        <h1>Cuenta Nueva</h1>

                        <p class="lead">¿Aún no estás registrado en nuestra página?</p>
                        <p>Registrase abre la posibilidad de que arriendes nuestras herramientas, accedas a descuentos y permitas realizar tus proyectos. Un gran equipo trabaja para ti.</p>
                        <hr>

                        <form id="form_registro" method="post">
                            <div class="row">
                                <div class="col-md-6 col-xs-12">                                    
                                    <div class="form-group">
                                        <label for="rut">Rut</label>
                                        <input type="text" class="form-control rut" id="rut" name="rut" minlength="7" maxlength="10" placeholder="Ej: 18485692-3" required>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <div class="form-group">
                                        <label for="nombres">Nombres</label>
                                        <input type="text" class="form-control" id="nombres" name="nombres" maxlength="40" placeholder= "Ej: Alfonso Pablo" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-xs-12">
                                    <div class="form-group">
                                        <label for="apellidos">Apellidos</label>
                                        <input type="text" class="form-control" id="apellidos" name="apellidos" maxlength="40" placeholder= "Ej: Rodriguez Sepúlveda" required>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <div class="form-group">
                                        <label for="correo">Correo</label>
                                        <input type="email" class="form-control" id="correo" name="correo" maxlength="50" placeholder="Ej: micorreo@gmail.com" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-xs-12">                                                   
                                    <div class="form-group">
                                        <label for="direccion">Dirección</label>
                                        <input type="text" class="form-control" id="direccion" name="direccion" maxlength="50" placeholder="Ej: 1 Oriente, 2 Norte #879" required>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <div class="form-group">
                                        <label for="telefono">Teléfono o Celular</label>
                                        <input type="number" class="form-control" id="telefono" name="telefono" max="999999999" placeholder="Ej: 987623456" required>
                                    </div>  
                                </div>
                            </div> 
                            <div class="row">
                                <div class="col-md-6 col-xs-12">
                                    <div class="form-group">
                                        <label for="password1">Contraseña</label>
                                        <input type="password" class="form-control" id="password1" maxlength="100" name="password1" required>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <div class="form-group">
                                        <label for="password2">Confirmar Contraseña</label>
                                        <input type="password" class="form-control" id="password2" maxlength="100" name="password2" required>
                                    </div>
                                </div>
                            </div>                               
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-user-md"></i> Registrarse</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="box">
                        <h1>Iniciar Sesión</h1>

                        <p class="lead">¿Ya es un cliente?</p>
                        <p class="text-muted">Inicie sesión para acceder a nuestras herramientas.</p>

                        <hr>

                        <form class="formulario">
                            <div class="form-group">
                                <label for="rut_sesion2">Rut</label>
                                <input type="text" class="form-control i_rut" id="rut_sesion2" name="rut_sesion" minlength="7" maxlength="10" placeholder="Ej: 19583756-3" required>
                            </div>
                            <div class="form-group">
                                <label for="password2_sesion2">Contraseña</label>
                                <input type="password" class="form-control i_pass" id="password_sesion2" name="password_sesion" maxlength="100" placeholder="Contraseña" required>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-sign-in"></i> Entrar</button>
                            </div>
                        </form>
                    </div>
                </div>
           </div>
            <!-- /.container -->
        </div>
        <!-- /#content -->
        <script>
            $(document).ready(function(){
                $("#form_registro").submit(function(e){
                    if($("#password1").val()!=$("#password2").val()){
                        swal("Las contraseñas no coindicen","Ingrese la contraseña nuevamente","error");
                    }else{
                        $.ajax({
                            url: "<?=base_url()?>registro",
                            data: $("#form_registro").serialize(),
                            type: "post",
                            success: function(data){
                                var conversion = JSON.parse(data);
                                if(conversion.estado=="TRUE"){
                                    swal(conversion.mensaje,"Registro Exitoso","success").then(function(){
                                        location.reload();
                                    });
                                }else{
                                    $('#form_registro')[0].reset();
                                    swal(conversion.mensaje,"Ha ocurrido un incoveniente","error");
                                }
                            }
                        });
                    }
                    e.preventDefault();
                });

                $(".rut").on('keyup',function(){
                    if(this.value.match(/^\d*.\d*$/)){
                        this.value = this.value.replace(".","");
                    }
                    if(this.value!="" && this.value.search("-")==-1){
                        if($.isNumeric(this.value)==false){
                            this.value = "";
                        }
                    }
                });
            });
        </script>