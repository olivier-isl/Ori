<template lang="pug">
	.menu.d-flex.flex-row.flex-wrap
		ul.list-unstyled
			li.d-flex.flex-row.flex-wrap
				nuxt-link(:to="localePath({name:'index'})")
					faLayers.icons
						faIcon.fa-lg(icon="home")
					span.col.px-0.text-left.m-3.text-truncate Dashboard
			li.d-flex.flex-row.flex-wrap
				nuxt-link.col(:to="localePath({name:'medias'})")
					faLayers.icons
						faIcon.fa-lg(icon="images")
					span.col.px-0.text-left.m-3.text-truncate Médias
				b-button.d-flex.flex-row.flex-wrap.align-items-center.justify-content-center(
					v-b-toggle.collapseMedia
					ref="collapseMedia"
					@click="getEvent($event, 'collapseMedia')"
				)
					i.mx-0.px-0.chevron.fas.fa-chevron-right
				b-collapse#collapseMedia.w-100
					ul.sub-menu
						li 
							nuxt-link(:to="localePath({name:'medias-add'})") Ajouter un médias
						li 
							nuxt-link(:to="localePath({name:'medias'})") Voir tout les médias
			li.d-flex.flex-row.flex-wrap(v-if="false")
				nuxt-link.col(:to="localePath({name:'pages-add'})")
					faLayers.icons
						faIcon.fa-lg(icon="copy")
					span.col.px-0.text-left.m-3.text-truncate Page
				b-button.d-flex.flex-row.flex-wrap.align-items-center.justify-content-center(
					v-b-toggle.collapsePage
					ref="collapsePage"
					@click="getEvent($event, 'collapsePage')"
				)
					i.mr-3.chevron.fas.fa-chevron-right
				b-collapse#collapsePage.w-100
					ul.sub-menu
						li 
							nuxt-link(:to="localePath({name:'pages'})") Ajouter une page
						li 
							nuxt-link(:to="localePath({name:'pages-add'})") Voir toutes les pages
			li.d-flex.flex-row.flex-wrap(v-if="false")
				nuxt-link.col(:to="localePath({name:'blog'})")
					faLayers.icons
						faIcon.fa-lg(icon="thumbtack")
					span.col.px-0.text-left.m-3.text-truncate Blog
				b-button.d-flex.flex-row.flex-wrap.align-items-center.justify-content-center(
					v-b-toggle.collapseBlog
					ref="collapseBlog"
					@click="getEvent($event, 'collapseBlog')"
				)
					i.mr-3.chevron.fas.fa-chevron-right
				b-collapse#collapseBlog.w-100
					ul.sub-menu
						li 
							nuxt-link(:to="localePath({name:'blog-add'})") Ajouter un article
						li 
							nuxt-link(:to="localePath({name:'blog'})") Voir toutes les articles
						li 
							nuxt-link(to="") Catégories
			li.d-flex.flex-row.flex-wrap
				nuxt-link.col(
					:to="localePath({name:'courses'})"
				)
					faLayers.icons
						faIcon.fa-lg(icon="book")
					span.col.px-0.text-left.m-3.text-truncate Cours
				b-button.d-flex.flex-row.flex-wrap.align-items-center.justify-content-center(
					v-b-toggle.collapseCourses
					ref="collapseCourses"
					@click="getEvent($event, 'collapseCourses')"
					:class="[$route['name'].includes('courses')?'active':null]"
					variant="menu"
				)
					i.mx-0.px-0.chevron.fas.fa-chevron-right
				b-collapse#collapseCourses.w-100
					ul.sub-menu
						li 
							nuxt-link(:to="localePath({name:'courses-add'})") Ajouter un cours
						li 
							nuxt-link(:to="localePath({name:'courses'})") Voir tout les cours
			li.d-flex.flex-row.flex-wrap
				nuxt-link.col(:to="localePath({name:'lessons'})") 
					faLayers.icons
						faIcon.fa-lg(icon="bookmark")
					span.col.px-0.text-left.m-3.text-truncate Leçons
				b-button.d-flex.flex-row.flex-wrap.align-items-center.justify-content-center(
					v-b-toggle.collapseLessons
					ref="collapseLessons"
					@click="getEvent($event, 'collapseLessons')"
				)
					i.mx-0.px-0.chevron.fas.fa-chevron-right
				b-collapse#collapseLessons.w-100
					ul.sub-menu
						li 
							nuxt-link(:to="localePath({name:'lessons-add'})") Ajouter un cours
						li 
							nuxt-link(:to="localePath({name:'lessons'})") Voir tout les cours
			li.d-flex.flex-row.flex-wrap
				nuxt-link.col(:to="localePath({name:'quizz'})")
					faLayers.icons
						faIcon.fa-lg(icon="question")
					span.col.px-0.text-left.m-3.text-truncate Quizz
				b-button.d-flex.flex-row.flex-wrap.align-items-center.justify-content-center(
					v-b-toggle.collapseQuizz
					ref="collapseQuizz"
					@click="getEvent($event, 'collapseQuizz')"
				)
					i.mx-0.px-0.chevron.fas.fa-chevron-right
				b-collapse#collapseQuizz.w-100
					ul.sub-menu
						li 
							nuxt-link(:to="localePath({name:'quizz-add'})") Ajouter un quizz
						li 
							nuxt-link(:to="localePath({name:'quizz'})") Voir tout les quizz
			li.d-flex.flex-row.flex-wrap
				nuxt-link(:to="localePath({name:'index'})")
					faLayers.icons
						faIcon.fa-lg(icon="users")
					span.col.px-0.text-left.m-3.text-truncate Membres
			li.d-flex.flex-row.flex-wrap
				nuxt-link.col(:to="localePath({name:'users'})")
					faLayers.icons
						faIcon.fa-lg(icon="user")
					span.col.px-0.text-left.m-3.text-truncate Utilisateurs
				b-button.d-flex.flex-row.flex-wrap.align-items-center.justify-content-center(
					v-b-toggle.collapseUsers
					ref="collapseUsers"
					@click="getEvent($event, 'collapseUsers')"
				)
					i.mx-0.px-0.chevron.fas.fa-chevron-right
				b-collapse#collapseUsers.w-100
					ul.sub-menu
						li 
							nuxt-link(to="") Ajouter un utilisateur
						li 
								nuxt-link.col(:to="localePath({name:'users'})") Voir tout les utilisateurs
			li.d-flex.flex-row.flex-wrap
				nuxt-link(:to="localePath({name:'devis'})")
					faLayers.icons
						faIcon.fa-lg(icon="file-invoice-dollar")
					span.col.px-0.text-left.m-3.text-truncate Devis
			li.d-flex.flex-row.flex-wrap
				nuxt-link(to="")
					faLayers.icons
						faIcon.fa-lg(icon="file-invoice-dollar")
					span.col.px-0.text-left.m-3.text-truncate Facturation
			li.d-flex.flex-row.flex-wrap(v-if="false")
				nuxt-link(to="")
					faLayers.icons
						faIcon.fa-lg(icon="search")
					span.col.px-0.text-left.m-3.text-truncate Référencement
			li.d-flex.flex-row.flex-wrap
				nuxt-link.col(:to="localePath({name: 'settings'})") 
					faLayers.icons
						faIcon.fa-lg(icon="cog")
					span.col.px-0.text-left.m-3.text-truncate Réglages
				b-button.d-flex.flex-row.flex-wrap.align-items-center.justify-content-center(
					v-b-toggle.collapseSettings
					ref="collapseSettings"
					@click="getEvent($event, 'collapseSettings')"
				)
					i.mx-0.px-0.chevron.fas.fa-chevron-right
				b-collapse#collapseSettings.w-100
					ul.sub-menu
						li 
							nuxt-link(:to="localePath({name: 'settings'})") Générale
						li 
							nuxt-link(to="") E-learning
						li 
							nuxt-link(to="") E-commerce
						li 
							nuxt-link(to="") Médias
</template>

<script>
export default {
	methods: {
		getEvent(e, ref) {
			if(e.target.closest('button').classList.contains('collapsed')) {
				e.target.closest('button').querySelector('.chevron').classList.remove('fa-chevron-right')
				e.target.closest('button').querySelector('.chevron').classList.add('fa-chevron-down')
			}
			if(e.target.closest('button').classList.contains('not-collapsed')) {
				e.target.closest('button').querySelector('.chevron').classList.remove('fa-chevron-down')
				e.target.closest('button').querySelector('.chevron').classList.add('fa-chevron-right')
			}
		},
	}
}
</script>

<style lang="scss" scoped>
	.text-truncate {
		overflow: hidden;
		text-overflow: ellipsis;
		white-space: nowrap;
	}
	.menu {
		height: 100vh;
		width: 260px;
		// background: rgb(0,193,165);
		// background: linear-gradient(29deg, rgba(0,193,165,1) 0%, rgba(0,114,173,1) 100%);
		background-color: white;
		font-size: 12px;
		font-weight: 500;
		letter-spacing: 1px;
		position: sticky;
		top: 0;
		z-index: 1;
		overflow-x: hidden;
		overflow-y: auto;
		a, button {
			// color: white;
			color: #625f6e;
			font-weight: 500;
			height: 42px;
			padding: 0 5px 0 10px;
		}
		.btn {
			border: 0;
			padding: 0 0 0 0;
			height: 42px;
			width: 42px;
			&:hover, &:active, &:visited, &:focus, &:link {
				outline: none !important;
				box-shadow: none !important;
				background-color: #f3f3f3;
				color: #625f6e;
			}
			&.active {
				background-color: #f3f3f3;
			}
			&>a {
				&:hover{
					text-decoration: none;
				}
			}
		}
		&>ul {
			width: 100%;
			&>li {
				position: relative;
				width: 100%;
				padding: 1px 10px;
				&>a, &>button {
					display: flex;
					flex-direction: row;
					// flex-wrap: wrap;
					align-items: center;
					width: 100%;
					background-color: rgba(0,0,0,0);
					&:hover {
						text-decoration: none;
					}
					.icons {
						width:20px;
						height: 20px;
						display: flex;
						flex-direction: row;
						flex-wrap: wrap;
						align-items: center;
						justify-content: center;
					}
				}
				ul {
					list-style-type: none;
					width: 100%;
					// background: linear-gradient(29deg, rgba(0,193,165,1) 0%, rgba(0,114,173,1) 100%);
					padding: 0;
					margin: 0;
					z-index: 1;
					&>li {
						padding: 0.5px 0;
						margin: 0;
						&>a {
							display: flex;
							flex-direction: row;
							// flex-wrap: wrap;
							align-items: center;
							width: 100%;
							padding: 0 5px 0 10px;
							height: 42px;
							border-radius: 0.25rem;
							transition: all 0.01s 0s linear;
							&:hover {
								text-decoration: none;
								background-color: #f3f3f3;
								transition: all 0.01s 0s linear;
							}
							&.nuxt-link-exact-active {
								background: rgb(0,193,165);
								background: linear-gradient(29deg, rgba(0,193,165,1) 0%, rgba(0,114,173,1) 100%);
								transition: all 0.01s 0s linear;
								color: white;
							}
						}
					}
				}
				// &:hover {
				// 	background-color: rgba(0,0,0,0.5);
				// }
			}
		}
	}
</style>