<?php 
	if ($f == 'students') {
		if ($s == 'new') {
			$url = '';
			$allowTypes = array('image/bmp', 'image/jpeg', 'image/x-png', 'image/png', 'image/gif');
			if (!empty($_FILES['image'])) {
				if (in_array($_FILES['image']['type'], $allowTypes)) {
					$url = share_file('image','uploads/students/',true,364,364);
				}
			}
			
			
			$insert = array(
				'firstname'	=>	__secure($_POST['firstname']),
				'lastname'	=>	__secure($_POST['lastname']),
				'email'	=>	__secure($_POST['email']),
				'lin'	=>	__secure($_POST['lin']),
				'class'	=>	__secure($_POST['class']),
				'dob'	=>	__secure($_POST['dob']),
				'gender'	=>	__secure($_POST['gender']),
				'image'	=>	__secure($url),
				'password'	=>	__secure(md5($_POST['password'])),
			);
			if (save_data('students',$insert)) {
				$data = array(
					'status'	=>	200,
					'message'	=>	'Student Member added successfully'
				);
			}else{
				$data = array(
					'status'	=>	201,
					'message'	=>	'Something went wrong'
				);
			}
			
		}

		if ($s == 'edit') {
			$id = __secure($_POST['id']);
			$Student = $db->where('id',$id)->getOne('students');

			$url = __secure($_POST['uimage']);
			$allowTypes = array('image/bmp', 'image/jpeg', 'image/x-png', 'image/png', 'image/gif');
			if (!empty($_FILES['image'])) {
				if (in_array($_FILES['image']['type'], $allowTypes)) {
					$url = share_file('image','uploads/students/',true,364,364);
				}
			}

			$insert = array(
				'firstname'	=>	__secure($_POST['firstname']),
				'lastname'	=>	__secure($_POST['lastname']),
				'email'	=>	__secure($_POST['email']),
				'lin'	=>	__secure($_POST['lin']),
				'dob'	=>	__secure($_POST['dob']),
				'gender'	=>	__secure($_POST['gender']),
				'class'	=>	__secure($_POST['class']),
				'image'	=>	__secure($url),
				'date_modified' => date('Y-m-d h:i:sa'),
				'modified_by' => 1
			);
			if (update_data('students',$insert,'WHERE id = "'.$id.'"')) {
				$data = array(
					'status' => 200,
					'message'	=>	'Student Updated Successfully',
					'url' => 'admin.php?page=students'
				);
			}else{
				$data = array(
					'status' => 201,
					'message'	=>	'Something went wrong'
				);
			}
			
		}
		
		if ($s == 'assign') {
			$subject = $_POST['subjects'];
			$subjects = "";
			foreach($subject as $l){
				$subjects != "" && $subjects .= ",";
				$subjects .= $l;
			};
			
			$url = '';
			$insert = array(
				'student_lin'	=>	__secure($_POST['student_lin']),
				'subject_code'	=>	$subjects,
			);
			if (save_data('student_subject',$insert)) {
				$data = array(
					'status'	=>	200,
					'message'	=>	'Subjects Assigned Successfully'
				);
			}else{
				$data = array(
					'status'	=>	201,
					'message'	=>	'Something went wrong'
				);
			}
			
		}

		if ($s == 'remove') {
			$id = __secure($_POST['id']);
			if ($db->where('id',$id)->delete('students')) {
				$data = array(
					'status'	=>	200,
					'message'	=>	'Student Deleted Successfully'
				);
			}else{
				$data = array(
					'status'	=>	201,
					'message'	=>	'Something went wrong'
				);
			}
		}

		
		if ($s == 'filter-student-subjects') {
			$class_id = __secure($_POST['class_id']);
			if (!empty($class_id)) {
				$data = array(
					'status'	=>	200,
					'message'	=>	'Filtering S.'.$class_id.' Student Results',
                    'url' => 'admin.php?page=student-subjects&class='.$class_id.'',
				);
			}else{
				$data = array(
					'status'	=>	201,
					'message'	=>	'Please Select All Fields to filter Summary'
				);
			}
		}

	}
?>