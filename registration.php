<?php include "includes/db.php"; ?>
<?php include "includes/header.php"; ?>

<?php

require 'vendor/autoload.php';

//Setting language variables
if(isset($_GET['lang']) && !empty($_GET['lang'])){
    
    $_SESSION['lang'] = $_GET['lang'];
    
    
    if(isset($_SESSION['lang']) && $_SESSION['lang'] != $_GET['lang']){
        
        echo "<script type='text/javascript'>location.reload()</script>";
    }
    
    if(isset($_SESSION['lang'])){
        
        include "includes/languages/".$_SESSION['lang'].".php";
    }else{
        include "includes/languages/en.php";
    }
    
}


//$dotenv = new \Dotnev\Dotnev(_DIR_);
//$dotenv->load();
    
$options = array(
    'cluster' => 'eu',
    'useTLS' => true
  );
$pusher = new Pusher\Pusher('857d67edf4c194bfbef2', '8214869db273ecb6bb5b', '714210', $options);

//$pusher = new Pusher\Pusher(getenv('APP_KEY'), getenv('APP_SECRET'), getenv('APP_ID'), $options);

?>


<?php
 if($_SERVER['REQUEST_METHOD']=="POST") {
            
 $username = trim($_POST['username']);
 $email    = trim($_POST['email']);
 $password = trim($_POST['password']);
            
  //nov registration sistem  lecture 227         
 $error=[
     'username'=>'',
     'email'=>'',
     'password'=>''
     
 ];           
            
            
            
  if(strlen($username)<4){
      
      $error['username'] = 'Username needs to be longer';
      
  }  
    
    if(strlen($username =='')){
      
      $error['username'] = 'Username cannot be empty';
      
  }  
    
      if(username_exists($username)){
      
      $error['username'] = 'Username already exists, pick another one.';
      
  }  
       
    if($email ==''){
      
      $error['email'] = 'Email cannot be empty';
      
  }  
    
      if(email_exists($email)){
      
      $error['email'] = 'Email already exists,<a href="index.php">Please login</a>';
      
  } 
       
       
    if($password==''){
        $error['password'] = 'Password cannot be empty';
    }
     
   //so foreach se proveruva dali ima vo nizata greski ako nema se povikuvat funkciite  
   foreach ($error as $key => $value){
       if(empty($value)){
          
           unset($error[$key]);
           
       }
       
   }
     if(empty($error)){
         
         register_user($username, $email, $password);
         
         //slednite dve linii kod e Pusher comanda za notifikacija koga ke se registrira nnov clen ama ne rabotit
         
         
         $data['message'] = $username;
         $pusher->trigger('notifications', 'new_user', $data);
         
         login_user($username,$password);
         
     }
    
}        
// ova e drug nacin na proverka za duplikat username i email     
//    if(username_exists($username)){
//        $message= "<h3>Username already exists. Please choose another username.</h3>";
//    } vaka ke bese ako bese se vo red so povikuvanjeto na funkcijata username_exists 
            
            
//    $query_username = "SELECT username FROM users WHERE username = '$username'";
//    $result_username = mysqli_query($connection, $query_username);
//    confirmQuery($result_username);
//            
//    $query_email = "SELECT user_email FROM users WHERE user_email = '$email'";
//    $result_email = mysqli_query($connection, $query_email);
//    confirmQuery($result_email);
//            
//    
//    if(mysqli_num_rows($result_username)>0){
//          $message = "<h3>Username already exists. Please choose another username.</h3>";
//    }elseif(mysqli_num_rows($result_email)>0){
//          $message = "<h3>Email already exists. Please choose another email.</h3>";    
//        
//    }else
       
?>        
 
        
            
    

    

    <!-- Navigation -->
    
    <?php  include "includes/navigation.php"; ?>
    
 
    <!-- Page Content -->
    <div class="container">
    
    <form method="get" class="navbar-form navbar-right" action="" id="language_form">
       <div class="form-group">
          <select name="lang" class="form-control" onchange="changeLanguage()" >
            <option value="en" <?php if(isset($_SESSION['lang']) && $_SESSION['lang']== 'en'){echo "selected";}?>>English</option>
             <option value="es" <?php if(isset($_SESSION['lang']) && $_SESSION['lang']== 'es'){echo "selected";}?>>Spanish</option>
          </select>
       </div>
    </form>
    
    
<section id="login">
    <div class="container">
        <div class="row">
            <div class="col-xs-6 col-xs-offset-3">
                <div class="form-wrap">
                <h1><?php if(isset($_GET['lang'])){
                    echo _REGISTER;}
    
            else{ echo "Register";
        }
 ?></h1>
                    <form role="form" action="registration.php" method="post" id="login-form" autocomplete="off">
                       <h6 class="text-center"></h6>
                        <div class="form-group">
                            <label for="username" class="sr-only">username</label>
                            <input type="text" name="username" id="username" class="form-control" placeholder="<?php if(isset($_GET['lang'])){
                    echo _USERNAME;}
    
            else{ echo "Username";
        }
 ?>" autocomplete="on" value="<?php echo isset($username) ? $username : '' ?>">
                            
                            <p>
                            <?php echo isset($error['username']) ? $error['username'] : '' ?>
                            </p>
                      
                        </div>
                         <div class="form-group">
                            <label for="email" class="sr-only">Email</label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="<?php if(isset($_GET['lang'])){
                    echo _EMAIL;}
    
            else{ echo "Email";
        }
 ?>" autocomplete="on" value="<?php echo isset($email) ? $email : '' ?>">
                            
                            <p>
                            <?php echo isset($error['email']) ? $error['email'] : '' ?>
                            </p>
                            
                        </div>
                         <div class="form-group">
                            <label for="password" class="sr-only">Password</label>
                            <input type="password" name="password" id="key" class="form-control" placeholder="<?php if(isset($_GET['lang'])){
                    echo _PASSWORD;}
    
            else{ echo "Password";
        }
 ?>">
                            
                            <p>
                            <?php echo isset($error['password']) ? $error['password'] : '' ?>
                            </p>
                            
                        </div>
                
                        <input type="submit" name="register" id="btn-login" class="btn btn-custom btn-lg btn-block" value="<?php if(isset($_GET['lang'])){
                    echo _REGISTER;}
    
            else{ echo "Register";
        }
 ?>">
                    </form>
                 
                </div>
            </div> <!-- /.col-xs-12 -->
        </div> <!-- /.row -->
    </div> <!-- /.container -->
</section>


<hr>
        
        
 <script>
        
      function changeLanguage(){
          document.getElementById('language_form').submit();
      }  
        
        
</script>       



<?php include "includes/footer.php";?>
