<template>
	<div>
		<v-select :value="selected.name" :options="results" label="name"  @input="onChange" @search="fetchOptions" />
	</div>
</template>
<script>
	export default{
		data(){
			return {
				results: [],
				selected: ''
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
			street_selected: {
				type: String,
				default: ""
			}
		},
		methods: {
			fetchOptions(search, loading) {
				this.results = [];
				if (search.length > 2){
					axios.get('/api/search',{params: {query: search}}).then(response => {
						this.results = response.data;
					});
				}
			},
			onChange(selected) {
				this.selected = selected
				if (this.selected != null) {
					axios.post(this.route, {id: this.id, street_name: this.selected.name, street_code: this.selected.code})
                	.then((response)=>{
						this.$emit('changezone', response.data.order)
						this.$noty.success(response.data.msg)						
					})
				}
			}
		},
		mounted() {
			this.selected = {name: this.street_selected}
		}
	}
</script>