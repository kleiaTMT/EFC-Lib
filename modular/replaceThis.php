<?php 
  if(!empty($_POST['newDis'])){
    $_SESSION['admain'] = $_POST['newDis'];
  }
  
  switch($_SESSION['admain']){
      case 'Dashboard':
        include ('C:/xampp/htdocs/testBS/modular/dasbord.php');
        break;
      case 'ManUse':
        include ('C:/xampp/htdocs/testBS/modular/manuser.php');                         
        break;
      case 'ManFile':
        include ('C:/xampp/htdocs/testBS/modular/manfile.php');                          
        break;
      case 'FfApprove':
        include ('C:/xampp/htdocs/testBS/modular/forapprove.php');
        break;
  }