<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div id="all">
        <div id="content">
            <div class="row" style="padding-left: 10px; padding-right: 10px; margin-left: 0px; margin-right: 0px;">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li><a href="<?php echo base_url(); ?>">Inicio</a>
                        </li>
                        <li>Mis arriendos</li>
                    </ul>
                </div>
                <div class="col-md-3 col-xs-12">
                    <div class="panel panel-default sidebar-menu">
                        <div class="panel-heading">
                            <h3 class="panel-title">Sección del Cliente</h3>
                        </div>
                        <div class="panel-body">
                            <ul class="nav nav-pills nav-stacked category-menu">
                                <li>
                                    <a href="<?php echo base_url();?>mi-cuenta" class="nav-link active"><i class="fa fa-list"></i> Mis arriendos</a>
                                </li>
                                <li>
                                    <a href="<?php echo base_url();?>mis-datos" class="nav-link"><i class="fa fa-user"></i> Mis datos</a>
                                </li>
                                <li>
                                    <a href="<?php echo base_url(); ?>salir" class="nav-link"><i class="fa fa-sign-out"></i> Salir</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-9 col-xs-12" id="checkout">
                    <div class="box">
                        <?php if($arriendos!=FALSE)
                        {
                        ?>                        
                        <h2>Mis Arriendos</h2>
                        <p class="lead">Todos tus arriendos en un solo lugar.</p>
                        <p class="text-muted">Si tienes alguna duda, habla con nosotros <a href="<?php echo base_url();?>contacto">contacto</a>, nuestro servicio al cliente estará esperando.</p>
                        <hr style="background-color:#4993e4; height: 1px; border: 0;">
                        <div class="table-responsive">
                            <table id="tabla_arriendos" class="table table-hover dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>N° Orden</th>
                                        <th>Fecha Transacción</th>
                                        <th>Total</th>
                                        <th>Estado</th>
                                        <th>Inicio arriendo</th>
                                        <th>Fin arriendo</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        foreach($arriendos as $row)
                                        {
                                    ?>
                                    <tr>
                                        <th># <?php echo $row->cod_arriendo;?></th>
                                        <td><?php echo $row->fecha_arriendo;?></td>
                                        <td>$<?php echo number_format($row->total, 0,'.', '.'); ?></td>
                                        <td class="col-md-1">
                                        <?php 
                                            if($row->estado=='PENDIENTE'){
                                                $class = 'warning';
                                            }else if($row->estado=='ENTREGADO'){
                                                $class = 'info';
                                            }else if($row->estado=='ANULADO'){
                                                $class = 'danger';
                                            }else if($row->estado=='COMPLETADO'){
                                                $class = 'success';
                                            }
                                        ?>
                                        <button class="btn btn-<?php echo $class;?> btn-block"><?php echo $row->estado;?></button>
                                        </td>
                                        <td><?php echo $row->fecha_inicio;?></td>
                                        <td><?php echo $row->fecha_final;?></td>
                                        <td><a href="<?php echo base_url();?>resumen/<?php echo $row->cod_arriendo?>" class="btn btn-primary btn-sm">Ver</a></td>
                                    </tr>
                                    <?php
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="box-footer">
                            <div class="pull-left">
                                <a href="<?php echo base_url(); ?>" class="btn btn-success"><i class="fa fa-chevron-left"></i>Volver a Inicio</a>
                            </div>
                        </div>
                        <?php 
                        }
                        else
                        {
                        ?>
                            <div id="error-page" class="row">
                                <div class="col-md-12 mx-auto">
                                <div class="text-center py-5">
                                    <h1><i class="fa fa-warning"></i></h1>
                                    <h2 class="text-muted">Aún no realizas arriendos en nuestro sistema</h2>
                                    <p class="text-center">Te invitamos a que revises nuestros productos</p>
                                    <p class="buttons"><a href="<?php echo base_url();?>productos/" class="btn btn-primary"><i class="fa fa-list"></i> Ir a cotizar</a></p>
                                </div>
                            </div>
                    </div>   
                        <?php
                        }
                        ?>
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col-md-9 -->
            </div>
        </div>
        <!-- /.container -->
    </div>
        <!-- /#content -->
    <script>
         $(document).ready(function(){

            $('#tabla_arriendos').DataTable({
                responsive: true,
                "columns": [
                    { "data": "Orden" },
                    { "data": "Fecha_Transaccion" },
                    { "data": "Total" },  
                    { "data": "Estado" },
                    { "data": "Fecha_Inicio_Arriendo" },
                    { "data": "Fecha_Fin_Arriendo"},
                    { "data": "Accion" }
                ],
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.10.12/i18n/Spanish.json"
                },
                columnDefs: [
                    { "searchable": false, "targets": [6] }
                ],    
                bSort: false,
                bInfo: true
            });
        });
    </script>