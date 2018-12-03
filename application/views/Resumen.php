<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div id="all">
        <div id="content">
            <div class="row" style="padding-left: 10px; padding-right: 10px; margin-left: 0px; margin-right: 0px;">
                <div class="col-md-12 col-xs-12">
                    <ul class="breadcrumb">
                        <li><a href="<?php echo base_url(); ?>">Inicio</a>
                        </li>
                        <li>Resumen de la Orden</li>
                    </ul>
                </div>
                <?php 
                    if($arriendo!=FALSE)
                    {
                ?>
                <div class="col-md-9" id="checkout">
                    <div class="box">
                        <h2>Orden de arriendo N° <?php echo $orden?> generada con éxito!</h2>
                        <hr style="background-color:#4993e4; height: 1px; border: 0;">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="fecha_arriendo">Fecha Transacción</label>
                                    <input class="form-control" type="text" id="fecha_arriendo" value="<?php echo $arriendo[0]->fecha_arriendo; ?>" disabled>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label for="sujeto">Arrendatario</label>
                                    <input class="form-control" type="text" id="sujeto" value="<?php echo $arriendo[0]->nombres." ".$arriendo[0]->apellidos; ?>" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="fecha_inicio">Fecha Inicio Arriendo</label>
                                    <input class="form-control" type="text" id="fecha_inicio" value="<?php echo $arriendo[0]->fecha_inicio; ?>" disabled>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="fecha_fin">Fecha Fin Arriendo</label>
                                    <input class="form-control" type="text" id="fecha_fin" value="<?php echo $arriendo[0]->fecha_final; ?>" disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="estado">Estado</label>
                                    <input class="form-control" type="text" id="estado" value="<?php echo $arriendo[0]->estado; ?>" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="content">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="col-md-1" style="text-align:center">Herramienta</th>
                                            <th class="col-md-5" style="text-align:center"></th>
                                            <th class="col-md-1" style="text-align:center">Estado</th>
                                            <th class="col-md-1" style="text-align:center">Cantidad</th>
                                            <th class="col-md-2" style="text-align:center">Precio Unitario</th>
                                            <th class="col-md-1" style="text-align:center">Descuento</th>
                                            <th class="col-md-1" style="text-align:center">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php //PHP
                                            foreach($detalle as $herramienta)
                                            {
                                        ?>
                                        <tr>
                                            <td class="col-md-1" style="text-align:center">
                                                <a href="<?php echo base_url(); ?>detalle/<?php echo $herramienta->cod_h;?>/<?php echo $herramienta->cod_sucursal;?>/<?php echo $herramienta->empresa;?>">
                                                    <img src="<?php echo base_url(); ?>assets/herramientas/<?php echo $herramienta->url_foto; ?>" alt="<?php echo $herramienta->nombre; ?>">
                                                </a>
                                            </td>
                                            <td class="col-md-5">
                                                <a href="<?php echo base_url(); ?>detalle/<?php echo $herramienta->cod_h;?>/<?php echo $herramienta->cod_sucursal;?>/<?php echo $herramienta->empresa;?>"><?php echo $herramienta->nombre; ?></a>
                                                <br>
                                                <button type="button" class="btn btn-info btn-xs" readonly><?php echo $herramienta->nombre_empresa; ?></button>
										        <button type="button" class="btn btn-danger btn-xs" readonly>SUCURSAL <?php echo $herramienta->nombre_sucursal; ?></button>
                                            </td>
                                            <td class="col-md-1" style="text-align:center">
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
                                                <button class="btn btn-<?php echo $class;?> btn-block" type="button"><?php echo $herramienta->estado; ?></button>
                                            </td>
                                            <td class="col-md-1" style="text-align:center">
                                                <?php echo $herramienta->cantidad; ?>
                                            </td>
                                            <td class="col-md-2" style="text-align:center">$<?php echo number_format($herramienta->precio, 0,'.', '.');?></td>
                                            <?php 
                                                if($herramienta->descuento!=null || $herramienta->descuento!=0)
                                                {
                                            ?>
                                                <td class="col-md-1" style="text-align:center"><?php echo $herramienta->descuento;?>%</td>
                                            <?php 
                                                }
                                                else
                                                {
                                            ?>
                                                <td class="col-md-1" style="text-align:center">0%</td>
                                            <?php
                                                }
                                            ?>
                                            <td class="col-md-1" style="text-align:center" style="text-align:center">$<?php echo number_format($herramienta->total_detalle, 0,'.', '.'); ?></td>
                                            </td>
                                        </tr>
                                        <?php //PHP
                                            }
                                        ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="6">Total</th>
                                            <th colspan="1" style="text-align:center">$<?php echo number_format($arriendo[0]->total, 0,'.', '.'); ?></th>
                                        </tr>
                                    </tfoot>
                                </table>

                            </div>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.content -->

                        <div class="box-footer">
                            <div class="pull-left">
                                <a href="<?php echo base_url();?>mi-cuenta" class="btn btn-success"><i class="fa fa-chevron-left"></i>Ir a mis arriendos</a>
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
                                        <th id="subtotal">$<?php echo number_format($arriendo[0]->total-($arriendo[0]->total*0.19), 0,'.', '.'); ?></th>
                                    </tr>
                                    <tr>
                                        <td>Cálculo IVA</td>
                                        <th id="iva">$<?php echo number_format(($arriendo[0]->total*0.19), 0,'.', '.'); ?></th>
                                    </tr>
                                    <tr>
                                        <td>IVA</td>
                                        <th>19%</th>
                                    </tr>
                                    <tr class="total">
                                        <td>Total</td>
                                        <th id="total2">$<?php echo number_format($arriendo[0]->total, 0,'.', '.'); ?></th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- /.col-md-3 -->
                <?php 
                    }
                    else
                    {
                ?>
                    <div id="error-page" class="row">
                        <div class="col-md-12 mx-auto">
                            <div class="box text-center py-5">
                                <h1><i class="fa fa-warning"></i></h1>
                                <h2 class="text-muted">Lo sentimos - no hay registros del detalle del arriendo seleccionado</h2>
                                <p class="text-center">Esto puede ser un error nuestro, por favor, comuníquese con nosotros <a href="<?php echo base_url();?>contacto">contacto</a>.</p>
                                <p class="buttons"><a href="<?php echo base_url();?>mi-cuenta/" class="btn btn-primary"><i class="fa fa-home"></i> Volver mis arriendos</a></p>
                            </div>
                        </div>
                    </div>   
                <?php 
                    }
                ?>
            </div>
        </div>
        <!-- /.container -->
    </div>
        <!-- /#content -->