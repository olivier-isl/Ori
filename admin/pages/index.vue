<template lang="pug">
	section.container-fluid
		header.row
			div.col-12.py-3.d-flex.flex-row.flex-wrap.align-items-center
				h2.pb-0.mb-0 Dashboard
		div.row.p-3
			div.col-8
				div.header.pb-3
					h5.title Membres
				div.membres
					.col-4.pl-0(
						v-for="i in 3"
						:class="{'pr-3': i < 3, 'pr-0': i >= 3}"
					)
						div.card
							div.card-body.d-flex.flex-row.flex-wrap
								div.col-2.px-0
									img.avatar.w-100(
										src="http://127.0.0.1:3000/img/avatar.png"
									)
								div.col-10
									div
										span.h6.font-weight-bold Olivier Isselé
									div
										span lorem
			div.col-4
			div.col-8
				.card
					.card-header
						h5.card-title Nouveaux abonnements
					.card-body
					.card-footer
</template>

<style lang="scss">
.avatar {
	border-radius: 100%;
}

.membres {
	display: flex;
	flex-direction: row;
	flex-wrap: wrap;
}
</style>

<script>
export default {
	
}
</script>