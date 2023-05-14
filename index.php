<?php session_start()?> <!--allows sessons throughout the file-->

<!DOCTYPE html>
<html lang="en-gb">
    <head>
        <title>Uclan Student Shop</title> <!--title to be displayed in the page tab-->
        <meta charset="UTF-8"> <!-- nakes sure that all character formating is in UTF-8 to make sure that elements aren't displayed incorrectly -->
        <meta name="description" content="Online store for Uclan merchantise. sells hoodies, jumpers and tshirts."> <!--discription that appears under the website link in the browser-->
        <meta name="keywords" content="Shop, Uclan, University of Centeral Lancashire, Preston, hoodies, jumpers, tshirts, UK, United Kingdom">  <!-- keywords for SEO to make sure it's more likely to appear when related topics are searched  -->
        <meta name="author" content="Harry Sims"> <!--author name-->
        <link rel="stylesheet" href="globalstyling.css" type="text/css"> <!-- links to my style sheet so that my styles can be applied -->
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
        
            if (isSet($_SESSION['validlogin'])){ // checks if there is a user logged in
                echo "hello ".$_SESSION["validlogin"]["user_full_name"].""; // echos the users name
            } 
            else{
                echo "not logged in. login via the Login page";  // echos a message letting the user know that they are not logged in
            }
        ?>
        </p>
         
        <?php
            echo " <h1 class='text'> <!-- header explaining the website-->
            This is a Shop dedicated to Uclan merchandise. view two videos about Uclan here, or go to products and start browsing.
            </h1>"; // echos text

            echo " <div class='mp4div'> <!-- provided mp4 video direcectly embeded in the website. -->
            <video class='mp4'  width='560' height='315' controls>
                <source src='together.mp4' type='video/mp4'>
                your browser does not support native video playback                                      
            </video>
            </div>"; // echos text

            echo " <div class='embdiv'> <!--embeded youtube video from the uclan website about applying. can be thought of as a 'portal' to youtube.-->
            <iframe class='embed' width='560' height='315' src='https://www.youtube.com/embed/xSZw_RldK5w' title='YouTube video player' allowfullscreen></iframe>
            </div>"; // echos text

            
            $pdo = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass); // credentials for the pdo statement
            $res = $pdo ->prepare("SELECT * FROM tbl_offers;"); // prepares the sql command
            $res->execute(); // executes the sql command
            echo "<ul class='offerslist'>"; // echos a ul list
            while ($row = $res->fetch(PDO::FETCH_ASSOC)) { // while it hasn't reached the end of the array (which is $row btw) it will execute
                echo "<div class='offerslistd'>";
                echo "<li><h3 class='title'>".$row["offer_title"]."</h3></li>"; // echos the offers
                echo "<li><p class='descr'>".$row["offer_dec"]."</p></li>";
                echo "</div>";
               
            }
            echo "</ul>";
        ?>
        
        
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






