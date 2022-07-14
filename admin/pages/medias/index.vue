<template lang="pug">
	section.container-fluid
		header.row
			div.col-12.py-3.d-flex.flex-row.flex-wrap.align-items-center
				h2.pb-0.mb-0 Médiathèque
				nuxt-link.btn.btn-outline-secondary.btn-sm.mx-3(:to="{name: 'courses-add'}") Ajouter
		div.content.row
			div.col-12
				div.card.lib.d-flex.flex-row.flex-wrap.align-items-start.justify-content-start
					div.img.m-2(
						v-for="img in imgs"
						@click="createModal(img.index)"
					)
						img(:src="img.img")
		b-modal(
			hide-footer
			id="modalImg"
			ref="modalImg"
			size="xxl"
			centered
		)
</template>

<script>
export default {
	data() {
		return {
			imgs : [
				{
					img : '/img/39515466_945741395633748_7336269226396614656_o.jpg', 
					index : 0
				},
				{
					img : '/img/avatar.png', 
					index : 1
				}
			],
			isModalOpen : false,
			currentImg : null,
		}
	},
	mounted() {
		document.addEventListener("keyup", this.switchData);
	},
	methods: {
		templateModal(data) {
			return `
			<div class="container-fluid">
				<div class="row p-0">
					<div class="col-8 preview">
						<img src="${data.img}">
					</div>
					<div class="col-4 bg-light">
						
					</div>
				</div>
			</div>
			`
		},
		createModal(idx) {
			this.$bvModal.show("modalImg")
			this.isModalOpen = true
			this.currentImg = idx
			setTimeout(() => {
				this.$refs.modalImg.$refs.body.innerHTML = this.templateModal(this.imgs[this.currentImg])
			}, 100)
		},
		switchData() {
			if (event.keyCode === 37 && this.currentImg > 0) {
				this.currentImg--
			} else if (event.keyCode === 39 && this.currentImg < this.imgs.length-1) {
				this.currentImg++
			}
			console.log(this.currentImg)
			
			if(this.$refs.modalImg) {
				console.log(this.$refs.modalImg)
				setTimeout(() => {
						this.$refs.modalImg.$refs.body.innerHTML = this.templateModal(this.imgs[this.currentImg])
					}, 100)
			}
			
		}
	},
}
</script>

<style lang="scss">
	@media (min-width: 1600px) {
		.modal-xxl {
			max-width: calc(100% - 40px);
		}
		.preview {
			width: calc(100% - 60px);
			padding: 1rem;
			img {
				display: block;
				margin: 0 auto 16px;
				max-width: 100%;
				max-height: calc(100% - 42px);
			}
		}
	}
	.modal-body {
		padding: 0;
	}
</style>


<style lang="scss" scoped>
	.lib {
		.img {
			width: 162px;
			height: 162px;
			cursor: pointer;
			position: relative;
			&:before {
				content:'';
				display:block;
				position: absolute;
				top: 0;
				left: 0;
				width: 100%;
				height: 100%;
				z-index: 1;
				background-color: rgba(255,255,255,0);
				transition: background 0.1s 0s linear;
			}
			&:hover {
				&:before {
					background-color: rgba(255,255,255,0.5);
					transition: background 0.2s 0s linear;
				}
			}
			img {
				width:100%;
				height: 100%;
				object-fit: cover;
				object-position: center;
				border-radius: 8px;
			}
		}
	}
</style>