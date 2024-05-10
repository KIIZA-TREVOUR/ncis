<?php 
	if ($f == 'schools') {
		if ($s == 'new') {
			$insert = array(
				'name'	=>	__secure($_POST['name']),
				'reg_no'	=>	__secure($_POST['reg_no']),
				'email'	=>	__secure($_POST['email']),
				'address'	=>	__secure($_POST['address']),
				'created_by'	=>	1,
			);
			if (save_data('schools',$insert)) {
				$data = array(
					'status'	=>	200,
					'message'	=>	'School Added successfully'
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
			$insert = array(
				'name'	=>	__secure($_POST['name']),
				'reg_no'	=>	__secure($_POST['reg_no']),
				'email'	=>	__secure($_POST['email']),
				'address'	=>	__secure($_POST['address']),
				'modified_by'	=>	1,
			);
			if (update_data('schools',$insert,'WHERE id = "'.$id.'"')) {
				$data = array(
					'status' => 200,
					'message'	=>	'School Updated Successfully',
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
			if ($db->where('id',$id)->delete('schools')) {
				$data = array(
					'status'	=>	200,
					'message'	=>	'School Member deleted Successfully'
				);
			}else{
				$data = array(
					'status'	=>	201,
					'message'	=>	'Something went wrong'
				);
			}
		}
        
		if ($s == 'approve') {
			$id = __secure($_POST['id']);
			$insert = array(
				'status'	=>	1,
                'date_modified' => date('Y-m-d H:i:s')
			);
			if (update_data('schools',$insert,'WHERE id = "'.$id.'"')) {
				$data = array(
					'status' => 200,
					'message'	=>	'School Approved Successfully',
				);
			}else{
				$data = array(
					'status' => 201,
					'message'	=>	'Something went wrong'
				);
			}
			
		}
		if ($s == 'disapprove') {
			$id = __secure($_POST['id']);
			$insert = array(
				'status'	=>	0,
                'date_modified' => date('Y-m-d H:i:s')
			);
			if (update_data('schools',$insert,'WHERE id = "'.$id.'"')) {
				$data = array(
					'status' => 200,
					'message'	=>	'School Disapproved',
				);
			}else{
				$data = array(
					'status' => 201,
					'message'	=>	'Something went wrong'
				);
			}
			
		}
		if ($s == 'deregister') {
			$id = __secure($_POST['id']);
			$insert = array(
				'status'	=>	0,
                'date_modified' => date('Y-m-d H:i:s')
			);
			if (update_data('schools',$insert,'WHERE id = "'.$id.'"')) {
				$data = array(
					'status' => 200,
					'message'	=>	'School Diregistered',
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