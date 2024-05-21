<?php
/*ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);*/
header('Cache-Control: max-age=846000');

@ini_set('max_execution_time', 0);
@ini_set("memory_limit", "-1");
@set_time_limit(0);
session_start();
require_once('config.php');

require_once('init/libraries/DB/vendor/autoload.php');

$wallet =  $config  = array();
$sqlConnect   = $wallet['sqlConnect'] = null;

    // Connect to SQL Server
    $sqlConnect   = $wallet['sqlConnect'] = mysqli_connect($sql_db_host, $sql_db_user, $sql_db_pass, $sql_db_name, 3306);

    // Handling Server Errors 
    $ServerErrors = array();
    if (mysqli_connect_errno()) {
        $ServerErrors[] = "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    if (!function_exists('curl_init')) {
        $ServerErrors[] = "PHP CURL is NOT installed on your web server !";
    }

    $query = mysqli_query($sqlConnect, "SET NAMES utf8mb4");
    if (isset($ServerErrors) && !empty($ServerErrors)) {
        foreach ($ServerErrors as $Error) {
            echo "<h3>" . $Error . "</h3>";
        }
        die();
    }

    $config              = config();
    $db                  = new MysqliDb($sqlConnect);


    $http_header           = 'http://';
    if (!empty($_SERVER['HTTPS'])) {
        $http_header = 'https://';
    }
    $wallet['actual_link']   = $http_header . $_SERVER['HTTP_HOST'] . urlencode($_SERVER['REQUEST_URI']);



    // Get LoggedIn User Data
    $wallet['loggedin']           = false;
    $wallet['user'] = array(); 
    $wallet['staff'] = array(); 
    $wallet['student'] = array();

    if (logged_in() == true) {
        $session_id         = (!empty($_SESSION['user_id'])) ? $_SESSION['user_id'] : $_COOKIE['user_id'];
        $wallet['user'] = mysqli_fetch_array(mysqli_query($sqlConnect,"SELECT * FROM users WHERE id = '$session_id'"));
        $wallet['sch_id'] = $wallet['user']['sch_id'];
      
        $wallet['staff'] = mysqli_fetch_array(mysqli_query($sqlConnect,"SELECT * FROM staff WHERE id = '$session_id'"));
        $wallet['student'] = mysqli_fetch_array(mysqli_query($sqlConnect,"SELECT * FROM students WHERE id = '$session_id'"));
        $wallet['loggedin'] = true;
    }
    //Let us record the unique visitor;
    record_visitor();

    // Icons Virables
    $error_icon   = '<i class="fa fa-exclamation-circle"></i> ';
    $success_icon = '<i class="fa fa-check"></i> ';


    $wallet['regx_attr'] = '/(onclick=["](.*?)["]|onclick=[\'](.*?)[\']|onafterprint=["](.*?)["]|onafterprint=[\'](.*?)[\']|onbeforeprint=["](.*?)["]|onbeforeprint=[\'](.*?)[\']|onbeforeunload=["](.*?)["]|onbeforeunload=[\'](.*?)[\']|onerror=["](.*?)["]|onerror=[\'](.*?)[\']|onhashchange=["](.*?)["]|onhashchange=[\'](.*?)[\']|onload=["](.*?)["]|onload=[\'](.*?)[\']|onmessage=["](.*?)["]|onmessage=[\'](.*?)[\']|onoffline=["](.*?)["]|onoffline=[\'](.*?)[\']|ononline=["](.*?)["]|ononline=[\'](.*?)[\']|onpagehide=["](.*?)["]|onpagehide=[\'](.*?)[\']|onpageshow=["](.*?)["]|onpageshow=[\'](.*?)[\']|onpopstate=["](.*?)["]|onpopstate=[\'](.*?)[\']|onresize=["](.*?)["]|onresize=[\'](.*?)[\']|onstorage=["](.*?)["]|onstorage=[\'](.*?)[\']|onunload=["](.*?)["]|onunload=[\'](.*?)[\']|onblur=["](.*?)["]|onblur=[\'](.*?)[\']|onchange=["](.*?)["]|onchange=[\'](.*?)[\']|oncontextmenu=["](.*?)["]|oncontextmenu=[\'](.*?)[\']|onfocus=["](.*?)["]|onfocus=[\'](.*?)[\']|oninput=["](.*?)["]|oninput=[\'](.*?)[\']|oninvalid=["](.*?)["]|oninvalid=[\'](.*?)[\']|onreset=["](.*?)["]|onreset=[\'](.*?)[\']|onsearch=["](.*?)["]|onsearch=[\'](.*?)[\']|onselect=["](.*?)["]|onselect=[\'](.*?)[\']|onsubmit=["](.*?)["]|onsubmit=[\'](.*?)[\']|onkeydown=["](.*?)["]|onkeydown=[\'](.*?)[\']|onkeypress=["](.*?)["]|onkeypress=[\'](.*?)[\']|onkeyup=["](.*?)["]|onkeyup=[\'](.*?)[\']|ondblclick=["](.*?)["]|ondblclick=[\'](.*?)[\']|onmousedown=["](.*?)["]|onmousedown=[\'](.*?)[\']|onmousemove=["](.*?)["]|onmousemove=[\'](.*?)[\']|onmouseout=["](.*?)["]|onmouseout=[\'](.*?)[\']|onmouseover=["](.*?)["]|onmouseover=[\'](.*?)[\']|onmouseup=["](.*?)["]|onmouseup=[\'](.*?)[\']|onmousewheel=["](.*?)["]|onmousewheel=[\'](.*?)[\']|onwheel=["](.*?)["]|onwheel=[\'](.*?)[\']|ondrag=["](.*?)["]|ondrag=[\'](.*?)[\']|ondragend=["](.*?)["]|ondragend=[\'](.*?)[\']|ondragenter=["](.*?)["]|ondragenter=[\'](.*?)[\']|ondragleave=["](.*?)["]|ondragleave=[\'](.*?)[\']|ondragover=["](.*?)["]|ondragover=[\'](.*?)[\']|ondragstart=["](.*?)["]|ondragstart=[\'](.*?)[\']|ondrop=["](.*?)["]|ondrop=[\'](.*?)[\']|onscroll=["](.*?)["]|onscroll=[\'](.*?)[\']|oncopy=["](.*?)["]|oncopy=[\'](.*?)[\']|oncut=["](.*?)["]|oncut=[\'](.*?)[\']|onpaste=["](.*?)["]|onpaste=[\'](.*?)[\']|onabort=["](.*?)["]|onabort=[\'](.*?)[\']|oncanplay=["](.*?)["]|oncanplay=[\'](.*?)[\']|oncanplaythrough=["](.*?)["]|oncanplaythrough=[\'](.*?)[\']|oncuechange=["](.*?)["]|oncuechange=[\'](.*?)[\']|ondurationchange=["](.*?)["]|ondurationchange=[\'](.*?)[\']|onemptied=["](.*?)["]|onemptied=[\'](.*?)[\']|onended=["](.*?)["]|onended=[\'](.*?)[\']|onerror=["](.*?)["]|onerror=[\'](.*?)[\']|onloadeddata=["](.*?)["]|onloadeddata=[\'](.*?)[\']|onloadedmetadata=["](.*?)["]|onloadedmetadata=[\'](.*?)[\']|onloadstart=["](.*?)["]|onloadstart=[\'](.*?)[\']|onpause=["](.*?)["]|onpause=[\'](.*?)[\']|onplay=["](.*?)["]|onplay=[\'](.*?)[\']|onplaying=["](.*?)["]|onplaying=[\'](.*?)[\']|onprogress=["](.*?)["]|onprogress=[\'](.*?)[\']|onratechange=["](.*?)["]|onratechange=[\'](.*?)[\']|onseeked=["](.*?)["]|onseeked=[\'](.*?)[\']|onseeking=["](.*?)["]|onseeking=[\'](.*?)[\']|onstalled=["](.*?)["]|onstalled=[\'](.*?)[\']|onsuspend=["](.*?)["]|onsuspend=[\'](.*?)[\']|ontimeupdate=["](.*?)["]|ontimeupdate=[\'](.*?)[\']|onvolumechange=["](.*?)["]|onvolumechange=[\'](.*?)[\']|onwaiting=["](.*?)["]|onwaiting=[\'](.*?)[\']|ontoggle=["](.*?)["]|ontoggle=[\'](.*?)[\'])/m';


$config['site_url']  = $site_url;
$wallet['site_url']  = $site_url;
$wallet['config']    = $config;