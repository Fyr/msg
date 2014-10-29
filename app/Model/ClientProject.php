<?php
/**
Подключение:
require_once('/scripts/project/frontend/modules/client/components/ClientProject.php');

Пример вызова:
$arr_user = ClientProject::getUserAuthData();

если массив пустой - значит не авторизован
иначе массив с ключами
 - user_id (id юзера в базе)
 - email
 - name
**/
App::uses('AppModel', 'Model');
class ClientProject extends AppModel {
    public $useTable = false;
    
    private static $encrypt_key = 'key_for_the_konstruktor_application_sessions';
    private static $cookie_key_name = 'konstr_user_data';
    
    
    public static function getUserAuthData()
    {
        if (isset($_COOKIE[self::$cookie_key_name]) && strlen($_COOKIE[self::$cookie_key_name])) {
            
            return self::decryptDataArray($_COOKIE[self::$cookie_key_name]);
            
        }
        return array();
    }
    
    
    public static function afterLoginSession($params)
    {
        $data = array(
                    'user_id'=>Yii::app()->user->getId(),
                    'email'=>Yii::app()->user->getModel()->username,
                    'name'=>Yii::app()->user->getModel()->userData->full_name
                      );
        
        $val = self::encryptDataArray($data);
        Yii::app()->request->cookies[self::$cookie_key_name] = new CHttpCookie(self::$cookie_key_name, $val);
        
        
    }
    
    public static function beforeLogOutSession($params)
    {
        
        Yii::app()->request->cookies[self::$cookie_key_name] = new CHttpCookie(self::$cookie_key_name, self::encryptDataArray(array()));
    }
    
    
    
    public static function encryptDataArray($arr)
    {
        return serialize($arr);
        return self::mc_encrypt($arr);
    }
    
    
    public static function decryptDataArray($string)
    {
        if (!$string) {
            return array();
        }
        return @unserialize($string);
        return self::mc_decrypt($string);
    }
    
    
    public static function getEncryptKey()
    {
        return md5(self::$encrypt_key);
    }
    
    
    
    public static function mc_encrypt($encrypt){
        $key = self::getEncryptKey();
        $encrypt = serialize($encrypt);
        $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC), MCRYPT_DEV_URANDOM);
        $key = pack('H*', $key);
        $mac = hash_hmac('sha256', $encrypt, substr(bin2hex($key), -32));
        $passcrypt = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $encrypt.$mac, MCRYPT_MODE_CBC, $iv);
        $encoded = base64_encode($passcrypt).'|'.base64_encode($iv);
        return $encoded;
    }
     

    public static function mc_decrypt($decrypt){
        $key = self::getEncryptKey();
        $decrypt = explode('|', $decrypt.'|');
        $decoded = base64_decode($decrypt[0]);
        $iv = base64_decode($decrypt[1]);
        if(strlen($iv)!==mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC)){ return false; }
        $key = pack('H*', $key);
        $decrypted = trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $decoded, MCRYPT_MODE_CBC, $iv));
        $mac = substr($decrypted, -64);
        $decrypted = substr($decrypted, 0, -64);
        $calcmac = hash_hmac('sha256', $decrypted, substr(bin2hex($key), -32));
        if($calcmac!==$mac){ return false; }
        $decrypted = unserialize($decrypted);
        return $decrypted;
    }
    
}