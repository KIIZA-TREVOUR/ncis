<?php 
	if ($f == 'classes') {
		if ($s == 'new') {
			$insert = array(
				'name'	=>	__secure($_POST['name']),
				'description'	=>	mysqli_real_escape_string($sqlConnect,$_POST['description']),
			);
			if (save_data('classes',$insert)) {
				$data = array(
					'status'	=>	200,
					'message'	=>	'Class Added successfully'
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
				'description'	=>	mysqli_real_escape_string($sqlConnect,$_POST['description']),
			);
			if (update_data('classes',$insert,'WHERE id = "'.$id.'"')) {
				$data = array(
					'status' => 200,
					'message'	=>	'Class Updated Successfully',
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
			if ($db->where('id',$id)->delete('classes')) {
				$data = array(
					'status'	=>	200,
					'message'	=>	'Class Deleted Successfully'
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