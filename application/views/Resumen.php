<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div id="all">
        <div id="content">
            <div class="row" style="padding-left: 10px; padding-right: 10px; margin-left: 0px; margin-right: 0px;">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li><a href="<?=base_url()?>">Inicio</a>
                        </li>
                        <li>Revisión de la Orden</li>
                    </ul>
                </div>
                <div class="col-md-9" id="checkout">
                    <div class="box">
                        <h2>Orden de arriendo N° <?=$orden?> generada con éxito!</h2>
                        <hr style="background-color:#4993e4; height: 1px; border: 0;">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="fecha_arriendo">Fecha Transacción</label>
                                    <input class="form-control" type="text" id="fecha_arriendo" value="<?=$arriendo[0]->fecha_arriendo?>" disabled>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label for="sujeto">Arrendatario</label>
                                    <input class="form-control" type="text" id="sujeto" value="<?=$arriendo[0]->nombres." ".$arriendo[0]->apellidos?>" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="fecha_inicio">Fecha Inicio Arriendo</label>
                                    <input class="form-control" type="text" id="fecha_inicio" value="<?=$arriendo[0]->fecha_inicio?>" disabled>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="fecha_fin">Fecha Fin Arriendo</label>
                                    <input class="form-control" type="text" id="fecha_fin" value="<?=$arriendo[0]->fecha_final?>" disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sucursal">Sucursal Arrendador</label>
                                    <input class="form-control" type="text" id="sucursal" value="<?=$arriendo[0]->nombre?>" disabled>
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
                                            <th class="col-md-2" style="text-align:center">Cantidad</th>
                                            <th class="col-md-2" style="text-align:center">Precio Unitario</th>
                                            <th class="col-md-2" style="text-align:center">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        foreach($detalle as $herramienta){
                                        ?>
                                        <tr>
                                            <td class="col-md-1" style="text-align:center">
                                                <a href="#">
                                                    <img src="<?=base_url()?>assets/herramientas/<?=$herramienta->url_foto?>" alt="<?=$herramienta->nombre?>">
                                                </a>
                                            </td>
                                            <td class="col-md-5">
                                                <a href="<?=base_url()?>detalle/<?=$herramienta->cod_h?>"><?=$herramienta->nombre?></a>
                                            </td>
                                            <td class="col-md-2" style="text-align:center">
                                                <?=$herramienta->cantidad?>
                                            </td>
                                            <td class="col-md-2" style="text-align:center">$<?=$herramienta->precio?></td>
                                            <td class="col-md-2" style="text-align:center" style="text-align:center">$<?=$herramienta->total_detalle?></td>
                                            </td>
                                        </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="4">Total</th>
                                            <th colspan="1" style="text-align:center">$<?=number_format($arriendo[0]->total, 0,'.', '.')?></th>
                                        </tr>
                                    </tfoot>
                                </table>

                            </div>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.content -->

                        <div class="box-footer">
                            <div class="pull-left">
                                <a href="<?=base_url()?>" class="btn btn-success"><i class="fa fa-chevron-left"></i>Volver a Inicio</a>
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
                                        <th id="subtotal">$<?=number_format($arriendo[0]->total-($arriendo[0]->total*0.19), 0,'.', '.')?></th>
                                    </tr>
                                    <tr>
                                        <td>Cálculo IVA</td>
                                        <th id="iva">$<?=number_format(($arriendo[0]->total*0.19), 0,'.', '.')?></th>
                                    </tr>
                                    <tr>
                                        <td>IVA</td>
                                        <th>19%</th>
                                    </tr>
                                    <tr class="total">
                                        <td>Total</td>
                                        <th id="total2">$<?=number_format($arriendo[0]->total, 0,'.', '.')?></th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- /.col-md-3 -->
            </div>
        </div>
        <!-- /.container -->
    </div>
        <!-- /#content -->