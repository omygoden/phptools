<?php
class PhpTools{
    //rsa加密
    public function rsaEncrype($data,$public_key){
        $crypto = '';
        foreach (str_split($data, 117) as $chunk) {
            openssl_public_encrypt($chunk, $encryptData, $public_key);
            $crypto .= $encryptData;
        }
        $encrypted = $this->urlsafe_b64encode($crypto);

        return $encrypted;
    }

    /**
     * URL base64编码
     * @param $string
     * @return mixed|string
     */
    function urlsafe_b64encode($string)
    {
        $data = base64_encode($string);
        $data = str_replace(array('+', '/', '='), array('-', '_', ''), $data);
        return $data;
    }

    /**
     * URL base64解码
     * @param $string
     * @return bool|string
     */
    function urlsafe_b64decode($string)
    {
        $data = str_replace(array('-', '_'), array('+', '/'), $string);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        return base64_decode($data);
    }

    //rsa解密
    public function rsaDecrype($encData,$private_key){
        $crypto = '';
        foreach (str_split($this->urlsafe_b64decode($data), 128) as $chunk) {
            openssl_private_decrypt($chunk, $decryptData, $private_key);
            $crypto .= $decryptData;
        }

        return $crypto;
    }

    //格式化公钥
    public function formatPublicKey($public_key){
        $fKey = "-----BEGIN PUBLIC KEY-----\n";
        $len = strlen($public_key);
        for ($i = 0; $i < $len;) {
            $fKey = $fKey . substr($public_key, $i, 64) . "\n";
            $i += 64;
        }
        $fKey .= "-----END PUBLIC KEY-----";
        return $fKey;
    }

    //格式化私钥
    public function formatPrivateKey($private_key){
        $fKey = "-----BEGIN PRIVATE KEY-----\n";
        $len = strlen($private_key);
        for ($i = 0; $i < $len;) {
            $fKey = $fKey . substr($private_key, $i, 64) . "\n";
            $i += 64;
        }
        $fKey .= "-----END PRIVATE KEY-----";
        return $fKey;
    }
}
>
