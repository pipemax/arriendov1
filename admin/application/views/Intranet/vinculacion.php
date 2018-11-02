<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
        <!-- main content area start -->
        <div class="main-content">
            <!-- page title area start -->
            <div class="page-title-area">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <div class="breadcrumbs-area clearfix">
                            <h4 class="page-title pull-left">Vinculación de Herramienta</h4>
                            <ul class="breadcrumbs pull-left">
                                <li><a href="<?php echo base_url()?>">Inicio</a></li>
                                <li><a href="<?php echo base_url()?>herramientas/">Herramientas</a></li>
                                <li><span>Área de Vinculación</span></li>
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
                        <div class="col-md-4 col-xs-12">
                            <div class="card card-bordered border-primary">
                                <img class="card-img-top img-fluid" src="<?php echo str_replace("admin/","",base_url());?>assets/herramientas/<?php echo $producto[0]->url_foto;?>" alt="image">
                                <div class="card-body">
                                    <h5 class="title" style="text-align:center">CODIGO: <span><?php echo $producto[0]->cod_herramienta;?></span></h5>
                                    <h6 class="title" style="text-align:center" id="<?php echo $producto[0]->cod_herramienta;?>-N"><?php echo $producto[0]->nombre;?></h6>
                                    <p class="card-text" style="text-align:justify">
                                        <?php echo $producto[0]->descripcion;?>
                                    </p>
                                    <p class="card-text" style="text-align:center">
                                        Categoría: <?php echo $producto[0]->categoria;?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8 col-xs-12">                                       
                            <div class="card text-center card-bordered border-success">
                                <div class="card-header bg-success" style="color:white">
                                    Sucursales Vinculadas
                                </div>
                                <div class="card-body">
                                <?php
                                    if($sucursales!=FALSE)
                                    {
                                    ?>  
                                        <table id="tabla_vinculacion" class="table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>                                                
                                                <th class="all">Nombre Sucursal</th>
                                                <th class="desktop">Vinculado</th>
                                                <th class="desktop">Acción</th> 
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                foreach($sucursales as $valor)
                                                {
                                            ?>
                                            <tr>
                                                <td id="<?php echo $valor->cod_sucursal?>-N"><?php echo $valor->nombre;?></td>                                             
                                                <td>
                                                <?php 
                                                    if($valor->vinculado=="SI")
                                                    {
                                                ?>
                                                    <button class="btn btn-success modificar" value="<?php echo $valor->cod_sucursal?>"><span class="fa fa-pencil"></span></button>
                                                <?php
                                                    }
                                                    else
                                                    {
                                                ?>
                                                    <button class="btn btn-danger"><span class="fa fa-remove"></span></button>
                                                <?php
                                                    }
                                                ?>
                                                </td>
                                                <td>
                                                <?php 
                                                    if($valor->vinculado=="SI")
                                                    {
                                                ?>
                                                    <button class="btn btn-danger desvincular" value="<?php echo $valor->cod_sucursal?>"><span class="fa fa-remove"></span> Desvincular</button>
                                                <?php
                                                    }
                                                    else
                                                    {
                                                ?>
                                                    <button class="btn btn-success vincular" value="<?php echo $valor->cod_sucursal?>"><span class="fa fa-check"></span> Vincular</button>                                                    
                                                <?php
                                                    }
                                                ?>                                                    
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
                </div>              
            </div>

            <!-- Modal -->
            <div class="modal fade" id="nueva_vinculacion" tabindex="-1" role="dialog" aria-labelledby="nueva_vinculacionLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="nueva_vinculacionLabel">Nueva Vinculación</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="form_nueva_vinculacion" method="post">
                                <div class="row">
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="sucursal_nueva_vinculacion">Código Sucursal</label>
                                            <input type="text" class="form-control" id="sucursal_nueva_vinculacion" name="sucursal_nueva_vinculacion" readonly>
                                        </div>
                                    </div>     
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="herramienta_nueva_vinculacion">Código Herramienta</label>
                                            <input type="text" class="form-control" id="herramienta_nueva_vinculacion" name="herramienta_nueva_vinculacion" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">                                  
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="stock_nueva_vinculacion">Stock Disponible</label>
                                            <input type="number" class="form-control" id="stock_nueva_vinculacion" name="stock_nueva_vinculacion" placeholder="Ingrese stock del producto" min="0" max="1000" step="1" required>
                                        </div>
                                    </div>      
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="precio_nueva_vinculacion">Precio</label>
                                            <input type="number" class="form-control" id="precio_nueva_vinculacion" name="precio_nueva_vinculacion" placeholder="Ingrese precio de arriendo por día" min="1000" max="10000000" required>
                                        </div>
                                    </div> 
                                </div>                                
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                    <button type="submit" id="vincular_herramienta" class="btn btn-primary">Vincular</button>
                                </div>
                            </form>
                        </div>                        
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="vinculacion_modificacion" tabindex="-1" role="dialog" aria-labelledby="vinculacion_modificacionLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="vinculacion_modificacionLabel">Desvinculación de Herramienta</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="form_vinculacion_modificacion" method="post">
                                <div class="row">
                                    <div class="col-md-3 col-xs-12">
                                        <div class="form-group">
                                            <label for="sucursal_vinculacion_modificacion">Código Sucursal</label>
                                            <input type="text" class="form-control" id="sucursal_vinculacion_modificacion" name="sucursal_vinculacion_modificacion" readonly>
                                        </div>
                                    </div>  
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="nombre_vinculacion_modificacion">Nombre Sucursal</label>
                                            <input type="text" class="form-control" id="nombre_vinculacion_modificacion" name="nombre_vinculacion_modificacion" readonly>
                                        </div>
                                    </div>    
                                    <div class="col-md-3 col-xs-12">
                                        <div class="form-group">
                                            <label for="herramienta_vinculacion_modificacion">Código Herramienta</label>
                                            <input type="text" class="form-control" id="herramienta_vinculacion_modificacion" name="herramienta_vinculacion_modificacion" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">                                  
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="stock_vinculacion_modificacion">Stock Disponible</label>
                                            <input type="number" class="form-control" id="stock_vinculacion_modificacion" name="stock_vinculacion_modificacion" placeholder="Ingrese stock del producto" min="1" max="1000" step="1" required>
                                        </div>
                                    </div>      
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="precio_vinculacion_modificacion">Precio</label>
                                            <input type="number" class="form-control" id="precio_vinculacion_modificacion" name="precio_vinculacion_modificacion" placeholder="Ingrese precio de arriendo por día" min="1000" max="10000000" required>
                                        </div>
                                    </div> 
                                </div>                                
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                    <button type="submit" id="vinculacion_modificacion" class="btn btn-primary">Modificar</button>
                                </div>
                            </form>
                        </div>                        
                    </div>
                </div>
            </div>

            <script>
                $(document).ready(function(){
                    $('#tabla_vinculacion').DataTable({
                        responsive: true,
                        "columns": [
                            { "data": "Nombre" },  
                            { "data": "Vinculacion" },
                            { "data": "Accion" }
                        ],
                        language: {
                            url: "https://cdn.datatables.net/plug-ins/1.10.12/i18n/Spanish.json"
                        },
                        columnDefs: [
                            { "searchable": false, "targets": 2 }
                        ],    
                        bSort: false,
                        bInfo: false
                    });

                    $(".vincular").click(function(){
                        var codigo = this.value;
                        var herramienta = "<?php echo $herramienta;?>";
                        $("#sucursal_nueva_vinculacion").val(codigo);
                        $("#herramienta_nueva_vinculacion").val(herramienta);
                        $("#nueva_vinculacion").modal();
                    });

                    $(".desvincular").click(function(){
                        var codigo = this.value;
                        var nombre_sucursal = $("#"+codigo+"-N").html();
                        var herramienta = "<?php echo $herramienta;?>";
                        var nombre_herramienta = $("#"+herramienta+"-N").html();
                        swal({
                            buttons: {
                                aceptar: {
                                    text: "Aceptar",
                                    value: "yes",
                                },
                                cancel: "Cerrar",
                            },     
                            html: true,
                            title: "¡Confirmación de Desvinculación!",
                            text: "Desvincular la herramienta \n\n"+nombre_herramienta+" \n\nde la sucursal \n\n"+nombre_sucursal,
                            icon: "warning",          
                        })
                        .then((value) => {                                        
                            if(value=='yes'){
                                $.ajax({
                                    url: "<?=base_url();?>desvincular-herramienta",
                                    data: {codigo:codigo,herramienta:herramienta},
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
                                                title: "¡Desvinculación Exitosa!",
                                                text: valor.mensaje,
                                                icon: "success",          
                                            })
                                            .then((value) => {                                        
                                                window.location.reload();                                        
                                            });
                                        }else{
                                            swal("¡Ha ocurrido un error!", valor.mensaje, "error"); 
                                        }
                                    }
                                });
                            }                                      
                        });                        
                    });

                    $(".modificar").click(function(){
                        var codigo = this.value;
                        var nombre_sucursal = $("#"+codigo+"-N").html();
                        var herramienta = "<?php echo $herramienta;?>";
                        $.ajax({
                            url: "<?=base_url();?>obtener-vinculacion",
                            data: {codigo:codigo, herramienta:herramienta},
                            type: 'post',
                            success: function (data){
                                var valor = JSON.parse(data);
                                if(valor!='FALSE'){
                                    $("#sucursal_vinculacion_modificacion").val(valor.cod_sucursal);
                                    $("#nombre_vinculacion_modificacion").val(nombre_sucursal);
                                    $("#herramienta_vinculacion_modificacion").val(valor.cod_herramienta);
                                    $("#stock_vinculacion_modificacion").val(valor.stock);
                                    $("#precio_vinculacion_modificacion").val(valor.precio);
                                    $("#vinculacion_modificacion").modal();
                                }else{
                                    swal("¡Ha ocurrido un error!", "Ha ocurrido un error interno", "error"); 
                                }
                            }
                        });
                    });

                    $("#form_vinculacion_modificacion").submit(function(e){
                        $.ajax({
                            url: "<?=base_url();?>modificar-vinculacion",
                            data: $('#form_vinculacion_modificacion').serialize(),
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
                                        window.location.reload();                                        
                                    });
                                }else{
                                    swal("¡Ha ocurrido un error!", valor.mensaje, "error"); 
                                }
                            }
                        });
                        e.preventDefault();
                    });

                    $("#form_nueva_vinculacion").submit(function(e){
                        $.ajax({
                            url: "<?php echo base_url();?>vincular-herramienta",     
                            data: $("#form_nueva_vinculacion").serialize(),                  
                            type: "post",
                            success: function(data){
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
                                        title: "¡Vinculación Exitosa!",
                                        text: valor.mensaje,
                                        icon: "success",          
                                    })
                                    .then((value) => {                                        
                                        window.location.reload();                                        
                                    });
                                }else{
                                    swal("¡Ha ocurrido un error!", valor.mensaje, "error"); 
                                }
                            }                            
                        });        
                        e.preventDefault();
                    });

                    $("#nueva_vinculacion").on("hidden.bs.modal", function () {
                        $("#form_nueva_vinculacion")[0].reset();
                    });

                    $("#vinculacion_modificacion").on("hidden.bs.modal", function () {
                        $("#form_vinculacion_modificacion")[0].reset();
                    });
                });
            </script>