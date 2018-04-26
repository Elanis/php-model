<?php
/**
 * Get the current full URL
 *
 * @param      <type>   $s                   { parameter_description }
 * @param      boolean  $use_forwarded_host  The use forwarded host
 *
 * @return     string   ( description_of_the_return_value )
 */
function full_url( $s, $use_forwarded_host = false )
{
    $ssl      = ( ! empty( $s['HTTPS'] ) && $s['HTTPS'] == 'on' );
    $sp       = strtolower( $s['SERVER_PROTOCOL'] );
    $protocol = substr( $sp, 0, strpos( $sp, '/' ) ) . ( ( $ssl ) ? 's' : '' );
    $port     = $s['SERVER_PORT'];
    $port     = ( ( ! $ssl && $port=='80' ) || ( $ssl && $port=='443' ) ) ? '' : ':'.$port;
    $host     = ( $use_forwarded_host && isset( $s['HTTP_X_FORWARDED_HOST'] ) ) ? $s['HTTP_X_FORWARDED_HOST'] : ( isset( $s['HTTP_HOST'] ) ? $s['HTTP_HOST'] : null );
    $host     = isset( $host ) ? $host : $s['SERVER_NAME'] . $port;
    return $protocol . '://' . $host . $s['REQUEST_URI'];
}

abstract class Stats extends MongoInterface {
    static function newVisit() {
        if(!isset($_SESSION['visited']) || $_SESSION['visited']!=$_SERVER['REQUEST_URI']) {
            $manager = new MongoDB\Driver\Manager("***REMOVED***");

            $visit = [];
            if(strstr(strtolower($_SERVER['HTTP_USER_AGENT']), "googlebot"))
            {
                $visit["user-agent"] = "Google Bot";
            } elseif(strstr(strtolower($_SERVER['HTTP_USER_AGENT']), "baidu")) {
                $visit["user-agent"] = "Baidu Bot";
            } elseif(substr($_SERVER['HTTP_USER_AGENT'],0,31)=="Mozilla/5.0 (compatible; Yandex") {
                $visit["user-agent"] = "Yandex Bot";
            } else {
                $visit['user-agent'] = $_SERVER['HTTP_USER_AGENT'];
            }
            
            $visit["ip"] = Web::get_client_ip();
            $visit["url"] = full_url($_SERVER);
            $visit["origin"] = (isset($_SERVER['HTTP_REFERER']))?$_SERVER['HTTP_REFERER']:null;
            $visit["time"] = time();

            $bulkVisit = new MongoDB\Driver\BulkWrite(['ordered' => true]);
            $bulkVisit->insert($visit);

            $manager->executeBulkWrite('stats.visits', $bulkVisit);

            $_SESSION['visited'] = $_SERVER['REQUEST_URI'];
        }
    }
}