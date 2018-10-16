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
    <link rel="shortcut icon" href="<?php echo base_url(); ?>assets/img/favicon.png">
</head>

<body>

<div id="top">
    <div class="container">
        <div class="col-md-6 offer" data-animate="fadeInDown">  
            <a href="#" class="btn btn-success btn-sm" data-toggle="modal" data-target="#sucursal-modal">SUCURSAL <?php echo $sucursal[0]->nombre; ?></a>
        </div>
        <div class="col-md-6" data-animate="fadeInDown">
            <?php //PHP
                if($this->session->estado==FALSE)
                { 
            ?>
                <ul class="menu">
                    <li>
                        <a href="#" data-toggle="modal" data-target="#login-modal">Iniciar Sesión</a>
                    </li>
                    <li>
                        <a href="<?php echo base_url(); ?>registrarse">Registrarse</a>
                    </li>
                    <li>
                        <a href="<?php echo base_url(); ?>contacto">Contacto</a>
                    </li>
                </ul>
            <?php //PHP
                }
                else
                {
            ?>
                <ul class="menu">
                    <li>
                        <a>Bienvenido <?php echo $this->session->nombres." ".$this->session->apellidos; ?></a>
                    </li>
                    <li>
                        <a href="<?php echo base_url(); ?>mi-cuenta">Mi Cuenta</a>
                    </li>
                    <li>
                        <a href="<?php echo base_url(); ?>salir">Cerrar Sesión</a>
                    </li>
                </ul>
            <?php //PHP
                } 
            ?>
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
                        <a class="btn btn-default navbar-toggle" href="<?php echo base_url(); ?>carrito">
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
                            <a href="<?php echo base_url(); ?>mision">Misión</a>
                        </li>
                        <li>
                            <a href="<?php echo base_url(); ?>vision">Visión</a>
                        </li>
                        <li>
                            <a href="<?php echo base_url(); ?>quienes-somos">Quienes Somos</a>
                        </li>                                        
                    </ul>
                </li>

                <li class="dropdown">
                    <a href="<?php echo base_url(); ?>productos" class="dropdown" data-hover="dropdown" data-delay="200">Productos <b class="caret"></b></a>
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
                    <a href="<?php echo base_url(); ?>contacto" class="dropdown-toggle" data-delay="200">Contacto</a>
                </li>
                <?php //PHP
                    if($this->session->estado==TRUE)
                    { 
                    ?>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="200">Mi Cuenta <b class="caret"></b></a>
                        <ul class="dropdown-menu">                        
                            <li>
                                <a href="category.html">Mis arriendos</a>
                            </li>
                            <li>
                                <a href="category.html">Mis datos personales</a>
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

<div class="modal fade" id="sucursal-modal" tabindex="-1" role="dialog" aria-labelledby="Sucursal" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <center><h4 class="modal-title" id="Login">Sucursales</h4></center>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <center>
                            <label for="sucursales">Sucursal Actual</label>
                        </center>
                        <center>
                            <button class="btn btn-primary" type="button">
                                SUCURSAL <?=$sucursal[0]->nombre?>
                            </button>
                        </center>
                    </div>
                    <div class="form-group">
                        <center><label for="sucursales">Cambiar de Sucursal</label></center>
                        <select class="form-control" id="sucursales">
                            <?php //PHP
                                foreach($sucursales as $valor)
                                {
                            ?>
                                <option value="<?=$valor->cod_sucursal?>"><?=$valor->nombre?></option>
                            <?php //PHP
                                } 
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <center><label for="datos">Información de Sucursal</label></center>
                        <ul class="list-group" id="datos">
                            <li class="list-group-item"><?=$sucursal[0]->direccion?></li>
                            <li class="list-group-item"><?=$sucursal[0]->telefono?></li>
                        </ul>
                    </div>
                    <p class="text-center">
                        <button class="btn btn-success" id="sucursal_guardar" type="button"><i class="fa fa-check"></i> Guardar</button>
                        <button class="btn btn-default" type="button" data-dismiss="modal">Cerrar</button>
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $("#sucursales").val('<?php echo $sucursal[0]->cod_sucursal?>');
        $("#sucursal_guardar").click(function(){
            var sucursal = $("#sucursales").val();
            $.ajax({
                url: "<?php echo base_url(); ?>sucursal",
                data: {sucursal: sucursal},
                type: 'post',
                success: function (data)                {
                    if(data=='TRUE')                    {
                        location.reload();
                    }
                }
            });
        });
    });

</script>