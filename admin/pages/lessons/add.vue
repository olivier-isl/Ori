<template lang="pug">
	section.container-fluid
		header.row.py-3
			div.col-12
				h2 Ajouter une leçon
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
								div.col-12.px-0.py-3
									div.pb-2
										span Vidéo du cours
									b-form-file(
										accept=".mp4, .m4v, .avi, .webm"
										v-model="video"
										:state="Boolean(video)"
										placeholder="Choisissez la vidéo à poster"
										drop-placeholder="Glissez la vidéo ici"
									)
									div.col-12.px-0.pt-3(
										v-if="renderVideo"
									)
										video.w-100(
											controls
											preload="auto"
											:src="renderVideo"
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

export default {
	components: {
		SEO,
		RS,
	},
	data() {
		return {
			type: 'lessons',
			data : {
				status : 'draft',
				order : 0,
				price : 0,
				delay : 0
			},
			renderVideo : '',
			courses: [],
			isPermalinkModif : false,
			editorOption: {
				// debug: 'info',
				readOnly: false,
				theme: 'snow',
				placeholder: '',
				modules : {
					toolbar: [
						[{ 'header': [1, 2, 3, 4, 5, 6, false] }],
						[{ 'font': ['Montserrat'] }],
						[{ 'align': [] }],
						['bold', 'italic','underline', 'strike'],
						['blockquote', 'code-block'],
						[{ 'list': 'ordered'}, { 'list': 'bullet' }],
						[{ 'script': 'sub'}, { 'script': 'super' }],
						[{ 'indent': '-1'}, { 'indent': '+1' }],
						['clean'] 
					]
				}
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
		video : {
			get() {
				console.log(this.data.video)
				return this.data.video
			},
			set(value) {
				console.log(value)
				this.renderVideo = URL.createObjectURL(value)
				this.data.video = value
			}
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
			console.log(this.courses)
			this.$axios.$post("http://127.0.0.1:8000/api/lessons/", {
				data : this.data,
				courses : this.courses
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
				this.$router.push('/lessons/'+res.data.id)
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