<template>
	<div>
		<input type="hidden" name="code" v-model="code">
		<input autocomplete="off" required type="text" :data-code="code" name="street" placeholder="Ingrese una calle" v-model="query" v-on:keyup="autoComplete()" class="validate form-control address">
		<div class="panel-footer" v-if="results.length && show ">
			<ul class="list-group">
				<li class="list-group-item" v-for="result in results" v-bind:key="result.code" v-on:click="updateValue(result)">
					{{ result.name }}
				</li>
			</ul>
		</div>
	</div>
</template>
<script>
	export default{
		data(){
			return {
				query: '',
				results: [],
				code: '',
				show: false
			}
		},
		methods: {
			autoComplete() {
				this.results = [];
				if (this.query.length > 2){
					axios.get('/api/search',{params: {query: this.query}}).then(response => {
						this.results = response.data;
						this.show = true
					});
				}
			},
			updateValue(value) {
				this.query = value.name
				this.code = value.code
				this.show = false
			}
		}
	}
</script>