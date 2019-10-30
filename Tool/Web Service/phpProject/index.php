<!DOCTYPE html>
<html>
    <head>
        <style>
        * {
            box-sizing: border-box;
        }

        input[type=text], select, textarea {
            width: 30%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            resize: vertical;
        }

        label {
            padding: 12px 12px 12px 0;
            display: inline-block;
        }

        input[type=submit] {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            float: left;
        }

        input[type=submit]:hover {
            background-color: #45a049;
        }

        .container {
            border-radius: 5px;
            background-color: #f2f2f2;
            padding: 20px;
        }

        .col-25 {
            float: left;
            width: 25%;
            margin-top: 6px;
        }

        .col-75 {
            float: left;
            width: 75%;
            margin-top: 6px;
        }

        /* Clear floats after the columns */
        .row:after {
            content: "";
            display: table;
            clear: both;
        }

        /* Responsive layout - when the screen is less than 600px wide, make the two columns stack on top of each other instead of next to each other */
        @media screen and (max-width: 600px) {
            .col-25, .col-75, input[type=submit] {
                width: 100%;
                margin-top: 0;
            }
        }
        </style>
    </head>
    <body>

       <!-- <h2>Welcome to NFR Operationalization Selection Interface</h2>
        <p>Please fill up the followings and don't forget to specify the role. You will be redirected to<br> another page where you have to choose 
        Priorities and NFR Operationalizations specified<br> in your requirements model.
        </p>
		-->
        <div class="container">
              <form name="form1" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                <div class="row">
                  <div class="col-25">
                    <label for="fname">First Name</label>
                  </div>
                  <div class="col-75">
                    <input type="text" id="fname" name="firstname" placeholder="Your name..">
                  </div>
                </div>
                <div class="row">
                  <div class="col-25">
                    <label for="lname">Last Name</label>
                  </div>
                  <div class="col-75">
                    <input type="text" id="lname" name="lastname" placeholder="Your last name..">
                  </div>
                </div>
                <div class="row">
                  <div class="col-25">
                    <label for="email">Email</label>
                  </div>
                  <div class="col-75">
                    <input type="text" id="email" name="email" onfocusout="ValidateEmail()" placeholder="Your Email id..">
                  </div>
                </div>
                <div class="row">
                  <div class="col-25">
                    <label for="priority">Select Your Role</label>
                  </div>
                  <div class="col-75">
                    <select name="role" placeholder="Select User" value="Select User">
                      <option value="Patient">Patient</option>
                      <option value="Doctor">Doctor</option>
                    </select>
                  </div>
                </div>
                <br>
                 <div class="row">
                    <input type="submit" name="submit" value="Next">
                 </div>
            </form>
        </div>
    </body>
</html>
<?php
    if($_SERVER["REQUEST_METHOD"]=="POST")
      {
        if(isset($_POST["submit"]))
          {
            $userDetails = array();
            $fname=$email=$lname=$role="";
                  
            $fname=textInput($_POST["firstname"]);
            $lname=textInput($_POST["lastname"]);
            $email=textInput($_POST["email"]);
            $role=textInput($_POST["role"]);
            $userDetails["fname"]=$fname;
            $userDetails["lname"]=$lname;
            $userDetails["email"]=$email;
            $userDetails["role"]=$role;
            session_start();
            $_SESSION['userdetails'] = $userDetails;
            //header('Location: ./selectpriority.php'); 
            header('Location: ./priorityselection.php'); 
        }
    }
    function textInput($data)
      {
        $data=trim($data);
        $data=stripslashes($data);
        $data=htmlspecialchars($data);
        return $data;
      }
?>