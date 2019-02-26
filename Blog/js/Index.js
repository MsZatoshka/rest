let posts = new Vue({
	el: "#app",
	data:{
		where: "",
		id_pag: 1,
		col_pag: 1,
		posts: [],
		user:{},
		url:{
			img: "http://blog/web/img/post/",
			post: "post.html?id=",

		},
	},
	methods:{
 		start: function (id) {
	        // Запрос на сервер
	         axios.post("http://blog",{
	         	nik: "maks_sem",
	         	token: "o2130dsajdsa",
	         	idPag: id,
	         })
	        .then((response) =>{
	        	console.log(response);
	        	this.posts = response.data.post;
	        	this.user.token = response.data.token;
	        	this.user.nik = response.data.nik;
	        	this.id_pag = response.data.pag.id_pag;
	        	this.col_pag = response.data.pag.col_pag;
	        })
	        .catch((error) => {
	        	console.log(error);
	        })
 		}
	},
	watch:{
		id_pag: function () {
			this.start(this.id_pag);
		} 
	},
    created: function () {// Vue компонент создан
		console.log('beforeCreate');
		this.start(this.id_pag);
    },
});

 


 

 