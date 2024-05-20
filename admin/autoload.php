<?php
   // Turn off error reporting
   error_reporting(0);
   
   if (!empty($wallet['user'])) {
       $page = 'dashboard';
   } elseif (!empty($wallet['staff'])) {
       $page = 'staff-dashboard';
   } else {
       $page = 'sdashboard';
   }
   
   if (!$wallet['loggedin'] && @$_GET['page'] != 'login') {
       if (@$_GET['page'] == 'forgot') {
           $page = 'forgot';
         } elseif (@$_GET['page'] == 'reset') {
             $page = 'reset';
         } elseif (@$_GET['page'] == 'register-school') {
             $page = 'register-school';
       } else {
           $page = 'login';
       }
   }
   
   // if ($wallet['loggedin'] && $wallet['user']['user_type'] != 'admin') {
   // $page = 'login';
   // }
   
   if (!empty($_GET['page']) && $wallet['loggedin']) {
       $page = __secure($_GET['page'], 0);
   }
   
   $page_loaded = LoadAdminPage("$page");
   
   $mode = 'day';
   if (!empty($_COOKIE['mode']) && $_COOKIE['mode'] == 'night') {
       $mode = 'night';
   }
   ?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
      <title><?= $wallet['config']['site_name'] ?></title>
      <!-- General CSS Files -->
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
      <link rel="stylesheet" href="<?= $wallet['config'][
         'site_url'
         ] ?>admin/assets/css/app.min.css">
      <!-- Template CSS -->
      <link rel="stylesheet" href="<?= $wallet['config'][
         'site_url'
         ] ?>admin/assets/css/style.css">
      <link rel="stylesheet" href="<?= $wallet['config'][
         'site_url'
         ] ?>admin/assets/css/components.css">
      <!-- Custom style CSS -->
      <link rel="stylesheet" href="<?= $wallet['config'][
         'site_url'
         ] ?>admin/assets/css/custom.css">
      <link rel='shortcut icon' type='image/x-icon'
         href='<?= $wallet['config'][
            'site_url'
            ] ?>layout/assets/images/favicon.png' />
      <link rel="stylesheet" href="<?= $wallet['config'][
         'site_url'
         ] ?>admin/assets/bundles/izitoast/css/iziToast.min.css">
      <script src="<?= $wallet['config'][
         'site_url'
         ] ?>admin/assets/bundles/izitoast/js/iziToast.min.js"></script>
      <link rel="stylesheet" href="<?= $wallet['config'][
         'site_url'
         ] ?>admin/assets/bundles/summernote/summernote-bs4.css">
      <link rel="stylesheet"
         href="<?= $wallet['config'][
            'site_url'
            ] ?>admin/assets/bundles/bootstrap-tagsinput/dist/bootstrap-tagsinput.css">
      <link rel="stylesheet" href="<?= $wallet['config'][
         'site_url'
         ] ?>admin/assets/bundles/jquery-selectric/selectric.css">
      <link rel="stylesheet"
         href="<?= $wallet['config'][
            'site_url'
            ] ?>admin/assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css">
      <!-- General JS Scripts -->
      <script src="<?= $wallet['config'][
         'site_url'
         ] ?>admin/assets/js/app.min.js"></script>
      <script type="text/javascript">
         function request() {
         return "<?php echo $wallet['config']['site_url'] . 'request.php'; ?>"
         }
      </script>
      <script
         src="<?= $wallet['config'][
            'site_url'
            ] ?>admin/assets/bundles/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js"></script>
      <script src="<?= $wallet['config'][
         'site_url'
         ] ?>admin/assets/bundles/summernote/summernote-bs4.js"></script>
      <script src="<?= $wallet['config'][
         'site_url'
         ] ?>admin/assets/bundles/jquery-selectric/jquery.selectric.min.js"></script>
      <!-- JS Libraies -->
      <script src="<?= $wallet['config'][
         'site_url'
         ] ?>admin/assets/bundles/apexcharts/apexcharts.min.js"></script>
      <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.0.1/min/dropzone.min.css" rel="stylesheet">
      <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.2.0/min/dropzone.min.js"></script>
      <style type="text/css">
         .gap-line-container {
            display: flex; /* Use flexbox to align items */
            align-items: center; /* Align items vertically */
        }
        .gap-line {
            flex-grow: 1; /* Allow the gap line to grow to fill available space */
            border-bottom: 1px solid black; /* Solid black line */
            margin-bottom: 10px; /* Margin for spacing */
        }
        .sgap-line {
            flex-grow: 1; /* Allow the gap line to grow to fill available space */
            border-bottom: 1px solid black; /* Solid black line */
            margin-bottom: 10px; /* Margin for spacing */
            width: 20px;
        }
        .lin-label {
            font-weight: bold;
            margin-right: 10px; /* Add margin to separate from gap line */
        }
        .bordered {
            border: 2px solid black; 
            border-radius: 10px; 
            border-color:green;
         }            
         .border {
            border: 4px red; /* Add border around the div */
            border-radius: 10px; 
         }
         @media print {
               .gap-line-container {
               display: flex; /* Use flexbox to align items */
               align-items: center; /* Align items vertically */
         }
         .gap-line {
               flex-grow: 1; /* Allow the gap line to grow to fill available space */
               border-bottom: 1px solid black; /* Solid black line */
               margin-bottom: 10px; /* Margin for spacing */
         }
         .sgap-line {
               flex-grow: 1; /* Allow the gap line to grow to fill available space */
               border-bottom: 1px solid black; /* Solid black line */
               margin-bottom: 10px; /* Margin for spacing */
               width: 20px;
         }
         .lin-label {
               font-weight: bold;
               margin-right: 10px; /* Add margin to separate from gap line */
         }
         .bordered {
               border: 2px solid black; 
               border-radius: 10px; 
               border-color:green;
            }            
            .border {
               border: 4px red; /* Add border around the div */
               border-radius: 10px; 
            }
         }
      </style>
   </head>
   <body>
      <div class="loader"></div>
      <div id="app">
         <?php if ($page == 'login' || $page == 'forgot' || $page == 'reset'|| $page == 'register-school'):
            echo $page_loaded;
            else:
             ?>
         <?php if (!empty($wallet['user'])): ?>
         <div class="main-wrapper main-wrapper-1">
            <div class="navbar-bg"></div>
            <nav class="navbar navbar-expand-lg main-navbar sticky">
               <div class="form-inline mr-auto">
                  <ul class="navbar-nav mr-3">
                     <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg
                        collapse-btn"> <i data-feather="align-justify"></i></a></li>
                     <li><a href="#" class="nav-link nav-link-lg fullscreen-btn">
                        <i data-feather="maximize"></i>
                        </a>
                     </li>
                     <li>
                        <form class="form-inline mr-auto">
                           <div class="search-element">
                              <input class="form-control" type="search" placeholder="Search" aria-label="Search"
                                 data-width="200">
                              <button class="btn" type="submit">
                              <i class="fas fa-search"></i>
                              </button>
                           </div>
                        </form>
                     </li>
                  </ul>
               </div>
               <ul class="navbar-nav navbar-right">
                  <li class="dropdown dropdown-list-toggle">
                     <a href="#" data-toggle="dropdown" class="nav-link nav-link-lg message-toggle"><i data-feather="mail"></i> 
                     <?php
                        $count = $db
                            ->where('status', 0)
                            ->getValue('front_cms_messages', 'count(*)');
                        if ($count > 0): ?>                           
                     <span class="badge headerBadge1">
                     <?= $count ?> </span> 
                     <?php endif;
                        ?>
                     </a>
                     <div class="dropdown-menu dropdown-list dropdown-menu-right pullDown">
                        <div class="dropdown-header">
                           Messages
                           <div class="float-right">
                              <a href="javascript:void(0)" class="mark-all">Mark All As Read</a>
                           </div>
                        </div>
                        <div class="dropdown-list-content dropdown-list-message">
                           <?php foreach (
                              $db
                                  ->orderBy('id', 'desc')
                                  ->get('front_cms_messages')
                              as $key => $value
                              ): ?>
                           <a href="admin.php?page=message_details&id=<?= $value->id ?>" class="dropdown-item"> 
                           <span class="dropdown-item-avatar text-white"> 
                           <img alt="image" src="<?= $wallet['config']['site_url'] ?>layout/assets/images/avatar-place.png" class="rounded-circle">
                           </span> 
                           <span class="dropdown-item-desc"> 
                           <span class="message-user"><?= $value->name ?></span>
                           <span class="time messege-text"><?= short_text(
                              $value->message,
                              50
                              ) ?></span>
                           <span class="time"><?= date(
                              'l d/m/Y',
                              strtotime($value->date_created)
                              ) ?></span>
                           </span>
                           </a> 
                           <?php endforeach; ?>
                        </div>
                        <div class="dropdown-footer text-center">
                           <a href="#">View All <i class="fas fa-chevron-right"></i></a>
                        </div>
                     </div>
                  </li>
                  <li class="dropdown dropdown-list-toggle">
                     <a href="#" data-toggle="dropdown"
                        class="nav-link notification-toggle nav-link-lg"><i data-feather="bell" class="bell"></i>
                     </a>
                     <div class="dropdown-menu dropdown-list dropdown-menu-right pullDown">
                        <div class="dropdown-header">
                           Notifications
                           <div class="float-right">
                              <a href="#">Mark All As Read</a>
                           </div>
                        </div>
                        <div class="dropdown-list-content dropdown-list-icons">
                           <a href="#" class="dropdown-item dropdown-item-unread"> 
                           <span class="dropdown-item-icon bg-primary text-white"> 
                           <i class="fas fa-code"></i>
                           </span> 
                           <span class="dropdown-item-desc"> 
                           System update is available now! <span class="time">2 Min Ago</span>
                           </span>
                           </a> 
                        </div>
                        <div class="dropdown-footer text-center">
                           <a href="#">View All <i class="fas fa-chevron-right"></i></a>
                        </div>
                     </div>
                  </li>
                  <li class="dropdown">
                     <a href="#" data-toggle="dropdown"
                        class="nav-link dropdown-toggle nav-link-lg nav-link-user"> 
                     <img alt="image"  src="<?= $wallet['config']['site_url'] .$wallet['user']['image'] ?>" class="user-img-radious-style" onerror="this.onerror=null;this.src='<?= $wallet['config']['site_url'] ?>layout/assets/img/avatar.png'"> <span
                        class="d-sm-none d-lg-inline-block"></span></a>
                     <div class="dropdown-menu dropdown-menu-right pullDown">
                        <div class="dropdown-title">Hello <?= $wallet['user']['lastname'] ?></div>
                        <a href="#" class="dropdown-item has-icon"> 
                        <i class="far fa-user"></i> Profile
                        </a> 
                        <!-- <div class="dropdown-divider"></div> -->
                        <a href="admin.php?page=logout" class="dropdown-item has-icon text-danger"> <i
                           class="fas fa-sign-out-alt"></i>
                        Logout
                        </a>
                     </div>
                  </li>
               </ul>
            </nav>
            <div class="main-sidebar sidebar-style-2">
               <aside id="sidebar-wrapper">
                  <div class="sidebar-brand">
                     <a href="admin.php"> <img alt="image"
                        src="<?= $wallet['config']['site_url'].$wallet['config']['logo'] ?>"
                        class="header-logo" onerror = "this.onerror=null;this.src='<?= $wallet['config']['site_url'] ?>layout/assets/img/sms.png'" />
                     </a>
                  </div>
                  <ul class="sidebar-menu">
                     <li class="menu-header">Main</li>
                     <li class="dropdown <?php if($page == 'dashboard' ){echo 'active';}?>">
                        <a href="admin.php?page=dashboard" class="nav-link"><i
                           data-feather="monitor"></i><span>Dashboard</span></a>
                     </li>
                     <li class="dropdown <?php if($page == 'admins' || $page == 'new-admin'|| $page == 'edit-admin'){echo 'active';}?>">
                        <a href="#" class="menu-toggle nav-link has-dropdown"><i
                           data-feather="users"></i><span>Admins</span></a>
                        <ul class="dropdown-menu">
                           <li><a class="nav-link" href="admin.php?page=new-admin">New Admin</a></li>
                           <li><a class="nav-link" href="admin.php?page=admins">All Admins</a></li>
                        </ul>
                     </li>
                     <li class="dropdown <?php if($page == 'staff' || $page == 'new-staff'|| $page == 'edit-staff'){echo 'active';}?>">
                        <a href="#" class="menu-toggle nav-link has-dropdown"><i
                           data-feather="users"></i><span>Staff</span></a>
                        <ul class="dropdown-menu">
                           <li><a class="nav-link" href="admin.php?page=new-staff">New Staff</a></li>
                           <li><a class="nav-link" href="admin.php?page=staff">Staff List</a></li>
                        </ul>
                     </li>
                     <li class="dropdown <?php if($page == 'students' || $page == 'new-student'|| $page == 'edit-student'){echo 'active';}?>">
                        <a href="#" class="menu-toggle nav-link has-dropdown"><i
                           data-feather="users"></i><span>Students</span></a>
                        <ul class="dropdown-menu">
                           <li><a class="nav-link" href="admin.php?page=new-student">New Student</a></li>
                           <li><a class="nav-link" href="admin.php?page=students">All Students</a></li>
                        </ul>
                     </li>
                     <li class="dropdown <?php if($page == 'subjects' || $page == 'new-subject'|| $page == 'edit-subject'){echo 'active';}?>">
                        <a href="#" class="menu-toggle nav-link has-dropdown"><i
                           data-feather="users"></i><span>Subjects</span></a>
                        <ul class="dropdown-menu">
                           <li><a class="nav-link" href="admin.php?page=new-subject">New Subject</a></li>
                           <li><a class="nav-link" href="admin.php?page=subjects">All Subjects</a></li>
                        </ul>
                     </li>
                     <li class="dropdown <?php if($page == 'requests' || $page == 'new-request'|| $page == 'edit-request'){echo 'active';}?>">
                        <a href="#" class="menu-toggle nav-link has-dropdown"><i
                           data-feather="users"></i><span>Requests</span></a>
                        <ul class="dropdown-menu">
                           <li><a class="nav-link" href="admin.php?page=requests">All Requests</a></li>
                        </ul>
                     </li>
                     <li class="dropdown <?php if($page == 'classes' || $page == 'new-class'|| $page == 'edit-class'){echo 'active';}?>">
                        <a href="#" class="menu-toggle nav-link has-dropdown"><i
                           data-feather="users"></i><span>Classes</span></a>
                        <ul class="dropdown-menu">
                           <li><a class="nav-link" href="admin.php?page=new-class">New Class</a></li>
                           <li><a class="nav-link" href="admin.php?page=classes">All Classes</a></li>
                        </ul>
                     </li>
                     <li class="dropdown <?php if($page == 'projects' || $page == 'new-project'|| $page == 'edit-project'){echo 'active';}?>">
                        <a href="#" class="menu-toggle nav-link has-dropdown"><i
                           data-feather="users"></i><span>Projects</span></a>
                        <ul class="dropdown-menu">
                           <li><a class="nav-link" href="admin.php?page=new-project">New Project</a></li>
                           <li><a class="nav-link" href="admin.php?page=projects">All Projects</a></li>
                        </ul>
                     </li>
                     <li class="dropdown <?php if($page == 'school-results' || $page == 'assign-results'|| $page == 'projectresults'|| $page ==  'individual-presults'|| $page ==  'editpresults'){echo 'active';}?>">
                        <a href="#" class="menu-toggle nav-link has-dropdown"><i
                           data-feather="users"></i><span>Manage Results</span></a>
                        <ul class="dropdown-menu">
                           <li><a class="nav-link" href="admin.php?page=school-results">All Results</a></li>
                           <li><a class="nav-link" href="admin.php?page=assign-results">Assign Project Results</a></li>
                           <li><a class="nav-link" href="admin.php?page=projectresults">All Project Results</a></li>
                           <li><a class="nav-link" href="admin.php?page=editpresults">Edit Project Results</a></li>
                        </ul>
                     </li>
                     <li class="dropdown <?php if($page == 'student-subjects'){echo 'active';}?>">
                        <a href="#" class="menu-toggle nav-link has-dropdown"><i
                           data-feather="users"></i><span>Student Subjects</span></a>
                        <ul class="dropdown-menu">
                           <li><a class="nav-link" href="admin.php?page=student-subjects">All Student Subjects</a></li>
                        </ul>
                     </li>
                     <li class="dropdown <?php if($page == 'uploads' || $page == 'upload-history'){echo 'active';}?>">
                        <a href="#" class="menu-toggle nav-link has-dropdown"><i
                           data-feather="users"></i><span>ManageUploads</span></a>
                        <ul class="dropdown-menu">
                           <li><a class="nav-link" href="admin.php?page=uploads">New Upload</a></li>
                           <li><a class="nav-link" href="admin.php?page=upload-history">Upload History</a></li>
                        </ul>
                     </li>
                     <li class="menu-header">Settings</li>
                     <li class="dropdown <?php if($page == 'settings' ){echo 'active';}?>">
                        <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="copy"></i><span>General Settings</span></a>
                        <ul class="dropdown-menu">
                           <li><a class="nav-link" href="admin.php?page=settings">Website Setup</a></li>
                           <li><a class="nav-link" href="#">Home Sliders</a></li>
                           <li><a href="#">Testimonials</a></li>
                        </ul>
                     </li>
                  </ul>
               </aside>
            </div>
            <!-- Main Content -->
            <div class="main-content" style="padding-top: 100px;">
               <?= $page_loaded ?>
            </div>
            <footer class="main-footer">
               <div class="footer-left">
                  <a href="<?= $wallet['config']['site_url'] ?>"><?= $wallet['config']['site_name'] ?></a></a>
               </div>
               <div class="footer-right">
               </div>
            </footer>
         </div>
         <?php elseif (!empty($wallet['staff'])): ?>
         <div class="main-wrapper main-wrapper-1">
            <div class="navbar-bg"></div>
            <nav class="navbar navbar-expand-lg main-navbar sticky">
               <div class="form-inline mr-auto">
                  <ul class="navbar-nav mr-3">
                     <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg
                        collapse-btn"> <i data-feather="align-justify"></i></a></li>
                     <li><a href="#" class="nav-link nav-link-lg fullscreen-btn">
                        <i data-feather="maximize"></i>
                        </a>
                     </li>
                     <li>
                        <form class="form-inline mr-auto">
                           <div class="search-element">
                              <input class="form-control" type="search" placeholder="Search" aria-label="Search"
                                 data-width="200">
                              <button class="btn" type="submit">
                              <i class="fas fa-search"></i>
                              </button>
                           </div>
                        </form>
                     </li>
                  </ul>
               </div>
               <ul class="navbar-nav navbar-right">
                  <li class="dropdown dropdown-list-toggle">
                     <a href="#" data-toggle="dropdown" class="nav-link nav-link-lg message-toggle"><i data-feather="mail"></i> 
                     <?php
                        $count = $db
                            ->where('status', 0)
                            ->getValue('front_cms_messages', 'count(*)');
                        if ($count > 0): ?>                           
                     <span class="badge headerBadge1">
                     <?= $count ?> </span> 
                     <?php endif;
                        ?>
                     </a>
                     <div class="dropdown-menu dropdown-list dropdown-menu-right pullDown">
                        <div class="dropdown-header">
                           Messages
                           <div class="float-right">
                              <a href="javascript:void(0)" class="mark-all">Mark All As Read</a>
                           </div>
                        </div>
                        <div class="dropdown-list-content dropdown-list-message">
                           <?php foreach (
                              $db
                                  ->orderBy('id', 'desc')
                                  ->get('front_cms_messages')
                              as $key => $value
                              ): ?>
                           <a href="admin.php?page=message_details&id=<?= $value->id ?>" class="dropdown-item"> 
                           <span class="dropdown-item-avatar text-white"> 
                           <img alt="image" src="<?= $wallet['config'][
                              'site_url'
                              ] ?>layout/assets/images/avatar-place.png" class="rounded-circle">
                           </span> 
                           <span class="dropdown-item-desc"> 
                           <span class="message-user"><?= $value->name ?></span>
                           <span class="time messege-text"><?= short_text(
                              $value->message,
                              50
                              ) ?></span>
                           <span class="time"><?= date(
                              'l d/m/Y',
                              strtotime($value->date_created)
                              ) ?></span>
                           </span>
                           </a> 
                           <?php endforeach; ?>
                        </div>
                        <div class="dropdown-footer text-center">
                           <a href="#">View All <i class="fas fa-chevron-right"></i></a>
                        </div>
                     </div>
                  </li>
                  <li class="dropdown dropdown-list-toggle">
                     <a href="#" data-toggle="dropdown"
                        class="nav-link notification-toggle nav-link-lg"><i data-feather="bell" class="bell"></i>
                     </a>
                     <div class="dropdown-menu dropdown-list dropdown-menu-right pullDown">
                        <div class="dropdown-header">
                           Notifications
                           <div class="float-right">
                              <a href="#">Mark All As Read</a>
                           </div>
                        </div>
                        <div class="dropdown-list-content dropdown-list-icons">
                           <a href="#" class="dropdown-item dropdown-item-unread"> 
                           <span class="dropdown-item-icon bg-primary text-white"> 
                           <i class="fas fa-code"></i>
                           </span> 
                           <span class="dropdown-item-desc"> 
                           System update is available now! <span class="time">2 Min Ago</span>
                           </span>
                           </a> 
                        </div>
                        <div class="dropdown-footer text-center">
                           <a href="#">View All <i class="fas fa-chevron-right"></i></a>
                        </div>
                     </div>
                  </li>
                  <li class="dropdown">
                     <a href="#" data-toggle="dropdown"
                        class="nav-link dropdown-toggle nav-link-lg nav-link-user"> 
                     <img alt="image"  src="<?= $wallet['config']['site_url'] .
                        $wallet['staff'][
                            'image'
                        ] ?>" class="user-img-radious-style" onerror="this.onerror=null;this.src='<?= $wallet['config']['site_url'] ?>layout/assets/img/avatar.png'"> <span
                        class="d-sm-none d-lg-inline-block"></span></a>
                     <div class="dropdown-menu dropdown-menu-right pullDown">
                        <div class="dropdown-title">Hello <?= $wallet['staff'][
                           'lastname'
                           ] ?></div>
                        <a href="#" class="dropdown-item has-icon"> 
                        <i class="far fa-user"></i> Profile
                        </a> 
                        <!-- <div class="dropdown-divider"></div> -->
                        <a href="admin.php?page=logout" class="dropdown-item has-icon text-danger"> <i
                           class="fas fa-sign-out-alt"></i>
                        Logout
                        </a>
                     </div>
                  </li>
               </ul>
            </nav>
            <div class="main-sidebar sidebar-style-2">
               <aside id="sidebar-wrapper">
                  <div class="sidebar-brand">
                     <a href="admin.php"> <img alt="image"
                        src="<?= $wallet['config']['site_url'] .
                           $wallet['config']['logo'] ?>"
                        class="header-logo" onerror = "this.onerror=null;this.src='<?= $wallet[
                           'config'
                           ]['site_url'] ?>layout/assets/img/sms.png'" />
                     </a>
                  </div>
                  <ul class="sidebar-menu">
                     <li class="menu-header">Main</li>
                     <li class="dropdown <?php if($page == 'staff-dashboard'){echo 'active';}?>">
                        <a href="admin.php?page=staff-dashboard" class="nav-link"><i
                           data-feather="monitor"></i><span>Dashboard</span></a>
                     </li>
                     <li class="dropdown <?php if($page == 'staff' || $page == 'new-staff'|| $page == 'edit-staff'){echo 'active';}?>">
                        <a href="#" class="menu-toggle nav-link has-dropdown"><i
                           data-feather="users"></i><span>Staff</span></a>
                        <ul class="dropdown-menu">
                           <li><a class="nav-link" href="admin.php?page=rstaff">Staff List</a></li>
                        </ul>
                     </li>
                     <li class="dropdown <?php if($page == 'rstudents' || $page == 'new-student'|| $page == 'edit-student'){echo 'active';}?>">
                        <a href="#" class="menu-toggle nav-link has-dropdown"><i
                           data-feather="users"></i><span>Students</span></a>
                        <ul class="dropdown-menu">
                           <li><a class="nav-link" href="admin.php?page=new-student">New Student</a></li>
                           <li><a class="nav-link" href="admin.php?page=rstudents">All Students</a></li>
                        </ul>
                     </li>
                     <li class="dropdown <?php if($page == 'rsubjects'){echo 'active';}?>">
                        <a href="#" class="menu-toggle nav-link has-dropdown"><i
                           data-feather="users"></i><span>Subjects</span></a>
                        <ul class="dropdown-menu">
                           <li><a class="nav-link" href="admin.php?page=rsubjects">All Subjects</a></li>
                        </ul>
                     </li>
                     <li class="dropdown <?php if($page == 'rclasses' || $page == 'new-class'|| $page == 'edit-class'){echo 'active';}?>">
                        <a href="#" class="menu-toggle nav-link has-dropdown"><i
                           data-feather="users"></i><span>Classes</span></a>
                        <ul class="dropdown-menu">
                           <li><a class="nav-link" href="admin.php?page=rclasses">All Classes</a></li>
                        </ul>
                     </li>
                     <li class="dropdown <?php if($page == 'rprojects' || $page == 'new-project'|| $page == 'edit-project'){echo 'active';}?>">
                        <a href="#" class="menu-toggle nav-link has-dropdown"><i
                           data-feather="users"></i><span>Projects</span></a>
                        <ul class="dropdown-menu">
                           <li><a class="nav-link" href="admin.php?page=new-project">New Project</a></li>
                           <li><a class="nav-link" href="admin.php?page=rprojects">All Projects</a></li>
                        </ul>
                     </li>
                     <li class="dropdown <?php if($page == 'projectresults'){echo 'active';}?>">
                        <a href="#" class="menu-toggle nav-link has-dropdown"><i
                           data-feather="users"></i><span>Project Results</span></a>
                        <ul class="dropdown-menu">
                           <li><a class="nav-link" href="admin.php?page=projectresults">All Students Results</a></li>
                        </ul>
                     </li>
                     <li class="dropdown <?php if($page == 'student-subjects'){echo 'active';}?>">
                        <a href="#" class="menu-toggle nav-link has-dropdown"><i
                           data-feather="users"></i><span>Student Subjects</span></a>
                        <ul class="dropdown-menu">
                           <li><a class="nav-link" href="admin.php?page=student-subjects">All Student Subjects</a></li>
                        </ul>
                     </li>
                  </ul>
               </aside>
            </div>
            <!-- Main Content -->
            <div class="main-content" style="padding-top: 100px;">
               <?= $page_loaded ?>
            </div>
            <footer class="main-footer">
               <div class="footer-left">
                  <a href="<?= $wallet['config']['site_url'] ?>"><?= $wallet['config']['site_name'] ?></a></a>
               </div>
               <div class="footer-right">
               </div>
            </footer>
         </div>
         <?php else: ?>
         <div class="main-wrapper main-wrapper-1">
            <div class="navbar-bg"></div>
            <nav class="navbar navbar-expand-lg main-navbar sticky">
               <div class="form-inline mr-auto">
                  <ul class="navbar-nav mr-3">
                     <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg
                        collapse-btn"> <i data-feather="align-justify"></i></a></li>
                     <li><a href="#" class="nav-link nav-link-lg fullscreen-btn">
                        <i data-feather="maximize"></i>
                        </a>
                     </li>
                     <li>
                        <form class="form-inline mr-auto">
                           <div class="search-element">
                              <input class="form-control" type="search" placeholder="Search" aria-label="Search"
                                 data-width="200">
                              <button class="btn" type="submit">
                              <i class="fas fa-search"></i>
                              </button>
                           </div>
                        </form>
                     </li>
                  </ul>
               </div>
               <ul class="navbar-nav navbar-right">
                  <li class="dropdown dropdown-list-toggle">
                     <a href="#" data-toggle="dropdown" class="nav-link nav-link-lg message-toggle"><i data-feather="mail"></i> 
                     <?php
                        $count = $db
                            ->where('status', 0)
                            ->getValue('front_cms_messages', 'count(*)');
                        if ($count > 0): ?>                           
                     <span class="badge headerBadge1">
                     <?= $count ?> </span> 
                     <?php endif;
                        ?>
                     </a>
                     <div class="dropdown-menu dropdown-list dropdown-menu-right pullDown">
                        <div class="dropdown-header">
                           Messages
                           <div class="float-right">
                              <a href="javascript:void(0)" class="mark-all">Mark All As Read</a>
                           </div>
                        </div>
                        <div class="dropdown-list-content dropdown-list-message">
                           <?php foreach (
                              $db
                                  ->orderBy('id', 'desc')
                                  ->get('front_cms_messages')
                              as $key => $value
                              ): ?>
                           <a href="admin.php?page=message_details&id=<?= $value->id ?>" class="dropdown-item"> 
                           <span class="dropdown-item-avatar text-white"> 
                           <img alt="image" src="<?= $wallet['config'][
                              'site_url'
                              ] ?>layout/assets/images/avatar-place.png" class="rounded-circle">
                           </span> 
                           <span class="dropdown-item-desc"> 
                           <span class="message-user"><?= $value->name ?></span>
                           <span class="time messege-text"><?= short_text(
                              $value->message,
                              50
                              ) ?></span>
                           <span class="time"><?= date(
                              'l d/m/Y',
                              strtotime($value->date_created)
                              ) ?></span>
                           </span>
                           </a> 
                           <?php endforeach; ?>
                        </div>
                        <div class="dropdown-footer text-center">
                           <a href="#">View All <i class="fas fa-chevron-right"></i></a>
                        </div>
                     </div>
                  </li>
                  <li class="dropdown dropdown-list-toggle">
                     <a href="#" data-toggle="dropdown"
                        class="nav-link notification-toggle nav-link-lg"><i data-feather="bell" class="bell"></i>
                     </a>
                     <div class="dropdown-menu dropdown-list dropdown-menu-right pullDown">
                        <div class="dropdown-header">
                           Notifications
                           <div class="float-right">
                              <a href="#">Mark All As Read</a>
                           </div>
                        </div>
                        <div class="dropdown-list-content dropdown-list-icons">
                           <a href="#" class="dropdown-item dropdown-item-unread"> 
                           <span class="dropdown-item-icon bg-primary text-white"> 
                           <i class="fas fa-code"></i>
                           </span> 
                           <span class="dropdown-item-desc"> 
                           System update is available now! <span class="time">2 Min Ago</span>
                           </span>
                           </a> 
                        </div>
                        <div class="dropdown-footer text-center">
                           <a href="#">View All <i class="fas fa-chevron-right"></i></a>
                        </div>
                     </div>
                  </li>
                  <li class="dropdown">
                     <a href="#" data-toggle="dropdown"
                        class="nav-link dropdown-toggle nav-link-lg nav-link-user"> 
                     <img alt="image"  src="<?= $wallet['config']['site_url'] .
                        $wallet['student'][
                            'image'
                        ] ?>" class="user-img-radious-style" onerror="this.onerror=null;this.src='<?= $wallet['config']['site_url'] ?>layout/assets/img/avatar.png'"> <span
                        class="d-sm-none d-lg-inline-block"></span></a>
                     <div class="dropdown-menu dropdown-menu-right pullDown">
                        <div class="dropdown-title">Hello <?= $wallet[
                           'student'
                           ]['lastname'] ?></div>
                        <a href="#" class="dropdown-item has-icon"> 
                        <i class="far fa-user"></i> Profile
                        </a> 
                        <!-- <div class="dropdown-divider"></div> -->
                        <a href="admin.php?page=logout" class="dropdown-item has-icon text-danger"> <i
                           class="fas fa-sign-out-alt"></i>
                        Logout
                        </a>
                     </div>
                  </li>
               </ul>
            </nav>
            <div class="main-sidebar sidebar-style-2">
               <aside id="sidebar-wrapper">
                  <div class="sidebar-brand">
                     <a href="admin.php"> <img alt="image"
                        src="<?= $wallet['config']['site_url'] .
                           $wallet['config']['logo'] ?>"
                        class="header-logo" onerror = "this.onerror=null;this.src='<?= $wallet['config']['site_url'] ?>layout/assets/img/sms.png'" />
                     </a>
                  </div>
                  <ul class="sidebar-menu">
                     <li class="menu-header">Main</li>
                     <li class="dropdown active">
                        <a href="admin.php?page=sdashboard" class="nav-link"><i
                           data-feather="monitor"></i><span>Dashboard</span></a>
                     </li>
                     <li class="dropdown">
                        <a href="#" class="menu-toggle nav-link has-dropdown"><i
                           data-feather="briefcase"></i><span>My Results</span></a>
                        <ul class="dropdown-menu">
                           <li><a class="nav-link" href="admin.php?page=my-project-results">Project Results</a></li>
                        </ul>
                     </li>
                  </ul>
               </aside>
            </div>
            <!-- Main Content -->
            <div class="main-content" style="padding-top: 100px;">
               <?= $page_loaded ?>
            </div>
            <footer class="main-footer">
               <div class="footer-left">
                  <a href="<?= $wallet['config']['site_url'] ?>"><?= $wallet['config']['site_name'] ?></a></a>
               </div>
               <div class="footer-right">
               </div>
            </footer>
         </div>
         <?php endif; ?>
         <?php
            endif; ?>
      </div>
      <!-- Template JS File -->
      <script src="<?= $wallet['config']['site_url'] ?>admin/assets/js/scripts.js"></script>
      <script src="<?= $wallet['config']['site_url'] ?>admin/assets/bundles/datatables/datatables.min.js"></script>
      <script src="<?= $wallet['config']['site_url'] ?>admin/assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js"></script>
      <script src="<?= $wallet['config']['site_url'] ?>admin/assets/bundles/datatables/export-tables/dataTables.buttons.min.js"></script>
      <script src="<?= $wallet['config']['site_url'] ?>admin/assets/bundles/datatables/export-tables/buttons.flash.min.js"></script>
      <script src="<?= $wallet['config']['site_url'] ?>admin/assets/bundles/datatables/export-tables/jszip.min.js"></script>
      <script src="<?= $wallet['config']['site_url'] ?>admin/assets/bundles/datatables/export-tables/pdfmake.min.js"></script>
      <script src="<?= $wallet['config']['site_url'] ?>admin/assets/bundles/datatables/export-tables/vfs_fonts.js"></script>
      <script src="<?= $wallet['config']['site_url'] ?>admin/assets/bundles/datatables/export-tables/buttons.print.min.js"></script>
      <script src="<?= $wallet['config']['site_url'] ?>admin/assets/js/page/datatables.js"></script>
      <!-- Custom JS File -->
      <script src="<?= $wallet['config']['site_url'] ?>admin/assets/js/custom.js"></script>
      <script type="text/javascript">
         // $('table').DataTable({
         // "scrollX": true,
         // stateSave: true
         // });
      </script>
      </script>
   </body>
</html>