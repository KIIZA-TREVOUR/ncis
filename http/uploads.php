<?php 
	if ($f == 'uploads') {
		if ($s == 'upload-subject') {
			global $sqlConnect; // Assuming $sqlConnect is defined globally
		
			$fileName = $_FILES["subject_data"]["name"];
			// Remove the file extension from the filename
			$fileNameWithoutExtension = pathinfo($fileName, PATHINFO_FILENAME);
			$fileExtension = explode('.', $fileName);
			$fileExtension = strtolower(end($fileExtension));
			$newFileName = $fileNameWithoutExtension . " on " . date("h.i.sa") . "." . $fileExtension;
			$targetDirectory = "uploads/uploads/subjects/" . $newFileName;
			move_uploaded_file($_FILES['subject_data']['tmp_name'], $targetDirectory);
			require 'init/excel/excel_reader2.php';
			require 'init/excel/SpreadsheetReader.php';
		
			$reader = new SpreadsheetReader($targetDirectory);
			$dataImported = false; // A flag to check if data was imported
		
			foreach ($reader as $key => $row) {
				if($key==0){
					continue;
				}
				$name = $row[0];
				$code = $row[1];
				$upload_id = getLatestId('id','upload_history');
				// Check if the record already exists
				$exists = exists('subjects', 'WHERE name = "'.$name.'" AND code = "'.$code.'"');
				if (!$exists) {
					// Use prepared statements to prevent SQL injection
					$query = "INSERT INTO subjects (name, code, upload_id) VALUES (?, ?, ?)";
					// Prepare the statement
					$stmt = mysqli_prepare($sqlConnect, $query);
			
					if ($stmt) {
						mysqli_stmt_bind_param($stmt, "ssi", $name, $code, $upload_id);
			
						if (mysqli_stmt_execute($stmt)) {
							$dataImported = true;
						}
					}
				} else {
					// If the record already exists, skip insertion
					continue;
				}
			}
			$history = array(
				'file_name' => $fileNameWithoutExtension, // Use the filename without extension
				'file' => $targetDirectory,
				'uploaded_by' => $wallet['user']['id'],
				'category' => 'subjects',
			);
		
			// Save the upload history only once, after processing all rows
			if ($dataImported) {
				$history_query = "INSERT INTO upload_history (file_name, file, uploaded_by,category) VALUES (?, ?, ?,?)";
				$history_stmt = mysqli_prepare($sqlConnect, $history_query);
		
				if ($history_stmt) {
					mysqli_stmt_bind_param($history_stmt, "ssss", $history['file_name'], $history['file'], $history['uploaded_by'],$history['category']);
		
					if (mysqli_stmt_execute($history_stmt)) {
						$data = array(
							'status' => 200,
							'message' => 'Subject Data Imported Successfully',
							'url' => 'index.php?page=upload',
						);
					}
				}
			} else {
				$data = array(
					'status' => 201,
					'message' => 'Data Importation Failed',
					'url' => 'index.php?page=reports',
				);
			}
		
			// Close the statements
			mysqli_stmt_close($stmt);
			mysqli_stmt_close($history_stmt);
		}
		if ($s == 'upload-student') {
			global $sqlConnect; // Assuming $sqlConnect is defined globally
		
			$fileName = $_FILES["student_data"]["name"];
			// Remove the file extension from the filename
			$fileNameWithoutExtension = pathinfo($fileName, PATHINFO_FILENAME);
			$fileExtension = explode('.', $fileName);
			$fileExtension = strtolower(end($fileExtension));
			$newFileName = $fileNameWithoutExtension . " on " . date("h.i.sa") . "." . $fileExtension;
			$targetDirectory = "uploads/uploads/students/" . $newFileName;
			move_uploaded_file($_FILES['student_data']['tmp_name'], $targetDirectory);
			require 'init/excel/excel_reader2.php';
			require 'init/excel/SpreadsheetReader.php';
		
			$reader = new SpreadsheetReader($targetDirectory);
			$dataImported = false; // A flag to check if data was imported
		
			foreach ($reader as $key => $row) {
				if($key==0){
					continue;
				}
				$sch_id = $row[0];
				$firstname = $row[1];
				$lastname = $row[2];
				$email = $row[3];
				$year = $row[4];
				$lin = getStudentLinNumber($year);
				$image = $row[5];
				$class = $row[6];
				$dob = $row[7];
				$gender = $row[8];
				$password = md5('@'.$lin);
				$upload_id = getLatestId('id','upload_history');
		
				// Check if the record already exists
				$exists = exists('students', 'WHERE email = "'.$email.'"');
				if (!$exists) {
					// Use prepared statements to prevent SQL injection
					$query = "INSERT INTO students (sch_id, firstname, lastname, email, lin, image, class, dob, gender, password, upload_id,year) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";
		
					// Prepare the statement
					$stmt = mysqli_prepare($sqlConnect, $query);
		
					if ($stmt) {
						mysqli_stmt_bind_param($stmt, "isssssssssii", $sch_id, $firstname, $lastname, $email, $lin, $image, $class, $dob, $gender, $password, $upload_id,$year);
		
						if (mysqli_stmt_execute($stmt)) {
							$dataImported = true;
						}
					}
					$insert = array(
						'name' => $row[1]." ". $row['2'],
						'email' => $row[3],
						'password' => $password,
						'lin'=>$lin,
						'user_type' => 'student'
					); 
					save_data('users',$insert);
				} else {
					// If the record already exists, skip insertion
					continue;
				}
			}
		
			$history = array(
				'file_name' => $fileNameWithoutExtension, // Use the filename without extension
				'file' => $targetDirectory,
				'uploaded_by' => $wallet['user']['id'],
				'category' => 'students',
			);
		
			// Save the upload history only once, after processing all rows
			if ($dataImported) {
				$history_query = "INSERT INTO upload_history (file_name, file, uploaded_by,category) VALUES (?, ?, ?,?)";
				$history_stmt = mysqli_prepare($sqlConnect, $history_query);
		
				if ($history_stmt) {
					mysqli_stmt_bind_param($history_stmt, "ssss", $history['file_name'], $history['file'], $history['uploaded_by'], $history['category']);
		
					if (mysqli_stmt_execute($history_stmt)) {
						$data = array(
							'status' => 200,
							'message' => 'Student Data Imported Successfully',
							'url' => 'index.php?page=upload',
						);
					}
				}
			} else {
				$data = array(
					'status' => 201,
					'message' => 'All records already exist. No new data imported.',
					'url' => 'index.php?page=reports',
				);
			}
			
		}
		
		if ($s == 'upload-staff') {
			global $sqlConnect; // Assuming $sqlConnect is defined globally
		
			$fileName = $_FILES["staff_data"]["name"];
			$fileNameWithoutExtension = pathinfo($fileName, PATHINFO_FILENAME);
			$fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
			$newFileName = $fileNameWithoutExtension . " on " . date("h.i.sa") . "." . $fileExtension;
			$targetDirectory = "uploads/uploads/staff/" . $newFileName;
		
			if (move_uploaded_file($_FILES['staff_data']['tmp_name'], $targetDirectory)) {
				require 'init/excel/excel_reader2.php';
				require 'init/excel/SpreadsheetReader.php';
		
				$reader = new SpreadsheetReader($targetDirectory);
				$dataImported = false; // A flag to check if data was imported
		
				foreach ($reader as $key => $row) {
					if ($key == 0) {
						continue; // Skip the header row
					}
		
					$firstname = $row[0];
					$lastname = $row[1];
					$email = $row[2];
					$contact = $row[3];
					$role = $row[4];
					$password = md5('!' . $email);
					$upload_id = getLatestId('id', 'upload_history');
		
					// Check if the record already exists
					$exists = exists('staff', 'WHERE email = "' . $email . '"');
		
					if (!$exists) {
						// Use prepared statements to prevent SQL injection
						$query = "INSERT INTO staff (firstname, lastname, email, contact, role, password, upload_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
		
						// Prepare the statement
						if ($stmt = mysqli_prepare($sqlConnect, $query)) {
							mysqli_stmt_bind_param($stmt, "ssssssi", $firstname, $lastname, $email, $contact, $role, $password, $upload_id);
		
							if (mysqli_stmt_execute($stmt)) {
								$dataImported = true;
								mysqli_stmt_close($stmt); // Close the statement
							}
						}
					}
					
					
		
						// Insert data into 'users' table
						$insert = array(
							'name' => $firstname. " " . $lastname,
							'email' => $email,
							'password' => md5('!' . $email),
							'user_type' => 'staff'
						);
						save_data('users', $insert);
		
				}
		
				// Save the upload history only once, after processing all rows
				if ($dataImported) {
					$history = array(
						'file_name' => $fileNameWithoutExtension,
						'file' => $targetDirectory,
						'uploaded_by' => $wallet['user']['id'],
						'category' => 'students',
					);
					$history_query = "INSERT INTO upload_history (file_name, file, uploaded_by, category) VALUES (?, ?, ?, ?)";
					if ($history_stmt = mysqli_prepare($sqlConnect, $history_query)) {
						mysqli_stmt_bind_param($history_stmt, "ssss", $history['file_name'], $history['file'], $history['uploaded_by'], $history['category']);
		
						if (mysqli_stmt_execute($history_stmt)) {
							mysqli_stmt_close($history_stmt); // Close the statement
							$data = array(
								'status' => 200,
								'message' => 'Student Data Imported Successfully',
								'url' => 'index.php?page=upload',
							);
						}
					}
				} else {
					$data = array(
						'status' => 201,
						'message' => 'All records already exist. No new data imported.',
						'url' => 'index.php?page=reports',
					);
				}
			} else {
				// Handle file upload error
				$data = array(
					'status' => 500,
					'message' => 'Failed to upload file.',
					'url' => 'index.php?page=upload',
				);
			}
		}
		
		if ($s == 'upload-staffs') {
			global $sqlConnect; // Assuming $sqlConnect is defined globally
		
			$fileName = $_FILES["staff_data"]["name"];
			// Remove the file extension from the filename
			$fileNameWithoutExtension = pathinfo($fileName, PATHINFO_FILENAME);
			$fileExtension = explode('.', $fileName);
			$fileExtension = strtolower(end($fileExtension));
			$newFileName = $fileNameWithoutExtension . " on " . date("h.i.sa") . "." . $fileExtension;
			$targetDirectory = "uploads/uploads/staff/" . $newFileName;
			move_uploaded_file($_FILES['staff_data']['tmp_name'], $targetDirectory);
			require 'init/excel/excel_reader2.php';
			require 'init/excel/SpreadsheetReader.php';
            
			$reader = new SpreadsheetReader($targetDirectory);
			$dataImported = false; // A flag to check if data was imported
            foreach ($reader as $key => $row) {
				if($key==0){
					continue;
				}
				$firstname = $row[0];
				$lastname = $row[1];
				$email = $row[2];
				$contact = $row[3];
				$image = $row[4];
				$role = $row[5];
				$description = $row[6];
                $sch_id = $row[7];
				$password = $row[8];
				$upload_id = getLatestId('id','upload_history');
				// Check if the record already exists
				$exists = exists('staff', 'WHERE email = "'.$email.'"');
				if (!$exists) {
					// Use prepared statements to prevent SQL injection
					$query = "INSERT INTO staff (firstname,lastname,email,contact,image,role,description,sch_id,password,upload_id) VALUES (?,?,?,?,?,?,?,?,?,?)";
			
					// Prepare the statement
					$stmt = mysqli_prepare($sqlConnect, $query);
			
					if ($stmt) {
						mysqli_stmt_bind_param($stmt, "sssssssisi", $firstname,$lastname,$email,$contact,$image,$role,$description,$sch_id,$password,$upload_id);
			
						if (mysqli_stmt_execute($stmt)) {
							$dataImported = true;
						}
					}
				} else {
					// If the record already exists, skip insertion
					continue;
				}
			}
			$history = array(
				'file_name' => $fileNameWithoutExtension, // Use the filename without extension
				'file' => $targetDirectory,
				'uploaded_by' => $wallet['user']['id'],
				'category' => 'staff',
			);
		
			// Save the upload history only once, after processing all rows
			if ($dataImported) {
				$history_query = "INSERT INTO upload_history (file_name, file, uploaded_by,category) VALUES (?, ?, ?,?)";
				$history_stmt = mysqli_prepare($sqlConnect, $history_query);
		
				if ($history_stmt) {
					mysqli_stmt_bind_param($history_stmt, "ssss", $history['file_name'], $history['file'], $history['uploaded_by'],$history['category']);
		
					if (mysqli_stmt_execute($history_stmt)) {
						$data = array(
							'status' => 200,
							'message' => 'Staff Data Imported Successfully',
							'url' => 'index.php?page=upload',
						);
					}
				}
			} else {
				$data = array(
					'status' => 201,
					'message' => 'Data Importation Failed',
					'url' => 'index.php?page=reports',
				);
			}
		
		}
		
		if ($s == 'upload-results') {
			global $sqlConnect; // Assuming $sqlConnect is defined globally
		
			$fileName = $_FILES["results_data"]["name"];
			// Remove the file extension from the filename
			$fileNameWithoutExtension = pathinfo($fileName, PATHINFO_FILENAME);
			$fileExtension = explode('.', $fileName);
			$fileExtension = strtolower(end($fileExtension));
			$newFileName = $fileNameWithoutExtension . " on " . date("h.i.sa") . "." . $fileExtension;
			$targetDirectory = "uploads/uploads/results/" . $newFileName;
			move_uploaded_file($_FILES['results_data']['tmp_name'], $targetDirectory);
			require 'init/excel/excel_reader2.php';
			require 'init/excel/SpreadsheetReader.php';
            
			$reader = new SpreadsheetReader($targetDirectory);
			$dataImported = false; // A flag to check if data was imported
            foreach ($reader as $key => $row) {
				if($key==0){
					continue;
				}
				$student_lin = $row[0];
				$subject_code = $row[1];
				$score = $row[2];
				$exam_type = $row[3];
				$class_id = $row[4];
				$upload_id = getLatestId('id','upload_history');
				// Check if the record already exists
				$exists = exists('exam_results', 'WHERE student_lin = "'.$student_lin.'" AND subject_code = "'.$subject_code.'" AND exam_type = "'.$exam_type.'"');
				if (!$exists) {
					// Use prepared statements to prevent SQL injection
					$query = "INSERT INTO exam_results (student_lin,subject_code,score,exam_type,class_id,upload_id) VALUES (?,?,?,?,?,?)";
			
					// Prepare the statement
					$stmt = mysqli_prepare($sqlConnect, $query);
			
					if ($stmt) {
						mysqli_stmt_bind_param($stmt, "ssssii", $student_lin,$subject_code,$score,$exam_type,$class_id,$upload_id);
			
						if (mysqli_stmt_execute($stmt)) {
							$dataImported = true;
						}
					}
				} else {
					// If the record already exists, skip insertion
					continue;
				}
			}
			$history = array(
				'file_name' => $fileNameWithoutExtension, // Use the filename without extension
				'file' => $targetDirectory,
				'uploaded_by' => $wallet['user']['id'],
				'category' => 'results',
			);
		
			// Save the upload history only once, after processing all rows
			if ($dataImported) {
				$history_query = "INSERT INTO upload_history (file_name, file, uploaded_by,category) VALUES (?, ?, ?,?)";
				$history_stmt = mysqli_prepare($sqlConnect, $history_query);
		
				if ($history_stmt) {
					mysqli_stmt_bind_param($history_stmt, "ssss", $history['file_name'], $history['file'], $history['uploaded_by'],$history['category']);
		
					if (mysqli_stmt_execute($history_stmt)) {
						$data = array(
							'status' => 200,
							'message' => 'Results Data Imported Successfully',
							'url' => 'index.php?page=upload',
						);
					}
				}
			} else {
				$data = array(
					'status' => 201,
					'message' => 'Data Importation Failed',
					'url' => 'index.php?page=reports',
				);
			}
		
		}
		
		if ($s == 'upload-project-results') {
			global $sqlConnect; // Assuming $sqlConnect is defined globally
		
			$fileName = $_FILES["project_results_data"]["name"];
			// Remove the file extension from the filename
			$fileNameWithoutExtension = pathinfo($fileName, PATHINFO_FILENAME);
			$fileExtension = explode('.', $fileName);
			$fileExtension = strtolower(end($fileExtension));
			$newFileName = $fileNameWithoutExtension . " on " . date("h.i.sa") . "." . $fileExtension;
			$targetDirectory = "uploads/uploads/projectresults/" . $newFileName;
			move_uploaded_file($_FILES['project_results_data']['tmp_name'], $targetDirectory);
			require 'init/excel/excel_reader2.php';
			require 'init/excel/SpreadsheetReader.php';
            
			$reader = new SpreadsheetReader($targetDirectory);
			
			$dataImported = false; // A flag to check if data was imported
            foreach ($reader as $key => $row) {
				if($key==0){
					continue;
				}
				$student_lin = $row[0];
				$project_code = $row[1];
				$score = $row[2];
				$subject_code = $row[3];
				$upload_id = getLatestId('id','upload_history');
				// Check if the record already exists
				$exists = exists('project_scores', 'WHERE student_lin = "'.$student_lin.'"  AND project_code = "'.$project_code.'"');
				if (!$exists) {
					// Use prepared statements to prevent SQL injection
					$query = "INSERT INTO project_scores (student_lin,project_code,score,subject_code,upload_id) VALUES (?,?,?,?,?)";
			
					// Prepare the statement
					$stmt = mysqli_prepare($sqlConnect, $query);
			
					if ($stmt) {
						mysqli_stmt_bind_param($stmt, "ssssi", $student_lin,$project_code,$score,$subject_code,$upload_id);
			
						if (mysqli_stmt_execute($stmt)) {
							$dataImported = true;
						}
					}
				} else {
					// If the record already exists, skip insertion
					continue;
				}
			}
			$history = array(
				'file_name' => $fileNameWithoutExtension, // Use the filename without extension
				'file' => $targetDirectory,
				'uploaded_by' => $wallet['user']['id'],
				'category' => 'projectresults',
			);
		
			// Save the upload history only once, after processing all rows
			if ($dataImported) {
				$history_query = "INSERT INTO upload_history (file_name, file, uploaded_by,category) VALUES (?, ?, ?,?)";
				$history_stmt = mysqli_prepare($sqlConnect, $history_query);
		
				if ($history_stmt) {
					mysqli_stmt_bind_param($history_stmt, "ssss", $history['file_name'], $history['file'], $history['uploaded_by'],$history['category']);
		
					if (mysqli_stmt_execute($history_stmt)) {
						$data = array(
							'status' => 200,
							'message' => 'Project Results Data Imported Successfully',
							'url' => 'index.php?page=upload',
						);
					}
				}
			} else {
				$data = array(
					'status' => 201,
					'message' => 'Data Importation Failed',
					'url' => 'index.php?page=uploads',
				);
			}
		
		}
		
		if ($s == 'remove') {
			$id = __secure($_POST['id']);
			$filepath = $db->where('id',$id)->getOne('upload_history');
			if ($db->where('id',$id)->delete('upload_history')) {
				$db->where('upload_id',$id)->delete('subjects');
				$db->where('upload_id',$id)->delete('staff');
				$db->where('upload_id',$id)->delete('students');
				$db->where('upload_id',$id)->delete('exam_results');
				if(exists('upload_history','WHERE file ="'.$filepath->file.'"')){
					unlink($filepath->file);
				}
				$data = array(
					'status'	=>	200,
					'message'	=>	'Data Related to this Upload Deleted Successfully'
				);
			}else{
				$data = array(
					'status'	=>	201,
					'message'	=>	'Something went wrong'
				);
			}
		}

		
		if ($s == 'clean-subjects') {
			$name = __secure($_POST['name']);
			if ($db->where('name',$name)->delete('subjects')) {
				$data = array(
					'status'	=>	200,
					'message'	=>	'Data Cleaned Successfully'
				);
			}else{
				$data = array(
					'status'	=>	201,
					'message'	=>	'Data Cleaning Failed'
				);
			}
		}
		
		if ($s == 'clean-results') {
			$subject_code = __secure($_POST['subject_code']);
			if ($db->where('subject_code',$subject_code)->delete('exam_results')) {
				$data = array(
					'status'	=>	200,
					'message'	=>	'Data Cleaned Successfully'
				);
			}else{
				$data = array(
					'status'	=>	201,
					'message'	=>	'Data Cleaning Failed'
				);
			}
		}
		if ($s == 'clean-staff') {
			$email = __secure($_POST['email']);
			if ($db->where('email',$email)->delete('staff')) {
				$data = array(
					'status'	=>	200,
					'message'	=>	'Data Cleaned Successfully'
				);
			}else{
				$data = array(
					'status'	=>	201,
					'message'	=>	'Data Cleaning Failed'
				);
			}
		}
		if ($s == 'clean-students') {
			$lin = __secure($_POST['lin']);
			if ($db->where('lin',$lin)->delete('students')) {
				$data = array(
					'status'	=>	200,
					'message'	=>	'Data Cleaned Successfully'
				);
			}else{
				$data = array(
					'status'	=>	201,
					'message'	=>	'Data Cleaning Failed'
				);
			}
		}

		
	}
?>