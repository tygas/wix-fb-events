<?php
define("LG_ROOT", dirname(__FILE__));
define('DEV_EMAIL', 'lg@mailinator.com');

//include_once( 'strings.php');

use Goutte\Client;

if (lH())
    define('LG', dirname(dirname(__FILE__)));
else
    define('LG', (dirname(__FILE__)));


@date_default_timezone_set('Europe/Vilnius');    // Version with E_ALL 1.4 AGENT

if (isset($_GET['cl']) OR lH() OR @$_SERVER['HTTP_USER_AGENT'] == 'Opera/9.80 (Windows NT 6.1; Win64; x64; U; Edition Next; en) Presto/2.10.289 Version/12.00'
) {           // CL gives you cooki[lg] and enabled logging only for guys with this cookie set | commet COOKIE_SET to go back to default
    setcookie("lg", @$_SERVER['REMOTE_ADDR'] . ' ' . date('H:i:s'), time() + 3600);
    define('COOKIE_SET', @$_SERVER['REMOTE_ADDR']);
} elseif (!defined('LG'))
    define('LG', $_SERVER['REMOTE_ADDR']);
//$_SERVER['HTTP_USER_AGENT']
//~ setcookie("lg", $value, time()+3600, "", ".krizeine.lt");



if (!function_exists('lg')) {

    function lp($cookie = false) {
        $ip = getenv('REMOTE_ADDR');
        $bReturn = false;

        if (isset($_COOKIE['lg'])) {
            return 2;
        } elseif (isset($_GET['lg']))
            return 3;
        elseif (defined('COOKIE_SET') OR !lH()) {
            return 0;
        }

        $aMyIp = array(
            '88.222.36.247', //Meganet    
        );


        foreach ($aMyIp as $sIp) {

            if ($sIp == $ip) {
                return true;
            }
        }
        return false;
    }

    function lg($var, $die = '', $stop = false, $sColor = '#003366') {

        if (0)
            tr('__FILE__');


        if (lp() || $die == 'a') {

            $str = array();
            $str [] = "<pre>";
            if (!is_string($die))
                $stop = true;


            if (isset($var))
                switch ($die) {
                    case 22 :
                        $str [] = (string) var_dump($vars);
                        break;
                    case 3:
                        $str [] = print_r($var, 1);
                        $str [] = '<small>' . __FILE__ . __LINE__ . '</small>';
                        break;

                    case 5:
                    case 'x': echo '<xmp>' . $var . '</xmp>';
                        break;
                    case 77: echo '<hr-------------------------/>' . PHP_EOL . $var . PHP_EOL . '<hr -------------------------/>';
                        break;

                    case 9: if (isset($_GET['g']))
                            $str [] = print_r($var, 1);
                        break;

                    case 'a': echo $stop . '<a class="lg" href=' . $var . ' >' . $var . '</a>';
                        $die = '';
                        break;

                    default :
                        $str [] = print_r($var, 1);
                        break;
                        $str [] = __FILE__;
                        $str [] = "</pre>";
                }

            whyout(implode("\n", $str), $die, $sColor);

            if (is_int($stop) OR is_int($die))
                die();
        }
    }

    function lgSet($var) {
        $_SESSION['lg'] = array();
        $_SESSION['lg'][] = $var;
    }

//~ if ( floatval(phpversion()) > 5)


    function fix_code($buffer) {
        return (preg_replace("!\s*/>!", ">", $buffer));
    }

    function lgCharset($sCharset = 'utf-8', $mime = "text/html") {


        if (stristr($_SERVER["HTTP_ACCEPT"], "application/xhtml+xml")) {
            if (preg_match("/application\/xhtml\+xml;q=([01]|0\.\d{1,3}|1\.0)/i", $_SERVER["HTTP_ACCEPT"], $matches)) {
                $xhtml_q = $matches[1];
                if (preg_match("/text\/html;q=q=([01]|0\.\d{1,3}|1\.0)/i", $_SERVER["HTTP_ACCEPT"], $matches)) {
                    $html_q = $matches[1];
                    if ((float) $xhtml_q >= (float) $html_q) {
                        $mime = "application/xhtml+xml";
                    }
                }
            } else {
                $mime = "application/xhtml+xml";
            }
        }
        if ($mime == "application/xhtml+xml") {
            $prolog_type = "<?xml version=\"1.0\" encoding=\"$sCharset\" ?>\n<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\" lang=\"en\">\n";
        } else {
            ob_start("fix_code");
            $prolog_type = "<!DOCTYPE html PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/loose.dtd\">\n<html lang=\"en\">\n";
        }
        header("Content-Type: $mime;charset=$sCharset");
        header("Vary: Accept");
        print $prolog_type;
    }

    function lgMail($mail_body, $email = DEV_EMAIL) {
        $Name = $_SERVER['HTTP_HOST']; //senders name
        $recipient = "web@hire.lt"; //recipient
        $subject = "Iphone atrisimas http://iphone.hire.lt " . $_SERVER['HTTP_REFFERER']; //subject
        $header = "From: " . $Name . " <" . $email . ">\r\n"; //optional headerfields
        ini_set('sendmail_from', DEV_EMAIL); //Suggested by "Some Guy"
        mail($recipient, $subject, $mail_body, $header); //mail command :)
    }

    function lgLink($sUrl, $sTitle, $sStyle = '', $iBlank = '') {
        if ($iBlank)
            $iBlank = "onclick=\"window.open(this.href);'\"";

        $sR = <<<EOT
	<a title="$sTitle"  href="$sUrl" $sStyle $iBlank>$sTitle</a>
EOT;
        echo $sR;
    }

    function whyout($data, $title = '', $sColor = '#003366') {
        $str = array();
        $str [] = "<div class='lg'>";

        if ($title) {
            $str [] = "<div style='color:   white;background-color:  $sColor;	padding:1px;	margin:0px;	font-weight:bold;'>";
            $str [] = $title;
            $str [] = "</div>";
        }
        $str [] = $data;
        $str [] = "</div>";

        echo implode("", $str);
    }

    function print_var($var) {
        if (is_string($var))
            return ('"' . str_replace(array("\x00", "\x0a", "\x0d", "\x1a", "\x09"), array('\0', '\n', '\r', '\Z', '\t'), $var) . '"');
        else if (is_bool($var)) {
            if ($var)
                return ('true');
            else
                return ('false');
        } else if (is_array($var)) {
            $result = 'array( ';
            $comma = '';
            foreach ($var as $key => $val) {
                $result .= $comma . print_var($key) . ' => ' . print_var($val);
                $comma = ', ';
            }
            $result .= ' )';
            return ($result);
        }

        return (var_export($var, true)); // anything else, just let php try to print it
    }

    function lgTr($msg, $die = '') {
        echo "<pre><div style='background-color:#E6E6E6; color:black;font-size:11px; font-family:Arial;	padding:1px; margin: -2px;	font-weight:normal;	text-align:left'>";
//	var_export( debug_backtrace() ); echo "</pre>\n"; return;    // this line shows what is going on underneath


        $trace = array_reverse(debug_backtrace());
        $indent = '';
        $func = '';

        print_r($msg) . "\n";

        foreach ($trace as $val) {
            if (isset($val ['file']))
                echo $indent . $val ['file'] . ' on ' . $val ['line'];

            if ($func)
                echo ' in function ' . $func;

            if ($val ['function'] == 'include' || $val ['function'] == 'require' || $val ['function'] == 'include_once' || $val ['function'] == 'require_once')
                $func = '';
            else {
                $func = $val ['function'] . '(';

                if (isset($val ['args'] [0])) {
                    $func .= ' ';
                    $comma = '';
                    foreach ($val ['args'] as $val) {
                        $func .= $comma . print_var($val);
                        $comma = ', ';
                    }
                    $func .= ' ';
                }

                $func .= ')';
            }

            echo "\n";

            $indent .= "\t";
        }

        echo "</div></pre>\n";

        if ($die)
            die();
    }

    function _findStingPathInArray($searching_string, $data, $mypath = '$', $seperator = "\n", $rez = array(), $isObject = 0) {
        foreach ($data as $tmp_path => $val) {
            if (!in_array($tmp_path, array('GLOBALS', '_ENV', '_POST', '_GET', '_COOKIE', '_SESSION', '_SERVER', '_FILES', 'HTTP_POST_FILES', 'HTTP_ENV_VARS', 'HTTP_POST_VARS', 'HTTP_GET_VARS', 'HTTP_COOKIE_VARS', 'HTTP_SESSION_VARS', 'HTTP_SERVER_VARS', 'HTTP_POST_VARS', '_REQUEST'))) {
                if ($val && !is_array($val) && !is_object($val) && ($val === $searching_string)) {
                    if ($isObject) {
                        $rez [] = $mypath . "['" . $tmp_path . "']" . $seperator;
                    } else {
                        $rez [] = $mypath . $tmp_path . $seperator;
                    }
                } //else echo " not int " . $path . $tmp_path . $seperator;
                elseif ($val && is_array($val)) {
                    if (substr($tmp_path, 0, 3) != '../') {
                        $rez = _findStingPathInArray($searching_string, $val, $mypath . "['" . $tmp_path . "']", $seperator, $rez);
                    }
                } elseif ($val && is_object($val)) {
                    if (substr($tmp_path, 0, 3) != '../') {
// jei ne rekursija
                        $rez = _findStingPathInArray($searching_string, $val, $mypath . $tmp_path . "->", $seperator, $rez, 1);
                    }
                }
            }
        }
        return $rez;
    }

    function findStingPath($searching_string, $data, $mypath = '^', $seperator = "\n") {
        print_r_(_findStingPathInArray($searching_string, $data, $mypath, $seperator, $rez = array()), "STRING PATH IN ARRAY");
    }

//flash

    function gUrl() {
        global $gUrl;
        $url = explode('/', $_SERVER['REQUEST_URI']);
        $sRef = '';

        foreach ($gUrl as $sU) {
            if ($sU != 'receptuknyga' OR $sU != 'test')
                $sRef .= $sU;
            return $gUrl;
        }
    }

    if (lp()) {
        error_reporting(E_ALL ^ E_NOTICE);
        //~ error_reporting(E_ALL);
        ini_set('display_errors', 'On');
    } else {
        error_reporting(0);
        ini_set('display_errors', 'Off');
    }
}

if (!class_exists('error_handler') AND 0) {

    class error_handler {
        /*
          show_user values:
          0 -> off
          1 -> on (default)

          show_developer values:
          0 -> off
          1 -> on (default)
          2 -> silent
          4 -> add context
          8 -> add backtrace
          16 -> font color white (red default)
          32 -> font color black (red default)
          add numbers together for more than one to be turned on e.g: add context + silent = 6
          matching ip address must be present for show_developer to be invoked
          add a valid email address or log file path to invoke these functions			[http://franktank.com/blog/]
         */

        //########################################################################
        //contructor for the class...
        function error_handler($ip = 0, $show_user = 1, $show_developer = 4, $email = NULL, $log_file = NULL) {
            $this->ip = $ip;
            $this->show_user = $show_user;
            $this->show_developer = $show_developer;
            $this->email = mysql_escape_string($email);
            $this->log_file = $log_file;
            $this->log_message = NULL;
            $this->email_sent = false;

            $this->error_codes = E_ERROR | E_CORE_ERROR | E_COMPILE_ERROR | E_USER_ERROR;
            $this->warning_codes = E_WARNING | E_CORE_WARNING | E_COMPILE_WARNING | E_USER_WARNING;

            //associate error codes with errno...
            $this->error_names = array('E_ERROR', 'E_WARNING', 'E_PARSE', 'E_NOTICE', 'E_CORE_ERROR', 'E_CORE_WARNING',
                'E_COMPILE_ERROR', 'E_COMPILE_WARNING', 'E_USER_ERROR', 'E_USER_WARNING',
                'E_USER_NOTICE', 'E_STRICT', 'E_RECOVERABLE_ERROR');

            for ($i = 0, $j = 1, $num = count($this->error_names); $i < $num; $i++, $j = $j * 2)
                $this->error_numbers[$j] = $this->error_names[$i];
        }

        //########################################################################
        //error handling function...
        function handler($errno, $errstr, $errfile, $errline, $errcontext) {
            $this->errno = $errno;
            $this->errstr = $errstr;
            $this->errfile = $errfile;
            $this->errline = $errline;
            $this->errcontext = $errcontext;

            if ($this->log_file)
                $this->log_error_msg();

            if ($this->email)
                $this->send_error_msg();

            if ($this->show_user)
                $this->error_msg_basic();

            if ($this->show_developer && preg_match("/^$this->ip$/i", $_SERVER['REMOTE_ADDR'])) //REMOTE_ADDR : HTTP_X_FORWARDED_FOR
                $this->error_msg_detailed();

            /* Don't execute PHP internal error handler */
            return true;
        }

        //########################################################################
        //error reporting functions...
        function error_msg_basic() {
            $message = NULL;
            if ($this->errno & $this->error_codes)
                $message .= "<b>ERROR:</b> There has been an error in the code.";
            if ($this->errno & $this->warning_codes)
                $message .= "<b>WARNING:</b> There has been an error in the code.";

            if ($message)
                $message .= ( $this->email_sent) ? " The developer has been notified.<br />\n" : "<br />\n";
            echo $message;
        }

        function error_msg_detailed() {
            //settings for error display...
            $silent = (2 & $this->show_developer) ? true : false;
            $context = (4 & $this->show_developer) ? true : false;
            $backtrace = (8 & $this->show_developer) ? true : false;

            switch (true) {
                case (16 & $this->show_developer): $color = 'white';
                    break;
                case (32 & $this->show_developer): $color = 'black';
                    break;
                default: $color = 'red';
            }

            $message = ($silent) ? "<!--\n" : '';
            $message .= "<pre style='color:$color;'>\n\n";
            $message .= "file: (" . print_r($this->errline, ture) . ')' . print_r($this->errfile, true) . "\n" .
                    print_r($this->error_numbers[$this->errno], true) .
                    $message .= "message: " . print_r($this->errstr, true) . "\n\n";
            $message .= ( $context) ? "context: " . print_r($this->errcontext, true) . "\n\n" : '';
            $message .= ( $backtrace) ? "backtrace: " . print_r(debug_backtrace(), true) . "\n\n" : '';
            $message .= "</pre>\n";
            $message .= ( $silent) ? "-->\n\n" : '';

            echo $message;
        }

        function send_error_msg() {
            $message = "file: " . print_r($this->errfile, true) . "\n";
            $message .= "line: " . print_r($this->errline, true) . "\n\n";
            $message .= "code: " . print_r($this->error_numbers[$this->errno], true) . "\n";
            $message .= "message: " . print_r($this->errstr, true) . "\n\n";
            $message .= "log: " . print_r($this->log_message, true) . "\n\n";
            $message .= "context: " . print_r($this->errcontext, true) . "\n\n";
            //$message .= "backtrace: ".print_r( $this->debug_backtrace(), true)."\n\n";

            $this->email_sent = false;
            if (
                    mail($this->email, 'Klaida: ' . $this->errcontext['SERVER_NAME'] . $this->errcontext['REQUEST_URI'], $message, "From: error@" . $this->errcontext['HTTP_HOST'] . "\r\n")
            )
                $this->email_sent = true;
        }

        function log_error_msg() {
            $message = "time: " . date("j M y - g:i:s A (T)", mktime()) . "\n";
            $message .= "file: " . print_r($this->errfile, true) . "\n";
            $message .= "line: " . print_r($this->errline, true) . "\n\n";
            $message .= "code: " . print_r($this->error_numbers[$this->errno], true) . "\n";
            $message .= "message: " . print_r($this->errstr, true) . "\n";
            $message .= "##################################################\n\n";

            if (!$fp = fopen($this->log_file, 'a+'))
                $this->log_message = "Could not open/create file: $this->log_file to log error.";
            $log_error = true;

            if (!fwrite($fp, $message))
                $this->log_message = "Could not log error to file: $this->log_file. Write Error.";
            $log_error = true;

            if (!$this->log_message)
                $this->log_message = "Error was logged to file: $this->log_file.";

            fclose($fp);
        }

    }

    function lAdsense($width, $height, $google_ad_client = "pub-7316295871993044", $slot = '8248645138') {

        if (!lH()) {
            ?>
            <script type="text/javascript">
                <!--

                google_ad_client = <?= $google_ad_client ?> ;
                /* 234x60, created 4/7/09 */
                google_ad_slot = <?= $slot ?>
                google_ad_width = <?= $width ?>;
                google_ad_height = <?= $height ?>;
                -->
            </script>
            <script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
            <?php
        } else {
            ?> <a style="width: <?= $width ?>px; height: <?= $height ?>px; line-height:  <?= $height ?>px; text-align:  center; background: #fff; display: block; color: #0000ff" href="http://google.com/adsense">Adsense goes here</a><?
        }
    }

    function file_lg($sUrl, $start = 350, $end = 0) {

//No response protection
        $ctx = stream_context_create(array(
            'http' => array(
                'timeout' => 1
            )
                )
        );
        lg(func_get_args(), 1);
        return file_get_contents($sUrl, 0, $ctx, $start, $end);
    }

}

if (!function_exists('tr')) {

    function tr($var) {
        lgTr($var);
    }

}
if (!function_exists('lgUrl')) {

    function lgUrl($sUrl = 0, $iFrom = 0) {
        if (!$sUrl)
            $sUrl = $_SERVER['REQUEST_URI'];
        $url = explode('/', $sUrl);

        if (lH()) {
            array_shift($url);
            array_shift($url);
        } else {
            array_shift($url);
        }

        if ($iFrom) {
            $aUrl = array();
            foreach ($url as $ii => $sVar) {
                $sLastVar = $sVar;

                if ($ii % 2)
                    $aUrl [$sLastVar] = $sVar;
            }
            return $aUrl;
        }

        return $url;
    }

}


if (isset($_GET['cook']))
    lg($_COOKIE);

function lgBrowser($agent = null) {
// Declare known browsers to look for
    $known = array('msie', 'firefox', 'safari', 'webkit', 'opera', 'netscape', 'konqueror', 'gecko');

// Clean up agent and build regex that matches phrases for known browsers
// (e.g. "Firefox/2.0" or "MSIE 6.0" (This only matches the major and minor
// version numbers.  E.g. "2.0.0.6" is parsed as simply "2.0"
    $agent = strtolower($agent ? $agent : $_SERVER['HTTP_USER_AGENT']);
    $pattern = '#(?<browser>' . join('|', $known) . ')[/ ]+(?<version>[0-9]+(?:\.[0-9]+)?)#';

// Find all phrases (or return empty array if none found)
    if (!preg_match_all($pattern, $agent, $matches))
        return array();

// Since some UAs have more than one phrase (e.g Firefox has a Gecko phrase,
// Opera 7,8 have a MSIE phrase), use the last one found (the right-most one
// in the UA).  That's usually the most correct.
    $i = count($matches['browser']) - 1;
    return array($matches['browser'][$i] => $matches['version'][$i]);
}

function aHref($sHref = '', $sTitle = '') {
    lg('<a href="' . $sHref . '">' . $sTitle ? $sTitle : $sHref . '</a>');
}

$log_file = dirname(__FILE__) . '/er';

//~ $handler = new error_handler('127.0.0.1', 1, 4, '', $log_file);
//~ set_error_handler(array(&$handler, "handler"));

function lH($path = 0) {
    $documentRoots = array(
        'C:/xampp/htdocs',
        'C:/Work/Projects'
    );

    if (in_array($_SERVER['DOCUMENT_ROOT'], $documentRoots)) {
        set_time_limit(11115);
        return $_SERVER['DOCUMENT_ROOT'];
    }
}

if (isset($_GET['cll'])) {
    lg_destroy_cookie();
    unset($_COOKIE['lg']);
}

function lg_destroy_cookie() {
    $sessionName = session_name();
    $sessionCookie = session_get_cookie_params();

    setcookie($sessionName, false, $sessionCookie['lifetime'], $sessionCookie['path'], $sessionCookie['domain'], $sessionCookie['secure']);
}

function g_url($sUrl, $sStart = 0, $aPost = array()) {

    $sUrl = trim($sUrl);
    include_once 'Uri.php';
    $sU = $sUrl = trim($sUrl);
    $sDir = LG . '/t/';
    $s64Url = Uri::get($sUrl);

//    lg($sDir . $s64Url . '.htm', 'a',1);

    if (file_exists($sDir . $s64Url . '.htm')) {
        if (defined('EE')) {
            lg($sDir . $s64Url . '.htm', 'a');
            lg($sUrl, 'a');
        }
        $sUrl = $sDir . $s64Url;
        $_SESSION['lasturl'] = $sUrl;

        return file_get_contents($sDir . $s64Url . '.htm', NULL, NULL, $sStart);
    } else {

        $aUrl = parse_url(trim($sUrl));


        if (isset($aUrl['host'])) {

            switch ($aUrl['host']) {
                case 'www.ruelala.com': $sSource = getRuela($sUrl);
                    break;
                case 'www.fab.com': $sSource = getFab($sUrl, $aPost);
                    break;
                case 'www.purecitizen.com':
                case 'purecitizen.com':
                    $sSource = getPure($sUrl);
                    break;
                case 'www.kembrel.com': $sSource = getKebrel($sUrl);
                    break;
                case 'www.editorscloset.com':
                case 'editorscloset.com':
                    $sSource = getEditor($sUrl);
                    break;
                case 'driftwagon.com':
                case 'www.driftwagon.com':
                    $sSource = getDrift($sUrl);
                    break;
                case 'reserve.refinery29.com': $sSource = getRefinery($sUrl);
                    break;
                case 'www.thesamplesale.com': $sSource = getThesample($sUrl);
                    break;
                case 'www.ideeli.com':
                    $sSource = getIdeeli($sUrl);
                    break;
                case 'www.enviius.com':
                    $sSource = getEnviius($sUrl);
                    break;
                case 'www.blitsy.com':
                case 'blitsy.com':
                    $sSource = getBlitsy($sUrl);
                    break;
                case 'www.gilt.com':
                case 'gilt.com':
                    $sSource = getGilt($sUrl);
                    break;
                case 'hire.lt':
                    $sSource = getHire($sUrl);
                    break;
                case 'www.myhabit.com':
                    $sSource = getMyhabit($sUrl);
                    break;
                case 'www.hautelook.com':
                    $sSource = getHaute($sUrl);
                    break;
                case 'www.beyondtherack.com':
                    $sSource = getBeon($sUrl);
                    break;
                case 'www.thepeacockparade.com':
                    $sSource = getThepea($sUrl);
                    break;
                case 'www.brandsexclusive.com.au':
                    $sSource = getBrand($sUrl);
                    break;
                case 'www.totsy.com':
                    $sSource = getTotsy($sUrl);
                    break;
                case 'www.onekingslane.com':
                    $sSource = getOneking($sUrl);
                    break;
                case 'www.secretsales.com':
                case 'secretsales.com':
                    $sSource = getSecret($sUrl);
                    break;
                case 'www.envite.com':
                    $sSource = getEnvite($sUrl);
                    break;
                case 'www.bagobsession.com':
                case 'bagobsession.com':
                    $sSource = getBango($sUrl);
                    break;
                case 'stylehopper.com':
                case 'www.stylehopper.com':
                    $sSource = getStyle($sUrl);
                    break;
                case 'www.ivorytrunk.com':
                    $sSource = getIvory($sUrl);
                    break;

                default:
                    $sSource = file_lg($sUrl);
                    break;
            }


            if (empty($sSource)) {
                lg($sUrl . ' tuscias sContent ' . $sU);
                return false;
            }

            if (lH())
                file_put_contents($sDir . $s64Url . '.htm', $sSource);

            $_SESSION['lasturl'] = $sUrl;

            return $sSource;
        }
    }
}

function curl($sUrl) {
    $options = array(
        CURLOPT_RETURNTRANSFER => true, // return web page
        CURLOPT_HEADER => false, // don't return headers
        CURLOPT_FOLLOWLOCATION => true, // follow redirects
        CURLOPT_ENCODING => "", // handle all encodings
        CURLOPT_USERAGENT => 'Opera/9.80 (Windows NT 6.1; WOW64; U; Edition Next; en) Presto/2.10.289 Version/12.00', // who am i
        CURLOPT_AUTOREFERER => true, // set referer on redirect
        CURLOPT_CONNECTTIMEOUT => 120, // timeout on connect
        CURLOPT_TIMEOUT => 120, // timeout on response
        CURLOPT_MAXREDIRS => 10, // stop after 10 redirects
        CURLOPT_COOKIEFILE => dirname(__FILE__) . '/tmp/symfony'
    );

    $ch = curl_init($sUrl);

    curl_setopt_array($ch, $options);
    $content = curl_exec($ch);
    $err = curl_errno($ch);
    $errmsg = curl_error($ch);
    $header = curl_getinfo($ch);
    curl_close($ch);
    return $content;
}

function ii($sUrl) {
    ?>
    <fieldset>
        <legend><?= $sUrl ?></legend>
        <iframe src="<?= $sUrl ?>" class="i100"></iframe>
    </fieldset>
    <?
}

function strGetFrom($string, $trimTo = '|') {
    $arr = explode($trimTo, $string);
    if (!isset($arr[1])) {
        lg($string, 'can not get color');
        return false;
    }
    return $arr[1];
}

//~ include 'strings.php';

function getEdition($sUrl) {
    try {

        $login_email = 'tygasz@gmail.com';
        $login_pass = 'asdasd';

        $ch = curl_init();
        if (!isset($_SESSION['edition'])) {

            curl_setopt($ch, CURLOPT_URL, 'https://www.edition01.com/index.php?route=account/login/embed');
            curl_setopt($ch, CURLOPT_POSTFIELDS, 'email=' . urlencode($login_email) . '&password=' . urlencode($login_pass));
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__) . "/tmp/my_cookies.txt");
            curl_setopt($ch, CURLOPT_COOKIEFILE, dirname(__FILE__) . "/tmp/my_cookies.txt");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_USERAGENT, "Opera/9.80 (Windows NT 6.1; WOW64; U; en) Presto/2.10.229 Version/11.64");
            curl_setopt($ch, CURLOPT_REFERER, "http://www.edition01.com");
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
            curl_setopt($ch, CURLOPT_TIMEOUT, 6);

            $page = curl_exec($ch);
            $_SESSION['edition'] = date('Y-m-d');
        }
        curl_setopt($ch, CURLOPT_URL, $sUrl);
        curl_setopt($ch, CURLOPT_COOKIEFILE, LG . "/tmp/my_cookies.txt");
        curl_setopt($ch, CURLOPT_POST, 0);

        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
        curl_setopt($ch, CURLOPT_TIMEOUT, 6);


        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($httpCode == 404) {
            lg(' 404:  ' . $sUrl);
        }

//            curl_close($handle);

        return curl_exec($ch);
    } catch (Exception $exc) {
        lg($sUrl);
        echo $exc->getTraceAsString();
    }
}

function getKebrel($sUrl) {

    $login_email = 'migle.baceviciute@gmail.com';
    $login_pass = 'Abcd456*';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://www.kembrel.com/customer/account/loginPost/referer/aHR0cDovL3d3dy5rZW1icmVsLmNvbS8,/');
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'login%5Busername%5D=crawl%40mailinator.com&fakepassword=Password&login%5Bpassword%5D=asdasd&send=');

    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__) . "/tmp/my_cookies.txt");
    curl_setopt($ch, CURLOPT_COOKIEFILE, dirname(__FILE__) . "/tmp/my_cookies.txt");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, "Opera/9.80 (Windows NT 6.1; WOW64; U; en) Presto/2.10.229 Version/11.64");
    curl_setopt($ch, CURLOPT_REFERER, "http://www.ideeli.com");
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $page = curl_exec($ch);
    curl_setopt($ch, CURLOPT_URL, $sUrl);
    curl_setopt($ch, CURLOPT_COOKIEFILE, LG . "/tmp/my_cookies.txt");
    curl_setopt($ch, CURLOPT_POST, 0);
    return curl_exec($ch);
}

function getPure($sUrl) {

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://purecitizen.com/customer/account/loginPost/');
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'login%5Busername%5D=crawl%40mailinator.com&login%5Bpassword%5D=asd5asd&send=');

    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__) . "/tmp/my_cookies.txt");
    curl_setopt($ch, CURLOPT_COOKIEFILE, dirname(__FILE__) . "/tmp/my_cookies.txt");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, "Opera/9.80 (Windows NT 6.1; WOW64; U; en) Presto/2.10.229 Version/11.64");
    curl_setopt($ch, CURLOPT_REFERER, "http://www.ideeli.com");
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $page = curl_exec($ch);
    curl_setopt($ch, CURLOPT_URL, $sUrl);
    curl_setopt($ch, CURLOPT_COOKIEFILE, LG . "/tmp/my_cookies.txt");
    curl_setopt($ch, CURLOPT_POST, 0);
    return curl_exec($ch);
}

function getRuela($sUrl) {

    $login_email = 'migle.baceviciute@gmail.com';
    $login_pass = 'Abcd456*';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://www.ruelala.com/registration/login');
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'email=' . urlencode($login_email) . '&password=' . urlencode($login_pass));

    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__) . "/tmp/my_cookies.txt");
    curl_setopt($ch, CURLOPT_COOKIEFILE, dirname(__FILE__) . "/tmp/my_cookies.txt");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, "Opera/9.80 (Windows NT 6.1; WOW64; U; en) Presto/2.10.229 Version/11.64");
    curl_setopt($ch, CURLOPT_REFERER, "http://www.ruelala.com");
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $page = curl_exec($ch);


    curl_setopt($ch, CURLOPT_URL, $sUrl);
    curl_setopt($ch, CURLOPT_COOKIEFILE, LG . "/tmp/my_cookies.txt");
    curl_setopt($ch, CURLOPT_POST, 0);
    $o = curl_exec($ch);
//        echo $o;
    return $o;
}

function getIdeeli($sUrl) {

    $login_email = 'migle.baceviciute@gmail.com';
    $login_pass = 'Abcd456*';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://www.ideeli.com/login');
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'login=' . urlencode($login_email) . '&password=' . urlencode($login_pass));

    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__) . "/tmp/my_cookies.txt");
    curl_setopt($ch, CURLOPT_COOKIEFILE, dirname(__FILE__) . "/tmp/my_cookies.txt");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, "Opera/9.80 (Windows NT 6.1; WOW64; U; en) Presto/2.10.229 Version/11.64");
    curl_setopt($ch, CURLOPT_REFERER, "http://www.ideeli.com");
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $page = curl_exec($ch);


    curl_setopt($ch, CURLOPT_URL, $sUrl);
    curl_setopt($ch, CURLOPT_COOKIEFILE, LG . "/tmp/my_cookies.txt");
    curl_setopt($ch, CURLOPT_POST, 0);
    $o = curl_exec($ch);
//        echo $o;
    return $o;
}

function getBango($sUrl) {

    $login_email = 'tygasz@gmail.com';
    $login_pass = 'asdasd';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://www.bagobsession.com/customer/account/login/');
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'login[username]' . urlencode($login_email) . '&login[password]=' . urlencode($login_pass));


    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__) . "/tmp/my_cookies.txt");
    curl_setopt($ch, CURLOPT_COOKIEFILE, dirname(__FILE__) . "/tmp/my_cookies.txt");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, "Opera/9.80 (Windows NT 6.1; WOW64; U; en) Presto/2.10.229 Version/11.64");
    curl_setopt($ch, CURLOPT_REFERER, "http://www.bagobsession.com");


    $page = curl_exec($ch);

    curl_setopt($ch, CURLOPT_URL, $sUrl);
    curl_setopt($ch, CURLOPT_COOKIEFILE, LG . "/tmp/my_cookies.txt");
    curl_setopt($ch, CURLOPT_POST, 0);
    $o = curl_exec($ch);
//        echo $o;
    return $o;
}

function getBlitsy($sUrl) {
    $login_email = 'tygasz@gmail.com';
    $login_pass = 'asdasd';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://blitsy.com/customer/account/login/');
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'login[username]' . urlencode($login_email) . '&login[password]=' . urlencode($login_pass));

    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__) . "/tmp/my_cookies.txt");
    curl_setopt($ch, CURLOPT_COOKIEFILE, dirname(__FILE__) . "/tmp/my_cookies.txt");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, "Opera/9.80 (Windows NT 6.1; WOW64; U; en) Presto/2.10.229 Version/11.64");
    curl_setopt($ch, CURLOPT_REFERER, "http://blitsy.com/");

    $page = curl_exec($ch);


    curl_setopt($ch, CURLOPT_URL, $sUrl);
    curl_setopt($ch, CURLOPT_COOKIEFILE, LG . "/tmp/my_cookies.txt");
    $o = curl_exec($ch);

    return $o;
}

function getDrift($sUrl) {

    $login_email = 'tygasz@gmail.com';
    $login_pass = 'asdasd';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://driftwagon.com/index.php/PrivateSales/Restricted/Login/');
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'login[username]' . urlencode($login_email) . '&login[password]=' . urlencode($login_pass));


    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__) . "/tmp/my_cookies.txt");
    curl_setopt($ch, CURLOPT_COOKIEFILE, dirname(__FILE__) . "/tmp/my_cookies.txt");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, "Opera/9.80 (Windows NT 6.1; WOW64; U; en) Presto/2.10.229 Version/11.64");
    curl_setopt($ch, CURLOPT_REFERER, "http://driftwagon.com/");

    $page = curl_exec($ch);

    curl_setopt($ch, CURLOPT_URL, $sUrl);
    curl_setopt($ch, CURLOPT_COOKIEFILE, LG . "/tmp/my_cookies.txt");
    curl_setopt($ch, CURLOPT_POST, 0);
    $o = curl_exec($ch);
//        echo $o;
    return $o;
}

function getGilt($sUrl) {

    $login_email = 'tygasz@gmail.com';
    $login_pass = 'asdasd';

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://www.gilt.com/login?return_url=');
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'email=' . urlencode($login_email) . '&password=' . urlencode($login_pass));

    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__) . "/tmp/my_cookies.txt");
    curl_setopt($ch, CURLOPT_COOKIEFILE, dirname(__FILE__) . "/tmp/my_cookies.txt");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, "Opera/9.80 (Windows NT 6.1; WOW64; U; en) Presto/2.10.229 Version/11.64");
    curl_setopt($ch, CURLOPT_REFERER, "http://www.gilt.com");
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $page = curl_exec($ch);
    $_SESSION['git'] = time();
    if (!isset($_SESSION['git'])) {
        
    }

    curl_setopt($ch, CURLOPT_URL, $sUrl);
    curl_setopt($ch, CURLOPT_COOKIEFILE, LG . "/tmp/my_cookies.txt");
    curl_setopt($ch, CURLOPT_POST, 0);
    $o = curl_exec($ch);
//        echo $o;
    return $o;
}

function getHire($sUrl) {

    $login_email = 'tygas';
    $login_pass = 'asdasd';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://hire.lt/users/signin');
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'data[User][User/username]=' . urlencode($login_email) . '&data[User][User/password]=' . urlencode($login_pass));

    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__) . "/tmp/my_cookies.txt");
    curl_setopt($ch, CURLOPT_COOKIEFILE, dirname(__FILE__) . "/tmp/my_cookies.txt");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, "Opera/9.80 (Windows NT 6.1; WOW64; U; en) Presto/2.10.229 Version/11.64");
    curl_setopt($ch, CURLOPT_REFERER, "http://www.gilt.com");
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $page = curl_exec($ch);

    curl_setopt($ch, CURLOPT_URL, $sUrl);
    curl_setopt($ch, CURLOPT_COOKIEFILE, LG . "/tmp/my_cookies.txt");
    curl_setopt($ch, CURLOPT_POST, 0);
    $o = curl_exec($ch);
//        echo $o;
    return $o;
}

function getHaute($sUrl) {

    $login_email = 'tygasz@gmail.com';
    $login_pass = 'asdasd';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://www.hautelook.com/v2/login');
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'email=' . urlencode($login_email) . '&password=' . urlencode($login_pass));

    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__) . "/tmp/my_cookies.txt");
    curl_setopt($ch, CURLOPT_COOKIEFILE, dirname(__FILE__) . "/tmp/my_cookies.txt");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, "Opera/9.80 (Windows NT 6.1; WOW64; U; en) Presto/2.10.229 Version/11.64");
    curl_setopt($ch, CURLOPT_REFERER, "http://www.hautelook.com");
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $page = curl_exec($ch);

    curl_setopt($ch, CURLOPT_URL, $sUrl);
    curl_setopt($ch, CURLOPT_COOKIEFILE, LG . "/tmp/my_cookies.txt");
    curl_setopt($ch, CURLOPT_POST, 0);
    $o = curl_exec($ch);
//        echo $o;
    return $o;
}

function getBrand($sUrl) {

    $login_email = 'tygasz@gmail.com';
    $login_pass = 'asdasd';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://www.brandsexclusive.com.au/so/login/?login=t');
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'username=' . urlencode($login_email) . '&password=' . urlencode($login_pass));

    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__) . "/tmp/my_cookies.txt");
    curl_setopt($ch, CURLOPT_COOKIEFILE, dirname(__FILE__) . "/tmp/my_cookies.txt");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, "Opera/9.80 (Windows NT 6.1; WOW64; U; en) Presto/2.10.229 Version/11.64");
    curl_setopt($ch, CURLOPT_REFERER, "https://www.brandsexclusive.com.au");
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $page = curl_exec($ch);

    curl_setopt($ch, CURLOPT_URL, $sUrl);
    curl_setopt($ch, CURLOPT_COOKIEFILE, LG . "/tmp/my_cookies.txt");
    curl_setopt($ch, CURLOPT_POST, 0);
    $o = curl_exec($ch);
//        echo $o;
    return $o;
}

function getTotsy($sUrl) {

    $login_email = 'migle.baceviciute@gmail.com';
    $login_pass = 'Abcd456*';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://www.totsy.com/customer/account/loginPost/');
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'login[username]=' . urlencode($login_email) . '&login[password]=' . urlencode($login_pass));

    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__) . "/tmp/my_cookies.txt");
    curl_setopt($ch, CURLOPT_COOKIEFILE, dirname(__FILE__) . "/tmp/my_cookies.txt");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, "Opera/9.80 (Windows NT 6.1; WOW64; U; en) Presto/2.10.229 Version/11.64");
    curl_setopt($ch, CURLOPT_REFERER, "https://www.totsy.com/");
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $page = curl_exec($ch);

    curl_setopt($ch, CURLOPT_URL, $sUrl);
    curl_setopt($ch, CURLOPT_COOKIEFILE, LG . "/tmp/my_cookies.txt");
    curl_setopt($ch, CURLOPT_POST, 0);
    $o = curl_exec($ch);
    return $o;
}

function getBeon($sUrl) {

    $login_email = 'migle.baceviciute@gmail.com';
    $login_pass = 'Abcd456*';

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://www.beyondtherack.com/auth/login?r=' . $sUrl);
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'email=' . urlencode($login_email) . '&password=' . urlencode($login_pass) . 'r=' . urlencode($sUrl));
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_COOKIEJAR, LG . "/tmp/my_cookies.txt");
    curl_setopt($ch, CURLOPT_COOKIEFILE, LG . "/tmp/my_cookies.txt");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, "Opera/9.80 (Windows NT 6.1; WOW64; U; en) Presto/2.10.229 Version/11.64");
    curl_setopt($ch, CURLOPT_REFERER, "http://www.beyondtherack.com");
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    curl_exec($ch);

    curl_setopt($ch, CURLOPT_URL, $sUrl);
    curl_setopt($ch, CURLOPT_COOKIEFILE, LG . "/tmp/my_cookies.txt");
    curl_setopt($ch, CURLOPT_POST, 0);


    $o = curl_exec($ch);

    return $o;
}

function get_string_between($string, $start, $end) {
    $string = " " . $string;
    $ini = stripos($string, $start);
    if ($ini == 0)
        return "";
    $ini += strlen($start);
    $len = stripos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}

function getOneking($sUrl) {

    $login_email = 'migle.baceviciute@gmail.com';
    $login_pass = 'Abcd456*';


    $ch = curl_init();

    if (!isset($_COOKIE['oneking'])) {
        curl_setopt($ch, CURLOPT_URL, 'https://www.onekingslane.com/login');
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'email=' . urlencode($login_email) . '&password=' . urlencode($login_pass));

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__) . "/tmp/my_cookies.txt");
        curl_setopt($ch, CURLOPT_COOKIEFILE, dirname(__FILE__) . "/tmp/my_cookies.txt");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, "Opera/9.80 (Windows NT 6.1; WOW64; U; en) Presto/2.10.229 Version/11.64");
        curl_setopt($ch, CURLOPT_REFERER, "http://www.onekingslane.com");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        setcookie('oneking', time());
    }

    $page = curl_exec($ch);

    curl_setopt($ch, CURLOPT_URL, $sUrl);
    curl_setopt($ch, CURLOPT_COOKIEFILE, LG . "/tmp/my_cookies.txt");
    curl_setopt($ch, CURLOPT_POST, 0);
    $o = curl_exec($ch);
//        echo $o;
    return $o;
}

function getSecret($sUrl) {
//        return getSec($sUrl);
    $login_email = 'tygasz@gmail.com';
    $login_pass = 'asdasd';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://secretsales.com/user_login_secure/');
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'username=' . urlencode($login_email) . '&password=' . urlencode($login_pass) . '&LoginRequest=do-login-new&password_hidden=');

    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__) . "/tmp/my_cookies.txt");
    curl_setopt($ch, CURLOPT_COOKIEFILE, dirname(__FILE__) . "/tmp/my_cookies.txt");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, "Opera/9.80 (Windows NT 6.1; WOW64; U; en) Presto/2.10.229 Version/11.64");

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $page = curl_exec($ch);

    curl_setopt($ch, CURLOPT_URL, $sUrl);
    curl_setopt($ch, CURLOPT_COOKIEFILE, LG . "/tmp/my_cookies.txt");
    curl_setopt($ch, CURLOPT_POST, 0);
    $o = curl_exec($ch);

    return $o;
}

function getSec($sUrl) {
    $login_email = 'tygasz@gmail.com';
    $login_pass = 'asdasd';

    $mc = EpiCurl::getInstance();

    $ch1 = curl_init('https://secretsales.com/user_login_secure/');
    curl_setopt($ch1, CURLOPT_POSTFIELDS, 'username=' . urlencode($login_email) . '&password=' . urlencode($login_pass) . '&LoginRequest=do-login-new&password_hidden=');
    curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch1, CURLOPT_POST, 1);
    curl_setopt($ch1, CURLOPT_USERAGENT, "Opera/9.80 (Windows NT 6.1; WOW64; U; en) Presto/2.10.229 Version/11.64");
    curl_setopt($ch1, CURLOPT_COOKIEFILE, dirname(__FILE__) . "/tmp/my_cookies.txt");
    curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false);
    $curl1 = $mc->addCurl($ch1);



    $ch2 = curl_init($sUrl);
    curl_setopt($ch2, CURLOPT_HEADER, 0);
    curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch2, CURLOPT_COOKIEFILE, dirname(__FILE__) . "/tmp/my_cookies.txt");
    $curl2 = $mc->addCurl($ch2);


    return $curl2->code;
}

function getEditor($sUrl) {

    $login_email = 'tygasz@gmail.com';
    $login_pass = 'asdasd';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_COOKIEFILE, dirname(__FILE__) . "/tmp/my_cookies.txt");
    curl_setopt($ch, CURLOPT_USERAGENT, "Opera/9.80 (Windows NT 6.1; WOW64; U; en) Presto/2.10.229 Version/11.64");


    curl_setopt($ch, CURLOPT_URL, 'http://www.editorscloset.com//cust/brandshows.asp?d=it');
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'email_address=' . urlencode($login_email) . '&password=' . urlencode($login_pass));

    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $page = curl_exec($ch);

    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_URL, $sUrl);
    curl_setopt($ch, CURLOPT_COOKIEFILE, LG . "/tmp/my_cookies.txt");

    return curl_exec($ch);
}

function getStyle($sUrl) {

    $login_email = 'tygasz@gmail.com';
    $login_pass = 'asdasd';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://www.stylehopper.com/customer/account/loginPost/');
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'login[username]=' . urlencode($login_email) . '&login[password]=' . urlencode($login_pass));

    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__) . "/tmp/my_cookies.txt");
    curl_setopt($ch, CURLOPT_COOKIEFILE, dirname(__FILE__) . "/tmp/my_cookies.txt");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, "Opera/9.80 (Windows NT 6.1; WOW64; U; en) Presto/2.10.229 Version/11.64");

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $page = curl_exec($ch);

    curl_setopt($ch, CURLOPT_URL, $sUrl);
    curl_setopt($ch, CURLOPT_COOKIEFILE, LG . "/tmp/my_cookies.txt");
    curl_setopt($ch, CURLOPT_POST, 0);
    return curl_exec($ch);
}

function getThepea($sUrl) {

    $login_email = 'tygasz@gmail.com';
    $login_pass = 'asdasd';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://www.thepeacockparade.com/user/sign_in');
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'user[email]=' . urlencode($login_email) . '&user[password]=' . urlencode($login_pass));

    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__) . "/tmp/my_cookies.txt");
    curl_setopt($ch, CURLOPT_COOKIEFILE, dirname(__FILE__) . "/tmp/my_cookies.txt");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, "Opera/9.80 (Windows NT 6.1; WOW64; U; en) Presto/2.10.229 Version/11.64");

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $page = curl_exec($ch);

    curl_setopt($ch, CURLOPT_URL, $sUrl);
    curl_setopt($ch, CURLOPT_COOKIEFILE, LG . "/tmp/my_cookies.txt");
    curl_setopt($ch, CURLOPT_POST, 0);
    return curl_exec($ch);
}

function getVenteprivee($sUrl) {

    $login_email = 'M8R-pgn66u@mailinator.com';
    $login_pass = 'derpinator';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://secure.uk.vente-privee.com/vp4/Login/Portal_EN.aspx');
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'txtEmail=' . urlencode($login_email) . '&txtPassword=' . urlencode($login_pass));

    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__) . "/tmp/my_cookies.txt");
    curl_setopt($ch, CURLOPT_COOKIEFILE, dirname(__FILE__) . "/tmp/my_cookies.txt");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, "Opera/9.80 (Windows NT 6.1; WOW64; U; en) Presto/2.10.229 Version/11.64");
    curl_setopt($ch, CURLOPT_REFERER, "http://en.vente-privee.com/vp4/Login/Portal.ashx");
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $page = curl_exec($ch);

    curl_setopt($ch, CURLOPT_URL, $sUrl);
    curl_setopt($ch, CURLOPT_COOKIEFILE, LG . "/tmp/my_cookies.txt");
    curl_setopt($ch, CURLOPT_POST, 0);
    $o = curl_exec($ch);

    return $o;
}

function getEnvite($sUrl) {

    $login_email = 'tygasz@gmail.com';
    $login_pass = 'asdasd';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://www.envite.com/login/sign_in');
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'email=' . urlencode($login_email) . '&password=' . urlencode($login_pass) . '&login=login&submit.x=64&submit.y=12');

    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__) . "/tmp/my_cookies.txt");
    curl_setopt($ch, CURLOPT_COOKIEFILE, dirname(__FILE__) . "/tmp/my_cookies.txt");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, "Opera/9.80 (Windows NT 6.1; WOW64; U; en) Presto/2.10.229 Version/11.64");
    curl_setopt($ch, CURLOPT_REFERER, "http://www.envite.com/index.php/sales");
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $page = curl_exec($ch);

    curl_setopt($ch, CURLOPT_URL, $sUrl);
    curl_setopt($ch, CURLOPT_COOKIEFILE, LG . "/tmp/my_cookies.txt");
    curl_setopt($ch, CURLOPT_POST, 0);
    $o = curl_exec($ch);

    return $o;
}

function getEnviius($sUrl) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://www.enviius.com/process.php');
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'username=crawl%40mailinator.com&password=asdasd&stay_logged=1&login=attempt&x=0&y=0');

    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__) . "/tmp/my_cookies.txt");
    curl_setopt($ch, CURLOPT_COOKIEFILE, dirname(__FILE__) . "/tmp/my_cookies.txt");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, "Opera/9.80 (Windows NT 6.1; WOW64; U; en) Presto/2.10.229 Version/11.64");
    curl_setopt($ch, CURLOPT_REFERER, "http://www.enviius.com");
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $page = curl_exec($ch);

    curl_setopt($ch, CURLOPT_URL, $sUrl);
    curl_setopt($ch, CURLOPT_COOKIEFILE, LG . "/tmp/my_cookies.txt");
    curl_setopt($ch, CURLOPT_POST, 0);
    return curl_exec($ch);
}

function getIvory($sUrl) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://www.ivorytrunk.com/cust/brandshows.asp?d=it');
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'email_address=crawler%40mailinator.com&password=asdasd&x=0&y=0');

    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__) . "/tmp/my_cookies.txt");
    curl_setopt($ch, CURLOPT_COOKIEFILE, dirname(__FILE__) . "/tmp/my_cookies.txt");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, "Opera/9.80 (Windows NT 6.1; WOW64; U; en) Presto/2.10.229 Version/11.64");
    curl_setopt($ch, CURLOPT_REFERER, "http://www.ivorytrunkk.com");
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $page = curl_exec($ch);

    curl_setopt($ch, CURLOPT_URL, $sUrl);
    curl_setopt($ch, CURLOPT_COOKIEFILE, LG . "/tmp/my_cookies.txt");
    curl_setopt($ch, CURLOPT_POST, 0);
    return curl_exec($ch);
}

function getRefinery($sUrl) {
    $ch = curl_init();

    if (!isset($_SESSION['logged_ivory'])) {
        curl_setopt($ch, CURLOPT_URL, 'https://welcome.refinery29.com/login');
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'email=crawl%40mailinator.com&password=asdasd');

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_COOKIEFILE, dirname(__FILE__) . "/tmp/my_cookies.txt");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, "Opera/9.80 (Windows NT 6.1; WOW64; U; en) Presto/2.10.229 Version/11.64");
        curl_setopt($ch, CURLOPT_REFERER, "http://www.refinery29.com");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $_SESSION['logged_ivory'] = time();
    }

    $page = curl_exec($ch);

    curl_setopt($ch, CURLOPT_URL, $sUrl);
    curl_setopt($ch, CURLOPT_COOKIEFILE, LG . "/tmp/my_cookies.txt");
    curl_setopt($ch, CURLOPT_POST, 0);
    return curl_exec($ch);
}

function getThesample($sUrl) {
    try {
        $ch = curl_init();

//            if (!isset($_SESSION['getThesample']) OR 1) {
        curl_setopt($ch, CURLOPT_URL, 'http://www.thesamplesale.com/cust/brandshows.asp?d=it');
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'email_address=crawl%40mailinator.com&password=asdasd&x=48&y=4');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__) . "/tmp/my_cookies.txt");
        curl_setopt($ch, CURLOPT_COOKIEFILE, dirname(__FILE__) . "/tmp/my_cookies.txt");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, "Opera/9.80 (Windows NT 6.1; WOW64; U; en) Presto/2.10.229 Version/11.64");
        curl_setopt($ch, CURLOPT_REFERER, "http://www.edition01.com");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
        curl_setopt($ch, CURLOPT_TIMEOUT, 6);

        $page = curl_exec($ch);
        $_SESSION['getThesample'] = time();
//            }

        curl_setopt($ch, CURLOPT_URL, $sUrl);
        curl_setopt($ch, CURLOPT_COOKIEFILE, LG . "/tmp/my_cookies.txt");
        curl_setopt($ch, CURLOPT_POST, 0);

        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
        curl_setopt($ch, CURLOPT_TIMEOUT, 6);


        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);


        if ($httpCode == 404) {
            lg(' 404:  ' . $sUrl);
        }


        return curl_exec($ch);
    } catch (Exception $exc) {
        lg($sUrl);
        echo $exc->getTraceAsString();
    }
}

function getFab($sUrl = 'https://fab.com/?', $sPost = 'utf8=%E2%9C%93&authenticity_token=j46viLMcH7CtTRDoGeihYnb57P%2B8j%2FGHMDGW73McPi8%3D&user%5Bun_or_email%5D=crawl%40mailinator.com&user%5Bpassword%5D=asdasd&user%5Bform_type%5D=login&user%5Breferrer_url%5D=&invitecode=&fref=&frefl=&nan_pid=') {
    try {


        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fab.com/?');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $sPost);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_COOKIEFILE, LG . "/tmp/my_cookies.txt");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, "Opera/9.80 (Windows NT 6.1; WOW64; U; en) Presto/2.10.229 Version/11.64");
        curl_setopt($ch, CURLOPT_REFERER, "http://www.fab.com");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
        curl_setopt($ch, CURLOPT_TIMEOUT, 6);

        $page = curl_exec($ch);

// set URL and other appropriate options

        curl_setopt($ch, CURLOPT_URL, $sUrl);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_COOKIEFILE, LG . "/tmp/my_cookies.txt");
        curl_setopt($ch, CURLOPT_POST, 1);

        $s = curl_exec($ch);
// close cURL resource, and free up system resources

        curl_close($ch);

        return $s;
    } catch (Exception $exc) {
        lg($sUrl);
        echo $exc->getTraceAsString();
    }
}

function getUrl($sUrl = 'http://www.artspace.com/signin', $sPost = 'http://www.artspace.com/signin') {
    try {


        $ch = curl_init();

        $aUrl = parse_url(trim($sUrl));


        if (!isset($_SESSION[$aUrl['host']])) {
            curl_setopt($ch, CURLOPT_URL, $sUrl);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $sPost);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_COOKIEFILE, LG . "/tmp/my_cookies.txt");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_USERAGENT, "Opera/9.80 (Windows NT 6.1; WOW64; U; en) Presto/2.10.229 Version/11.64");
            curl_setopt($ch, CURLOPT_REFERER, "http://www.fab.com");
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
            curl_setopt($ch, CURLOPT_TIMEOUT, 6);

            $page = curl_exec($ch);
        }

// set URL and other appropriate options

        curl_setopt($ch, CURLOPT_URL, $sUrl);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_COOKIEFILE, LG . "/tmp/my_cookies.txt");
        curl_setopt($ch, CURLOPT_POST, 1);

        $s = curl_exec($ch);
// close cURL resource, and free up system resources

        curl_close($ch);

        return $s;
    } catch (Exception $exc) {
        lg($sUrl);
        echo $exc->getTraceAsString();
    }
}

function GetBetw($content, $start, $end, $bStrip = false) {
    $r = explode($start, $content);

    if (isset($r[1])) {
        $r = explode($end, $r[1]);

        if ($bStrip)
            return cl($r[0]);
        else
            return $r[0];
    }
    return '';
}

function GetBetween($haystack, $start, $end, $debug = false) {
    $startpos = strpos($haystack, $start);
    $haystack2 = substr($haystack, $startpos + strlen($start), strlen($haystack) - $startpos + 1 - strlen($start));
    $endpos = strpos($haystack2, $end) + $startpos + strlen($start);

    //                $haystack = substr($haystack, $end);

    if ($debug == 'dbg') {
        lg("DEBUG: startPos='$startpos', endPos='$endpos', string='$haystack'");
    }

    if ($startpos === false AND $endpos === false AND $endpos < $startpos)
        return NULL;

    $endpos = $endpos + strlen($end);
    $endpos = $endpos - $startpos;
    $endpos = $endpos - strlen($end);
    $tag = substr($haystack, $startpos, $endpos);
    $tag = substr($tag, strlen($start));

    if ($debug == 'dbg')
        var_dump($haystack, $startpos, $endpos, $tag);

    if ($debug)
        return cl($tag);

    return $tag;
}

function cl($sVar) {
    return trim(strip_tags(($sVar)));
}

