<?php
    require('init/app_start.php');
	function load_page($url = ''){
        global $sqlConnect, $wallet, $db,$page;
        $pg = './layout/'.$url.'.phtml';
        if (!file_exists($pg)) {
            $pg = './layout/404.phtml';
        }
        $content = '';
        ob_start();
        require ($pg);
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }

    function logged_in() {
	    if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
	        return true;
	    } else if (isset($_COOKIE['user_id']) && !empty($_COOKIE['user_id'])) {
	       return true; 
	    } else {
	        return false;
	    }
	}

function config() {
    global $sqlConnect;
    $data  = array();
    $query = mysqli_query($sqlConnect, "SELECT * FROM config");
    if (mysqli_num_rows($query)) {
        while ($fetched_data = mysqli_fetch_assoc($query)) {
            $data[$fetched_data['name']] = $fetched_data['value'];
        }
    }        
    return $data;
}

function __secure($string, $censored_words = 1, $br = true, $strip = 0) {
    global $sqlConnect;
    $string = trim($string);
    $string = cleanString($string);
    $string = mysqli_real_escape_string($sqlConnect, $string);
    $string = htmlspecialchars($string, ENT_QUOTES);
    if ($br == true) {
        $string = str_replace('\r\n', " <br>", $string);
        $string = str_replace('\n\r', " <br>", $string);
        $string = str_replace('\r', " <br>", $string);
        $string = str_replace('\n', " <br>", $string);
    } else {
        $string = str_replace('\r\n', "", $string);
        $string = str_replace('\n\r', "", $string);
        $string = str_replace('\r', "", $string);
        $string = str_replace('\n', "", $string);
    }
    if ($strip == 1) {
        $string = stripslashes($string);
    }
    $string = str_replace('&amp;#', '&#', $string);
    if ($censored_words == 1) {
        global $config;
        $censored_words = @explode(",", $config['censored_words']);
        foreach ($censored_words as $censored_word) {
            $censored_word = trim($censored_word);
            $string        = str_replace($censored_word, '****', $string);
        }
    }
    return $string;
}

function cleanString($string) {
    return $string = preg_replace("/&#?[a-z0-9]+;/i","", $string); 
}

function save_data($table,$_data){
    global $sqlConnect;
    if (empty($_data)) {
        return false;
    }
    $fields                              = '`' . implode('`,`', array_keys($_data)) . '`';
    $data                                = '\'' . implode('\', \'', $_data) . '\'';
    $query                               = mysqli_query($sqlConnect, "INSERT INTO {$table} ({$fields})  VALUES ({$data})") or die(mysqli_error($sqlConnect));

    if ($query) {
        return true;
    } else {
        return false;
    }
}


function login($email,$password){
    global $sqlConnect,$db;
    $d = mysqli_query($sqlConnect,"SELECT * FROM users WHERE email = '$email' AND password = '$password'");

    if (mysqli_num_rows($d) > 0) {
        return true;
    }
    return false;
}

function logins($email,$password,$table){
    global $sqlConnect,$db;
    $d = mysqli_query($sqlConnect,"SELECT * FROM $table WHERE email = '$email' AND password = '$password'");

    if (mysqli_num_rows($d) > 0) {
        return true;
    }
    return false;
}function login_student($email,$password){
    global $sqlConnect,$db;
    $d = mysqli_query($sqlConnect,"SELECT * FROM students WHERE lin = '$email' AND password = '$password'");

    if (mysqli_num_rows($d) > 0) {
        return true;
    }
    return false;
}

function update_data($table, $data, $condition)
{
    global $sqlConnect;
    $table = __secure($table, 0);

    $update = array();
    foreach ($data as $field => $_data) {
        $update[] = '`' . $field . '` = \'' . $_data . '\'';
    }

    $impload   = implode(', ', $update);
    $query_one = " UPDATE $table SET {$impload} $condition ";
    $query1    = mysqli_query($sqlConnect, $query_one) or die(mysqli_error($sqlConnect));

    if ($query1) {
        return true;
    } else {
        return false;
    }
}

function LoadAdminPage($page_url = '') {
    global $wallet,$db;
    $page         = './admin/layout/' . $page_url . '.phtml';
    $page_content = '';
    ob_start();
    require($page);
    $page_content = ob_get_contents();
    ob_end_clean();
    return $page_content;
}


function share_file($name,$destination,$resize = false, $height = 0, $width = 0){
    global $wallet, $sqlConnect;
    $ext = pathinfo($_FILES[$name]['name'], PATHINFO_EXTENSION);
    $filename = md5(time().$_FILES[$name]['name']).'.'.$ext;
    $file = $_FILES[$name]['tmp_name'];
    if ($resize) {
        $source_properties = getimagesize($file);
        $image_type = $source_properties[2];
        if ($image_type == IMAGETYPE_JPEG) {
            $image_resource_id = imagecreatefromjpeg($file);
        } elseif ($image_type == IMAGETYPE_GIF) {
            $image_resource_id = imagecreatefromgif($file);
        } elseif ($image_type == IMAGETYPE_PNG) {
            $image_resource_id = imagecreatefrompng($file);
        }

        $target_layer = imagecreatetruecolor($width, $height);
        imagecopyresampled($target_layer, $image_resource_id, 0, 0, 0, 0, $width, $height, $source_properties[0], $source_properties[1]);
        imagejpeg($target_layer, $destination.$filename);

        return $destination.$filename;

    }else{
    
        if (move_uploaded_file($_FILES[$name]['tmp_name'], $destination.$filename)) {
            return $destination.$filename;
        }
    }

    return '';
}

function random_string($n) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
  
    for ($i = 0; $i < $n; $i++) {
        $index = rand(0, strlen($characters) - 1);
        $randomString .= $characters[$index];
    }
  
    return $randomString;
}
function short_text($text = "", $len = 100){
    if (empty($text) || !is_string($text) || !is_numeric($len) || $len < 1) {
        return "****";
    }
    if (strlen($text) > $len) {
        $text = mb_substr($text, 0, $len, "UTF-8") . "..";
    }
    return $text;
}

function slug($text){
    return preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', __secure($text))).'-'.random_string(5);
}

function so($query = '') {
    global $wallet, $config;
    if ($wallet['config']['seo'] == 1) {
        $query = preg_replace(array(
            '/^index\.php\?page=team-details&id=([A-Za-z0-9_]+)$/i',
            '/^index\.php\?page=publications&id=([A-Za-z0-9_]+)$/i',
            '/^index\.php\?page=department-details&slug=([^\/]+)$/i',
            '/^index\.php\?page=news-details&slug=([^\/]+)$/i',
            '/^index\.php\?page=research-details&slug=([^\/]+)$/i',
            '/^index\.php\?page=capacity-details&slug=([^\/]+)$/i',
            '/^index\.php\?page=service-details&slug=([^\/]+)$/i',
            '/^index\.php\?page=lab-details&slug=([^\/]+)$/i',
            '/^index\.php\?page=pages&slug=([^\/]+)$/i',
            '/^index\.php\?page=event-details&id=([^\/]+)$/i',
            '/^index\.php\?page=partners&slug=([^\/]+)$/i',
            '/^index\.php\?page=team$/i',
            '/^index\.php\?page=about$/i',
            '/^index\.php\?page=contact$/i',
            '/^index\.php\?page=publication$/i',
            '/^index\.php\?page=events$/i',
            '/^index\.php\?page=gallery$/i',
            '/^index\.php\?page=trainig$/i',
            '/^index\.php\?page=404$/i',
            '/^index\.php\?page=news$/i',
            '/^index\.php\?page=community$/i',
            '/^index\.php\?page=publications$/i',
            '/^index\.php\?page=guest_accommodation$/i',
            '/^index\.php\?page=index$/i',
        ), array(
            $config['site_url'] . 'staff/$1/',
            $config['site_url'] . 'publications/$1/',
            $config['site_url'] . 'departments/$1/',
            $config['site_url'] . 'news/$1/',
            $config['site_url'] . 'research/$1/',
           $config['site_url'] . 'capacity-building/$1/',
            $config['site_url'] . 'services/$1/',
            $config['site_url'] . 'laboratory/$1/',
            $config['site_url'] . 'pages/$1/',
            $config['site_url'] . 'events/$1/',
            $config['site_url'] . 'patners/$1/',
            $config['site_url'] . 'staff/',
            $config['site_url'] . 'about-us/',
            $config['site_url'] . 'contact-us/',
            $config['site_url'] . 'publication/',
            $config['site_url'] . 'events/',
            $config['site_url'] . 'gallery/',
            $config['site_url'] . 'trainig/',
            $config['site_url'] . '404/',
            $config['site_url'] . 'news/',
            $config['site_url'] . 'community-outreach/',
            $config['site_url'] . 'publications/',
            //$config['site_url'] . 'capacity/',            
            $config['site_url'] . 'guest-accommodation/',
            $config['site_url'],
        ), $query);
    } else {
        $query = $config['site_url'] . $query;
    }
    return $query;
}
function seo($query = '') {
    global $wallet, $config;
    if ($wallet['config']['seo'] == 1) {
        $query = preg_replace(array(
            '/^index\.php\?page=team-details&id=([A-Za-z0-9_]+)$/i',
            '/^index\.php\?page=student-details&id=([A-Za-z0-9_]+)$/i',
            '/^index\.php\?page=alumni-details&id=([A-Za-z0-9_]+)$/i',
            '/^index\.php\?page=publications&id=([A-Za-z0-9_]+)$/i',
            '/^index\.php\?page=department-details&slug=([^\/]+)$/i',
            '/^index\.php\?page=news-details&slug=([^\/]+)$/i',
            '/^index\.php\?page=research-details&slug=([^\/]+)$/i',
            '/^index\.php\?page=capacity-details&slug=([^\/]+)$/i',
            '/^index\.php\?page=service-details&slug=([^\/]+)$/i',
            '/^index\.php\?page=lab-details&slug=([^\/]+)$/i',
            '/^index\.php\?page=pages&slug=([^\/]+)$/i',
            '/^index\.php\?page=event-details&id=([^\/]+)$/i',
            '/^index\.php\?page=partners&slug=([^\/]+)$/i',
            '/^index\.php\?page=team$/i',
            '/^index\.php\?page=about$/i',
            '/^index\.php\?page=contact$/i',
            '/^index\.php\?page=publication$/i',
            '/^index\.php\?page=events$/i',
            '/^index\.php\?page=gallery$/i',
            '/^index\.php\?page=trainig$/i',
            '/^index\.php\?page=404$/i',
            '/^index\.php\?page=news$/i',
            '/^index\.php\?page=community$/i',
            '/^index\.php\?page=publications$/i',
            '/^index\.php\?page=guest_accommodation$/i',
            '/^index\.php\?page=director-message$/i',
            '/^index\.php\?page=partners$/i',
            '/^index\.php\?page=index$/i',
        ), array(
            $config['site_url'] . 'staff/$1/',
            $config['site_url'] . 'student/$1/',
            $config['site_url'] . 'alumni/$1/',
            $config['site_url'] . 'publications/$1/',
            $config['site_url'] . 'departments/$1/',
            $config['site_url'] . 'news/$1/',
            $config['site_url'] . 'research/$1/',
           $config['site_url'] . 'capacity-building/$1/',
            $config['site_url'] . 'services/$1/',
            $config['site_url'] . 'laboratory/$1/',
            $config['site_url'] . 'pages/$1/',
            $config['site_url'] . 'event-details/$1/',
            $config['site_url'] . 'patners/$1/',
            $config['site_url'] . 'staff/',
            $config['site_url'] . 'about-us/',
            $config['site_url'] . 'contact-us/',
            $config['site_url'] . 'publication/',
            $config['site_url'] . 'events/',
            $config['site_url'] . 'gallery/',
            $config['site_url'] . 'trainig/',
            $config['site_url'] . '404/',
            $config['site_url'] . 'news/',
            $config['site_url'] . 'community-outreach/',
            $config['site_url'] . 'publications/',          
            $config['site_url'] . 'guest-accommodation/',
            $config['site_url'] . 'director/',
            $config['site_url'] . 'partners/',
            $config['site_url'],
        ), $query);
    } else {
        $query = $config['site_url'] . $query;
    }
    return $query;
}
//get publictions with distict years
function getPublicationYears()
{
    global $sqlConnect;
    $sql = "SELECT DISTINCT year FROM `publications` ORDER BY year DESC";
    $result = mysqli_query($sqlConnect, $sql);
    $data = array();
    while ($row = mysqli_fetch_array($result)) {
        array_push($data, $row);
    }

    return $data;
}

function getLatestId($columnName,$tableName)
{
    global $sqlConnect;
    $sql = "SELECT MAX($columnName) AS max_id FROM $tableName";
    $result = mysqli_query($sqlConnect, $sql);
    $row = mysqli_fetch_assoc($result);
    
    // Check if the latest ID is empty, null, or 0
    if ($row['max_id'] === null || $row['max_id'] === '' || $row['max_id'] == 0) {
        return 1;
    } else {
        return $row['max_id']+1;
    }
}

 
function generateStudentNumber() {
    // Get the last two digits of the current year
    $year = date("y");
    
    // Append "0100" to the year
    $studentNumber = $year . "0100";
    
    return $studentNumber;
}

function getStudentLinNumber()
{
    global $sqlConnect;
    $sql = "SELECT MAX(lin) AS max_id FROM students";
    $result = mysqli_query($sqlConnect, $sql);
    $row = mysqli_fetch_assoc($result);
    
    // Check if the latest ID is empty, null, or 0
    if ($row['max_id'] === null || $row['max_id'] === '' || $row['max_id'] == 0) {
        return generateStudentNumber()+1;
    } else {
        return $row['max_id']+1;
    }
}



function record_visitor(){
    global $db, $sqlConnect;
    // Get the visitor's IP address
    $ip = $_SERVER['REMOTE_ADDR'];
    // Get the visitor's user agent (browser)
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    // Get the current date and time
    $datetime = date("Y-m-d H:i:s");

    $check = $db->where('ip',$ip)->getOne('visitors');
    if (empty($check)) {
        // Insert the visitor data into the database
        $sql = "INSERT INTO visitors (ip, user_agent, datetime) VALUES ('$ip', '$user_agent', '$datetime')";
        mysqli_query($sqlConnect, $sql);
        // Close the database connection
        //mysqli_close($sqlConnect);
    }
    
}

function filterDate($datetime) {
    $date = new DateTime($datetime);
    return $date->format("jS F  Y");
}


function exists($table, $condition)
{
	global $sqlConnect;
	$g = mysqli_query($sqlConnect, "SELECT * FROM $table $condition");
	if (mysqli_num_rows($g) > 0) {
		return true;
	}
	return false;
}

function fetchStudentResults()
{
    global $sqlConnect; // Assuming $sqlConnect is your MySQLi connection

    $sql = "SELECT DISTINCT student_lin
            FROM exam_results ";

    $result = mysqli_query($sqlConnect, $sql);

    if (!$result) {
        // Handle query error
        return false;
    }

    $studentDetails = array();

    while ($row = mysqli_fetch_assoc($result)) {
        $studentDetails[] = $row;
    }

    mysqli_free_result($result);

    return $studentDetails;
}
function fetchStudentResultsByClass($class)
{
    global $sqlConnect; // Assuming $sqlConnect is your MySQLi connection

    $sql = "SELECT DISTINCT student_lin
            FROM exam_results WHERE class_id ='".$class."'";
    $result = mysqli_query($sqlConnect, $sql);

    if (!$result) {
        // Handle query error
        return false;
    }

    $studentDetails = array();

    while ($row = mysqli_fetch_assoc($result)) {
        $studentDetails[] = $row;
    }

    mysqli_free_result($result);

    return $studentDetails;
}

function getCurrentDate()
{
    date_default_timezone_set("Africa/Kampala");
    $date = date("Y-m-d h:i:s");
    return $date;
}

function getProjectScoresByTerm($student_lin, $term,$class)
{
    global $sqlConnect;
    $sql = "
        SELECT ps.*, p.subject_code, p.name 
        FROM project_scores ps
        JOIN projects p ON ps.project_id = p.id
        WHERE ps.student_lin = ? AND p.term = ? AND p.class_id =?
    ";

    // Prepare the statement
    $stmt = mysqli_prepare($sqlConnect, $sql);
    if (!$stmt) {
        die("MySQL prepare statement error: " . mysqli_error($sqlConnect));
    }

    // Bind the parameters
    mysqli_stmt_bind_param($stmt, 'sii', $student_lin, $term,$class);

    // Execute the statement
    mysqli_stmt_execute($stmt);

    // Get the result
    $result = mysqli_stmt_get_result($stmt);
    if (!$result) {
        die("MySQL execute statement error: " . mysqli_error($sqlConnect));
    }

    // Fetch the data
    $data = array();
    while ($row = mysqli_fetch_object($result)) {
        // Convert the score from out of 100 to out of 5
        $row->score = ($row->score / 100) * 1.67;
        array_push($data, $row);
    }

    // Close the statement
    mysqli_stmt_close($stmt);

    return $data;
}

?>