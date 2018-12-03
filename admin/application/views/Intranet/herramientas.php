<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
        <!-- main content area start -->
        <div class="main-content">
            <!-- page title area start -->
            <div class="page-title-area">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <div class="breadcrumbs-area clearfix">
                            <h4 class="page-title pull-left">Herramientas</h4>
                            <ul class="breadcrumbs pull-left">
                                <li><a href="<?php echo base_url()?>">Inicio</a></li>
                                <li><span>Herramientas</span></li>
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
                                <div class="col-md-4 col-xs-12">
                                    <form action="" method="get">
                                        <div class="row">
                                            <div class="col-md-12 col-xs-12">
                                                <div class="input-group mb-1">
                                                    <input type="text" class="form-control" name="search" placeholder="Buscar código o nombre de herramienta" aria-label="Busqueda" required>
                                                    <div class="input-group-append">
                                                        <button class="btn btn-success" type="submit"><i class="fa fa-search"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-4 col-xs-12">
                                    <div class="row">
                                        <div class="col-md-12 col-xs-12">
                                            <div class="input-group mb-3">                                        
                                                <select class="custome-select" id="categoria" name="categoria" aria-label="Select">
                                                    <option value="">Filtrar por categoría</option>
                                                    <?php 
                                                        foreach($categorias as $cat)
                                                        {
                                                    ?>
                                                    <option value="<?php echo $cat->cod_categoria;?>" selected><?php echo $cat->nombre;?></option>
                                                    <?php
                                                        }
                                                    ?>
                                                </select>
                                                <div class="input-group-append">
                                                    <button class="btn btn-primary" id="categoria_button" type="button"><i class="fa fa-search"></i></button>
                                                </div>
                                            </div>
                                        </div>                                        
                                    </div>
                                </div>
                                <div class="col-md-2 col-xs-12"></div>
                                <div class="col-md-2 col-xs-12">
                                    <button class="btn btn-primary btn-block" data-toggle="modal" data-target="#nueva_herramienta" type="button">
                                        <i class="fa fa-plus"></i>
                                            <span> Nueva Herramienta</span>
                                    </button>
                                </div>
                            </div>
                            <hr>
                            <?php //PHP
                            if($output!=FALSE)
                            {
                                $total = count($output);
                                $sumador = 0;
                                $contador = 1;
                                foreach($output as $producto)
                                { 
                                    if($sumador==0)
                                    {
                            ?>
                            
                            <div class="row">
                                <?php //PHP
                                    }
                                ?>                        
                                    <div class="col-lg-4 col-md-6 mt-5">
                                        <div class="card card-bordered">
                                            <img class="card-img-top img-fluid" src="<?php echo str_replace("admin/","",base_url());?>assets/herramientas/<?php echo $producto->url_foto;?>" alt="image">
                                            <div class="card-body">                                                
                                                <h5 class="title" style="text-align:center">CODIGO: <?php echo $producto->cod_herramienta;?></h5>
                                                <h6 class="title" style="text-align:center" id="<?php echo $producto->cod_herramienta;?>-N"><?php echo $producto->nombre;?></h6>
                                                <p class="card-text" style="text-align:justify">
                                                    <?php echo $producto->descripcion;?>
                                                </p>
                                                <p class="card-text" style="text-align:center">
                                                    Categoría: <?php echo $producto->categoria;?>
                                                </p>
                                                <center>
                                                    <button type="button" class="btn btn-primary modificar" value="<?php echo $producto->cod_herramienta;?>"><i class="fa fa-pencil"></i></button>
                                                    <button type="button" class="btn btn-danger eliminar" value="<?php echo $producto->cod_herramienta;?>"><i class="fa fa-remove"></i></button>
                                                    <a type="button" class="btn btn-success" href="<?php echo base_url()."herramientas/".$producto->cod_herramienta;?>">Vincular/Desvincular</a>
                                                </center>
                                            </div>
                                        </div>
                                    </div>
                                    
                                <?php //PHP
                                    if($sumador==2 OR ($sumador<2 && $contador==$total))
                                    {
                                ?>
                                    </div>
                                <?php //PHP
                                    }
                                ?>
                                <?php //PHP                        
                                    if($sumador==2)
                                    {
                                        $sumador=0;
                                    }
                                    else
                                    {
                                        $sumador++;
                                    }
                                    $contador++;
                                }
                                ?>
                            <br>
                            <div class="row">
                                <div class="col-md-4"></div>
                                <div class="col-md-4">
                                    <ul class="pagination pg-color-border justify-content-center">
                                    <?php 
                                        echo $this->pagination->create_links();
                                    ?>
                                    </ul>
                                </div>
                                <div class="col-md-4"></div>
                            </div>      
                            
                        <?php
                            }
                            else
                            {
                        ?>
                            <div class="alert alert-info">
                                No hay herramientas en la base de datos o no hay resultados de la búsqueda realizada
                            </div>
                        <?php
                            }
                        ?>
                        </div>
                    </div>
                </div>              
            </div>

            <!-- Modal -->
            <div class="modal fade" id="nueva_herramienta" tabindex="-1" role="dialog" aria-labelledby="nueva_herramientaLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="nueva_herramientaLabel">Nueva Herramienta</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="form_nueva_herramienta" method="post">
                                <div class="row">
                                    <div class="col-md-4 col-xs-12">
                                        <div class="form-group">
                                            <label for="codigo_nueva_herramienta">Código</label>
                                            <input type="number" class="form-control" id="codigo_nueva_herramienta" name="codigo_nueva_herramienta" placeholder="Ingrese el código asociado" min="0" max="999999999" required>
                                        </div>
                                    </div>     
                                    <div class="col-md-4 col-xs-12">
                                        <div class="form-group">
                                            <label for="nombre_nueva_herramienta">Nombre</label>
                                            <input type="text" class="form-control" id="nombre_nueva_herramienta" name="nombre_nueva_herramienta" placeholder="Ingrese nombre herramienta" maxlength="50" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-xs-12">
                                        <div class="form-group">
                                            <label for="descripcion_nueva_herramienta">Descripción</label>
                                            <input type="text" class="form-control" id="descripcion_nueva_herramienta" name="descripcion_nueva_herramienta" placeholder="Ingrese descripción" maxlength="200" required>
                                        </div>
                                    </div>  
                                </div>
                                <div class="row">                                  
                                    <div class="col-md-4 col-xs-12">
                                        <div class="form-group">
                                            <label for="categoria_nueva_sucursal">Categoría</label>
                                        <?php   
                                            if($categorias_herramientas!=FALSE)
                                            {
                                        ?>
                                            <select type="text" class="form-control" id="categoria_nueva_sucursal" name="categoria_nueva_sucursal"< required>
                                                <option value="">Seleccione una categoría</option>
                                                <?php 
                                                    foreach($categorias_herramientas as $cat)
                                                    {
                                                ?>
                                                    <option value="<?php echo $cat->cod_categoria;?>" selected><?php echo $cat->nombre;?></option>
                                                <?php        
                                                    }
                                                ?>
                                            </select>
                                        <?php 
                                            }else{
                                        ?>
                                            <input type="text" class="form-control" id="categoria_nueva_sucursal" value="No hay categorías registradas" disabled>
                                        <?php
                                            }
                                        ?>
                                        </div>
                                    </div>      
                                    <div class="col-md-5 col-xs-12">
                                        <div class="form-group">
                                            <label for="foto_nueva_sucursal">Fotografía</label>
                                            <input type="file" class="form-control" id="foto_nueva_sucursal" name="foto_nueva_sucursal" placeholder="Seleccione una foto asociada a la herramienta" accept=".jpg,.png" data-max-size="5000" required>
                                        </div>
                                    </div> 
                                    <div class="col-md-3 col-xs-12">
                                        <div class="form-group">
                                            <label for="foto_n_nueva_sucursal">Nombre Foto</label>
                                            <input type="text" class="form-control" id="foto_n_nueva_sucursal" name="foto_n_nueva_sucursal" placeholder="Nombre para la foto" required></select>
                                        </div>
                                    </div> 
                                </div>                                
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                    <button type="submit" id="registrar_herramienta" class="btn btn-primary">Registrar</button>
                                </div>
                            </form>
                        </div>                        
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="modificar_herramienta" tabindex="-1" role="dialog" aria-labelledby="modificar_herramientaLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modificar_herramientaLabel">Modificar Herramienta</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="form_modificar_herramienta" method="post">
                                <div class="row">
                                    <div class="col-md-3 col-xs-12">
                                        <div class="form-group">
                                            <label for="codigo_modificar_herramienta">Código</label>
                                            <input type="text" class="form-control" id="codigo_modificar_herramienta" name="codigo_modificar_herramienta" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-9 col-xs-12">
                                        <div class="form-group">
                                            <label for="nombre_modificar_herramienta">Nombre</label>
                                            <input type="text" class="form-control" id="nombre_modificar_herramienta" name="nombre_modificar_herramienta" placeholder="Ingrese Nombre" maxlength="50" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="descripcion_modificar_herramienta">Descripcion</label>
                                            <textarea type="text" class="form-control" id="descripcion_modificar_herramienta" name="descripcion_modificar_herramienta"rows="5" required></textarea>
                                        </div>
                                    </div>   
                                </div>
                                <div class="row">
                                    <div class="col-md-4 col-xs-12">
                                        <div class="form-group">
                                            <label for="categoria_modificar_herramienta">Categoría</label>
                                        <?php   
                                            if($categorias_herramientas!=FALSE)
                                            {
                                        ?>
                                            <select type="text" class="form-control" id="categoria_modificar_herramienta" name="categoria_modificar_herramienta"< required>
                                                <option value="">Seleccione una categoría</option>
                                                <?php 
                                                    foreach($categorias_herramientas as $cat)
                                                    {
                                                ?>
                                                    <option value="<?php echo $cat->cod_categoria;?>" selected><?php echo $cat->nombre;?></option>
                                                <?php        
                                                    }
                                                ?>
                                            </select>
                                        <?php 
                                            }else{
                                        ?>
                                            <input type="text" class="form-control" id="categoria_modificar_herramienta" value="No hay categorías registradas" disabled>
                                        <?php
                                            }
                                        ?>
                                        </div>
                                    </div>          
                                </div>                      
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                    <button type="submit" id="modificar_sucursal" class="btn btn-primary">Modificar</button>
                                </div>
                            </form>
                        </div>
                        
                    </div>
                </div>
            </div>

            <script>
                $(document).ready(function(){
                    $("#categoria").val('<?php echo $categoria;?>');
                    $("#categoria_nueva_sucursal").val("");

                    <?php 
                        if($categorias_herramientas==FALSE)
                        {
                    ?>
                        document.getElementById("registrar_herramienta").disabled = true; 
                        document.getElementById("modificar_herramienta").disabled = true; 
                    <?php
                        }
                    ?>

                    $("#form_nueva_herramienta").submit(function(e){
                        var formData = new FormData(this);
                        $.ajax({
                            url: "<?php echo base_url();?>agregar-herramienta",
                            data: formData,                            
                            type: "post",
                            cache: false,
                            fileElementId: 'foto_nueva_sucursal',
                            secureuri: false,
                            contentType: false,
                            processData: false,
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
                                        title: "¡Registro Exitoso!",
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

                    $("#form_modificar_herramienta").submit(function(e){
                        $.ajax({
                            url: "<?=base_url();?>modificar-herramienta",
                            data: $('#form_modificar_herramienta').serialize(),
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
                            text: "¿Está seguro que desea eliminar la herramienta: "+nombre,
                            icon: "warning",          
                        })
                        .then((value) => {                                        
                            if(value=='yes'){
                                $.ajax({
                                    url: "<?=base_url();?>eliminar-herramienta",
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


                    $("#categoria_button").click(function(){
                        var categoria = $("#categoria").val();
                        if(categoria==""){
                            window.location.href = "<?php echo base_url(); ?>herramientas/";
                        }else{
                            arreglar_url("categoria",categoria); 
                        }
                    });

                    function arreglar_url(nombre_item,valor_item){
                        var url = new URI(document.location);
                        if(url.hasQuery(nombre_item)===true){
                            url.setSearch(nombre_item,valor_item);
                        }else{
                            url.addSearch(nombre_item,valor_item)
                        }
                        window.location.href = url.toString();
                    }

                    $("#nueva_herramienta").on("hidden.bs.modal", function () {
                        $("#form_nueva_herramienta")[0].reset();
                        $("#categoria_nueva_sucursal").val("");
                    });

                    $("#modificar_herramienta").on("hidden.bs.modal", function () {
                        $("#form_modificar_herramienta")[0].reset();
                    });

                    $(".modificar").click(function(){
                        var codigo = this.value;
                        $.ajax({
                            url: "<?=base_url();?>obtener-herramienta",
                            data: {codigo:codigo},
                            type: 'post',
                            success: function (data){
                                var valor = JSON.parse(data);
                                console.log(valor);
                                if(valor!='FALSE'){
                                    $("#codigo_modificar_herramienta").val(valor.cod_herramienta);
                                    $("#nombre_modificar_herramienta").val(valor.nombre);
                                    $("#descripcion_modificar_herramienta").val(valor.descripcion);
                                    $("#categoria_modificar_herramienta").val(valor.cod_categoria);
                                    $("#modificar_herramienta").modal('toggle');
                                }else{
                                    swal("¡Ha ocurrido un error!", "Ha ocurrido un error interno", "error"); 
                                }
                            }
                        });
                    });
                });
            </script>