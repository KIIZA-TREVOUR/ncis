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


function login($email_or_username, $password) {
    global $sqlConnect; // Assuming $sqlConnect is your database connection
    
    // Check if the input is an email or username
    if (filter_var($email_or_username, FILTER_VALIDATE_EMAIL)) {
        // If it's an email, prepare the statement to query by email
        $stmt = mysqli_prepare($sqlConnect, "SELECT * FROM users WHERE email = ? AND password = ?");
    } else {
        // If it's a username, prepare the statement to query by username
        $stmt = mysqli_prepare($sqlConnect, "SELECT * FROM users WHERE lin = ? AND password = ?");
    }
    
    // Bind parameters
    mysqli_stmt_bind_param($stmt, "ss", $email_or_username, $password);
    
    // Execute the statement
    mysqli_stmt_execute($stmt);
    
    // Store the result
    mysqli_stmt_store_result($stmt);
    
    // Check if user exists
    if (mysqli_stmt_num_rows($stmt) > 0) {
        mysqli_stmt_close($stmt);
        return true;
    } else {
        mysqli_stmt_close($stmt);
        return false;
    }
}

function studentLogin($lin,$password){
    global $sqlConnect,$db;
    $d = mysqli_query($sqlConnect,"SELECT * FROM users WHERE lin = '$lin' AND password = '$password'");

    if (mysqli_num_rows($d) > 0) {
        return true;
    }
    return false;
}
	// Helper functions
    function isEmail($input) {
        return filter_var($input, FILTER_VALIDATE_EMAIL) !== false;
    }
    function isNumber($input) {
        return preg_match('/^[0-9]+$/', $input);
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

function generateInitialLinNumber($year) {
    // Start the sequence from 0101 for the given year
    return $year . "0101";
}

function getStudentLinNumber($year) {
    global $sqlConnect;

    // Prepare the SQL query to get the max LIN number for the given year
    $sql = "SELECT MAX(lin) AS max_lin FROM students WHERE LEFT(lin, 4) = '{$year}'";
    $result = mysqli_query($sqlConnect, $sql);
    $row = mysqli_fetch_assoc($result);

    // Check if the latest LIN number is empty, null, or 0
    if ($row['max_lin'] === null || $row['max_lin'] === '' || $row['max_lin'] == 0) {
        // If no LIN number exists for the given year, generate the first LIN number for that year
        return generateInitialLinNumber($year);
    } else {
        // Extract the numeric part from the latest LIN number
        $numericPart = (int)substr($row['max_lin'], 4);

        // Increment the numeric part
        $nextNumber = $numericPart + 1;

        // Combine the year and the incremented numeric part to form the new LIN number
        $newLinNumber = $year . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        return $newLinNumber;
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
function getCurrentDateWithTwoMonthsAdded()
{
    date_default_timezone_set("Africa/Kampala");

    // Get the current date
    $currentDate = date_create();

    // Add 2 months to the current date
    date_add($currentDate, date_interval_create_from_date_string('2 months'));

    // Format the date as desired
    $formattedDate = date_format($currentDate, 'Y-m-d h:i:s');

    return $formattedDate;
}
function getTotalScoresByTerm($term) {
    global $sqlConnect;
    $sql = "
        SELECT ps.score
        FROM project_scores ps
        JOIN projects p ON ps.project_code = p.project_code
        WHERE p.term = ?
    ";

    // Prepare the statement
    $stmt = mysqli_prepare($sqlConnect, $sql);
    if (!$stmt) {
        die("MySQL prepare statement error: " . mysqli_error($sqlConnect));
    }

    // Bind the parameters
    mysqli_stmt_bind_param($stmt, 'i', $term);

    // Execute the statement
    mysqli_stmt_execute($stmt);

    // Get the result
    $result = mysqli_stmt_get_result($stmt);
    if (!$result) {
        die("MySQL execute statement error: " . mysqli_error($sqlConnect));
    }

    // Calculate the total score
    $totalScore = 0;
    while ($row = mysqli_fetch_object($result)) {
        // Convert the score from out of 100 to out of 5
        $score =$row->score;
        $totalScore += $score;
    }

    // Close the statement
    mysqli_stmt_close($stmt);

    return $totalScore;
}

function getProjectScoresByTerm($student_lin, $term, $class)
{
    global $sqlConnect;

    $sql = "
        SELECT ps.*, p.subject_code, p.name 
        FROM project_scores ps
        JOIN projects p ON ps.project_code = p.project_code
        WHERE ps.student_lin = ? AND p.term = ? AND p.class_id = ?
    ";

    // Prepare the statement
    $stmt = mysqli_prepare($sqlConnect, $sql);
    if (!$stmt) {
        die("MySQL prepare statement error: " . mysqli_error($sqlConnect));
    }

    // Bind the parameters
    mysqli_stmt_bind_param($stmt, 'sii', $student_lin, $term, $class);

    // Execute the statement
    mysqli_stmt_execute($stmt);

    // Get the result
    $result = mysqli_stmt_get_result($stmt);
    if (!$result) {
        die("MySQL execute statement error: " . mysqli_error($sqlConnect));
    }

    // Fetch the data and group by subject_code
    $scores_by_subject = array();
    while ($row = mysqli_fetch_object($result)) {
        $subject_code = $row->subject_code;
        if (!isset($scores_by_subject[$subject_code])) {
            $scores_by_subject[$subject_code] = array();
        }
        $scores_by_subject[$subject_code][] = $row->score;
    }

    // Close the statement
    mysqli_stmt_close($stmt);

    // Compute the average scores
    $average_scores = array();
    foreach ($scores_by_subject as $subject_code => $scores) {
        $num_projects = count($scores);
        if ($num_projects == 1) {
            $average_score = $scores[0];
        } elseif ($num_projects == 2) {
            $average_score = array_sum($scores) / 2;
        } else {
            // Sort scores in descending order and take the top 3 scores
            rsort($scores);
            $top_scores = array_slice($scores, 0, 3);
            $average_score = array_sum($top_scores) / count($top_scores);
        }

        // Convert the score from out of 100 to out of 20
        $converted_score = ($average_score / 100) * 20;

        // Prepare the result object
        $subject_result = new stdClass();
        $subject_result->subject_code = $subject_code;
        $subject_result->average_score = $average_score;
        $subject_result->converted_score = $converted_score;

        $average_scores[] = $subject_result;
    }

    return $average_scores;
}


function getProjectScoresByClassSubjectTerm($class_id, $subject_code, $term)
{
    global $sqlConnect;

    $sql = "
        SELECT ps.*, p.name, p.description, p.class_id, p.project_type, p.term, p.year 
        FROM project_scores ps
        JOIN projects p ON ps.project_code = p.project_code
        WHERE p.class_id = ? AND p.subject_code = ? AND p.term = ?
    ";

    // Prepare the statement
    $stmt = mysqli_prepare($sqlConnect, $sql);
    if (!$stmt) {
        die("MySQL prepare statement error: " . mysqli_error($sqlConnect));
    }

    // Bind the parameters
    mysqli_stmt_bind_param($stmt, 'isi', $class_id, $subject_code, $term);

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
        array_push($data, $row);
    }

    // Close the statement
    mysqli_stmt_close($stmt);

    return $data;
}

function getProjectScoresByClassAllSubjectsTerm($class_id, $term)
{
    global $sqlConnect;

    $sql = "
        SELECT ps.*, p.name, p.description, p.class_id, p.project_type, p.term, p.year 
        FROM project_scores ps
        JOIN projects p ON ps.project_code = p.project_code
        WHERE p.class_id = ?  AND p.term = ?
    ";

    // Prepare the statement
    $stmt = mysqli_prepare($sqlConnect, $sql);
    if (!$stmt) {
        die("MySQL prepare statement error: " . mysqli_error($sqlConnect));
    }

    // Bind the parameters
    mysqli_stmt_bind_param($stmt, 'ii', $class_id, $term);

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
        array_push($data, $row);
    }

    // Close the statement
    mysqli_stmt_close($stmt);

    return $data;
}
function getProjectScoresByClassAllSubjectsTermByStudent($class_id, $term,$lin)
{
    global $sqlConnect;

    $sql = "
        SELECT ps.*, p.name, p.description, p.class_id, p.project_type, p.term, p.year 
        FROM project_scores ps
        JOIN projects p ON ps.project_code = p.project_code
        WHERE p.class_id = ?  AND p.term = ? AND ps.student_lin =?
    ";

    // Prepare the statement
    $stmt = mysqli_prepare($sqlConnect, $sql);
    if (!$stmt) {
        die("MySQL prepare statement error: " . mysqli_error($sqlConnect));
    }

    // Bind the parameters
    mysqli_stmt_bind_param($stmt, 'iis', $class_id, $term,$lin);

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
        array_push($data, $row);
    }

    // Close the statement
    mysqli_stmt_close($stmt);

    return $data;
}
function getSubjectsByStudent($student_lin) {
    global $sqlConnect;

    $sql = "
        SELECT subject_code
        FROM student_subject
        WHERE student_lin = ?
    ";

    // Prepare the statement
    $stmt = mysqli_prepare($sqlConnect, $sql);
    if (!$stmt) {
        die("MySQL prepare statement error: " . mysqli_error($sqlConnect));
    }

    // Bind the parameters
    mysqli_stmt_bind_param($stmt, 's', $student_lin);

    // Execute the statement
    mysqli_stmt_execute($stmt);

    // Get the result
    $result = mysqli_stmt_get_result($stmt);
    if (!$result) {
        die("MySQL execute statement error: " . mysqli_error($sqlConnect));
    }

    // Fetch the data
    $subjects = array();
    while ($row = mysqli_fetch_object($result)) {
        // Explode the subject codes if they are in comma-separated format
        $subject_codes = explode(",", $row->subject_code);
        // Merge with existing subjects
        $subjects = array_merge($subjects, $subject_codes);
    }

    // Close the statement
    mysqli_stmt_close($stmt);

    // Remove duplicate subject codes
    $subjects = array_unique($subjects);

    return $subjects;
}

function getStaffEmailBySubject($subject_code) {
    global $sqlConnect;

    // SQL query to get the staff email who teaches the given subject
    $sql = "
        SELECT s.email 
        FROM staff_subject ss
        JOIN staff s ON ss.staff_email = s.email
        WHERE FIND_IN_SET(?, ss.subject_code)
        LIMIT 1
    ";

    // Prepare the statement
    $stmt = mysqli_prepare($sqlConnect, $sql);
    if (!$stmt) {
        die("MySQL prepare statement error: " . mysqli_error($sqlConnect));
    }

    // Bind the parameter
    mysqli_stmt_bind_param($stmt, 's', $subject_code);

    // Execute the statement
    mysqli_stmt_execute($stmt);

    // Get the result
    $result = mysqli_stmt_get_result($stmt);
    if (!$result) {
        die("MySQL execute statement error: " . mysqli_error($sqlConnect));
    }

    // Fetch the data
    $email = null;
    if ($row = mysqli_fetch_object($result)) {
        $email = $row->email;
    }

    // Close the statement
    mysqli_stmt_close($stmt);

    return $email;
}

function getProjectScoresByClassAllSubjectsAllTerms($class_id)
{
    global $sqlConnect;

    $sql = "
        SELECT ps.*, p.name, p.description, p.class_id, p.project_type, p.term, p.year 
        FROM project_scores ps
        JOIN projects p ON ps.project_code = p.project_code
        WHERE p.class_id = ?
        ORDER BY p.term
    ";

    // Prepare the statement
    $stmt = mysqli_prepare($sqlConnect, $sql);
    if (!$stmt) {
        die("MySQL prepare statement error: " . mysqli_error($sqlConnect));
    }

    // Bind the parameters
    mysqli_stmt_bind_param($stmt, 'i', $class_id);

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
        array_push($data, $row);
    }

    // Close the statement
    mysqli_stmt_close($stmt);

    return $data;
}


function getProjectScoresByClassSubjectAllTerms($class_id, $subject_code)
{
    global $sqlConnect;

    $sql = "
    SELECT ps.*, p.subject_code, p.name, p.description, p.class_id, p.project_type, p.term, p.year 
    FROM project_scores ps
    JOIN projects p ON ps.project_code = p.project_code 
    WHERE p.class_id = ? AND p.subject_code = ?
    ORDER BY p.term
    ";

    // Prepare the statement
    $stmt = mysqli_prepare($sqlConnect, $sql);
    if (!$stmt) {
        die("MySQL prepare statement error: " . mysqli_error($sqlConnect));
    }

    // Bind the parameters
    mysqli_stmt_bind_param($stmt, 'is', $class_id, $subject_code);

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
        array_push($data, $row);
    }

    // Close the statement
    mysqli_stmt_close($stmt);

    return $data;
}

function getTotalScoresForSubject($student_lin, $subject_code, $class)
{
    global $sqlConnect;

    $sql = "
        SELECT ps.score
        FROM project_scores ps
        JOIN projects p ON ps.project_code = p.project_code
        WHERE ps.student_lin = ? AND p.subject_code = ? AND p.class_id = ?
    ";

    // Prepare the statement
    $stmt = mysqli_prepare($sqlConnect, $sql);
    if (!$stmt) {
        die("MySQL prepare statement error: " . mysqli_error($sqlConnect));
    }

    // Bind the parameters
    mysqli_stmt_bind_param($stmt, 'ssi', $student_lin, $subject_code, $class);

    // Execute the statement
    mysqli_stmt_execute($stmt);

    // Get the result
    $result = mysqli_stmt_get_result($stmt);
    if (!$result) {
        die("MySQL execute statement error: " . mysqli_error($sqlConnect));
    }

    // Fetch the data
    $scores = array();
    while ($row = mysqli_fetch_object($result)) {
        $scores[] = $row->score;
    }

    // Close the statement
    mysqli_stmt_close($stmt);

    if (empty($scores)) {
        return 0; // No scores found
    }

    // Sort scores in descending order to easily pick the top scores if needed
    rsort($scores);

    // Determine the scores to use based on the number of projects
    if (count($scores) == 1) {
        $selected_scores = $scores;
    } elseif (count($scores) == 2) {
        $selected_scores = $scores;
    } else {
        // Pick the top 3 scores if there are more than 3 projects
        $selected_scores = array_slice($scores, 0, 3);
    }

    // Convert each selected score from out of 100 to out of 20
    $converted_scores = array_map(function($score) {
        return ($score / 100) * 20;
    }, $selected_scores);

    // Calculate the average of the converted scores
    $average_score = array_sum($converted_scores) / count($converted_scores);

    return $average_score;
}

function getSingleProjectScoreContribution($student_lin, $term, $class, $project_code) {
    global $sqlConnect;
    $sql = "
        SELECT ps.*, p.subject_code, p.name 
        FROM project_scores ps
        JOIN projects p ON ps.project_code = p.project_code
        WHERE ps.student_lin = ? AND p.term = ? AND p.class_id = ? AND ps.project_code = ?
    ";

    // Prepare the statement
    $stmt = mysqli_prepare($sqlConnect, $sql);
    if (!$stmt) {
        die("MySQL prepare statement error: " . mysqli_error($sqlConnect));
    }

    // Bind the parameters
    mysqli_stmt_bind_param($stmt, 'siii', $student_lin, $term, $class, $project_code);

    // Execute the statement
    mysqli_stmt_execute($stmt);

    // Get the result
    $result = mysqli_stmt_get_result($stmt);
    if (!$result) {
        die("MySQL execute statement error: " . mysqli_error($sqlConnect));
    }

    // Fetch the data
    $row = mysqli_fetch_object($result);
    if ($row) {
        // Convert the score from out of 100 to out of 1.67
        $row->score = ($row->score / 100) * 1.67;
    } else {
        $row = null;
    }

    // Close the statement
    mysqli_stmt_close($stmt);

    return $row ? $row->score : null;
}

function getAnnualProjectContribution($student_lin, $class) {
    // Initialize an array to store the total scores of each term
    $terms = [1, 2, 3];
    $totalScore = 0;

    // Loop through each term and accumulate the scores
    foreach ($terms as $term) {
        $termScores = getProjectScoresByTerm($student_lin, $term, $class);

        foreach ($termScores as $score) {
            $totalScore += $score->score;
        }
    }

    // The sum of the scores is already converted to be out of 1.67
    // Normalize the total score to be out of 5%
    $annualContribution = $totalScore;

    return $annualContribution;
}

function generateProjectCodes1($year, $subject) {
    global $sqlConnect;
    
    // Ensure subject is in uppercase and trimmed to a suitable length (e.g., 4 characters)
    $subjectCode = strtoupper(substr(trim($subject), 0, 4));

    // Get the next sequential number
    $nextNumber = getLatestId('project_code', 'projects', $year, $subject);

    // Format the project code
    $formattedNumber = str_pad($nextNumber, 2, '0', STR_PAD_LEFT);
    $projectCode = $subjectCode . $year . $formattedNumber;

    return $projectCode;
}

function getLastFourYears() {
    $currentYear = date("Y");

    $years = [];

    // Generate the last four years as objects
    for ($i = 0; $i < 4; $i++) {
        $yearObject = new stdClass();
        $yearObject->year = $currentYear - $i;
        $years[] = $yearObject;
    }

    return $years;
}
function generateProjectCode($year, $subject) {
    global $sqlConnect;
    
    // Ensure subject is in uppercase and trimmed to a suitable length (e.g., 4 characters)
    $subjectCode = strtoupper(substr(trim($subject), 0, 4));

    // Prepare the SQL query to get the max project code for the given subject and year
    $sql = "SELECT MAX(project_code) AS max_code FROM projects WHERE project_code LIKE '{$subjectCode}{$year}%'";
    $result = mysqli_query($sqlConnect, $sql);
    $row = mysqli_fetch_assoc($result);

    // Check if the latest code is empty or null
    if ($row['max_code'] === null || $row['max_code'] === '') {
        // Define an initial number if no codes exist for the given subject and year
        $initialNumber = '01';
    } else {
        // Extract the latest code
        $latestCode = $row['max_code'];

        // Extract the numeric part from the latest code
        $numericPart = substr($latestCode, -2);

        // Increment the numeric part
        $nextNumber = (int)$numericPart + 1;

        // Format the incremented number with leading zeros
        $initialNumber = str_pad($nextNumber, strlen($numericPart), '0', STR_PAD_LEFT);
    }

    // Combine the subject code, year, and the initial number to form the new code
    $formattedNumber = str_pad($initialNumber, 2, '0', STR_PAD_LEFT);
    $projectCode = $subjectCode . $year . $formattedNumber;

    return $projectCode;
}
?>