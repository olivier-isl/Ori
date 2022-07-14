<template lang="pug">
	section.container-fluid
		header.row.py-3
			div.col-12
				h2 Ajouter un cours
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
										@blur="setPermalink()"
									)
									div.permalink.my-3(
										ref="permalink"
									)
										span(v-if="!isPermalinkModif") 
											| {{$t('Permalink')}} :
											| {{data.permalink}}
										b-button.ml-3(
											v-if="!isPermalinkModif"
											variant="primary"
											@click="changePermalink"
										) {{$t('Modify')}}
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
											) {{$t('Modify')}}
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
						)
			div.col-3.stiky-top
				b-card.mb-3(title="Publier")
					label.form-label(for="status") {{$t('Status')}}
					b-form-select.mb-3(
						id="status"
						v-model="data.status"
						:options="statusOptions"
					)
					b-button(
						variant="primary"
						@click="submit"
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
import SEO from '~/components/Blocks/seo.vue';
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
			type: 'courses',
			data : {
				status : 'draft',
				order : 0,
				price : 0,
				delay : 0
			},
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
	computed: {
		editor() {
			return this.$refs.myQuillEditor.quill
		},
	},
	methods : {
		// permalink
		changePermalink() {
			this.isPermalinkModif = !this.isPermalinkModif
		},
		setPermalink() {
			if(this.data.permalink) {
				if(this.data.permalink.length <= 0) {
					this.data.permalink = this.data.title
				}
			}else {
				this.data.permalink = this.data.title
			}
			this.$forceUpdate();
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
			console.log(this.lessons)
		},
		// data from LessonList
		
		submit() {
			const date = Date.now()

			this.data.created_at = date
			this.data.updated_at = date
			console.log(this.data)
			console.log(this.lessons)
			this.$axios.$post("http://127.0.0.1:8000/api/courses/", {
				data : this.data,
				lessons : this.lessons
			}).then(res => {
				console.log(res);
				this.$swal.fire({
					toast: true,
					icon: 'success',
					title: 'Cours ajouté',
					timer: 2000,
					timerProgressBar : true,
					position: 'top-end',
					showConfirmButton : false,
					didOpen: (toast) => {
						toast.addEventListener('mouseenter', this.$swal.stopTimer)
						toast.addEventListener('mouseleave', this.$swal.resumeTimer)
					}
				})
				this.$router.push('/courses/'+res.data.id)
			}).catch(err => {
				console.warn(err)
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