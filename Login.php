<?php session_start();?>
<!DOCTYPE html>
<html lang="en-gb">
    <head>
        <title>Uclan Products List</title> <!--title to be displayed in the page tab-->
        <meta charset="UTF-8"> <!-- nakes sure that all character formating is in UTF-8 to make sure that elements aren't displayed incorrectly -->
        <meta name="description" content="Online store for Uclan merchantise. sells hoodies, jumpers and tshirts."> <!--discription that appears under the website link in the browser-->
        <meta name="keywords" content="Shop, Uclan, University of Centeral Lancashire, Preston, hoodies, jumpers, tshirts, UK, United Kingdom">  <!-- keywords for SEO to make sure it's more likely to appear when related topics are searched  -->
        <meta name="author" content="Harry Sims"> <!--author name-->
        <link rel="stylesheet" href="globalstyling.css" type="text/css"> <!-- links to my style sheet so that my styles can be applied -->
        <link rel="stylesheet" href="productsstyling.css" type="text/css">
        <link rel="stylesheet" href="Login.css" type="text/css">
    </head>

    <body>
        <header>
                
            <img class="img" src="UCLAN_Logo_FullColour_01.webp" width="200" height="120" alt="Logo of the University of Centeral Lancashire, with blue main text and red sub text"> <!--university logo to display on all pages-->
            <ul class="bannertext"> <!--list for displaying the navigation links to the other pages that i want accessable -->
                <li class="bannerelements"><a href="index.php">Home</a></li>
                <li class="bannerelements"><a href="products.php">Products</a></li>
                <li class="bannerelements"><a href="Login.php">Login</a></li>
                <li class="bannerelements"><a href="cart.php">Cart</a></li>        
            </ul>
                
        </header>

        <?php

            echo "<br>";
            if (isSet($_SESSION['validlogin'])){  // displays welcome message

                echo "hello ".$_SESSION["validlogin"]["user_full_name"]."";
            } 
            else{
                echo "not logged in. login via the Login page";
            }
        ?>

        <div class="login"> <!--div containing the form for login. htmlspecialchars is for sql injection cecking, onsubmit is for validation-->

            <h4>Login page</h4>
            <p><span class="error">* required</span></p>
            <form name="form1" action = "<?php echo htmlspecialchars("Login.php"); ?>" method="post" onsubmit="return validateForm()">
            <label>email:
                    <input type="text" name = "email" placeholder="johndoe@emample.com">
                    <span id="email_error" class="error"></span>
                    <br>
                </label><br>
            <label>Password:
                    <input type="password" name = "password" placeholder="password123">
                    <span id="password_error" class="error"></span>
                    <br>
                </label><br>
            <input type="submit" name="submit" value="submit">

            </form>
        </div>
        <div class="register"> <!--div containing the form for regristration. htmlspecialchars is for sql injection cecking, onsubmit is for validation-->

            <h4>Register an account</h4>

            <p><span class="error">* required</span></p>

            <form name="form2" action="<?php echo htmlspecialchars("Login.php");?>" method="post" onsubmit="return validateFormreg()">
            <label>Full Name:
                    <input type="text" name = "name_reg" placeholder="John Doe">
                    <span id="name_reg" class="error"></span>
                    <br>
            </label><br>
            <label>Email:
                    <input type="text" name = "Email_reg"  placeholder="johndoe@emample.com">
                    <span id="Email_reg" class="error"></span>
                    <br>
            </label><br>
            <label>Address:
                    <input type="text" name = "Address_reg"  placeholder="123 streetlane, place">
                    <span id="Address_reg" class="error"></span>
                    <br>
            </label><br>
            <label>Password:
                <input type="password" name="password_reg"  placeholder="password123">
                <span id="password_reg" class="error"></span>
                <br>
            </label><br>
            <input type="submit" name="submit" value="submit">
            </form>
        </div>
        <?php
           
        

        if(isset($_POST['name_reg']) && isset($_POST['password_reg']) && isset($_POST['Address_reg']) && isset($_POST['Email_reg'])){ // checks to see if user is registering
          
            $name_reg = htmlspecialchars($_POST['name_reg']);
            $password_reg_raw = htmlspecialchars($_POST['password_reg']); // value of password
            $password_reg = hash("sha512", $password_reg_raw); // value of hashed password
            $address_reg = htmlspecialchars($_POST['Address_reg']);     // variables and readability for $_POST values
            $email_reg = htmlspecialchars($_POST['Email_reg']);
            $id_reg = 0;
            $timestamp_reg; 
            $idloop = 0;

            $pdo = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
            $res= $pdo->prepare(("SELECT user_email FROM tbl_users WHERE user_email = '$email_reg'")); // checks to see if the account all ready exists
            $res->execute();
            $user = $res->fetch();

            if (isset($user['user_email'])){
                echo "<script>alert('an account associated with this email already exists')</script>";
              
            }else{ // if it doesn't exist then it will insert into users
                
                $timestamp_reg = date("Y-m-d H:i:s"); // grabs a timestamp
                $res2 = $pdo ->prepare("INSERT INTO tbl_users (user_full_name, user_address, user_email, user_pass, user_timestamp)
                VALUES ('".$name_reg."','".$address_reg."','".$email_reg."','".$password_reg."','".$timestamp_reg."')");
                $res2->execute();
                echo "<script>alert('account created')</script>";
            }
        }
   
        if(isset($_POST['email']) && isset($_POST['password'])){ // checks to see if user is logging in

            $email = htmlspecialchars($_POST['email']);
            $password = htmlspecialchars($_POST['password']);
            $pdo = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);

            $res= $pdo->prepare(("SELECT * FROM tbl_users WHERE user_email = '$email'")); 
            $res->execute();
            $result = $res->fetch();

            if($email == $result['user_email']){

                if(hash("sha512", $password ) == $result['user_pass']){ // hashes input and compares it to the hashed value in the database

                    $_SESSION['validlogin'] = $result; // a valid login session is created
                    echo "<script>alert('Successfully logged in')</script>";
                }
                else{
                    echo "<script>alert('Incorrect Login Details')</script>";
                }
            }
            else{
                echo "<script>alert('Incorrect Login Details')</script>";
            }
        }

        ?>
        <script>

            function validateFormreg(){ // validation for regristration

                var name = document.forms["form2"]["name"];
                var password = document.forms["form2"]["password"];
                var email = document.forms["form2"]["Email"];
                var address = document.forms["form2"]["Address"];

                if(name_textfield == null || name_textfield == ""){

                        console.log("username empty");
                        alert("Full name is required");
                        var name_input = document.getElementById("name_reg");
                        const textnode = document.createTextNode("* Full name is required");  // checks if a value was entered
                        name_input.appendChild(textnode);
                        console.log("real1");
                        return false;
                    }
                    else if(email == null || email == ""){

                        console.log("email empty");
                        alert("email is required");
                        var email_input = document.getElementById("Email_reg");
                        const textnode = document.createTextNode("* email is required");  // checks if a value was entered
                        email_input.appendChild(textnode);
                        console.log("real3");
                        return false;
                    }
                    else if(address == null || address == ""){

                        console.log("Address empty");
                        alert("Address is required");
                        var address_input = document.getElementById("Address_reg");
                        const textnode = document.createTextNode("* Address is required");  // checks if a value was entered
                        address_input.appendChild(textnode);
                        console.log("real3");
                        return false;
                    }
                    else if(password == null || password == ""){

                        console.log("password empty");
                        alert("Password is required");
                        var password_input = document.getElementById("password_reg");
                        const textnode = document.createTextNode("* Password is required");  // checks if a value was entered
                        password_input.appendChild(textnode);
                        console.log("real3");
                        return false;
                    }
                    else{
                        console.log("fake3");
                        return true;
                    }      
            }

            function  validateForm() {  // validation for regristration

                var password_textfield = document.forms["form1"]["password"].value;
                var username_textfield = document.forms["form1"]["username"].value;

                if(username_textfield == null || username_textfield == ""){

                    alert("Username is required");
                    var username_input = document.getElementById("username_error");
                    const textnode = document.createTextNode("* username is required");  // checks if a value was entered
                    username_input.appendChild(textnode);
                    console.log("real1");
                    return false;
                }
                else if(password_textfield == null || password_textfield == ""){

                    console.log("password empty");
                    alert("Password is required");
                    var password_input = document.getElementById("password_error");
                    const textnode = document.createTextNode("* Password is required"); // checks if a value was entered
                    password_input.appendChild(textnode);
                    console.log("real3");
                    return false;
                }
                else{
                    return true;
                }
            }

        </script>

        <footer class="loginf">
            <ul> <!--list for displaying footer inofrmation like contacts such as uni phone numbers, contact forms location and the main website-->
                <li> 
                    <h3>contact information</h3>
                    <a href="tel:+441772201201">+44 1772 201 201</a>
                </li>
                <li>
                    <h3>location</h3>
                    <a target="_blank" href="https://www.google.com/maps/place/University+of+Central+Lancashire/@53.7614993,-2.7044902,15.5z/data=!4m5!3m4!1s0x487b727a73870a81:0x50eb2eeb2e8bd2e0!8m2!3d53.7645082!4d-2.7083445">Fylde Rd, Preston PR1 2HE</a>
                </li>
                <li>
                    <h3>links</h3>
                    <a target="_blank" href="https://www.uclan.ac.uk/">Uclan Website</a>
                    <br>
                    <br>
                    <a href="https://www.uclan.ac.uk/contact/general-enquiries">general Enquiries</a>
                </li>
            </ul>
        </footer> 
    </body>