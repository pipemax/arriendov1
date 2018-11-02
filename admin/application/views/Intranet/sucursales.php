<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
        <!-- main content area start -->
        <div class="main-content">
            <!-- page title area start -->
            <div class="page-title-area">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <div class="breadcrumbs-area clearfix">
                            <h4 class="page-title pull-left">Sucursales</h4>
                            <ul class="breadcrumbs pull-left">
                                <li><a href="<?php echo base_url()?>">Inicio</a></li>
                                <li><span>Sucursales</span></li>
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
                                    <button class="btn btn-primary btn-block" data-toggle="modal" data-target="#nueva_sucursal" type="button">
                                        <i class="fa fa-plus"></i>
                                            <span> Nueva Sucursal</span>
                                    </button>
                                </div>
                            </div>
                            <hr>
                        <?php
                            if($output!=FALSE)
                            {
                        ?>                 
                            <table id="tabla_sucursales" class="table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th class="all">Nombre Sucursal</th>
                                        <th class="desktop">Dirección</th>
                                        <th class="desktop">Telefono</th> 
                                        <th class="desktop">Comuna</th>
                                        <th class="all">Acción</th> 
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        foreach($output as $valor){
                                    ?>
                                    <tr>
                                        <td id="<?php echo $valor->cod_sucursal?>-N"><?php echo $valor->nombre;?></td>
                                        <td><?php echo $valor->direccion;?></td>
                                        <td><?php echo $valor->telefono;?></td>                
                                        <td><?php echo $valor->comuna_nombre;?></td>
                                        <td>
                                            <button class="btn btn-success modificar" value="<?php echo $valor->cod_sucursal?>"><span class="fa fa-pencil"></span></button>
                                            <button class="btn btn-danger eliminar" value="<?php echo $valor->cod_sucursal?>"><span class="fa fa-remove"></span></button>
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
                                No hay sucursales registradas en la base de datos.
                            </div>
                        <?php
                            }
                        ?>
                        </div>
                    </div>
                </div>              
            </div>

            <!-- Modal -->
            <div class="modal fade" id="nueva_sucursal" tabindex="-1" role="dialog" aria-labelledby="nueva_sucursalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="nueva_sucursalLabel">Nueva sucursal</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="form_nueva_sucursal" method="post">
                                <div class="row">
                                    <div class="col-md-4 col-xs-12">
                                        <div class="form-group">
                                            <label for="nombre_nueva_sucursal">Nombre</label>
                                            <input type="text" class="form-control" id="nombre_nueva_sucursal" name="nombre_nueva_sucursal" placeholder="Ingrese Nombre" maxlength="50" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-xs-12">
                                        <div class="form-group">
                                            <label for="direccion_nueva_sucursal">Direccion</label>
                                            <input type="text" class="form-control" id="direccion_nueva_sucursal" name="direccion_nueva_sucursal" placeholder="Ingrese dirección" maxlength="50" required>
                                        </div>
                                    </div>   
                                    <div class="col-md-4 col-xs-12">
                                        <div class="form-group">
                                            <label for="telefono_nueva_sucursal">Telefono</label>
                                            <input type="number" class="form-control" id="telefono_nueva_sucursal" name="telefono_nueva_sucursal" placeholder="Ej: 7564525162" min="0" max="999999999" required>
                                        </div>
                                    </div>      
                                </div>
                                <div class="row">                                     
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="region_nueva_sucursal">Región</label>
                                            <?php if($regiones!=FALSE)
                                            {
                                            ?>
                                                <select class="form-control form-control-lg" id="region_nueva_sucursal" name="region_nueva_sucursal" required>
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
                                                <input type="text" class="form-control" value="No hay regiones registradas" disabled>
                                            <?php
                                            }                                            
                                            ?>                                             
                                        </div>
                                    </div> 
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="comuna_nueva_sucursal">Comuna</label>
                                            <select class="form-control form-control-lg" id="comuna_nueva_sucursal" name="comuna_nueva_sucursal" required></select>
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
            <div class="modal fade" id="modificar_sucursal" tabindex="-1" role="dialog" aria-labelledby="modificar_sucursalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modificar_sucursalLabel">Modificar Sucursal</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="form_modificar_sucursal" method="post">
                                <div class="row">
                                    <div class="col-md-4 col-xs-12">
                                        <div class="form-group">
                                            <label for="codigo_modificar_sucursal">Código</label>
                                            <input type="text" class="form-control" id="codigo_modificar_sucursal" name="codigo_modificar_sucursal" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 col-xs-12">
                                        <div class="form-group">
                                            <label for="nombre_modificar_sucursal">Nombre</label>
                                            <input type="text" class="form-control" id="nombre_modificar_sucursal" name="nombre_modificar_sucursal" placeholder="Ingrese Nombre" maxlength="50" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-xs-12">
                                        <div class="form-group">
                                            <label for="direccion_modificar_sucursal">Direccion</label>
                                            <input type="text" class="form-control" id="direccion_modificar_sucursal" name="direccion_modificar_sucursal" placeholder="Ingrese dirección" maxlength="50" required>
                                        </div>
                                    </div>   
                                    <div class="col-md-4 col-xs-12">
                                        <div class="form-group">
                                            <label for="telefono_modificar_sucursal">Telefono</label>
                                            <input type="number" class="form-control" id="telefono_modificar_sucursal" name="telefono_modificar_sucursal" placeholder="Ej: 7564525162" min="0" max="999999999" required>
                                        </div>
                                    </div>     
                                </div>
                                <div class="row">                                     
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="region_modificar_sucursal">Región</label>
                                            <input type="text" class="form-control" id="region_modificar_sucursal" name="region_modificar_sucursal" readonly>                                         
                                        </div>
                                    </div> 
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="comuna_modificar_sucursal">Comuna</label>
                                            <input type="text" class="form-control" id="comuna_modificar_sucursal" name="comuna_modificar_sucursal" readonly>
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

                    $('#tabla_sucursales').DataTable({
                        responsive: true,
                        "columns": [
                            { "data": "Nombres" },
                            { "data": "Apellidos" },  
                            { "data": "Correo" },
                            { "data": "Celular" },
                            { "data": "Accion" }
                        ],
                        language: {
                            url: "https://cdn.datatables.net/plug-ins/1.10.12/i18n/Spanish.json"
                        },
                        columnDefs: [
                            { "searchable": false, "targets": 4 }
                        ],    
                        bSort: false,
                        bInfo: true
                    });

                    $("#form_nueva_sucursal").submit(function(e){
                        $.ajax({
                            url: "<?=base_url();?>agregar-sucursal",
                            data: $('#form_nueva_sucursal').serialize(),
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
                                        window.location.href = "<?php echo base_url()?>sucursales/"                                        
                                    });
                                }else{
                                    swal("¡Ha ocurrido un error!", valor.mensaje, "error"); 
                                }
                            }
                        });
                        e.preventDefault();
                    });

                    $("#form_modificar_sucursal").submit(function(e){
                        $.ajax({
                            url: "<?=base_url();?>modificar-sucursal",
                            data: $('#form_modificar_sucursal').serialize(),
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
                                        window.location.href = "<?php echo base_url()?>sucursales/"                                        
                                    });
                                }else{
                                    swal("¡Ha ocurrido un error!", valor.mensaje, "error"); 
                                }
                            }
                        });
                        e.preventDefault();
                    });

                    $(".eliminar").click(function(){
                        var codigo = this.value;
                        var nombre = $("#"+codigo+"-N").html();
                        swal({
                            buttons: {
                                aceptar: {
                                    text: "Aceptar",
                                    value: "yes",
                                },
                                cancel: "Cerrar",
                            },     
                            title: "¡Confirmación de Borrado!",
                            text: "¿Está seguro que desea eliminar la sucursal: "+nombre,
                            icon: "warning",          
                        })
                        .then((value) => {                                        
                            if(value=='yes'){
                                $.ajax({
                                    url: "<?=base_url();?>eliminar-sucursal",
                                    data: {codigo:codigo},
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
                                                window.location.href = "<?php echo base_url()?>sucursales/"                                        
                                            });
                                        }else{
                                            swal("¡Ha ocurrido un error!", valor.mensaje, "error"); 
                                        }
                                    }
                                });
                            }                                      
                        });
                        
                    });

    
                    $("#region_nueva_sucursal").change(function(){
                        var region= $("#region_nueva_sucursal").val();
                        obtenerComunas(region);
                    });
                    

                    function obtenerComunas(region){
                        $("#comuna_nueva_sucursal").attr('readonly', true);
                        $("#comuna_nueva_sucursal").children().remove();
                        $.ajax({
                            url: "<?=base_url()?>Inicio/obtener_comunas_sucursal",
                            data: {region: region},
                            type: 'post',
                            success: function (data){
                                if(data!=='FALSE'){
                                    var datos = JSON.parse(data);
                                    select = document.getElementById('comuna_nueva_sucursal');  
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
                                    $("#comuna_nueva_sucursal").attr('readonly', false);
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

                    $("#nueva_sucursal").on("hidden.bs.modal", function () {
                        $("#form_nueva_sucursal")[0].reset();
                    });

                    $("#modificar_sucursal").on("hidden.bs.modal", function () {
                        $("#form_modificar_sucursal")[0].reset();
                    });

                    $(".modificar").click(function(){
                        var codigo = this.value;
                        $.ajax({
                            url: "<?=base_url();?>obtener-sucursal",
                            data: {codigo:codigo},
                            type: 'post',
                            success: function (data){
                                var valor = JSON.parse(data);
                                console.log(valor);
                                if(valor!='FALSE'){
                                    $("#codigo_modificar_sucursal").val(valor.cod_sucursal);
                                    $("#nombre_modificar_sucursal").val(valor.nombre);
                                    $("#direccion_modificar_sucursal").val(valor.direccion);
                                    $("#telefono_modificar_sucursal").val(valor.telefono);
                                    $("#region_modificar_sucursal").val(valor.region[0].region_nombre);
                                    $("#comuna_modificar_sucursal").val(valor.comuna);
                                    $("#modificar_sucursal").modal('toggle');
                                }else{
                                    swal("¡Ha ocurrido un error!", "Ha ocurrido un error interno", "error"); 
                                }
                            }
                        });
                    });

                });
            </script>