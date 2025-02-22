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
				'image'	=>	__secure($url),
				'password'	=>	__secure(md5($_POST['password'])),
			);
			$uinsert = array(
				'name'	=>	__secure($_POST['firstname']) ." " .__secure($_POST['lastname']),
				'email'	=>	__secure($_POST['email']),
				'password'	=>	__secure(md5($_POST['password'])),
				'user_type'	=>	'admin',
			);
			if (save_data('admins',$insert)) {
				save_data('users',$uinsert);
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
				'image'	=>	__secure($url),
			);
			$uinsert = array(
				'name'	=>	__secure($_POST['firstname']) ." " .__secure($_POST['lastname']),
				'email'	=>	__secure($_POST['email']),
			);
			if (update_data('admins',$insert,'WHERE id = "'.$id.'"')) {
				update_data('users',$uinsert,'WHERE email = "'.$_POST['email'].'"');
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

		
		if ($s == 'editadmin') {
			$id = __secure($_POST['id']);
			$users = $db->where('id',$id)->getOne('users');
			$url = __secure($_POST['uimage']);
			$allowTypes = array('image/bmp', 'image/jpeg', 'image/x-png', 'image/png', 'image/gif');
			if (!empty($_FILES['image'])) {
				if (in_array($_FILES['image']['type'], $allowTypes)) {
					$url = share_file('image','uploads/admins/',true,364,364);
				}
			}
			$insert = array(
				'name'	=>	__secure($_POST['name']),
				'email'	=>	__secure($_POST['email']),
				'photo'	=>	__secure($url),
			);
			if (update_data('users',$insert,'WHERE id = "'.$id.'"')) {
				$data = array(
					'status' => 200,
					'message'	=>	'Profile Updated Successfully',
					'url' => 'admin.php?page=profile'
				);
			}else{
				$data = array(
					'status' => 201,
					'message'	=>	'Something went wrong'
				);
			}
			
		}


	}
?>