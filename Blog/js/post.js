let posts = new Vue({
	el: "#app",
	data:{
		id_pag: 1,
		col_pag: 1,
		post: [],
		user:{},
		url:{
			img: "http://blog/web/img/post/",

		},
	},
	methods:{
 		start: function (id) {

 		}   
	},
	watch:{
		id_pag: function () {
			// this.start(this.id_pag);
		} 
	},
    created: function () {// Vue компонент создан
		console.log('beforeCreate');
		// this.start(this.id_pag);
    },
} ) 


 