<?php 
	if ($f == 'results') {
		if ($s == 'new') {
			$insert = array(
				'student_lin'	=>	__secure($_POST['student_lin']),
				'subject_code'	=>	__secure($_POST['subject_code']),
				'score'	=>	__secure($_POST['score']),
				'class_id'	=>	__secure($_POST['class_id']),
				'exam_type'	=>	__secure($_POST['exam_type']),
			);
			if (save_data('exam_results',$insert)) {
				$data = array(
					'status'	=>	200,
					'message'	=>	'Results Added successfully',
					'url'	=>	'admin.php?page=school-results',
				);
			}else{
				$data = array(
					'status'	=>	201,
					'message'	=>	'Something went wrong'
				);
			}
        }

		if ($s == 'request') {
			$insert = array(
				'staff_email'	=>	__secure($_POST['staff_email']),
				'cid'	=>	__secure($_POST['cid']),
				'lin'	=>	__secure($_POST['lin']),
				'subject_code'	=>	__secure($_POST['subject_code']),
				'pid'	=>	__secure($_POST['pid']),
				'request'	=>	mysqli_real_escape_string($sqlConnect,$_POST['request']),
			);
			
			$update = array(
				'status'	=>	1,
			);
			if (save_data('requests',$insert)) {
				update_data('project_scores',$update,'WHERE id = "'.__secure($_POST['pid']).'"');
				$data = array(
					'status'	=>	200,
					'message'	=>	'Request Submitted for Approval',
					'url'	=>	'admin.php?page=projectresults',
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
				'student_lin'	=>	__secure($_POST['student_lin']),
				'subject_code'	=>	__secure($_POST['subject_code']),
				'score'	=>	__secure($_POST['score']),
				'class_id'	=>	__secure($_POST['class_id']),
				'exam_type'	=>	__secure($_POST['exam_type']),
			);
			if (update_data('exam_results',$insert,'WHERE id = "'.$id.'"')) {
				$data = array(
					'status' => 200,
					'message'	=>	'Results Updated Successfully',
					'url'	=>	'admin.php?page=results',
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
			if ($db->where('id',$id)->delete('exam_results')) {
				$data = array(
					'status'	=>	200,
					'message'	=>	'Result Deleted Successfully',
                    'url' => 'admin.php?page=school-results',
				);
			}else{
				$data = array(
					'status'	=>	201,
					'message'	=>	'Something went wrong'
				);
			}
		}
		
		if ($s == 'edit-score') {
			$id = __secure($_POST['id']);
			$insert = array(
				'score'	=>	__secure($_POST['pscore']),
				'date_modified' => date("Y-m-d H:i:s"),
			);
			$up = array(
				'status'	=>	3,
			);
			if (update_data('project_scores',$insert,'WHERE id = "'.$id.'"')) {
				update_data('project_scores',$up,'WHERE id = "'.__secure($id).'"');
				$data = array(
					'status' => 200,
					'message'	=>	'Project Score Updated Successfully',
					'url'	=>	'admin.php?page=projectresults',
				);
			}else{
				$data = array(
					'status' => 201,
					'message'	=>	'Something went wrong'
				);
			}
			
		}

		if ($s == 'approve') {
			$id = __secure($_POST['id']);
			$request = $db->where('id',$id)->getOne('requests');
				$insert = array(
					'status'	=>	2,
					'date_modified' => date('Y-m-d H:i:s')
				);
				if (update_data('project_scores',$insert,'WHERE id = "'.$id.'"')) {
					$data = array(
						'status' => 200,
						'message'	=>	'Request Approved Successfully',
						'url' => 'admin.php?page=projectresults',
					);
				}else{
					$data = array(
						'status' => 201,
						'message'	=>	'Something went wrong'
					);
				}
		}

		
		if ($s == 'filter-results') {
			$class_id = __secure($_POST['class_id']);
			if (!empty($class_id)) {
				$data = array(
					'status'	=>	200,
					'message'	=>	'Filtering S.'.$class_id.' Student Results',
                    'url' => 'admin.php?page=school-results&class='.$class_id.'',
				);
			}else{
				$data = array(
					'status'	=>	201,
					'message'	=>	'Please Select All Fields to filter Summary'
				);
			}
		}

		if ($s == 'filter-result-asign') {
			$class_id = __secure($_POST['class_id']);
			if (!empty($class_id)) {
				$data = array(
					'status'	=>	200,
					'message'	=>	'Filtering S.'.$class_id.' List',
                    'url' => 'admin.php?page=school-results&class='.$class_id.'',
				);
			}else{
				$data = array(
					'status'	=>	201,
					'message'	=>	'Please Select Class to Filter Summary'
				);
			}
		}
		
        
	}
?>