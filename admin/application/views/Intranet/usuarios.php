<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
        <!-- main content area start -->
        <div class="main-content">            
            <!-- page title area start -->
            <div class="page-title-area">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <div class="breadcrumbs-area clearfix">
                            <h4 class="page-title pull-left">Usuarios</h4>
                            <ul class="breadcrumbs pull-left">
                                <li><a href="<?php echo base_url()?>">Inicio</a></li>
                                <li><span>Usuarios</span></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-6 clearfix">
                        <div class="user-profile pull-right">
                            <h4 class="user-name dropdown-toggle" data-toggle="dropdown">Bienvenido <?php echo $this->session->nombres." ".$this->session->apellidos;?> <i class="fa fa-angle-down"></i></h4>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="#">Mensajes</a>
                                <a class="dropdown-item" href="#">Perfil de Usuario</a>
                                <a class="dropdown-item" href="<?php echo base_url()?>salir">Cerrar Sesión</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- page title area end -->
            <div class="main-content-inner">
                <!-- sales report area start -->
                <div class="sales-report-area mt-5 mb-5">
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <hr>
                        <?php
                            if($output!=FALSE)
                            {
                        ?>                 
                            <table id="tabla_usuarios" class="table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th class="all">Rut</th>
                                        <th class="desktop">Nombres</th>
                                        <th class="desktop">Apellidos</th>
                                        <th class="desktop">Correo</th> 
                                        <th class="none">Celular</th>
                                        <th class="none">Dirección</th>
                                        <th class="desktop">Estado</th>
                                        <th class="all">Acción</th> 
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        foreach($output as $valor){
                                    ?>
                                    <tr>
                                        <td><?php echo substr($valor->rut, 0, -1)."-".substr($valor->rut,strlen($valor->rut)-1,strlen($valor->rut));?></td>
                                        <td><?php echo $valor->nombres;?></td>
                                        <td><?php echo $valor->apellidos;?></td>
                                        <td><?php echo $valor->correo;?></td>                
                                        <td><?php echo $valor->celular;?></td>
                                        <td><?php echo $valor->direccion;?></td>
                                        <td>
                                            <?php 
                                                if($valor->estado==1){
                                                    echo "ACTIVO";
                                                }else{
                                                    echo "NO ACTIVO";
                                                }
                                            ?>
                                        </td>
                                        <td>
                                            <button class="btn btn-success modificar" value="<?php echo $valor->rut?>"><span class="fa fa-pencil"></span></button>                                            
                                            <button class="btn btn-primary password" value="<?php echo $valor->rut?>"><span class="fa fa-lock"></span></button>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        <?php
                            }
                            else
                            {
                        ?>
                            <div class="alert alert-info">
                                Aún no hay usuarios que hayan realizado arriendos con su empresa.
                            </div>
                        <?php
                            }
                        ?>
                        </div>
                    </div>
                </div>               
            </div>

            <!-- Modal -->
            <div class="modal fade" id="modificar_usuario" tabindex="-1" role="dialog" aria-labelledby="modificar_usuarioLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modificar_usuarioLabel">Modificar Usuario</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="form_modificar_usuario" method="post">
                                <div class="row">
                                    <div class="col-md-4 col-xs-12">
                                        <div class="form-group">
                                            <label for="rut_modificar_usuario">Rut</label>
                                            <input type="text" class="form-control" id="rut_modificar_usuario" name="rut_modificar_usuario" placeholder="Ej: 18123456-6" maxlength="10" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-xs-12">
                                        <div class="form-group">
                                            <label for="nombres_modificar_usuario">Nombres</label>
                                            <input type="text" class="form-control" id="nombres_modificar_usuario" name="nombres_modificar_usuario" placeholder="Ej: Juan Alejandro" maxlength="50" required>
                                        </div>
                                    </div>   
                                    <div class="col-md-4 col-xs-12">
                                        <div class="form-group">
                                            <label for="apellidos_modificar_usuario">Apellidos</label>
                                            <input type="text" class="form-control" id="apellidos_modificar_usuario" name="apellidos_modificar_usuario" placeholder="Ej: Perez Brito" maxlength="50" required>
                                        </div>
                                    </div>      
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-4 col-xs-12">
                                        <div class="form-group">
                                            <label for="correo_modificar_usuario">Correo</label>
                                            <input type="email" class="form-control" id="correo_modificar_usuario" name="correo_modificar_usuario" placeholder="Ej: jperez@ucm.cl" maxlength="50" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-xs-12">
                                        <div class="form-group">
                                            <label for="telefono_modificar_usuario">Celular</label>
                                            <input type="number" class="form-control" id="telefono_modificar_usuario" name="telefono_modificar_usuario" placeholder="Ej: 987654301" max="999999999" min="900000000" required>
                                        </div>
                                    </div>  
                                    <div class="col-md-4 col-xs-12">
                                        <div class="form-group">
                                            <label for="direccion_modificar_usuario">Direccion</label>
                                            <input type="text" class="form-control" id="direccion_modificar_usuario" name="direccion_modificar_usuario" placeholder="Av. Cancha Rayada 5436" maxlength="50" required>
                                        </div>
                                    </div>                         
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                    <button type="submit" class="btn btn-primary">Modificar</button>
                                </div>
                            </form>
                        </div>
                        
                    </div>
                </div>
            </div>

            <script>
                $(document).ready(function(){

                    $('#tabla_usuarios').DataTable({
                        responsive: true,
                        "columns": [
                            { "data": "Rut" },
                            { "data": "Nombres" },
                            { "data": "Apellidos" },  
                            { "data": "Correo" },
                            { "data": "Celular" },
                            { "data": "Direccion" },
                            { "data": "Estado"},
                            { "data": "Accion" }
                        ],
                        language: {
                            url: "https://cdn.datatables.net/plug-ins/1.10.12/i18n/Spanish.json"
                        },
                        columnDefs: [
                            { "searchable": false, "targets": 7 }
                        ],    
                        bSort: false,
                        bInfo: true
                    });


                    $("#form_modificar_usuario").submit(function(e){
                        $.ajax({
                            url: "<?=base_url();?>modificar-usuario",
                            data: $('#form_modificar_usuario').serialize(),
                            type: 'post',
                            success: function (data){
                                console.log(data);
                                var valor = JSON.parse(data);
                                if(valor.estado=='TRUE'){
                                    swal({
                                        buttons: {
                                            recargar: {
                                                text: "Aceptar",
                                                value: "yes",
                                            },
                                            cancel: "Cerrar",
                                        },     
                                        title: "¡Modificación Exitosa!",
                                        text: valor.mensaje,
                                        icon: "success",          
                                    })
                                    .then((value) => {                                        
                                        window.location.href = "<?php echo base_url()?>usuarios"                                        
                                    });
                                }else{
                                    swal("¡Ha ocurrido un error!", valor.mensaje, "error"); 
                                }
                            }
                        });
                        e.preventDefault();
                    });

                    $(".password").click(function(){
                        var rut = this.value;
                        swal({
                            buttons: true,     
                            dangerMode: true,
                            title: "¡Cambio de contraseña!",
                            text: "Ingrese la nueva contraseña para el Rut: "+rut,
                            icon: "warning",
                            content: {
                                element: "input",
                                attributes: {
                                    placeholder: "Escriba la nueva contraseña",
                                    type: "password",
                                }
                            }        
                        })
                        .then((value) => {   
                            if(value){
                                $.ajax({
                                    url: "<?=base_url();?>contrasena-usuario",
                                    data: {rut: rut,pass: value},
                                    type: 'post',
                                    success: function (data){
                                        var valor = JSON.parse(data);
                                        if(valor.estado=='TRUE'){
                                            swal({
                                                buttons: {
                                                    recargar: {
                                                        text: "Aceptar",
                                                        value: "yes",
                                                    },
                                                    cancel: "Cerrar",
                                                },     
                                                title: "¡Cambio de contraseña exitoso!",
                                                text: valor.mensaje,
                                                icon: "success",          
                                            })
                                            .then((value) => {                                        
                                                window.location.href = "<?php echo base_url()?>usuarios"                                        
                                            });
                                        }else{
                                            swal("¡Ha ocurrido un error!", valor.mensaje, "error"); 
                                        }
                                    }
                                });
                            }                      
                        });
                    });

                    $("#modificar_usuario").on("hidden.bs.modal", function () {
                        $("#form_modificar_usuario")[0].reset();
                    });

                    $(".modificar").click(function(){
                        var rut = this.value;
                        $.ajax({
                            url: "<?=base_url();?>obtener-usuario",
                            data: {rut:rut},
                            type: 'post',
                            success: function (data){
                                var valor = JSON.parse(data);
                                console.log(valor);
                                if(valor!='FALSE'){
                                    var rut = valor.rut;
                                    $("#rut_modificar_usuario").val(rut.substring(0,rut.length-1)+"-"+rut.substring(rut.length-1,rut.length));
                                    $("#nombres_modificar_usuario").val(valor.nombres);
                                    $("#apellidos_modificar_usuario").val(valor.apellidos);
                                    $("#correo_modificar_usuario").val(valor.correo);
                                    $("#telefono_modificar_usuario").val(valor.celular);
                                    $("#direccion_modificar_usuario").val(valor.direccion);
                                    $("#comuna_modificar_usuario_hidden").val(valor.comuna);
                                    $("#modificar_usuario").modal('toggle');
                                }else{
                                    swal("¡Ha ocurrido un error!", "Ha ocurrido un error interno", "error"); 
                                }
                            }
                        });
                    });


                });

                
            </script>