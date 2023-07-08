<?php session_start();
 include('connect.php');
    if(!isset($_SESSION["email"])){
    ?>
    <script>
    window.location="login.php";
    </script>
    <?php
    
} else { 

    ?>
   
    <div id="main-wrapper">
        
        <div class="header">
            <nav class="navbar top-navbar navbar-expand-md navbar-light">
              
                <div class="navbar-header">
                    <a class="navbar-brand" href="index.php">
                        
                         <?php
             $sql_header_logo = "select * from manage_website"; 
             $result_header_logo = $conn->query($sql_header_logo);
             $row_header_logo = mysqli_fetch_array($result_header_logo);
             ?>
                        <strong><img src="uploadImage/Logo/<?php echo $row_header_logo['logo'];?>" alt="homepage" class="dark-logo" style="width:100%;height:auto;"/></strong>
                    </a>
                </div>
                
                <div class="navbar-collapse">
                    
                    <ul class="navbar-nav mr-auto mt-md-0">
                        
                        <li class="nav-item"> <a class="nav-link nav-toggler hidden-md-up text-muted  " href="javascript:void(0)"><em class="mdi mdi-menu"></em></a> </li>
                        <li class="nav-item m-l-10"> <a class="nav-link sidebartoggler hidden-sm-down text-muted  " href="javascript:void(0)"><em class="ti-menu"></em></a> </li>
                        
                    </ul>
                    
                    <ul class="navbar-nav my-lg-0">

                      
                        
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted  " href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <?php 
                                $sql = "select * from admin where id = '".$_SESSION["id"]."'";
                                $query=$conn->query($sql);
                                while($row=mysqli_fetch_array($query))
                                    {
                                     
                                      extract($row);
                                      $fname = $row['fname'];
                                      $lname = $row['lname'];
                                      $email = $row['email'];
                                      $contact = $row['contact'];
                                      $dob1 = $row['dob'];
                                      $gender = $row['gender'];
                                      $image = $row['image'];
                                    }
                                                                    ?>
                                <img src="uploadImage/Profile/<?=$image?>" alt="user" class="profile-pic" /></a>
                            <div class="dropdown-menu dropdown-menu-right animated zoomIn">
                                <ul class="dropdown-user">
                                    <li><a href="profile.php"><em class="ti-user"></em> Profile</a></li>
                                   
                                     <li><a href="changepassword.php"><em class="ti-key"></em> Changed Password</a></li>
                                  
                                    <li><a href="logout.php"><em class="fa fa-power-off"></em> Logout</a></li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
       
        <?php } ?>