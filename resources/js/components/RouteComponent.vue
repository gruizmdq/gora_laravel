<template>
		<div class="row">
			<div class="col-md-4">
				<route-actual v-if="data.length > 0" v-on:orderCompleted="orderCompleted($event)" route="/delivery/orders/complete_order" :order="data[0]"></route-actual>
			</div>
			<div class="col-md-8">
				<h5 class="bl-blue p-3 white z-depth-1">Siguientes envíos</h5>
				<table id="tabla" class="z-depth-1 white py-2 px-1 table table-sm table-striped  table-bordered">
					<thead>
						<tr>
							<th scope="col">Nombre</th>
							<th scope="col">Dirección</th>
							<th scope="col">Teléfono</th>
							<th scope="col">Precio</th>
							<th scope="col">Producto</th>
							<th scope="col">Comentarios</th>
						</tr>
					</thead>

					<tbody>
						<tr v-for="order in orders.slice(1)" :key="order.id">
							<td>{{ order.name }}</td>
							<td>{{ order.street }} {{ order.number }}</td>
							<td>{{ order.phone }}</td>
							<td>{{ order.price }}</td>
							<td>{{ order.product }}</td>
							<td>{{ order.description }}</td>
						</tr>
					</tbody>
				</table>
				<h5 class="mt-5 bl-blue p-3 white z-depth-1">Envíos completados</h5>
				<table id="tabla" class="z-depth-1 white py-2 px-1 table table-sm table-striped  table-bordered">
					<thead>
						<tr>
							<th scope="col">Nombre</th>
							<th scope="col">Dirección</th>
							<th scope="col">Teléfono</th>
							<th scope="col">Precio</th>
							<th scope="col">Producto</th>
							<th scope="col">Comentarios</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="order in orders_completed" :key="order.id">
							<td>{{ order.name }}</td>
							<td>{{ order.street }} {{ order.number }}</td>
							<td>{{ order.phone }}</td>
							<td>{{ order.price }}</td>
							<td>{{ order.product }}</td>
							<td>{{ order.description }}</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
</template>
<script>
	export default{
		data() {
			return {
				data: this.orders,
				orders_completed: []
				//datazones: this.zones, 
				//datastatus: this.status
			}
		},
		props: {
			orders: {
				type: Array
			},
			route: {
				type: String
			},
			zones: {
				type: Array
			},
			status: {
				type: Array
			}
		},
		methods: {
			orderCompleted: function(order){
				this.orders_completed.push(this.data.shift())
			}, 
			onChange(){
				if (this.data.number != null) {
					axios.post(this.route, {id: this.order.id, number: this.data.number })
                	.then((response)=>{
						console.log(this)
						this.$noty.success(response.data.msg)
						this.data =response.data.order						
					})
				}
			}
		}
	}
</script>