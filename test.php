<?php include "includes/db.php"; ?>
<?php include "includes/header.php"; ?>




<?php

echo loggedInUserId();

if(userLikedThisPost(29)){
    echo "User already liked this post";
}else {
    echo "User didnt liked this post";
}


?>