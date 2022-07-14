<template lang="pug">
	section.container-fluid
		header.row
			div.col-12.py-3.d-flex.flex-row.flex-wrap.align-items-center
				h2.pb-0.mb-0 Toutes les leçons
				nuxt-link.btn.btn-success.btn-sm.mx-3(:to="localePath({name: 'lessons-add'})") Ajouter une nouvelle leçon
		div.row.p-3
			div.col-12.mb-3.d-flex.flex-row.flex-wrap.px-3.card.py-3
				div.col.statusButton.px-0(
					ref="statusButton"
				)
					nuxt-link.btn.btn-transparent.all(
						:to="localePath({name:'lessons', hash: ''})"
						:class="[$route.hash === ''? 'active': '']"
					) 
						| Tous
						span.badge.badge-primary.ml-2(
							v-show="countStatus"
						) {{countStatus.all}}
					nuxt-link.btn.btn-transparent.publish(
						:to="localePath({name:'lessons', hash: '#publish'})"
						v-show="countStatus.publish > 0"
						:class="[$route.hash === '#publish'? 'active': '']"
					) 
						| Publié
						span.badge.badge-primary.ml-2(
							v-show="countStatus"
						) {{countStatus.publish}}
					nuxt-link.btn.btn-transparent.draft(
						:to="localePath({name:'lessons', hash: '#draft'})"
						v-show="countStatus.draft > 0"
						:class="[$route.hash === '#draft'? 'active': '']"
					) 
						| Brouillon
						span.badge.badge-primary.ml-2(
							v-show="countStatus"
						) {{countStatus.draft}}
					nuxt-link.btn.btn-transparent.archived(
						:to="localePath({name:'lessons', hash: '#archived'})"
						v-show="countStatus.archived > 0"
						:class="[$route.hash === '#archived'? 'active': '']"
					) 
						| Archivé
						span.badge.badge-primary.ml-2(
							v-show="countStatus"
						) {{countStatus.archived}}
				div.col-2.px-0
					input.form-control(
						type="text"
						v-model="search"
						@focus="eventSearch()"
						ref="searchInput"
						placeholder="Rechercher un cours"
					)
				div.col-12.pt-3.px-0.d-flex.flex-row.flex-wrap
					button.btn.btn-outline-secondary.btn-sm Archiver
			div.w-100.position-relative.mb-3
				vuetable.w-100.card.rounded-8px.mb-0(
					ref="vuetable"
					:fields="fields"
					:api-mode="false"
					:per-page="perPage"
					:row-class="rowClass"
					:data-manager="dataManager"
					loading="test"
					pagination-path="pagination"
					@vuetable:pagination-data="onPaginationData"
					:sort-order="sortOrder"
					:css="css.table"
				)
					div.w-100(slot="title" slot-scope="props")
						nuxt-link(:to="localePath({name : 'lessons-id', params: {id : props.rowData.id}})") {{props.rowData.title}}
					div.w-100(slot="updated_at" slot-scope="props")
						span(v-if="props.rowData.status === 'publish'") Publié le 
						span(v-else) Dernière modification le
						br
						| {{ getDate(props.rowData.updated_at)}}
					div.w-100(slot="seo" slot-scope="props")
						p test
					div.w-100.text-right(slot="actions" slot-scope="props")
						nuxt-link.btn.btn-light.mx-1(
							:to="localePath({name : 'lessons-id', params: {id : props.rowData.id}})"
							v-b-tooltip.hover.top="'Éditer'"
						)
							faIcon(icon="edit")
						Button.btn.btn-light.mx-1(
							v-b-tooltip.hover.top="'Archiver'"
							@click="archiveItems(props.rowData)"
						)
							faIcon(icon="trash")
				div.loading(v-show="loading") Chargement
			div.w-100(style="padding-top:10px")
				VuetablePagination(
					ref="pagination"
					@vuetable-pagination:change-page="onChangePage"
				)
</template>

<script>

import Vuetable from 'vuetable-2'
import VuetablePagination from "vuetable-2/src/components/VuetablePagination";
import _ from "lodash";

import FieldsDef from "~/assets/data/lessonsHandle.js";
export default {
	components: {
		Vuetable,
		VuetablePagination
	},
	data() {
		return {
			data : [],
			data_origin : [],
			fields : FieldsDef,
			perPage : 10,
			search: '',
			currentStatus: 'all',
			keyTime : null,
			countStatus : {},
			loading : false,
			css : {
				table: {
					tableWrapper: '',
					tableHeaderClass: 'mb-0',
					tableBodyClass: 'mb-0',
					tableClass: 'table table-bordered table-hover',
					loadingClass: 'loading',
					ascendingIcon: 'fa fa-chevron-up',
					descendingIcon: 'fa fa-chevron-down',
					ascendingClass: 'sorted-asc',
					descendingClass: 'sorted-desc',
					sortableIcon: 'fa fa-sort',
					detailRowClass: 'vuetable-detail-row',
					handleIcon: 'fa fa-bars text-secondary',
					renderIcon(classes, options) {
						return `<i class="${classes.join(' ')}"></span>`
					}
				},
			},
			sortOrder: [{
				field: 'updated_at',
				direction: 'asc'
			}],
		}
	},
	async fetch() {
		this.loading = true
		this.data = await fetch("http://127.0.0.1:8000/api/lessons").then(res => {
			return res.json()
		}).then(r => {
			this.countStatus = Object.assign({}, this.countStatus, {
				publish : r.data.filter(el => el.status === 'publish').length,
				draft : r.data.filter(el => el.status === 'draft').length,
				archived : r.data.filter(el => el.status === 'archived').length,
				all : r.data.filter(el => el.status === 'publish').length + r.data.filter(el => el.status === 'draft').length
			})
			this.data_origin = r.data
			this.loading = false;
			if(this.$route.hash.length > 0) {
				return r.data.filter(data => data.status === this.$route.hash.slice(1))
			}
			return r.data.filter(data => data.status !== 'archived')
		})
	},
	watch: {
		data(_newVal, _oldVal) {
			this.$refs.vuetable.refresh();
		},
		$route(to, _from) {
			// console.log(to.hash)
			this.loading = true
			if(to.hash.length > 0) {
				this.getByStatus(to.hash.slice(1))
			} else {
				this.getAll();
			}
		}
	},
	watchQuery : ['page'],
	methods: {
		getDate(date) {
			return this.$moment(new Date(date)).format("DD/MM/YYYY à h[h]mm")
		},
		rowClass(dataItem, _index) {
			return dataItem.status
		},
		onPaginationData(paginationData) {
			this.$refs.pagination.setPaginationData(paginationData);
		},
		onChangePage(page) {
			this.$refs.vuetable.changePage(page);
		},
		dataManager(sortOrder, pagination) {
			if (this.data.length < 1) return;

			let local = this.data;

			// sortOrder can be empty, so we have to check for that as well
			if (sortOrder.length > 0) {
				local = _.orderBy(
					local,
					sortOrder[0].sortField,
					sortOrder[0].direction
				);
			}

			pagination = this.$refs.vuetable.makePagination(
				local.length,
				this.perPage
			);
			const from = pagination.from - 1;
			const to = from + this.perPage;

			return {
				pagination,
				data: _.slice(local, from, to)
			};
		},
		archiveItems(data) {
			if(data.status !== 'archived') {
				this.$swal({
					icon : 'warning',
					title : 'Archiver "' + data.title + '"',
					html : 'Souhaitez-vous réellement archiver ce cours ?',
					showConfirmButton : true,
					showCancelButton : true,
					confirmButtonText : 'Archiver',
					cancelButtonText : "Annuler",
				}).then((res) => {
					if(res.isConfirmed) {
						this.data.updated_at = Date.now()
						this.$axios.$put("http://127.0.0.1:8000/api/lessons/" + data.id, {
							status : "archived"
						}).then(res => {
							this.$fetch()
							this.$swal.fire({
								toast: true,
								icon: 'success',
								title: 'Cours Archivé',
								position: 'top-end',
								timerProgressBar : true,
								timer: 2000,
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
				});
			} else {
				this.$swal({
					icon : 'warning',
					title : 'supprimer "' + data.title + '"',
					html : 'Souhaitez-vous réellement supprimer ce cours ?<br><br><b>Attention, vous ne pourrez-plus récupérer ce contenu.</b><br><br>En êtes-vous réellement sûr ?',
					showConfirmButton : true,
					showCancelButton : true,
					confirmButtonText : 'Supprimer',
					cancelButtonText : "Annuler",
				}).then((res) => {
					if(res.isConfirmed) {
						this.$axios.$delete("http://127.0.0.1:8000/api/lessons/" + data.id).then(res => {
							console.log(res)
							this.$fetch().then(() => {
								if(this.countStatus[this.$route.hash.slice(1)] <= 0) {
									this.$router.push('/lessons')
								}
							})
							this.$swal.fire({
								toast:  true,
								icon: 'success',
								title: 'Cours supprimer',
								timer: 2000,
								position: 'top-end',
								timerProgressBar : true,
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
				});
			}
			
		},
		getAll() {
			if(this.data_origin.length <= 0) {
				this.$axios.$get("http://127.0.0.1:8000/api/lessons").then(res => {
					this.data = res.data.filter(data => data.status !== 'archived')
					this.data_origin = res.data
					this.loading = false
				})
			} else {
				this.data = this.data_origin.filter(data => data.status !== 'archived')
				setTimeout(() => {this.loading = false}, 200)
			}
		},
		getByStatus(status) {
			if(this.data_origin.length <= 0) {
				this.$axios.$get("http://127.0.0.1:8000/api/statuslessons/"+status).then(res => {
					this.data = res.data
					this.loading = false
				})
			} else {
				this.data = this.data_origin.filter(data => data.status === status)
				setTimeout(() => {this.loading = false}, 200)
			}
		},
		keyUp(e) {
			this.loading = true
			this.keyTime = setTimeout(() => {
				if(this.search.length > 0) {
					this.$axios.$get("http://127.0.0.1:8000/api/search/"+this.search, {
						params: {
							type : 'lessons'
						}
					}).then(res => {
						this.data = res.data
						this.loading = false
					})
				} else {
					this.getAll();
					this.loading = false
				}
			}, 1000)
		},
		keyDown(e) {
			clearTimeout(this.keyTime)
		},
		eventSearch() {
			this.$refs.searchInput.addEventListener('keyup', (event) => {
				this.keyUp(event)
			})
			this.$refs.searchInput.addEventListener('keydown', (event) => {
				this.keyDown(event)
			})
		},
		moment() {
			return this.$moment()
		}
	},
}
</script>

<style lang="scss">
	.loading {
		background-color: rgba(255,255,255,0.8);
		position: absolute;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		z-index: 1;
		display: flex;
		flex-direction: row;
		flex-wrap: wrap;
		align-items: center;
		justify-content: center;
		color: black;
	}
	.statusButton {
		.btn {
			outline: none;
			&:hover, &:focus, &:active, :visited {
				outline: none;
				box-shadow: none;
			}
		}
		button, a {
			color: #0077AD;
			cursor: pointer;
			transition: all 0.1s 0s linear;
			&:hover {
				color: #0077AD;
			}
			&.active {
				cursor: default !important;
				font-weight: 800;
				color: black;
				pointer-events: none;
			}
		}
	}

	.vuetable-td-order {
		vertical-align: center;
		text-align: center;
	}
	.vuetable-th-slot-seo {
		text-align:center;
	}
</style>
