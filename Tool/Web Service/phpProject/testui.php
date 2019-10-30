<?php
session_start();
		$returnval=array();
		$secSoltype=array();
		$allReturn=array();
		$secSoltype=parseNFR("nfrs.txt");
		$efSoltype=array();
		$efSoltype=parseNFR("storagenfr.txt");
		//$secSol=$secSoltype["secsol"];
		//$efSol=$efSoltype["secsol"];
		$eftype=$efSoltype["type"];
		$sectype=$secSoltype["type"];
		$secpar=$secSoltype["par"];
		$efpar=$efSoltype["par"];
		$allReturn[0]=$efSoltype;
		$allReturn[1]=$secSoltype;
		function parseNFR($filename)
		{
			$myfile = fopen($filename, "r") or die("Unable to open file!");

			$p="decompositionType";
			$s="decomposedBy";
			$count=0;
			$type="";
			$secSol=array();
			$i=0;
			$solution="";
			while(!feof($myfile)) 
			{
			   $a=fgets($myfile) ;
			   $token = strtok($a, " ");
			   
			  while ($token !== false)
			  {
			  		$m=strcasecmp($token, "softGoal");
			  		if($m==0)
			  		{
			  			$par=strtok(" ");
			  			
			  			
			  		}
			      $token=trim($token);
			       $c=strcasecmp($token,$p);
			        //echo "$token"."<br>";
			       if($c==0)
			       {
			        $type=strtok(" ");
			        $type=strtok(" ");
			        $type=preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $type); //remove SPECIAL CHARACTERS
			        //$type = preg_replace('/;+/', ';', $type);
			        
			       }
			       $c=strcasecmp($token,$s);
			       if($c==0)
			       {
			         $tok = strtok(" ");

			         while($tok !== false)
			         {
			          $tok = strtok(" ");
			          
			          $cmp1=strcasecmp($tok,";");
			          if($cmp1==0)
			          {
			            //echo "breaking";
			            //break;
			          }
			          $cmp=strcasecmp($tok,",");
			          if($cmp!=0)
			          {
			            if (preg_match('/[bcdfghjklmnpqrstvwxz]{2}/i', $tok)) // validate a string as word by checking if it contains 2 consonanats.
			            {
			              $tok=rtrim($tok,',');
			              //echo "$tok<br>";// put it in array
			              $secSol[$i]=$tok;
			              $i++;
			            }
			            //$cmp1=strcmp($tok,";");
			            
			            
			          }
			         }
			         
			       }

			      $token = strtok(" ");

			  } 
			}

			fclose($myfile);
			$returnval["secsol"]=$secSol;
			$returnval["type"]=$type;
			$returnval["par"]=$par;
			return($returnval);
		}
      function textInput($data)
      {
        $data=trim($data);
        $data=stripslashes($data);
        $data=htmlspecialchars($data);
        return $data;
      }
?>

<!DOCTYPE html>
<html>
<head>
<style>
* {
    box-sizing: border-box;
}

input[type=text], select, textarea {
    width: 100%;
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
<!--<h1>NFR Operationalization Choice Interface</h1>
<p>Select appropriate solutions from each of the category. You will be redirected to next page where you have to choose other Non functional requirements.</p>
-->

<form name="form1" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">

  

          <?php
          $l=65;
          	for($i = 0; $i < count($allReturn); $i++)
            	{
            		$p="solution";

            		
            		$sl=chr($l);
            		$p=$p.$sl;
            		//echo "$p";
            		$l=$l+1;
            		echo '<div class="container">';
            		$arr=$allReturn[$i];
            		$parsol=$arr["par"];
            		$secSol=$arr["secsol"];
            		echo '<h3>Selection of '.$parsol.' Solution</h3>';
	              for($j = 0; $j < count($secSol); $j++)
	              {
	                //echo "<br>$secSol[$j]";
	                $k=$j+1;
	       
	                echo '<div class="row"><div class="col-25"><label for="Solution '.$k.'">Solution '.$k.'</label></div><div class="col-75"><input type="radio" name="'.$p.'" value="'.$secSol[$j].'"> '.$secSol[$j].'</div></div>';
	             		
	              }
	              if($i<count($allReturn)-1)
	              {
	              	//echo '</div><h3>Selection of Efficiency Solution</h3>';
	              }
	              else{
	              	echo '</div>';
	              }
	              
	             }
          ?>
   
    
  

<!--<h3>Selection of Efficiency Solution</h3>
<div class="container">
	<?php

              /*for($j = 0; $j < count($efSol); $j++)
              {
                //echo "<br>$secSol[$j]";
                $k=$j+1;
                echo '<div class="row"><div class="col-25"><label for="Solution '.$k.'">Solution '.$k.'</label></div><div class="col-75"><input type="radio" name="efsolution" value="'.$efSol[$j].'"> '.$efSol[$j].'</div></div>';
              }*/
          ?>
</div>-->
<br>
<div class="row">
      <input type="submit" name="submit" value="submit">
    </div>
</form>
</body>
<script type="text/javascript">
  function ValidateEmail()
  {
    var inputText=document.getElementById('email');
    var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
    if(inputText.value.match(mailformat))
    {
      
      //document.form1.email.focus();
      return true;
    }
    else
    {
      alert("You have entered an invalid email address!");
      document.form1.email.value="";
      //document.form1.email.focus();
      return false;
    }
  }


</script>
</html>
<?php
  if($_SERVER["REQUEST_METHOD"]=="POST")
      {

        //if(isset($_POST["solution"]))
        //{
          $solution="";
          $priority=4;
          if(isset($_POST["submit"]))
          {
            $userDetail=array();
            $userDetail=$_SESSION['userdetails'];
            $fname=$userDetail["fname"];
            $lname=$userDetail["lname"];
            $email=$userDetail["email"];
            $role=$userDetail["role"];
            $solprio=$userDetail["solprio"];
            $sgoal=$userDetail["sgoal"];
            //$_SESSION['userdetails'] = $userDetail;
			//$priority=textInput($_POST["priority"]);
            echo "$fname, $lname, $email";
            //$efsolution=$_POST["efsolution"];
            //echo '<span style="color: #DC143C;text-align:center;">You have selected '.$solution.$fname.$lname.$email.$priority.$sectype.$role.$efsolution.$eftype.$secpar.$efpar.'</span>';
            	$h=65;
            	for($j = 0; $j < count($allReturn); $j++)
            	{
            		$val="solution";

            		$sol=$sgoal[$j];
            		$sk=chr($h);
            		$val=$val.$sk;
            		$h=$h+1;
            		$arr=$allReturn[$j];
            		
					$stype=$arr["type"];
					
					$spar=$arr["par"];
					$solution=$_POST[$val];
					$priority=$solprio[$sol];
					//echo '<span style="color: #DC143C;text-align:center;">you have selected'.$email.' '.$solution. 'par is'. $spar.' priority is $priority'. '</span>';
					insertToDB($email,$fname,$lname,$spar,$solution,$stype,$priority);
            	}
            	//header('Location: ./validate.php');
            	header('Location: ./validatenew.php');
            	//insertToDB($email,$fname,$lname,$efpar,$efsolution,$eftype,$priority);		
          }
          
        //}
      }
/*function textInput($data)
			{
				$data=trim($data);
				$data=stripslashes($data);
				$data=htmlspecialchars($data);
				return $data;
			}*/
			function insertToDB($email,$fname,$lname,$par,$solution,$type,$priority)
			{
				$updaterequired=$_SESSION['updaterequired'];
				$servername='localhost';
						$username='root';
						$password='';
						$db='consistencychecker';
						$con=new mysqli($servername,$username,$password,$db);
						if($con->connect_error)
						{
							die('Unable to connect. Error : '.$con->connect_error);
						}
						else
						{
							echo "<br><br>Database Connected";
							if($updaterequired==1)
							{
								echo "<br> Solution is $solution, $par, $email";
								$sql="update usersolutionchoice set solutionChosen='".$solution."' where id='".$email."' and solutionType='".$par."'";
								if(mysqli_query($con, $sql)){ 
									echo "<br>Updated";
								    
								} else { 
								    echo "ERROR: Could not able to execute $sql. "  
								                            . mysqli_error($con); 
								}  
							}
							else
							{
								$stmt=$con->prepare("CALL spInsertSolution(?,?,?,?,?,?,?)");
								$stmt->bind_param("ssssssi",$email,$fname,$lname,$soltype,$solution,$type,$prio);
								$soltype=$par;
								$prio=(int)$priority;
								if($stmt->execute())
								{
									echo '<script>alert("Inserted successfully")</script>';
								}
								else
								{
									echo 'Sorry!!! '.$con->error;
								}
								$stmt->close();
							}
							
							
							$con->close();
						}
			}
?>
