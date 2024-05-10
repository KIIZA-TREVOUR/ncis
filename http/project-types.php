<?php 
	if ($f == 'project_types') {
		if ($s == 'new') {
			$insert = array(
				'name'	=>	__secure($_POST['name']),
				'description'	=>	mysqli_real_escape_string($sqlConnect,$_POST['description']),
			);
			if (save_data('project_types',$insert)) {
				$data = array(
					'status'	=>	200,
					'message'	=>	'Project Type Added successfully'
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
			if (update_data('project_types',$insert,'WHERE id = "'.$id.'"')) {
				$data = array(
					'status' => 200,
					'message'	=>	'Project Type Updated Successfully',
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
			if ($db->where('id',$id)->delete('project_types')) {
				$data = array(
					'status'	=>	200,
					'message'	=>	'Project Type Deleted Successfully'
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