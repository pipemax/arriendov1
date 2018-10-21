<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- *** FOOTER ***
 _________________________________________________________ -->
        <div id="footer" data-animate="fadeInUp"  style="padding-left: 0px; padding-right: 0px;">
            <div class="container">
                <div class="row">
                    <div class="col-md-3 col-sm-6">
                        <h4>Paginas</h4>

                        <ul>
                            <li>
                                <a href="text.html">Nosotros</a>
                            </li>
                            <li>
                                <a href="text.html">Terminos y Condiciones</a>
                            </li>
                            <li>
                                <a href="contact.html">Contáctanos</a>
                            </li>
                        </ul>

                        <hr>
                        <h4>Sección de Usuario</h4>
                        <ul>
                            <li><a href="#" data-toggle="modal" data-target="#login-modal">Inicio de Sesión</a>
                            </li>
                            <li><a href="registrarse">Registrarse</a>
                            </li>
                        </ul>
                        <hr class="hidden-md hidden-lg hidden-sm">
                    </div>
                    <!-- /.col-md-3 -->

                    <div class="col-md-3 col-sm-6">
                        <h4>Arriendos </h4>
                        <ul>
                            <li><a href="category.html">T-shirts</a>
                            </li>
                            <li><a href="category.html">Skirts</a>
                            </li>
                            <li><a href="category.html">Pants</a>
                            </li>
                            <li><a href="category.html">Accessories</a>
                            </li>
                        </ul>

                        <hr class="hidden-md hidden-lg">

                    </div>
                    <!-- /.col-md-3 -->

                    <div class="col-md-3 col-sm-6">

                        <h4>Nos puedes encontrar en</h4>

                        <p><strong>Constru Ok Ltd.</strong>
                            <br>Av. San Miguel
                            <br>51 Oriente
                            <br>Número 5872
                            <br>Talca
                            <br>
                            <strong>Chile</strong>
                        </p>

                        <a href="<?=base_url()?>contacto">Ir a la página de Contacto</a>

                        <hr class="hidden-md hidden-lg">

                    </div>
                    <!-- /.col-md-3 -->



                    <div class="col-md-3 col-sm-6">

                        <h4>Nuestras redes sociales</h4>

                        <p class="social">
                            <a href="#" class="facebook external" data-animate-hover="shake"><i class="fa fa-facebook"></i></a>
                            <a href="#" class="instagram external" data-animate-hover="shake"><i class="fa fa-instagram"></i></a>
                            <a href="#" class="email external" data-animate-hover="shake"><i class="fa fa-envelope"></i></a>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div id="copyright">
            <div class="container">
                <div class="col-md-6">
                    <p class="pull-left">© 2018 Constru Ok.</p>

                </div>
                <div class="col-md-6">
                    <p class="pull-right">Template ofrecido por <a href="https://bootstrapious.com/e-commerce-templates">Bootstrapious</a> & <a href="https://fity.cz">Fity</a>
                         <!-- Not removing these links is part of the license conditions of the template. Thanks for understanding :) If you want to use the template without the attribution links, you can do so after supporting further themes development at https://bootstrapious.com/donate  -->
                    </p>
                </div>
            </div>
        </div>
    </div>

</body>
<script>
    $(document).ready(function(){
        

        $("#boton_modal").click(function(){
            var rut = $("#rut_sesion").val();
            var pass = $("#password_sesion").val();
            if(rut=="" || pass==""){
                swal("Hay campos vacíos en el formulario", "Verifique los datos ingresados!", "error"); 
            }else{
                $.ajax({
                    url: "<?=base_url()?>validacion",
                    data: {rut_sesion: rut,password_sesion:pass},
                    type: 'post',
                    success: function (data){
                        var valor = JSON.parse(data);
                        if(valor.estado=='TRUE'){
                            location.reload();
                        }else{
                            swal(valor.mensaje, "Inténtalo nuevamente!", "error"); 
                            $("#rut_sesion").val("");
                            $("#password_sesion").val("");
                        }
                    }
                });
            }
        });

        $('.formulario').submit(function(e){
            $.ajax({
                url: "<?=base_url()?>validacion",
                data: $(".formulario").serialize(),
                type: 'post',
                success: function (data){
                    var valor = JSON.parse(data);
                    if(valor.estado=='TRUE'){
                        location.reload();
                    }else{
                        swal(valor.mensaje, "Inténtalo nuevamente!", "error"); 
                        LimpiarForm();
                    }
                }
            });
            e.preventDefault();
        });

        $('#fecha_inicial').datepicker({
            startDate: new Date(),
            language: "es"
        });

        $('#fecha_final').datepicker({
            startDate: new Date(),
            language: "es"            
        });

        $("#guardar_fecha").click(function(){            
            var inicio = $("#fecha_inicial").val();
            var final = $("#fecha_final").val();
            var f_i = moment(inicio,"DD/MM/YYYY"); 
            var f_f = moment(final,"DD/MM/YYYY");
            if(f_f.diff(f_i)<=0){
                swal("La fecha final no puede ser menor o igual a la inicial", "Modifique las fechas!", "error"); 
            }else{
                $.ajax({
                    url: "<?=base_url()?>fechas",
                    data: {inicio: inicio,fin: final},
                    type: 'post',
                    success: function (data){
                        if(data=='TRUE'){
                            location.reload();
                        }
                    }
                });
            }
        });

        $('#fecha_inicial_i').datepicker({
            startDate: new Date(),
            language: "es"
        });

        $('#fecha_final_i').datepicker({
            startDate: new Date(),
            language: "es"            
        });

        $("#formulario_inicio").submit(function(e){            
            var inicio = $("#fecha_inicial_i").val();
            var final = $("#fecha_final_i").val();
            var region = $("#region").val();
            var comuna = $("#comuna").val();
            var texto = $("#busqueda").val();
            var f_i = moment(inicio,"DD/MM/YYYY"); 
            var f_f = moment(final,"DD/MM/YYYY");
            var busqueda = $("#busqueda").val();
            if(f_f.diff(f_i)<=0){
                swal("La fecha final no puede ser menor o igual a la inicial", "Modifique las fechas!", "error"); 
            }else{
                $.ajax({
                    url: "<?=base_url()?>busqueda",
                    data: {inicio:inicio,final:final,region:region,comuna:comuna},
                    type: 'post',
                    success: function (data){
                        arreglar_url_inicio("search",busqueda); 
                    }
                });
                /*$.ajax({
                    url: "<?=base_url()?>fechas",
                    data: {inicio: inicio,fin: final},
                    type: 'post',
                    success: function (data){
                        if(data=='TRUE'){
                            location.reload();
                        }
                    }
                });*/
            }
            e.preventDefault();
        });
        
        function arreglar_url_inicio(nombre_item,valor_item){
            var url = new URI('<?php echo base_url()?>productos');
            if(url.hasQuery(nombre_item)===true){
                url.setSearch(nombre_item,valor_item);
            }else{
                url.addSearch(nombre_item,valor_item)
            }
            window.location.href = url.toString();
        }

        function LimpiarForm(){
            $(".i_rut").val("");
            $(".i_pass").val("");
        }
    });
</script>
</html>