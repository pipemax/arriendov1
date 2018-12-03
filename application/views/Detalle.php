<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div id="all">
        <div id="content">
            <div class="row" style="padding-left: 10px; padding-right: 10px; margin-left: 0px; margin-right: 0px;">

                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li><a href="<?php echo base_url(); ?>">Inicio</a>
                        </li>
                        <li><a href="<?php echo base_url(); ?>productos">Herramientas</a></li>
                        <?php //PHP
                            if($herramienta!=FALSE)
                            { 
                        ?>
                        <li><a href="<?php echo base_url(); ?>detalle/<?php echo $herramienta[0]->cod_herramienta;?>"><?php echo $herramienta[0]->nombre; ?></a></li>
                        <?php 
                            }
                        ?>
                    </ul>
                </div>

                <div class="col-md-3 col-xs-12">
                            
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
                </div>
                
                <?php //PHP
                    if($herramienta!=FALSE)
                    { 
                ?>
                    <div class="col-md-9 col-xs-12">
                        <div class="row" id="productMain">
                            <div class="col-sm-6">
                                <div id="mainImage">
                                    <img src="<?php echo base_url(); ?>assets/herramientas/<?php echo $herramienta[0]->url_foto; ?>" alt="" class="img-responsive" width="100%">
                                </div>

                                <?php //PHP
                                    if($herramienta[0]->stock>0)
                                    {
                                ?>  <?php 
                                        if($herramienta[0]->descuento!=null)
                                    {
                                    ?>
                                        <div class="ribbon new">
                                            <div class="theribbon" style="text-align:center">OFERTA <?php echo $herramienta[0]->descuento;?>%</div>
                                            <div class="ribbon-background"></div>
                                        </div>
                                    <?php
                                        }
                                    ?>
                                    <div class="ribbon gift">
                                        <div class="theribbon" style="text-align:center">STOCK <?php echo $herramienta[0]->stock; ?></div>
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
                                <!-- /.ribbon -->
                            </div>
                            <div class="col-sm-6">
                                <div class="box">
                                    <h2 class="text-center"><?php echo $herramienta[0]->nombre; ?></h2>
                                    <p class="goToDescription"><a href="#details" class="scroll-to">Presionar para ver más detalles</a>
                                    </p>
                                    <button type="button" class="btn btn-info btn-block" readonly><?php echo $herramienta[0]->empresan; ?></button>
                                    <button type="button" class="btn btn-danger btn-block" readonly>SUCURSAL <?php echo $herramienta[0]->nombres; ?></button>                                    
                                    
                                    <?php //PHP
                                        if($herramienta[0]->stock>0)
                                        {
                                    ?>
                                         <?php
                                            if($herramienta[0]->descuento!=null)
                                            {
                                        ?>
                                            <div class="row">
                                                <div class="col-md-6 col-xs-12">
                                                    <br>
                                                    <center style="font-size: 18px;">ANTES</center>
                                                    <p class="price" style="margin-top: 5px; margin-bottom: 10px;"><del>$<?php echo number_format($herramienta[0]->precio, 0,'.', '.'); ?></del></p>
                                                </div>
                                                <div class="col-md-6 col-xs-12">
                                                    <br>
                                                    <center style="font-size: 18px;">AHORA</center>
                                                    <p class="price" style="margin-top: 5px; margin-bottom: 10px;">$<?php echo number_format(round($herramienta[0]->precio - $herramienta[0]->precio*($herramienta[0]->descuento/100)), 0,'.', '.'); ?></p>
                                                </div>
                                            </div>
                                        <?php 
                                            }
                                            else
                                            {
                                        ?>
                                            <p class="price" id="precio">$<?php echo number_format($herramienta[0]->precio, 0,'.', '.'); ?></p>
                                        <?php
                                            }
                                        ?>
                                        <center>
                                            <div class="form-group" style="width: 50%">
                                                <label for="cantidad">Cantidad</label>
                                                <input type="number" id="cantidad" class="form-control" min="1" max="<?php echo $herramienta[0]->stock; ?>" value="1" required>
                                            </div>
                                        </center>
                                    <?php //PHP
                                        }
                                        else
                                        {
                                    ?>
                                        <center><button class="btn btn-danger btn-block">SIN STOCK</button>
                                        <br>
                                        <div class="alert alert-info" style="text-align: justify; font-size: 16px;">La herramienta no posee stock en el intervalo de fecha establecido.</div></center>
                                    <?php //PHP
                                        } 
                                    ?>

                                    <p class="text-center buttons"> 
                                        <?php //PHP
                                            if($herramienta[0]->stock>0)
                                            {
                                        ?>
                                            <button class="btn btn-primary" value="<?php echo $herramienta[0]->cod_herramienta."-".$herramienta[0]->cod_sucursal."-".$herramienta[0]->empresa;?>" id="carrito-detalle"><i class="fa fa-shopping-cart"></i> Agregar al Carro</button> 
                                        <?php //PHP
                                            }
                                        ?>
                                        <a href="<?php echo base_url(); ?>productos" class="btn btn-default"><i class="fa fa-arrow-circle-left"></i> Volver a las Herramientas</a>
                                    </p>
                                </div>
                            </div>
                        </div>


                        <div class="box" id="details">
                            <p>
                                <h4>Detalles</h4>
                            <p><?php echo $herramienta[0]->descripcion; ?></p>
                        </div>
                    </div>
                <?php //PHP
                    }
                    else
                    {
                ?>
                    
                    <div class="col-lg-9">
                        <div class="box text-center py-5">
                            <h1><i class="fa fa-warning"></i></h1>
                            <h2 class="text-muted">Lo sentimos - no hay registros de esta herramienta en la sucursal seleccionada</h2>
                            <p class="text-center">Esto puede ser un error nuestro, por favor, comuníquese con nosotros <a href="<?php echo base_url();?>contacto">contacto</a>.</p>
                            <p class="buttons"><a href="<?php echo base_url();?>productos/" class="btn btn-primary"><i class="fa fa-home"></i> Volver a los productos</a></p>
                        </div>
                    </div> 
                <?php
                    }
                ?>
            </div>
        </div>

        <script>
            $(document).ready(function(){
                $("#carrito-detalle").click(function(){
                    var array = $(this)[0].value;
                    var datos = array.split("-");
                    var codigo_h = datos[0];
                    var sucursal = datos[1];
                    var empresa = datos[2];
                    var cantidad_h = $("#cantidad").val();
                    $.ajax({
                        url: "<?php echo base_url(); ?>carrito-agregar",
                        data: {codigo:codigo_h,cantidad:cantidad_h,empresa: empresa,sucursal: sucursal},
                        type: 'post',
                        success: function (data){
                            var valor = JSON.parse(data);
                            if(valor.estado=='TRUE'){                        
                                swal({
                                    title: valor.mensaje,
                                    icon: "success",
                                    text: "Se ha agregado una herramienta al carrito",
                                    buttons: {                                
                                        inicio: "Ir al Carrito",
                                        cancel: "Cerrar Ventana"
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