<template>
	<div>

		<div class="container">
			<!--ALERT DE EXITO-->
		    <b-alert show variant="success" fade dismissible v-if="alert_success == true">{{alert_message}}</b-alert>
		   <!---->
			<div class="card shadow">
				<div class="card-body">
					<h1 class="text-center">Inventario</h1>
					<div class="mb-3 row justify-content-between">
						<div class="col-md-3">
							<!--<button class="btn btn-primary" type="button" @click="refrescar">Refrescar</button>-->
						</div>
						<div class="col-md-5">
							<div class="form-inline">
								<div class="form-group">
									<input type="text" v-model="search" class="form-control d-inline" placeholder="Buscar producto" @change="get_inventario">
									<button type="button" class="btn btn-primary" @click="get_inventario">Buscar</button>
								</div>

							</div>
						</div>

					</div>

					<table class="table table-bordered table-sm table-hover table-striped">
						<thead>
							<tr>
								<th rowspan="">Producto</th>
								<th>Cantidad</th>
								<th>Precio</th>
								<th>Acciones</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="(producto, index) in productos" :key="index">
								<td>{{producto.inventario.name}}</td>
								<td>{{producto.cantidad}}</td>
								<td>{{producto.inventario.precio.total_menor}}</td>
								<td>
									<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#verDetalles">Detalles</button>
								</td>

								<!-- Modal PARA VER LOS DETALLES -->
								<div class="modal fade" id="verDetalles" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
								  	<div class="modal-dialog modal-lg">
								    	<div class="modal-content">
								      		<div class="modal-header">
								        		<h5 class="modal-title" id="exampleModalLabel">Detalles del producto</h5>
								        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								          		<span aria-hidden="true">&times;</span>
								        		</button>
								      		</div>
								      		<div class="modal-body">

								      			<h5 class="text-center font-weight-bold">{{producto.inventario.name}}</h5>

								      			<table class="table table-bordered">

								      				<thead>
								      					<tr>
								      						<th>propiedades</th>
								      						<th>Valores</th>

								      					</tr>
								      				</thead>

								      				<tbody>
								      					<tr>
								      						<th>Cantidad</th>
								      						<td>{{producto.cantidad}}</td>

								      					</tr>

								      					<tr>
								      						<th>Unidad</th>
								      						<td>{{producto.inventario.unit_type_menor}}</td>

								      					</tr>

								      					<tr>
								      						<td>Subtotal</td>
								      						<td>{{producto.inventario.precio.sub_total_menor}}</td>

								      					</tr>

								      					<tr>
								      						<td>Iva</td>
								      						<td>{{producto.inventario.precio.iva_menor}}</td>

								      					</tr>

								      					<tr>
								      						<td>Total</td>
								      						<td>{{producto.inventario.precio.total_menor}}</td>

								      					</tr>


								      				</tbody>
								      			</table>

								      		</div>
								      		<div class="modal-footer">
								        		<button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
								      		</div>
								    	</div>
								  	</div>
								</div>
							</tr>

							<tr v-if="productos == []">
								<td class="text-center">No hay productos registrados</td>
							</tr>
						</tbody>
					</table>
					{{currentPage}}
					<div class="overflow-auto">
						<b-pagination v-model="currentPage" @change="paginar($event)" :per-page="per_page"  :total-rows="total_paginas" size="sm"></b-pagination>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>


	export default{
		components: {

		},
		data(){
			return{
				productos: [],
				page: "",
				currentPage: 0,
				per_page: 0,
				total_paginas: 0,
				alert_success: false,
				alert_message: "",
				id: 0,
				search: null
			}
		},
		methods:{
			get_inventario(){

				axios.get('/api/get-inventario', {params:{search: this.search}}).then(response => {
					//console.log(response.data);
					this.per_page = response.data.per_page;
					this.total_paginas = response.data.total;
					this.productos = response.data.data

					console.log(this.productos)
				}).catch(e => {
					console.log(e.response)
				});
			},
			paginar(event){

				axios.get('/api/get-inventario?page='+event).then(response => {
					console.log(response.data)
					this.per_page = response.data.per_page;
					this.total_paginas = response.data.total;
					this.productos = response.data.data

				}).catch(e => {
					console.log(e.response)
				});
			},
			refrescar(){
				this.alert_success = false;
				//ULTIMO PRODUCTO DE INVENTORY REGISTRADO
				axios.get('/api/ultimo-inventory').then(response => {
					//console.log(response.data)
					let ultimoInventory = response.data
					//TRAEMOS DE LA WEB TODOS LOS PRODUCTOS APARTIR DEL ULTIMO ID
					axios.get('http://mipuchito.com/api/get-inventory/'+ultimoInventory).then(response => {//WEB

						//console.log(response)
						let productos = response.data
						//REGISTRAMOS LOS NUEVOS PRODUCTOS
						if (productos.length > 0) {

							console.log("hay que registrar")
							axios.post('/api/registrar-inventory', {productos: productos}).then(response => {

								if (response.data == true) {
									console.log("productos registrados exitosamente")
									this.alert_message = "productos registrados exitosamente"
									this.alert_success = true
								}
							}).catch(e => {
								console.log(e.response)
							});

						}else{
							console.log("no hay productos para registrar")
						}

						//ACTUALIZAMOS LOS PRECIOS

						axios.get('http://mipuchito.com/api/get-precios-inventory').then(response => {//WEB

							console.log(response)
							let articulos = response.data

							axios.post('/api/actualizar-precios-inventory', {productos: articulos}).then(response => {

								console.log(response.data)
							}).catch(e => {
								console.log(e.response)
							});
						}).catch(e => {
							console.log(e.response)
						});
					}).catch(e => {
						console.log(e.response)
					});

				}).catch(e => {
					console.log(e.response)
				});

				//SICRONIZACION
				axios.post('/api/sincronizacion', {id: this.id}).then(response => {
					console.log(response);

					axios.post('/api/sincronizacion', {id: this.id}).then(response => {//WEB
						console.log(response);

					}).catch(e => {
						console.log(e.response);
					});

				}).catch(e => {
					console.log(e.response);
				});
			},
			get_id(){

				axios.get('/api/get-id').then(response => {

					this.id = response.data;

				}).catch(e => {
					console.log(e.response)
				});
			}
		},
		created(){
			//console.log(this.productos)
			this.get_inventario();
			this.get_id();

		},

	}
</script>