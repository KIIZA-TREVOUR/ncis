<?php 
	error_reporting(E_ERROR | E_PARSE);
    require('init/controller.php');
    require('init/excel/excel_reader2.php');
    require('init/excel/SpreadsheetReader.php');
	
	date_default_timezone_set('Africa/Kampala');

	if (!empty($_GET)) {
	    foreach ($_GET as $key => $value) {
	        $value = preg_replace('/on[^<>=]+=[^<>]*/m', '', $value);
	        $value = preg_replace('/\((.*?)\)/m', '', $value);
	        $_GET[$key] = strip_tags($value);
	    }
	}
	if (!empty($_REQUEST)) {
	    foreach ($_REQUEST as $key => $value) {
	        $value = preg_replace('/on[^<>=]+=[^<>]*/m', '', $value);
	        $_REQUEST[$key] = strip_tags($value);
	    }
	}
	if (!empty($_POST)) {
	    foreach ($_POST as $key => $value) {
	        $value = preg_replace('/on[^<>=]+=[^<>]*/m', '', $value);
	        $_POST[$key] = strip_tags($value);
	    }
	}

	$page = 'index';
	
	if (!empty($_GET['page'])) {
		$page = __secure($_GET['page']);
	}
	$content  = load_page($page);


?>
<!DOCTYPE html>
<html lang="en">
  
   <head>
      <meta charset="utf-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
      <link rel="icon" href="<?=$wallet['config']['site_url'] ?>layout/assets/img/favicon.png" type="image/png" />
      <title><?=$wallet['config']['site_name'] ?></title>
      <link rel="stylesheet" href="<?=$wallet['config']['site_url'] ?>layout/assets/css/bootstrap.css" />
      <link rel="stylesheet" href="<?=$wallet['config']['site_url'] ?>layout/assets/css/flaticon.css" />
      <link rel="stylesheet" href="<?=$wallet['config']['site_url'] ?>layout/assets/css/themify-icons.css" />
      <link rel="stylesheet" href="<?=$wallet['config']['site_url'] ?>layout/assets/vendors/owl-carousel/owl.carousel.min.css" />
      <link rel="stylesheet" href="<?=$wallet['config']['site_url'] ?>layout/assets/vendors/nice-select/css/nice-select.css" />
      <link rel="stylesheet" href="<?=$wallet['config']['site_url'] ?>layout/assets/css/style.css" />
      <link rel="stylesheet" href="<?=$wallet['config']['site_url'] ?>layout/assets/css/mdb.min.css" />
   </head>
   <style>
    /* Custom Styles */
    body {
      background-color: #f8f9fa; /* Background color for the whole page */
    }
    .report-card {
      margin: 20px auto;
      max-width: 800px;
      background-color: #fff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      position: relative;
    }
    .school-badge {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      opacity: 0.2;
      z-index: 0;
    }
    .student-info {
      position: relative;
      z-index: 1;
    }
    .grade {
      font-size: 24px;
      font-weight: bold;
    }
  </style>
   <body>


<?= load_page('partials/header')?>

<?=$content?>

<!--body content end--> 

<?= load_page('partials/footer')?>


</div>

<!-- page wrapper end -->


<script src="<?=$wallet['config']['site_url'] ?>layout/assets/js/jquery-3.2.1.min.js"></script>
      <script src="<?=$wallet['config']['site_url'] ?>layout/assets/js/popper.js"></script>
      <script src="<?=$wallet['config']['site_url'] ?>layout/assets/js/bootstrap.min.js"></script>
      <script src="<?=$wallet['config']['site_url'] ?>layout/assets/vendors/nice-select/js/jquery.nice-select.min.js"></script>
      <script src="<?=$wallet['config']['site_url'] ?>layout/assets/vendors/owl-carousel/owl.carousel.min.js"></script>
      <script src="<?=$wallet['config']['site_url'] ?>layout/assets/js/owl-carousel-thumb.min.js"></script>
      <script src="<?=$wallet['config']['site_url'] ?>layout/assets/js/jquery.ajaxchimp.min.js"></script>
      <script src="<?=$wallet['config']['site_url'] ?>layout/assets/js/mail-script.js"></script>
      <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCjCGmQ0Uq4exrzdcL6rvxywDDOvfAu6eE"></script>
      <script src="<?=$wallet['config']['site_url'] ?>layout/assets/js/gmaps.min.js"></script>
      <script src="<?=$wallet['config']['site_url'] ?>layout/assets/js/theme.js"></script>
      <script src="<?=$wallet['config']['site_url'] ?>layout/assets/js/mdb.min.js"></script>
      <script async src="https://www.googletagmanager.com/gtag/js?id=UA-23581568-13"></script>
      <script>
         window.dataLayer = window.dataLayer || [];
         function gtag(){dataLayer.push(arguments);}
         gtag('js', new Date());
         
         gtag('config', 'UA-23581568-13');
      </script>
      <script defer src="https://static.cloudflareinsights.com/beacon.min.js/v2b4487d741ca48dcbadcaf954e159fc61680799950996" integrity="sha512-D/jdE0CypeVxFadTejKGTzmwyV10c1pxZk/AqjJuZbaJwGMyNHY3q/mTPWqMUnFACfCTunhZUVcd4cV78dK1pQ==" data-cf-beacon='{"rayId":"7b9f605b5b1d051d","version":"2023.3.0","b":1,"token":"cd0b4b3a733644fc843ef0b185f98241","si":100}' crossorigin="anonymous"></script>
   </body>
</html>
<?php

	mysqli_close($sqlConnect);
	unset($wallet);
?>