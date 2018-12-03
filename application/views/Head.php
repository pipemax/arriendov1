<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="robots" content="all,follow">
    <meta name="googlebot" content="index,follow,snippet,archive">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Obaju e-commerce template">
    <meta name="author" content="Ondrej Svestka | ondrejsvestka.cz">
    <meta name="keywords" content="arriendo,herramientas,construccion">

    <title>
        Constru OK - Inicio
    </title>

    <meta name="keywords" content="">
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,500,700,300,100' rel='stylesheet' type='text/css'>

    <!-- styles -->
    <link href="<?php echo base_url(); ?>assets/css/font-awesome.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/css/animate.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/css/owl.carousel.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/css/owl.theme.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/css/bootstrap-datepicker.min.css" rel="stylesheet">

    <!-- theme stylesheet -->
    <link href="<?php echo base_url(); ?>assets/css/style.blue.css" rel="stylesheet" id="theme-stylesheet">

    <!-- your stylesheet with modifications -->
    <link href="<?php echo base_url(); ?>assets/css/custom.css" rel="stylesheet">

     <!-- datatables -->
    <link href="<?=base_url();?>assets/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
    <link href="<?=base_url();?>assets/css/responsive.bootstrap4.min.css" rel="stylesheet" />

    <script src="<?php echo base_url(); ?>assets/js/jquery-1.11.0.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/jquery.cookie.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/waypoints.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/modernizr.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/bootstrap-hover-dropdown.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/owl.carousel.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/front.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/respond.min.js"></script>    
    <script src="<?php echo base_url(); ?>assets/js/sweetalert.min.js"></script>   
    <script src="<?php echo base_url(); ?>assets/js/bootstrap-datepicker.min.js"></script> 
    <script src="<?php echo base_url(); ?>assets/js/bootstrap-datepicker.es.min.js"></script>    
    <script src="<?php echo base_url(); ?>assets/js/URI.min.js"></script>  
    <script src="<?php echo base_url(); ?>assets/js/moment.js"></script>  

    <!-- datatables js -->
    <script src="<?=base_url();?>assets/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="<?=base_url();?>assets/js/dataTables.bootstrap4.min.js" type="text/javascript"></script>
    <script src="<?=base_url();?>assets/js/dataTables.responsive.min.js" type="text/javascript"></script>    
    <script src="<?=base_url();?>assets/js/responsive.bootstrap4.min.js" type="text/javascript"></script>
    
    <link rel="shortcut icon" href="<?php echo base_url(); ?>assets/img/favicon.png">
</head>

<body>
<div id="top">
    <div class="row" style="margin-left: 0px; margin-right: 0px;">       
        <div class="col-md-12">     
            <div class="col-md-6 col-xs-12" data-animate="fadeInDown">   
                <div class="row">
                    <div class="col-md-6 col-xs-12"> 
                        <div class="input-group" style="width:100%;">
                            <span class="input-group-addon" style="background-color: rgb(92, 184, 92); border-color: rgb(92, 184, 92); color: white; width: 77px;" id="basic-addon1">Región</span>
                            <select class="form-control input-sm" name="region_top" id="region_top">
                                <?php 
                                    foreach($regiones as $region)
                                    {
                                ?>
                                    <option value="<?php echo $region->region_id;?>"> <?php echo $region->region_nombre;?></option>
                                <?php
                                    }

                                ?>
                            </select>
                        </div>    
                    </div>
                    <div class="col-md-6 col-xs-12">  
                        <div class="input-group" style="width:100%;">
                            <span class="input-group-addon" style="background-color: rgb(92, 184, 92); border-color: rgb(92, 184, 92); color: white;  width: 77px;" id="basic-addon1">Comuna</span>
                            <select class="form-control input-sm" name="comuna_top" id="comuna_top">
                            </select>
                        </div>     
                    </div>
                </div>                      
            </div>
            <div class="col-md-6 col-xs-12" data-animate="fadeInDown">
            <?php //PHP
                if($this->session->estado==FALSE)
                { 
            ?>
                <div class="row">
                    <div class="col-md-6 col-xs-12"></div>
                    <div class="col-md-6 col-xs-12">
                        <div class="row">
                            <a class="btn btn-success" data-toggle="modal" data-target="#login-modal">Iniciar Sesión</a>                    
                            <a class="btn btn-primary" href="<?php echo base_url(); ?>registrarse">Registrarse</a>
                            <a class="btn btn-danger" href="<?php echo base_url(); ?>contacto">Contacto</a>
                            </div>
                    </div>
                </div>
            <?php //PHP
                }
                else
                {
            ?>  
                <div class="row">
                    <div class="col-md-2 col-xs-12"></div>
                    <div class="col-md-10 col-xs-12">
                        <div class="row">
                            <a class="btn btn-info"> Bienvenid@ <?php echo $this->session->nombres." ".$this->session->apellidos; ?></a>                    
                            <a class="btn btn-success" href="<?php echo base_url(); ?>mi-cuenta">Mi cuenta</a>
                            <a class="btn btn-danger" href="<?php echo base_url(); ?>salir">Cerrar Sesión</a>
                        </div>
                    </div>
                </div>
            <?php //PHP
                } 
            ?>
            </div>    
        </div> 
    </div>
    <div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="Login" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="Login">Inicio de Cliente</h4>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <input type="text" class="form-control i_rut" name="rut_sesion" id="rut_sesion" minlength="7" maxlength="10" placeholder="Rut Ej: 18572832-1" required>
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control i_pass" name="password_sesion" id="password_sesion" maxlength="100" placeholder="Contraseña" required>
                        </div>
                        <p class="text-center">
                            <button class="btn btn-primary" id="boton_modal" type="button"><i class="fa fa-sign-in"></i> Iniciar</button>
                        </p>
                    </form>
                    <p class="text-center text-muted">¿No registrado aún?</p>
                    <p class="text-center text-muted"><a href="<?php echo base_url(); ?>registrarse"><strong>Registrarse ahora</strong></a>! Es sencillo y solo tomará 1&nbsp;minuto!</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="navbar navbar-default yamm" role="navigation" id="navbar">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand home" href="<?=base_url();?>">
                <img src="<?php echo base_url(); ?>assets/img/logo.png" alt="Constru OK" class="hidden-xs">
                <img src="<?php echo base_url(); ?>assets/img/logo.png" alt="Obaju logo" class="visible-xs"><span class="sr-only">Inicio</span>
            </a>
            
            <div class="navbar-buttons">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation">
                    <span class="sr-only">Toggle navigation</span>
                    <i class="fa fa-align-justify"></i>
                </button>
                <?php //PHP
                    if($this->session->estado==TRUE)
                    { 
                ?>
                    <?php //PHP
                        if($cantidad[0]->cantidad!=0)
                        { 
                    ?>
                        <a class="btn btn-default navbar-toggle" href="<?php echo base_url(); ?>carrito/">
                            <i class="fa fa-shopping-cart"></i>
                            <?php echo $cantidad[0]->cantidad; ?>
                        </a>
                    <?php //PHP
                        }
                        else
                        { 
                    ?>
                        <button class="btn btn-default navbar-toggle">
                            <i class="fa fa-shopping-cart"></i>
                            <?php echo $cantidad[0]->cantidad; ?>
                        </button>
                    <?php //PHP
                        } 
                    ?>
                <?php //PHP
                    } 
                ?>
            </div>
        </div>
        <div class="navbar-collapse collapse" id="navigation">
            <ul class="nav navbar-nav navbar-left">
                <li class="active"><a href="<?php echo base_url(); ?>">Inicio</a>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="200">Nosotros <b class="caret"></b></a>
                    <ul class="dropdown-menu">                        
                        <li>
                            <a href="<?php echo base_url(); ?>mision/">Misión</a>
                        </li>
                        <li>
                            <a href="<?php echo base_url(); ?>vision/">Visión</a>
                        </li>
                        <li>
                            <a href="<?php echo base_url(); ?>quienes-somos/">Quienes Somos</a>
                        </li>                                        
                    </ul>
                </li>

                <li class="dropdown">
                    <a href="<?php echo base_url(); ?>productos/" class="dropdown" data-hover="dropdown" data-delay="200">Productos <b class="caret"></b></a>
                    <ul class="dropdown-menu">      
                        <?php //PHP
                            foreach($categorias as $valor)
                            {
                        ?>                  
                        <li>                            
                            <a href="<?php echo base_url(); ?>productos/?categoria=<?php echo $valor->cod_categoria; ?>"><?php echo $valor->nombre; ?></a>
                        </li>
                        <?php //PHP
                            } 
                        ?>
                    </ul>
                </li>           

                <li class="dropdown">
                    <a href="<?php echo base_url(); ?>contacto/" class="dropdown-toggle" data-delay="200">Contacto</a>
                </li>
                <?php //PHP
                    if($this->session->estado==TRUE)
                    { 
                    ?>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="200">Mi Cuenta <b class="caret"></b></a>
                        <ul class="dropdown-menu">                        
                            <li>
                                <a href="<?php echo base_url(); ?>mi-cuenta">Mis arriendos</a>
                            </li>
                            <li>
                                <a href="<?php echo base_url(); ?>mis-datos">Mis datos personales</a>
                            </li>
                            <li>
                                <a href="<?php echo base_url(); ?>salir">Cerrar Sesión</a>
                            </li>                                        
                        </ul>
                    </li>
                <?php 
                    } 
                ?>
            </ul>

        </div>

        <div class="navbar-buttons">
            <?php //PHP
                if($this->session->estado == TRUE)
                {
            ?>
                <div class="navbar-collapse collapse right" id="basket-overview">
                    <?php //PHP
                        if($cantidad[0]->cantidad>0)
                        { 
                    ?>
                        <a href="<?php echo base_url(); ?>carrito" id="boton_carrito" class="btn btn-primary navbar-btn"><i class="fa fa-shopping-cart"></i>
                    <?php //PHP
                        }
                        else
                        { 
                    ?>
                        <a class="btn btn-primary navbar-btn"><i class="fa fa-shopping-cart"></i>
                    <?php //PHP
                        }
                    ?>
                            <span class="hidden-sm">
                                <?php //PHP
                                    if($cantidad[0]->cantidad>0)
                                    {
                                        if($cantidad[0]->cantidad==1)
                                        {
                                            $mensaje = " item en el carrito";
                                        }
                                        else
                                        {
                                            $mensaje = " items en el carrito";
                                        }
                                        echo $cantidad[0]->cantidad.$mensaje;
                                    }
                                    else
                                    {
                                        echo "No tiene items en el carrito";
                                    }
                                ?>                        
                            </span>
                    </a>
                </div>
            <?php //PHP
                } 
            ?>            
        </div>
    </div>
</div>



<script>
    $(document).ready(function(){
        $("#region_top").val('<?php echo $this->session->region?>');
        var region_top = $("#region_top").val();
        establecerComunaTop(region_top);

        $("#region_top").change(function(){
            var region = this.value;
            $("#comuna_top").attr('readonly', true);
            $("#comuna_top").children().remove();
            establecerComunaTop(region);
            select = document.getElementById('comuna_top'); 
            option = document.createElement('option');
            option.value = "";
            option.innerHTML = "Seleccione una comuna";
            select.append(option);
            select.value = "";
        });

        function establecerComunaTop(region){
            $.ajax({
                url: "<?=base_url()?>Inicio/obtener_comunas",
                data: {region: region},
                type: 'post',
                success: function (data){
                    if(data!=='FALSE'){
                        var datos = JSON.parse(data);
                        select = document.getElementById('comuna_top');         
                        var contador = 0;              
                        var comuna_actual = '<?php echo $this->session->comuna;?>';
                        for(var i=0;i<datos.length;i++){
                            option = document.createElement('option');
                            option.value = datos[i].comuna_id;
                            option.innerHTML = datos[i].comuna_nombre;
                            if(datos[i].comuna_id===comuna_actual){
                                contador++;
                            }
                            select.append(option);
                        }
                        $("#comuna_top").attr('readonly', false);    
                        if(contador>0){
                            select.value = '<?php echo $this->session->comuna;?>';     
                        }else{
                            select.value = '';
                        }   
                    }
                }
            });                        
        }

        $("#comuna_top").change(function(){
            var region = $("#region_top").val();
            var comuna = $("#comuna_top").val();
            if(comuna!==""){
                $.ajax({
                    url: "<?php echo base_url(); ?>comuna",
                    data: {region: region,comuna: comuna},
                    type: 'post',
                    success: function (data){
                        location.reload();
                    }
                });
            }else{
                swal("¡Atención!", "Debe seleccionar una comuna en el navegador superior", "error"); 
            }
        });
    });

</script>