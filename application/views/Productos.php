<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div id="all">
        <div id="content">
            <div class="row" style="padding-left: 10px; padding-right: 10px; margin-left: 0px; margin-right: 0px;">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li><a href="<?=base_url()?>">Inicio</a>
                        </li>
                        <li>Herramientas</li>
                    </ul>
                </div>

                <div class="col-md-3">
                    <!-- *** MENUS AND FILTERS ***
 _________________________________________________________ -->
                    <div class="panel panel-default sidebar-menu">

                        <div class="panel-heading">
                            <h3 class="panel-title">Categorias</h3>
                        </div>

                        <div class="panel-body">
                            <ul class="nav nav-pills nav-stacked category-menu">
                                <?php 
                                    foreach($Categorias as $cat){
                                ?>
                                    <li id="<?=$cat->cod_categoria?>">
                                        <a href="<?=base_url()?>productos/1/<?=$cat->cod_categoria?>"><?=$cat->nombre?><span class="badge pull-right"><?=$cat->contador?></span></a>
                                    </li>
                                <?php 
                                    }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-md-9">
                    <div class="box">
                        <h1><?=$Titulo?></h1>
                        <p style="text-align: justify; font-size: 16px;">Para un mejor desplieque de nuestro stock de herramientas, se requiere que seleccione un intervalo de fechas. Esto es necesario para efectuar el arriendo.</p>
                    </div>
                    
                    <?php 
                        if($Herramientas!=FALSE){
                    ?>

                    <!--<div class="box info-bar">
                        <div class="row">
                            <div class="col-sm-12 col-md-4 products-showing">
                                Showing <strong>12</strong> of <strong>25</strong> products
                            </div>

                            <div class="col-sm-12 col-md-8  products-number-sort">
                                <div class="row">
                                    <form class="form-inline">
                                        <div class="col-md-6 col-sm-6">
                                            <div class="products-number">
                                                <strong>Show</strong>  <a href="#" class="btn btn-default btn-sm btn-primary">12</a>  <a href="#" class="btn btn-default btn-sm">24</a>  <a href="#" class="btn btn-default btn-sm">All</a> products
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6">
                                            <div class="products-sort-by">
                                                <strong>Sort by</strong>
                                                <select name="sort-by" class="form-control">
                                                    <option>Price</option>
                                                    <option>Name</option>
                                                    <option>Sales first</option>
                                                </select>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>-->

                    <div class="box info-bar">
                        <div class="row">
                            <div class="col-sm-12 col-md-4 products-showing">
                                <label for="fecha_inicial">Fecha Inicial del Arriendo</label>
                                <input class="form-control" type="text" id="fecha_inicial" value="<?=$this->session->inicio?>" readonly>
                            </div>
                            <div class="col-sm-12 col-md-4 products-showing">
                                <label for="fecha_final">Fecha Final del Arriendo</label>
                                <input class="form-control" type="text" id="fecha_final" value="<?=$this->session->fin?>" readonly>
                            </div>
                            <div class="col-sm-12 col-md-4 products-showing">
                                <label for="guardar_fecha" style="color: white;">.</label>
                                <button type="button" class="btn btn-success btn-block" id="guardar_fecha">Guardar</button>
                            </div>                            
                        </div>
                    </div>
                    <?php 
                        }
                    ?>

                    <?php 
                        if($Herramientas!=FALSE){
                    ?>
                    <?php 
                        $total = count($Herramientas);
                        $sumador = 0;
                        $contador = 1;
                        foreach($Herramientas as $producto){                            
                    ?>
                    <?php if($sumador==0){ ?>
                    <div class="row products">
                    <?php } ?>                        
                        <div class="col-md-4 col-sm-6">
                            <div class="product">
                                <div class="flip-container">
                                    <div class="flipper">
                                        <div class="front">
                                            <a href="<?=base_url()?>detalle/<?=$producto->cod_herramienta?>">
                                                <img src="<?=base_url()?>assets/herramientas/<?=$producto->url_foto?>" alt="" class="img-responsive">
                                            </a>
                                        </div>
                                        <div class="back">
                                            <a href="<?=base_url()?>detalle/<?=$producto->cod_herramienta?>">
                                                <img src="<?=base_url()?>assets/herramientas/<?=$producto->url_foto?>" alt="" class="img-responsive">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <a href="<?=base_url()?>detalle/<?=$producto->cod_herramienta?>" class="invisible">
                                    <img src="<?=base_url()?>assets/herramientas/<?=$producto->url_foto?>" alt="" class="img-responsive">
                                </a>
                                <div class="text">
                                    <h3><a href="<?=base_url()?>detalle/<?=$producto->cod_herramienta?>"><?=$producto->nombre?></a></h3>
                                    <?php if($producto->stock>0){ ?>
                                        <p style="text-align: justify; font-size: 12px;"><?=$producto->descripcion?>.</p>
                                        <div class="row">
                                            <div class="col-md-6 col-xs-12">
                                                <center><label for="precio">precio x Día</label></center>
                                                <p class="price" id="precio">$<?=$producto->precio?></p>
                                            </div>
                                            <div class="col-md-6 col-xs-12">
                                                <div class="form-group">
                                                    <center><label for="stock">Cantidad</label></center>
                                                    <center><input type="number" style="width:70%;" id="<?=$producto->cod_herramienta?>-stock" class="form-control" value="1" min="1" max="<?=$producto->stock?>"></center>
                                                </div>
                                            </div>
                                        </div>                                    
                                        <p class="buttons">
                                            <a href="<?=base_url()?>detalle/<?=$producto->cod_herramienta?>" class="btn btn-default">Ver Detalle</a>
                                            <button type="button" class="btn btn-primary carro" value="<?=$producto->cod_herramienta?>"><i class="fa fa-shopping-cart"></i>Agregar al Carrito</button>
                                        </p>
                                    <?php }else{ ?>
                                        <button class="btn btn-danger btn-block">SIN STOCK</button>
                                        <br>
                                        <div class="alert alert-info" style="text-align: justify; font-size: 16px;">La herramienta no posee stock en el intervalo de fecha establecido.</div>
                                    <?php } ?>
                                </div>
                                
                                <?php if($producto->stock>0){ ?>
                                    <div class="ribbon gift">
                                        <div class="theribbon" style="text-align:center">STOCK <?=$producto->stock?></div>
                                        <div class="ribbon-background"></div>
                                    </div>
                                <?php }else{ ?>
                                    <div class="ribbon sale">
                                        <div class="theribbon" style="text-align:center">SIN STOCK</div>
                                        <div class="ribbon-background"></div>
                                    </div>
                                <?php } ?>
                                <!-- /.text -->
                            </div>
                            <!-- /.product -->
                        </div>
                    <?php if($sumador==2 || ($sumador<2 && $contador==$total)){ ?>
                    </div>
                    <?php } ?>
                    <?php                         
                        if($sumador==2){
                            $sumador=0;
                        }else{
                            $sumador++;
                        }
                        $contador++;
                        }
                    ?>
                    <!-- /.products -->

                    <div class="pages">
                        <ul class="pagination">
                            <li><a href="#">&laquo;</a>
                            </li>
                            <li class="active"><a href="#">1</a>
                            </li>
                            <li><a href="#">2</a>
                            </li>
                            <li><a href="#">3</a>
                            </li>
                            <li><a href="#">4</a>
                            </li>
                            <li><a href="#">5</a>
                            </li>
                            <li><a href="#">&raquo;</a>
                            </li>
                        </ul>
                    </div>

                    <?php 
                        }
                    ?>
                </div>
                <!-- /.col-md-9 -->
            </div>
            <!-- /.container -->
        </div>
        <!-- /#content -->
<script>
    $(document).ready(function(){
        <?php if($ID!=""){ ?>
            $("#<?=$ID?>").addClass("active");
        <?php } ?>

        $(".carro").click(function(){
            var codigo_h = $(this)[0].value;
            var cantidad_h = $("#"+codigo_h+"-stock").val();
            $.ajax({
                url: "<?=base_url()?>carrito-agregar",
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
                                window.location.href = "<?=base_url()?>carrito";
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
                                window.location = "<?=base_url()?>registrarse";
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