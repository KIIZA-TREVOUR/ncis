<?php
    require('init/controller.php');
	date_default_timezone_set('Africa/Kampala');
    $f = @$_GET['f'];
    $s = @$_GET['s'];
    
    $f = __secure($f);
    $s = __secure($s);
    
    
    $data = array();

    if (file_exists('http/'.$f.'.php')) {
        include_once 'http/'.$f.'.php';
    }else{
        $data = array(
            'status' => 404,
            'message' => 'Endpoint not reached'
        );
    }
    header('Content-Type: application/json');
    echo json_encode($data);
?>