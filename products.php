<?php session_start()?> <!--allows sessons throughout the file-->
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
            
            <form method="post"> <!--form for basic search buttons-->
                <input type="submit" name="All" value="All">
                <input type="submit" name="tshirts" value="tshirts">
                <input type="submit" name="jumpers" value="jumpers"> <!--submit boxes act as filter buttons-->
                <input type="submit" name="hoodies" value="hoodies">
                
            </form>
            <form name="searchform" action="<?php htmlspecialchars("products.php");?>" method="post" onsubmit="Searchvalidate()"> <!--form for advanced search-->
                <label>
                    <input type="text" name="search" placeholder="Advanced Search"> <!--text field and submit button-->
                    <span id="search_error" class="error_search"></span>
                </label>
                <input type="submit" name="searchbutton" value="search">
            </form>
        </header>

        <p><?php

            if (isSet($_SESSION['validlogin'])){ // if the user is logged in
                echo "hello ".$_SESSION["validlogin"]["user_full_name"].""; // displays a message
            } 
            else{
                echo "not logged in. login via the Login page"; // notifies the user that they are not logged in
            }
            if(isset($_POST['All'])){ // if the all button is set
               
                $uid = 1;
                $pdo = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass); // database credentials
                $res = $pdo ->prepare("SELECT * FROM tbl_products;"); // sql statement
                $res->execute(); // execution

                echo "<ul class='clothslist'>"; // ul list           
                    while ($row = $res->fetch(PDO::FETCH_ASSOC)) { // while not end of array
                        echo "<div class='clothslistd'>";
                            echo "<li><img class='image' src='".$row["product_image"]."' alt='hello'></li>";
                            echo "<li><h3 class='title'>".$row["product_title"]."</h3></li>";
                            echo "<li><p class='descr'>".$row["product_desc"]."</p></li>";
                            echo "<li><p class='price'> ".$row["product_price"]."</p></li>";   // displays all items
                            echo "<li><p class='type'>".$row["product_type"]."</p></li>";
                            $echostore = $row["product_title"];
                            echo "<form name='buybutton' action='item.php' method='post'><input type=''hidden name='hiddenval' value='$echostore'><input type='submit' name='buybutton' value='Buy'></form>";
                        echo "</div>";
                        echo "<br>";
                    }
                echo "</ul>";
            }
            elseif(isset($_POST['tshirts'])){ // if the shirts button is set
                $dbhost = 'localhost';
                $dbuser = 'hsims1';
                $dbpass = 'PAkExgWR';
                $dbname = 'hsims1';
                $uid = 1;
                $pdo = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
                $res = $pdo ->prepare("SELECT * FROM tbl_products;");
                $res->execute();

                echo "<ul class='clothslist'>";          
                    while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
                        if($row["product_type"] == "UCLan Logo Tshirt"){
                            echo "<div class='clothslistd'>";
                                echo "<li><img class='image' src='".$row["product_image"]."' alt='hello'></li>";        // displays all tshirts
                                echo "<li><h3 class='title'>".$row["product_title"]."</h3></li>";
                                echo "<li><p class='descr'>".$row["product_desc"]."</p></li>";
                                echo "<li><p class='price'> ".$row["product_price"]."</p></li>";
                                echo "<li><p class='type'>".$row["product_type"]."</p></li>";
                                $echostore = $row["product_title"];
                                echo "<form name='buybutton' action='item.php' method='post'><input type=''hidden name='hiddenval' value='$echostore'><input type='submit' name='buybutton' value='Buy'></form>";

                            echo "</div>";
                            echo "<br>";
                        }
                    }
                echo "</ul>";
            }
            elseif(isset($_POST['jumpers'])){// if the jumpers button is set
                $dbhost = 'localhost';
                $dbuser = 'hsims1';
                $dbpass = 'PAkExgWR';
                $dbname = 'hsims1';
                $uid = 1;
                $pdo = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
                $res = $pdo ->prepare("SELECT * FROM tbl_products;");
                $res->execute();
                
                echo "<ul class='clothslist'>";           
                    while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
                        if($row["product_type"] == "UCLan Logo Jumper"){
                            echo "<div class='clothslistd'>";
                                echo "<li><img class='image' src='".$row["product_image"]."' alt='hello'></li>";    // displays all jumpers
                                echo "<li><h3 class='title'>".$row["product_title"]."</h3></li>";
                                echo "<li><p class='descr'>".$row["product_desc"]."</p></li>";
                                echo "<li><p class='price'> ".$row["product_price"]."</p></li>";
                                echo "<li><p class='type'>".$row["product_type"]."</p></li>";
                                $echostore = $row["product_title"];
                                echo "<form name='buybutton' action='item.php' method='post'><input type=''hidden name='hiddenval' value='$echostore'><input type='submit' name='buybutton' value='Buy'></form>";
                            echo "</div>";
                            echo "<br>";
                        } 
                    }
                echo "</ul>";
            }
            elseif(isset($_POST['hoodies'])){ // if the hoodies button is set
                $dbhost = 'localhost';
                $dbuser = 'hsims1';
                $dbpass = 'PAkExgWR';
                $dbname = 'hsims1';
                $uid = 1;
                $pdo = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
                $res = $pdo ->prepare("SELECT * FROM tbl_products;");
                $res->execute();
                
                echo "<ul class='clothslist'>";     
                    while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
                        if($row["product_type"] == "UCLan Hoodie"){
                            echo "<div class='clothslistd'>";
                                echo "<li><img class='image' src='".$row["product_image"]."' alt='hello'></li>";
                                echo "<li><h3 class='title'>".$row["product_title"]."</h3></li>";
                                echo "<li><p class='descr'>".$row["product_desc"]."</p></li>";              //displays all hoodies
                                echo "<li><p class='price'> ".$row["product_price"]."</p></li>";
                                echo "<li><p class='type'>".$row["product_type"]."</p></li>";
                                $echostore = $row["product_title"];
                                echo "<form name='buybutton' action='item.php' method='post'><input type=''hidden name='hiddenval' value='$echostore'><input type='submit' name='buybutton' value='Buy'></form>";
                            echo "</div>";
                            echo "<br>";
                        }
                    }
                echo "</ul>";
            }
            else{
                if(isset($_POST['search'])){ //if advanced search is set
                    $dbhost = 'localhost';
                    $dbuser = 'hsims1';
                    $dbpass = 'PAkExgWR';
                    $dbname = 'hsims1';
                    $uid = 1;
                    $pdo = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
                    $search = $_POST['search'];
                    $res = $pdo ->prepare("SELECT * FROM tbl_products WHERE product_title LIKE '%$search%';");
                    $res->execute();

                    echo "<ul class='clothslist'>";          
                        while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
                            echo "<div class='clothslistd'>";
                                echo "<li><img class='image' src='".$row["product_image"]."' alt='hello'></li>";
                                echo "<li><h3 class='title'>".$row["product_title"]."</h3></li>";
                                echo "<li><p class='descr'>".$row["product_desc"]."</p></li>";    // filteres to each item that matches the search querey
                                echo "<li><p class='price'> ".$row["product_price"]."</p></li>";
                                echo "<li><p class='type'>".$row["product_type"]."</p></li>";
                                $echostore = $row["product_title"];
                                echo "<form name='buybutton' action='item.php' method='post'><input type=''hidden name='hiddenval' value='$echostore'><input type='submit' name='buybutton' value='Buy'></form>";
                            echo "</div>";
                            echo "<br>";
                        }
                    echo "</ul>";
                }
                else{ // if no other options are set, then it will display everything
                    $dbhost = 'localhost';
                    $dbuser = 'hsims1';
                    $dbpass = 'PAkExgWR';
                    $dbname = 'hsims1';
                    $uid = 1;
                    $pdo = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
                    $res = $pdo ->prepare("SELECT * FROM tbl_products;");
                    $res->execute();

                    echo "<ul class='clothslist'>";         
                        while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
                            echo "<div class='clothslistd'>";
                                echo "<li><img class='image' src='".$row["product_image"]."' alt='hello'></li>";
                                echo "<li><h3 class='title'>".$row["product_title"]."</h3></li>";
                                echo "<li><p class='descr'>".$row["product_desc"]."</p></li>";
                                echo "<li><p class='price'> ".$row["product_price"]."</p></li>";  // displays all items
                                echo "<li><p class='type'>".$row["product_type"]."</p></li>";
                                $echostore = $row["product_title"];
                                echo "<form name='buybutton' action='item.php' method='post'><input type=''hidden name='hiddenval' value='$echostore'><input type='submit' name='buybutton' value='Buy'></form>";
                            echo "</div>";
                            echo "<br>";
                        }
                    echo "</ul>";
                }   
            }  
        ?>  
        </p>

        <script>
            function Searchvalidate(){ 

                var input = document.forms['search form']['search'];

                if(input == null){
                    alert("search field cannot be empty");
                    var search_input = document.getElementById("search_error");  // makes sure that search is not null
                    const textnode = document.createTextNode("input required to search");
                    search_input.appendChild(textnode);
                    return false;
                }
                else{
                    return true;
                }
            }

        </script>
        
        <footer>
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