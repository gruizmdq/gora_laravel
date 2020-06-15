<template>
	<div>
		<select class="form-control" @change="onChange">
            <option value="0" selected>Elegir zona...</option>
            <option v-for="zone in zones" v-bind:key="zone.id" :value="zone.id" :selected="is_selected(zone.id)">{{ zone.name }}</option>
        </select>
	</div>
</template>
<script>
	export default{
        props: {
			id: {
				type: Number,
				default: -1
			},
			route: {
				type: String,
				default: ''
			}
		},
		methods: {
			onChange(selected) {
				axios.post(this.route, {id: this.id, id_zone:selected.target.value})
                .then((response)=>{
                    this.$noty.success(response.data)
                })
			},
			is_selected(id) {
				if (id == parseInt(this.zoneselected))
					return 'selected';
			}
		}
	}
</script>