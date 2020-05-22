<template>
	<div>
		<div v-for="(line, index) in lines" :key="index" class="row">
			<div class="col-7">
				<input required type="text" :data-code="line.code" :name="'street-'+index" placeholder="Ingrese una calle" v-model="line.query" v-on:keyup="autoComplete(line)" class="validate form-control address">
				<div class="panel-footer" v-if="line.results.length && line.show ">
					<ul class="list-group">
						<li class="list-group-item" v-for="result in line.results" v-bind:key="result.code" v-on:click="updateValue(line, result)">
							{{ result.name }}
						</li>
					</ul>
				</div>
			</div>
			<div class="col-3">
				<input required type="number" :name="'number-'+index" class="w-100 form-control validate address-number" step="1" min="0">
			</div>
			<div class="col-2">
				<ul class="ul-actions">
					<li class="ul-actions-element">
						<svg class="bi bi-trash" v-on:click="removeLine(index)" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
							<path d="M5.5 5.5A.5.5 0 016 6v6a.5.5 0 01-1 0V6a.5.5 0 01.5-.5zm2.5 0a.5.5 0 01.5.5v6a.5.5 0 01-1 0V6a.5.5 0 01.5-.5zm3 .5a.5.5 0 00-1 0v6a.5.5 0 001 0V6z"/>
							<path fill-rule="evenodd" d="M14.5 3a1 1 0 01-1 1H13v9a2 2 0 01-2 2H5a2 2 0 01-2-2V4h-.5a1 1 0 01-1-1V2a1 1 0 011-1H6a1 1 0 011-1h2a1 1 0 011 1h3.5a1 1 0 011 1v1zM4.118 4L4 4.059V13a1 1 0 001 1h6a1 1 0 001-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" clip-rule="evenodd"/>
						</svg>
					</li>
					<li class="ul-actions-element">
						<svg v-if="index + 1 === lines.length" v-on:click="addLine" class="bi bi-plus-circle" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
							<path fill-rule="evenodd" d="M8 3.5a.5.5 0 01.5.5v4a.5.5 0 01-.5.5H4a.5.5 0 010-1h3.5V4a.5.5 0 01.5-.5z" clip-rule="evenodd"/>
							<path fill-rule="evenodd" d="M7.5 8a.5.5 0 01.5-.5h4a.5.5 0 010 1H8.5V12a.5.5 0 01-1 0V8z" clip-rule="evenodd"/>
							<path fill-rule="evenodd" d="M8 15A7 7 0 108 1a7 7 0 000 14zm0 1A8 8 0 108 0a8 8 0 000 16z" clip-rule="evenodd"/>
						</svg>
					</li>
				</ul>
			</div>
		</div>
	</div>
</template>
<script>
	export default{
		data(){
			return {
				lines: [],
      			blockRemoval: true,
				query: '',
				results: [],
				code: '',
				show: false
			}
		},
		watch: {
			lines () {
				this.blockRemoval = this.lines.length <= 1
			}
		},
		methods: {
			autoComplete(line) {
				line.results = [];
				if (line.query.length > 2){
					axios.get('/api/search',{params: {query: line.query}}).then(response => {
						line.results = response.data;
						line.show = true
					});
				}
			},
			updateValue(line, value) {
				line.query = value.name
				line.code = value.code
				line.show = false
			},
			addLine () {
				let checkEmptyLines = this.lines.filter(line => line.query === '')
				/*
				if (checkEmptyLines.length >= 1 && this.lines.length > 0) {
					return
				} */

				this.lines.push({
					query: '',
					code: '',
					results: [],
					show: false
				})
			},
			removeLine (lineId) {
				if (!this.blockRemoval) {
					this.lines.splice(lineId, 1)
				}
			},
		},
		mounted () {
			this.addLine()
		}
	}
</script>