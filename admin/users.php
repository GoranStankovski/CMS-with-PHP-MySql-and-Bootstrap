<?php include "includes/admin_header.php"; ?>    
    
<!--sledniot php blok e povikuvanje na funkcijata is_admin od functions.php koja proveruva dali logiraniot clen e subscriber ili admin. i tuka na stranata users dozvoluva da pristapat samo admins a subscribers gi  vraka na index.php  -->
 <?php

if(!is_admin($_SESSION['username'])){
    
    header("Location: index.php");
    
}

?>
    
    
    
    
    
    

    <div id="wrapper">

        <!-- Navigation -->
 <?php include "includes/admin_navigation.php"; ?>  

        <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                       
                       <h1 class="page-header">
                            Welcome to posts
                            <small>Author</small>
                        </h1>
                        
<?php
                        
 if(isset($_GET['source'])){
     
     $source = $_GET['source'];
     
 }   else { 
     
     $source = '';
 }
                        
 switch($source){
         
   case'add_user';
   include "includes/add_user.php";
   break;
         
   case'edit_user';
   include "includes/edit_user.php";
   break;     
         
   case'200';
   echo "Nice 200";
   break;      
         
   default:
         
   include "includes/view_all_users.php";
        
   break;    
         
 }                       
                        
                        
                        
?>
                        
                       
                    </div>
                </div>
                <!-- /.row -->

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

<?php include "includes/admin_footer.php"; ?>