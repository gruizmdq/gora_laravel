<template>
	<div>
		<div class="animate fadeIn card w-100 map-card">
			<iframe width="100%" height="450" :src="getSrc" frameborder="0"
			style="border:0" allowfullscreen></iframe>
			<div class="card-body pb-0" style="z-index: 10">
				<div class="button px-2">
					<a target="_blank" class="btn-floating boton-directions btn-lg float-right z-depth-1 blue text-white" :href="getDirection"><i class="fas fa-directions"></i></a>
				</div>
				<h5 class="font-weight-bold mb-3">Próximo envío</h5>
				<p class="mb-0">{{ order.street }} {{ order.number }}</p>
				<p class="font-weight-bold mb-0">{{ order.name }}
				</p>
				<hr>
				<div class="d-flex justify-content-center pt-2 mt-1 text-center text-uppercase living-coral-text">
					<div>
						<a target="_blank" class="btn px-3 z-depth-1 green text-white radius-100" :href="getWhatsapp"><i class="fab fa-whatsapp"></i></a>
						<p class="mb-0">Whatsapp</p>
					</div>
					<div class="ml-5 ">
						<a type="button" class="btn px-3 z-depth-1 radius-100" :href="getCall"><i class="fas fa-phone"></i></a>
						<p class="mb-0">Llamar</p>
					</div>
				</div>
				<hr>
				<table class="table table-borderless">
					<tbody>
						<tr>
							<th scope="row" class="px-0 pb-3 pt-2">
								<i class="fas fa-dollar-sign"></i>
							</th>
							<td class="pb-3 pt-2">{{ order.price }}</td>
						</tr>
						<tr class="mt-2">
							<th scope="row" class="px-0 pb-3 pt-2">
								<i class="fas fa-shopping-cart"></i>
							</th>
							<td class="pb-3 pt-2">{{ order.product }}</td>
						</tr>
						<tr class="mt-2">
							<th scope="row" class="px-0 pb-3 pt-2">
							<i class="far fa-comment"></i>
							</th>
							<td class="pb-3 pt-2"><span class="font-weight-bold mr-2"> Comentarios:</span> {{ order.description }}</td>
						</tr>
					</tbody>
				</table>
				<hr>
				<div class="text-center">
					<button type="button" class="btn btn-primary" @click="completeOrder">Completado</button>
				</div>
			</div>
		</div>		
	</div>
</template>
<script>
	export default{
		computed: {
			getSrc: function () {
				var prefix = "https://www.google.com/maps/embed/v1/place?key=AIzaSyD1rxLVzd_wUtX_DORZQazOAJoUnglxh2s&&zoom=16&center="
				var street = this.order.street.split(" ").filter(word => !word.includes('.')).join('+')
				return prefix+this.order.lat+','+this.order.lng+'&q='+street+'+'+this.order.number
			},
			getWhatsapp: function () {
				return 'https://wa.me/54'+this.order.phone
			},
			getCall: function () {
				return 'tel:'+this.order.phone
			},
			getDirection: function() {
				return "https://www.google.com/maps/dir/?api=1&destination="+this.order.lat+","+this.order.lng+"&travelmode=driving"
			}
		},
		props: {
			order: {
				type: Object
			},
			route: {
				type: String
			}
		},
		methods: {
			completeOrder(){
				if (this.order.number != null) {
					axios.post(this.route, {id: this.order.id })
                	.then((response)=>{
						this.$noty.success(response.data.msg)
						this.$emit('orderCompleted', response.data)
					})
				}
			}
		}
	}
</script>