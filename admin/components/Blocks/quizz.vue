<template lang="pug">
	b-tab(title="Quizz" active)
		div.quizzBlock.w-100.d-flex.flex-row.flex-wrap.px-0.pb-3
			div.col-12.pl-0
				div.col-12.px-0.pb-3
					b-button(
						variant="primary"
						@click="addQuestion"
					) 
						i.fas.fa-plus.mr-2
						| Ajouter un question
				draggable(
					v-model="questions"
					tag="div"
					@end="changeOrder"
				).col-12.px-0
					.card.border(v-for="(question, index) in questions" :key="index")
						.card-header.d-flex.flex-row.flex-wrap.align-items-center.justify-content-between.px-0.py-0
							div.d-flex.flex-row.flex-wrap.align-items-center
								span.btn.btn-block.grab.p-0.d-flex.flex-row.flex-wrap.align-items-center.justify-content-center(
									:id="'drag'+index"
									v-if="questions.length > 1"
								)
									faLayers.icons
										faIcon.fa-lg(icon="grip-vertical")
								b-tooltip(
									:target="'drag'+index" 
									triggers="hover"
									placement="right"
									v-if="questions.length > 1"
								) Glisser et déplacer pour ré-ordonner vos questions
								span.h6.px-3.pt-3.pb-2 Question {{index + 1}} : {{question.label}}
							div.group-button
								button.btn.btn-block(
									v-if="questions.length > 1 && index == 0"
									@click="setTo(index, index+1, null, 'questions')"
								)
									i.fas.fa-chevron-down
								button.btn.btn-block(
									v-if="index > 0 && index < questions.length-1"
									@click="setTo(index, index+1, null, 'questions')"
								)
									i.fas.fa-chevron-down
								button.btn.btn-block(
									v-if="index > 0"
									@click="setTo(index, index-1, null, 'questions')"
								)
									i.fas.fa-chevron-up
								b-button(
									v-b-toggle="'collapse-'+index"
									variant="block"
								)
									i.fas.fa-caret-up(
										v-show="question.collapse"
									)
									i.fas.fa-caret-down(
										v-show="!question.collapse"
									)
								button.remove(
									@click="removeQuestion(index)"
								)
									i.fas.fa-trash
						b-collapse.card-body(
							:visible="question.collapse"
							:id="'collapse-'+index"
						)
							div.w-100.pb-3
								b-input(placeholder="Quel est la question ?" v-model="question.label")
							div.w-100
								div.w-100.pb-3
									b-button(
										variant="secondary"
										@click="addAnswer(index)"
									) Ajouter une réponse
								div.w-100.d-flex.flex-row.flex-wrap.pb-3(v-for="(answer, i) in question.answers")
									.input-group.border.rounded(
										:class="{'border-success': answer.right}"
									)
										div.col-2.d-flex.flex-row.flex-wrap.align-items-center.input-group-text.px-4
											div.input-group.d-flex.flex-row.flex-wrap.align-items-center.pl-2
												label.ml-3.form-check-label(
													:for="'Q'+index+'-'+i"
												) Bonne réponse ?
												input.form-check-input(
													:id="'Q'+index+'-'+i"
													type="checkbox"
													:value="'R'+index+'-'+i"
													v-model="answer.right"
													@change="pickRightAnswer(index, i, answer.right)"
												)
										b-input.h-100(placeholder="Quel est la résponse ?" v-model="answer.label")
										button.remove(
											@click="removeAnswer(index, i)"
										)
											i.fas.fa-trash
								div.separator
								div.w-100.pt-3
									quill-editor(
										ref="quillExplication"
										v-model="question.explication"
										:options="editorOption"
									)


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
	},
	data() {
		return {
			questions: [],
			editorOption: {
				readOnly: false,
				theme: 'snow',
				placeholder: 'Explication de la ou des réponse(s)',
			},
		}
	},
	async fetch() {
		if(this.$route.params.id) {
			await fetch("http://127.0.0.1:8000/api/quizz/" + this.$route.params.id).then(res => {
				return res.json()
			}).then(r => {
				console.log(r)
				return r.data[0]
			}).then(data => {
				if('question' in JSON.parse(data.json)) {
					this.questions = JSON.parse(data.json).question
				}
			})
		}
	},
	computed: {
		
	},
	methods: {
		setTo(a, b, c, element) {
			if(element === 'questions'){
				const d = this.questions.splice(a, 1);
				this.questions.splice(b, 0, ...d);
			}
			if(element === 'answers'){
				const d = this.questions[a].answers.splice(c, 1)
				this.questions[a].answers.splice(b, 1, ...d)
			}
			this.$emit('handleQuestion', this.questions)
		},
		changeOrder() {
			this.$emit('handleQuestion', this.questions)
		},
		addQuestion() {
			this.questions.push({label: "", answers : [], explication: "", collapse : false})
			this.$emit('handleQuestion', this.questions)
		},
		removeQuestion(index) {
			this.questions.splice(index, 1)
			this.$emit('handleQuestion', this.questions)
		},
		addAnswer(index) {
			this.questions[index].answers.push({
				label : "",
				right : false
			})
			this.$emit('handleQuestion', this.questions)
		},
		removeAnswer(index, i) {
			this.questions[index].answers.splice(i, 1)
			this.$emit('handleQuestion', this.questions)
		},
		pickRightAnswer(index, i, value) {
			this.questions[index].answers[i].right = value
			this.$emit('handleQuestion', this.questions)
		}
	},
}
</script>

<style lang="scss">
.grab {
	cursor: grab !important;
	&.grabbing {
		cursor: grabbing !important;
	}
}
.group-button {
	display: flex;
	flex-direction: row;
	flex-wrap: wrap;
	>* {
		margin: 0 !important;
		border-radius: 0;
		outline: none !important;
		box-shadow: none !important;
		&:first-child {
			border-radius: 4px 0 0 4px;
		}
		&:not(:first-child) {
			border-left: 1px solid lighten(rgb(34 41 47), 60%);
		}
		&:last-child {
			border-radius : 0 4px 4px 0;
		}
	}
	
}
.btn-block {
	width: 45px;
	height: 45px;
	background-color: darken(#F7F7F7, 5%);
	transition: all 0.2s;
	&:hover {
		background-color: lighten(#F7F7F7, 5%);
		color: black;
	}
}
.remove {
	width: 45px;
	height: 45px;
	border: none;
	border-left: 1px solid lighten(rgb(34 41 47), 60%);
	transition: all 0.2s;
	background-color: lighten(rgb(0,0,0), 80%);
	border-radius: 0 4px 4px 0;
	color: black;
	&:hover {
		background-color: lighten(rgb(255,0,0), 30%);
		color: white;
		border-left: 1px solid lighten(rgb(34 41 47), 60%);
	}
}
</style>