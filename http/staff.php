<?php 
	if ($f == 'staff') {
		if ($s == 'new') {
			$url = '';
			$allowTypes = array('image/bmp', 'image/jpeg', 'image/x-png', 'image/png', 'image/gif');
			if (!empty($_FILES['image'])) {
				if (in_array($_FILES['image']['type'], $allowTypes)) {
					$url = share_file('image','uploads/staff/',true,364,364);
				}
			}
			$insert = array(
				'firstname'	=>	__secure($_POST['firstname']),
				'lastname'	=>	__secure($_POST['lastname']),
				'email'	=>	__secure($_POST['email']),
				'contact'	=>	__secure($_POST['contact']),
				'role'	=>	__secure($_POST['role']),
				'sch_id'	=>	__secure($_POST['sch_id']),
				'description'	=>	mysqli_real_escape_string($sqlConnect,$_POST['description']),
				'image'	=>	__secure($url),
				'facebook'	=>	__secure($_POST['facebook']),
				'password'	=>__secure(md5('!'.$_POST['email'])),
				'twitter'	=>	__secure($_POST['twitter']),
			);
			$uinsert = array(
				'name'	=>	__secure($_POST['firstname']) ." " .__secure($_POST['lastname']),
				'email'	=>	__secure($_POST['email']),
				'password'	=>__secure(md5('!'.$_POST['email'])),
				'user_type'	=>	'staff',
			);
			if (save_data('staff',$insert)) {
				save_data('users',$uinsert);
				$data = array(
					'status'	=>	200,
					'message'	=>	'Staff Member added successfully'
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
			$staff = $db->where('id',$id)->getOne('staff');

			$url = __secure($_POST['uimage']);
			$allowTypes = array('image/bmp', 'image/jpeg', 'image/x-png', 'image/png', 'image/gif');
			if (!empty($_FILES['image'])) {
				if (in_array($_FILES['image']['type'], $allowTypes)) {
					$url = share_file('image','uploads/staff/',true,364,364);
				}
			}

			$insert = array(
				'firstname'	=>	__secure($_POST['firstname']),
				'lastname'	=>	__secure($_POST['lastname']),
				'email'	=>	__secure($_POST['email']),
				'contact'	=>	__secure($_POST['contact']),
				'role'	=>	__secure($_POST['role']),
				'sch_id'	=>	__secure($_POST['sch_id']),
				'description'	=>	mysqli_real_escape_string($sqlConnect,$_POST['description']),
				'image'	=>	__secure($url),
				'facebook'	=>	__secure($_POST['facebook']),
				'twitter'	=>	__secure($_POST['twitter']),
			);
			$uinsert = array(
				'name'	=>	__secure($_POST['firstname']) ." " .__secure($_POST['lastname']),
				'email'	=>	__secure($_POST['email']),
			);
			if (update_data('staff',$insert,'WHERE id = "'.$id.'"')) {
				update_data('users',$uinsert,'WHERE email = "'.$_POST['email'].'"');
				$data = array(
					'status' => 200,
					'message'	=>	'Staff Updated Successfully',
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
				'staff_id'	=>	__secure($_POST['staff_id']),
				'subject_id'	=>	$subjects,
			);
			if (save_data('staff_subject',$insert)) {
				$data = array(
					'status'	=>	200,
					'message'	=>	'Subjects Assigned Successfully',
					'url' => 	'admin.php?page=staff'
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
			if ($db->where('id',$id)->delete('staff')) {
				$user = $db->where('id',$id)->getOne('staff');
				$db->where('email',$user->email)->delete('users');
				$data = array(
					'status'	=>	200,
					'message'	=>	'Staff Deleted Successfully'
				);
			}else{
				$data = array(
					'status'	=>	201,
					'message'	=>	'Something went wrong'
				);
			}
		}
	}
?>