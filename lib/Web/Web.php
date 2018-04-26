<?php
abstract class Web {
	/**
	 * Check if a website is online
	 *
	 * @param      string  $site   Website adress
	 *
	 * @return     bool  online or not
	 */
	static function siteStatus($site)
	{
		$fp = @fsockopen($site, 80, $errno, $errstr, 1);

		return $fp;
	}

	/**
	 * Check if a server is online
	 *
	 * @param      <type>  $server  server adress
	 * @param      <type>  $port    server port
	 *
	 * @return     bool    online or not
	 */
	static function serverStatus($server,$port)
	{
		$fp = @fsockopen($server,$port, $errno, $errstr, 1);

		$online=($fp >= 1)?true:false;
		
		return $online;
	}

	/**
	 * Gets average ping
	 *
	 * @return     integer  average ping
	 */
	static function averagePing() {
		$hosts = array('google.com', 'wikipedia.org','twitter.com');
		
		$pings = array();
		$aping = 0;
		$i = 0;

		foreach ($hosts as $host) {
	   		exec('ping -qc 1 '.$host, $ping);
	   		$exploded = explode("=",$ping[3]);
	   		$exploded = explode("/",$exploded[1]);
			$aping=$aping+intval($exploded[1]);
			$i++;
		}	
		$aping = ceil($aping/$i);
		
		return $aping;
	}

	/**
	 * Get local and wan ip
	 *
	 * @return     array  data
	 */
	static function ipConfig()
	{
		$ipa = array();
	    $ipa['lan']= $_SERVER['SERVER_ADDR'];
	    $ipa['wan']= exec('curl http://ipecho.net/plain; echo');
		
		return $ipa;
	}

	/**
	 * Gets the client ip.
	 *
	 * @return     string  The client ip.
	 */
	static function get_client_ip() {
		$ipaddress = '';
	    if (getenv('HTTP_CLIENT_IP'))
	        $ipaddress = getenv('HTTP_CLIENT_IP');
	    else if(getenv('HTTP_X_FORWARDED_FOR'))
	        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
	    else if(getenv('HTTP_X_FORWARDED'))
	        $ipaddress = getenv('HTTP_X_FORWARDED');
	    else if(getenv('HTTP_FORWARDED_FOR'))
	        $ipaddress = getenv('HTTP_FORWARDED_FOR');
	    else if(getenv('HTTP_FORWARDED'))
	       $ipaddress = getenv('HTTP_FORWARDED');
	    else if(getenv('REMOTE_ADDR'))
	        $ipaddress = getenv('REMOTE_ADDR');
	    else
	        $ipaddress = 'UNKNOWN';
	    return $ipaddress;
	}
}