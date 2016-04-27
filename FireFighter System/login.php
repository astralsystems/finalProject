<?php
	/**
	* Creates the login page for the user to login with a 
	* valid username and password.
	*/
    require("config.php");
    require("lib/password.php");

    echo mysql_get_server_info();
    $error_msg = ''; // this will be displayed to the user if not empty
    print_r(error_get_last());
    //echo password_hash("passtest",PASSWORD_DEFAULT);

    // password hash for passtest:
    //$2y$10$IARLX2ptoJ5mg0NQgynF..qE/KavEUKRjtVMtoAEOFTjbZpRbZaQC

    if ( !empty($_POST) ) {
        $username = $_POST["username"];
        $password = $_POST["password"];


        if ( !empty($username) & !empty($password) ) {
            $result = mysql_query("SELECT * FROM Officer O, Firefighter F WHERE O.username='$username' and O.id = F.id");

            if ( !$result ) {
                die('Invalid query: ' . mysql_error());
            } else {
                $login_success = false;

                $user_data = array();

                while ( $row = mysql_fetch_assoc($result) ) {
                    if ( password_verify($password, $row['password']) ) {
                        // we're done with the password, so throw it away so that
                        // if the user logs in successfully, it isn't stored in
                        // the session (as it's a big security risk!)
                        $row['password'] = '';
                        $user_data[] = $row;
                        $login_success = true;
                    }
                }

                if ( $login_success ) {
                    // store user data in session for later access
                    $_SESSION['user'] = $user_data;

                    // return the user to whichever page sent them here
                    if ( !empty($_SESSION['page_from']) ) {
                        header('Location: '.$_SESSION["page_from"]);
                        die("Redirecting to: " + $_SESSION['page_from']);
                    }
                    else {
                        header('Location: index.php');
                        die("Redirecting to: index.php");
                    }
                }
                else {
                    // we could specify whether the username or password (or
                    // both) was incorrect but doing that allows attackers to,
                    // for example, simply guess the username to gain access
                    // if the page says that only the password was wrong
                    $error_msg = 'Username/password is incorrect.';
                }
            }
        } else {
            $error_msg = 'Please enter a username and password.';
        }
    }
?>

<!doctype html>
<html lang="en">
    <head>
        <title>Astral Systems</title>
        <meta charset="utf-8"/>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

        <script src="https://cdn.jsdelivr.net/velocity/1.2.2/velocity.min.js"></script>

        <script src="https://cdn.jsdelivr.net/velocity/1.2.2/velocity.ui.min.js"></script>

<script src="http://s.codepen.io/assets/libs/modernizr.js" type="text/javascript"></script>


        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">

        <link rel="stylesheet" type="text/css" href="style.css">

        <style>


            .loginbox {
                padding: 0px;
                text-align: center;
            }

            .error {
                    color: #ED2553;
    font-size: 18px;
    font-weight: 500;
            }

            .center {
                float: none;
                margin-left: auto;
                margin-right: auto;
            }


            .box {
   position: relative;
   top: 0;
   opacity: 1;
   float: left;
   padding: 60px 50px 40px 50px;
   width: 100%;
   background: #fff;
   border-radius: 10px;
   transform: scale(1);
   -webkit-transform: scale(1);
   -ms-transform: scale(1);
   z-index: 5;
}

.box.back {
   transform: scale(.95);
   -webkit-transform: scale(.95);
   -ms-transform: scale(.95);
   top: -20px;
   opacity: .8;
   z-index: -1;
}

.box:before {
   content: "";
   width: 100%;
   height: 30px;
   border-radius: 10px;
   position: absolute;
   top: -10px;
   background: rgba(255, 255, 255, .6);
   left: 0;
   transform: scale(.95);
   -webkit-transform: scale(.95);
   -ms-transform: scale(.95);
   z-index: -1;
}

.overbox .title {
   color: #fff;
}

.overbox .title:before {
   background: #fff;
}

.title {
   width: 100%;
   float: left;
   line-height: 46px;
   font-size: 34px;
   font-weight: 700;
   letter-spacing: 2px;
   color: #ED2553;
   position: relative;
}

.title:before {
   content: "";
   width: 5px;
   height: 100%;
   position: absolute;
   top: 0;
   left: -50px;
   background: #ED2553;
}

.input,
.input label,
.input input,
.input .spin,
.button,
.button button .button.login button i.fa,
.material-button .shape:before,
.material-button .shape:after,
.button.login button {
   transition: 300ms cubic-bezier(.4, 0, .2, 1);
   -webkit-transition: 300ms cubic-bezier(.4, 0, .2, 1);
   -ms-transition: 300ms cubic-bezier(.4, 0, .2, 1);
}

.material-button,
.alt-2,
.material-button .shape,
.alt-2 .shape,
.box {
   transition: 400ms cubic-bezier(.4, 0, .2, 1);
   -webkit-transition: 400ms cubic-bezier(.4, 0, .2, 1);
   -ms-transition: 400ms cubic-bezier(.4, 0, .2, 1);
}

.input,
.input label,
.input input,
.input .spin,
.button,
.button button {
   width: 100%;
   float: left;
}

.input,
.button {
   margin-top: 30px;
   height: 70px;
}

.input,
.input input,
.button,
.button button {
   position: relative;
}

.input input {
   height: 60px;
   top: 10px;
   border: none;
   background: transparent;
}

.input input,
.input label,
.button button {
   font-family: 'Roboto', sans-serif;
   font-size: 24px;
   color: rgba(0, 0, 0, 0.8);
   font-weight: 300;
}

.input:before,
.input .spin {
   width: 100%;
   height: 1px;
   position: absolute;
   bottom: 0;
   left: 0;
}

.input:before {
   content: "";
   background: rgba(0, 0, 0, 0.1);
   z-index: 3;
}

.input .spin {
   background: #ED2553;
   z-index: 4;
   width: 0;
}

.overbox .input .spin {
   background: rgba(255, 255, 255, 1);
}

.overbox .input:before {
   background: rgba(255, 255, 255, 0.5);
}

.input label {
   position: absolute;
   top: 10px;
   left: 0;
   z-index: 2;
   cursor: pointer;
   line-height: 60px;
}

.button.login {
   width: 60%;
   left: 20%;
}

.button.login button,
.button button {
    width: 100%;
    line-height: 64px;
    left: 0%;

    border: 3px solid #ED2553;
    font-weight: 900;
    font-size: 18px;

}

.button.login {
   margin-top: 30px;
}

.button {
   margin-top: 20px;
}

.button button {
   background-color: #fff;
   color: #ED2553;
   border: none;
}

.button.login button.active {
   border: 3px solid transparent;
   color: #fff !important;
}

.button.login button.active span {
   opacity: 0;
   transform: scale(0);
   -webkit-transform: scale(0);
   -ms-transform: scale(0);
}

.button.login button.active i.fa {
   opacity: 1;
   transform: scale(1) rotate(-0deg);
   -webkit-transform: scale(1) rotate(-0deg);
   -ms-transform: scale(1) rotate(-0deg);
}

.button.login button i.fa {
   width: 100%;
   height: 100%;
   position: absolute;
   top: 0;
   left: 0;
   line-height: 60px;
   transform: scale(0) rotate(-45deg);
   -webkit-transform: scale(0) rotate(-45deg);
   -ms-transform: scale(0) rotate(-45deg);
}

.button.login button:hover {
   color: #ED2553;
   border-color: #ED2553;
}

.button {
   margin: 40px 0;
   overflow: hidden;
   z-index: 2;
}

.button button {
   cursor: pointer;
   position: relative;
   z-index: 2;
}

.pass-forgot {
   width: 100%;
   float: left;
   text-align: center;
   color: rgba(0, 0, 0, 0.4);
   font-size: 18px;
}

.click-efect {
   position: absolute;
   top: 0;
   left: 0;
   background: #ED2553;
   border-radius: 50%;
}

.overbox {
   width: 100%;
   height: 100%;
   position: absolute;
   top: 0;
   left: 0;
   overflow: inherit;
   border-radius: 10px;
   padding: 60px 50px 40px 50px;
}

.overbox .title,
.overbox .button,
.overbox .input {
   z-index: 111;
   position: relative;
   color: #fff !important;
   display: none;
}

.overbox .title {
   width: 80%;
}

.overbox .input {
   margin-top: 20px;
}

.overbox .input input,
.overbox .input label {
   color: #fff;
}

.overbox .material-button,
.overbox .material-button .shape,
.overbox .alt-2,
.overbox .alt-2 .shape {
   display: block;
}

.material-button,
.alt-2 {
   width: 140px;
   height: 140px;
   border-radius: 50%;
   background: #ED2553;
   position: absolute;
   top: 40px;
   right: -70px;
   cursor: pointer;
   z-index: 100;
   transform: translate(0%, 0%);
   -webkit-transform: translate(0%, 0%);
   -ms-transform: translate(0%, 0%);
}

.material-button .shape,
.alt-2 .shape {
   position: absolute;
   top: 0;
   right: 0;
   width: 100%;
   height: 100%;
}

.material-button .shape:before,
.alt-2 .shape:before,
.material-button .shape:after,
.alt-2 .shape:after {
   content: "";
   background: #fff;
   position: absolute;
   top: 50%;
   left: 50%;
   transform: translate(-50%, -50%) rotate(360deg);
   -webkit-transform: translate(-50%, -50%) rotate(360deg);
   -ms-transform: translate(-50%, -50%) rotate(360deg);
}

.material-button .shape:before,
.alt-2 .shape:before {
   width: 25px;
   height: 4px;
}

.material-button .shape:after,
.alt-2 .shape:after {
   height: 25px;
   width: 4px;
}

.material-button.active,
.alt-2.active {
   top: 50%;
   right: 50%;
   transform: translate(50%, -50%) rotate(0deg);
   -webkit-transform: translate(50%, -50%) rotate(0deg);
   -ms-transform: translate(50%, -50%) rotate(0deg);
}

body {

  background-color:#223248;
   background-position: center;
   background-size: cover;
   background-repeat: no-repeat;
   min-height: 100vh;
   font-family: 'Roboto', sans-serif;
}

body,
html {
   overflow: hidden;
}

.materialContainer {
   width: 100%;
   max-width: 460px;
   position: absolute;
   top: 50%;
   left: 50%;
   transform: translate(-50%, -50%);
   -webkit-transform: translate(-50%, -50%);
   -ms-transform: translate(-50%, -50%);
}

*,
*:after,
*::before {
   -webkit-box-sizing: border-box;
   -moz-box-sizing: border-box;
   box-sizing: border-box;
   margin: 0;
   padding: 0;
   text-decoration: none;
   list-style-type: none;
   outline: none;
}


.button.login button:hover {
    color: white;
    /* border-color: #ED2553; */
    background-color: #ED2553;
}



</style>



    </head>

    <body>
        <div class="loginbox">
	        <!--
            <h2>Firehouse Management System</h2>
            <h4>User Login</h4>
            -->

            <form method="post" action="login.php">
	            <!--
                <input type="text" name="username" placeholder="username" value="<?php echo $username; ?>"><br>
                <input type="password" placeholder="password" name="password"><br>
                <input type="submit" value="Submit">
                -->

          <!--
                      <div class="login-block">
  <h1>Loundonville </h1>
  <div class="login">
<input type="text" name="username" placeholder="username" value="<?php echo $username; ?>"><br>
                <input type="password" placeholder="password" name="password"><br>
                <input type="submit" class="button" value="Submit">
  </div>
</div>

-->



            <div class="materialContainer">


   <div class="box">

     <div class="title"><center>Loudonville Fire Login</center></div>

      <div class="input">

         <input type="text" name="username" placeholder="username" value="<?php echo $username; ?>"><br>
         <span class="spin"></span>
      </div>

      <div class="input">

         <input type="password" placeholder="password" name="password"><br>
         <span class="spin"></span>
      </div>

      <?php
                if (!empty($error_msg) ) {
                    echo '<div class="error">';
                    echo $error_msg;
                    echo '</div>';
                }
            ?>

      <div class="button login">
         <button><span>Submit</span> <i class="fa fa-check"></i></button>
      </div>



   </div>

   <div class="overbox">

   </div>

</div>



            </form>




        </div>

    </body>
</html>
