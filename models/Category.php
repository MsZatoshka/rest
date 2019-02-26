<?php

namespace app\models;

use Yii;
use \yii\db\Query;

/**
 * This is the model class for table "category".
 *
 * @property int $id
 * @property string $name
 *
 * @property PostCategory[] $postCategories
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 128],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }
    public function getPost_category($id){ // показать название категории у поста
        $query = new Query();
        $result = $query->select('category.name')
        ->from('post_category')
        ->join('INNER JOIN', 'category' , 'category.id = post_category.id_category')
        ->WHERE(["post_category.id_post" => $id])
        ->all();
        return $result;
        
    }
    public function getCategoryAll(){ // показать все категории
        return $this::find()->all();
    }
    public function getCategoryPostsAll($id){ // показать посты 1 категории
        $query = new Query();
            $query_set = $query->select('post.id, post.anons, post.title,post.img,post.data')
                ->from('post_category')
                ->join('INNER JOIN', 'post' , 'post.id = post_category.id_post')
                ->where(["post_category.id_category" => $id]);
                $kovlo = $query_set->count();
                $result = $query_set->limit(4)->all();
                return [
                    "all" => $result,
                    "kolvo" =>$kovlo
                ];
    }
    public function getCategoryName($id){// название категории
        return $this::findOne($id)->name;
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostCategories()
    {
        return $this->hasMany(PostCategory::className(), ['id_category' => 'id']);
    }
}
