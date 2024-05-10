<?php 
	if ($f == 'testimonials') {
		if ($s == 'new') {
			$url = '';
			$allowTypes = array('image/bmp', 'image/jpeg', 'image/x-png', 'image/png', 'image/gif');
			if (!empty($_FILES['image'])) {
				if (in_array($_FILES['image']['type'], $allowTypes)) {
					$url = share_file('image','uploads/testimonials/',true,80,80);
				}
			}
			$insert = array(
				'name'	=>	__secure($_POST['name']),
				'location'	=>	__secure($_POST['location']),
				'image'	=>	__secure($url),
				'description'	=>	mysqli_real_escape_string($sqlConnect,$_POST['description']),
			);
			if (save_data('front_cms_testimonial',$insert)) {
				$data = array(
					'status'	=>	200,
					'message'	=>	'Testimonials  added successfully'
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
			$testimonial = $db->where('id',$id)->getOne('front_cms_testimonial');

			$url = __secure($_POST['uimage']);
			$allowTypes = array('image/bmp', 'image/jpeg', 'image/x-png', 'image/png', 'image/gif');
			if (!empty($_FILES['image'])) {
				if (in_array($_FILES['image']['type'], $allowTypes)) {
					$url = share_file('image','uploads/testimonials/',true,80,80);
					unlink($wallet['config']['site_url'].__secure($_POST['uimage']));
				}
			}
			$insert = array(
				'name'	=>	__secure($_POST['name']),
				'location'	=>	__secure($_POST['location']),
				'image'	=>	__secure($url),
				'description'	=>	mysqli_real_escape_string($sqlConnect,$_POST['description']),
			);
			if (update_data('front_cms_testimonial',$insert,'WHERE id = "'.$id.'"')) {
				$data = array(
					'status' => 200,
					'message'	=>	'Testimonial Updated Successfully',
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
			if ($db->where('id',$id)->delete('front_cms_testimonial')) {
				$data = array(
					'status'	=>	200,
					'message'	=>	'Testimonial Deleted Successfully'
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