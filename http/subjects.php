<?php 
	if ($f == 'subjects') {
		if ($s == 'new') {
			$insert = array(
				'name'	=>	__secure($_POST['name']),
				'code'	=>	__secure($_POST['code']),
				'created_by'	=>	1,
			);
			if(exists('subjects','WHERE code = "'.__secure($_POST['code']).'"')){

				$data = array(
					'status'	=>	201,
					'message'	=>	'Subject Already Exists'
				);
			}else{
				if (save_data('subjects',$insert)) {
					$data = array(
						'status'	=>	200,
						'message'	=>	'Subject Registered successfully'
					);
				}else{
					$data = array(
						'status'	=>	201,
						'message'	=>	'Something went wrong'
					);
				}

			}
        }

		if ($s == 'edit') {
			$id = __secure($_POST['id']);
			$insert = array(
				'name'	=>	__secure($_POST['name']),
				'code'	=>	__secure($_POST['code']),
				'modified_by'	=>	1,
			);
			if(exists('subjects','WHERE code = "'.__secure($_POST['code']).'"')){

				$data = array(
					'status'	=>	201,
					'message'	=>	'Subject Already Exists'
				);
			}else{
				if (update_data('subjects',$insert,'WHERE id = "'.$id.'"')) {
					$data = array(
						'status' => 200,
						'message'	=>	'Subject Updated Successfully',
					);
				}else{
					$data = array(
						'status' => 201,
						'message'	=>	'Something went wrong'
					);
				}
			}
			
		}

		if ($s == 'remove') {
			$id = __secure($_POST['id']);
			if ($db->where('id',$id)->delete('subjects')) {
				$data = array(
					'status'	=>	200,
					'message'	=>	'Subject Deleted Successfully'
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
        
	}
?>