<?php 
    session_start();
    include("includes/header.php");
//include database connection
    include("db.php");
?>
<div class="container main-area">
    <div class="row">
        <div class="col-md-12 add-new-post">
            <?php 
                $user_id = $_SESSION['user_id'];
//            initiate variable for errors
                $error ='';
//                check for validity of data and clean strings and other data for database insertion
                if(empty($_POST['post_title'])){
                    $error .= "Please enter post title<br>";
                } else{
                    $post_title = filter_var($_POST['post_title'], FILTER_SANITIZE_STRING);
                }
                if(empty($_POST['post_desc'])){
                    $error .= "Please enter post description<br> ";
                } else{
                    $post_desc = filter_var($_POST['post_desc'], FILTER_SANITIZE_STRING);
                }
                if(empty($_POST['cat_id'])){
                    $error .= "Please select catagory<br>";
                } else{
                    $cat_id = filter_var($_POST['cat_id'], FILTER_SANITIZE_STRING);
                }
                if(empty($_FILES['post_pic']['name'])){
                    $error .= "Please upload image for post";
                } else{

                    //upload picture on the serer
                    $post_pic = $_FILES['post_pic']['name'];
                    $post_pic_tmp= $_FILES['post_pic']['tmp_name'];
                    move_uploaded_file($post_pic_tmp, "img/$post_pic");
                 }
                //display error if any
                if($error){
                    echo "<div class='alert alert-danger'>". $error ."</div>";
                } else {
                //insert data into database 

                    $run = $conn->prepare("insert into posts(`post_title`,`post_desc`,`cat_id`,`post_pic`,`user_id`) values (:title, :desc ,:cat, :pic, :user)");
//                    bind values to prevent from sql injection
                    $run->bindParam(':title', $post_title);
                    $run->bindParam(':desc', $post_desc);
                    $run->bindParam(':cat', $cat_id);
                    $run->bindParam(':pic', $post_pic);
                    $run->bindParam(':user', $user_id);

                    $result =$run->execute();
                    if($result){
                        //if data inserted successfull display success message
                        echo "<div class='alert alert-success'>Post added Successfully</div>";


                    }else {
//                        if data is not inserted display error message
                        echo "<div class='alert alert-danger'>Post could not be added, please try again</div>";
                    }
                }
            ?>   
        </div><!--col-md-md-12 end here-->
    </div><!--row end here-->
</div><!--container div end-->

<!--include footer-->
<?php include("includes/footer.php"); ?>
