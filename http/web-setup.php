<?php 
if ($f == 'web-setup') {
	$data = array();
	$targetDir = "uploads/system/"; 
    $allowTypes = array('jpg','png','jpeg','gif'); 
	if ($s == 'update') {
		$business_settings = array();
		foreach ($_POST['images'] as $key => $type) {
			if (isset($_FILES[$type]) && !empty($_FILES[$type])) {
				$business_settings['value'] = share_file($type,$targetDir);
				$db->where('name',$type)->update('config',$business_settings);
			}
		}

		foreach ($_POST['types'] as $key => $type) {

			
            if($type == 'site_name'){
                //$this->overWriteEnvFile('APP_NAME', $_POST[$type]);
            }
            if($type == 'timezone'){
                //$this->overWriteEnvFile('APP_TIMEZONE', $_POST[$type]);
            }else {
                if(gettype($_POST[$type]) == 'array'){
                	$d = array();
                	foreach ($_POST[$type] as $key => $value) {
                		array_push($d,$value);
                	}
                    $business_settings['value'] =  json_encode($d);
                }
                else {
                    $business_settings['value'] = $_POST[$type];
                }
                
            }
            $db->where('name',$type)->update('config',$business_settings);
        }
        $data = array(
	        'status'	=>	200,
	        'message'	=>	'Settings updated successfully',
	    );
        
	}

}