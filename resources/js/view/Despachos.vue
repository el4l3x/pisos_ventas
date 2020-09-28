<template>
	<div>	
		<div class="container">

			<!--ALERT DE EXITO-->
		    <b-alert show variant="success" fade dismissible v-if="alert_success == true">{{alert_message}}</b-alert>
		   <!---->
			<div class="card shadow">
				<div class="card-body">
					<h1 class="text-center">Despachos-pisos</h1>
					<div class="mb-3">
						<div class="row justify-content-between">
							<div class="col-12 col-md-2">			
								<!--<button class="btn btn-primary " @click="refrescar">Sincronizar</button>-->
							</div>
							<div class="col-12 col-md-2">
							</div>		
							
						</div>
					</div>
					
					<table class="table table-bordered table-sm table-hover table-striped">
						<thead>
							<tr>
								<th>Fecha</th>
								<th>tipo</th>
								<th>Confirmado</th>
								<th>Acciones</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="(despacho, index) in despachos" :key="index">
								<td>{{despacho.created_at}} {{despacho.id}}</td>
								<th>{{despacho.type == 1? "despacho" : "retiro"}}</th>		
								<td v-if="despacho.confirmado == null" class="small font-weight-bold">No se ah confirmado</td>
								<td v-else class="small font-weight-bold">{{despacho.confirmado == 1 ? "confirmado" : "negado"}}</td>
								<td>
									<button class="btn btn-primary" data-toggle="modal" data-target="#modalVer">Ver</button>
									<button class="btn btn-success" v-if="despacho.confirmado == null" @click="confirmar(despacho.id, index)">confirmar</button>
									<button class="btn btn-danger" v-if="despacho.confirmado == null" @click="negar(despacho.id, index)">Negar</button>
								</td>

								<!-- Modal PARA VER LOS DETALLES -->
								<div class="modal fade" id="modalVer" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
								  	<div class="modal-dialog">
								    	<div class="modal-content">
								      		<div class="modal-header">
								        		<h5 class="modal-title" id="exampleModalLabel">Detalles</h5>
								        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								          		<span aria-hidden="true">&times;</span>
								        		</button>
								      		</div>
								      		<div class="modal-body">
								        	
								      		<table class="table table-bordered">
											<thead>
												<tr>
													<th>Producto</th>
													<th>cantidad</th>
												</tr>
											</thead>
											<tbody>
												<tr v-for="(product, index) in despacho.productos" :key="index">
													<td>{{product.product_name}}</td>
													<td v-if="product.pivot.tipo == 1">al menor</td>
													<td v-if="product.pivot.tipo == 2">al mayor</td>
													<td>{{product.pivot.cantidad}}</td>
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

							<tr v-if="despachos == []">
								<td class="text-center">No hay despachos registrados</td>
							</tr>
						</tbody>
					</table>

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
		data(){
			return{
				despachos: [],
				currentPage: 0,
				per_page: 0,
				total_paginas: 0,
				id: 0,
				alert_success: false,
				alert_message : ""
			}
		},
		methods:{
			get_despachos(){

				axios.get('/api/get-despachos').then(response => {
					//console.log(response.data);
					this.per_page = response.data.per_page;
					this.total_paginas = response.data.total;
					this.despachos = response.data.data

					console.log(this.despachos)
				}).catch(e => {
					console.log(e.response)
				});
			},
			paginar(event){

				axios.get('/api/get-despachos?page='+event).then(response => {
					console.log(response.data)
					this.per_page = response.data.per_page;
					this.total_paginas = response.data.total;
					this.despachos = response.data.data
			
				}).catch(e => {
					console.log(e.response)
				});
			},
			confirmar(id, index){

				axios.post('/api/confirmar-despacho', {id: id}).then(response => {

					console.log(response)
					this.despachos.splice(index, 1, response.data);
				}).catch(e => {
					console.log(e.response);
				})
			},
			negar(id, index){

				axios.post('/api/negar-despacho', {id: id}).then(response => {

					console.log(response.data)
					this.despachos.splice(index, 1, response.data);
				}).catch(e => {
					console.log(e.response);
				})
			},
			get_id(){

				axios.get('/api/get-id').then(response => {
					
					this.id = response.data;

				}).catch(e => {
					console.log(e.response)
				});
			},
			refrescar(){
				this.alert_success = false;

				let ultimoDespacho = 0;
				let nuevosDespachos = [];
				let despachosSinConfirmar = [];
				let despachosConfirmados = [];
				//ULTIMO DESPACHO RECIBIDO
				axios.get('/api/ultimo-despacho').then(response => {

					//console.log(response)
					//SI SE TRAJO ALGUN DESPACHO, ESTO QUITA EL ERROR DE LA PRIMERA VEZ YA QUE NO ABRA NINGUN REGISTRO previo
					if (response.data.id_extra != null) {
						ultimoDespacho = response.data.id_extra;
					}
					
					
					//SOLICITAR DESPACHOS NUEVOS (para eso necesito el ultimo despacho recibido)
					axios.post('http://127.0.0.1:8000/api/get-despachos-web', {piso_venta_id: this.id, ultimo_despacho: ultimoDespacho}).then(response => {//DEL LADO DE LA WEB

						nuevosDespachos = response.data;
						console.log(nuevosDespachos)
						/*
						if (nuevosDespachos.id == null) {
						console.log(nuevosDespachos)
						}else{
							console.log("hay algo")
						}
						*/
						if (nuevosDespachos.length > 0) {

							//REGISTRAR LOS DESPACHOS RECIBIDOS
							axios.post('/api/registrar-despachos-piso-venta', {despachos: nuevosDespachos}).then(response => {//
					
								console.log(response);//SI REGISTRA DEBERIA DAR TRUE
								if (response.data == true) {
									this.alert_success = true;
									this.alert_message = "sincronizacion exitosa nuevos despachos registrados"
								}
							}).catch(e => {
								console.log(e.response)
							});

						}else{

						this.alert_success = true;
						this.alert_message = "No hay despachos para sincronizar"
						}
						//PEDIR DE LA WEB LOS DESPACHOS QUE NO ESTAN CONFIRMADOS
						axios.get('http://127.0.0.1:8000/api/get-despachos-sin-confirmacion/'+this.id).then(response => {//DEL LADO DE LA WEB
							
						despachosSinConfirmar = response.data;
						//console.log(response.data);
						if (despachosSinConfirmar.length > 0) {
							//PEDIR LOS DATOS EN LOCAL DE LOS QUE NO ESTAN CONFIRMADOS EN LA WEB
							axios.post('/api/get-despachos-confirmados', {despachos: despachosSinConfirmar}).then(response => {//

								despachosConfirmados = response.data
								//console.log(response.data);
								//GUARDAR LOS DATOS ANTERIORES EN LA WEB
								axios.post('http://127.0.0.1:8000/api/actualizar-confirmados', {despachos: despachosConfirmados, piso_venta_id: this.id}).then(response => {//DEL LADO DE LA WEB PARA ACTUALIZAR LAS CONFIRMACIONES
						
								console.log(response);
								this.alert_success = true;
								this.alert_message += " nuevos datos confirmados"
								}).catch(e => {
									console.log(e.response)
								});

							}).catch(e => {
								console.log(e.response)
							});
						}

						}).catch(e => {
							console.log(e.response)
						});
		
					}).catch(e => {
						console.log(e.response);
					})

				}).catch(e => {
					console.log(e.response);
				})

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
			sinconfirmacion(){
				//PEDIR DE LA WEB LOS DESPACHOS QUE NO ESTAN CONFIRMADOS
							axios.get('/api/get-despachos-sin-confirmacion/'+this.id).then(response => {
					
								console.log(response);

							}).catch(e => {
								console.log(e.response)
							});
			}
		},
		created(){

			this.get_despachos()
			this.get_id()
		}
	}
</script>