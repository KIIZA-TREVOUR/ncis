<?php 
	if ($f == 'project') {
		if ($s == 'assign') {
			if(!empty(isset($_POST['project']) || !empty(isset($_POST['score'])))){
				$insert = array(
					'student_lin'	=>	__secure($_POST['student_lin']),
					'project_id'	=>	__secure($_POST['project']),
					'score'	=>	    __secure($_POST['marks']),
				);
				if(save_data('project_scores',$insert)){
					$data = array(
						'status'	=>	200,
						'message'	=>	'Student Assigned Project Marks',
						'url' => 'admin.php?page=assign-pmarks&lin='.__secure($_POST['student_lin']),
					);
				}elseif(exists('project_scores', "WHERE student_lin = '" .$_POST['student_lin']. "'AND project_id ='".$_POST['project']."' ")) {
					$data = array(
						'status'	=>	201,
						'message'	=>	'Student Already Assigned Project Marks',
						'url' => 'admin.php?page=projectresults&class='.__secure($_POST['class']).'',
					);
				}

			}else{
				$data = array(
					'status'	=>	201,
					'message'	=>	'Please Select a Project and Assign marks',
					'url' => 'admin.php?page=assign-results',
				);
			}
        }
	}