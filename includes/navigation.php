<!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
           
            
              <!-- Brand and toggle get grouped for better mobile display -->
              
              
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
<!--                slednata linija e smeneto index.php vo ?CMSGoran posle Rewriteing (lecture 287) za vo adresnata linija da ne se pokazuvaat oindex.php. contact itn-->
                <a class="navbar-brand" href="/CMSgoran">CMS Front</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                  
                  <?php
                    
                    $query = "SELECT * FROM categories";
                    $select_all_categories_query = mysqli_query($connection, $query);
                    
                    //za da gi prikazime kategoriite
                    while($row = mysqli_fetch_assoc($select_all_categories_query)){
                        
                      $cat_title = $row['cat_title'];
                      $cat_id = $row['cat_id'];
                        
                       $category_class = ''; 
                        $registration_class = '';
                        
                        $pageName = basename($_SERVER['PHP_SELF']);
                        $registration = 'registration.php';
                        
                        
                        if(isset($_GET['category']) && $_GET['category'] == $cat_id){
                            
                            $category_class = 'active'; 
                            
                        } else if($pageName == $registration){
                            
                            
                            $registration_class = 'active';
                            
                            
                            
                        }
                        
                      echo "<li class='$category_class'><a href='/CMSGoran/category/{$cat_id}'>{$cat_title}<a/></li>";
                        
                    }
                    
                    ?>
                    
                    <?php if(isLoggedIn()): 
                    //vaka mozi da se zapocni php cod bez da se vmetnuva vo html i da zavrsi podolu so endif; ?>
                    <li>
                        <a href="/CMSGoran/admin"></a>
                    </li>
                     <li>
                        <a href="/CMSGoran/admin">Admin</a>
                    </li>
                     <li>
                        <a href="/CMSGoran/includes/logout.php">Logout</a>
                    </li>
                    
                    <?php else: ?>
                    
                     <li>
                        <a href="/CMSGoran/login.php">Login</a>
                    </li>
                    
                    <?php endif; ?>
                    
                   
                     <li class='<?php echo  $registration_class; ?>'>
                        <a href="/CMSGoran/registration.php">Registration</a>
                    </li>
                     
                    
<?php 
                    
if(isset($_SESSION['user_role'])) {
    
    if(isset($_GET['p_id'])){
        
        $the_post_id = $_GET['p_id'];
        
        echo "<li><a href='/CMSGoran/admin/posts.php?source=edit_post&p_id={$the_post_id}'>Edit Post</a></li>";
        
    }
}      
                    
?>                
       
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>