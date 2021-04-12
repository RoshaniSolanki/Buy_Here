<?php require_once("../resources/config.php");?>
<?php include(TEMPLATE_FRONT . DS . "header.php");?>

<?php
    if(isset($_POST['submit'])) {
        $username = escape_string($_POST['username']);
        $email = escape_string($_POST['email']);
        $password = escape_string($_POST['password']);

        $password = password_hash($password, PASSWORD_BCRYPT, array('cost'=> 12 ));

        if(!empty($email) && !empty($username) && !empty($password)) {

            $email_query = query("SELECT * FROM users where email='{$email}'");
            $email_count = mysqli_num_rows($email_query);
   
            if($email_count>0) {
                echo "<script> alert('Email Already Exists'); </script>";
            }else {

                $insert_query = query("INSERT INTO users(username, email, password) VALUES ('{$username}', '{$email}', '{$password}')");
                confirm($insert_query);

                while($row = mysqli_fetch_assoc($email_query)) {
                    $_SESSION['userid'] =$row['user_id'];
                    $_SESSION['firstname'] = $row['first_name'];
                    $_SESSION['lastname'] = $row['last_name'];
                    $_SESSION['email'] = $row['email'];
                }
                echo '<script> alert("Registration is done successfully"); </script>';
            }
            
        }else {
            echo '<script> alert("Fields can not be empty"); </script>';
        }

    }  
?>
    <!-- Page Content -->
    <div class="container">

      <header>
            <h1 class="text-center">Register</h1>
        <div class="col-sm-4 col-sm-offset-5">         
            <form class="" action="register.php" method="post">
               <div class="form-group"><label for="username">
                    User Name<input type="text" name="username" class="form-control"></label>
                </div>
                <div class="form-group"><label for="email">
                    Email<input type="email" name="email" class="form-control"></label>
                </div>
                <div class="form-group"><label for="password">
                    Password<input type="password" name="password" class="form-control"></label>
                </div>

                <input type="submit" name="submit" id="btn-login" class="btn btn-custom btn-lg" value="Register">
                
            </form>
        </div>  


    </header>


        </div>


<?php include(TEMPLATE_FRONT . DS . "footer.php");?>