<?
    namespace app\controllers;


    use yii\rest\Controller;
    // Настройка для REST под JSON
    class ApiController extends Controller{
        public function behaviors(){
            $behaviors = parent::behaviors();
            $behaviors['corsFilter' ] = [
                'class' => \yii\filters\Cors::className(),
            ];
            $behaviors['contentNegotiator'] = [
                'class' => \yii\filters\ContentNegotiator::className(),
                'formats' => [
                    'application/json' => \yii\web\Response::FORMAT_JSON,
                ],
            ];
            return $behaviors;
        }
    }
?>