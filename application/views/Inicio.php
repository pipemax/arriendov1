<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div id="all">
    <div id="content">
        <div class="row" style="padding-left: 30px; padding-right: 30px;">
            <form id="formulario_inicio">
                <div class="col-md-6 col-xs-12" style="background-color: #FFF157; margin-bottom: 20px;">
                    <h2 style="color: black">¿Qué necesitas para tu proyecto?</h2>
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <div class="form-group">
                                <label for="busqueda">Herramienta o Maquinaria</label>
                                <input type="text" class="form-control input-lg" name="busqueda" id="busqueda" placeholder="Ingrese herramienta o maquinaria" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group">
                                <label for="region">Seleccione Región</label>
                                <select class="form-control input-lg" name="region" id="region">
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
                            <div class="form-group">
                                <label for="comuna">Seleccione Comuna</label>
                                <select class="form-control input-lg" name="comuna" id="comuna" required></select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group">
                                <label for="fecha_inicial_i">Fecha Arriendo</label>
                                <input type="text" class="form-control input-lg" id="fecha_inicial_i" value="<?php echo $this->session->inicio; ?>" readonly>
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group">
                                <label for="fecha_final_i">Fecha Devolución</label>
                                <input type="text" class="form-control input-lg" id="fecha_final_i" value="<?php echo $this->session->fin; ?>" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <center><button class="btn btn-primary btn-lg" type="submit">Buscar</button></center>
                        </div>
                    </div>
                </div> 
            </form>
            <div class="col-md-3 col-xs-12" style="">
                <div id="main-slider">
                    <?php //PHP
                        foreach($arriendos as $item)
                        { 
                    ?>
                        <div class="item">
                            <div class="product" style="border: 0px; margin-bottom: auto; height: auto;">
                                <a href="<?php echo base_url(); ?>detalle/<?php echo $item->cod_h; ?>">
                                    <center><img src="<?php echo base_url(); ?>assets/herramientas/<?php echo $item->url_foto; ?>" alt="" class="img-responsive" style="height: 253px;"></center>
                                </a>
                                <div class="text" style="padding-bottom: 21px;">
                                    <h3><a href="<?php echo base_url(); ?>detalle/<?php echo $item->cod_h; ?>"><?php echo $item->nombre; ?></a></h3>
                                    <p class="price">$<?php echo number_format($item->precio, 0,'.', '.'); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php 
                        } 
                    ?>
                </div>
            </div>   
            <div class="col-md-3 col-xs-12" style="">
                <div id="main-slider2">
                    <?php //PHP
                        foreach($arriendos as $item)
                        { 
                    ?>
                        <div class="item">
                            <div class="product" style="border: 0px; margin-bottom: auto; height: auto;">
                                <a href="<?php echo base_url(); ?>detalle/<?php echo $item->cod_h; ?>">
                                    <center><img src="<?php echo base_url(); ?>assets/herramientas/<?php echo $item->url_foto; ?>" alt="" class="img-responsive" style="height: 253px;"></center>
                                </a>
                                <div class="text" style="padding-bottom: 21px;">
                                    <h3><a href="<?php echo base_url(); ?>detalle/<?php echo $item->cod_h?>"><?php echo $item->nombre; ?></a></h3>
                                    <p class="price">$<?php echo number_format($item->precio, 0,'.', '.'); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php //PHP
                        } 
                    ?>
                </div>
            </div>             
        </div>

        <div id="advantages">
            <div class="container">
                <div class="same-height-row">
                    <div class="col-sm-4">
                        <div class="box same-height">
                            <div class="icon"><i class="fa fa-heart"></i>
                            </div>

                            <h3><a>Queremos lo mejor para tus proyectos</a></h3>
                            <p>Somos conocidos por proveer un servicio de alta calidad.</p>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="box same-height">
                            <div class="icon"><i class="fa fa-tags"></i>
                            </div>

                            <h3><a>Precios Accesibles</a></h3>
                            <p>Contamos con amplio stock de herramientas, a precios competitivos del mercado.</p>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="box same-height clickable">
                            <div class="icon"><i class="fa fa-thumbs-up"></i>
                            </div>

                            <h3><a href="#">100% satisfacción garantizada</a></h3>
                            <p>Invertimos en conseguir las mejores herramientas para que concretes tus proyectos.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php //PHP
            if($arriendos!=FALSE)
            { 
        ?>
        <div id="hot">
            <div class="box">
                <div class="container">
                    <div class="col-md-12">
                        <h2>Las herramientas más solicitadas</h2>
                    </div>
                </div>
            </div>

            <div class="container">
                <div class="product-slider">
                    <?php //PHP
                        foreach($arriendos as $item)
                        { 
                    ?>
                        <div class="item">
                            <div class="product">
                                <a href="<?php echo base_url(); ?>detalle/<?php echo $item->cod_h; ?>">
                                    <img src="<?php echo base_url(); ?>assets/herramientas/<?php echo $item->url_foto; ?>" alt="" class="img-responsive">
                                </a>
                                <div class="text">
                                    <h3><a href="<?php echo base_url(); ?>detalle/<?php echo $item->cod_h; ?>"><?php echo $item->nombre; ?></a></h3>
                                    <p class="price">$<?php echo number_format($item->precio, 0,'.', '.');?></p>
                                </div>
                            </div>
                        </div>
                    <?php //PHP
                        } 
                    ?>
                </div>
            </div>
        </div>

        <?php //PHP
            } 
        ?>
    </div>

    <script>
        $(document).ready(function(){
            $("#region").val("<?php echo $this->session->region;?>")
            var region = $("#region").val();
            establecerComuna(region);

            $("#region").change(function(){
                var region = this.value;
                $("#comuna").attr('readonly', true);
                $("#comuna").children().remove();
                establecerComuna(region);
                select = document.getElementById('comuna'); 
                option = document.createElement('option');
                option.value = "";
                option.innerHTML = "Seleccione una comuna";
                select.append(option);
                select.value = "";
            });

            function establecerComuna(region){
                $.ajax({
                    url: "<?=base_url()?>Inicio/obtener_comunas",
                    data: {region: region},
                    type: 'post',
                    success: function (data){
                        if(data!=='FALSE'){
                            var datos = JSON.parse(data);
                            select = document.getElementById('comuna');  
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
                            $("#comuna").attr('readonly', false);
                            if(contador>0){
                                select.value = '<?php echo $this->session->comuna;?>';     
                            }else{
                                select.value = '';
                            }   
                        }
                    }
                });
            }
                       
        });
    </script>
