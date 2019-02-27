<?php ob_start();?>
<?php
$db['db_host']="localhost";
$db['db_user']="root";
$db['db_pass']="";
$db['db_name']="cms";
foreach($db as $key => $value){    
define(strtoupper($key), $value);
}
//$connection = mysqli_connect('localhost','root','','cms'); OVA E POEDNOSTAVEN NACIN NA POVRZUVANJE
$connection = mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);
mysqli_query($connection,"SET NAMES UTF8");


//if($connection){
    
  //  echo "Конекцијата со базата е воспоставена!";
//}




?>