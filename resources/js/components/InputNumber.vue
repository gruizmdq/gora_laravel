<template>
	<div>
		<input class="form-control w-100" type="number" v-model="value" @blur="onChange">
	</div>
</template>
<script>
	export default{
		data() {
			return {
				value: 1
			}
		},
		props: {
			id: {
				type: Number,
				default: ""
			},
			route: {
				type: String,
				default: ""
			},
			number: {
				type: Number
			}
		},
		methods: {
			onChange() {
				if (this.value != null) {
					axios.post(this.route, {id: this.id, number: this.value})
                	.then((response)=>{
						this.$noty.success(response.data)						
					})
				}
			}
		},
		mounted() {
			this.value = this.number
		}
	}
</script>