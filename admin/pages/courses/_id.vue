<template lang="pug">
	section.container-fluid
		header.row.py-3
			div.col-12
				h2 Éditer le cours
		div.content.row.align-items-start.justify-content-start.px-3
			div.col-9.d-flex.flex-row.flex-wrap.align-items-start.justify-content-start.px-0
				div.col-12.card.px-3.pt-3
					b-tabs(
						pills
						content-class="mt-3"
					)
						b-tab(:title="$t('content')" active)
							div.websiteBlock.w-100.pb-3
								div.col-12.px-0
									b-form-input(
										v-model="data.title"
										placeholder="Entrez le titre du cours"
									)
									div.permalink.my-3(
										ref="permalink"
									)
										span(v-if="!isPermalinkModif") 
											| Permalien :
											| {{data.permalink}}
										b-button.ml-3(
											v-if="!isPermalinkModif"
											variant="primary"
											@click="changePermalink"
										) Modifier
										div.col-12.px-0.d-flex.flex-row.flex-wrap.align-items-center(
											v-if="isPermalinkModif"
										)
											span Permalien :
											b-form-input.col.ml-3(
												v-model="data.permalink"
											)
											b-button.ml-3(
												variant="primary"
												@click="changePermalink"
											) Modifier
								div.separator.mb-5
								div.col-12.px-0
									quill-editor(
										ref="myQuillEditor"
										v-model="data.description"
										:options="editorOption"
									)
						SEO(
							:data="data"
							:type="type"
							@changeSeoTitle="changeSeoTitle"
							@changeSeoMeta="changeSeoMeta"
							@changeSeoRequest="changeSeoRequest"
							:slug="data.permalink"
						)
						RS()
				div.col-12.card.px-0
					b-tabs(
						pills
						card
						vertical
					)
						b-tab(
							title="Abonnement"
							active
						)
							div.price
								div.d-flex.flex-row.flex-wrap
									div.col-6
										label.form-label(
											for="price"
										) Prix de l'abonnement
										b-input-group.mb-3(
											append="€"
										)
											b-form-input(
												id="price"
												type="number"
												v-model="data.price"
											)
									div.col-6
										label.form-label(
											for="delay"
										) Durée de l'abonnement (par mois)
										b-input-group.mb-3(
											append="mois"
										)
											b-form-input(
												id="delay"
												type="number"
												v-model="data.delay"
											)
						ListLesson(
							@setLesson="setLesson"
							:lessons="lessons"
						)
			div.col-3.stiky-top
				b-card.mb-3(title="Publier")
					label.form-label(for="status") Statut
					b-form-select.mb-3(
						id="status"
						v-model="data.status"
						:options="statusOptions"
					)
					div.col-12.py-3.px-0.d-flex.flex-row.flex-wrap
						span Publié le&nbsp;
						span {{getDate(data.created_at)}}
					b-button(
						variant="primary"
						@click="update"
					) {{$t('MAJ')}}
				b-card.mb-3(title="Attribut")
					label.form-label(
						for="order"
					) ordre du cours
					b-form-input.mb-3(
						id="order"
						type="number"
						v-model="data.order"
					)
				b-card.mb-3(title="Image à la une")
					div.preview
						
					b-button(
						variant="primary"
					) Ajouter une image
</template>

<script>
import SEO from '~/components/Blocks/seo.vue'
import RS from '~/components/Blocks/rs.vue';
import ListLesson from '~/components/List/listLesson.vue';

export default {
	components: {
		SEO,
		RS,
		ListLesson,
	},
	data() {
		return {
			data : {},
			type: 'courses',
			lessons: [],
			isPermalinkModif : false,
			editorOption: {
				readOnly: false,
				theme: 'snow',
				placeholder: '',
			},
			statusOptions : [
				{ value: "draft", text: 'Brouillon' },
				{ value: "publish", text: 'Publié' },
				{ value: "archived", text: 'Archivé' },
			],
		}
	},
	async fetch() {
		this.data = await fetch("http://127.0.0.1:8000/api/courses/" + this.$route.params.id).then(res => {
			return res.json()
		}).then(r => {
			return r.data[0]
		})
	},
	computed: {
		editor() {
			return this.$refs.myQuillEditor.quill
		},
	},
	methods : {
		getDate(date) {
			return this.$moment(new Date(date)).format("DD MMMM YYYY à h[h]mm")
		},
		// permalink
		changePermalink() {
			this.isPermalinkModif = !this.isPermalinkModif
		},
		// permalink
		// editor
		onEditorChange({ quill, html, text }) {
			this.content = html
		},
		// editor
		// seo
		changeSeoTitle(value) {
			this.data.title_seo = value
		},
		changeSeoMeta(value) {
			this.data.meta_seo = value
		},
		changeSeoRequest(value) {
			this.data.request_seo = value
		},
		// seo
		// data from LessonList
		setLesson(val) {
			this.lessons = val
		},
		// data from LessonList
		update() {
			const date = Date.now()
			this.data.updated_at = date
			console.log(this.data)
			this.$axios.$put("http://127.0.0.1:8000/api/courses/" + this.$route.params.id, this.data).then(res => {
				console.log(res)
				/* faire une redirection vers la page avec l'id pour pouvoir éditer convenablement les données par après. */
				this.$swal.fire({
					toast: true,
					icon: 'success',
					title: 'Cours édité',
					timer: 2000,
					timerProgressBar : true,
					position: 'top-end',
					showConfirmButton : false,
					didOpen: (toast) => {
						toast.addEventListener('mouseenter', this.$swal.stopTimer)
						toast.addEventListener('mouseleave', this.$swal.resumeTimer)
					}
				})
			}).catch(err => {
				console.log(err)
			})
		}
	},
}
</script>

<style lang="scss">
.ql-editor {
	height: 300px;
}

.tabs {
	width: 100%;
}

.seoBlock {
	textarea {
		resize: none;
	}
}
</style>