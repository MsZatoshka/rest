<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $login
 * @property string $password
 * @property string $nik
 * @property string $email
 * @property string $data
 * @property string $check_ban
 *
 * @property Comment[] $comments
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['login', 'password', 'nik', 'email'], 'required'],
            [['data'], 'safe'],
            [['check_ban'], 'string'],
            [['login', 'password', 'nik'], 'string', 'max' => 32],
            [['email'], 'string', 'max' => 128],
            [['login'], 'unique'],
            [['nik'], 'unique'],
            [['email'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'login' => 'Login',
            'password' => 'Password',
            'nik' => 'Nik',
            'email' => 'Email',
            'data' => 'Data',
            'check_ban' => 'Check Ban',
        ];
    }

    public function Check_User($res){   // проверка аудинтификации
        $array = $res;
        // if(!empty($array["token"]) && !empty($array["nik"])){
        //     $array_my["0"] = $array["token"];
        //     $array_my["1"] = $array['nik'];
        //     return $array_my;
        // }else{
        //     return false;
        // }
        return $res;
    }
    // Авторизация
    public function CheckReqAuth($login, $password){
         // проверка пустоты login
        if(empty($login)){
            $error['login'] = true;
        }
         // проверка пустоты password
        if(empty($password)){
            $error['password'] = true;
        }
        return  $error;
    }
    public function CheckDannAuth($login, $password){
         // проверка Логин + пароль
        return $this::find()->select(['token', 'nik']) 
            ->where(['login' => $login])
            ->andwhere(['password' => $password])
            ->all();  
    }


    public function CheckGetEmail($email) {
        return $this::find()->where(["email" => $email])->all();
    }
    public function CheckGetLogin($login) {
        return $this::find()->where(["login" => $login])->all();
    }
    public function CheckGetNik($nik) {
        return $this::find()->where(["nik" => $nik])->all();
    }
    public function CreateUser($dann,$token) {
        $this->login = $dann["login"];
        $this->email = $dann["email"];
        $this->password = $dann["password"];
        $this->nik = $dann["nik"];
        $this->token = $token;
        $this->save();
    }
 


    
    // Регистрация
    public function checkLogin($login) {
        // Проверка пустоты
        if(empty($login)){ 
            return [
                "req" => true 
            ];
       } else{ // поле не пустое
            //  Min символов
           if(strlen($login) < 6){
               $error["min"] =  true; 
           }
           //  MAX символов
           if(strlen($login) > 32){
                $error["max"] =  true; 
            }
            if(!preg_match ("/^\w+$/i" ,$login)){
                $error["regul"] = true;
            }
            // Ошибки есть
            if(!empty($error)){
                return $error;
            }else if(!empty($this->CheckGetLogin($login))){ // проверка уникальности
                return ["zan" => true];
            }
       }
    }
    public function checkNik($nik) {
        // Проверка пустоты
        if(empty($nik)){
            return [
                "req" => true 
            ];
       } else{// поле не пустое
            //  Min символов
            if(strlen($nik) < 8){
                $error["min"] =  true;
            }
            //  MAX символов
            if(strlen($nik) > 32){
                $error["max"] =  true;
            }
            setlocale(LC_ALL, "ru_RU.UTF-8");
            if(!preg_match ("/^[a-zA-Z0-9а-яА-Я]+$/u" , $nik)){
                $error["regul"] = true;
            }
            // Ошибки есть
            if(!empty($error)){
                return $error;
            }else if(!empty($this->CheckGetNik($nik))){ // проверка уникальности
                return ["zan" => true];
            }
       }
    }
    public function checkEmail($email) {
        if(empty($email)){
            return [
                "req" => true 
            ];
       } else{
           if(!preg_match ("/.+@.+\..+/i" , $email)){
                return ["regul"=> true];
           }else if(!empty($this->CheckGetEmail($email))){ // проверка уникальности
                return ["zan" => true];
            }

       }
    }
    public function checkPassword($password,$password_2) {
        // Проверка пустоты
        if(empty($password)){
            return [
                "req" => true 
            ];
       } else{// поле не пустое
            //  Min символов
           if(strlen($password) < 8){
                $error["min"] =  true;
           }
           //  MAX символов
           if(strlen($password) > 32){
                $error["max"] =  true; 
            }
            if(!preg_match ("/^\w+$/i" ,$password)){
                $error["regul"] = true;
            }
            // Ошибки есть
            if(!empty($error)){
                return $error;
            }else{// Ошибок нет
                if($password != $password_2){ // пароли совпадают
                    return ["password2" => true];
                }
            }
       }
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comment::className(), ['id_user' => 'id']);
    }
}
