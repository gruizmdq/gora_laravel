<template>
		<tr>
			<td><autocomplete-bis v-on:changezone="updatezone($event)" :street_selected="data.street" :id="data.id" :number="data.number" :route="route"></autocomplete-bis></td>
			<td><input v-model="data.number" class="form-control w-100 order-input" name="number" type="number" min="0" required step="1" @blur="onChange"></td>
			<td><input v-model="data.phone" class="form-control w-100" type="text" name="phone"></td>
			<td><zone-selector route="/delivery/orders/set_zone" :zones="datazones" :id="data.id" :zoneselected="data.zone"></zone-selector></td>
			<td><input class="form-control w-100" type="number" name="price" v-model="order.price"></td>
			<td class="text-center"><status-selector route="/delivery/orders/set_status" :options="datastatus" :id="data.id" :optionselected="data.status"></status-selector></td>
			<td class="text-center"><a class="maps-link" target="_blank" :href="'https://www.google.com/maps/dir/?api=1&destination='+order.lat+','+order.lng+'&travelmode=driving'"><span class="badge badge-warning order-edit-label py-1 px-2">Maps</span></a></td>
			<td class="text-center"><a class="" target="_blank" :href="'https://wa.me/'+data.phone"><i class="fab fa-whatsapp"></i></a></td>
			<td class="text-center"><a class="" target="_blank" :href="'tel:'+data.phone"><i class="fas fa-phone-volume"></i></a></td>
                            
		</tr>
</template>
<script>
	export default{
		data() {
			return {
				data: this.order,
				datazones: this.zones, 
				datastatus: this.status
			}
		},
		props: {
			order: {
				type: Object
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
			updatezone: function(order){
				this.data = order;
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