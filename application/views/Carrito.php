<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div id="all">
	<div id="content">
		<div class="row" style="padding-left: 10px; padding-right: 10px; margin-left: 0px; margin-right: 0px;"> 
			<div class="col-md-12">
				<ul class="breadcrumb">
					<li><a href="<?php echo base_url(); ?>">Inicio</a>
					</li>
					<li>Carro de Arriendos</li>
				</ul>
			</div>
		
			<div class="col-md-9" id="basket">
				<div class="box">
					<h1>Carro de Arriendos</h1>
					<p class="text-muted">Actualmente tienes <?php echo $cantidad[0]->cantidad; ?> item(s) en el carro.</p>
					<div class="row">
						<div class="col-sm-12 col-md-4 products-showing">
							<label for="fecha_inicial2">Fecha inicial del arriendo</label>
							<input class="form-control" type="text" id="fecha_inicial2" value="<?php echo $this->session->inicio; ?>" readonly>
						</div>
						<div class="col-sm-12 col-md-4 products-showing">
							<label for="fecha_final2">Fecha final del arriendo</label>
							<input class="form-control" type="text" id="fecha_final2" value="<?php echo $this->session->fin; ?>" readonly>
						</div>
						<div class="col-sm-12 col-md-4 products-showing">
							<label for="cant_dias">Cantidad de días del arriendo</label>
							<?php 
								$date1 = DateTime::createFromFormat('d/m/Y', $this->session->inicio);
								$date2 = DateTime::createFromFormat('d/m/Y', $this->session->fin);
							?>
							<input class="form-control" type="text" id="cant_dias" value="<?php echo $date2->diff($date1)->format("%a");; ?>" readonly>
						</div>     
					</div>
					<br>
					<div class="table-responsive">
						<table class="table table-responsive">
							<thead>
								<tr>
									<th class="col-md-1" style="text-align:center">Herramienta</th>
									<th class="col-md-4" style="text-align:center"></th>
									<th class="col-md-1" style="text-align:center">Cantidad</th>
									<th class="col-md-1" style="text-align:center">Precio unitario</th>
									<th class="col-md-1" style="text-align:center">Descuento</th>
									<th class="col-md-1" style="text-align:center">Total </th>
									<th class="col-md-1" style="text-align:center"></th>
								</tr>
							</thead>
							<tbody>
								<?php 
									$verificar_fecha = 0;
									foreach($carrito as $herramienta)
									{
										
								?>
								<?php 
									if($herramienta->cantidad <= $herramienta->disponibilidad)
									{
										echo '<tr>';
									}
									else
									{
								?>
								<div class="alert alert-danger" role="alert">
									<strong><i class="fa fa-warning"></i> ¡Atención!</strong>
									- La(s) herramienta(s) seleccionada(s) no poseen stock en la fecha seleccionada. Por lo tanto, no podrá arrendarlas.
								</div>
								<?php
										echo '<tr class="info">';
									}
								?>
									<td class="col-md-1 table-primary" style="text-align:center">
										<a href="<?php echo base_url(); ?>detalle/<?php echo $herramienta->cod_herramienta;?>/<?php echo $herramienta->cod_sucursal;?>/<?php echo $herramienta->cod_empresa;?>">
											<img src="<?php echo base_url(); ?>assets/herramientas/<?php echo $herramienta->url_foto; ?>" alt="<?php echo $herramienta->nombre; ?>">
										</a>
									</td>
									<td class="col-md-4">
										<a class="btn btn-default" href="<?php echo base_url(); ?>detalle/<?php echo $herramienta->cod_herramienta;?>/<?php echo $herramienta->cod_sucursal;?>/<?php echo $herramienta->cod_empresa;?>"><?php echo $herramienta->nombre; ?></a>
										<br>
										<button type="button" class="btn btn-info btn-xs" readonly><?php echo $herramienta->nombree; ?></button>
										<button type="button" class="btn btn-danger btn-xs" readonly>SUCURSAL <?php echo $herramienta->nombres; ?></button>
									</td>
									<?php 
										if($herramienta->cantidad <= $herramienta->disponibilidad)
										{
									?>
										<td class="col-md-1">
											<button class="hidden" id="<?php echo $herramienta->cod_herramienta."-".$herramienta->cod_sucursal; ?>-datos" value="<?php echo $herramienta->cod_sucursal."-".$herramienta->cod_empresa;?>"></button>
											<input type="number" id="<?php echo $herramienta->cod_herramienta."-".$herramienta->cod_sucursal; ?>-cantidad" style="width:100%" value="<?php echo $herramienta->cantidad; ?>" min="1" max="<?php echo $herramienta->disponibilidad; ?>" class="form-control cantidad">
											<input class="hidden" id="<?php echo $herramienta->cod_herramienta."-".$herramienta->cod_sucursal; ?>-respaldo" value="<?php echo $herramienta->cantidad; ?>">
										</td>
									<?php
										}
										else
										{
									?>
										<td class="col-md-1">
											<center>SIN STOCK</center>										
										</td>
									<?php									
										}									
									?>									
									<td class="col-md-1" style="text-align:center">$<?php echo $herramienta->precio; ?></td>
									<input class="hidden" id="<?php echo $herramienta->cod_herramienta."-".$herramienta->cod_sucursal; ?>-descuento" value="<?php echo $herramienta->descuento; ?>">
									<td class="col-md-1" style="text-align:center"><?php echo $herramienta->descuento;?>%</td>
									<input class="hidden" id="<?php echo $herramienta->cod_herramienta."-".$herramienta->cod_sucursal; ?>-precio" value="<?php echo $herramienta->precio; ?>">
									<td class="col-md-1" id="<?php echo $herramienta->cod_herramienta."-".$herramienta->cod_sucursal; ?>-total" style="text-align:center">$<?php echo $herramienta->total; ?></td>
									<td class="col-md-1" style="text-align:center"><button type="button" class="btn btn-default btn-xs eliminar_h" value="<?php echo $herramienta->cod_herramienta."-".$herramienta->cod_sucursal."-".$herramienta->cod_empresa; ?>"><i class="fa fa-trash-o"></i></button>
									</td>
								</tr>
								<?php
									}
								?>
							</tbody>
							<tfoot>
								<tr>
									<th colspan="5">Total</th>
									<th colspan="2" id="total">$<?php echo number_format($total, 0,'.', '.'); ?></th>
								</tr>
							</tfoot>
						</table>
					</div>
					<!-- /.table-responsive -->

					<div class="box-footer">
						<div class="pull-left">
							<a href="<?php echo base_url(); ?>productos" class="btn btn-default"><i class="fa fa-chevron-left"></i> Continuar cotizando</a>
						</div>
						<div class="pull-right">                           
							<button type="button" class="btn btn-primary" id="boton_arriendo">Proceder al arriendo <i class="fa fa-chevron-right"></i>
							</button>
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
						<?php $cantidad_dias = $date2->diff($date1)->format("%a"); ; ?>
						<table class="table">
							<tbody>
								<tr>
									<td>Cantidad de días</td>
									<th id="n_dias"><?php echo $cantidad_dias; ?></th>
								</tr>
								<tr>
									<td>Sub-total</td>
									<th id="subtotal">$<?php echo number_format(($total-($total*0.19))*$cantidad_dias, 0,'.', '.'); ?></th>
								</tr>
								<tr>
									<td>Cálculo IVA</td>
									<th id="iva">$<?php echo number_format(($total*0.19)*$cantidad_dias, 0,'.', '.'); ?></th>
								</tr>
								<tr>
									<td>IVA</td>
									<th>19%</th>
								</tr>
								<tr class="total">
									<td>Total</td>
									<th id="total2">$<?php echo number_format($total*$cantidad_dias, 0,'.', '.'); ?></th>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<!-- /.col-md-3 -->
		</div>
	</div>
	<!-- /#content -->

	<script>
		$(document).ready(function(){
			$(".cantidad").change(function(){
				var cantidad_actual = parseInt($(this)[0].value);
				var division = ($(this)[0].id).split("-");
				var id = $(this)[0].id;
				var codigo_producto = division[0];
				var datos = ($("#"+codigo_producto+"-"+division[1]+"-datos").val()).split("-");
				var sucursal = datos[0];
				var empresa = datos[1];
				var cantidad_respaldo = parseInt($("#"+division[0]+"-"+division[1]+"-respaldo").val());
				document.getElementById(id).disabled = true;
				if(cantidad_actual<cantidad_respaldo){
					$.ajax({
						url: "<?php echo base_url(); ?>carrito-quitar",
						data: {codigo: codigo_producto,cantidad: (cantidad_respaldo-cantidad_actual),sucursal: sucursal,empresa:empresa},
						type: "post",
						success: function(data){
							var conversion = JSON.parse(data);
							if(conversion.estado=='TRUE'){
								var total_x = $("#"+division[0]+"-"+division[1]+"-precio").val()*cantidad_actual;
								var total_descuento = (total_x - total_x*($("#"+division[0]+"-"+division[1]+"-descuento").val()/100));
								$("#"+division[0]+"-"+division[1]+"-total").html("$"+Math.round(total_descuento));
								$("#"+division[0]+"-"+division[1]+"-respaldo").val(cantidad_actual);
								Actualizar_Total();
								document.getElementById(id).disabled = false;
							}else if(conversion.estado=='LOGIN'){                                
								swal({
									icon: "warning",
									text: "Para manipular el carrito debe iniciar sesión",
									buttons: {                                
										inicio: "Iniciar Sesión",
										registro: "Registrarme",
										cancel: "Cerrar"
									},
								}).then(function(buttons){
									if(buttons=="inicio"){
										$("#login-modal").modal();
									}else if(buttons=="registro"){
										window.location = "<?php echo base_url(); ?>registrarse";
									}
								});
							}else{
								swal(conversion.mensaje, "Ha ocurrido un error", "error");  
							}                            
						}
					});         
				}else if(cantidad_actual>cantidad_respaldo){
					$.ajax({
						url: "<?php echo base_url(); ?>carrito-agregar",
						data: {codigo: codigo_producto,cantidad: (cantidad_actual-cantidad_respaldo),sucursal: sucursal,empresa: empresa},
						type: "post",
						success: function(data){
							var conversion = JSON.parse(data);
							if(conversion.estado=='TRUE'){
								var total_x = $("#"+division[0]+"-"+division[1]+"-precio").val()*cantidad_actual;
								var total_descuento = (total_x - total_x*($("#"+division[0]+"-"+division[1]+"-descuento").val()/100));
								$("#"+division[0]+"-"+division[1]+"-total").html("$"+Math.round(total_descuento));
								$("#"+division[0]+"-"+division[1]+"-respaldo").val(cantidad_actual);
								Actualizar_Total();
								document.getElementById(id).disabled = false;
							}else if(conversion.estado=='LOGIN'){                                
								swal({
									icon: "warning",
									text: "Para manipular el carrito debe iniciar sesión",
									buttons: {                                
										inicio: "Iniciar Sesión",
										registro: "Registrarme",
										cancel: "Cerrar"
									},
								}).then(function(buttons){
									if(buttons=="inicio"){
										$("#login-modal").modal();
									}else if(buttons=="registro"){
										window.location = "<?php echo base_url(); ?>registrarse";
									}
								});
							}else{
								swal(conversion.mensaje, "Ha ocurrido un error", "error");  
							}                            
						}
					});      
				}else{
					document.getElementById(id).disabled = false;
				}
				
			});

			function Actualizar_Total(){
				var n_dias = <?php echo $cantidad_dias; ?>;
				$.ajax({
					url: "<?php echo base_url(); ?>total-carro",
					type: "get",
					success: function(data){
						$("#total").html("$"+addCommas(data));                        
						$("#subtotal").html("$"+addCommas(Math.round((data-(data*0.19))*n_dias)));
						$("#iva").html("$"+addCommas(Math.round(data*0.19)*n_dias));
						$("#total2").html("$"+addCommas(data*n_dias));                        
					}
				});
			}

			function addCommas(nStr){
				nStr += '';
				x = nStr.split('.');
				x1 = x[0];
				x2 = x.length > 1 ? '.' + x[1] : '';
				var rgx = /(\d+)(\d{3})/;
				while (rgx.test(x1)) {
					x1 = x1.replace(rgx, '$1' + '.' + '$2');
				}
				return x1 + x2;
			}

			$(".eliminar_h").click(function(){
				var datos = ($(this)[0].value).split("-");
				var codigo = datos[0];
				var sucursal = datos[1];
				var empresa = datos[2];
				$.ajax({
					url: "<?php echo base_url(); ?>carrito-borrar",
					data: {codigo: codigo,sucursal: sucursal,empresa: empresa},
					type: "post",
					success: function(data){
						var conversion = JSON.parse(data);
						if(conversion.estado=='TRUE'){
							swal(conversion.mensaje,"Eliminación Exitosa","success").then(function(){
								location.reload();
							});
						}else if(conversion.estado=='LOGIN'){
							swal(conversion.mensaje,"Atención!","warning").then(function(){
								window.location.href = "<?php echo base_url(); ?>";
							});
						}else{
							swal(conversion.mensaje,"Ha ocurrido un error","error").then(function(){
								location.reload();
							});
						}
					}
				})
			});

			$("#boton_arriendo").click(function(){
				$.ajax({
					url: "<?php echo base_url(); ?>arriendo",
					data: {arriendo: "YES"},
					type: "post",
					success: function(data){
						var conversion = JSON.parse(data);
						if(conversion.estado=='TRUE'){
							window.location.href = "<?php echo base_url(); ?>resumen/"+conversion.arriendo;                            
						}else{
							swal(conversion.mensaje,"Ups! Houston! Tenemos Problemas!","error");
						}
					}
				});
			});
		});
	</script>