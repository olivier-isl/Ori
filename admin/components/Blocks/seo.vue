<template lang="pug">
	b-tab(title="SEO")
		div.seoBlock.w-100.d-flex.flex-row.flex-wrap.px-0.pb-3
			div.col-6.pl-0
				div.col-12.px-0.pb-3
					label.form-label(
						for="request_seo"
					) Requête cible
					b-form-tags(
						id="request_seo"
						input-id="tags-limit"
						v-model="requestSeo"
						:limit="5"
						ref="request_seo"
						:placeholder="request_placeholder"
						limitTagsText="La limite de requête à été atteinte"
						addButtonText="ajouter"
						remove-on-delete
					)
				div.col-12.px-0
					label.form-label(
						for="titreSeo"
					) Titre SEO
						span.pl-3.small longueur titre:
							b-badge.ml-1(variant="primary") {{sizeTitleSeo}}
						span.pl-3.small nombre de mot :
							b-badge.ml-1(variant="primary") {{countWordTitleSeo}}
					b-form-input(
						id="titreSeo"
						v-model="titleSeo"
					)
					b-progress.mt-3(
						:variant="sizeTitleSeo > 0 && sizeTitleSeo <= maxsizeseo && countWordTitleSeo <= 12? 'success': 'danger'"
						:max="maxsizeseo"
						animated
					)
						b-progress-bar(
							:value="sizeTitleSeo"
						)
				div.separator.mb3
				div.col-12.px-0
					label.form-label(
						for="contentSeo"
					) Méta description
					b-form-textarea(
						id="contentSeo"
						v-model="metaSeo"
						placeholder="Modifiez votre méta description en l'éditant ici"
						rows="5"
						no-auto-shrink
					)
					b-progress.mt-3(
						:variant="sizeMetaSeo <= 0 ? 'danger': sizeMetaSeo > 0 && sizeMetaSeo < 106? 'warning' : sizeMetaSeo >= 106 && sizeMetaSeo <= maxsizecontentseo ? 'success': 'warning'"
						:max="maxsizecontentseo"
						animated
					)
						b-progress-bar(
							:value="sizeMetaSeo"
						)
			div.col-6.pr-0
				div.col-12.pb-3.px-0
					h2.h4 Aperçu google
				div.col-12.exempleSEOMobile.px-0
					div.top.w-100
						div.url.w-100
							img(src="https://admin.myrage.be/medias/2020/08/cropped-favicon.png", alt="alt")
							span admin.myrage.be > {{slug}}
						div.title.w-100 {{titleSeo}}
					div.content.w-100 {{metaSeo | limit(maxsizecontentseo)}}
				div.separator.mb-3
				div.col-12.pb-3.px-0
					h2.h4 Résultat d'analyse
					ul.list-unstyled
						li.mb-2
							span.circle(
								:class="colors[requestSeoAnalyse()[0]]"
							)
							u Longueur des requêtes
							| &nbsp;: 
							| {{requestSeoAnalyse()[1]}}
						li.mb-2(
							v-show="request_seo.length > 0"
						) 
							u Requêtes déjà utilisée
							| &nbsp;: 
							ul.list-unstyled.pt-2
								li(
									v-for="request in request_seo"
								) 
									span.badge.badge-secondary.px-2.pt-1.pb-2 {{request}}
									span.ml-2 lorem
						li.mb-2(
							v-show="request_seo.length > 0"
						) 
							u Requêtes dans le titre
							| &nbsp;: 
							ul.list-unstyled.pt-2
								li(
									v-for="request in request_seo"
								) 
									span.badge.badge-secondary.px-2.pt-1.pb-2 {{request}}
									span.circle(
										:class="colors[requestSeoTitle(request)[0]]"
									)
									span.ml-2 {{requestSeoTitle(request)[1]}}
						li.mb-2(
							v-show="request_seo.length > 0"
						) 
							u Requêtes dans la méta description
							| &nbsp;: 
							ul.list-unstyled.pt-2
								li(
									v-for="request in request_seo"
								) 
									span.badge.badge-secondary.px-2.pt-1.pb-2 {{request}}
									span.circle(
										:class="colors[requestSeoMeta(request)[0]]"
									)
									span.ml-2 {{requestSeoMeta(request)[1]}}
						li.mb-2(
							v-show="request_seo.length > 0"
						) 
							u Requête dans le slug
							| &nbsp;: 
							ul.list-unstyled.pt-2
								li(
									v-for="request in request_seo"
								) 
									span.badge.badge-secondary.px-2.pt-1.pb-2 {{request}}
									span.circle(
										:class="colors[requestSeoSlug(request)[0]]"
									)
									span.ml-2 {{requestSeoSlug(request)[1]}}
						li.mb-2 
							u Longueur du contenu
							| &nbsp;: 
						li.mb-2 
							u Longueur du titre SEO
							| &nbsp;: 
							| {{sizeTitleSeo}}
						li.mb-2 
							u Longueur de la méta description
							| &nbsp;: 
							| {{sizeMetaSeo}}

</template>

<script>
export default {
	props: {
		data : {
			type : Object,
			default () {
				return {}
			}
		},
		type : {
			type : String,
			default : ''
		},
		slug : {
			type: String,
			default : ''
		}
	},
	data() {
		return {
			maxsizeseo : 65,
			maxsizecontentseo: 150,
			title_seo : '',
			meta_seo : '',
			request_seo : [],
			request_seo_limit : 1,
			colors : {
				green : 'green',
				red : 'red',
				orange : 'orange'
			},
			analyze: {
				request : {
					green : 'Tout est Ok !',
					orange : 'Attention, vous pouvez en mettre autant que vous le souhaitez mais la recommandation est de 4',
					red : 'Aucune requête cible n’a été définie pour cette page',
				},
				requestTitle : {
					green : 'Le mots de votre requête apparaît au début de votre titre SEO.',
					orange : '',
					red : 'Le mot de votre requête n\'est pas dans votre titre SEO',
				},
				requestMeta : {
					green : 'Le mot de votre requête apparaît dans de votre méta description SEO.',
					orange : '',
					red : '',
				},
				requestSlug : {
					green : 'Le mot de votre requête apparaît dans votre permalien.',
					orange : '',
					red : '',
				}
			},
		}
	},
	async fetch() {
		if(this.$route.params.id) {
			await fetch("http://127.0.0.1:8000/api/"+this.type+"/" + this.$route.params.id).then(res => {
				return res.json()
			}).then(r => {
				return r.data[0]
			}).then(data => {
				this.title_seo = data.title_seo
				this.meta_seo =	data.meta_seo
				this.request_seo = data.request_seo != null? JSON.parse(data.request_seo) : [] 
			})
		}
	},
	computed: {
		request_placeholder() {
			if(this.request_seo.length < this.request_seo_limit) {
				return 'Ajouter une requête cible'
			}
			return ''
		},
		titleSeo: {
			get() {
				return this.title_seo
			},
			set(value) {
				this.title_seo = value
				this.$emit('changeSeoTitle', value)
			}
		},
		countWordTitleSeo() {
			if(this.title_seo) {
				if(this.title_seo.length > 0) {
					return this.title_seo.match(/(\w+)/g).length
				}
				return 0
			}
			return 0
		},
		sizeTitleSeo() {
			if(this.title_seo) {
				if(this.title_seo.length > 0) {
					return this.title_seo.length
				}
				return 0
			}
			return 0
		},
		metaSeo: {
			get() {
				return this.meta_seo
			},
			set(value) {
				this.meta_seo = value
				this.$emit('changeSeoMeta', value)
			}
		},
		sizeMetaSeo() {
			if(this.meta_seo) {
				if(this.meta_seo.length > 0) {
					return this.meta_seo.length
				}
				return 0
			}
			return 0
		},
		requestSeo: {
			get() {
				return this.request_seo
			},
			set(value) {
				this.request_seo = value
				this.$emit('changeSeoRequest', value)
			}
		},
	},
	methods: {
		requestSeoAnalyse() {
			if(this.request_seo.length <= 0) {
				return ['red', this.analyze.request.red]
			}
			if(this.request_seo.length > 4) {
				return ['orange', this.analyze.request.orange]
			}
			return ['green', this.analyze.request.green]
		},
		// same. find a way to reduce this
		requestSeoTitle(request) {
			if(this.title_seo.includes(request)) {
				return ['green', this.analyze.requestTitle.green]
			}
			return ['red', this.analyze.requestTitle.red]
		},
		requestSeoMeta(request) {
			if(this.meta_seo.includes(request)) {
				return ['green', this.analyze.requestMeta.green]
			}
			return ['red', this.analyze.requestMeta.red]
		},
		requestSeoSlug(request) {
			if(this.slug.includes(request)) {
				return ['green', this.analyze.requestSlug.green]
			}
			return ['red', this.analyze.requestSlug.red]
		},
	},
}
</script>

<style lang="scss">
.exempleSEOMobile {
	border-bottom: 1px hidden rgb(255, 255, 255);
	border-radius: 8px;
	box-shadow: rgba(32,33,36,28%) 0 1px 6px;
	font-family: Arial, 'Roboto-Regular', 'HelveticaNeue', sans-serif;
	max-width: 400px;
	box-sizing: border-box;
	font-size: 14px;
	.url {
		display: inline-block;
		cursor: pointer;
		position: relative;
		white-space: nowrap;
		font-size: 14px;
		vertical-align: top;
		overflow: hidden;
		text-overflow: ellipsis;
		max-width: 100%;
		margin-bottom: 12px;
		padding-top: 1px;
		line-height: 20px;
		img {
			width: 16px;
			height: 16px;
			margin-right: 12px;
			vertical-align: middle;
		}
	}
	.title {
		color: rgb(21, 88, 214);
		text-decoration: none;
		font-size: 20px;
		line-height: 26px;
		font-weight: normal;
		margin: 0;
		display: inline-block;
		overflow: hidden;
		max-width: 600px;
		vertical-align: top;
		text-overflow: ellipsis;
		
	}
	.top{
		padding: 12px 16px;
	}
	.content {
		color: rgb(60, 64, 67);
		font-size: 14px;
		line-height: 20px;
		cursor: pointer;
		position: relative;
		max-width: 600px;
		padding: 12px 16px;
	}
}
</style>