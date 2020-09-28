<template>
	<div>	
		<div class="container">
			<div class="card">
				<div class="card-body">
					<h1 class="text-center">Compras</h1>
					<div class="mb-3">
						<div class="row justify-content-between">
							<div class="col-12 col-md-2">			
								<button class="btn btn-primary ">Refrescar</button>
							</div>
							<div class="col-12 col-md-2">
								<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-nueva-compra">Nueva</button>
							</div>		
							
						</div>
					</div>
					
					<table class="table table-bordered">
						<thead>
							<tr>
								<th>ID</th>
								<th>Sub Total</th>
								<th>Iva</th>
								<th>Total</th>
								<th>cantidad de productos</th>
								<th>Descripcion</th>
								<th>Acciones</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>1</td>
								<td>323231</td>
								<td>12356</td>
								<td>2121</td>
								<td>5</td>
								<td>135166</td>
								<td>
									<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#detalles-compra">Ver</button>
									<button class="btn btn-danger">Eliminar</button>
								</td>


								<!-- Modal VER DETALLES, FACTURA -->
								<div class="modal fade" id="detalles-compra" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
								  	<div class="modal-dialog modal-lg">
								    	<div class="modal-content">
								      		<div class="modal-header">
								        		<h5 class="modal-title" id="exampleModalLabel">Detalles de venta</h5>
								        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								          		<span aria-hidden="true">&times;</span>
								        		</button>
								      		</div>
									      	<div class="modal-body">
									      		<table class="table table-bordered">
									      			<thead>
										      			<tr>
										      				<th>Producto</th>
										      				<th>Cantidad</th>
										      				<th>Subtotal</th>
										      				<th>Iva</th>
										      				<th>Total</th>
										      			</tr>
									      			</thead>

									      			<tbody>
									      				<tr>
									      					<td>Harina pan</td>
									      					<td>2 kg</td>
									      					<td>230000</td>
									      					<td>60000</td>
									      					<td>260000</td>
									      				</tr>
									      			</tbody>

									      		</table>

									      		<div class="row">
									      			<div class="col-md-7">
									      				
									      			</div>

									      			<div class="col-md-2 text-right">
									      				<span class="font-weight-bold">Sub total:</span><br>
									      				<span class="font-weight-bold">iva:</span><br>
									      				<br>
									      				<span class="font-weight-bold">Total:</span>

									      			</div>

									      			<div class="col-md-3">
									      				<span> 230000</span><br>
										      			<span> 60000</span><br>
										      			<br>
										      			<span> 290000</span>
									      			</div>
									      			
									      		</div>
									      	</div>

									      	<div class="modal-footer">
									        	<button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
									      	</div>
								    	</div>
								  	</div>
								</div>

							</tr>
						</tbody>
					</table>


					<!-- Modal NUEVA COMPRA-->
					<div class="modal fade" id="modal-nueva-compra" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
						<div class="modal-dialog modal-lg">
					    	<div class="modal-content">
					      		<div class="modal-header">
						        	<h5 class="modal-title" id="exampleModalLabel">Nueva venta</h5>
						        	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						         	<span aria-hidden="true">&times;</span>
						        	</button>
					      		</div>
					      		<form action="/" method="post"><!--Formulario-->
						      	<div class="modal-body">

						      		<div>
						      			<select name="metodo_product" id="metodo-product" class="form-control" v-model="metodo_product">
						      				<option value="">Seleccione una forma de agregar el producto</option>
						      				<option value="1">Producto ya existente</option>
						      				<option value="2">Registrar producto</option>
						      			</select>

						      		</div>

									<div class="form-row mt-3" v-if="metodo_product == 1">
										<div class="form-group col-md-3">
											<label for="producto">Producto:</label>
										    <select class="form-control">
											  <option>Seleecion producto</option>
											</select>
										</div>

										<div class="form-group col-md-3">
											<label>Tipo de despacho:</label><br>
											<select class="form-control">
											  <option>Seleecione el tipo</option>
											  <option>Al menor</option>
											  <option>Al mayor</option>
											</select>
										</div>
										<div class="form-group col-md-3">
											<label for="cantidad">Cantidad:</label>
											<input type="number" name="cantidad" id="cantidad" placeholder="Cantidad" class="form-control">
										</div>

										<div class="form-group col-md-3">
											<label class="text-center" for="">Acción:</label><br>
											<button class="btn btn-primary btn-block">Agregar</button>
										</div>
									</div>

									<div v-if="metodo_product == 2" class="mt-3">
										<p>Nuevo producto:</p>
										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
												    <label for="name-product">Nombre</label>
													<input type="email" class="form-control" id="name-product" placeholder="Harina pan">
												</div>
											</div>

											<div class="col-md-4">
												<div class="form-group">
												    <label for="cantidad-menor">Cantidad al menor</label>
													<input type="number" class="form-control" id="cantidad-menor" placeholder="8">
												</div>
											</div>

											<div class="col-md-4">
												<div class="form-group">
												    <label for="cantidad-mayor">Cantidad al mayor</label>
													<input type="number" class="form-control" id="cantidad-mayor" placeholder="3">
												</div>
											</div>

											<div class="col-md-3">
												<div class="form-group">
												    <label for="unidad-menor">Unidad al menor</label>
													<input type="number" class="form-control" id="unidad-menor" placeholder="Kg">
												</div>
											</div>

											<div class="col-md-3">
												<div class="form-group">
												    <label for="unidad-mayor">Unidad al mayor</label>
													<input type="number" class="form-control" id="unidad-mayor" placeholder="Bulto">
												</div>
											</div>

											<div class="col-md-6">
												<div class="form-group">
												    <label for="cantidad-menor-por-mayor">Cantidad de paquetes que trae al mayor</label>
													<input type="number" class="form-control" id="cantidad-menor-por-mayor" placeholder="20">

												</div>
											</div>
										</div>
										<p>Precio al menor</p>
										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<label for="subtotal-menor">Sub total:</label>
												    <input type="number" class="form-control" id="subtotal-menor" placeholder="300000">
												</div>
											</div>

											<div class="col-md-4">
												<div class="form-group">
													<label for="iva-menor">Iva(%):</label>
												    <input type="number" class="form-control" id="iva-menor" placeholder="12">
												</div>
											</div>

											<div class="col-md-4">
												<div class="form-group">
													<label for="total-menor">total:</label>
												    <input type="email" class="form-control" id="total-menor" placeholder="350000">
												</div>
											</div>
										</div>

										<p>Precio al mayor</p>
										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<label for="subtotal-mayor">Sub total:</label>
												    <input type="number" class="form-control" id="subtotal-ayorr" placeholder="1500000">
												</div>
											</div>

											<div class="col-md-4">
												<div class="form-group">
													<label for="iva-mayor">Iva(%):</label>
												    <input type="number" class="form-control" id="iva-mayor" placeholder="12">
												</div>
											</div>

											<div class="col-md-4">
												<div class="form-group">
													<label for="total-mayor">total:</label>
												    <input type="email" class="form-control" id="total-mayor" placeholder="1800000">
												</div>
											</div>

											<div class="col-md-12">
												<button type="submit" class="btn btn-primary">Registrar</button>
											</div>
										</div>
									</div>


									<table class="table table-bordered mt-3">
										<thead>
											<tr>
												<th>Producto</th>
												<th>Tipo</th>
												<th>cantidad</th>
												<th>Acciones</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>Harina pan</td>
												<td>Al menor</td>
												<td>10</td>
												<td>
													<button class="btn btn-danger">Eliminar</button>
												</td>
											</tr>
										</tbody>
									</table>
						      	</div>
						      	<div class="modal-footer">
						        	<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						        	<button type="submit" class="btn btn-primary" data-dismiss="modal">Vender</button>
						      	</div>
						      	</form>
					    	</div>
					  	</div>
					</div>




				</div>
			</div>
		</div>
	</div>
</template>

<script>
	export default{
		data(){
			return{
				metodo_product: ""
			}
		},
		methods:{
			
		}
	}
</script>