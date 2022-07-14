// import VuetableFieldHandle from 'vuetable-2/src/components/VuetableFieldHandle.vue'

export default [

	{
		name : "id",
		sortField : "id",
		title : "Id",
		width: '62px',
	},{
		name : "login",
		sortField : "login",
		title : "Nom",
	},{
		name : "email",
		sortField : "email",
		title : "Email",
	},{
		name : "created_at",
		sortField : "created_at",
		title : "Inscrit le",
		width: '200px',
		formatter: (value) => {
			return `${value}`
		}
	},{
		name: "actions",
		title : "Actions",
		width: '200px',
	},
]