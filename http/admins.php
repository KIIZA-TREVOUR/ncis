<?php 
	if ($f == 'admins') {
		if ($s == 'new') {
			$url = '';
			$allowTypes = array('image/bmp', 'image/jpeg', 'image/x-png', 'image/png', 'image/gif');
			if (!empty($_FILES['image'])) {
				if (in_array($_FILES['image']['type'], $allowTypes)) {
					$url = share_file('image','uploads/admins/',true,364,364);
				}
			}
			$insert = array(
				'firstname'	=>	__secure($_POST['firstname']),
				'lastname'	=>	__secure($_POST['lastname']),
				'email'	=>	__secure($_POST['email']),
				'school_id'	=>	__secure($_POST['school_id']),
				'image'	=>	__secure($url),
				'password'	=>	__secure(md5($_POST['password'])),
			);
			if (save_data('admins',$insert)) {
				$data = array(
					'status'	=>	200,
					'message'	=>	'School Admin Added successfully'
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
			$admin = $db->where('id',$id)->getOne('admins');

			$url = __secure($_POST['uimage']);
			$allowTypes = array('image/bmp', 'image/jpeg', 'image/x-png', 'image/png', 'image/gif');
			if (!empty($_FILES['image'])) {
				if (in_array($_FILES['image']['type'], $allowTypes)) {
					$url = share_file('image','uploads/admins/',true,364,364);
				}
			}

			$insert = array(
				'firstname'	=>	__secure($_POST['firstname']),
				'lastname'	=>	__secure($_POST['lastname']),
				'email'	=>	__secure($_POST['email']),
				'sch_id'	=>	__secure($_POST['school_id']),
				'image'	=>	__secure($url),
			);
			if (update_data('admins',$insert,'WHERE id = "'.$id.'"')) {
				$data = array(
					'status' => 200,
					'message'	=>	'Admin Updated Successfully',
				);
			}else{
				$data = array(
					'status' => 201,
					'message'	=>	'Something went wrong'
				);
			}
			
		}


		if ($s == 'remove') {
			$id = __secure($_POST['id']);
			if ($db->where('id',$id)->delete('admins')) {
				$data = array(
					'status'	=>	200,
					'message'	=>	'Admin Deleted Successfully'
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