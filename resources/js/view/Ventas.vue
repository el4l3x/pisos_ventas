<template>
	<div>
		<div class="container">
			<!--ALERT SI NO HAY SUFICIENTES PRODUCTOS-->
			<b-alert
			:show="dismissCountDown"
			dismissible
			variant="danger"
			@dismissed="dismissCountDown=0"
			@dismiss-count-down="countDownChanged"
			v-if="error == true"
			fade
			>
			<p>{{error_message}}.</p>
			<b-progress
			variant="warning"
			:max="dismissSecs"
			:value="dismissCountDown"
			height="4px"
			></b-progress>
		</b-alert>
		<!---->
		<!--ALERT DE EXITO-->
		<b-alert show variant="success" fade dismissible v-if="alert_success == true">{{alert_message}}</b-alert>
		<!---->
		<b-alert show variant="success" fade dismissible v-if="sincro_exitosa == true">sincronozacion exitosa</b-alert>
		<b-alert show variant="warning" fade dismissible v-if="sin_ventas == true"> no se han detectado ventas</b-alert>
		<b-alert show variant="danger" fade dismissible v-if="error == true">ah ocurido un error</b-alert>

		<div class="row">
			<div class="col-md-3">
				<div class="card shadow">
					<div class="card-header text-center">
						<span>sincronizar inventario</span>
					</div>
					<div class="card-body">
						<div v-if="piso_venta_selected.length != 0" style="font-size: 1em;" class="mt-3">
							<span><span class="font-weight-bold">PV:</span> {{piso_venta_selected.nombre}}</span> <br>
							<!-- <span><span class="font-weight-bold">Lugar:</span> {{piso_venta_selected.ubicacion}}</span> <br> -->
							<span><span class="font-weight-bold">Dinero:</span> {{formattedCurrencyValue}}</span> <br>

						</div>
						<hr>
						<span class="font-weight-bold" > Last Update: </span> <span v-if="sincronizacion !== null">{{sincronizacion}}</span> <br>
						<!-- <span class="font-weight-bold" >Ultima vez que vacio la caja: </span><span  v-if="caja !== null">{{caja}}</span> <br> -->
						<hr>
						<button class="btn btn-primary btn-block" @click="sincronizar">

							<span v-if="loading == false">Sincronizar</span>
							<div class="spinner-border text-light text-center" role="status" v-if="loading == true">
								<span class="sr-only">Loading...</span>
							</div>
						</button>

						<!--<button class="btn btn-warning btn-block" @click="precios">Precios</button>-->
					</div>
				</div>
			</div>
			<div class="col-md-9">

				<div class="card shadow">
					<div class="card-body">
						<h1 class="text-center">Ventas y compras</h1>
						<div class="mb-3">
							<div class="row justify-content-between">
								<div class="col-12 col-md-2">
									<!--<button class="btn btn-primary" @click="refrescar">Sincronizar</button>-->
									<!--<button type="button" @click="sync_anular" class="btn btn-primary">sync anular</button>-->
								</div>

								<div class="col-12 col-md-4">
<!--
<button type="button" class="btn btn-danger" @click="showModalCompra">Compra</button>
<button type="button" class="btn btn-primary" @click="showModalNuevo">Venta</button>
-->
</div>

</div>
</div>

<div class="text-right my-3">
	<form action="" method="get" @submit.prevent="filtrar">
		<input type="date" v-model="fecha_inicial">
		<input type="date" v-model="fecha_final" >
		<button type="submit" class="btn btn-primary">Filtrar</button>
	</form>

</div>
<table class="table table-bordered table-sm table-hover table-striped">
	<thead>
		<tr>
			<th>Factura</th>
			<th>Fecha</th>
			<th>tipo</th>
			<th>Total</th>
			<th>Acciones</th>
		</tr>
	</thead>
	<tbody>
		<tr v-for="(venta, index) in ventas" :key="index" :class="{'bg-danger': venta.anulado == 0 || venta.anulado == 1, 'text-white': venta.anulado == 1 || venta.anulado == 0}">
			<td>FC-00{{venta.id}}</td>
			<td>{{venta.created_at}}</td>
			<td v-if="venta.type == 1">Venta</td>
			<td v-if="venta.type == 2">Compra</td>
			<td>{{venta.total}}</td>
			<td>
				<button type="button" class="btn btn-primary" @click="showModalDetalles(venta.id)">Ver</button>
				<button class="btn btn-danger" type="button" @click="showModalAnular(venta.id)" v-if="venta.anulado == null">Anular</button>
			</td>




			<!-- Modal VER DETALLES, FACTURA -->
			<b-modal :id="'modal-detalles-'+venta.id" size="lg" title="Detalles de la venta">

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
						<tr v-for="(producto, index) in venta.detalle" :key="index">
							<td>{{producto.name}}</td>
							<td>{{producto.pivot.cantidad}}</td>
							<td>{{producto.pivot.sub_total}}</td>
							<td>{{producto.pivot.iva}}</td>
							<td>{{producto.pivot.total}}</td>
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
						<span>{{venta.sub_total}}</span><br>
						<span>{{venta.iva}}</span><br>
						<br>
						<span>{{venta.total}}</span>
					</div>

				</div>
			</b-modal>

			<!--MODAL DE ANULAR FACTURA-->

			<div class="modal fade" :id="'modal-anular-'+venta.id" tabindex="-1" aria-labelledby="modalANular" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							¿Esta seguro que desea anular la venta?
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
							<button type="button" class="btn btn-danger" @click="anular(venta.id, index)">Anular</button>
						</div>
					</div>
				</div>
			</div>

		</tr>

		<tr v-if="ventas == []">
			<td class="text-center">No hay ventas registradas</td>
		</tr>
	</tbody>
</table>

<div class="overflow-auto">
	<b-pagination v-model="currentPage" @change="paginar($event)" :per-page="per_page"  :total-rows="total_paginas" size="sm"></b-pagination>
</div>


<!-- Modal NUEVA VENTA-->
<b-modal id="modal-nuevo" size="lg" title="Realizar ua nueva venta" hide-footer>

	<form method="post" @submit.prevent=""><!--Formulario-->

		<div>
			<div class="form-row">
				<div class="form-group col-md-3">
					<label for="producto">Producto:</label>
					<select class="form-control" v-model="articulo.id" @change="establecer_nombre(articulo.id)">
						<option value="0">Seleecion producto</option>
						<option v-for="(prod, index) in inventario" :key="index" :value="prod.inventario.id">{{prod.inventario.name}}</option>
					</select>
				</div>

				<div class="form-group col-md-3">
					<label for="cantidad">Cantidad disponible:</label>
					<input type="number" name="cantidad_disponible" id="cantidad_disponible" placeholder="" class="form-control" v-model="cantidad_disponible" disabled="">
				</div>

				<div class="form-group col-md-3">
					<label for="cantidad">Cantidad al menor:</label>
					<input type="number" name="cantidad" id="cantidad" placeholder="Cantidad" class="form-control" v-model="articulo.cantidad">
				</div>

				<div class="form-group col-md-3">
					<label class="text-center" for="">Acción:</label><br>
					<button class="btn btn-primary btn-block" type="button" @click="agregar_producto()" :disabled="disabled_venta">Agregar</button>
				</div>
			</div>
		</div>

		<table class="table table-bordered">
			<thead>
				<tr>
					<th>Producto</th>
					<th>cantidad</th>
					<th>Sub total</th>
					<th>Iva</th>
					<th>Total</th>
					<th>Acciones</th>
				</tr>
			</thead>
			<tbody>
				<tr v-for="(produc_enviar, index) in productos" :key="index">
					<td>{{produc_enviar.nombre}}</td>
					<td>{{produc_enviar.cantidad}}</td>
					<td>{{produc_enviar.sub_total}}</td>
					<td>{{produc_enviar.iva}}</td>
					<td>{{produc_enviar.total}}</td>
					<td>
						<button class="btn btn-danger" type="button" @click="eliminar(index)">Eliminar</button>
					</td>
				</tr>
			</tbody>
		</table>

		<div class="row">
			<div class="col-md-7">

			</div>

			<div class="col-md-2 text-right">
				<span class="font-weight-bold small">Sub total:</span><br>
				<span class="font-weight-bold small">iva:</span><br>
				<br>
				<span class="font-weight-bold small">Total:</span>

			</div>

			<div class="col-md-3">
				<span class="small">{{sub_total_total}}</span><br>
				<span class="small">{{iva_total}}</span><br>
				<br>
				<span class="small">{{total_total}}</span>
			</div>

		</div>

		<div class="modal-footer">
			<button type="submit" class="btn btn-primary" @click="vender" :disabled="productos.length <= 0">Vender</button>
		</div>
	</form>

</b-modal>


<!-- Modal NUEVA COMPRA-->
<b-modal id="modal-compra" size="lg" title="Realizar ua nueva compra" hide-footer>

	<div >
<!--
<div class="form-row">
<div class="form-group col-md-4">
<label for="producto">Producto:</label>
<select class="form-control" v-model="articulo_compra.id" @change="establecer_nombre(articulo_compra.id, 'compra')">
<option value="0">Seleecion producto</option>
<option v-for="(prod, index) in inventario_compra" :key="index" :value="prod.inventario.id">{{prod.inventario.name}}</option>
</select>
</div>

<div class="form-group col-md-4">
<label for="cantidad">Cantidad al menor:</label>
<input type="number" name="cantidad" id="cantidad" placeholder="Cantidad" class="form-control" v-model="articulo_compra.cantidad">
</div>

<div class="form-group col-md-4">
<label class="text-center" for="">Acción:</label><br>
<button class="btn btn-primary btn-block" type="button" @click="agregar_producto('compra')">Agregar</button>
</div>
</div>
-->
<!---->

<div >
	<h6>Nuevo producto:</h6>
	<div class="row">
		<div class="col-md-4">
			<div class="form-group">
				<label for="name-product">Nombre</label>
				<input type="text" class="form-control" id="name-product" placeholder="Harina pan" v-model="articulo_compra.nombre">
			</div>
		</div>

		<div class="col-md-4">
			<div class="form-group">
				<label for="cantidad-menor">Cantidad</label>
				<input type="number" class="form-control" id="cantidad" placeholder="8" v-model="articulo_compra.cantidad">
			</div>
		</div>

		<div class="col-md-4">
			<div class="form-group">
				<label for="unidad">Unidad</label>
				<input type="text" class="form-control" id="unidad" placeholder="Kg" v-model="articulo_compra.unidad">
			</div>
		</div>
<!--
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
-->
</div>

<div class="row">
	<div class="col-md-4">
		<div class="form-group">
			<label for="costo">Costo:</label>
			<input type="number" class="form-control" id="costo" placeholder="250000" v-model.number="articulo_compra.costo">
		</div>
	</div>

	<div class="col-md-4">
		<div class="form-group">
			<label for="margen-ganancia">margen de ganancia(%):</label>
			<input type="number" class="form-control" id="margen-ganancia" placeholder="20" v-model.number="articulo_compra.margen_ganancia">
		</div>
	</div>

	<div class="col-md-4">
		<div class="form-group">
			<label for="iva-porc">Iva(%):</label>
			<input type="number" class="form-control" id="iva-porc" placeholder="8" v-model.number="articulo_compra.iva_porc">
		</div>
	</div>

	<div class="col-md-3">
		<div class="form-group">
			<label for="subtotal-menor">Sub total:</label>
			<input type="number" class="form-control" id="subtotal-menor" placeholder="300000" v-model.number="articulo_compra.sub_total" disabled>
			<span class="" v-show="false">{{sub_total_comprar}}</span>
		</div>
	</div>

	<div class="col-md-3">
		<div class="form-group">
			<label for="iva">Iva:</label>
			<input type="number" class="form-control" id="iva" placeholder="50000" v-model.number="articulo_compra.iva" disabled>
			<span class="" v-show="false">{{iva_total_comprar}}</span>
		</div>
	</div>

	<div class="col-md-3">
		<div class="form-group">
			<label for="total">total:</label>
			<input type="number" class="form-control" id="total" placeholder="350000" v-model.number="articulo_compra.total" disabled>
			<span class="" v-show="false">{{total_comprar}}</span>
		</div>
	</div>

	<div class="col-md-3">
		<label >Acción:</label>
		<button type="button" class="btn btn-primary btn-block" @click="agregar_producto('compra')" :disabled="disabled_compra">Agregar</button>
	</div>
</div>
<!--
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
-->
</div>
</div>

<table class="table table-bordered">
	<thead>
		<tr>
			<th>Producto</th>
			<th>cantidad</th>
			<th>Sub total</th>
			<th>Iva</th>
			<th>Total</th>
			<th>Acciones</th>
		</tr>
	</thead>
	<tbody>
		<tr v-for="(produc_enviar, index) in productos_comprar" :key="index">
			<td>{{produc_enviar.nombre}}</td>
			<td>{{produc_enviar.cantidad}}</td>
			<td>{{produc_enviar.sub_total}}</td>
			<td>{{produc_enviar.iva}}</td>
			<td>{{produc_enviar.total}}</td>
			<td>
				<button class="btn btn-danger" type="button" @click="eliminar(index, 'comprar')">Eliminar</button>
			</td>
		</tr>
	</tbody>
</table>

<div class="row">
	<div class="col-md-7">

	</div>

	<div class="col-md-2 text-right">
		<span class="font-weight-bold small">Sub total:</span><br>
		<span class="font-weight-bold small">iva:</span><br>
		<br>
		<span class="font-weight-bold small">Total:</span>

	</div>

	<div class="col-md-3">
		<span class="small">{{sub_total_total_comprar}}</span><br>
		<span class="small">{{iva_total_total_comprar}}</span><br>
		<br>
		<span class="small">{{total_total_comprar}}</span>
	</div>

</div>

<div class="modal-footer">
	<button type="submit" class="btn btn-danger" @click="comprar" :disabled="productos_comprar <= 0">Compra</button>
</div>
</form>

</b-modal>

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
			ventas: [],
			piso_venta_selected:[],
			loading:false,
			sincronizacion:'',
			inventario: [],
			productos: [],//LISTA DE PRODUCTOS QUE VOY A AGREGAR
			articulo: {
				id: 0,
				nombre: "",
				cantidad: "",
				sub_total: "",
				iva: "",
				total: ""
			},
			sub_total: 0,
			iva: 0,
			total: 0,
			type: "",
			piso_venta: "",
			currentPage: 0,
			per_page: 0,
			total_paginas: 0,
			cantidad_disponible: "",
			error: false,
			error_message: "",
			dismissSecs: 10,//MODAL
			dismissCountDown: 0,
			showDismissibleAlert: false,
			alert_success: false,
			sincro_exitosa:false,
			sin_ventas:false,
			error:false,
			id:'',
			alert_message: "",
			inventario_compra: [],
			articulo_compra: {
				nombre: "",
				cantidad: "",
				unidad: "",
				costo: null,
				iva_porc: null,
				margen_ganancia: null,
				sub_total: null,
				iva: null,
				total: null,
				sub_total_unitario: null,
				iva_unitario: null,
				total_unitario: null,
			},
			productos_comprar: [],
			sub_de_total: 0,
			iva_de_compra: 0,
			total_de_compra: 0,
			fecha_inicial: "",
			fecha_final: ""
		}
	},
	methods:{
		sync_anulados(){
			console.log("desde el metodo anulado")
			let ventas = [];
		   //ANULADOS
		   //OBTENEMOS LOS PRODCUTOS QUE HALLAN SIDO ANULADOS
			axios.get('/api/get-ventas-anuladas').then(response => {
				console.log(response)
				let ventas = response.data;
				console.log(ventas.length)
				if (ventas.length > 0) {
					//ACTUALIZAMOS LOS ANULADOS EN LA WEB
					axios.post('http://mipuchito.com/api/actualizar-anulados', {ventas: ventas, piso_venta: this.id}).then(response => {//WEB

					console.log(response);
					//VOLVEMOS A ACTUALIZAR EN LOCAL
					axios.post('/api/actualizar-anulados-local').then(response => {
						//SINC
						this.sincron.anulados = true
						console.log(response)
					}).catch(e => {
						console.log(e.response);
					});

				    }).catch(e => {
						console.log(e.response);
					});
				}else{
					//SINC
					this.sincron.anulados = true;
				}
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
		sincronizar(){
			this.cambiar()
			this.sincro_exitosa = false
			this.sin_ventas = false
			this.error = false
				//VENTAS
		//VENTAS

				//OBTENEMOS MI ID DE PISO DE VENTA
				axios.get('/api/get-piso-venta-id').then(response => {
					let piso_venta_id = response.data;
					//console.log(piso_venta_id)
					//OBTENEMOS DE LA WEB LA ULTIMA VENTA QUE TIENE REGISTRADA CON NUESTRO PISO DE VENTA
					console.log('primera peticion')
					axios.get('http://mipuchito.com/api/ultima-venta/'+piso_venta_id).then(response => {//WEB
						console.log('segunda peticion')
						let ultima_venta = response.data.id_extra
						//console.log(ultima_venta)

						//OBTENEMOS TODAS LAS VENTAS QUE SEAN MAYOR AL ID_EXTRA QUE ACABO DE CONSEGUIR
						axios.get('/api/ventas-sin-registrar/'+piso_venta_id+'/'+ultima_venta).then(response => {
							console.log('tercera peticion')
							console.log(response.data)
							let ventas = response.data
							//VALIDACION SI TRAJO ALGUNA VENTA
							if (ventas.length > 0) {


							//EN ESE CASO REGISTRAMOS LAS VENTAS EN LA WEB
							axios.post('http://mipuchito.com/api/registrar-ventas', {ventas: ventas, piso_venta_id: piso_venta_id}).then(response => {
								console.log('cuarta peticion')
								console.log(response.data)
								if (response.data == true) {

									//SINC
									this.sincron.ventas = true;
									this.sync_anulados();
								}else{

									//this.showAlert();
								}
							}).catch(e => {
								console.log(e.response)
								this.error = true;
								this.cambiar()
							})
							}else{

								//SINC
								this.sincron.ventas = true;
								this.sync_anulados();
							}
						}).catch(e => {
							console.log(e.response)
							this.error = true;
							this.cambiar()
						})
					}).catch(e => {
						console.log(e.response)
						this.error = true;
						this.cambiar()
					})

				}).catch(e => {
					console.log(e.response)
					this.error = true;
					this.cambiar()
				});

				//ACTUALIZAR MONTO EN LA WEB
				axios.put('http://mipuchito.com/api/actualizar-dinero-piso-venta/'+this.id, {dinero: this.piso_venta_selected.dinero}).then(response => {//EN LA WEB
					console.log('5ta peticion')
					console.log(response);
					//SINC
					this.sincron.monto = true;
				}).catch(e => {

					console.log(e.response);
					this.error = true;
					this.cambiar()
				});

				//ACTUALIZAR VACIADAS DE CAJA

				//SOLICITAMOS EL ULTIMO QUE TENGA
				axios.get('http://mipuchito.com/api/ultima-vaciada-caja/'+this.id).then(response => {//WEB
					console.log('6ta peticion')
					console.log(response)
					let ultima_caja = response.data;
					if(ultima_caja == null){
						ultima_caja = 0;
					}
					//SOLICITAMOS LAS VACIADAS QUE TENGO EN LOCAL
					axios.get('/api/ultima-vaciada-caja-local/'+ultima_caja.id_extra).then(response => {
						console.log('7 peticion')
						console.log(response)
						let cajas = response.data;

						if (cajas.length > 0) {

							axios.post('http://mipuchito.com/api/registrar-cajas', {cajas: cajas}).then(response => {//WEB
								console.log('8 peticion')
								console.log(response)
								//SINC
								this.sincron.vaciar_caja = true;
								window.location="http://127.0.0.1:8000/ventas";
							}).catch(e => {
								console.log(e.response)
								this.error = true;
								this.cambiar()
							});
						}else{

						//SINC
						this.sincron.vaciar_caja = true;

						}
					}).catch(e => {

					console.log(e.response)
					this.error = true;
					this.cambiar()
					});
				}).catch(e => {

					console.log(e.response)
					this.error = true;
					this.cambiar()
				});

		},
		cambiar(){
			console.log("btn cambio")
			this.loading = !this.loading;
		},
		get_piso_venta(){

			axios.get('/api/get-piso-venta').then(response =>{
				console.log(response)
				this.piso_venta_selected = response.data.piso_venta;
				this.sincronizacion = response.data.sincronizacion.created_at;

			}).catch(e => {
				console.log(e.response);
			});
		},
		get_ventas(){

			axios.get('/api/get-ventas').then(response => {
				console.log(response.data.data);
				this.per_page = response.data.per_page;
				this.total_paginas = response.data.total;
				this.ventas = response.data.data

				console.log(this.despachos)
			}).catch(e => {
				console.log(e.response)
			});

		},
		get_datos(){
		//SOLICITO LOS PISOS DE VENTAS Y PRODUCTOS
		axios.get('/api/ventas-datos-create').then(response => {

			console.log(response);
			this.inventario = response.data
			this.inventario_compra = response.data
		}).catch(e => {

		});
		},
		showModalNuevo(){

			this.get_datos();
			this.$bvModal.show("modal-nuevo")
		},
		establecer_nombre(id, compra){//COLOCAR EL NOMBRE AL PRODUCTO QUE ESTOY AGREGANDO
			if (compra == "compra") {
				let resultado = this.inventario_compra.find(element => element.inventario.id == id)
				this.articulo_compra.nombre = resultado.inventario.name;
				this.articulo_compra.sub_total = resultado.inventario.precio.sub_total_menor
				this.articulo_compra.iva = resultado.inventario.precio.iva_menor
				this.articulo_compra.total = resultado.inventario.precio.total_menor
				console.log(this.articulo_compra);
			}else{
				let resultado = this.inventario.find(element => element.inventario.id == id)
				this.articulo.nombre = resultado.inventario.name;
				this.articulo.sub_total = resultado.inventario.precio.sub_total_menor
				this.articulo.iva = resultado.inventario.precio.iva_menor
				this.articulo.total = resultado.inventario.precio.total_menor
				this.cantidad_disponible = resultado.cantidad;
				console.log(this.articulo);
			}
		},
		agregar_producto(compra){

			if (compra == "compra") {

				this.articulo_compra.sub_total_unitario = this.articulo_compra.sub_total
				this.articulo_compra.iva_unitario = this.articulo_compra.iva
				this.articulo_compra.total_unitario = this.articulo_compra.total
				this.articulo_compra.sub_total *= this.articulo_compra.cantidad
				this.articulo_compra.iva *= this.articulo_compra.cantidad
				this.articulo_compra.total *= this.articulo_compra.cantidad
				this.productos_comprar.push(this.articulo_compra);
				this.articulo_compra = {nombre: "", cantidad: "", sub_total: "", iva: "", total: "", unidad: "", costo: null, iva_porc: null, margen_ganancia: null};
			}else{

				this.articulo.sub_total *= this.articulo.cantidad
				this.articulo.iva *= this.articulo.cantidad
				this.articulo.total *= this.articulo.cantidad
				this.productos.push(this.articulo);
				this.articulo = {id: 0, nombre: "", cantidad: "", sub_total: "", iva: "", total: ""};
			}
		},
		eliminar(index, comprar){
			if (comprar == "comprar") {
				this.productos_comprar.splice(index, 1);
			}else{
				this.productos.splice(index, 1);
			}
		},
		paginar(event){

			axios.get('/api/get-ventas?page='+event).then(response => {
				console.log(response.data)
				this.per_page = response.data.per_page;
				this.total_paginas = response.data.total;
				this.ventas = response.data.data

			}).catch(e => {
				console.log(e.response)
			});
		},
		showModalDetalles(id){
			this.$bvModal.show("modal-detalles-"+id)
		},
		vender(){
			this.error = false;
			axios.post('/api/ventas', {venta: {sub_total: this.sub_total, iva: this.iva, total: this.total, type: this.type},productos: this.productos}).then(response => {
				console.log(response.data)
				if (response.data.errors != null) {//COMPROBAR SI HAY ERRORES DE INSUFICIENCIA DE PRODUCTOS
					this.error_message = response.data.errors
					this.error = true;
					this.showAlert();
					this.articulo = {id: 0, nombre: "", cantidad: "", sub_total: "", iva: "", total: ""};
					this.cantidad_disponible = ""
					this.productos = [];
				}else{
					this.articulo = {id: 0, nombre: "", cantidad: "", sub_total: "", iva: "", total: ""};
					this.cantidad_disponible = ""
					this.ventas.splice(0,0, response.data);
					this.productos = [];
				}

			}).catch(e => {

				console.log(e.response)
			})

			this.$bvModal.hide("modal-nuevo")
		},
		countDownChanged(dismissCountDown) {//MODAL
			this.dismissCountDown = dismissCountDown
		},
		showAlert() {//MODAL
			this.dismissCountDown = this.dismissSecs
		},
		refrescar(){
			this.alert_success = false;
			this.error = false
			//OBTENEMOS MI ID DE PISO DE VENTA
			axios.get('/api/get-piso-venta-id').then(response => {
				let piso_venta_id = response.data;
				//console.log(piso_venta_id)
				//OBTENEMOS DE LA WEB LA ULTIMA VENTA QUE TIENE REGISTRADA CON NUESTRO PISO DE VENTA
				axios.get('http://mipuchito.com/api/ultima-venta/'+piso_venta_id).then(response => {//WEB

					let ultima_venta = response.data.id_extra
						//console.log(ultima_venta)
						//OBTENEMOS TODAS LAS VENTAS QUE SEAN MAYOR AL ID_EXTRA QUE ACABO DE CONSEGUIR
					axios.get('/api/ventas-sin-registrar/'+piso_venta_id+'/'+ultima_venta).then(response => {

						console.log(response.data)
						let ventas = response.data
						//VALIDACION SI TRAJO ALGUNA VENTA
						if (ventas.length > 0) {

							//EN ESE CASO REGISTRAMOS LAS VENTAS EN LA WEB
							axios.post('http://mipuchito.com/api/registrar-ventas', {ventas: ventas, piso_venta_id: piso_venta_id}).then(response => {

								console.log(response.data)
								if (response.data == true) {
									this.alert_message = "la sincronizacion fue exitosa";
									this.alert_success = true;
								}else{
									this.error_message = "Ha ocurrido un error."
									this.error = true;
									this.showAlert();
								}
							}).catch(e => {
								console.log(e.response)
							})
						}else{
							this.alert_message = "Usted esta al dia con la sincronizacion";
							this.alert_success = true;
						}
					}).catch(e => {
						console.log(e.response)
					})
				}).catch(e => {
					console.log(e.response)
				})
			}).catch(e => {
				console.log(e.response)
			});
			//SICRONIZACION
				axios.post('/api/sincronizacion', {id: piso_venta_id}).then(response => {
					console.log(response);

				axios.post('/api/sincronizacion', {id: piso_venta_id}).then(response => {//WEB
					console.log(response);

				}).catch(e => {
					console.log(e.response);
				});

			}).catch(e => {
				console.log(e.response);
			});
		},
		showModalCompra(){
			this.get_datos();
			this.$bvModal.show("modal-compra")
		},
		comprar(){

			this.error = false;
			axios.post('/api/ventas-comprar', {venta: {sub_total: this.sub_total_de_compra, iva: this.iva_de_compra, total: this.total_de_compra},productos: this.productos_comprar}).then(response => {
				console.log(response.data)

				this.articulo_compra = {nombre: "", cantidad: "", sub_total: "", iva: "", total: "", unidad: "", costo: null, iva_porc: null, margen_ganancia: null};
				this.ventas.splice(0,0, response.data);
				this.productos_comprar = [];

			}).catch(e => {

				console.log(e.response)
			})

			this.$bvModal.hide("modal-compra")
		},
		filtrar(){

			axios.get('/api/get-ventas', {params:{fecha_i: this.fecha_inicial, fecha_f: this.fecha_final}}).then(response => {
				console.log(response.data.data);
				this.per_page = response.data.per_page;
				this.total_paginas = response.data.total;
				this.ventas = response.data.data

				console.log(this.despachos)
			}).catch(e => {
				console.log(e.response)
			});
		},
		showModalAnular(id){

			$('#modal-anular-'+id).modal('show');
		},
		anular(id, index){

			axios.put('/api/anular/'+id).then(response => {
				console.log(response);
				this.ventas.splice(index, 1, response.data);
				console.log("anulado")
			}).catch(e => {
				console.log(e.response);
			});

			$('#modal-anular-'+id).modal('hide');
		},
		sync_anular(){

			//OBTENEMOS MI ID DE PISO DE VENTA
			axios.get('/api/get-piso-venta-id').then(response => {

				let piso_venta_id = response.data;
				//OBTENEMOS LOS PRODCUTOS QUE HALLAN SIDO ANULADOS
				axios.get('/api/get-ventas-anuladas').then(response => {

					let ventas = response.data;
					if (ventas.length > 0) {
					//ACTUALIZAMOS LOS ANULADOS EN LA WEB
						axios.post('http://mipuchito.com/api/actualizar-anulados', {ventas: ventas, piso_venta: piso_venta_id}).then(response => {//WEB

							console.log(response);
							//VOLVEMOS A ACTUALIZAR EN LOCAL
							axios.post('/api/actualizar-anulados-local', {ventas: ventas, piso_venta: piso_venta_id}).then(response => {

								console.log(response);

							}).catch(e => {
								console.log(e.response);
							});

						}).catch(e => {
							console.log(e.response);
						});
					}

				}).catch(e => {
					console.log(e.response);
				})

			}).catch(e => {
				console.log(e.response);
			})
		}
	},
	computed:{
		formattedCurrencyValue(){
       	 		if(!this.piso_venta_selected.dinero){
       	 		 return "0.00"
       	 		}
           	 	return "Bs " + parseFloat(this.piso_venta_selected.dinero).toFixed(2)
        },
		sub_total_total(){
			let subtotal = 0;

			this.productos.forEach(producto => {

				subtotal += producto.sub_total

			})
			this.sub_total = subtotal;

			return subtotal;
		},
		iva_total(){
			let iva = 0;

			this.productos.forEach(producto => {

				iva += producto.iva;
			})

			this.iva = iva;

			return iva;
		},
		total_total(){
			let total = 0;

			this.productos.forEach(producto => {

				total += producto.total

			})
			this.total = total

			return total;
		},
		sub_total_comprar(){
			//SUB TOTAL
			let precioSinIva = ((this.articulo_compra.margen_ganancia * this.articulo_compra.costo) / 100) + this.articulo_compra.costo
			this.articulo_compra.sub_total = precioSinIva

			return this.articulo_compra.sub_total;
			},
		iva_total_comprar(){
			let precioSinIva = ((this.articulo_compra.margen_ganancia * this.articulo_compra.costo) / 100) + this.articulo_compra.costo
		//IVA
		let iva = (this.articulo_compra.iva_porc * precioSinIva) / 100
		this.articulo_compra.iva = iva
		return this.articulo_compra.iva;
		},
		total_comprar(){
			let precioSinIva = ((this.articulo_compra.margen_ganancia * this.articulo_compra.costo) / 100) + this.articulo_compra.costo
			let iva = (this.articulo_compra.iva_porc * precioSinIva) / 100
			//TOTAL
			let total = iva + precioSinIva
			this.articulo_compra.total = total
			return this.articulo_compra.total;
		},
		sub_total_total_comprar(){
			let subtotal = 0;

			this.productos_comprar.forEach(producto => {

				subtotal += producto.sub_total

			})
			this.sub_total_de_compra = subtotal;

			return subtotal;
		},
		iva_total_total_comprar(){
			let iva = 0;

			this.productos_comprar.forEach(producto => {

				iva += producto.iva;
			})

			this.iva_de_compra = iva;

			return iva;
		},
		total_total_comprar(){
			let total = 0;

			this.productos_comprar.forEach(producto => {

				total += producto.total

			})
			this.total_de_compra = total

			return total;
		},
		disabled_venta(){

			if (this.articulo.id != 0 && this.articulo.cantidad != ""){

				return false;
			}else{
				return true;
			}
		},
		disabled_compra(){

			if (this.articulo_compra.nombre != "" && this.articulo_compra.cantidad != "" && this.articulo_compra.unidad != "" && this.articulo_compra.costo != null && this.articulo_compra.iva_porc != null && this.articulo_compra.margen_ganancia != null){

				return false;
			}else{
				return true;
			}
		},
		},
		created(){
			this.get_id()
			this.get_ventas()
			this.get_piso_venta()
		}
}
</script>