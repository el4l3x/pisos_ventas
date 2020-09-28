<template>
	<div class="container">
		<!--ALERT DE EXITO-->
		<b-alert show variant="success" fade dismissible v-if="sincronizacion_exito == true">Sincronizacion exitosa</b-alert>
		<!--ALERT SI NO HAY SUFICIENTES PRODUCTOS-->
		<b-alert show variant="danger" fade dismissible v-if="error == true">Ha ocurrido un error</b-alert>

		<div class="row ">
			<div class="col-md-3">
				<div class="card shadow">
					<div class="card-body">
						<div v-if="piso_venta_selected != null" style="font-size: 1em;" class="mt-3">
							<span><span class="font-weight-bold">Nombre:</span> {{piso_venta_selected.nombre}}</span> <br>
							<span><span class="font-weight-bold">Lugar:</span> {{piso_venta_selected.ubicacion}}</span> <br>
							<span><span class="font-weight-bold">Dinero:</span> {{piso_venta_selected.dinero}}</span> <br>

						</div>
							<hr>
							<span class="font-weight-bold" >Ultima vez que sincronizo: </span> <span v-if="sincronizacion !== null">{{sincronizacion}}</span> <br>
							<span class="font-weight-bold" >Ultima vez que vacio la caja: </span><span  v-if="caja !== null">{{caja}}</span> <br>
							<hr>

							<button type="button" class="btn btn-danger btn-block mb-2" data-toggle="modal" data-target="#vaciar-caja">
							Vaciar caja
							</button>
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
			<div class="col-md-9" >

				<h1 class="text-center font-italic">Resumen del mes:</h1>
				<!--DATOS GLOBALES-->
				<div class="row text-white text-center">
					<div class="col-md-3" style="line-height: 5em; font-size: 1.5em;">
						<div class="bg-primary rounded shadow">Ventas: {{count.ventas}}</div>

					</div>
					<div class="col-md-3" style="line-height: 5em; font-size: 1.5em;">
						<div class="bg-danger rounded shadow">Compras: {{count.compras}}</div>
					</div>
					<div class="col-md-3" style="line-height: 5em; font-size: 1.5em;">
						<div class="bg-warning rounded shadow">Despachos: {{count.despachos}}</div>
					</div>
					<div class="col-md-3" style="line-height: 5em; font-size: 1.5em;">
						<div class="bg-success rounded shadow">Retiros: {{count.retiros}}</div>

					</div>
				</div>
				<!--TABLAS DE VENTAS Y COMPRAS-->
				<div class="mt-3">
					<div class="card shadow">
						<div class="card-body">
							<h4 class="text-center">Ventas y compras</h4>
							<tableVentas :id="id"/>
						</div>
					</div>
				</div>
				<!--TABLAS DE DESPACHOS Y RETIROS-->
				<div class="mt-3">
					<div class="card shadow">
						<div class="card-body">
							<h4 class="text-center">Despachos y retiros</h4>
							<tableDespachos :id="id"/>
						</div>
					</div>
				</div>

				<!--TABLA DE INVENTARIO-->
				<div class="mt-3">
					<div class="card shadow">
						<div class="card-body">
							<h4 class="text-center">Inventario</h4>
							<tableInventario :id="id"/>
						</div>
					</div>
				</div>

			</div>

			<!-- Modal -->
			<div class="modal fade" id="vaciar-caja" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog">
				    <div class="modal-content">
						<div class="modal-header">
				        	<h5 class="modal-title" id="exampleModalLabel">Vciar caja</h5>
				        	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				          	<span aria-hidden="true">&times;</span>
				        	</button>
				      	</div>
				     	<div class="modal-body">
				      	¿Esta seguro que desea vaciar su caja?.
				      	</div>
				      	<div class="modal-footer">
					        <button type="button" class="btn btn-primary" data-dismiss="modal">cerrar</button>
					        <button class="btn btn-danger" @click="vaciar_caja">Vaciar caja</button>
				      	</div>
				    </div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	import tableVentas from '../components/TableVentas';
	import tableDespachos from '../components/TableDespachos';
	import tableInventario from '../components/TableInventario';

	export default{
		components:{
			tableVentas,
			tableDespachos,
			tableInventario
		},
		data(){
			return {
				count:{
					ventas: 0,
					compras: 0,
					despachos: 0,
					retiros: 0
				},
				id: 0,
				piso_venta: {},
				piso_venta_selected: null,
				sincronizacion: null,
				caja: null,
				alert_success: false,
				alert_message: "",
				error: false,
				error_message: "",
				dismissSecs: 10,//MODAL
		        dismissCountDown: 0,
		        showDismissibleAlert: false,
		        sincron:{
		        	precios: false,
		        	despachos: false,
		        	ventas: false,
		        	monto: false,
		        	vaciar_caja: false,
		        	anulados: false,
		        	sincronizacion: false
		        },
		        loading: false
			}
		},
		methods:{
			precios(){//BORRAR

				axios.get('/api/get-inventories-id').then(response => {
					console.log(response);
					let inventario = response.data;

					if (inventario.length > 0) {

						axios.post('http://mipuchito.com/api/get-inventories', {inventario: inventario, piso_venta: this.id}).then(response => {

							console.log(response);
							let nuevoInventario = response.data;

							axios.post('/api/actualizar-inventory-id', {inventario: nuevoInventario}).then(response => {

								console.log(response);
								//ACTUALIZAMOS LOS PRECIOS

								axios.get('http://mipuchito.com/api/get-precios-inventory').then(response => {//WEB

									console.log(response)
									let inventory = response.data.inventory
									let inventario = response.data.inventario

									axios.post('/api/actualizar-precios-inventory', {productos: inventory, precios: inventario}).then(response => {

										console.log(response.data)
										//SINC
										this.sincron.precios = true;
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

							}).catch(e => {
								console.log(e.response)
							});

						}).catch(e => {
							console.log(e.response)
						});
					}else{
						console.log("no hay productos para anclar")
						//ACTUALIZAMOS LOS PRECIOS

						axios.get('http://mipuchito.com/api/get-precios-inventory').then(response => {//WEB

							console.log(response)
							let inventory = response.data.inventory
							let inventario = response.data.inventario

							axios.post('/api/actualizar-precios-inventory', {productos: inventory, precios: inventario}).then(response => {

								console.log(response.data)
								//SINC
								this.sincron.precios = true;
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
					}
				}).catch(e => {
					console.log(e.response)
				});

			},
			resumen(){

				axios.get('/api/resumen').then(response => {
					//console.log(response);
					this.count = response.data;

				}).catch(e => {
					console.log(e.response)
				});
			},
			get_id(){
				axios.get('/api/get-id').then(response => {
					this.id = response.data;
				}).catch(e => {
					console.log(e.response);
				});
			},
			get_piso_venta(){

				axios.get('/api/get-piso-venta').then(response =>{
					console.log(response)
					this.piso_venta_selected = response.data.piso_venta;
					this.sincronizacion = response.data.sincronizacion.created_at;
					this.caja = response.data.caja.created_at;


				}).catch(e => {
					console.log(e.response);
				});
			},
			vaciar_caja(){

				axios.post('/api/vaciar-caja').then(response => {

					console.log(response.data);
					this.piso_venta_selected = response.data.piso_venta;
				}).catch(e => {

					console.log(e.response);
				});

				$('#vaciar-caja').modal('hide');
			},
			sincronizar(){
				this.sincron = {
		        	precios: false,
		        	despachos: false,
		        	ventas: false,
		        	monto: false,
		        	vaciar_caja: false,
		        	sincronizacion: false
		        }

		        this.error = false;

		        this.loading = false;

				this.cambiar()


				//PRECIOS
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

									//SINC
									this.sincron.precios = true;
								}
							}).catch(e => {
								console.log(e.response)
								this.error = true;
								this.cambiar()
							});

						}else{
							console.log("no hay productos para registrar")
						}

						//ACTUALIZAMOS LOS PRECIOS
						//ANCLAR PRODUCTOS A UN INVENTORY_ID
						//OBTENEMOS DE LOS PISOS TODOS LOS QUE INVENTORY_ID == NUll
						axios.get('/api/get-inventories-id').then(response => {
							console.log(response);
							let inventario = response.data;

							if (inventario.length > 0) {
								//LOS BUSCAMOS EN LA WEB A VER SI SE LE ASIGNO UN INVENTORY_ID
								axios.post('http://mipuchito.com/api/get-inventories', {inventario: inventario, piso_venta: this.id}).then(response => {

									console.log(response);
									let nuevoInventario = response.data;
									//ACTUALIZAMOS EN LOCAL LOS INVENTORY_ID
									axios.post('/api/actualizar-inventory-id', {inventario: nuevoInventario}).then(response => {

										console.log(response);
										//ACTUALIZAMOS LOS PRECIOS

										axios.get('http://mipuchito.com/api/get-precios-inventory').then(response => {//WEB

											console.log(response)
											let inventory = response.data.inventory
											let inventario = response.data.inventario

											axios.post('/api/actualizar-precios-inventory', {productos: inventory, precios: inventario}).then(response => {

												console.log(response.data)
												//SINC
												this.sincron.precios = true;
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

									}).catch(e => {
										console.log(e.response)
									});

								}).catch(e => {
									console.log(e.response)
								});
							}else{
								console.log("no hay productos para anclar")
								//ACTUALIZAMOS LOS PRECIOS

								axios.get('http://mipuchito.com/api/get-precios-inventory').then(response => {//WEB

									console.log(response)
									let inventory = response.data.inventory
									let inventario = response.data.inventario

									axios.post('/api/actualizar-precios-inventory', {productos: inventory, precios: inventario}).then(response => {

										console.log(response.data)
										//SINC
										this.sincron.precios = true;
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
							}
						}).catch(e => {
							console.log(e.response)
						});
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

				//DESPACHOS

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
					axios.post('http://mipuchito.com/api/get-despachos-web', {piso_venta_id: this.id, ultimo_despacho: ultimoDespacho}).then(response => {//DEL LADO DE LA WEB

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

									//SINC
									this.sincron.despachos = true;
								}
							}).catch(e => {
								console.log(e.response)
								this.error = true;
								this.cambiar()
							});

						}else{


						//SINC
						this.sincron.despachos = true;
						}
						//PEDIR DE LA WEB LOS DESPACHOS QUE NO ESTAN CONFIRMADOS
						axios.get('http://mipuchito.com/api/get-despachos-sin-confirmacion/'+this.id).then(response => {//DEL LADO DE LA WEB

						despachosSinConfirmar = response.data;
						//console.log(response.data);
						if (despachosSinConfirmar.length > 0) {
							//PEDIR LOS DATOS EN LOCAL DE LOS QUE NO ESTAN CONFIRMADOS EN LA WEB
							axios.post('/api/get-despachos-confirmados', {despachos: despachosSinConfirmar}).then(response => {//

								despachosConfirmados = response.data
								//console.log(response.data);
								//GUARDAR LOS DATOS ANTERIORES EN LA WEB
								axios.post('http://mipuchito.com/api/actualizar-confirmados', {despachos: despachosConfirmados, piso_venta_id: this.id}).then(response => {//DEL LADO DE LA WEB PARA ACTUALIZAR LAS CONFIRMACIONES

								console.log(response);
								//SINC
								this.sincron.despachos = true;
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
						}

						}).catch(e => {
							console.log(e.response)
							this.error = true;
							this.cambiar()
						});

					}).catch(e => {
						console.log(e.response);
						this.error = true;
						this.cambiar()
					})

				}).catch(e => {
					console.log(e.response);
					this.error = true;
					this.cambiar()
				})

				//VENTAS

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
					console.log(response)
					let ultima_caja = response.data;
					if(ultima_caja == null){
						ultima_caja = 0;
					}
					//SOLICITAMOS LAS VACIADAS QUE TENGO EN LOCAL
					axios.get('/api/ultima-vaciada-caja-local/'+ultima_caja.id_extra).then(response => {

						console.log(response)
						let cajas = response.data;

						if (cajas.length > 0) {

							axios.post('http://mipuchito.com/api/registrar-cajas', {cajas: cajas}).then(response => {//WEB

								console.log(response)
								//SINC
								this.sincron.vaciar_caja = true;
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



				//SICRONIZACION
				axios.post('/api/sincronizacion', {id: this.id}).then(response => {
					console.log(response);

					axios.post('http://mipuchito.com/api/sincronizacion', {id: this.id}).then(response => {//WEB
						console.log(response);
						//SINC
						this.sincron.sincronizacion = true;
					}).catch(e => {
						console.log(e.response);
						this.error = true;
						this.cambiar()
					});

				}).catch(e => {
					console.log(e.response);
					this.error = true;
					this.cambiar()
				});


			},
			actualizar_monto(){

				axios.put('http://mipuchito.com/api/actualizar-dinero-piso-venta/'+this.id, {dinero: this.piso_venta_selected.dinero}).then(response => {//EN LA WEB

					console.log(response);
				}).catch(e => {

					console.log(e.response);
				});
			},
			actualizar_caja(){
				console.log("funcionando")
				//SOLICITAMOS EL ULTIMO QUE TENGA
				axios.get('http://mipuchito.com/api/ultima-vaciada-caja/'+this.id).then(response => {//WEB
					console.log(response)
					let ultima_caja = response.data;
					if(ultima_caja == null){
						ultima_caja = 0;
					}
					//SOLICITAMOS LAS VACIADAS QUE TENGO EN LOCAL
					axios.get('/api/ultima-vaciada-caja-local/'+ultima_caja.id_extra).then(response => {

						console.log(response)
						let cajas = response.data;

						if (cajas.length > 0) {

							axios.post('http://mipuchito.com/api/registrar-cajas', {cajas: cajas}).then(response => {//WEB

								console.log(response)
							}).catch(e => {
								console.log(e.response)
							});
						}
					}).catch(e => {

					console.log(e.response)
					});
				}).catch(e => {

					console.log(e.response)
				});
			},
			cambiar(){
				console.log("btn cambio")
				this.loading = !this.loading;
			},
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
			}
		},
		computed:{
			sincronizacion_exito(){

				if (this.sincron.precios == true && this.sincron.despachos == true && this.sincron.ventas == true && this.sincron.monto == true && this.sincron.vaciar_caja == true && this.sincron.sincronizacion == true) {
					this.cambiar()
					console.log("el valor de sincron anulados es "+ this.sincron.anulados)
					return  true;

				}

			}
		},
		created(){
			this.resumen();
			this.get_id();
			this.get_piso_venta();
		}
	}
</script>