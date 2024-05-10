<?php 
	if ($f == 'projects') {
		if ($s == 'new') {
			$insert = array(
				'name'	=>	__secure($_POST['name']),
				'description'	=>	mysqli_real_escape_string($sqlConnect,$_POST['description']),
				'subject_code'	=>	__secure($_POST['subject_code']),
				'class_id'	=>	__secure($_POST['class_id']),
				'project_type'	=>	__secure($_POST['project_type']),
			);
			if (save_data('projects',$insert)) {
				$data = array(
					'status'	=>	200,
					'message'	=>	'Project Added successfully'
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
				'subject_code'	=>	__secure($_POST['subject_code']),
				'class_id'	=>	__secure($_POST['class_id']),
				'project_type'	=>	__secure($_POST['project_type']),
			);
			if (update_data('projects',$insert,'WHERE id = "'.$id.'"')) {
				$data = array(
					'status' => 200,
					'message'	=>	'Project Updated Successfully',
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
			if ($db->where('id',$id)->delete('projects')) {
				$data = array(
					'status'	=>	200,
					'message'	=>	'Project Deleted Successfully'
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