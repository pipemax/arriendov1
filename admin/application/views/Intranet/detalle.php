<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
        <!-- main content area start -->
        <div class="main-content">            
            <!-- page title area start -->
            <div class="page-title-area">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <div class="breadcrumbs-area clearfix">
                            <h4 class="page-title pull-left">Detalle del arriendo</h4>
                            <ul class="breadcrumbs pull-left">
                                <li><a href="<?php echo base_url()?>">Inicio</a></li>
                                <li><a href="<?php echo base_url()?>arriendos/">Arriendos</a></li>
                                <li><span>N° de Orden <?php echo $id;?></span></li>
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
                            if($detalle!=FALSE || $arriendo!=FALSE)
                            {
                        ?>                 
                            <div class="row">
                                <div class="col-md-8 col-xs-12" id="checkout">                                
                                    <h2>Revisión la orden N° <?php echo $id?></h2>
                                    <hr style="background-color:#4993e4; height: 1px; border: 0;">
                                    <div class="row">
                                        <div class="col-md-3 col-xs-12">
                                            <div class="form-group">
                                                <label for="fecha_arriendo">Fecha Transacción</label>
                                                <input class="form-control" type="text" id="fecha_arriendo" value="<?php echo $arriendo[0]->fecha_arriendo; ?>" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-9 col-xs-12">
                                            <div class="form-group">
                                                <label for="sujeto">Arrendatario</label>
                                                <input class="form-control" type="text" id="sujeto" value="<?php echo $arriendo[0]->nombres." ".$arriendo[0]->apellidos; ?>" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3 col-xs-12">
                                            <div class="form-group">
                                                <label for="fecha_inicio">Fecha Inicio Arriendo</label>
                                                <input class="form-control" type="text" id="fecha_inicio" value="<?php echo $arriendo[0]->fecha_inicio; ?>" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-xs-12">
                                            <div class="form-group">
                                                <label for="fecha_fin">Fecha Fin Arriendo</label>
                                                <input class="form-control" type="text" id="fecha_fin" value="<?php echo $arriendo[0]->fecha_final; ?>" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xs-12">
                                            <div class="form-group">
                                                <label for="estado">Estado</label>
                                                <?php 
                                                    if($arriendo[0]->estado=='PENDIENTE'){
                                                        $class = 'warning';
                                                    }else if($arriendo[0]->estado=='ENTREGADO'){
                                                        $class = 'info';
                                                    }else if($arriendo[0]->estado=='ANULADO'){
                                                        $class = 'danger';
                                                    }else if($arriendo[0]->estado=='COMPLETADO'){
                                                        $class = 'success';
                                                    }
                                                ?>
                                                <input class="btn btn-<?php echo $class;?> btn-block" id="estado" type="button" value="<?php echo $arriendo[0]->estado;?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-xs-12" id="checkout">  
                                    <h2>Resumen de la orden</h2>
                                    <hr style="background-color:#4993e4; height: 1px; border: 0;">                                   
                                    <div class="table-responsive">
                                        <table class="table table-condensed">
                                            <tbody>
                                                <?php 
                                                    $date1 = DateTime::createFromFormat('d/m/Y', $arriendo[0]->fecha_inicio);
                                                    $date2 = DateTime::createFromFormat('d/m/Y', $arriendo[0]->fecha_final);
                                                ?>
                                                <tr>
                                                    <td style="padding: 5px;">Días de arriendo</td>
                                                    <th style="padding: 5px;"><?php echo $date2->diff($date1)->format("%a");; ?></th>
                                                </tr>
                                                <tr>
                                                    <td style="padding: 5px;">Sub-total</td>
                                                    <th style="padding: 5px;">$<?php echo number_format($arriendo[0]->total-($arriendo[0]->total*0.19), 0,'.', '.'); ?></th>
                                                </tr>
                                                <tr>
                                                    <td style="padding: 5px;">Cálculo IVA</td>
                                                    <th style="padding: 5px;">$<?php echo number_format(($arriendo[0]->total*0.19), 0,'.', '.'); ?></th>
                                                </tr>
                                                <tr>
                                                    <td style="padding: 5px;">IVA</td>
                                                    <th style="padding: 5px;">19%</th>
                                                </tr>
                                                <tr class="table-primary">
                                                    <td style="padding: 5px;">Total</td>
                                                    <th style="padding: 5px;">$<?php echo number_format($arriendo[0]->total, 0,'.', '.'); ?></th>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <hr>                            
                            <table class="table" id="tabla_detalle">
                                <thead>
                                    <tr>
                                        <th class="col-md-2 all" style="text-align:center"></th>
                                        <th class="col-md-5 desktop" style="text-align:center">Herramienta</th>
                                        <th class="col-md-3 desktop" style="text-align:center"></th>
                                        <th class="col-md-1 desktop" style="text-align:center;">Estado</th>
                                        <th class="col-md-1 desktop" style="text-align:center">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php //PHP
                                        foreach($detalle as $herramienta)
                                        {
                                    ?>
                                    <tr>
                                        <td class="col-md-2" style="text-align:center">
                                            <a>
                                                <img class="img-responsive" src="<?php echo str_replace("admin/","",base_url());?>assets/herramientas/<?php echo $herramienta->url_foto; ?>" alt="<?php echo $herramienta->nombre; ?>">
                                            </a>
                                        </td>
                                        <td class="col-md-5">
                                            <h5>SKU: <?php echo $herramienta->cod_h; ?></h5>
                                            <br>
                                            <h5><?php echo $herramienta->nombre; ?></h5>
                                            <br>
                                            <button type="button" class="btn btn-info btn-xs" readonly><?php echo $herramienta->nombre_empresa; ?></button>
                                            <button type="button" class="btn btn-danger btn-xs" readonly>SUCURSAL <?php echo $herramienta->nombre_sucursal; ?></button>
                                        </td>
                                        <td class="col-md-3">
                                            <table class="table table-light">
                                                    <tbody>
                                                    <tr>
                                                        <td>Precio Unitario</td>
                                                        <th>$<?php echo number_format($herramienta->total_unitario, 0,'.', '.'); ?></th>
                                                    </tr>
                                                    <tr>
                                                        <td>Descuento aplicado</td>
                                                        <th><?php echo $herramienta->descuento;?>%</th>
                                                    </tr>
                                                    <tr>
                                                        <td>Cantidad</td>
                                                        <th><?php echo $herramienta->cantidad;?></th>
                                                    </tr>
                                                    <tr>
                                                        <td>Total</td>
                                                        <th>$<?php echo number_format($herramienta->total_detalle, 0,'.', '.'); ?></th>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                        <td class="col-md-1" style="text-align:center;">
                                            <?php 
                                                if($herramienta->estado=='PENDIENTE'){
                                                    $class = 'warning';
                                                }else if($herramienta->estado=='ENTREGADO'){
                                                    $class = 'info';
                                                }else if($herramienta->estado=='ANULADO'){
                                                    $class = 'danger';
                                                }else if($herramienta->estado=='COMPLETADO'){
                                                    $class = 'success';
                                                }
                                            ?>
                                            <button class="btn btn-<?php echo $class;?>" type="button"><?php echo $herramienta->estado; ?></button>
                                        </td>                                                        
                                        <td class="col-md-1" style="text-align:center">
                                        <?php
                                            if($herramienta->empresa==$empresa_admin)
                                            {
                                        ?>
                                            <button class="btn btn-primary modificar" value="<?php echo $herramienta->cod_h."-".$herramienta->empresa."-".$herramienta->cod_sucursal."-".$herramienta->id_a;?>">Modificar</button>
                                        <?php
                                            }
                                            else
                                            {
                                        ?>
                                            <button class="btn btn-primary" disabled>Modificar</button>
                                        <?php
                                            }
                                        ?>
                                        </td>
                                        
                                    </tr>
                                    <?php //PHP
                                        }
                                    ?>
                                </tbody>
                            </table>                              
                        </div>
                    </div>                            
                        <?php
                            }
                            else
                            {
                        ?>
                            <div class="row">
                                <div class="col-md-12 mx-auto col-xs-12">
                                    <div class="box text-center py-5">
                                        <h1><i class="fa fa-warning"></i></h1>
                                        <h2 class="text-muted">Lo sentimos, ha ocurrido un error</h2>
                                        <p class="text-center">Al parecer no tenemos registros del arriendo seleccionado</p>
                                        <br>
                                        <p class="buttons"><a href="<?php echo base_url();?>arriendos/" class="btn btn-primary"><i class="fa fa-home"></i> Volver a Arriendos</a></p>
                                    </div>
                                </div>
                            </div> 
                        <?php
                            }
                        ?>
                        </div>
                    </div>
                </div>               
            </div>

            <!-- Modal -->
            <div class="modal fade" id="modal_detalle" tabindex="-1" role="dialog" aria-labelledby="modal_detalleLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modal_detalleLabel">Modificación del detalle</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="form_modal_detalle" method="post">
                                <div class="row">
                                    <div class="col-md-3 col-xs-12">
                                        <div class="form-group">
                                            <label for="codigo_modal_detalle">SKU</label>
                                            <input type="text" class="form-control" id="codigo_modal_detalle" readonly>
                                        </div>
                                    </div>     
                                    <div class="col-md-9 col-xs-12">
                                        <div class="form-group">
                                            <label for="nombre_modal_detalle">Nombre Herramienta</label>
                                            <input type="text" class="form-control" id="nombre_modal_detalle" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">                                  
                                    <div class="col-md-4 col-xs-12">
                                        <div class="form-group">
                                            <label for="empresa_modal_detalle">Empresa</label>
                                            <input type="text" class="form-control" id="empresa_modal_detalle" readonly>
                                        </div>
                                    </div>      
                                    <div class="col-md-4 col-xs-12">
                                        <div class="form-group">
                                            <label for="sucursal_modal_detalle">Sucursal</label>
                                            <input type="text" class="form-control" id="sucursal_modal_detalle" readonly>
                                        </div>
                                    </div> 
                                    <div class="col-md-4 col-xs-12">
                                        <div class="form-group">
                                            <label for="estado_modal_detalle">Estado</label>
                                            <select class="form-control" name="estado_modal_detalle" id="estado_modal_detalle" required>
                                                <option value="">Seleccione un estado</option>
                                                <option value="PENDIENTE">PENDIENTE</option>
                                                <option value="ENTREGADO">ENTREGADO</option>
                                                <option value="ANULADO">ANULADO</option>
                                                <option value="COMPLETADO">COMPLETADO</option>
                                            </select>                                            
                                        </div>
                                    </div> 
                                </div>   
                                <div class="row">                                  
                                    <div class="col-md-3 col-xs-12">
                                        <div class="form-group">
                                            <label for="unitario_modal_detalle">Precio Unitario</label>
                                            <input type="text" class="form-control" id="unitario_modal_detalle" readonly>
                                        </div>
                                    </div>      
                                    <div class="col-md-3 col-xs-12">
                                        <div class="form-group">
                                            <label for="descuento_modal_detalle">Descuento</label>
                                            <input type="text" class="form-control" id="descuento_modal_detalle" readonly>
                                        </div>
                                    </div> 
                                    <div class="col-md-3 col-xs-12">
                                        <div class="form-group">
                                            <label for="cantidad_modal_detalle">Cantidad</label>
                                            <input type="text" class="form-control" id="cantidad_modal_detalle" name="cantidad_modal_detalle" readonly>
                                        </div>
                                    </div> 
                                    <div class="col-md-3 col-xs-12">
                                        <div class="form-group">
                                            <label for="total_modal_detalle">Total Detalle</label>
                                            <input type="text" class="form-control" id="total_modal_detalle" readonly>
                                        </div>
                                    </div> 
                                </div>                              
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                    <button type="button" id="modificar_modal_detalle" class="btn btn-primary">Guardar Cambios</button>
                                </div>
                            </form>
                        </div>                        
                    </div>
                </div>
            </div>

            <script>
                $(document).ready(function(){
                    $('#tabla_detalle').DataTable({
                        responsive: true,
                        "columns": [
                            { "data": "Foto" },
                            { "data": "Herramienta"},
                            { "data": "Informacion" },  
                            { "data": "Estado" },
                            { "data": "Acción" }
                        ],
                        language: {
                            url: "https://cdn.datatables.net/plug-ins/1.10.12/i18n/Spanish.json"
                        },
                        columnDefs: [
                            { "searchable": false }
                        ],    
                        bSort: false,
                        bInfo: false
                    });

                    $(".modificar").click(function(){
                        var datos = ($(this)[0].value).split("-");
                        var codigo = $(this)[0].value;
                        var herramienta = datos[0];
                        var empresa = datos[1];
                        var sucursal = datos[2];
                        var arriendo = datos[3];
                        $.ajax({
                            url: "<?=base_url();?>obtener-detalle",
                            data: {herramienta: herramienta,empresa: empresa, sucursal: sucursal, arriendo: arriendo},
                            type: 'post',
                            success: function (data){
                                if(data!='FALSE'){
                                    var retorno = JSON.parse(data);
                                    $("#modal_detalle #codigo_modal_detalle").val(herramienta);
                                    $("#modal_detalle #nombre_modal_detalle").val(retorno[0].nombre);
                                    $("#modal_detalle #empresa_modal_detalle").val(retorno[0].nombre_empresa);
                                    $("#modal_detalle #sucursal_modal_detalle").val(retorno[0].nombre_sucursal);
                                    $("#modal_detalle #estado_modal_detalle").val(retorno[0].estado);
                                    $("#modal_detalle #unitario_modal_detalle").val(retorno[0].total_unitario);
                                    $("#modal_detalle #descuento_modal_detalle").val(retorno[0].descuento+"%");
                                    $("#modal_detalle #cantidad_modal_detalle").val(retorno[0].cantidad);
                                    document.getElementById("cantidad_modal_detalle").max = retorno[0].cantidad;
                                    $("#modal_detalle #total_modal_detalle").val(retorno[0].total_detalle);
                                    document.getElementById("modificar_modal_detalle").value = codigo;
                                    $("#modal_detalle").modal();
                                }else{
                                    swal("¡Ha ocurrido un error!", "Al parecer está intentando acceder a una herramienta de otra empresa", "error"); 
                                }
                            }
                        });
                    });

                    $("#modal_detalle").on("hidden.bs.modal", function () {
                        $("#form_modal_detalle")[0].reset();
                    });

                    $("#modificar_modal_detalle").click(function(){
                        var datos = ($(this)[0].value).split("-");
                        var herramienta = datos[0];
                        var empresa = datos[1];
                        var sucursal = datos[2];
                        var arriendo = datos[3];
                        var estado = $("#estado_modal_detalle").val();
                        $.ajax({
                            url: "<?=base_url();?>modificar-detalle",
                            data: {herramienta: herramienta,empresa: empresa, sucursal:sucursal, arriendo: arriendo, estado: estado},
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
                    });
                });
            </script>
