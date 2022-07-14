<template lang="pug">
	div.col.statusButton.px-0(
		ref="statusButton"
	)
		button.btn.btn-transparent.all.active(
			@click="getAll()"
		) 
			| Tous
			span.badge.badge-primary.ml-2(
				v-show="countStatus"
			) {{countStatus.all}}
		button.btn.btn-transparent.publish(
			@click="getByStatus('publish')"
		) 
			| Publié
			span.badge.badge-primary.ml-2(
				v-show="countStatus"
			) {{countStatus.publish}}
		button.btn.btn-transparent.draft(
			@click="getByStatus('draft')"
		) 
			| Brouillon
			span.badge.badge-primary.ml-2(
				v-show="countStatus"
			) {{countStatus.draft}}
		button.btn.btn-transparent.archived(
			@click="getByStatus('archived')"
			v-show="countStatus.archived > 0"
		) 
			| Archivé
			span.badge.badge-primary.ml-2(
				v-show="countStatus"
			) {{countStatus.archived}}
</template>