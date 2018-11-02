<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
        <!-- main content area start -->
        <div class="main-content">            
            <!-- page title area start -->
            <div class="page-title-area">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <div class="breadcrumbs-area clearfix">
                            <h4 class="page-title pull-left">Administradores</h4>
                            <ul class="breadcrumbs pull-left">
                                <li><a href="<?php echo base_url()?>">Inicio</a></li>
                                <li><span>Administradores</span></li>
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
                            <div class="row">
                                <div class="col-md-10 col-xs-12"></div>
                                <div class="col-md-2 col-xs-12">
                                    <button class="btn btn-primary btn-block" data-toggle="modal" data-target="#nuevo_administrador" type="button">
                                        <i class="fa fa-plus"></i>
                                            <span> Nuevo administrador</span>
                                    </button>
                                </div>
                            </div>
                            <hr>
                        <?php
                            if($output!=FALSE)
                            {
                        ?>                 
                            <table id="tabla_administradores" class="table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th class="all">Rut</th>
                                        <th class="desktop">Nombres</th>
                                        <th class="desktop">Apellidos</th>
                                        <th class="none">Correo</th> 
                                        <th class="none">Celular</th>
                                        <th class="desktop">Comuna</th>
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
                                        <td><?php echo $valor->comuna_nombre;?></td>
                                        <td>
                                            <button class="btn btn-success modificar" value="<?php echo $valor->rut?>"><span class="fa fa-pencil"></span></button>
                                            <button class="btn btn-danger eliminar" value="<?php echo $valor->rut?>"><span class="fa fa-remove"></span></button>
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
                                No hay administradores registrados en la base de datos.
                            </div>
                        <?php
                            }
                        ?>
                        </div>
                    </div>
                </div>               
            </div>

            <!-- Modal -->
            <div class="modal fade" id="nuevo_administrador" tabindex="-1" role="dialog" aria-labelledby="nuevo_administradorLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="nuevo_administradorLabel">Nuevo administrador</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="form_nuevo_administrador" method="post">
                                <div class="row">
                                    <div class="col-md-4 col-xs-12">
                                        <div class="form-group">
                                            <label for="rut_nuevo_administrador">Rut</label>
                                            <input type="text" class="form-control" id="rut_nuevo_administrador" name="rut_nuevo_administrador" placeholder="Ej: 18123456-6" maxlength="10" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-xs-12">
                                        <div class="form-group">
                                            <label for="nombres_nuevo_administrador">Nombres</label>
                                            <input type="text" class="form-control" id="nombres_nuevo_administrador" name="nombres_nuevo_administrador" placeholder="Ej: Juan Alejandro" maxlength="50" required>
                                        </div>
                                    </div>   
                                    <div class="col-md-4 col-xs-12">
                                        <div class="form-group">
                                            <label for="apellidos_nuevo_administrador">Apellidos</label>
                                            <input type="text" class="form-control" id="apellidos_nuevo_administrador" name="apellidos_nuevo_administrador" placeholder="Ej: Perez Brito" maxlength="50" required>
                                        </div>
                                    </div>      
                                </div>
                                <div class="row">                                     
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="region_nuevo_administrador">Región</label>
                                            <?php if($regiones!=FALSE)
                                            {
                                            ?>
                                                <select class="form-control form-control-lg" id="region_nuevo_administrador" name="region_nuevo_administrador" required>
                                                    <?php 
                                                            foreach($regiones as $item)
                                                            {
                                                    ?>  
                                                            <option value="<?php echo $item->region_id?>"><?php echo $item->region_nombre?></option>
                                                    <?php    
                                                            }
                                                   
                                                    ?>
                                                </select>
                                            <?php
                                            }
                                            else
                                            {
                                            ?> 
                                                <input type="text" class="form-control" value="La empresa no posee sucursales" disabled>
                                            <?php
                                            }                                            
                                            ?>                                             
                                        </div>
                                    </div> 
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="comuna_nuevo_administrador">Comuna</label>
                                            <select class="form-control form-control-lg" id="comuna_nuevo_administrador" name="comuna_nuevo_administrador" required></select>
                                        </div>
                                    </div> 
                                </div>
                                <div class="row">
                                    <div class="col-md-4 col-xs-12">
                                        <div class="form-group">
                                            <label for="correo_nuevo_administrador">Correo</label>
                                            <input type="email" class="form-control" id="correo_nuevo_administrador" name="correo_nuevo_administrador" placeholder="Ej: jperez@ucm.cl" maxlength="50" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-xs-12">
                                        <div class="form-group">
                                            <label for="telefono_nuevo_administrador">Celular</label>
                                            <input type="number" class="form-control" id="telefono_nuevo_administrador" name="telefono_nuevo_administrador" placeholder="Ej: 987654301" max="999999999" min="900000000" required>
                                        </div>
                                    </div> 
                                    <div class="col-md-4 col-xs-12">
                                        <div class="form-group">
                                            <label for="password_nuevo_administrador">Contraseña</label>
                                            <input type="password" class="form-control" id="password_nuevo_administrador" name="password_nuevo_administrador" placeholder="Ej: 123456IZIPIZI" maxlength="50" required>
                                        </div>
                                    </div>                          
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                    <button type="submit" class="btn btn-primary">Registrar</button>
                                </div>
                            </form>
                        </div>
                        
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="modificar_administrador" tabindex="-1" role="dialog" aria-labelledby="modificar_administradorLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modificar_administradorLabel">Modificar administrador</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="form_modificar_administrador" method="post">
                                <div class="row">
                                    <div class="col-md-4 col-xs-12">
                                        <div class="form-group">
                                            <label for="rut_modificar_administrador">Rut</label>
                                            <input type="text" class="form-control" id="rut_modificar_administrador" name="rut_modificar_administrador" placeholder="Ej: 18123456-6" maxlength="10" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-xs-12">
                                        <div class="form-group">
                                            <label for="nombres_modificar_administrador">Nombres</label>
                                            <input type="text" class="form-control" id="nombres_modificar_administrador" name="nombres_modificar_administrador" placeholder="Ej: Juan Alejandro" maxlength="50" required>
                                        </div>
                                    </div>   
                                    <div class="col-md-4 col-xs-12">
                                        <div class="form-group">
                                            <label for="apellidos_modificar_administrador">Apellidos</label>
                                            <input type="text" class="form-control" id="apellidos_modificar_administrador" name="apellidos_modificar_administrador" placeholder="Ej: Perez Brito" maxlength="50" required>
                                        </div>
                                    </div>      
                                </div>
                                <div class="row">                                     
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="region_modificar_administrador">Región</label>
                                            <?php if($regiones!=FALSE)
                                            {
                                            ?>                                    
                                            <select class="form-control form-control-lg" name="region_modificar_administrador" id="region_modificar_administrador">            
                                                <?php 
                                                        foreach($regiones as $item)
                                                        {
                                                ?>  
                                                    <option value="<?php echo $item->region_id?>"><?php echo $item->region_nombre?></option>
                                                <?php    
                                                        }
                                                    
                                                ?>
                                            </select>
                                            <input type="text" class="d-none" id="region_modificar_administrador_hidden">
                                            <?php
                                            }
                                            else
                                            {
                                            ?> 
                                                <input type="text" class="form-control" value="La empresa no posee sucursales" disabled>
                                            <?php
                                            }                                            
                                            ?>                                             
                                        </div>
                                    </div> 
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="comuna_modificar_administrador">Comuna</label>
                                            <select class="form-control form-control-lg" id="comuna_modificar_administrador" name="comuna_modificar_administrador" required></select>
                                            <input type="text" class="d-none" id="comuna_modificar_administrador_hidden">
                                        </div>
                                    </div> 
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="correo_modificar_administrador">Correo</label>
                                            <input type="email" class="form-control" id="correo_modificar_administrador" name="correo_modificar_administrador" placeholder="Ej: jperez@ucm.cl" maxlength="50" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="telefono_modificar_administrador">Celular</label>
                                            <input type="number" class="form-control" id="telefono_modificar_administrador" name="telefono_modificar_administrador" placeholder="Ej: 987654301" max="999999999" min="900000000" required>
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

                    var region = "<?php echo $regiones[0]->region_id;?>";
                    obtenerComunas(region);

                    $('#tabla_administradores').DataTable({
                        responsive: true,
                        "columns": [
                            { "data": "Rut" },
                            { "data": "Nombres" },
                            { "data": "Apellidos" },  
                            { "data": "Correo" },
                            { "data": "Celular" },
                            { "data": "Comuna"},
                            { "data": "Accion" }
                        ],
                        language: {
                            url: "https://cdn.datatables.net/plug-ins/1.10.12/i18n/Spanish.json"
                        },
                        columnDefs: [
                            { "searchable": false, "targets": 6 }
                        ],    
                        bSort: false,
                        bInfo: true
                    });

                    $("#form_nuevo_administrador").submit(function(e){
                        $.ajax({
                            url: "<?=base_url();?>agregar-administrador",
                            data: $('#form_nuevo_administrador').serialize(),
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
                                        title: "¡Registro Exitoso!",
                                        text: valor.mensaje,
                                        icon: "success",          
                                    })
                                    .then((value) => {                                        
                                        window.location.href = "<?php echo base_url()?>administradores"                                        
                                    });
                                }else{
                                    swal("¡Ha ocurrido un error!", valor.mensaje, "error"); 
                                }
                            }
                        });
                        e.preventDefault();
                    });

                    $("#form_modificar_administrador").submit(function(e){
                        $.ajax({
                            url: "<?=base_url();?>modificar-administrador",
                            data: $('#form_modificar_administrador').serialize(),
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
                                        window.location.href = "<?php echo base_url()?>administradores"                                        
                                    });
                                }else{
                                    swal("¡Ha ocurrido un error!", valor.mensaje, "error"); 
                                }
                            }
                        });
                        e.preventDefault();
                    });

                    $(".eliminar").click(function(){
                        var rut = this.value;
                        swal({
                            buttons: {
                                aceptar: {
                                    text: "Aceptar",
                                    value: "yes",
                                },
                                cancel: "Cerrar",
                            },     
                            title: "¡Confirmación de Borrado!",
                            text: "¿Está seguro que desea eliminar al administrador con Rut: "+rut,
                            icon: "warning",          
                        })
                        .then((value) => {                                        
                            if(value=='yes'){
                                $.ajax({
                                    url: "<?=base_url();?>eliminar-administrador",
                                    data: {rut: rut},
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
                                                title: "¡Borrado Exitoso!",
                                                text: valor.mensaje,
                                                icon: "success",          
                                            })
                                            .then((value) => {                                        
                                                window.location.href = "<?php echo base_url()?>administradores"                                        
                                            });
                                        }else{
                                            swal("¡Ha ocurrido un error!", valor.mensaje, "error"); 
                                        }
                                    }
                                });
                            }                                      
                        });
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
                                    url: "<?=base_url();?>contrasena-administrador",
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
                                                window.location.href = "<?php echo base_url()?>administradores"                                        
                                            });
                                        }else{
                                            swal("¡Ha ocurrido un error!", valor.mensaje, "error"); 
                                        }
                                    }
                                });
                            }                      
                        });
                    });

                    
                    $("#region_nuevo_administrador").change(function(){
                        var region= $("#region_nuevo_administrador").val();
                        obtenerComunas(region);
                    });

                    $("#region_modificar_administrador").change(function(){
                        var region = $("#region_modificar_administrador").val();
                        var comuna = $("#comuna_modificar_administrador_hidden").val();
                        var region_h = $("#region_modificar_administrador_hidden").val();
                        var comuna_s = "";
                        if(region==region_h){
                            comuna_s = comuna;
                        }
                        obtenerComunasModificar(region,comuna_s);
                    });
                    
                    function obtenerComunas(region){
                        $("#comuna_nuevo_administrador").attr('readonly', true);
                        $("#comuna_nuevo_administrador").children().remove();
                        $.ajax({
                            url: "<?=base_url()?>Inicio/obtener_comunas",
                            data: {region: region},
                            type: 'post',
                            success: function (data){
                                if(data!=='FALSE'){
                                    var datos = JSON.parse(data);
                                    select = document.getElementById('comuna_nuevo_administrador');  
                                    option = document.createElement('option');
                                    option.value = "";
                                    option.innerHTML = "Seleccione una comuna";
                                    select.append(option);                     
                                    for(var i=0;i<datos.length;i++){
                                        option = document.createElement('option');
                                        option.value = datos[i].comuna_id;
                                        option.innerHTML = datos[i].comuna_nombre;
                                        select.append(option);
                                    }
                                    $("#comuna_nuevo_administrador").attr('readonly', false);
                                }
                            }
                        });
                    }

                    function obtenerComunasModificar(region,comuna = ""){
                        $("#comuna_modificar_administrador").attr('readonly', true);
                        $("#comuna_modificar_administrador").children().remove();
                        $.ajax({
                            url: "<?=base_url()?>Inicio/obtener_comunas",
                            data: {region: region},
                            type: 'post',
                            success: function (data){
                                if(data!=='FALSE'){
                                    var datos = JSON.parse(data);
                                    select = document.getElementById('comuna_modificar_administrador');  
                                    option = document.createElement('option');
                                    option.value = "";
                                    option.innerHTML = "Seleccione una comuna";
                                    select.append(option);                     
                                    for(var i=0;i<datos.length;i++){
                                        option = document.createElement('option');
                                        option.value = datos[i].comuna_id;
                                        option.innerHTML = datos[i].comuna_nombre;
                                        select.append(option);
                                    }
                                    $("#comuna_modificar_administrador").attr('readonly', false);
                                    select.value = comuna
                                }
                            }
                        });
                    }

                    $("#nuevo_administrador").on("hidden.bs.modal", function () {
                        $("#form_nuevo_administrador")[0].reset();
                    });

                    $("#modificar_administrador").on("hidden.bs.modal", function () {
                        $("#form_modificar_administrador")[0].reset();
                    });

                    $(".modificar").click(function(){
                        var rut = this.value;
                        $.ajax({
                            url: "<?=base_url();?>obtener-administrador",
                            data: {rut:rut},
                            type: 'post',
                            success: function (data){
                                var valor = JSON.parse(data);
                                console.log(valor);
                                if(valor!='FALSE'){
                                    var rut = valor.rut;
                                    $("#rut_modificar_administrador").val(rut.substring(0,rut.length-1)+"-"+rut.substring(rut.length-1,rut.length));
                                    $("#nombres_modificar_administrador").val(valor.nombres);
                                    $("#apellidos_modificar_administrador").val(valor.apellidos);
                                    $("#correo_modificar_administrador").val(valor.correo);
                                    $("#telefono_modificar_administrador").val(valor.celular);
                                    $("#region_modificar_administrador").val(valor.region.region_id);
                                    $("#region_modificar_administrador_hidden").val(valor.region.region_id);
                                    obtenerComunasModificar(valor.region.region_id,valor.comuna);
                                    $("#comuna_modificar_administrador").val(valor.comuna);
                                    $("#comuna_modificar_administrador_hidden").val(valor.comuna);
                                    $("#modificar_administrador").modal('toggle');
                                }else{
                                    swal("¡Ha ocurrido un error!", "Ha ocurrido un error interno", "error"); 
                                }
                            }
                        });
                    });
                    
                });
            </script>