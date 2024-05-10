<?php 
  ob_start();
  include ('C:/xampp/htdocs/testBS/modular/dasbord.php');
  $dashboard = ob_get_contents();
  ob_end_clean();

  ob_start();
  include ('C:/xampp/htdocs/testBS/modular/manuser.php');
  $manuser = ob_get_contents();
  ob_end_clean();

  ob_start();
  include ('C:/xampp/htdocs/testBS/modular/manfile.php');
  $manfile = ob_get_contents();
  ob_end_clean();

  ob_start();
  include ('C:/xampp/htdocs/testBS/modular/forapprove.php');
  $ffapprove = ob_get_contents();
  ob_end_clean();


  if(!empty($_POST['newDis'])){
    $_SESSION['admain'] = $_POST['newDis'];
  }
  
  switch($_SESSION['admain']){
      case 'Dashboard':
        echo $dashboard;
        break;
      case 'ManUse':
        echo $manuser;                         
        break;
      case 'ManFile':
        echo $manfile;                          
        break;
      case 'FfApprove':
        echo $ffapprove;
        break;
  }