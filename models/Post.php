<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "post".
 *
 * @property int $id
 * @property string $title
 * @property string $anons
 * @property string $text
 * @property string $data
 * @property string $img
 *
 * @property Comment[] $comments
 * @property PostCategory[] $postCategories
 */
class Post extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'post';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'anons', 'text', 'img'], 'required'],
            [['anons', 'text'], 'string'],
            [['data'], 'safe'],
            [['title'], 'string', 'max' => 128],
            [['img'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'anons' => 'Anons',
            'text' => 'Text',
            'data' => 'Data',
            'img' => 'Img',
        ];
    }



 
    public function pag($res){// принятие пагинации
        $id_pag = $res;
        if(!empty($id_pag) ){
            return $id_pag;
        }else{
            return 1;
        }
    } 
    public function get_pag($kolvo){// количество пагинации
        $set = (int) ($kolvo / 4) + 1;
        return $set;
    }
    public function get_offset($id){// получить offset
        return ($id-1) * 4;
    }
    public function getCount(){ // получить количество записей постов
        return $this::find()->count();
    }
    public function getPostsAll($offset,$limit){ // показать все посты
        return $this::find()->select(['id','anons','title','data', 'img'])->offset($offset)->limit($limit)->all();
    }
    public function getPost($id){ // показать пост
        return $this::find()->select(["text","title","data", "img"])->WHERE(['id' => $id])->all();
    }
    public function getPostLike($name){ // показать пост
        return $this::find()->select(['id','anons','title','data', 'img'])->filterWhere(['like', 'title', $name])->offset($offset)->limit($limit)->all();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comment::className(), ['id_post' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostCategories()
    {
        return $this->hasMany(PostCategory::className(), ['id_post' => 'id']);
    }
}
