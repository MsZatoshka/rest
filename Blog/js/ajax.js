
 

let posts = new Vue({
	el: ".post_all",
	data:{
		post:[],
		my: "1",
		posts: [],
		url:{
			img: "http://blog/web/img/post/",
		},
	},
	methods:{
		getPost: function(id){
			this.posts = "";
			axios.post("http://blog/view/" + id)
			.then((response)=> {
				this.post = response.data;
			});
		},
	},
    beforeCreate: function () {// Vue компонент создан
        console.log('beforeCreate');
        // Запрос на сервер
        axios.post('http://blog')
        .then((response) =>{
        	this.posts = response.data.post;
        })
        .catch((error) => {
        	console.log(error);
        })
    },
});

 


 

 