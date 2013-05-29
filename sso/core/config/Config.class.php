<?php
/**
 * SSO config file
 *
 * Version: 0.1	   Date: 2013/4/1
 * Author: zhangbo
 */
namespace sso\core\config;

//use sso\core\common as cm;

//require '../common/Log.php';

class Config //extends \sso\core\common\Singleton
{
	public static $LogPath 				= 'core/logs/';	
	public static $SSOURL 				= 'www.sso.com';
	public static $ssoticketName        = 'allyes_ticket';
	public static $ticketLifeSpan 		= 0;
	public static $cookTicketLifeSpan 	= 0;
	public static $cookSessName 		= 'allyes_sso';
    public static $ssohomepage          = 'home/index';
	
    //5*60s session life span
	public static $sessGcMaxlifetime 	= 180; 
	
	public static $sessGcProbability 	= 100;
	public static $sessGcDivisor 		= 1;
	
	//keep dummy client alive for certain seconds
	public static $keepDummyClientAliveTime = 3600; 
	
	
	public static $memcachedSessionEnabled 	= false;
	public static $sessSaveHandler 		= 'memcached';
	public static $memServerAdd 		= '127.0.0.1:11211';
	//public static $memServerIP 			= '127.0.0.1';
	//public static $memPort 				= '11211';

    public static $memcServers = array(
                                    'localhost' => 11211
    );

    
    public static $smartyTemplateDir = 'public/tpl';
    public static $smartyCompileDir = 'public/tpl_c';
    public static $smartyConfigDir = 'public/configs';
    public static $smartyCacheDir = 'public/cache';
    public static $smartyDebugging = true;


    //note that memcacheD session will be prefixed with 
    //memc.sess.key.{session_id}
    public static $sessNamePrefix        = 'memc.sess.key.';
	
	
    public static $ssoRSAPrivateKey     = <<<FINE
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

    public static $ssoRSAPublicKey      = <<<FINE
-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCxM0wofjINugZtT4fdIiGPR7Np
yBhkkMeykURWsn/vJgjgmwLmKubdLGVcURYAe3vGxL0XRUNIw0zbDsH74vRAev4/
zCNfP54MUK0UOYTVuAURKG10abIkM6dU/ClyfhFEJgC3KdCisVQHkD6XbFswAV5E
UcoUw42wr4Y91wJFJwIDAQAB
-----END PUBLIC KEY-----
FINE;

    public static $ssoRSAPublicKeyN     = <<<FINE
B1334C287E320DBA066D4F87DD22218F47B369C8186490C7B2914456B27FEF2608E09B02E62AE6DD2C655C5116007B7BC6C4BD17454348C34CDB0EC1FBE2F4407AFE3FCC235F3F9E0C50AD143984D5B80511286D7469B22433A754FC29727E11442600B729D0A2B15407903E976C5B30015E4451CA14C38DB0AF863DD7024527
FINE;

    public static $ssoRSAPublicKeyD     = <<<FINE
7082EDBC584457CAE201CE19AA22DDC427F69717E1C518D8735D21531B0B784162299AC3007A051431F839E19262C40517741BCC06C26E14F35BB3C71FB43EF91391231F036B9453A26434293E57F1A79B59558FF798B6AB2DE3E7749E9C2E16B999F7FE08EB30A080B1272635F8AE1223A8FCF1181FFA408BE0B651513ED101
FINE;

    public static $ssoRSAPublicKeyE     = '010001';
    //deprecated
    public static $ssoRSAConf        = array (
                                            'digest_alg' => 'sha1',
                                            'x509_extensions' => 'v3_ca',
                                            'req_extensions'   => 'v3_req',
                                            'private_key_bits' => 1024,
                                            'private_key_type' => OPENSSL_KEYTYPE_RSA,
                                            'encrypt_key' 		=> false,	
                                            'encrypt_key_cipher' => OPENSSL_CIPHER_DES,	
                                    );

	//app domain and login page and home page
    /*
	public static $apps = array(
							'http://www.domain1.com'=>array('login'=>'login.php', 'home'=>'app.php'),
							'http://www.domain2.com'=>array('login'=>'login.php', 'home'=>'app.php'),
						);
	
	public static $clientDomains = array(
										"www.domain1.com",
										"www.domain2.com",
									);
	
	//public static $services = array(
									//"domain1",
									//"domain2",
								//);
     */

    public static $dbDSN = 'mysqli://root:rereadyou224011@127.0.0.1:3306/sso';


	public function __construct()
	{
		date_default_timezone_set('Asia/Shanghai');
	}
	
	/**
	 * set_session_feature
	 * 
	 * Default session features
	 */
	public static function set_session_feature()
	{
		ini_set("session.name", self::$cookSessName);
		ini_set("session.cookie_lifetime", self::$cookTicketLifeSpan);
		ini_set("session.gc_maxlifetime", self::$sessGcMaxlifetime);
		ini_set("session.gc_probability", self::$sessGcProbability);
		ini_set("session.gc_divisor", self::$sessGcDivisor);
	}
	
	/**
	 * set_file_session_handler
	 * 
	 * File session feature
	 */
	public static function set_file_session_handler()
	{
		self::set_session_feature();
	}
	
	/**
	 * set_memcache_session_handler
	 * 
	 * Memcache session feature
	 */
	public static function enable_memcached_session_handler()
	{
		try 
		{
			self::set_session_feature();
			ini_set("session.save_handler", self::$sessSaveHandler);
			ini_set("session.save_path", self::$memServerAdd);
			self::$memcachedSessionEnabled = true;
            self::$sessNamePrefix = 'memc.sess.key.';

            //cm\Log::access('Memcached enabled.'); 
		} 
		catch (\Exception $e) //Exception is in Global namespace
		{
            //cm\Log::error($e->getMessage());

			exit;
		}
	}
}



?>
