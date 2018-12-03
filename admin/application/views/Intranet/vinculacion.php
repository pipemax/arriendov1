<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
        <!-- main content area start -->
        <div class="main-content">
            <!-- page title area start -->
            <div class="page-title-area">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <div class="breadcrumbs-area clearfix">
                            <h4 class="page-title pull-left">Vinculación de Herramienta</h4>
                            <ul class="breadcrumbs pull-left">
                                <li><a href="<?php echo base_url()?>">Inicio</a></li>
                                <li><a href="<?php echo base_url()?>herramientas/">Herramientas</a></li>
                                <li><span>Área de Vinculación</span></li>
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
                        <div class="col-md-4 col-xs-12">
                            <div class="card card-bordered border-primary">
                                <img class="card-img-top img-fluid" src="<?php echo str_replace("admin/","",base_url());?>assets/herramientas/<?php echo $producto[0]->url_foto;?>" alt="image">
                                <div class="card-body">
                                    <h5 class="title" style="text-align:center">CODIGO: <span><?php echo $producto[0]->cod_herramienta;?></span></h5>
                                    <h6 class="title" style="text-align:center" id="<?php echo $producto[0]->cod_herramienta;?>-N"><?php echo $producto[0]->nombre;?></h6>
                                    <p class="card-text" style="text-align:justify">
                                        <?php echo $producto[0]->descripcion;?>
                                    </p>
                                    <p class="card-text" style="text-align:center">
                                        Categoría: <?php echo $producto[0]->categoria;?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8 col-xs-12">                                       
                            <div class="card text-center card-bordered border-success">
                                <div class="card-header bg-success" style="color:white">
                                    Sucursales Vinculadas
                                </div>
                                <div class="card-body">
                                <?php
                                    if($sucursales!=FALSE)
                                    {
                                    ?>  
                                        <table id="tabla_vinculacion" class="table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>                                                
                                                <th class="all">Nombre Sucursal</th>
                                                <th class="desktop">Vinculado</th>
                                                <th class="desktop">Descuento</th>
                                                <th class="desktop">Acción</th> 
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                foreach($sucursales as $valor)
                                                {
                                            ?>
                                            <tr>
                                                <td id="<?php echo $valor->cod_sucursal?>-N"><?php echo $valor->nombre;?></td>                                             
                                                <td>
                                                <?php 
                                                    if($valor->vinculado=="SI")
                                                    {
                                                ?>
                                                    <button class="btn btn-success modificar" value="<?php echo $valor->cod_sucursal?>"><span class="fa fa-pencil"></span></button>
                                                <?php
                                                    }
                                                    else
                                                    {
                                                ?>
                                                    <button class="btn btn-danger" disabled><span class="fa fa-remove"></span></button>
                                                <?php
                                                    }
                                                ?>
                                                </td>
                                                <td>
                                                    <?php 
                                                    if($valor->descuento!=null)
                                                    {
                                                        echo $valor->descuento." %";
                                                    ?>
                                                        
                                                    <?php 
                                                    }
                                                    else
                                                    {
                                                    ?>
                                                        NO
                                                    <?php 
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                <?php 
                                                    if($valor->vinculado=="SI")
                                                    {
                                                ?>
                                                    <button class="btn btn-danger desvincular" value="<?php echo $valor->cod_sucursal?>"><span class="fa fa-remove"></span> Desvincular</button>
                                                <?php
                                                    }
                                                    else
                                                    {
                                                ?>
                                                    <button class="btn btn-success vincular" value="<?php echo $valor->cod_sucursal?>"><span class="fa fa-check"></span> Vincular</button>                                                    
                                                <?php
                                                    }
                                                ?>                                                    
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
                                        No hay sucursales registradas en la base de datos.
                                    </div>
                                <?php
                                    }
                                ?>
                                </div>
                            </div>                            
                        </div>
                    </div>
                </div>              
            </div>

            <!-- Modal -->
            <div class="modal fade" id="nueva_vinculacion" tabindex="-1" role="dialog" aria-labelledby="nueva_vinculacionLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="nueva_vinculacionLabel">Nueva Vinculación</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="form_nueva_vinculacion" method="post">
                                <div class="row">
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="sucursal_nueva_vinculacion">Código Sucursal</label>
                                            <input type="text" class="form-control" id="sucursal_nueva_vinculacion" name="sucursal_nueva_vinculacion" readonly>
                                        </div>
                                    </div>     
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="herramienta_nueva_vinculacion">Código Herramienta</label>
                                            <input type="text" class="form-control" id="herramienta_nueva_vinculacion" name="herramienta_nueva_vinculacion" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">                                  
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="stock_nueva_vinculacion">Stock Disponible</label>
                                            <input type="number" class="form-control" id="stock_nueva_vinculacion" name="stock_nueva_vinculacion" placeholder="Ingrese stock del producto" min="0" max="1000" step="1" required>
                                        </div>
                                    </div>      
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="precio_nueva_vinculacion">Precio</label>
                                            <input type="number" class="form-control" id="precio_nueva_vinculacion" name="precio_nueva_vinculacion" placeholder="Ingrese precio de arriendo por día" min="1000" max="10000000" required>
                                        </div>
                                    </div> 
                                </div>   
                                <div class="row">                                  
                                    <div class="col-md-4 col-xs-12">
                                        <div class="form-group">
                                            <label for="descuento_nueva_vinculacion">Porcentaje descuento</label>
                                            <input type="number" class="form-control" id="descuento_nueva_vinculacion" name="descuento_nueva_vinculacion" placeholder="Ingrese el descuento" min="1" max="100" step="1">
                                            <p style="font-size: 12px; color: red;" id="error_descuento_vinculacion"></p>
                                        </div>
                                    </div>      
                                    <div class="col-md-4 col-xs-12">
                                        <div class="form-group">
                                            <label for="fecha_inicio_nueva_vinculacion">Fecha inicio descuento</label>
                                            <input type="text" class="form-control" id="fecha_inicio_nueva_vinculacion" name="fecha_inicio_nueva_vinculacion" placeholder="Fecha inicio">
                                            <p style="font-size: 12px; color: red;" id="error_fecha_i_vinculacion"></p>
                                        </div>
                                    </div> 
                                    <div class="col-md-4 col-xs-12">
                                        <div class="form-group">
                                            <label for="fecha_final_nueva_vinculacion">Fecha final descuento</label>
                                            <input type="text" class="form-control" id="fecha_final_nueva_vinculacion" name="fecha_final_nueva_vinculacion" placeholder="Fecha fin">
                                            <p style="font-size: 12px; color: red;" id="error_fecha_f_vinculacion"></p>
                                        </div>
                                    </div> 
                                </div>                              
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                    <button type="submit" id="vincular_herramienta" class="btn btn-primary">Vincular</button>
                                </div>
                            </form>
                        </div>                        
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="vinculacion_modificacion" tabindex="-1" role="dialog" aria-labelledby="vinculacion_modificacionLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="vinculacion_modificacionLabel">Desvinculación de Herramienta</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="form_vinculacion_modificacion" method="post">
                                <div class="row">
                                    <div class="col-md-3 col-xs-12">
                                        <div class="form-group">
                                            <label for="sucursal_vinculacion_modificacion">Código Sucursal</label>
                                            <input type="text" class="form-control" id="sucursal_vinculacion_modificacion" name="sucursal_vinculacion_modificacion" readonly>
                                        </div>
                                    </div>  
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="nombre_vinculacion_modificacion">Nombre Sucursal</label>
                                            <input type="text" class="form-control" id="nombre_vinculacion_modificacion" name="nombre_vinculacion_modificacion" readonly>
                                        </div>
                                    </div>    
                                    <div class="col-md-3 col-xs-12">
                                        <div class="form-group">
                                            <label for="herramienta_vinculacion_modificacion">Código Herramienta</label>
                                            <input type="text" class="form-control" id="herramienta_vinculacion_modificacion" name="herramienta_vinculacion_modificacion" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">                                  
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="stock_vinculacion_modificacion">Stock Disponible</label>
                                            <input type="number" class="form-control" id="stock_vinculacion_modificacion" name="stock_vinculacion_modificacion" placeholder="Ingrese stock del producto" min="1" max="1000" step="1" required>
                                        </div>
                                    </div>      
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="precio_vinculacion_modificacion">Precio</label>
                                            <input type="number" class="form-control" id="precio_vinculacion_modificacion" name="precio_vinculacion_modificacion" placeholder="Ingrese precio de arriendo por día" min="1000" max="10000000" required>
                                        </div>
                                    </div> 
                                </div>  
                                <div class="row">                                  
                                    <div class="col-md-4 col-xs-12">
                                        <div class="form-group">
                                            <label for="descuento_vinculacion_modificacion">Porcentaje descuento</label>
                                            <input type="number" class="form-control" id="descuento_vinculacion_modificacion" name="descuento_vinculacion_modificacion" placeholder="Ingrese el descuento" min="1" max="100" step="1">
                                            <p style="font-size: 12px; color: red;" id="error_descuento"></p>
                                        </div>
                                    </div>      
                                    <div class="col-md-4 col-xs-12">
                                        <div class="form-group">
                                            <label for="fecha_inicio_vinculacion_modificacion">Fecha inicio descuento</label>
                                            <input type="text" class="form-control" id="fecha_inicio_vinculacion_modificacion" name="fecha_inicio_vinculacion_modificacion" placeholder="Fecha inicio">
                                            <p style="font-size: 12px; color: red;" id="error_fecha_i"></p>
                                        </div>
                                    </div> 
                                    <div class="col-md-4 col-xs-12">
                                        <div class="form-group">
                                            <label for="fecha_final_vinculacion_modificacion">Fecha final descuento</label>
                                            <input type="text" class="form-control" id="fecha_final_vinculacion_modificacion" name="fecha_final_vinculacion_modificacion" placeholder="Fecha fin">
                                            <p style="font-size: 12px; color: red;" id="error_fecha_f"></p>
                                        </div>
                                    </div> 
                                </div>                              
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                    <button type="submit" id="vinculacion_modificacion" class="btn btn-primary">Modificar</button>
                                </div>
                            </form>
                        </div>                        
                    </div>
                </div>
            </div>

            <script>
                $(document).ready(function(){
                    $('#tabla_vinculacion').DataTable({
                        responsive: true,
                        "columns": [
                            { "data": "Nombre" },  
                            { "data": "Vinculacion" },
                            { "data": "Descuento"},
                            { "data": "Accion" }
                        ],
                        language: {
                            url: "https://cdn.datatables.net/plug-ins/1.10.12/i18n/Spanish.json"
                        },
                        columnDefs: [
                            { "searchable": false, "targets": 2 }
                        ],    
                        bSort: false,
                        bInfo: false
                    });

                    $("#tabla_vinculacion .vincular").click(function(){
                        var codigo = this.value;
                        var herramienta = "<?php echo $herramienta;?>";
                        $("#sucursal_nueva_vinculacion").val(codigo);
                        $("#herramienta_nueva_vinculacion").val(herramienta);
                        $("#nueva_vinculacion").modal();
                    });

                    $("#tabla_vinculacion .desvincular").click(function(){
                        var codigo = this.value;
                        console.log(codigo);
                        var nombre_sucursal = $("#"+codigo+"-N").html();
                        var herramienta = "<?php echo $herramienta;?>";
                        var nombre_herramienta = $("#"+herramienta+"-N").html();
                        swal({
                            buttons: {
                                aceptar: {
                                    text: "Aceptar",
                                    value: "yes",
                                },
                                cancel: "Cerrar",
                            },     
                            html: true,
                            title: "¡Confirmación de Desvinculación!",
                            text: "Desvincular la herramienta \n\n"+nombre_herramienta+" \n\nde la sucursal \n\n"+nombre_sucursal,
                            icon: "warning",          
                        })
                        .then((value) => {                                        
                            if(value=='yes'){
                                $.ajax({
                                    url: "<?=base_url();?>desvincular-herramienta",
                                    data: {codigo:codigo,herramienta:herramienta},
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
                                                title: "¡Desvinculación Exitosa!",
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
                            }                                      
                        });                        
                    });

                    $('#fecha_inicio_vinculacion_modificacion').datepicker({
                        startDate: new Date(),
                        language: "es"
                    });

                    $('#fecha_final_vinculacion_modificacion').datepicker({
                        startDate: new Date(),
                        language: "es"            
                    });

                    $('#fecha_inicio_nueva_vinculacion').datepicker({
                        startDate: new Date(),
                        language: "es"
                    });

                    $('#fecha_final_nueva_vinculacion').datepicker({
                        startDate: new Date(),
                        language: "es"            
                    });

                    $("#tabla_vinculacion .modificar").click(function(){
                        var codigo = this.value;
                        var nombre_sucursal = $("#"+codigo+"-N").html();
                        var herramienta = "<?php echo $herramienta;?>";
                        $.ajax({
                            url: "<?=base_url();?>obtener-vinculacion",
                            data: {codigo:codigo, herramienta:herramienta},
                            type: 'post',
                            success: function (data){
                                var valor = JSON.parse(data);
                                if(valor!='FALSE'){
                                    $("#sucursal_vinculacion_modificacion").val(valor.cod_sucursal);
                                    $("#nombre_vinculacion_modificacion").val(nombre_sucursal);
                                    $("#herramienta_vinculacion_modificacion").val(valor.cod_herramienta);
                                    $("#stock_vinculacion_modificacion").val(valor.stock);
                                    $("#precio_vinculacion_modificacion").val(valor.precio);
                                    $("#descuento_vinculacion_modificacion").val(valor.descuento);
                                    $("#fecha_inicio_vinculacion_modificacion").val(valor.f_inicio_d);
                                    $("#fecha_final_vinculacion_modificacion").val(valor.f_final_d);
                                    $("#vinculacion_modificacion").modal();
                                }else{
                                    swal("¡Ha ocurrido un error!", "Ha ocurrido un error interno", "error"); 
                                }
                            }
                        });                        
                    });

                    $("#form_vinculacion_modificacion").submit(function(e){                        
                        var inicio = $("#fecha_inicio_vinculacion_modificacion").val();
                        var final = $("#fecha_final_vinculacion_modificacion").val();
                        var descuento = $("#descuento_vinculacion_modificacion").val();
                        var f_i = moment(inicio,"DD/MM/YYYY"); 
                        var f_f = moment(final,"DD/MM/YYYY");
                        var verificador_descuento = 0;
                        var verificador_fecha_i = 0;
                        var verificador_fecha_f = 0;
                        if(descuento!==""){
                            verificador_descuento = 1;
                        }else{
                            verificador_descuento = 0;
                        }
                        if(inicio!==""){
                            verificador_fecha_i = 1;
                        }else{
                            verificador_fecha_i = 0;
                        }
                        if(final!==""){
                            verificador_fecha_f = 1;
                        }else{
                            verificador_fecha_f = 0;
                        }
                        
                        if((verificador_descuento==1 && verificador_fecha_i==1 && verificador_fecha_f==1)
                            || (verificador_descuento==0 && verificador_fecha_i==0 && verificador_fecha_f==0)){
                            if(verificador_fecha_i==1 && verificador_fecha_f==1 && f_f.diff(f_i)<=0){
                                swal("La fecha final no puede ser menor o igual a la inicial", "Modifique las fechas!", "error"); 
                            }else{
                                $.ajax({
                                    url: "<?=base_url();?>modificar-vinculacion",
                                    data: $('#form_vinculacion_modificacion').serialize(),
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
                            }
                        }else{
                            if(verificador_descuento==0){
                                $("#error_descuento").html("Debe ingresar el descuento del producto");
                            }else{
                                $("#error_descuento").html("");
                            }
                            if(verificador_fecha_i==0){
                                $("#error_fecha_i").html("Debe ingresar la fecha inicial del descuento");
                            }else{
                                $("#error_fecha_i").html("");
                            }
                            if(verificador_fecha_f==0){
                                $("#error_fecha_f").html("Debe ingresar la fecha final del descuento");
                            }else{
                                $("#error_fecha_f").html("");
                            }
                        }
                        e.preventDefault();
                    });

                    $("#form_nueva_vinculacion").submit(function(e){
                         var inicio = $("#fecha_inicio_nueva_vinculacion").val();
                        var final = $("#fecha_final_nueva_vinculacion").val();
                        var descuento = $("#descuento_nueva_vinculacion").val();
                        var f_i = moment(inicio,"DD/MM/YYYY"); 
                        var f_f = moment(final,"DD/MM/YYYY");
                        var verificador_descuento = 0;
                        var verificador_fecha_i = 0;
                        var verificador_fecha_f = 0;
                        if(descuento!==""){
                            verificador_descuento = 1;
                        }else{
                            verificador_descuento = 0;
                        }
                        if(inicio!==""){
                            verificador_fecha_i = 1;
                        }else{
                            verificador_fecha_i = 0;
                        }
                        if(final!==""){
                            verificador_fecha_f = 1;
                        }else{
                            verificador_fecha_f = 0;
                        }
                        if((verificador_descuento==1 && verificador_fecha_i==1 && verificador_fecha_f==1)
                            || (verificador_descuento==0 && verificador_fecha_i==0 && verificador_fecha_f==0)){
                            if(verificador_fecha_i==1 && verificador_fecha_f==1 && f_f.diff(f_i)<=0){
                                swal("La fecha final no puede ser menor o igual a la inicial", "Modifique las fechas!", "error"); 
                            }else{
                                $.ajax({
                                    url: "<?php echo base_url();?>vincular-herramienta",     
                                    data: $("#form_nueva_vinculacion").serialize(),                  
                                    type: "post",
                                    success: function(data){
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
                                                title: "¡Vinculación Exitosa!",
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
                            }
                        }else{
                            if(verificador_descuento==0){
                                $("#error_descuento_vinculacion").html("Debe ingresar el descuento del producto");
                            }else{
                                $("#error_descuento_vinculacion").html("");
                            }
                            if(verificador_fecha_i==0){
                                $("#error_fecha_i_vinculacion").html("Debe ingresar la fecha inicial del descuento");
                            }else{
                                $("#error_fecha_i_vinculacion").html("");
                            }
                            if(verificador_fecha_f==0){
                                $("#error_fecha_f_vinculacion").html("Debe ingresar la fecha final del descuento");
                            }else{
                                $("#error_fecha_f_vinculacion").html("");
                            }
                        }    
                        e.preventDefault();
                    });

                    $("#nueva_vinculacion").on("hidden.bs.modal", function () {
                        $("#form_nueva_vinculacion")[0].reset();
                        $("#error_descuento_vinculacion").html("");
                        $("#error_fecha_i_vinculacion").html("");
                        $("#error_fecha_f_vinculacion").html("");
                    });

                    $("#vinculacion_modificacion").on("hidden.bs.modal", function () {
                        $("#form_vinculacion_modificacion")[0].reset();
                        $("#error_descuento").html("");
                        $("#error_fecha_i").html("");
                        $("#error_fecha_f").html("");
                    });
                });
            </script>