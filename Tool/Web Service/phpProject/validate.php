<?php
session_start();
	function checkThreshold($op1,$cont1,$op2,$cont2,$solutionarray,$no_of_solutions)
	{
		$NFR1="";
		$NFR2="";
		$thresholdval1=0;
		$thresholdval2=0;
		$isvalid=0;
		for($i=0;$i<count($solutionarray);$i++)
		{
			if($solutionarray[$i]["solutionChosen"]==$op1)
			{
				
				$NFR1=$solutionarray[$i]["solutionType"];
				
			}
		}
		for($i=0;$i<count($solutionarray);$i++)
		{
			if($solutionarray[$i]["solutionChosen"]==$op2)
			{
				
				$NFR2=$solutionarray[$i]["solutionType"];
				
			}
		}
		$NFR1=trim($NFR1);
		$NFR2=trim($NFR2);
		//echo "$NFR1, $NFR2";
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
			//$sql="select * from contributionvaluedb where op='".$op."'";
			$sql="select * from thresholdvalue where NFR='".$NFR1."'";
			$result = mysqli_query ($con,$sql);
			if ($result->num_rows > 0) 
			{

 				while($row = mysqli_fetch_assoc($result)) 
				{
					$thresholdval1=$row["tval"];
					echo "<br>threshold for ".$row["NFR"]." $thresholdval1";
				}
			}
			else
			{
				echo"<br> not fetched";
			}
			if($thresholdval1<$cont1)
			{
				echo "<br>$op1 satisfies the threshold";
				$isvalid++;
			}
			else
			{
				echo "<br>$op1 doesnot satisfy the threshold";
			}
			$sql="select * from thresholdvalue where NFR='".$NFR2."'";
			$result = mysqli_query ($con,$sql);
			if ($result->num_rows > 0) 
			{

 				while($row = mysqli_fetch_assoc($result)) 
				{
					$thresholdval2=$row["tval"];
					echo "<br>threshold ".$row["NFR"]." $thresholdval2";
				}
			}
			if($thresholdval2<$cont2)
			{
				echo "<br>$op2 satisfies the threshold";
				$isvalid++;
			}
			else
			{
				echo "<br>$op2 doesnot satisfy the threshold";
			}
		}
		if($isvalid==$no_of_solutions)
		{
			$myfile = fopen("NFRCombination.txt", "w") or die("Unable to open file!");
	        $solop1 = "$op1"."\r\n";
	        fwrite($myfile, $solop1);
	        //fwrite($myfile, "the next operationalization is \n\r \r\n \n");
	        $solop2 =$op2;
	        fwrite($myfile, $solop2);
	        fclose($myfile);
			echo '<div align="center"><label for="msg"><font color="#660066"><h2>Congratulations...<br>You have chosen valid solution</h2></font></label><br><a href="./allcombination.php">You can choose optimal solution.</a></div>';
		}
		else{
			
            $_SESSION['updaterequired'] = 1;
			echo '<div align="center"><label for="msg"><font color="red"><h2>Sorry...<br>You have chosen invalid solution</h2></font></label><br>
			<a href="./testui.php">You can change your selection</a><br><a href="./allcombination.php">You can choose optimal solution.</a></div>';
		}		
	}
	function extractPriority($op,$solutionarray)
	{
		$prio="priority";
		$p=0;
		for($i=0;$i<count($solutionarray);$i++)
		{
			if($solutionarray[$i]["solutionChosen"]==$op)
			{
				
				$p=$solutionarray[$i]["priority"];
				echo "Priority of $op is $p";
				
			}
		}
		return ($p);
	}
	function extractContribution($op)
	{
		$contributionvalue;
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
							
							$sql="select * from contributionvaluedb where op='".$op."'";
							$result = mysqli_query ($con,$sql);
							if ($result->num_rows > 0) 
							{

    							$i=0;
    							while($row = mysqli_fetch_assoc($result)) 
						    	{
						    		$contributionvalue=$row["contributionvalue"];
						    		//echo "<br>contribution value is ".$row["contributionvalue"];
						    	}
							}
						}
						$con->close();
						return($contributionvalue); 
	}
	function accessUserChoice()
	{
						$priorityarray=array();
						$priorityarraylist=array();
						$servername='localhost';
						$username='root';
						$password='';
						$db='consistencychecker';
						//$email='soumik@gmail.com';
						$solutionarray=array();
						$tempsol=array();
						$userDetail=array();
            			$userDetail=$_SESSION['userdetails'];
            			$fname=$userDetail["fname"];
            			$email=$userDetail["email"];
            			echo '<h3>Welcome '.$fname.'</h3>';
						$con=new mysqli($servername,$username,$password,$db);
						if($con->connect_error)
						{
							die('Unable to connect. Error : '.$con->connect_error);
						}
						else
						{
							
							
							$sql="select * from usersolutionchoice where id='".$email."'";
							$result = mysqli_query ($con,$sql);
						
						if ($result->num_rows > 0) 
						{

    						$i=0;
    						while($row = mysqli_fetch_assoc($result)) 
						   // while($row = $result->fetch_assoc()) 
						    {
						    	$soltype=$row["solutionType"];
						    	$tempsol["solutionChosen"]=$row["solutionChosen"];
						    	$tempsol["solutionType"]=$row["solutionType"];
						    	$tempsol["priority"]=$row["priority"];
						    	$priorityarray["type"]=$soltype;
						    	$priorityarray["priority"]=$row["priority"];
						    	$solutionarray[$i]=$tempsol;
						    	$priorityarraylist[$i]=$priorityarray;
						    	$i++;
						    	//echo "solution chosen ".$row["solutionChosen"];
						        //echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
						    }
						 }
						 else
						 {
						 	
						 }
						
						
					}
					$con->close();
					$_SESSION['priorities'] = $priorityarraylist;
					return ($solutionarray);

	}

	function extractCombinedQuantification($op1,$affectedop)
	{
		$servername='localhost';
		$username='root';
		$password='';
		$db='consistencychecker';
		$combinedQuantification=0;
		
		//$op1="AES_Encryption";
		//$affectedop="LZ";
		$con=new mysqli($servername,$username,$password,$db);
		if($con->connect_error)
		{
			die('Unable to connect. Error : '.$con->connect_error);
		}
		else
		{
			$sql="select * from conflictvaluedb where op1='".$op1."' and op2affected='".$affectedop."'";
			$result = mysqli_query ($con,$sql);
			if ($result->num_rows > 0) 
			{
				
				
    			$i=0;
    			while($row = mysqli_fetch_assoc($result)) 
    			{
    				//echo "<br>$row[combinedQuantification]";
    				$combinedQuantification=$row["combinedQuantification"];
    				
    			}
    		}
		}
		$con->close();
		return ($combinedQuantification);
	}
	$no_of_solutions=0;
	$solutionarray=accessUserChoice();				//holds user choice, NFR type and priority
	$no_of_solutions=count($solutionarray);

	$temparr=array();  						//array for chosen solutions
	$strsolchoose="solutionChosen";
	$strsoltype="solutionType";
	for($i=0;$i<count($solutionarray);$i++)
	{
		$temparr[$i]=$solutionarray[$i][$strsolchoose];
		//echo "$temparr[$strsolchoose], $temparr[$strsoltype], $temparr[priority]<br>";
		//echo "$temparr[$i]";						// holds the operationalizations chosen by user.
	}
	$combinedQuantificationVal=array();   // holds combined qunatification for a operationalization pair
//here we have to do the all possible combination of operationalizations
	$combinedQuantificationVal[0]=extractCombinedQuantification($temparr[0],$temparr[1]);
	$combinedQuantificationVal[1]=extractCombinedQuantification($temparr[1],$temparr[0]);
	$c=count($combinedQuantificationVal);
	//echo "$c";
	/*for($i=0;$i<$no_of_solutions;$i++)
	{
		
		if($combinedQuantificationVal[$i]==0)
		{
			echo "This is a valid solution and send it to threshold checking checkThreshold(op1,op2)";
		}
		else if($combinedQuantificationVal[$])
		//echo "<br> $combinedQuantificationVal[$i]";
	}*/
	//$p=extractPriority($temparr[1],$solutionarray);
	
	/*if($combinedQuantificationVal[0]==0)
		{
			echo "This is a valid solution and send it to threshold checking checkThreshold(op1,op2)";
		}
		else if($combinedQuantificationVal[1]==0)
		{
			echo "This is a valid solution and send it to threshold checking checkThreshold(op2,op1)";
		}*/
	 if($combinedQuantificationVal[0]<$combinedQuantificationVal[1])
	{
			$p=extractPriority($temparr[1],$solutionarray);	
			$p2=extractPriority($temparr[0],$solutionarray);		//LZ -> AES
			$k=2*10;
			$p=$p+$p2;
			$p=1-($p/$k);
			echo "Normalized Priority is $p";
			
			$conflictValue=$combinedQuantificationVal[0]*$p;
			//echo "$conflictValue";
			$contributionValue2=extractContribution($temparr[1]);
			$contributionValue2=$contributionValue2-$conflictValue;
			$contributionValue1=extractContribution($temparr[0]);
			echo "<br>Contribution value for $temparr[0] is ".$contributionValue1." Contribution value for $temparr[1] is ".$contributionValue2;
			//checkThreshold();
			checkThreshold($temparr[0],$contributionValue1,$temparr[1],$contributionValue2,$solutionarray,$no_of_solutions);
	}
	else
	{
		     //AES -> LZ
			
			$p=extractPriority($temparr[1],$solutionarray);	
			$p2=extractPriority($temparr[0],$solutionarray);		
			$k=2*10;
			$p=$p+$p2;
			$p=1-($p/$k);
			echo "Normalized Priority is $p";
			$conflictValue=$combinedQuantificationVal[1]*$p;
			//echo "$conflictValue";
			$contributionValue2=extractContribution($temparr[0]);
			$contributionValue2=$contributionValue2-$conflictValue;
			$contributionValue1=extractContribution($temparr[1]);
			echo "<br>Contribution value for $temparr[1] is ".$contributionValue1." Contribution value for $temparr[0] is ".$contributionValue2;
			checkThreshold($temparr[1],$contributionValue1,$temparr[0],$contributionValue2,$solutionarray,$no_of_solutions);
	}
?>