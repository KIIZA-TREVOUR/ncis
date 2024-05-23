<?php 
   if ($f == 'auth') {
	

if ($s == 'login') {
    $username = __secure($_POST['email']); // Username can be email or lin
    $password = __secure($_POST['password']);
    $hashed_password = md5($password);
    
    $isLoggedIn = false;
    if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
        $isLoggedIn = login($username, $hashed_password);
    } elseif (isNumber($username)) {
        $isLoggedIn = studentLogin($username, $hashed_password);
    }
    if ($isLoggedIn) {
        // Fetch user from database
        if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
            $user = $db->where('email', $username)->getOne('users');
        } else {
            $user = $db->where('lin', $username)->getOne('users');
        }
        if ($user) {
            $_SESSION['user_id'] = $user->id;
            $_COOKIE['user_id'] = $user->id;

            if ($user->user_type == 'admin') {
                $data = array(
                    'status' => 200,
                    'message' => 'Admin Login Successful',
                    'url' => 'admin.php?page=index'
                );
            } elseif ($user->user_type == 'staff') {
                $data = array(
                    'status' => 200,
                    'message' => 'Staff Login Successful',
                    'url' => 'admin.php?page=index'
                );
            } elseif ($user->user_type == 'student') {
                $data = array(
                    'status' => 200,
                    'message' => 'Student Login Successful',
                    'url' => 'admin.php?page=index'
                );
            }else{
                $data = array(
                    'status' => 201,
                    'message' => 'Not Registered, Contact Admin'
                );
            }
        } else {
            $data = array(
                'status' => 201,
                'message' => 'User not found'
            );
        }
    } else {
        $data = array(
            'status' => 201,
            'message' => 'Incorrect Login Credentials'
        );
    }
}

   	if ($s == 'logins') {
   		$email = __secure($_POST['email']);
   		$lin = __secure($_POST['email']);
   		$password = md5(__secure($_POST['password']));
   		$user_type = __secure($_POST['user_type']);
   		if($user_type == 'admin'){
   			if(logins($email,$password,'admins')){
   				$user = $db->where('email',$email)->getOne('admins');
   				if($user){
   					$_SESSION['user_id'] = $user->id;
   					$_COOKIE['user_id'] = $user->id;
   					
   					$data = array(
   						'status' => 200,
   						'message' => 'Admin Logged In Successfully' ,
   						'url' => 'admin.php?page=dashboard'
   					);
   					}else{
   								$data = array(
   								'status' => 201,
   								'message' => 'You arent registered as an admin'
   							);
   						}
   			}else{
   				$data = array(
   							'status' => 201,
   							'message' => 'Incorrect Login Credentials'
   						);
   			}
   
   		}elseif($user_type == 'staff'){
			if(logins($email,$password,'staff')){
				$staff = $db->where('email',$email)->getOne('staff');
				if($staff){
					$_SESSION['user_id'] = $staff->id;
					$_COOKIE['user_id'] = $staff->id;
					$data = array(
						'status' => 200,
						'message' => 'Staff Logged In Successfully' ,
						'url' => 'admin.php?page=dashboard'
					);
					}else{
								$data = array(
								'status' => 201,
								'message' => 'You arent registered as a Staff'
							);
						}
			}else{
				$data = array(
							'status' => 201,
							'message' => 'Incorrect Login Credentials'
						);
			}
   		}else{
			if(login_student($email,$password)){
				$student = $db->where('lin',$lin)->getOne('students');
				if($student){
					$_SESSION['user_id'] = $student->id;
					$_COOKIE['user_id'] = $student->id;
					$data = array(
						'status' => 200,
						'message' => 'Student Logged In Successfully' ,
						'url' => 'student.php?page=dashboard'
					);
					}else{
								$data = array(
								'status' => 201,
								'message' => 'You arent registered as a Student'
							);
						}
			}else{
				$data = array(
							'status' => 201,
							'message' => 'Incorrect Login Credentials'
						);
			}   
   		}
   	
   	}
   	
   	if ($s == 'password') {
   		$email= __secure($_POST['email']);
   		$admin_data = $db->where('email',$email)->getOne('users');
   		if(empty($admin_data)){
   			$data = array(
   				'status' => 201,
   				'message'	=>	'Something went wrong! Enter your registered email'
   			);
   		}else{		
   			$code = rand(111111,999999);
   			$token = random_string(32);
   			$url = 'admin.php?page=reset&user_id='.$admin_data->id.'&token='.$token;
   			$message_body = "We have received a request to reset your password. Use the code below <br>".$code;
   			$message_body.= 'or Click on the link below <br>'.$url;
   
   			$to = $email;
                     $subject = "New Password Reset";
                     $headers = "From: support@gu.ac.ug";
               $insert = array(
               	'user_id' => $admin_data->id,
               	'reset_code' => $code,
               	'token' => $token
               );
   
               $sql = save_data('password_reset',$insert);
               if($sql){
               	$data = array(
   				'status' => 200,
   				'message'	=>	'Data Saved Successfully '
   			);
   
               }
               // if(mail($to,$subject,$message_body,$headers)){
               // 	$data = array(
   			// 	'status' => 200,
   			// 	'message'	=>	'We have sent you a Verification Code to your email address '.$email,
   			// 	'url' => 'admin.php?page=forgot'
   			// );
               // }else{
   	        //     	$data = array(
   			// 		'status' => 201,
   			// 		'message'	=>	'Error Sending Verification Code',
   			// 	);
               // }
           }
       }

	   
	   if ($s == 'register') {
        $school_config = $school['config'];
        $school_config['email'] = __secure($_POST['cemail']);
        $school_config['phone'] = __secure($_POST['ccontact']);
        $school_config['address'] = __secure($_POST['caddress']);
        $school_config['logo'] = 'default';
        $school_config['footer_description'] = 'default';
        $school_config['country'] = __secure($_POST['country']);
        $school_config['web_description'] = 'Results Management';
        $school_config['web_paragraph'] = 'Results Management';
        $school_config['badge'] = 'badge';
    
        $notifications = json_encode([
            "notifyShipmentStatus" => 0,
            "notifyStatusChange" => 0,
            "notifyNewCustomer" => 0
        ]);
    
        $channels = json_encode([
            "whatsapp" => 0,
            "email" => 0,
            "sms" => 0
        ]);
    
        $insert = array(
            "company_name" => __secure($_POST['cname']),
            "config" => json_encode($school_config),
            "notifications" => $notifications,
            "channels" => $channels
        );
         $email = $school_config['email'];
        if (exists('schools', "WHERE JSON_EXTRACT(config, '$.email') = '" . $email . "'")) {
                $data = array(
                    'status' => 201,
                    'message' => 'School Account Already Exists'
                );
                return;
        }elseif (exists('schools', "WHERE name = '".__secure($_POST['name'])."' OR reg_no = '".__secure($_POST['reg_no'])."'")) {
             $data = array(
                    'status' => 201,
                    'message' => 'School Account Already Exists'
                );
                return;
        }
    
        $id = insert_id('companies', $insert);
    
        if ($id > 0) {
            $fullname = __secure($_POST['name']);
            $fname = $lname = '';
            $p = explode(' ', $fullname);
            
            if (count($p) > 1) {
                $fname = $p[0];
                $lname = $p[1];
            }
    
            $user = array(
                "firstname" => __secure($fname),
                "lastname" => __secure($lname),
                "username" => mb_strtolower(str_replace(" ", ".", $fullname) . '.' . rand(1, 9)),
                "email" => __secure($_POST['email']),
                "password" => md5(__secure($_POST['password'])),
                "phone" => __secure($_POST['phone']),
                "company_id" => __secure($id),
                'user_type' => 'company'
            );
    
            $email = $school_config['email'];
            if (save_data('users', $user)) {
                    $data = array(
                        'status' => 200,
                        'message' => 'Company Account Created Successfully',
                        'url' => 'admin.php?page=login'
                    );
                } else if (exists('companies', "WHERE JSON_EXTRACT(config, '$.email') = '" . $email . "'")) {
                $data = array(
                    'status' => 201,
                    'message' => 'Company Account Already Exists'
                );
            } else {
                    $data = array(
                        'status' => 201,
                        'message' => 'Account Creation Failed'
                    );
            }
    
             
        } else {
            $data = array(
                'status' => 201,
                'message' => 'Company Creation Failed'
            );
        }
    }
    
   }
   ?>