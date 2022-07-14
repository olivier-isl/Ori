<template lang="pug">
	section.container-fluid
		header.row
			div.col-12.py-3.d-flex.flex-row.flex-wrap.align-items-center
				h2.pb-0.mb-0 configuration générale
		div.content.row.align-items-start.justify-content-start
			div.col-9.d-flex.flex-column.flex-wrap
				div.col-12.card.py-3
					div.form-group.mb-3
						label.form-label Titre du site
						input.form-control.col-3(
							type="text"
							v-model="data.site_name"
						)
					div.form-group.mb-3
						label.form-label Slogan
						input.form-control.col-3(
							type="text"
							v-model="data.description"
						)
					div.form-group.mb-3
						label.form-label Adresse du site web
						input.form-control.col-3(
							type="text"
							v-model="data.site_url"
						)
					div.form-group.mb-3
						label.form-label Adresse email de votre administration
						input.form-control.col-3(
							type="text"
							v-model="data.admin_email"
						)
			div.col-3
				b-card.mb-3(title="Publier")
					b-button(
						variant="primary"
						@click="update"
					) Mettre à jour
</template>

<script>
export default {
	data() {
		return {
			data : {}
		}
	},
	mounted() {
		this.$axios.$get("http://127.0.0.1:8000/api/options").then(res => {
			Object.values(res.data).forEach(el => {
				this.data = Object.assign({}, this.data, {[el.name] : el.value})
			})
			console.log(this.data)
			return res.data
		})
	},
	methods: {
		update() {
			this.$axios.put("http://127.0.0.1:8000/api/options", this.data).then(res => {
				if(res.status === 200) {
					this.$swal.fire({
						toast: true,
						icon: 'success',
						title: 'Les configurations ont bien été modifiée',
						timer: 2000,
						timerProgressBar : true,
						position: 'top-end',
						showConfirmButton : false,
						didOpen: (toast) => {
							toast.addEventListener('mouseenter', this.$swal.stopTimer)
							toast.addEventListener('mouseleave', this.$swal.resumeTimer)
						}
					})
				}
			})
		}
	}
}
</script>