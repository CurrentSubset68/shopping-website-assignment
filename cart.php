<?php session_start()?>
<!DOCTYPE html>

<html lang="en-gb">
    <head>
        <title>Uclan Shop Checkout</title> <!--title to be displayed in the page tab-->
        <meta charset="UTF-8"> <!-- nakes sure that all character formating is in UTF-8 to make sure that elements aren't displayed incorrectly -->
        <meta name="description" content="Online store for Uclan merchantise. sells hoodies, jumpers and tshirts."> <!--discription that appears under the website link in the browser-->
        <meta name="keywords" content="Shop, Uclan, University of Centeral Lancashire, Preston, hoodies, jumpers, tshirts, UK, United Kingdom">  <!-- keywords for SEO to make sure it's more likely to appear when related topics are searched  -->
        <meta name="author" content="Harry Sims"> <!--author name-->
        <link rel="stylesheet" href="globalstyling.css" type="text/css"> <!-- links to my style sheet so that my styles can be applied -->
        <link rel="stylesheet" href="cartstyling.css" type="text/css">
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

        <p> 
        <?php

         $arrayindex = 0;
        
         $pdo = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
     
            if (isSet($_SESSION['validlogin'])){

                echo "hello ".$_SESSION["validlogin"]["user_full_name"]."";
                echo "
                <P>
                <form name='orderform' method='post'>
                <input type='submit' name='orderbutton' value='Place Order'> 
                </form>
                </P>
                "; // if the user is logged in it allows them to order their cart

                if(isset($_POST['orderbutton'])){

                    $product_ids = array();

                    for($i = 0; $i < count($_SESSION['cartstore']); $i++){ 

                        $queryvalue = $_SESSION['cartstore'][$i]['name'];

                        $res = $pdo ->prepare("SELECT * FROM tbl_products WHERE product_title = '$queryvalue';");  // looks for the item at the indexed location in the array in the database
                        $res->execute();
                        $row = $res->fetch(PDO::FETCH_ASSOC);
                        array_push($product_ids, $row['product_id']); // adds to the array
                       }
                    
                    $timestamp = date("Y-m-d H:i:s"); // records a timestamp

                    $user_id = $_SESSION['validlogin']['user_id']; // variable equals the session id
                    
                    $databaseoutput = "";
                    foreach($product_ids as $x => $x_value){ // for each array member
                        
                            $databaseoutput .= $x_value; // concatanates to the string
                            $databaseoutput .= ", ";
                    }
                    $res2 = $pdo ->prepare("INSERT INTO tbl_orders (order_date, user_id, product_ids)
                      VALUES ('".$timestamp."','".$user_id."','".$databaseoutput."')"); // inserts the order with all entries into the array
                    $res2->execute(); // executes
                }
            }
            else{
             echo "not logged in. login from the login page to place an order";

            }
           

            if(isset($_SESSION['cartstore'])){

                for($i = 0; $i < count($_SESSION['cartstore']); $i++){
                    
                    $cartarrayvalue = $_SESSION['cartstore'][$i]['index'];   // selects index of the element
                    $queryvalue = $_SESSION['cartstore'][$i]['name']; // selects array element at $i because counting

                    $res = $pdo ->prepare("SELECT * FROM tbl_products WHERE product_title = '$queryvalue';");
                    $res->execute();
                    $row = $res->fetch(PDO::FETCH_ASSOC);  
                    
                    echo "<ul class='clothslist'>";
                        echo "<div class='clothslistd'>";
                            echo "<li><img class='image' src='".$row["product_image"]."' alt='hello'></li>";
                            echo "<li><h3 class='title'>".$row["product_title"]."</h3></li>";
                            echo "<li><p class='descr'>".$row["product_desc"]."</p></li>";
                            echo "<li><p class='price'> ".$row["product_price"]."</p></li>";  // displays all items in the array from the database
                            echo "<li><p class='type'>".$row["product_type"]."</p></li>";
                            echo "<form name='buybutton' method='post'><input type=''hidden name='hiddenvalcart' value='$cartarrayvalue'><input type='submit' name='buybutton' value='Remove Item'></form>";
                        echo "</div>";
                        echo "<br>";
                    echo "</ul>";
                    
                }
            }

            if(isset($_POST['hiddenvalcart'])){

             unset($_SESSION['cartstore'][$cartarrayvalue]); // removes array element

            }
        ?>
        </p>

        <footer class="cartf">
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
</html>