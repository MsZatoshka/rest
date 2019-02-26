<?php

namespace app\models;

use Yii;
use \yii\db\Query;

/**
 * This is the model class for table "comment".
 *
 * @property int $id
 * @property int $id_post
 * @property int $id_user
 * @property string $text
 * @property string $data
 *
 * @property Post $post
 * @property User $user
 */
class Comment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'comment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_post', 'id_user', 'text'], 'required'],
            [['id_post', 'id_user'], 'integer'],
            [['text'], 'string'],
            [['data'], 'safe'],
            [['id_post'], 'exist', 'skipOnError' => true, 'targetClass' => Post::className(), 'targetAttribute' => ['id_post' => 'id']],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['id_user' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_post' => 'Id Post',
            'id_user' => 'Id User',
            'text' => 'Text',
            'data' => 'Data',
        ];
    }
    public function get_comment($id,$offset,$limit){
        $query = new Query();
        $result  = $query->select('comment.id,comment.text, comment.data, user.nik')
        ->from('comment')
        ->join('INNER JOIN', 'user' , 'user.id = comment.id_user')
        ->WHERE(["id_post" => $id])
        ->offset($offset)
        ->LIMIT($limit)
        ->all(); 
        return $result;
    }
   

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPost()
    {
        return $this->hasOne(Post::className(), ['id' => 'id_post']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'id_user']);
    }
}
