<template>
	<div>
		<select class="form-control" v-bind:class="selectedName" @change="onChange">
            <option v-for="option in options" v-bind:key="option.id" :value="option.id" :selected="is_selected(option.id)">{{ option.name }}</option>
        </select>
	</div>
</template>
<script>
	export default{
		data () {
			return {
				selectedName: ""
			}
		},
        props: {
			id: {
				type: Number,
				default: -1
			},
			options: {
				type: Array,
				default: []
			},
			optionselected: {
				type: Number,
				default: 1
			},
			route: {
				type: String,
				default: ''
			}
		},
		methods: {
			onChange(selected) {
				axios.post(this.route, {id: this.id, id_status:selected.target.value})
                	.then((response)=>{
						this.$noty.success(response.data)						
				})
				this.selectedName = this.options[selected.target.value-1].color
			},
			is_selected(id) {
				if (id == parseInt(this.optionselected)){
					return 'selected';
				}
			}
		},
		mounted() {
			this.selectedName = this.options[this.optionselected-1].color
		}
	}
</script>