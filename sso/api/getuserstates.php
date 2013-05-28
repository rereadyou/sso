<?php 

use sso\core\module as sm;
use sso\core\config as sc;
use sso\core\common\functions as sf;
use sso\core\common as cc;

$uid = sf\getGPC('uid', 'g');
//no user id supplied;
$retcode = 16;
$infoArr = array();

if($uid)
{
	//illegal user id
	$retcode = 15;
	
    $sso = cc\Allyes_SSO::sso();
    $sso->enable_memcached();
    $mc = $sso->mc;

    $retcode = 11; //unlogined

    $user = unserialize($mc->get($uid));
    $sessid = sc\Config::$sessNamePrefix.$user->sessid;

	if($uinfo = $mc->get($sessid))
	{
		$retcode = 17;
		if($info = $sso->get_mem_session_in_array($uinfo))
		{
			$infoArr = $info;
			$retcode = 5; //logined
		}
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
exit;

error_reporting(E_ALL);
highlight_string('<%php echo time(); %>');
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



//var_dump(getenv('OPENSSL_CONF'));

//generate a pair of private and public key
$res = openssl_pkey_new($conf);
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

$code = <<<FINE
2250cf072b0d4e657ba1fd5865bbe85aacdbfefb6975b349f1ee4293577068306fe750c5e8277915816da7ad7f6f5e63431fec70b92c9d5060faca4d6247f197a8723c8c47da1a2273fdc9986bc942a3430dba966bc461accb44ae1b736084a2985f76fb8638f5181d1b1cb5c6638e89ac4c5dd232ada73ac254e74acf21d617
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

echo '</br>', 'js encrypt: ', '</br>';
echo $code;

echo '</br> oooooooooooooo</br>';
$kh = openssl_pkey_get_private($ikey);
$pk = openssl_pkey_get_details($kh);
echo '<pre>';
print_r($pk['rsa']);
echo strtoupper(bin2hex($pk['rsa']['n']));
echo '</br>';
echo strtoupper(bin2hex($pk['rsa']['e']));
echo '</br>';
echo strtoupper(bin2hex($pk['rsa']['d']));
echo '</pre>';
echo '</br> oooooooooooooo</br>';
//openssl_public_encrypt($msg, $pcode, $pubkey);
//echo '</br>', 'public encrypt: ', '</br>';
//echo $pcode;
$code = pack('H*', $code);
openssl_private_decrypt(($code), $dmsg, $prikey);
echo '</br>', 'private decrypt: ', '</br>';
echo $dmsg;

?>
