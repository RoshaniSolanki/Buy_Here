<?php require_once("../resources/config.php");?>
<?php include(TEMPLATE_FRONT . DS . "header.php");?>
    <!-- Page Content -->
    <div class="container">

      <header>
            <h1 class="text-center">Register</h1>
        <div class="col-sm-4 col-sm-offset-5">         
            <form class="" action="" method="post">
               <div class="form-group"><label for="firstname">
                    First Name<input type="text" name="firstname" class="form-control"></label>
                </div>
                 <div class="form-group"><label for="lastname">
                    Last Name<input type="text" name="lastname" class="form-control"></label>
                </div>
                <div class="form-group"><label for="">
                    Mobile No.<input type="string" name="Mobile No." class="form-control"></label>
                </div>
                <div class="form-group"><label for="">
                    Email<input type="email" name="email" class="form-control"></label>
                </div>
                <div class="form-group"><label for="password">
                    Password<input type="password" name="password" class="form-control"></label>
                </div>

                <div class="form-group">
                  <a href=""><button class="btn btn-primary">Register</button></a>
                </div>
        
                
            </form>
        </div>  


    </header>


        </div>


<?php include(TEMPLATE_FRONT . DS . "footer.php");?>