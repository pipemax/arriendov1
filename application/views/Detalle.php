<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div id="all">
        <div id="content">
            <div class="row" style="padding-left: 10px; padding-right: 10px; margin-left: 0px; margin-right: 0px;">

                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li><a href="<?=base_url()?>">Inicio</a>
                        </li>
                        <li><a href="<?=base_url()?>productos/1">Herramientas</a></li>
                        <li>Hola</li>
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
                
                <?php if($Herramienta!=FALSE){ ?>
                    <div class="col-md-9">
                        <div class="row" id="productMain">
                            <div class="col-sm-6">
                                <div id="mainImage">
                                    <img src="<?=base_url()?>assets/herramientas/<?=$Herramienta[0]->url_foto?>" alt="" class="img-responsive" width="100%">
                                </div>

                                <?php if($Herramienta[0]->stock>0){ ?>
                                    <div class="ribbon gift">
                                        <div class="theribbon" style="text-align:center">STOCK <?=$Herramienta[0]->stock?></div>
                                        <div class="ribbon-background"></div>
                                    </div>
                                <?php }else{ ?>
                                    <div class="ribbon sale">
                                        <div class="theribbon" style="text-align:center">SIN STOCK</div>
                                        <div class="ribbon-background"></div>
                                    </div>
                                <?php } ?>
                                <!-- /.ribbon -->
                            </div>
                            <div class="col-sm-6">
                                <div class="box">
                                    <h2 class="text-center"><?=$Herramienta[0]->nombre?></h2>
                                    <p class="goToDescription"><a href="#details" class="scroll-to">Presionar para ver más detalles</a>
                                    </p>
                                    <p class="price">$<?=number_format($Herramienta[0]->precio, 0,'.', '.')?></p>
                                    
                                    <?php if($Herramienta[0]->stock>0){ ?>
                                        <center>
                                            <div class="form-group" style="width: 50%">
                                                <label for="cantidad">Cantidad</label>
                                                <input type="number" id="cantidad" class="form-control" min="1" max="<?=$Herramienta[0]->stock?>" value="1" required>
                                            </div>
                                        </center>
                                    <?php }else{ ?>
                                        <center><button class="btn btn-danger btn-block">SIN STOCK</button>
                                        <br>
                                        <div class="alert alert-info" style="text-align: justify; font-size: 16px;">La herramienta no posee stock en el intervalo de fecha establecido.</div></center>
                                    <?php } ?>

                                    <p class="text-center buttons"> 
                                        <?php if($Herramienta[0]->stock>0){ ?>
                                            <button class="btn btn-primary" value="<?=$Herramienta[0]->cod_herramienta?>" id="carrito-detalle"><i class="fa fa-shopping-cart"></i> Agregar al Carro</button> 
                                        <?php } ?>
                                        <a href="<?=base_url()?>productos/1" class="btn btn-default"><i class="fa fa-arrow-circle-left"></i> Volver a las Herramientas</a>
                                    </p>
                                </div>
                            </div>
                        </div>


                        <div class="box" id="details">
                            <p>
                                <h4>Detalles</h4>
                            <p><?=$Herramienta[0]->descripcion?></p>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>

        <script>
            $(document).ready(function(){
                $("#carrito-detalle").click(function(){
                    var codigo = $(this)[0].value;
                    var cantidad = $("#cantidad").val();
                    $.ajax({
                        url: "<?=base_url()?>carrito-agregar",
                        data: {codigo:codigo,cantidad:cantidad},
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