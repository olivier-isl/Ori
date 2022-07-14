// import VuetableFieldHandle from 'vuetable-2/src/components/VuetableFieldHandle.vue'
export default [
	{
		name : "order",
		sortField : "order",
		title : "Ordre",
		width: '90px',
		formatter: (value) => {
			return `${value}`
		}
	},{
		name : "title",
		sortField : "title",
		title : "Titre",
	},{
		name : "price",
		title : "Prix",
		width: '120px',
		formatter: (value) => {
			return `${value} &euro;`
		}
	},{
		name : "delay",
		title : "durée",
		width: '120px',
		formatter: (value) => {
			return `${value} mois`
		}
	},{
		name : "updated_at",
		sortField : "updated_at",
		title : "Date",
		width: '200px',
	},{
		name : "status",
		title : "Statuts",
		width: '170px',
		formatter: (value) => {
			switch(value) {
				case 'draft' : return `<span class="badge badge-primary-50 badge-pill px-3 py-2 badge-md fw-600">Brouillon</span>`;
				case 'publish' : return `<span class="badge badge-primary badge-pill px-3 py-2 badge-md fw-600">Publié</span>`;
				case 'archived' : return `<span class="badge badge-secondary badge-pill px-3 py-2 badge-md fw-600">Archivé</span>`;
			}
		}
	},{
		name:'seo',
		title: '<i class="fas fa-search" title="SEO"></i>',
		width: '50px',
	},
	{
		name: "actions",
		title : "Actions",
		width: '109px',
	},
]