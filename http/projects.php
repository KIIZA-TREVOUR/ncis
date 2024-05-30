<?php 
	if ($f == 'projects') {
		if ($s == 'new') {
			$insert = array(
				'name'	=>	__secure($_POST['name']),
				'description'	=>	mysqli_real_escape_string($sqlConnect,$_POST['description']),
				'subject_code'	=>	__secure($_POST['subject_code']),
				'class_id'	=>	__secure($_POST['class_id']),
				'term'	=>	__secure($_POST['term']),
				'year'	=>	__secure($_POST['year']),
				'project_code'	=>	generateProjectCode(__secure($_POST['year']), __secure($_POST['subject_code'])),
				'project_type'	=>	__secure($_POST['project_type']),
			);
			if(exists('projects','WHERE name = "'.__secure($_POST['name']).'" AND subject_code ="'.__secure($_POST['subject_code']).'" AND class_id = "'.__secure($_POST['class_id']).'"')){
				$data = array(
					'status'	=>	201,
					'message'	=>	'Project Already Exists'
				);
			}else{
				if (save_data('projects',$insert)) {
					$data = array(
						'status'	=>	200,
						'message'	=>	'Project Added successfully',
						'url'	=>	'admin.php?page=projects',
					);
				}else{
					$data = array(
						'status'	=>	201,
						'message'	=>	'Something went wrong'
					);
				}
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
				'name'	=>	__secure($_POST['name']),
				'description'	=>	mysqli_real_escape_string($sqlConnect,$_POST['description']),
				'subject_code'	=>	__secure($_POST['subject_code']),
				'class_id'	=>	__secure($_POST['class_id']),
				'term'	=>	__secure($_POST['term']),
				'project_type'	=>	__secure($_POST['project_type']),
			);
			if(exists('projects','WHERE name = "'.__secure($_POST['name']).'" AND subject_code ="'.__secure($_POST['subject_code']).'" AND class_id = "'.__secure($_POST['class_id']).'"')){
				$data = array(
					'status'	=>	201,
					'message'	=>	'Project Already Exists'
				);
			}else{
				if (update_data('projects',$insert,'WHERE id = "'.$id.'"')) {
					$data = array(
						'status' => 200,
						'message'	=>	'Project Updated Successfully',
						'url'	=>	'admin.php?page=projects',
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
			$project_code = __secure($_POST['project_code']);
			if ($db->where('project_code',$project_code)->delete('projects')) {
				$db->where('project_code',$project_code)->delete('project_scores');
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
			// $request = $db->where('id',$id)->getOne('requests');
				$insert = array(
					'status'	=>	2,
					'date_modified' => date('Y-m-d H:i:s')
				);
				if (update_data('project_scores',$insert,'WHERE id = "'.$id.'"')) {
					$data = array(
						'status' => 200,
						'message'	=>	'Request Approved Successfully',
						'url' => 'admin.php?page=requests',
					);
				}else{
					$data = array(
						'status' => 201,
						'message'	=>	'Something went wrong'
					);
				}
		}

		if ($s == 'reject') {
			$id = __secure($_POST['id']);
			$rid = __secure($_POST['rid']);
			// $request = $db->where('id',$id)->getOne('requests');
				$insert = array(
					'status'	=>	0,
					'date_modified' => date('Y-m-d H:i:s')
				);
				if (update_data('project_scores',$insert,'WHERE id = "'.$id.'"')) {
					$db->delete('requests','WHERE id = "'.$rid.'"');
					$data = array(
						'status' => 200,
						'message'	=>	'Request Rejected Successfully',
						'url' => 'admin.php?page=requests',
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
			$subject_code = __secure($_POST['subject_code']);
			$term = __secure($_POST['term']);
			if (!empty($class_id)) {
				$data = array(
					'status'	=>	200,
					'message'	=>	'Filtering S.'.$class_id. ' '.$subject_code.' Project Results',
                    'url' => 'admin.php?page=projectresults&class='.$class_id.'&subject='.$subject_code.'&term='.$term,
				);
			}else{
				$data = array(
					'status'	=>	201,
					'message'	=>	'Please Select All Fields to filter Summary'
				);
			}
		}
		if ($s == 'filter-results-lin') {
			$lin = __secure($_POST['student_lin']);
			if (!empty($lin)) {
				$data = array(
					'status'	=>	200,
					'message'	=>	'Filtering Results for '.$lin,
                    'url' => 'admin.php?page=editpresults&lin='.$lin,
				);
			}else{
				$data = array(
					'status'	=>	201,
					'message'	=>	'Please Select All Fields to filter Summary'
				);
			}
		}
		if ($s == 'filter-results-edit') {
			$class_id = __secure($_POST['class_id']);
			$subject_code = __secure($_POST['subject_code']);
			if (!empty($class_id)) {
				$data = array(
					'status'	=>	200,
					'message'	=>	'Filtering S.'.$class_id. ' '.$subject_code.' Project Results',
                    'url' => 'admin.php?page=editpresults&class='.$class_id.'&subject='.$subject_code,
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
                    'url' => 'admin.php?page=assign-results&class='.$class_id.'',
				);
			}else{
				$data = array(
					'status'	=>	201,
					'message'	=>	'Please Select Class to Filter Summary'
				);
			}
		}
		if ($s == 'assign') {
			if (!empty($_POST['project']) && !empty($_POST['marks'])) {
				$project = $db->where('project_code', __secure($_POST['project']))->getOne('projects');
		
				if ($project) { // Check if the project is found
					$studentLin = __secure($_POST['student_lin']);
					$subjectCode = $project->subject_code;
		
					// Check if the student is assigned to the subject
					$studentSubjects = $db->where('student_lin', $studentLin)->getOne('student_subject');
					$subjectCodes = explode(',', $studentSubjects->subject_code);
		
					if (in_array($subjectCode, $subjectCodes)) {
						$insert = array(
							'student_lin'   => $studentLin,
							'project_code'  => __secure($_POST['project']),
							'subject_code'  => $subjectCode,
							'score'         => __secure($_POST['marks']),
						);
		
						$exists = exists('project_scores', 'WHERE project_code ="' . __secure($_POST['project']) . '" AND subject_code ="' . $subjectCode . '" AND student_lin = "' . $studentLin . '"');
		
						if ($exists) {
							$data = array(
								'status'    => 201,
								'message'   => 'Student Already Assigned Project Marks',
								'url'       => 'admin.php?page=projectresults&class=' . __secure($_POST['class']),
							);
						} else {
							save_data('project_scores', $insert);
							$data = array(
								'status'    => 200,
								'message'   => 'Student Assigned Project Marks',
								'url'       => 'admin.php?page=assign-pmarks&lin=' . $studentLin,
							);
						}
					} else {
						$data = array(
							'status'    => 403,
							'message'   => 'Student is not assigned to the subject of the selected project',
							'url'       => 'admin.php?page=assign-results',
						);
					}
				} else {
					$data = array(
						'status'    => 404,
						'message'   => 'Project not found',
						'url'       => 'admin.php?page=assign-results',
					);
				}
			} else {
				$data = array(
					'status'    => 201,
					'message'   => 'Please Select a Project and Assign Marks',
					'url'       => 'admin.php?page=assign-results',
				);
			}
		}
		
		
		
        
	}
?>