<template lang="pug">
	b-tab(:title="$t('RS')")
		div.rsBlock.w-100.d-flex.flex-row.flex-wrap.px-0.pb-3
			div.col-12.d-flex.flex-row.flex-wrap.px-0
				div.col-6
					h2.h4 Facebook
					.form-group
						label.form-label(
							for="titleFacebook"
						) image Facebook
						br
						button.btn.btn-primary Ajouter une image
					.form-group
						label.form-label(
							for="titleFacebook"
						) Titre Facebook
						b-form-input(
							id="titleFacebook"
							v-model="titleFacebook"
						)
					.form-group
						label.form-label(
							for="titleFacebook"
						) Description Facebook
						b-form-textarea(
							id="contentFacebook"
							v-model="contentFacebook"
						)
				div.col-6
					h2.h4 Aperçu Facebook
					div.previewFacebook
						div.img
							img(
								:src="imageFacebook"
							)
						div.content
							div.slug Myrage.be
							div.title {{titleFacebook}}
							div.description {{contentFacebook}}

			div.separator.mb-3
			div.col-12.d-flex.flex-row.flex-wrap.px-0
				div.col-6
					h2.h4 Twitter
					.form-group
						label.form-label(
							for="titleFacebook"
						) image Twitter
						br
						button.btn.btn-primary Ajouter une image
					.form-group
						label.form-label(
							for="titleTwitter"
						) Titre Twitter
						b-form-input(
							id="titleTwitter"
							v-model="titleTwitter"
						)
					.form-group
						label.form-label(
							for="titleTwitter"
						) Description Twitter
						b-form-textarea(
							id="contentTwitter"
							v-model="contentTwitter"
						)
				div.col-6
					h2.h4 Aperçu Twitter
					div.previewTwitter
						div.img
							img(
								:src="imageTwitter"
							)
						div.content
							div.title {{titleTwitter}}
							div.description {{contentTwitter}}
							div.slug Myrage.be
</template>

<script>
export default {
	data() {
		return {
			titleFacebook : '',
			contentFacebook : '',
			imageFacebook : '',
			titleTwitter : '',
			contentTwitter : '',
			imageTwitter : '',
		}
	}
}
</script>

<style lang="scss">
.rsBlock {
	textarea {
		height: 130px;
		resize: none;
	}
}
.previewFacebook {
	width: 527px;
	border: 1px solid grey;
	.img {
		width: 100%;
		height: 273.64444444444px;
	}
	.content {
		padding: 10px 12px;
		background-color: #F2F3F5;
		font-family: Arial, Helvetica, sans-serif;
		.slug {
			color: #606770;
			flex-shrink: 0;
			font-size: 12px;
			line-height: 16px;
			overflow: hidden;
			padding: 0;
			text-overflow: ellipsis;
			text-transform: uppercase;
			white-space: nowrap;
			font-family: inherit;
		}
		.title {
			font-family: inherit;
			font-size: 16px;
			line-height: 20px;
			margin: 3px 0 0;
			padding-top: 2px;
			color: #1d2129;
			text-decoration: none;
			font-weight: 600;
		}
		.description {
			margin-top: 3px;
			color: #606770;
			font-size: 14px;
			line-height: 20px;
			word-break: break-word;
			font-family: inherit;
		}
	}
}

.previewTwitter {
	width: 438px;
	border: 1px solid grey;
	border-radius: 12px;
	overflow: hidden;
	.img {
		width: 100%;
		height: 219px;
		border-bottom-width: 1px;
	}
	.content {
		padding: 10px 12px;
		background-color: white;
		font-family: Arial, Helvetica, sans-serif;
		.slug {
			color: rgb(136, 153, 166);
			flex-shrink: 0;
			font-size: 14px;
			line-height: 18.2px;
			overflow: hidden;
			padding: 0;
			text-overflow: ellipsis;
			text-transform: uppercase;
			white-space: nowrap;
			font-family: inherit;
			margin-top:4.52662px;
		}
		.title {
			font-family: inherit;
			font-size: 14px;
			margin: 0 0 0.15em;
			color: black;
		}
		.description {
			margin-top: 4.52662px;
			color: black;
			font-size: 14px;
			line-height: 18.2px;
			word-break: break-word;
			font-family: inherit;
		}
	}
}
</style>