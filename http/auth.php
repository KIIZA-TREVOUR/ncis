<?php 
   if ($f == 'auth') {
   	if ($s == 'login') {
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
   			// 	'url' => 'index.php?page=forgot'
   			// );
               // }else{
   	        //     	$data = array(
   			// 		'status' => 201,
   			// 		'message'	=>	'Error Sending Verification Code',
   			// 	);
               // }
           }
       }
   }
   ?>