<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
        <!-- main content area start -->
        <div class="main-content">            
            <!-- page title area start -->
            <div class="page-title-area">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <div class="breadcrumbs-area clearfix">
                            <h4 class="page-title pull-left">Arriendos</h4>
                            <ul class="breadcrumbs pull-left">
                                <li><a href="<?php echo base_url()?>">Inicio</a></li>
                                <li><span>Arriendos</span></li>
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
                            if($output!=FALSE)
                            {
                        ?>                 
                            <div class="row">
                                <div class="col-md-3 col-xs-12">
                                    <form method="get" class="form-group">
                                        <div class="input-group">
                                            <input type="text" placeholder="Filtro por Rut" name="filtro_rut" class="form-control" maxlength="10" required>
                                            <div class="input-group-append">
                                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-3 col-xs-12">
                                    <form method="get" class="form-group">
                                        <div class="input-group">
                                            <input type="date" placeholder="Filtro por Fecha" name="filtro_fecha" class="form-control" required>
                                            <div class="input-group-append">
                                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-3 col-xs-12">
                                    <a href="<?php echo base_url();?>arriendos/" class="btn btn-danger">Borrar filtros</a>
                                </div>
                            </div>
                            <br>
                            <table id="tabla_arriendos" class="table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th class="all">N° Orden</th>
                                        <th class="all">Rut Cliente</th>
                                        <th class="desktop">Total</th>
                                        <th class="desktop">Estado</th> 
                                        <th class="desktop">Fecha Transacción</th>
                                        <th class="desktop">Acción</th> 
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        foreach($output as $valor){
                                    ?>
                                    <tr>
                                        <td><?php echo $valor->cod_arriendo;?></td>
                                        <td><?php echo substr($valor->rut_u, 0, -1)."-".substr($valor->rut_u,strlen($valor->rut_u)-1,strlen($valor->rut_u));?></td>
                                        <td>$<?php echo number_format($valor->total, 0,'.', '.'); ?></td>
                                        <td><span class="badge badge-info" style="font-size: 14px;"><?php echo $valor->estado;?></span></td>                
                                        <td><?php echo $valor->fecha_arriendo;?></td>
                                        <td> 
                                            <a href="<?php echo base_url();?>detalle/<?php echo $valor->cod_arriendo;?>" class="btn btn-primary btn-sm">Ver detalle</a>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        <?php
                            }
                            else
                            {
                        ?>
                            <div class="alert alert-info">
                                Aún no hay arriendos registrados.
                            </div>
                        <?php
                            }
                        ?>
                        </div>
                    </div>
                </div>               
            </div>

            <script>
                $(document).ready(function(){
                    $('#tabla_arriendos').DataTable({
                        responsive: true,
                        "columns": [
                            { "data": "Cod_arriendo" },
                            { "data": "Rut" },
                            { "data": "Total" },  
                            { "data": "Estado" },
                            { "data": "Fecha" },
                            { "data": "Acción" }
                        ],
                        language: {
                            url: "https://cdn.datatables.net/plug-ins/1.10.12/i18n/Spanish.json"
                        },
                        columnDefs: [
                            { "searchable": false, "targets": [5] }
                        ],    
                        bSort: false,
                        bInfo: false
                    });

                });
            </script>
