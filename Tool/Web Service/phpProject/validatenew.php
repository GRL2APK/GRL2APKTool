<?php
	session_start();
	$servername='localhost';
	$username='root';
	$password='';
	$db='consistencychecker';
	$userDetail=array();
    $userDetail=$_SESSION['userdetails'];
    $fname=$userDetail["fname"];
    $email=$userDetail["email"];
    echo '<h3>Welcome '.$fname.'</h3>';
    $priorityArray=array();
    $solutionArray=array();
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
			{
				$solutionArray[$i]=$row["solutionChosen"];
				$priorityArray[$i]=$row["priority"];
				$i=$i+1;
			}
		}

		$sol1=$solutionArray[0];
		$sol2=$solutionArray[1];
		//echo "<h4>Combination Trace between $sol1 and $sol2</h4>";
		$prio1=$priorityArray[0];
		$prio2=$priorityArray[1];
		$prio=$prio1+$prio2;
		$k=7;
		$prio=1-($prio/$k);
		
		$sql="select * from effectivecontribution where (sol1='".$sol1."' or sol2='".$sol1."') and (sol1='".$sol2."' or sol2='".$sol2."')";
		$result = mysqli_query ($con,$sql);
		
		if ($result->num_rows > 0) 
		{
			while($row = mysqli_fetch_assoc($result)) 
			{
				$sigma=$row["sigma"];
				//echo "Contribution in the NFR Trace <".$sol1.", ".$sol2."> is $sigma <br>";
				//echo "Normalized Priority is $prio <br>";
				$rho=$sigma*$prio;
				//echo"Quality Metric is $rho";
				if($rho>=0.35)
				{
					$myfile = fopen("NFRCombination.txt", "w") or die("Unable to open file!");
			        $solop1 = "$sol1"."\r\n";
			        fwrite($myfile, $solop1);
			        //fwrite($myfile, "the next operationalization is \n\r \r\n \n");
			        $solop2 =$sol2;
			        fwrite($myfile, $solop2);
			        fclose($myfile);
					//echo '<div align="left"><label for="msg"><font color="#660066"><h2>Congratulations...<br>You have chosen valid solution. Your selected solution is saved successfully.<br> You can choose different solution as well. This will replace the previous selection.</h2></font></label></div>';
		
				}
				else
				{
					$_SESSION['updaterequired'] = 1;
			//echo '<div align="center"><label for="msg"><font color="red"><h2>Sorry...<br>You have chosen invalid solution</h2></font></label></div>';

				}
			}
		}


	}
	$con->close();
//<br><a href="./allcombination.php">You can choose optimal solution.</a>
	//<br><a href="./testui.php">You can change your selection</a><br><a href="./allcombination.php">You can choose optimal solution.</a>
?>


<html>
	<head>
	<style type="text/css">
  table          {border:ridge 5px black;}
  table td       {border:inset 1px #000;}
  table tr#valid  {background-color:purple; color:white;}
  table tr#invalid  {background-color:orange;}
  table tr#dev  {background-color:aqua; color:black;}
  table tr#nf {background-color:Maroon; color:white;}
  table tr#opt {background-color:green; color:white;}

  table td#valid  {background-color:purple; color:white;}
  table td#invalid  {background-color:orange;}
  table td#dev  {background-color:aqua; color:black;}
  table td#nf {background-color:Maroon; color:white;}
  table td#opt {background-color:green; color:white;}

  label { display: block; width: 100px; }
  .button {
  background-color: #008CBA;
  border: none;
  color: white;
  padding: 15px 32px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  margin: 4px 2px;
  cursor: pointer;
}
</style>
	<script type="text/javascript">
		function extractTableData() {
        document.getElementById('info').innerHTML = "";
        var hid = document.getElementById('hidsol');
        var myTab = document.getElementById('tblsol');
        document.getElementById('btnsubmit').disabled=false;
        // LOOP THROUGH EACH ROW OF THE TABLE AFTER HEADER.
       
            	var ele = document.getElementsByName('selectsol'); 
              // var inv=document.getElementsByName('invradio');
              // for(j = 0; j<inv.length;j++)
              // {
              // 	inv[j].style.display='none';
              // }
              
            for(i = 0; i < ele.length; i++) { 
            	//alert(i+" "+ele[i].checked);
                if(ele[i].checked) 
                {
                	
                    var objCells=myTab.rows.item(i+1).cells;
                    var qm=parseFloat(objCells.item(5).innerHTML);
                    var conf=parseFloat(objCells.item(3).innerHTML);
                    if(qm>0.35)
                    {
                    	info.innerHTML = objCells.item(1).innerHTML;
                    	hidsol.value=objCells.item(1).innerHTML;	
                    }
                    else if(conf==0.0)
                    {
                    	alert("Your solution has Quality metric less than Threshold. Please check before proceed!!");
                    	info.innerHTML = objCells.item(1).innerHTML;
                    	hidsol.value=objCells.item(1).innerHTML;	
                    }
                    else
                    {
                    	alert("You have to select a valid solution with valid Quality Metric");
                    	alert(objCells.item(5).innerHTML);
                    	document.getElementById('btnsubmit').disabled=true;
                    }
                    
                }
               }
                	
                
                
          
    
        
    }
	</script>
	</head>
	<body>
	
	<!--<h3>Other Valid solutions are as follows.</h3><br>
	
	<br>-->
	Quality Threshold is 0.35
	<form name="form1" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                
		<table border="1" id="tblsol">
			<tr>
				<th></th><th>Solution</th><th>Contri<br>-bution</th><th>Conflict</th><th>Sigma</th><th>Quality<br>Metric</th>

			</tr>
			<tr id="opt">
					<td ><input type="radio" name="selectsol" value="1" onclick="extractTableData()"></td>
					<td>LZ, BlowFish</td><td>0.72</td><td>0.0</td><td>0.72</td><td>0.411</td>
			</tr>
			<tr id="dev">
					<td><input type="radio" name="selectsol" value="0" onclick="extractTableData()"></td>
					<td>LZ, 3DES_Encryption</td><td>0.65</td><td>0.0</td><td>0.65</td><td>0.371</td>
			</tr>
			<tr id="invalid">
					<td ></td>
					<td>LZ, AES_Encryption</td><td>0.213</td><td>0.172</td><td>0.041</td><td>0.023</td>
			</tr>
			<tr id="invalid">
				<td></td>
				<td>LZ, DES_Encryption</td><td>0.260</td><td>0.144</td><td>0.116</td><td>0.066</td>
			</tr>

			

			<tr id="valid">
					<td ><input type="radio" name="selectsol" value="2" onclick="extractTableData()"></td>
					<td>CM, BlowFish</td><td>0.63</td><td>0.0</td><td>0.63</td><td>0.36</td>
			</tr>
			<tr id="invalid">
					<td ></td>
					<td>CM, 3DES_Encryption</td><td>0.29</td><td>0.126</td><td>0.164</td><td>0.094</td>
			</tr>
			<tr id="invalid">
					<td ></td>
					<td>CM, AES_Encryption</td><td>0.447</td><td>0.032</td><td>0.415</td><td>0.237</td>
			</tr>
			<tr id="nf">
					<td ></td>
					<td>CM, DES_Encryption</td><td>0.117</td><td>0.23</td><td>-0.113</td><td>-0.065</td>
			</tr>

			
			<tr id="invalid">
					<td ></td>
					<td>PPM, BlowFish</td><td>0.58</td><td>0.0</td><td>0.58</td><td>0.331</td>
			</tr>
			<tr id="nf">
					<td ></td>
					<td>PPM, 3DES_Encryption</td><td>0.183</td><td>0.190</td><td>-0.007</td><td>-0.004</td>
			</tr>
			<tr id="invalid">
					<td></td>
					<td>PPM, AES_Encryption</td><td>0.268</td><td>0.139</td><td>0.129</td><td>0.074</td>
			</tr>
			<tr id="valid">
					<td><input type="radio" name="selectsol" value="3" onclick="extractTableData()"></td>
					<td>PPM, DES_Encryption</td><td>0.635</td><td>0.0</td><td>0.635</td><td>0.362</td>
			</tr>
			

			
			
		</table>
		<br>
		<!--<br><h2><font color="blue">Optimal Solution is the combination of </font><font color="red">LZ</font><font color="blue"> and </font><font color="red">BlowFish</font> <font color="blue">and the Quality value is </font><font color="red">0.411</font></h2>-->
		<input type="submit" class="button" name="submit" id="btnsubmit" value="Proceed">
		<p id="info" name="info"></p>
		<input type="hidden" name="hidsol" id="hidsol">
		</form>

		<table>
		<tr>
		<td id="valid"><label></label></td><td>Feasible Sub-optimal Solution</td>
		</tr>
		<tr>
		<td id="nf"></td><td>Not Feasible Solution</td>
		</tr>
		<tr>
		<td id="invalid"></td><td>Feasible Bad Quality Solution</td>
		</tr>
		<tr>
		<td id="dev"></td><td>Developer's Choice</td>
		</tr>
		<tr>
		<td id="opt"></td><td>Optimal Solution</td>
		</tr>
		</table>
	</body>
</html>
<?php
    if($_SERVER["REQUEST_METHOD"]=="POST")
      {
        if(isset($_POST["submit"]))
          {
          	 $sol=textInput($_POST["hidsol"]);
          	 //echo "selected solution is $sol";
          	 //$string = "123,46,78,000"; 
			$str_arr = explode (",", $sol);  
			$op1=$str_arr[0];
			$op2=$str_arr[1];
			$op1=trim($op1);
			$op2=trim($op2);
			echo "$op1<br>";
			echo"$op2";
			$myfile = fopen("NFRCombination.txt", "w") or die("Unable to open file!");
			$solop1 = $op1."\r\n";
	        fwrite($myfile, $solop1);
	        $solop2 = $op2;
	        fwrite($myfile, $solop2);
	        fclose($myfile);
	        echo"<script>alert('Your solution has been submitted successfully!')</script>";
          	header('Location: ./index.php'); 
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