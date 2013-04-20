<?php 

use sso\core\module as sm;
use sso\core\config as sc;
use sso\core\common\functions as sf;
use sso\core\common as cc;

require '../core/config/Config.php';
require '../core/common/functions.php';
require '../core/module/User.php';
require '../core/common/sso.php';


$uid = sf\getGPC('uid', 'g');

//no user id supplied;
$retcode = 16;

$infoArr = array();

if($uid)
{
	//illegal user id
	$retcode = 15;
	
	$user = new sm\User($uid);

	
	if($uinfo = $user->get_user_login_status())
	{
		
		$sso = cc\Allyes_SSO::sso();
		
		
		
		//echo $uinfo, '</br>';
		$retcode = 17;
		if($info = $sso->get_mem_session_in_array($uinfo))
		{
			$infoArr = $info;
			$retcode = 5; //logined
		}
	}
	else 
	{
		$retcode = 11; //Unlogined
		//echo 'no such user exists!';
	}

}

$ret = array();
$ret['retcode'] = $retcode;
$ret['reason'] = sf\get_retcode($retcode);

if(count($infoArr))
{
	$ret = array_merge($ret, $infoArr);
}


$ret = json_encode($ret);

if($callback = sf\getGPC('callback', 'g'))
{
	$ret = $callback.'('.$ret.')';
}

echo $ret; 

//openssl rsa conf
$conf = array (
		'digest_alg' => 'sha1',
		'x509_extensions' => 'v3_ca',
		'req_extensions'   => 'v3_req',
		'private_key_bits' => 1024,
		'private_key_type' => OPENSSL_KEYTYPE_RSA,
		'encrypt_key' 		=> false,	
		'encrypt_key_cipher' => OPENSSL_CIPHER_DES,	
		'config' 		   => 'f:\php\sso\core\common\openssl.cnf'
);

/*

//var_dump(getenv('OPENSSL_CONF'));

//generate a pair of private and public key
// $res = openssl_pkey_new($conf);
//echo getcwd();


//  while($msg = openssl_error_string())
//  	echo "</br>\n", $msg;

// openssl_pkey_export($res, $prikey);

// echo '<pre>';
// echo $prikey;
// echo '</pre>';
$filePubkey = '../core/common/ssopublic.key';
$filePrikey = '../core/common/ssoprivate.key';


$ikey = <<<FINE
-----BEGIN RSA PRIVATE KEY-----
MIICXQIBAAKBgQCxM0wofjINugZtT4fdIiGPR7NpyBhkkMeykURWsn/vJgjgmwLm
KubdLGVcURYAe3vGxL0XRUNIw0zbDsH74vRAev4/zCNfP54MUK0UOYTVuAURKG10
abIkM6dU/ClyfhFEJgC3KdCisVQHkD6XbFswAV5EUcoUw42wr4Y91wJFJwIDAQAB
AoGAcILtvFhEV8riAc4ZqiLdxCf2lxfhxRjYc10hUxsLeEFiKZrDAHoFFDH4OeGS
YsQFF3QbzAbCbhTzW7PHH7Q++RORIx8Da5RTomQ0KT5X8aebWVWP95i2qy3j53Se
nC4WuZn3/gjrMKCAsScmNfiuEiOo/PEYH/pAi+C2UVE+0QECQQDaVqZQyuf1lJn+
uTPAKbYQsA57BC5YRAbMY7P9xw9GyPm9SZ7AggDJK57JS73NuYjK9OEdB7bUwGaQ
CMcfaDkhAkEAz8QUU1Ed44xYjmZFmUP96x8pZ7y2mPu2Kw2nleKEmudJZVWGAtSq
DdmBQ2vXlYBj2Vk3V2BlDfo2DzwwqPvNRwJBAJnUfu1haDhwcGc1WS7EtI/hWvJ1
Kanqk9ehT4k3nJ3Er6AbpIwBjEgwQXmVsa6mT6ifC5QGgVw4CorkhFauecECQF3J
laNGqsddQ1yErkZRev34Qyig94x3k/Q7ZbszVcjXPzqPML92YI1/8eoNdHP4URp8
O48l5Hg6ysx1z0F46WECQQDK9sWe770JA+Dob6ejyVEpRi5hYYMhIG8GwFUhMBW+
ewZeY+tHByjB4tDgh1Wx3AaShyj8iOIdkba8sx5HwV29
-----END RSA PRIVATE KEY-----
FINE;


$ukey = <<<FINE
-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCxM0wofjINugZtT4fdIiGPR7Np
yBhkkMeykURWsn/vJgjgmwLmKubdLGVcURYAe3vGxL0XRUNIw0zbDsH74vRAev4/
zCNfP54MUK0UOYTVuAURKG10abIkM6dU/ClyfhFEJgC3KdCisVQHkD6XbFswAV5E
UcoUw42wr4Y91wJFJwIDAQAB
-----END PUBLIC KEY-----
FINE;

// $prikey = openssl_pkey_get_private(file_get_contents($filePrikey));
// $pubkey = openssl_pkey_get_public(file_get_contents($filePubkey));

$prikey = openssl_pkey_get_private($ikey);
$pubkey = openssl_pkey_get_public($ukey);


$msg = 'form sso msg';

openssl_private_encrypt($msg, $code, $prikey);
// echo 'after private encode: '.$code, '</br>';
// openssl_public_decrypt($code, $dmsg, $pubkey);
// echo 'after public decode: '.$dmsg;



// Get public key

// 	$pubkey=openssl_pkey_get_details($res);

//$pubkey=$pubkey["key"];

*/
exit;





?>