<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
    </body>
    <script>
        $(document).ready(function(){
            $("#form_admin").submit(function(e){
                var rut = $("#rut_admin").val();
                var pass = $("#password_admin").val();
                if(rut=="" && pass==""){
                    swal("Los campos rut y contraseña son requeridos", "¡Ingrese un valor válido!", "error"); 
                }else{
                    if(rut!=""){
                        if(pass!=""){
                            $.ajax({
                                url: "<?=base_url();?>validacion",
                                data: {rut:rut,pass:pass},
                                type: 'post',
                                success: function (data){
                                    console.log(data);
                                    var valor = JSON.parse(data);
                                    if(valor.estado=='TRUE'){
                                        location.reload();
                                    }else{
                                        swal(valor.mensaje, "Inténtalo nuevamente!", "error"); 
                                        LimpiarForm();
                                    }
                                }
                            });
                        }else{
                            swal("El campo contraseña es requerido", "¡Ingrese un valor válido!", "error"); 
                        }
                    }else{
                        swal("El campo rut es requerido", "¡Ingrese un valor válido!", "error"); 
                    }
                }
                
                e.preventDefault();
            });

            function LimpiarForm(){
                $("#rut").val("");
                $("#password").val("");
            }
        });
    </script>
    
    
    <!-- others plugins -->
    <script src="<?php echo base_url(); ?>assets/js/plugins.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>  
    <script src="<?php echo base_url(); ?>assets/js/sweetalert.min.js"></script>   
    <script src="<?php echo base_url(); ?>assets/js/bootstrap-datepicker.min.js"></script> 
    <script src="<?php echo base_url(); ?>assets/js/bootstrap-datepicker.es.min.js"></script>    
    <script src="<?php echo base_url(); ?>assets/js/URI.min.js"></script>  
    <script src="<?php echo base_url(); ?>assets/js/moment.js"></script>  
</html>