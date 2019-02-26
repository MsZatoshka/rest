<?
    namespace app\controllers;

    use app\models\Post;
    use app\models\Comment;
    use app\models\Category;
    use app\models\User;
    use \yii\db\Query;
    use yii;

                // $token =  Yii::$app->getSecurity()->generateRandomString(24);
    class BlogController extends ApiController{

        public function actionIndex(){ // показ всех постов
            $model_post = new Post(); 
            $model_user = new User();  
            // принять параметры POST  --------------------------------------------------------------------------------------------------------------------------------
            $post_error = Yii::$app->request->getRawBody();  
            $post = json_decode($post_error);
            //  --------------------------------------------------------------------------------------------------------------------------------
            //  определение параметров
            $id_pag = $model_post->pag($post->idPag);
            $offset = $model_post->get_offset($id_pag);
            return [
                "post" => $model_post->getPostsAll($offset ,4),
                'token' =>  $post->token,
                'nik' =>  $post->nik,
                "pag" =>[
                    'id_pag' => $id_pag,
                    'col_pag' => $model_post->get_pag($model_post->getCount()),
                ],
            ];
        }
        public function actionView($id){ // показ одного поста
            $model_user = new User();
            $model_post = new Post();
            $model_comment = new Comment();
            $model_category = new Category();
             // принять параметры POST  --------------------------------------------------------------------------------------------------------------------------------
            $post_error = Yii::$app->request->getRawBody();  
            $post = json_decode($post_error);
            //  --------------------------------------------------------------------------------------------------------------------------------
            //  определение параметров
            $id_pag = $model_post->pag($post->idPag); 
            $offset = $model_post->get_offset($id_pag); 
            return [
                'post' => $model_post->getPost($id),
                'comments' => $model_comment->get_comment($id,$offset,4),
                'categorys' => $model_category->getPost_category($id),
                'token' =>  $post->token,
                'nik' =>  $post->nik,
                "pag" =>[
                    'id_pag' => $id_pag,
                    'col_pag' => $model_post->get_pag($model_post->getCount()),
                ]
            ];
        }
        public function actionCategoryall(){ // показ всех категории
            $category_model = new Category();
            $model_user = new User();
            $model_post = new Post();
             // принять параметры POST  --------------------------------------------------------------------------------------------------------------------------------
             $post_error = Yii::$app->request->getRawBody();  
             $post = json_decode($post_error);
             //  --------------------------------------------------------------------------------------------------------------------------------
            return [
                'category' => $category_model->getCategoryAll(),
                'token' =>  $post->token,
                'nik' =>  $post->nik,
            ];
        }
        public function actionCategory($id){// показ постов одной категории
            $category_model = new Category();
            $model_post = new Post();
            $model_user = new User();
            $posts = $category_model->getCategoryPostsAll($id);
             // принять параметры POST  --------------------------------------------------------------------------------------------------------------------------------
             $post_error = Yii::$app->request->getRawBody();  
             $post = json_decode($post_error);
             //  --------------------------------------------------------------------------------------------------------------------------------
            // определение параметров
            $id_pag = $model_post->pag($_REQUEST['id_pag']); 
            $offset = $model_post->get_offset($id_pag); 
            $user  = $model_user->Check_User($_REQUEST);
            return [
                "category" =>  $category_model->getCategoryName($id),
                "post" => $posts["all"],
                'token' =>  $post->token,
                'nik' =>  $post->nik,
                'pag' =>[
                    'id_pag' => $id_pag,
                    'col_pag' => $model_post->get_pag($post['kolvo']),
                ]
            ];
        }
        public function actionAuth(){
            // $error;
            $model_user  = new User();
            // принять параметры POST  --------------------------------------------------------------------------------------------------------------------------------
            $post_error = Yii::$app->request->getRawBody();  
            $post = json_decode($post_error);
            //  --------------------------------------------------------------------------------------------------------------------------------
            $error = $model_user->CheckReqAuth($post->login,$post->password);
            // проверка не все указано
            if(!empty($error)){
                return [
                   "error" => $error
                ];
            }else{ // все указано
                $check_dann = $model_user->CheckDannAuth($post->login,$post->password);
                if($check_dann != null){
                    return $check_dann;
                }else{// неверно указан пароль или логин
                    $error['user'] = "не верно указаны данные";
                    return  [
                        "error" => $error
                    ];
                }
            }
        }
        // Регистрация
        public function actionReg(){
            $model_user = new User();
            // принять параметры POST  --------------------------------------------------------------------------------------------------------------------------------
            $post_error = Yii::$app->request->getRawBody();  
            $post = json_decode($post_error);
            //  --------------------------------------------------------------------------------------------------------------------------------
            $error["login"] = $model_user->checkLogin($post->login);    
            $error["nik"] = $model_user->checkNik($post->nik);  
            $error["email"] = $model_user->checkEmail($post->email);  
            $error["password"] = $model_user->checkPassword($post->password,$post->password_2); 
            foreach ($error as $key => $value) {
                if(!empty($value)){// проверка что ошибка есть
                    $a[$key] = $value;
                }
            }// передача ошибок если есть
            if(!empty($a)){
                return $a;
            }else{
                // Создание записи 
                $token = Yii::$app->getSecurity()->generateRandomString(24);
                $model_user->CreateUser($post,$token);
                return [
                    "token" => $token,
                    "nik" => $post->nik,
                ];
            }
        }
        //  ПОиск постов
        public function actionLike($name){
            $model_post = new Post();
            $model_user = new User();
             // принять параметры POST  --------------------------------------------------------------------------------------------------------------------------------
             $post_error = Yii::$app->request->getRawBody();  
             $post = json_decode($post_error);
             //  --------------------------------------------------------------------------------------------------------------------------------
            $posts = $model_post->getPostLike($name); // запрос с LIKE
              // определение параметров
            $id_pag = $model_post->pag($_REQUEST['id_pag']); 
            $offset = $model_post->get_offset($id_pag); 
            return [
                "post" => $posts,
                'token' =>  $post->token,
                'nik' =>  $post->nik,
                'pag' =>[
                    'id_pag' => $id_pag,
                    'col_pag' => $model_post->get_pag($post['kolvo']),
                ],
            ];  
        }
    }
?>