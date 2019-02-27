<?php
// DATABASE HELPER FUNCTIONS



function escape($string){
    
global $connection;
    
return    mysqli_real_escape_string($connection, trim(strip_tags($string)));
    
}


function users_online(){
    
    
    if(isset($_GET['onlineusers'])) {
    
global $connection;
        
if(!$connection) {
    
    session_start();
    include("../includes/db.php");
    
    
$session = session_id();
$time = time();
$time_out_in_seconds = 05;
$time_out = $time - $time_out_in_seconds;

$query = "SELECT * FROM users_online WHERE session = '$session'";
$send_query = mysqli_query($connection, $query);
$count = mysqli_num_rows($send_query);
        
 if($count == NULL){
     
     
 mysqli_query($connection, "INSERT INTO users_online(session, time) VALUES('$session','$time')");    
     
     
 } else {
     
   mysqli_query($connection, "UPDATE users_online SET time = '$time' WHERE session = '$session'");    
     
 }  
        
 $users_online_query = mysqli_query($connection, "SELECT * FROM users_online WHERE time > '$time_out'");   
 echo $count_user = mysqli_num_rows($users_online_query);    
    
    
    
    
}

 
    

} //get request isset ()
    
    
}

users_online();



function confirmQuery($result){
    
    global $connection;
    
    if(!$result){
        
        die("QUERY FAILED ." . mysqli_error($connection));
 }
}

function fetchRecords($result){
    return mysqli_fetch_array($result);
}

function insert_categories(){
    
    global $connection;
    
    if(isset($_POST['submit'])){
                                
                        
$cat_title = $_POST['cat_title'];
                                
if($cat_title == "" || empty($cat_title)){
                                    
echo "This field should not be empty / Ова поле не смее да е празно!";
                                
}else{
           
//PREPARED STATEMENTS ZA VNESUVANJE VO BAZA  LECTURE 281 !!! MNOGU BITNO ISTO I ZA FETCHING POSTS
$stmt=mysqli_prepare($connection, "INSERT INTO categories(cat_title) VALUES(?) ");

mysqli_stmt_bind_param($stmt, 's', $cat_title);

mysqli_stmt_execute($stmt);
    
if(!$stmt){
die('QUERY FAILED' . mysqli_error($connection));
}
}
        mysqli_stmt_close($stmt); //zatvoranje na konekcija so baza
}
}


function findAllCategories(){
   global $connection; 
    
    $query = "SELECT * FROM categories";
                              $select_categories = mysqli_query($connection, $query);  
                               
                              while($row = mysqli_fetch_assoc($select_categories)){
                        
                              $cat_id = $row['cat_id'];
                              $cat_title = $row['cat_title'];
                                  
                                  
                              echo "<tr>";
                              echo "<td>{$cat_id}</td>";
                              echo "<td>{$cat_title}</td>";
                              echo "<td><a href='categories.php?delete={$cat_id}'>DELETE</a></td>";
                              echo "<td><a href='categories.php?edit={$cat_id}'>Edit</a></td>";   
                              echo "</tr>";
                              }
       
}

function delete_categories(){
    global $connection; 
    
    if(isset($_GET['delete'])){
                                
                                $the_cat_id = $_GET['delete'];
                                
                                    $query = "DELETE FROM categories WHERE cat_id = {$the_cat_id} ";
                                    $delete_query = mysqli_query($connection,$query);
                                    header("Location: categories.php");
                            }
    
}
//SECTION 44 NOVA FUNKCIJA ZA PRIKAZUVANJE POST VO ADMIN/INDEX (GRRAFIKOT). vo index(admin) veke ima edna  recordCount($table) ama ovaa e ponova i ke zema postovi samo od logiranoit user

function get_all_user_posts(){
    
    return query("SELECT * FROM posts WHERE user_id=".loggedInUserId()."");
    
}

function get_all_post_user_comments(){
    return query("SELECT * FROM posts INNER JOIN comments ON posts.post_id = comments.comment_post_id WHERE user_id=".loggedInUserId()."");
}

function get_all_user_categories(){
    return query("SELECT * FROM categories WHERE user_id=".loggedInUserId()."");
}

function get_all_user_published_posts(){
     return query("SELECT * FROM posts WHERE user_id=".loggedInUserId()." AND post_status='published'");
}

function get_all_user_draft_posts(){
     return query("SELECT * FROM posts WHERE user_id=".loggedInUserId()." AND post_status='draft'");
}

function get_all_user_approved_comments(){
     return query("SELECT * FROM posts INNER JOIN comments ON posts.post_id = comments.comment_post_id WHERE user_id=".loggedInUserId()." AND comment_status='approved'");
}

function get_all_user_unapproved_comments(){
     return query("SELECT * FROM posts INNER JOIN comments ON posts.post_id = comments.comment_post_id WHERE user_id=".loggedInUserId()." AND comment_status='unapproved'");
}
    
    

//brojac na records Count (za prikazuvanje vo grafikot index9admin)
function count_records($result){
    return mysqli_num_rows($result);
}


//ova e funkcija za pobrzo prikazuvanje na posts, users, comments, i categories vo admin i e integrirana vo HTML-ot na index(admin)

function recordCount($table){

 global $connection;
    
$query = "SELECT * FROM " . $table;
$select_all_post = mysqli_query($connection,$query);
    
$result = mysqli_num_rows($select_all_post);

confirmQuery($result);
    
return $result;
    
}


function checkStatus($table,$column,$status){
global $connection;
    
$query = "SELECT * FROM $table WHERE $column = '$status' ";
$result = mysqli_query($connection,$query); 
    
confirmQuery($result);
    
    
return mysqli_num_rows($result);  

    
}

function checkUserRole($table,$column,$role){
    global $connection;

   $query = "SELECT * FROM $table WHERE $column = '$role'"; 
    $select_all_subscribers = mysqli_query($connection,$query);
    
    
confirmQuery($select_all_subscribers);
    
    return mysqli_num_rows($select_all_subscribers);  
   
}

function get_user_name(){
    return isset($_SESSION['username']) ? $_SESSION['username'] : null;
    
}




//slednata funkcija is_admin gi vcituva site user_role-s od tabela users, gi proveruva dali se admin i sprema toa vraka true(za admin) i false(za subscriber) pa potoa ja povikuvame ovaa funkcija vo users.php kade sto dozvoluva samo admins da vleguvaat vo users a ne subscribers

function is_admin(){
    if(isloggedin()){
        
     $result = query("SELECT user_role FROM users WHERE user_id = ".$_SESSION['user_id']."");
     
    $row = fetchRecords($result);
    
    if($row['user_role'] == 'admin'){
        return true;
    }else{
        return false; 
    }  
    }    
   return false;
}



//funkcija koja ke proveruva dali username postoi vo bazata za da ne moze da se dupliraat usernames. gi vcituva username-ot go sporeduva so tie vo bazata i ako najdi takov username "if(mysqli_num_rows($result)>0)" (brojot da e pogolem od 0, sto ako najdi ke bidi ako ne najdi isto nema da bidi ) ke vrati true ako ne najdi ist takov ke vrati false.   (moze da se napravi i so unikatna vrednost vo bazata MySql ama ova e nacinot so PHP) potoa ja povikuvame vo registration.php pri registriranje na nov user. NE MOZEV DA JA POVIKAM SAMO VO FUNCTIONS.PHP PA GO PREMESTIV KODOT TAMU. ZNACI OVAA NE E POVIKANA TAMU


function username_exists($username){
    
    global $connection;
    
    $query = "SELECT username FROM users WHERE username = '$username'";
    $result = mysqli_query($connection, $query);
    confirmQuery($result);
    
    if(mysqli_num_rows($result)>0){
        return true;
    }else{
        return false;
    }
    
    
}

//ovaa e ista kako prethodnata samo za proveruvanje na emailot dali postoi. Isto ne mozev da ja povikam pa kodot e npapisan direktno na register.php
function email_exists($email){
    
    global $connection;

    $query = "SELECT user_email FROM users WHERE user_email = '$email'";
    $result = mysqli_query($connection, $query);
    confirmQuery($result);
    
    if(mysqli_num_rows($result)>0){
        return true;
    }else{
        return false;
    }
    
    
}

//funkcija za redirect namesto pisenje Location na mnogu mesta, za da ne nosi dao admin ili drugo mesto
function redirect($location){
   header("Location:" . $location);
   exit;
    
}


function ifItIsMethod($method=null){
    
    if($_SERVER['REQUEST_METHOD'] == strtoupper($method)){
        
        return true;
    }
    return false;
}

function isLoggedIn(){
    
    if(isset($_SESSION['user_role'])){
        
        return true;
        
    }
    return false;
}


//funkcija query
function query($query){
    global $connection;
    $result = mysqli_query($connection, $query);
    confirmQuery($result);
    return $result;
}

//funkcija za detektiranje user za likes

function loggedInUserId(){
    
    if(isLoggedIn()){
        
        $result = query("SELECT * FROM users WHERE username='" . $_SESSION['username'] ."'");
        confirmQuery($result);
        $user = mysqli_fetch_array($result);
        return mysqli_num_rows($result) >= 1 ? $user['user_id'] : false;
        
    }
   return false;
    
}

//funkcija - dali userot go ima like-nato postot za da se znae koja ikona da mu se pokaze thumbs Up or DOWN

function userLikedThisPost($post_id=''){
    
    $result = query("SELECT * FROM likes WHERE user_id=" .loggedInUserId() . " AND post_id={$post_id}");
    confirmQuery($result);
    return mysqli_num_rows($result) >= 1 ? true : false;
}


function checkIfUserIsLoggedInAndRedirect($redirectLocation=null){
    
    if(isLoggedIn()){
        
        redirect($redirectLocation);
            
    }
    
}

// funkcija za zemanje lajkovite za  post

function getPostLikes($post_id){
    $result = query("SELECT * FROM likes WHERE post_id=$post_id");
    confirmQuery($result);
    
    echo mysqli_num_rows($result);
}






//funkcija za registriranje na users kako na pocetokot od registration.php ama deka ne mozam da gi povikvam tamu samo kodot e napisan tuka



function register_user($username, $email, $password){
    
        global $connection;
    
        $username = mysqli_real_escape_string($connection, $username);
        $email    = mysqli_real_escape_string($connection, $email);
        $password = mysqli_real_escape_string($connection, $password);
            
        $password = password_hash($password, PASSWORD_BCRYPT, array('cost' => 12));
    
        $query = "INSERT INTO users (username, user_email, user_password, user_role) ";
        $query.="VALUES('{$username}','{$email}','{$password}','subscriber' )";
        $register_user_query = mysqli_query($connection,$query);
            
        confirmQuery($register_user_query);
            
        } 
    



function login_user($username, $password){
global $connection;

    
$username=trim($username);
$password=trim($password);

$username = mysqli_real_escape_string($connection,$username);
$password = mysqli_real_escape_string($connection,$password);

$query = "SELECT * FROM users WHERE username = '{$username}' ";
$select_user_query = mysqli_query($connection, $query);
if(!$select_user_query){
    die("QUERY FAILED". mysqli_error($connection));
}
    
    while($row = mysqli_fetch_array($select_user_query)){
        
        $db_user_id = $row['user_id'];
        $db_username = $row['username'];
        $db_user_password = $row['user_password'];
        $db_user_firstname = $row['user_firstname'];
        $db_user_lastname = $row['user_lastname'];
        $db_user_role = $row['user_role'];
        
            if(password_verify($password,$db_user_password)){
    
    $_SESSION['user_id'] = $db_user_id;
    $_SESSION['username'] = $db_username; 
    $_SESSION['firstname'] = $db_user_firstname;
    $_SESSION['lastname'] = $db_user_lastname;
    $_SESSION['user_role'] = $db_user_role;
        
    redirect("/cmsgoran/admin");
        
   } else {
        
return false;
        
}
}
    return true;
    
}

//ova e funkcija za naoganje momentalniot user se koristi vo view_all_posts za da prikazat vo admin postovi samo od toj user
function currentUser(){
    if(isset($_SESSION['username'])){
        return $_SESSION['username'];
    }
    
    return false;
}
   
//funkcija za postavuvawe na slika ako ne se stavi pri kreiranje nov post. Ke ja kalj image_4.jpg
function imagePlaceholder($image=''){
    if(!$image){
        
        return 'image_4.jpg';
    }else{
        return $image;
    }
}











?>