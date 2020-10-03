<template>
	<div class="container">
		<div class="card shadow">
			<div class="card-body">

				<h1 class="text-center">Nueva compra:</h1>

				<select name="metodo" id="metodo" class="form-control" v-model="metodo">
					<option value="">Seleecione el modo de establecer el producto</option>
					<option value="1">Producto ya existente</option>
					<option value="2">Registrar producto</option>
				</select>

				<div >
					<hr>
					<div class="form-row" v-if="metodo == 1">

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

					<!---->

					<div v-if="metodo == 2">

						<hr>
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
							<!--
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
							-->
							<div class="col-md-3">
								<!--
								<div class="form-group">
									<label for="subtotal-menor">Sub total:</label>
								    <input type="number" class="form-control" id="subtotal-menor" placeholder="300000" v-model.number="articulo_compra.sub_total" disabled>
								    <span class="" v-show="false">{{sub_total_comprar}}</span>
								</div>
								-->
							</div>

							<div class="col-md-3">
								<!--
								<div class="form-group">
									<label for="iva">Iva:</label>
								    <input type="number" class="form-control" id="iva" placeholder="50000" v-model.number="articulo_compra.iva" disabled>
								    <span class="" v-show="false">{{iva_total_comprar}}</span>
								</div>
							-->
							</div>

							<div class="col-md-3">
								<!--
								<div class="form-group">
									<label for="total">total:</label>
								    <input type="number" class="form-control" id="total" placeholder="350000" v-model.number="articulo_compra.total" disabled>
								    <span class="" v-show="false">{{total_comprar}}</span>
								</div>
							-->
							</div>

							<div class="col-md-3 mb-3">
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

				<table class="table table-bordered table-hover table-striped">
					<thead>
						<tr>
							<th>Producto</th>
							<th>cantidad</th>
							<!--
							<th>Sub total</th>
							<th>Iva</th>
							<th>Total</th>
							-->
							<th>Acciones</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="(produc_enviar, index) in productos_comprar" :key="index">
							<td>{{produc_enviar.nombre}}</td>
							<td>{{produc_enviar.cantidad}}</td>
							<!--
							<td>{{produc_enviar.sub_total}}</td>
							<td>{{produc_enviar.iva}}</td>
							<td>{{produc_enviar.total}}</td>
							!-->
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
			</div>
		</div>
	</div>
</template>

<script>

	export default{
		data(){
			return{
				articulo_compra: {
					id: 0,
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
		 		inventario: [],
		 		inventario_compra: [],
		 		metodo: ""
			}
		},
		methods:{
			agregar_producto(compra){

				if (compra == "compra") {

					this.articulo_compra.sub_total_unitario = this.articulo_compra.sub_total
					this.articulo_compra.iva_unitario = this.articulo_compra.iva
					this.articulo_compra.total_unitario = this.articulo_compra.total

					this.articulo_compra.sub_total *= this.articulo_compra.cantidad
					this.articulo_compra.iva *= this.articulo_compra.cantidad
					this.articulo_compra.total *= this.articulo_compra.cantidad

					this.productos_comprar.push(this.articulo_compra);

					//console.log(this.productos)
					this.articulo_compra = {nombre: "", cantidad: "", sub_total: "", iva: "", total: "", unidad: "", costo: null, iva_porc: null, margen_ganancia: null};
				}else{

					this.articulo.sub_total *= this.articulo.cantidad
					this.articulo.iva *= this.articulo.cantidad
					this.articulo.total *= this.articulo.cantidad

					this.productos.push(this.articulo);

					//console.log(this.productos)
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
			comprar(){

				this.error = false;
				axios.post('/api/ventas-comprar', {venta: {sub_total: this.sub_total_de_compra, iva: this.iva_de_compra, total: this.total_de_compra},productos: this.productos_comprar}).then(response => {
					console.log(response.data)
						window.location = "/ventas";
						//this.articulo_compra = {nombre: "", cantidad: "", sub_total: "", iva: "", total: "", unidad: "", costo: null, iva_porc: null, margen_ganancia: null};
						//this.ventas.splice(0,0, response.data);
						//this.productos_comprar = [];

				}).catch(e => {

					console.log(e.response)
				})

				this.$bvModal.hide("modal-compra")
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

			}

		},
		computed:{
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
			disabled_compra(){
				/*
				if (this.articulo_compra.nombre != "" && this.articulo_compra.cantidad != "" && this.articulo_compra.unidad != "" && this.articulo_compra.costo != null && this.articulo_compra.iva_porc != null && this.articulo_compra.margen_ganancia != null){

					return false;
				}else{
					return true;
				}
				*/
				if (this.articulo_compra.nombre != "" && this.articulo_compra.cantidad != "" && this.articulo_compra.unidad != ""){

					return false;
				}else{
					return true;
				}
			}
		},
		created(){
			this.get_datos();
		},
		watch:{
			metodo: function(val){
				if (val == 2) {
					this.articulo_compra.id = 0;
				}
			}
		}
	}
</script>