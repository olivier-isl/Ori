<template lang="pug">
	b-tab.px-0.py-0(
		:title="titleLesson"
		active
	)
		div.lesson.px-3
			div.col-12.py-3.px-0.d-flex.flex-row.flex-wrap
				div.col-6
					label Leçons
					b-form-select(
						list="lessonList"
						@input="add($event)"
						ref="selectOptionsLessons"
					)
						option(
							v-for="item in lessonList"
							:value="{lessons_id : item.id}"
						) {{item.title}}
				div.col-6
					label Quizz
					b-form-select(
						list="quizzList"
						@input="add($event)"
						ref="selectOptionsQuizz"
					)
						option(
							v-for="item in quizzList"
							:value="{quizz_id : item.id}"
						) {{item.title}}

			div.table-responsive
				table.table.table-hover(
					v-if="items.length > 0"
					hover
					bordered
					sticky-header
					head-variant="light"
					:items="items"
					:fields="fields"
					ref="tableLesson"
				)
					thead.thead-light
						tr
							th(
								v-for="field in fields"
								scope="col"
							)
								| {{field.label}}
					draggable(
						v-model="items"
						tag="tbody"
						@end="changeOrder"
					)
						tr(
							v-for="(item, key) in items"
							:key="key"
						)
							td
								faLayers.icons
									faIcon.fa-lg(icon="grip-vertical")
							td {{item.order}}
							td {{item.title}}
							td {{showType(item)}}
							td
								button.btn.btn-secondary(
									@click="remove(item, key)"
								) Supprimer
					
				div.col-12.mb-3.text-center(v-else) Aucune leçon enregistrée
</template>

<script>
import Sortable from 'sortablejs';

const createSortable = (el, options, vnode) => {
	let order = [];
	const data = options.data;
	const orderColumn = options.orderColumn;
	for(let i = 0; i < el.children.length; i++) {
		el.children[i].setAttribute('sortable-id', i);
	}
	return Sortable.create(el, {
		dataIdAttr: 'sortable-id',
		chosenClass : options.chosenClass,
		onStart() {
			order = [...this.toArray()];
		},
		onEnd(evt) {
			this.sort(order);
			data.splice(evt.newIndex, 0, ...data.splice(evt.oldIndex, 1));
			if (!orderColumn) return;
			data.forEach((o, i) => {
				o[orderColumn] = i + 1;
			});
		},
	});
};

const sortable = {
	name: 'sortable',
	bind(el, binding, vnode) {
		const table = el;
		table._sortable = createSortable(table.querySelector("tbody"), binding.value, vnode);
	},
};
export default {
	directives : {sortable},
	props: {
		lessons : {
			type : Array,
			default() {
				return []
			}
		}
	},
	data() {
		return {
			fields: [
				{key : 'drag', label: '', width:"120px"},
				{key : 'order', label: 'Ordre'},
				{key : 'title', label: 'Titre'},
				{key : 'type', label: 'Type'},
				{key : 'action', label: 'Action'},
			],
			items : [],
			lists : [],
			quizz: [],
		}
	},
	async fetch() {
		const data = await Promise.all(
			[
				this.$axios.$get("http://127.0.0.1:8000/api/lessons/", {
					params : {
						status : "publish"
					}
				}),
				this.$axios.$get("http://127.0.0.1:8000/api/quizz/", {
					params : {
						status : "publish"
					}
				}),

			])
		this.lists = data[0].data
		this.quizz = data[1].data
		if(this.$route.params.id) {
			this.items = await fetch("http://127.0.0.1:8000/api/lessons_courses/courses/" + this.$route.params.id).then(res => {
				return res.json()
			}).then(r => {
				return r.data.map(el => {
					let title;
					if(el.lessons_id && el.quizz_id === null) title = this.lists.find(list => list.id === el.lessons_id).title
					if(el.quizz_id && el.lessons_id === null) title = this.quizz.find(q => q.id === el.quizz_id).title
					return {
						order : el.order,
						lessons_id: el.lessons_id,
						quizz_id: el.quizz_id,
						title,
						openChangeOrder : false,
					}
				})
			})
		}
	},
	computed: {
		titleLesson() { // affiche "leçon (n) + plurielisation"
			return `${this.$tc('lesson', this.items.length)} et quizz (${this.items.length})`
		},
		getLessonLastOrder() { // récupère le dernier ordre de la liste de leçon enregistrée dans le cours
			return this.items.length > 0 ? this.items[this.items.length-1].order: -1
		},
		lessonList() { // liste les leçonss non enregistrée dans le cours
			return this.lists.filter(el => {
				return this.items.every(item => {
					return item.lessons_id !== el.id
				})
			})
		},
		quizzList() { // liste les quizz non enregistrée dans le cours
			return this.quizz.filter(el => {
				return this.items.every(item => {
					return item.quizz_id !== el.id
				})
			})
		},
	},
	methods: {
		add(val) { // ajoute une leçon ou un quizz dans le cours
			if(this.$route.params.id) {
				if('lessons_id' in val) {
					this.$axios.$post('http://127.0.0.1:8000/api/lessons_courses', {
						order: Number(this.getLessonLastOrder)+1,
						courses_id: Number(this.$route.params.id),
						lessons_id: val.lessons_id,
						quizz_id: null
					}).then(res => {
						if(res.success) {
							this.items.push({
								order: Number(this.getLessonLastOrder)+1,
								lessons_id: val.lessons_id,
								quizz_id: null,
								title: this.lists.find(item => item.id === val.lessons_id).title,
								openChangeOrder : false,
							})
						}
					})
				}
				if('quizz_id' in val) {
					this.$axios.$post('http://127.0.0.1:8000/api/lessons_courses', {
						order: Number(this.getLessonLastOrder)+1,
						courses_id: Number(this.$route.params.id),
						lessons_id: null,
						quizz_id: val.quizz_id
					}).then(res => {
						if(res.success) {
							this.items.push({
								order: Number(this.getLessonLastOrder)+1,
								lessons_id: null,
								quizz_id: val.quizz_id,
								title: this.quizz.find(item => item.id === val.quizz_id).title,
								openChangeOrder : false,
							})
						}
					})
				}
			} else {
				Promise.resolve(val).then(data => {
					if('lessons_id' in data) {
						this.items.push({
							order: Number(this.getLessonLastOrder)+1,
							lessons_id: data.lessons_id,
							quizz_id: null,
							title: this.lists.find(item => item.id === data.lessons_id).title,
							openChangeOrder : false,
						})
					}
					if('quizz_id' in data) {
						this.items.push({
							order: Number(this.getLessonLastOrder)+1,
							lessons_id: null,
							quizz_id: data.quizz_id,
							title: this.quizz.find(item => item.id === data.quizz_id).title,
							openChangeOrder : false,
						})
					}
					this.$emit('setLesson', this.items)
				})
			}
			this.$refs.selectOptionsLessons.$el.selectedIndex = 0
			this.$refs.selectOptionsQuizz.$el.selectedIndex = 0
		},
		// lesson or quizz
		remove(row, key) { // efface une leçon ou un quizz dans le cours
			if(this.$route.params.id) {
				this.$axios.$delete("http://127.0.0.1:8000/api/lessons_courses/courses/" + this.$route.params.id, {
					data : {
						courses_id: this.$route.params.id,
						lessons_id: row.lessons_id,
						quizz_id: row.quizz_id,
					}
				}).then(res => {
					this.items.splice(key, 1)
					this.items.forEach(el => {
						el.order = key
						this.changeOrder()
					})
				})
			}else {
				this.items.splice(key, 1)
				this.$emit('setLesson', this.items)
			}
			this.$refs.selectOptionsLessons.$el.selectedIndex = 0
			this.$refs.selectOptionsQuizz.$el.selectedIndex = 0

		},
		
		// lesson or quizz
		changeOrder() {
			if(this.$route.params.id) {
				this.items.forEach((el, i) => {
					el.order = i
					this.$axios.$put('http://127.0.0.1:8000/api/lessons_courses/courses/' + this.$route.params.id, {
						courses_id: this.$route.params.id,
						lessons_id: el.lessons_id,
						quizz_id: el.quizz_id,
						order : el.order
					})
				})
			}else {
				this.items.forEach((el, i) => {
					el.order = i
				})
				this.$emit('setLesson', this.items)
			}
		},
		showType(data) {
			if(data.lessons_id && data.quizz_id === null) return "Leçons"
			if(data.quizz_id && data.lessons_id === null) return "Quizz"
		}	
	}
}
</script>

<style lang="scss">
table {
	tr {
		cursor: grab;
	}
	.sortable-ghost {
		opacity: 0;
	}
}
</style>