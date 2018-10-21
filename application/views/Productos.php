<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div id="all">
        <div id="content">
            <div class="row" style="padding-left: 10px; padding-right: 10px; margin-left: 0px; margin-right: 0px;">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li>
                            <a href="<?php echo base_url(); ?>">Inicio</a>
                        </li>
                        <li>Herramientas</li>
                    </ul>
                </div>

                <div class="col-md-3">
                    <div class="panel panel-default sidebar-menu">
                        <div class="panel-heading">
                            <h3 class="panel-title">Fechas del Arriendo</h3>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-xs-12 col-md-12 products-showing">
                                    <label for="fecha_inicial">Fecha Inicial del Arriendo</label>
                                    <input class="form-control" type="text" id="fecha_inicial" value="<?php echo $this->session->inicio; ?>" readonly>
                                </div>
                                <div class="col-xs-12 col-md-12 products-showing">
                                    <label for="fecha_final">Fecha Final del Arriendo</label>
                                    <input class="form-control" type="text" id="fecha_final" value="<?php echo $this->session->fin; ?>" readonly>
                                </div>
                                <div class="col-xs-12 col-md-12 products-showing">
                                    <label for="guardar_fecha" style="color: white;">.</label>
                                    <button type="button" class="btn btn-success btn-block" id="guardar_fecha">Guardar</button>
                                </div>  
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-default sidebar-menu">
                        <div class="panel-heading">
                            <h3 class="panel-title">Categorias</h3>
                        </div>
                        <div class="panel-body">
                            <ul class="nav nav-pills nav-stacked category-menu">
                                <?php //PHP
                                    foreach($categorias as $cat)
                                    {
                                ?>
                                    <li id="<?php echo $cat->cod_categoria; ?>">
                                        <a href="<?php echo base_url(); ?>productos/?categoria=<?php echo $cat->cod_categoria; ?>"><?php echo $cat->nombre; ?><span class="badge pull-right"><?php echo $cat->contador; ?></span></a>
                                    </li>
                                <?php //PHP
                                    }
                                ?>
                            </ul>
                        </div>
                    </div>

                    <div class="panel panel-default sidebar-menu">
                        <div class="panel-heading">
                            <h3 class="panel-title">Filtros</h3>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12 col-xs-12">
                                    <form action="" method="get">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="search" placeholder="¿Qué herramienta necesitas?" required>
                                            <span class="input-group-btn">
                                                <button class="btn btn-success" type="submit"><i class="fa fa-search"></i></button>
                                            </span>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-12 col-xs-12">                                    
                                    <div class="form-group">
                                        <label for="orden_precio">Ordenar por precio</label>
                                        <select class="form-control" id="orden_precio">
                                            <option value="1">Menor precio</option>
                                            <option value="0">Mayor precio</option>
                                        </select>                                        
                                    </div>
                                </div>
                                <div class="col-md-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="orden_stock">Ordenar por stock</label>
                                        <select class="form-control" id="orden_stock">
                                            <option value="1">Menor stock</option>
                                            <option value="0">Mayor stock</option>
                                        </select>
                                    </div>
                                </div>
                            </div>                          
                        </div>
                    </div>
                </div>

                <div class="col-md-9">
                    <?php
                        if($herramientas!=FALSE)
                        {
                    ?>
                    <div class="box">
                        <h1><?php echo $titulo; ?></h1>
                        <p style="text-align: justify; font-size: 16px;">Para un mejor desplieque de nuestro stock de herramientas, se requiere que seleccione un intervalo de fechas. Esto es necesario para efectuar el arriendo.</p>
                    </div>
                    <?php 
                        }
                        else
                        {                    
                    ?>
                        <?php
                            if($filas==0 && $this->input->get('search')!=null)
                            {
                        ?>
                            <div id="error-page" class="row">
                                <div class="col-md-12 mx-auto">
                                <div class="box text-center py-5">
                                    <h1><i class="fa fa-warning"></i></h1>
                                    <h2 class="text-muted"><?php echo $titulo; ?></h2>
                                    <p class="text-center">Pruebe utilizar el <strong>Formulario de Búsqueda</strong> o <strong>el Navegador</strong> arriba.</p>
                                    <p class="buttons"><a href="<?php echo base_url();?>productos/" class="btn btn-primary"><i class="fa fa-home"></i> Volver a Herramientas</a></p>
                                </div>
                                </div>
                            </div> 
                        <?php
                            }
                            else
                            {
                        ?>
                            <div id="error-page" class="row">
                                <div class="col-md-12 mx-auto">
                                <div class="box text-center py-5">
                                    <h1><i class="fa fa-warning"></i></h1>
                                    <h2 class="text-muted">Lo sentimos - no hay más herramientas que mostrar</h2>
                                    <p class="text-center">Pruebe utilizar el <strong>Formulario de Búsqueda</strong> o <strong>el Navegador</strong> arriba.</p>
                                    <p class="buttons"><a href="<?php echo base_url();?>productos/" class="btn btn-primary"><i class="fa fa-home"></i> Volver a Herramientas</a></p>
                                </div>
                                </div>
                            </div>   
                        <?php 
                            }
                        ?>
                    <?php 
                        }
                    ?>                    
                    
                    <?php //PHP
                        if($herramientas!=FALSE)
                        {
                    ?>

                    
                    
                    <?php //PHP
                        }
                    ?>

                    <?php //PHP
                        if($herramientas!=FALSE)
                        {
                            $total = count($herramientas);
                            $sumador = 0;
                            $contador = 1;
                            foreach($herramientas as $producto)
                            { 
                                if($sumador==0)
                                {
                    ?>
                    
                                    <div class="row products">
                            <?php //PHP
                                }
                            ?>                        
                            <div class="col-md-4 col-sm-6">
                                <div class="product">
                                    <div class="flip-container">
                                        <div class="flipper">
                                            <div class="front">
                                                <a href="<?php echo base_url(); ?>detalle/<?php echo $producto->cod_herramienta; ?>">
                                                    <img src="<?php echo base_url(); ?>assets/herramientas/<?php echo $producto->url_foto; ?>" alt="" class="img-responsive">
                                                </a>
                                            </div>
                                            <div class="back">
                                                <a href="<?php echo base_url(); ?>detalle/<?php echo $producto->cod_herramienta; ?>">
                                                    <img src="<?php echo base_url(); ?>assets/herramientas/<?php echo $producto->url_foto; ?>" alt="" class="img-responsive">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="<?php echo base_url(); ?>detalle/<?php echo $producto->cod_herramienta; ?>" class="invisible">
                                        <img src="<?php echo base_url(); ?>assets/herramientas/<?php echo $producto->url_foto; ?>" alt="" class="img-responsive">
                                    </a>
                                    <div class="text">
                                        <h3><a href="<?php echo base_url(); ?>detalle/<?php echo $producto->cod_herramienta; ?>"><?php echo $producto->nombre; ?></a></h3>
                                        <?php //PHP
                                            if($producto->stock>0)
                                            {  
                                        ?>
                                            <p style="text-align: justify; font-size: 12px;"><?php echo $producto->descripcion; ?>.</p>
                                            <div class="row">
                                                <div class="col-md-6 col-xs-12">
                                                    <center><label for="precio">precio x Día</label></center>
                                                    <p class="price" id="precio">$<?php echo $producto->precio; ?></p>
                                                </div>
                                                <div class="col-md-6 col-xs-12">
                                                    <div class="form-group">
                                                        <center><label for="stock">Unidades</label></center>
                                                        <center><input type="number" style="width:70%;" id="<?php echo $producto->cod_herramienta; ?>-stock" class="form-control" value="1" min="1" max="<?php echo $producto->stock; ?>"></center>
                                                    </div>
                                                </div>
                                            </div>                                    
                                            <p class="buttons">
                                                <a href="<?php echo base_url(); ?>detalle/<?php echo $producto->cod_herramienta; ?>" class="btn btn-default">Ver Detalle</a>
                                                <button type="button" class="btn btn-primary carro" value="<?php echo $producto->cod_herramienta; ?>"><i class="fa fa-shopping-cart"></i>Agregar al Carrito</button>
                                            </p>
                                        <?php //PHP
                                            }
                                            else
                                            { 
                                        ?>
                                            <button class="btn btn-danger btn-block">SIN STOCK</button>
                                            <br>
                                            <div class="alert alert-info" style="text-align: justify; font-size: 16px;">La herramienta no posee stock en el intervalo de fecha establecido.</div>
                                        <?php //PHP
                                            }
                                        ?>
                                    </div>
                                    
                                    <?php //PHP
                                        if($producto->stock>0)
                                        { 
                                    ?>
                                        <div class="ribbon gift">
                                            <div class="theribbon" style="text-align:center">STOCK <?php echo $producto->stock; ?></div>
                                            <div class="ribbon-background"></div>
                                        </div>
                                    <?php //PHP
                                        }
                                        else
                                        {
                                    ?>
                                        <div class="ribbon sale">
                                            <div class="theribbon" style="text-align:center">SIN STOCK</div>
                                            <div class="ribbon-background"></div>
                                        </div>
                                    <?php //PHP
                                        }
                                    ?>
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

                    <div class="pages">
                        <ul class="pagination">
                            <?php 
                                echo $this->pagination->create_links();
                            ?>
                        </ul>
                    </div>

                    <?php //PHP
                        }
                    ?>
                </div>
            </div>
        </div>
<script>
    $(document).ready(function(){
        <?php if($id!=""){ ?>
            $("#<?php echo $id; ?>").addClass("active");
        <?php } ?>

        $("#orden_precio").val("<?php echo $precio;?>");
        $("#orden_stock").val("<?php echo $stock;?>");

        $("#orden_precio").change(function(){
            arreglar_url("precio",$(this)[0].value);           
        });

        $("#orden_stock").change(function(){
            arreglar_url("stock",$(this)[0].value); 
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

        $(".carro").click(function(){
            var codigo_h = $(this)[0].value;
            var cantidad_h = $("#"+codigo_h+"-stock").val();
            $.ajax({
                url: "<?php echo base_url(); ?>carrito-agregar",
                data: {codigo:codigo_h,cantidad:cantidad_h},
                type: 'post',
                success: function (data){
                    var valor = JSON.parse(data);
                    if(valor.estado=='TRUE'){                        
                        swal({
                            title: valor.mensaje,
                            icon: "success",
                            text: "Actualización Exitosa",
                            buttons: {                                
                                inicio: "Ir al Carrito",
                                cancel: "Seguir Cotizando"
                            },
                        }).then(function(buttons){
                            if(buttons=="inicio"){
                                window.location.href = "<?php echo base_url(); ?>carrito";
                            }
                        });
                    }else if(valor.estado=='LOGIN'){                        
                        swal({
                            icon: "warning",
                            text: "Para reservar una herramienta debe iniciar sesión",
                            buttons: {                                
                                inicio: "Iniciar Sesión",
                                registro: "Registrarme",
                                cancel: "Cerrar"
                            },
                        }).then(function(buttons){
                            if(buttons=="inicio"){
                                $("#login-modal").modal();
                            }else if(buttons=="registro"){
                                window.location = "<?php echo base_url(); ?>registrarse";
                            }
                        });
                    }else{
                        swal(valor.mensaje, "Ha ocurrido un error", "error");  
                    }
                }
            });
            
        }); 
    });
</script>