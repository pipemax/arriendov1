<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div id="all">
    <div id="content">
        <div class="container">

            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li><a href="<?=base_url()?>">Inicio</a>
                    </li>
                    <li>Carrito de Arriendos</li>
                </ul>
            </div>

            <div class="col-md-9" id="basket">
                <div class="box">
                    <h1>Carro de Arriendos</h1>
                    <p class="text-muted">Actualmente tienes <?=$Cantidad[0]->cantidad?> item(s) en el carro.</p>
                    <div class="table-responsive">
                        <table class="table table-responsive">
                            <thead>
                                <tr>
                                    <th class="col-md-1" style="text-align:center">Herramienta</th>
                                    <th class="col-md-5" style="text-align:center"></th>
                                    <th class="col-md-1" style="text-align:center">Cantidad</th>
                                    <th class="col-md-1" style="text-align:center">Precio Unitario</th>
                                    <th class="col-md-1" style="text-align:center">Total</th>
                                    <th class="col-md-1" style="text-align:center"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                foreach($Carrito as $herramienta){
                                ?>
                                <tr>
                                    <td class="col-md-1" style="text-align:center">
                                        <a href="#">
                                            <img src="<?=base_url()?>assets/herramientas/<?=$herramienta->url_foto?>" alt="<?=$herramienta->nombre?>">
                                        </a>
                                    </td>
                                    <td class="col-md-5">
                                        <a href="<?=base_url()?>detalle/<?=$herramienta->cod_herramienta?>"><?=$herramienta->nombre?></a>
                                    </td>
                                    <td class="col-md-1">
                                        <input type="number" id="<?=$herramienta->cod_herramienta?>-cantidad" style="width:100%" value="<?=$herramienta->cantidad?>" min="1" max="<?=$herramienta->stock?>" class="form-control cantidad">
                                        <input class="hidden" id="<?=$herramienta->cod_herramienta?>-respaldo" value="<?=$herramienta->cantidad?>">
                                    </td>
                                    <td class="col-md-1" style="text-align:center">$<?=$herramienta->precio?></td>
                                    <input class="hidden" id="<?=$herramienta->cod_herramienta?>-precio" value="<?=$herramienta->precio?>">
                                    <td class="col-md-1" id="<?=$herramienta->cod_herramienta?>-total" style="text-align:center" style="text-align:center">$<?=$herramienta->total?></td>
                                    <td class="col-md-1" style="text-align:center"><button type="button" class="btn btn-default eliminar_h" value="<?=$herramienta->cod_herramienta?>"><i class="fa fa-trash-o"></i></button>
                                    </td>
                                </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="5">Total</th>
                                    <th colspan="2" id="total">$<?=number_format($Total, 0,'.', '.')?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <!-- /.table-responsive -->

                    <div class="box-footer">
                        <div class="pull-left">
                            <a href="<?=base_url()?>productos" class="btn btn-default"><i class="fa fa-chevron-left"></i> Continuar cotizando</a>
                        </div>
                        <div class="pull-right">                           
                            <button type="button" class="btn btn-primary" id="boton_arriendo">Proceder al arriendo <i class="fa fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <!-- /.box -->


                


            </div>
            <!-- /.col-md-9 -->

            <div class="col-md-3">
                <div class="box" id="order-summary">
                    <div class="box-header">
                        <h3>Resumen de la orden</h3>
                    </div>
                    <p class="text-muted" style="text-align: justify">Debe recordar que la orden se genera con las fechas indicadas por usted, en caso de no cumplir las fechas, cancelaremos la orden.</p>

                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td>Sub-total</td>
                                    <th id="subtotal">$<?=number_format($Total-($Total*0.19), 0,'.', '.')?></th>
                                </tr>
                                <tr>
                                    <td>Cálculo IVA</td>
                                    <th id="iva">$<?=number_format(($Total*0.19), 0,'.', '.')?></th>
                                </tr>
                                <tr>
                                    <td>IVA</td>
                                    <th>19%</th>
                                </tr>
                                <tr class="total">
                                    <td>Total</td>
                                    <th id="total2">$<?=number_format($Total, 0,'.', '.')?></th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /.col-md-3 -->

        </div>
        <!-- /.container -->
    </div>
    <!-- /#content -->

    <script>
        $(document).ready(function(){
            $(".cantidad").change(function(){
                var cantidad_actual = parseInt($(this)[0].value);
                var division = ($(this)[0].id).split("-");
                var id = $(this)[0].id;
                var codigo_producto = division[0];
                var cantidad_respaldo = parseInt($("#"+division[0]+"-respaldo").val());
                document.getElementById(id).disabled = true;
                if(cantidad_actual<cantidad_respaldo){
                    $.ajax({
                        url: "<?=base_url()?>carrito-quitar",
                        data: {codigo: codigo_producto,cantidad: (cantidad_respaldo-cantidad_actual)},
                        type: "post",
                        success: function(data){
                            var conversion = JSON.parse(data);
                            if(conversion.estado=='TRUE'){
                                $("#"+division[0]+"-total").html("$"+$("#"+division[0]+"-precio").val()*cantidad_actual);
                                $("#"+division[0]+"-respaldo").val(cantidad_actual);
                                Actualizar_Total();
                                document.getElementById(id).disabled = false;
                            }else if(conversion.estado=='LOGIN'){                                
                                swal({
                                    icon: "warning",
                                    text: "Para manipular el carrito debe iniciar sesión",
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
                                swal(conversion.mensaje, "Ha ocurrido un error", "error");  
                            }                            
                        }
                    });         
                }else if(cantidad_actual>cantidad_respaldo){
                    $.ajax({
                        url: "<?=base_url()?>carrito-agregar",
                        data: {codigo: codigo_producto,cantidad: (cantidad_actual-cantidad_respaldo)},
                        type: "post",
                        success: function(data){
                            var conversion = JSON.parse(data);
                            if(conversion.estado=='TRUE'){
                                $("#"+division[0]+"-total").html("$"+$("#"+division[0]+"-precio").val()*cantidad_actual);
                                $("#"+division[0]+"-respaldo").val(cantidad_actual);
                                Actualizar_Total();
                                document.getElementById(id).disabled = false;
                            }else if(conversion.estado=='LOGIN'){                                
                                swal({
                                    icon: "warning",
                                    text: "Para manipular el carrito debe iniciar sesión",
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
                                swal(conversion.mensaje, "Ha ocurrido un error", "error");  
                            }                            
                        }
                    });      
                }else{
                    document.getElementById(id).disabled = false;
                }
                
            });

            function Actualizar_Total(){
                $.ajax({
                    url: "<?=base_url()?>total-carro",
                    type: "get",
                    success: function(data){
                        $("#total").html("$"+addCommas(data));                        
                        $("#subtotal").html("$"+addCommas(Math.round(data-(data*0.19))));
                        $("#iva").html("$"+addCommas(Math.round(data*0.19)));
                        $("#total2").html("$"+addCommas(data));                        
                    }
                });
            }

            function addCommas(nStr){
                nStr += '';
                x = nStr.split('.');
                x1 = x[0];
                x2 = x.length > 1 ? '.' + x[1] : '';
                var rgx = /(\d+)(\d{3})/;
                while (rgx.test(x1)) {
                    x1 = x1.replace(rgx, '$1' + '.' + '$2');
                }
                return x1 + x2;
            }

            $(".eliminar_h").click(function(){
                var codigo = $(this)[0].value;
                $.ajax({
                    url: "<?=base_url()?>carrito-borrar",
                    data: {codigo: codigo},
                    type: "post",
                    success: function(data){
                        var conversion = JSON.parse(data);
                        if(conversion.estado=='TRUE'){
                            swal(conversion.mensaje,"Eliminación Exitosa","success").then(function(){
                                location.reload();
                            });
                        }else if(conversion.estado=='LOGIN'){
                            swal(conversion.mensaje,"Atención!","warning").then(function(){
                                window.location.href = "<?=base_url()?>";
                            });
                        }else{
                            swal(conversion.mensaje,"Ha ocurrido un error","error").then(function(){
                                location.reload();
                            });
                        }
                    }
                })
            });

            $("#boton_arriendo").click(function(){
                $.ajax({
                    url: "<?=base_url()?>arriendo",
                    data: {arriendo: "YES"},
                    type: "post",
                    success: function(data){
                        var conversion = JSON.parse(data);
                        if(conversion.estado=='TRUE'){
                            window.location.href = "<?=base_url()?>resumen/"+conversion.arriendo;                            
                        }else{
                            swal(conversion.mensaje,"Ups! Houston! Tenemos Problemas!","error");
                        }
                    }
                });
            });
        });
    </script>