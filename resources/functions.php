<?php

$upload_directory = "uploads";

// helper functions

function last_id() {
    global $connection;
    return mysqli_insert_id($connection);
}

function set_message($msg) {
    
if(!empty($msg)) {
    $_SESSION['message'] = $msg;
}else {
    $msg = "";
}
}

function display_message() {
    if(isset($_SESSION['message'])) {
        
        echo $_SESSION['message'];
        unset($_SESSION['message']);
    }
}

function redirect($location) {
    
header("Location: $location ");
    
}

function query($sql) {
    
    global $connection;
    
    return mysqli_query($connection, $sql);
    
}

function confirm($result) {
    
    global $connection;
    
    if(!$result) {
        
        die("QUERY FAILED " . mysqli_error($connection));
        
    }
}

function escape_string($string) {
    
    global $connection;
    
    return mysqli_real_escape_string($connection, $string);
    
}

function fetch_array($result) {
    
    return mysqli_fetch_array($result);
    
}

/********************FRONT END FUNCTIONS*************************/

// get products

function get_products() {


    $query = query(" SELECT * FROM products");
    confirm($query);
    
    $rows = mysqli_num_rows($query);
    
    
    if(isset($_GET['page'])){ 
    
        $page = preg_replace('#[^0-9]#', '', $_GET['page']);
    
    
    
    } else{
    
        $page = 1;
    
    }
    
    
    $perPage = 3; 
    
    $lastPage = ceil($rows / $perPage);
    
    
    if($page < 1){ 
    
        $page = 1;
    
    }elseif($page > $lastPage){ 
    
        $page = $lastPage;
    
    }
    
    
    
    $middleNumbers = ''; 
    
    
    $sub1 = $page - 1;
    $sub2 = $page - 2;
    $add1 = $page + 1;
    $add2 = $page + 2;
    
    
    
    if($page == 1){
    
          $middleNumbers .= '<li class="page-item active"><a>' .$page. '</a></li>';
    
          $middleNumbers .= '<li class="page-item"><a class="page-link" href="'.$_SERVER['PHP_SELF'].'?page='.$add1.'">' .$add1. '</a></li>';
    
    } elseif ($page == $lastPage) {
        
          $middleNumbers .= '<li class="page-item"><a class="page-link" href="'.$_SERVER['PHP_SELF'].'?page='.$sub1.'">' .$sub1. '</a></li>';
          $middleNumbers .= '<li class="page-item active"><a>' .$page. '</a></li>';
    
    }elseif ($page > 2 && $page < ($lastPage -1)) {
    
          $middleNumbers .= '<li class="page-item"><a class="page-link" href="'.$_SERVER['PHP_SELF'].'?page='.$sub2.'">' .$sub2. '</a></li>';
    
          $middleNumbers .= '<li class="page-item"><a class="page-link" href="'.$_SERVER['PHP_SELF'].'?page='.$sub1.'">' .$sub1. '</a></li>';
    
          $middleNumbers .= '<li class="page-item active"><a>' .$page. '</a></li>';
    
             $middleNumbers .= '<li class="page-item"><a class="page-link" href="'.$_SERVER['PHP_SELF'].'?page='.$add1.'">' .$add1. '</a></li>';
    
          $middleNumbers .= '<li class="page-item"><a class="page-link" href="'.$_SERVER['PHP_SELF'].'?page='.$add2.'">' .$add2. '</a></li>';
    
         
    
    
    } elseif($page > 1 && $page < $lastPage){
    
         $middleNumbers .= '<li class="page-item"><a class="page-link" href="'.$_SERVER['PHP_SELF'].'?page= '.$sub1.'">' .$sub1. '</a></li>';
    
         $middleNumbers .= '<li class="page-item active"><a>' .$page. '</a></li>';
     
         $middleNumbers .= '<li class="page-item"><a class="page-link" href="'.$_SERVER['PHP_SELF'].'?page='.$add1.'">' .$add1. '</a></li>';
    
    
         
    
    
    }
    
    
    
    
    
    $limit = 'LIMIT ' . ($page-1) * $perPage . ',' . $perPage;
    
    
    
    
   
    
    $query2 = query(" SELECT * FROM products $limit");
    confirm($query2);
    
    
    $outputPagination = "";
    
    
    
    if($page != 1){
    
    
        $prev  = $page - 1;
    
        $outputPagination .='<li class="page-item"><a class="page-link" href="'.$_SERVER['PHP_SELF'].'?page='.$prev.'">Back</a></li>';
    }
    
     
    
    $outputPagination .= $middleNumbers;
    
    
    
    
    if($page != $lastPage){
    
    
        $next = $page + 1;
    
        $outputPagination .='<li class="page-item"><a class="page-link" href="'.$_SERVER['PHP_SELF'].'?page='.$next.'">Next</a></li>';
    
    }
    
    
   
    
    while($row = fetch_array($query2)) {
    
    $product_image = display_image($row['product_image']);
    
    $product = <<<DELIMETER
    
    <div class="col-sm-4 col-lg-4 col-md-4">
        <div class="thumbnail">
            <a href="item.php?id={$row['product_id']}"><img style="height:90px" src="../resources/{$product_image}" alt=""></a>
            <div class="caption">
                <h4 class="pull-right">&#36;{$row['product_price']}</h4>
                <h4><a href="item.php?id={$row['product_id']}">{$row['product_title']}</a>
                </h4>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
                 <a class="btn btn-primary" target="_blank" href="../resources/cart.php?add={$row['product_id']}">Add to cart</a>
            </div>
    
    
           
        </div>
    </div>
    
    DELIMETER;
    
    echo $product;
    
    
            }
    
    
           echo "<div class='text-center'><ul class='pagination'>{$outputPagination}</ul></div>";
    
    
    }
    

function get_categories() {
    
    
$query = query("SELECT * FROM categories");
confirm($query);
    
while($row = fetch_array($query)) {
    
$category_links = <<<DELIMETER
    <a href='category.php?id={$row['cat_id']}' class='list-group-item'>{$row['cat_title']}</a>
    
DELIMETER;
    
echo $category_links;
    }
}



function get_products_in_cat_page() {
    
$query = query("SELECT * FROM products WHERE product_category_id = ". escape_string($_GET['id']) ." AND product_quantity>=1 ");    
confirm($query);
    
while($row = fetch_array($query)) {

$product_image = display_image($row['product_image']);
    
$product = <<<DELIMETER

      <div class="col-md-3 col-sm-6 hero-feature">
                <div class="thumbnail">
                    <img src="../resources/{$product_image}" alt="img" style="height:150px">
                    <div class="caption">
                        <h3>{$row['product_title']}</h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
                        <p>
                            <a href="../resources/cart.php?add={$row['product_id']}" class="btn btn-primary">Buy Now!</a> <a href="item.php?id={$row['product_id']}" class="btn btn-default">More Info</a>
                        </p>
                    </div>
                </div>
            </div>

                    
DELIMETER;

echo $product;
}
    
}




/*function login_user() {
    
    if(isset($_POST['submit'])) {
        $username = escape_string($_POST['username']);
        $password = escape_string($_POST['password']);
        
        $query = query("SELECT * FROM users WHERE username = '{$username}' ");
        confirm($query);

        while($row = mysqli_fetch_assoc($query)) {
            $db_user_id  = $row['user_id'];
            $db_username = $row['username'];
            $db_email    = $row['email'];
            $db_password = $row['password'];
        }
        if($username === "admin") {
             redirect("admin");
        } else {
            if(password_verify($password, $db_password)) {

                $_SESSION['user_id']  = $db_user_id;
                $_SESSION['username'] = $db_username;
                $_SESSION['email']    = $db_email;
                $_SESSION['password'] = $db_password;

                redirect("index.php");
            
            } else {

            set_message("Your Password is wrong");
            redirect("login.php");

        }
    }
    }
}*/


function login_user(){

    if(isset($_POST['submit'])){
    
    $username = escape_string($_POST['username']);
    $password = escape_string($_POST['password']);
    
    $query = query("SELECT * FROM users WHERE username = '{$username}' AND password = '{$password }' ");
    confirm($query);
    
    if(mysqli_num_rows($query) == 0) {
    
    set_message("Your Password or Username are wrong");
    redirect("login.php");
    
    
    } else {
    
    $_SESSION['username'] = $username;
    redirect("admin");
    
    }
}
}

function send_message() {
    
    if(isset($_POST['submit'])) {

       
       $to = "sroshani025@gmail.com";  
       $from_name = $_POST['name'];
       $subject = $_POST['subject'];
       $email = $_POST['email'];
       $body = $_POST['message'];
        
        $headers = "From: {$from_name} {$email}";
        
       $result = mail($to, $subject, $body, $headers);
        
        if(!$result) {
            set_message("Sorry we could not send your message");
            redirect("contact.php");
        }else {
            
            set_message("Your Message has been sent");
             redirect("contact.php");
        }
        
    }
}


function get_products_in_shop_page() {


    $query = query(" SELECT * FROM products WHERE product_quantity >=1");
    confirm($query);
    
    while($row = fetch_array($query)) {
    
    $product_image = display_image($row['product_image']);
    
    $product = <<<DELIMETER
    
    
                <div class="col-md-3 col-sm-6 hero-feature">
                    <div class="thumbnail">
                        <img src="../resources/{$product_image}" alt="img" style="height:100px;width:600px;">
                        <div class="caption">
                            <h3>{$row['product_title']}</h3>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
                            <p>
                                <a href="../resources/cart.php?add={$row['product_id']}" class="btn btn-primary">Buy Now!</a> <a href="item.php?id={$row['product_id']}" class="btn btn-default">More Info</a>
                            </p>
                        </div>
                    </div>
                </div>
    
    DELIMETER;
    
    echo $product;
    
    
            }
    
    
    }
    








/********************BACK END FUNCTIONS*************************/


function display_orders() {

    $query = query("SELECT * FROM orders");
    confirm($query);

    while($row = fetch_array($query)) {

        $orders = <<<DELIMETER

        <tr>
        <td>{$row['order_id']}</td>
        <td>{$row['order_amount']}</td>
        <td>{$row['order_transaction']}</td>
        <td>{$row['order_currency']}</td>
        <td>{$row['order_status']}</td>
        <td><a onClick="return confirm('Are you sure you want to delete this order');" class="btn btn-danger" href="../../resources/templates/back/delete_order.php?id={$row['order_id']}"><span class="glyphicon glyphicon-remove"></span></a></td>
        </tr>

DELIMETER;

echo $orders;
    }

}


/******************** Admin Products Page *************************/

function display_image($picture) {

    global $upload_directory;

    return $upload_directory . DS . $picture;
}

function get_products_in_admin() {

$query = query("SELECT * FROM products");    
confirm($query);
    
while($row = fetch_array($query)) {

$category = show_product_category_title($row['product_category_id']);

$product_image = display_image($row['product_image']);
    
$product = <<<DELIMETER

        <tr>
            <td>{$row['product_id']}</td>
            <td>{$row['product_title']} <br>
            <a href="index.php?edit_product&id={$row['product_id']}"><img width='100' height='50' src="../../resources/{$product_image}" alt="img"></a>
            </td>
            <td>{$category}</td>
            <td>{$row['product_price']}</td>
            <td>{$row['product_quantity']}</td>
            <td><a onClick="return confirm('Are you sure you want to delete this product');" class="btn btn-danger" href="../../resources/templates/back/delete_product.php?id={$row['product_id']}"><span class="glyphicon glyphicon-remove"></span></a></td>
        </tr>
                    
DELIMETER;

echo $product;
}

}

function show_product_category_title($product_category_id) {

    $category_query = query("SELECT * FROM categories WHERE cat_id = '{$product_category_id}' ");
    confirm($category_query);

    while($category_row = fetch_array($category_query)) {
        
        return $category_row['cat_title'];
    }

}

/******************** Add Products in admin *************************/

function add_product() {

    if(isset($_POST['publish'])) {

       $product_title           = escape_string($_POST['product_title']);
       $product_category_id        = escape_string($_POST['product_category_id']);
       $product_price           = escape_string($_POST['product_price']);
       $product_description     = escape_string($_POST['product_description']);
       $short_desc              = escape_string($_POST['short_desc']);
       $product_quantity        = escape_string($_POST['product_quantity']);
       $product_image           = escape_string($_FILES['file']['name']);
       $image_temp_location     = escape_string($_FILES['file']['tmp_name']);
      /*  $product_image           = escape_string($_FILES['file']);
        $product_image_name      = $product_image['name'];
        $product_image_tmp_loc   = $product_image['tmp_name'];
        $product_image_extension = explode('.',$product_image_name);
        $product_image_check     = strtolower(end($product_image_extension));
        $product_image_extstored = array('png', 'jpg', 'jpeg');

            if(in_array($product_image_check, $product_image_extstored)) {
                if(!is_dir("../uploads/")) {
                    mkdir('../uploads/');
                }
            }else {
                echo "Display Picture Upload failed";
            }
            
            $product_image_destination = '../uploads/' .$product_image_check;
            move_uploaded_file($product_image_tmp_loc, $product_image_destination);
      
       */
       move_uploaded_file($image_temp_location, UPLOAD_DIRECTORY . DS . $product_image);

       $query = query("INSERT INTO products(product_title, product_category_id, product_price, product_description, short_desc, product_quantity, product_image) VALUES('{$product_title}', '{$product_category_id}', '{$product_price}', '{$product_description}', '{$short_desc}', '{$product_quantity}', '{$product_image}')");
       $last_id = last_id();
       confirm($query);
       set_message("New Product with id {$last_id} was Added");
       redirect("index.php?products");
    }

}

function show_categories_add_product_page() {
    
    
    $query = query("SELECT * FROM categories");
    confirm($query);
        
    while($row = fetch_array($query)) {
        
    $categories_options = <<<DELIMETER
    <option value="{$row['cat_id']}">{$row['cat_title']}</option>
        
DELIMETER;
        
    echo $categories_options;

        }
    }


/************************** Updating Product ***************************/

function update_product() {

    if(isset($_POST['update'])) {

       $product_title           = escape_string($_POST['product_title']);
       $product_category_id     = escape_string($_POST['product_category_id']);
       $product_price           = escape_string($_POST['product_price']);
       $product_description     = escape_string($_POST['product_description']);
       $short_desc              = escape_string($_POST['short_desc']);
       $product_quantity        = escape_string($_POST['product_quantity']);
       $product_image           = escape_string($_FILES['file']['name']);
       $image_temp_location     = escape_string($_FILES['file']['tmp_name']);

       if(empty($product_image)) {

            $get_pic = query("SELECT product_image FROM products WHERE product_id =".escape_string($_GET['id'])." ");
            confirm($get_pic);

            while($pic = fetch_array($get_pic)) {

                $product_image = $pic['product_image'];

            }
       }
       
       move_uploaded_file($image_temp_location, UPLOAD_DIRECTORY . DS . $product_image);
       //move_uploaded_file($image_temp_location,"C://xampp/htdocs/Roshani_php/Buy_Here/resources/uploads/".$product_image);

       $query ="UPDATE products SET ";
       $query .="product_title         = '{$product_title}'        , ";
       $query .="product_category_id   = '{$product_category_id}'  , ";
       $query .="product_price         = '{$product_price}'        , ";
       $query .="product_description   = '{$product_description}'  , ";
       $query .="short_desc            = '{$short_desc}'           , ";
       $query .="product_quantity      = '{$product_quantity}'     , ";
       $query .="product_image         = '{$product_image}'          ";
       $query .="WHERE product_id=" . escape_string($_GET['id']);

       $send_update_query = query($query);
       confirm($send_update_query);
       set_message("Product has been updated");
       redirect("index.php?products");
    }

}



/************************** Categories in Admin ************************/

function show_categories_in_admin() {

    $category_query = query("SELECT * FROM categories");
    confirm($category_query);

    while($row = fetch_array($category_query)) {

        $cat_id = $row['cat_id'];
        $cat_title = $row['cat_title'];

        $category = <<<DELIMETER

        <tr>
            <td>{$cat_id}</td>
            <td>{$cat_title}</td>
            <td><a onClick="return confirm('Are you sure you want to delete this category');" class="btn btn-danger" href="../../resources/templates/back/delete_category.php?id={$row['cat_id']}"><span class="glyphicon glyphicon-remove"></span></a></td>
        </tr>

DELIMETER;

echo $category;
    }

}

function add_category() {

    if(isset($_POST['add_category'])) {

        $cat_title = escape_string($_POST['cat_title']);

        if(empty($cat_title) || $cat_title == " ") {

            echo "<p class='bg-danger'>This can't be empty</p>";
        }else{

        $insert_cat = query("INSERT INTO categories(cat_title) VALUES('{$cat_title}')");
        confirm($insert_cat);

        set_message("Category Created");
        }

        
    }
}



/*************************** Admin Users ***************************/

function display_users() {

    $category_query = query("SELECT * FROM users");
    confirm($category_query);

    while($row = fetch_array($category_query)) {

        $user_id = $row['user_id'];
        $username = $row['username'];
        $email = $row['email'];
        $password = $row['password'];

        $user = <<<DELIMETER

        <tr>
            <td>{$user_id}</td>
            <td>{$username}</td>
            <td>{$email}</td>
            <td><a onClick="return confirm('Are you sure you want to delete this user');" class="btn btn-danger" href="../../resources/templates/back/delete_user.php?id={$row['user_id']}"><span class="glyphicon glyphicon-remove"></span></a></td>
        </tr>

DELIMETER;

echo $user;


    }


}

function add_user() {

    if(isset($_POST['add_user'])) {

        $username   = escape_string($_POST['username']);
        $email      = escape_string($_POST['email']);
        $password   = escape_string($_POST['password']);

        $password = password_hash($password, PASSWORD_BCRYPT, array('cost'=> 12 ));

        $query = query("INSERT INTO users(username,email,password) VALUES('{$username}','{$email}','{$password}')");
        confirm($query);

        set_message("USER CREATED");

        redirect("index.php?users");
    }

}

function get_reports() {

    $query = query("SELECT * FROM reports");    
    confirm($query);
        
    while($row = fetch_array($query)) {
        
    $report = <<<DELIMETER
    
            <tr>
                <td>{$row['report_id']}</td>
                <td>{$row['product_id']}</td>
                <td>{$row['order_id']}</td>
                <td>{$row['product_price']}</td>
                <td>{$row['product_title']}
                <td>{$row['product_quantity']}</td>
                <td><a onClick="return confirm('Are you sure you want to delete this record');" class="btn btn-danger" href="../../resources/templates/back/delete_report.php?id={$row['report_id']}"><span class="glyphicon glyphicon-remove"></span></a></td>
            </tr>
                        
    DELIMETER;
    
    echo $report;
    }
    
    }

/******************** Slides Function *************************/

function add_slides() {
    
    if(isset($_POST['add_slide'])) {

        $slide_title        = escape_string($_POST['slide_title']);
        $slide_image        = escape_string($_FILES['file']['name']);
        $slide_image_loc    = escape_string($_FILES['file']['tmp_name']);

        if(empty($slide_title) || empty($slide_image)) {

            echo "<p class='bg-danger'>This field can't be empty</p>";

        }else {

            move_uploaded_file($slide_image_loc, UPLOAD_DIRECTORY . DS . $slide_image);

            $query = query("INSERT INTO slides(slide_title, slide_image) VALUES ('{$slide_title}', '{$slide_image}')");
            confirm($query);
            set_message("Slide Added");
            redirect("index.php?slides");

        }
    }

}

function get_current_slide_in_admin() {

    $query = query("SELECT * FROM slides ORDER BY slide_id DESC LIMIT 1");
    confirm($query);

    while($row = fetch_array($query)) {

        $slide_image = display_image($row['slide_image']);

        $slide_active_admin = <<<DELIMETER

        <img class="img-responsive" src="../../resources/{$slide_image}" alt="img" width="500">
   
DELIMETER;

    echo $slide_active_admin;

    }
    
}

function get_active_slide() {

    $query = query("SELECT * FROM slides ORDER BY slide_id DESC LIMIT 1");
    confirm($query);

    while($row = fetch_array($query)) {

        $slide_image = display_image($row['slide_image']);

        $slide_active = <<<DELIMETER

        <div class="item active">
            <img class="slide-image" src="../resources/{$slide_image}" alt="">
        </div>
   
DELIMETER;

    echo $slide_active;

    }
    
}

function get_slides() {

    $query = query("SELECT * FROM slides");
    confirm($query);

    while($row = fetch_array($query)) {

        $slide_image = display_image($row['slide_image']);

        $slides = <<<DELIMETER

        <div class="item">
            <img class="slide-image" src="../resources/{$slide_image}" alt="">
        </div>
   
DELIMETER;

    echo $slides;

    }
}

function get_slide_thumbnails() {

    $query = query("SELECT * FROM slides ORDER BY slide_id ASC");
    confirm($query);

    while($row = fetch_array($query)) {

        $slide_image = display_image($row['slide_image']);

        $slide_thumb_admin = <<<DELIMETER

        <div class="col-xs-6 col-md-3 image_container">
    
        <a href="index.php?delete_slide_id={$row['slide_id']}">
        
        <img class="img-responsive slide_image" src="../../resources/{$slide_image}" alt="">

        </a>

        <div class="caption">

        <p>{$row['slide_title']}</p>
        
        </div>
    
    </div>
   
DELIMETER;

    echo $slide_thumb_admin;

    }
    
}


?>
