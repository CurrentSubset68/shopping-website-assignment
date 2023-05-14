<?php session_start()?>
<!DOCTYPE html>
<html lang="en-gb">
    <head>
        <title>Uclan Products List</title> <!--title to be displayed in the page tab-->
        <meta charset="UTF-8"> <!-- nakes sure that all character formating is in UTF-8 to make sure that elements aren't displayed incorrectly -->
        <meta name="description" content="Online store for Uclan merchantise. sells hoodies, jumpers and tshirts."> <!--discription that appears under the website link in the browser-->
        <meta name="keywords" content="Shop, Uclan, University of Centeral Lancashire, Preston, hoodies, jumpers, tshirts, UK, United Kingdom">  <!-- keywords for SEO to make sure it's more likely to appear when related topics are searched  -->
        <meta name="author" content="Harry Sims"> <!--author name-->
        <link rel="stylesheet" href="globalstyling.css" type="text/css"> <!-- links to my style sheet so that my styles can be applied -->
        <link rel="stylesheet" href="cartstyling.css" type="text/css">
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
            
            if(isset($_POST['hiddenval'])){
                $_SESSION['store'] = $_POST['hiddenval']; // stores hiddenval in a session for persistent storage
            }

            $store = $_SESSION['store']; // variable for the session for sql input
            
            $uid = 1;
            $pdo = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
            $res = $pdo ->prepare("SELECT * FROM tbl_products WHERE product_title = '$store';");
            $res->execute();

            echo "<ul class='clothslist'>";
                $row = $res->fetch(PDO::FETCH_ASSOC);   // fetches the row     

                echo "<div class='clothslistd'>";
                    echo "<li><img class='image' src='".$row["product_image"]."' alt='hello'></li>"; // displays the selected item
                    echo "<li><h3 class='title'>".$row["product_title"]."</h3></li>";
                    echo "<li><p class='descr'>".$row["product_desc"]."</p></li>";
                    echo "<li><p class='price'> ".$row["product_price"]."</p></li>";
                    echo "<li><p class='type'>".$row["product_type"]."</p></li>";
                    echo "<form name='buybutton_item'  method='post'><input type='hidden' name='hiddenvalitem' value='$store'><input type='submit' name='buybutton' value='Add to cart'></form>";
                echo "</div>";
                echo "<br>";
            echo "</ul>";
            $count = $pdo ->prepare("SELECT COUNT(review_title) FROM tbl_reviews;"); // selects all reviews that match the current item
            $count->execute();
            $databasestore = array();
            if($count == 1){ // does this if there is one review entry because otherwise it doesn't display any
                
                $pdo = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
                $resreview = $pdo ->prepare("SELECT * FROM tbl_reviews WHERE product_id = '$reviewstore';"); // selects all reviews that match the current item
                $resreview->execute();
                $row = $resreview->fetch(PDO::FETCH_ASSOC);
                array_push($databasestore, $row); // appends to array
               
                $totalnumber = $row['review_rating']; // becomes the sole review
            }
            else{
                $typestore = $row['product_type'];
                $reviewstore = $row['product_id'];
                $pdo = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
                $resreview = $pdo ->prepare("SELECT * FROM tbl_reviews WHERE product_id = '$reviewstore';"); // selects all reviews that match the current item
                $resreview->execute();
                $totalnumber = 0;
                $divisionindex = 0;
                while ($row = $resreview->fetch(PDO::FETCH_ASSOC)) {
                    if($row['product_id'] == $reviewstore){
                        array_push($databasestore, $row);
                        $totalnumber += $row["review_rating"]; // adds to total number and increments for score calculation
                        $divisionindex++;
                    }  
                }
           
            }

            if (isSet($_SESSION['validlogin'])){  // displays welcome message
                echo "
                    <div class='register'>
                        <form name='reviewbuton'  action='item.php' method='post' onsubmit='return validateForm()'>
                            <label> title:
                                <input type='text' name='reviewtitle' placeholder='enter a title'>
                                <span id='title_error' class='error'></span>
                            </label> 
                            <label> score:
                                <input type='number' name='reviewscore' placeholder='enter 1 to 5'>
                                <span id='score_error' class='error'></span>
                            </label>
                            <label> description:
                                <input type='text' name='reviewdesc' placeholder='enter a description'>
                                <span id='desc_error' class='error'></span>
                            </label> 
                            <input type='submit' name='reviewbutton' value='Leave a review'>
                        </form>
                    </div>
                ";
                
            } 
            else{
                echo "Login to Write a review.";
                echo "<br>";
            }
            
            if(isset($_POST['hiddenval'])){
                $_SESSION['store'] = $_POST['hiddenval']; // stores hiddenval in a session for persistent storage
            }


           
            if(isset($divisionindex) && $divisionindex != 0){ 
                $result = $totalnumber / $divisionindex;
                
                echo "<h2>average score is:".round($result, 2)."</h2>"; // displays score based on both numbers. rounds so the rating is not awefully long
            }
            elseif(isset($totalnumber)){
                $result = $totalnumber / 1;
                
                echo "<h2>average score is:".round($result, 2)."</h2>"; // displays score based on one numbers. rounds so the rating is not awefully long

            }
            else{
                echo "<h2>average score is: 0 </h2>"; // displays 0 if there is no reviews

            }

            if(isset($_POST['reviewbutton'])){
                $rev_user_id =  htmlspecialchars($_SESSION['validlogin']['user_id']);
                $rev_product_id = $reviewstore;
                $timestamp = date("Y-m-d H:i:s");
                $title = htmlspecialchars($_POST['reviewtitle']);
                $score = htmlspecialchars($_POST['reviewscore']);  // values for insert
                $desc = htmlspecialchars($_POST['reviewdesc']);

                $res2 = $pdo ->prepare("INSERT INTO tbl_reviews (user_id, product_id, review_title, review_desc, review_rating, review_timestamp)
                        VALUES ('".$rev_user_id."','".$rev_product_id."','".$title."','".$desc."','".$score."','".$timestamp."')"); // inserts values into database
                $res2->execute(); // executes the insert
            }

            if(isset($_POST['hiddenvalitem'])){
                $echostore =  $_POST['hiddenvalitem']; // stores the post value in a variable for readablity
            }
            
            if(isset($echostore)){

                if(isset($_SESSION['index'])){
                    $arrayindex = $_SESSION['index']; // if tbe index is set then it is set
                }
                else{
                    $arrayindex = 0; // otherwise the value is 0
                }
            
                $_SESSION['index']++; // increments
                $_SESSION['cartstore'][] = array( // builds an array for the cart
                    'name' => $echostore,
                    'index' => $arrayindex
                );
            }

            echo "<br>";

            echo "<ul class='clothslist'>"; // displays all relevent reviews        
                     
                for($i = 0; $i < count($databasestore); $i++ ){ // for the length of the array
                    
                    echo "<div class='clothslistd'>";
                        echo "<li><h3 class='title'>".$databasestore[$i]['review_title']."</h3></li>";      //echos each element
                        echo "<li><h4 class='descr'>".$databasestore[$i]['review_rating']."</h4></li>"; 
                        echo "<li><p class ='descr'> ".$databasestore[$i]['review_desc']."</p></li> "; 
                        echo "<li><p class='type'> ".$databasestore[$i]['review_timestamp']."</p></li> "; 

                    echo "</div>";
                    echo "<br>";
                }
            echo "</ul>";

        ?>
        <script>

            function validateForm(){ // validation for review submission

                var title = document.forms["reviewbutton"]["reviewtitle"];
                var desc = document.forms["reviewbutton"]["reviewdesc"];     // grabs the form inputs
                var score = document.forms["reviewbutton"]["reviewscore"];    
                
                if(title == null || title == ""){
                    alert("title is requrired");
                    var title_input = document.getElementById("title_error");
                    const textnode = document.createTextNode("* title is required");   // checks for a title
                    title_input.appendChild(textnode);
                    return false;
                }
                else if(score == null || desc == "" || score > 5 || score < 0){
                    alert("score is invalid");
                    var desc_input = document.getElementById("desc_error");
                    const textnode = document.createTextNode("* score is required");  // checks for a valid score
                    desc_input.appendChild(textnode);
                    return false;
                }
                else if(desc == null || desc == ""){
                    alert("description is requrired");
                    var desc_input = document.getElementById("desc_error");
                    const textnode = document.createTextNode("* description is required"); // checks for a description
                    desc_input.appendChild(textnode);
                    return false;
                }
                else{
                    return true; // allows for entry
                }
            }

        </script>

    </body>
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
</html>