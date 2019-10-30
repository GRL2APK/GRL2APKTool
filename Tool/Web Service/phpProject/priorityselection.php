<?php
session_start();
$myfile = fopen("softgoals.txt", "r") or die("Unable to open file!");
	$sgoal=array();
	$i=0;
	while(!feof($myfile)) 
	{
		$a=fgets($myfile) ;
		$a = trim(preg_replace('/\s\s+/', ' ', $a));
		$sgoal[$i]=$a;
		$i=$i+1;
	}
?>
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
    padding: 4px 15px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    float: center;
}

input[type=button]:hover {
    background-color: #D39C00;
}
input[type=button] {
    background-color: #F5BA10  ;
    color: white;
    padding: 4px 15px;
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
<script type="text/javascript">
	function listbox_moveacross(sourceID, destID) {
			var src = document.getElementById(sourceID);
			var dest = document.getElementById(destID);

			for(var count=0; count < src.options.length; count++) {

				if(src.options[count].selected == true) {
						var option = src.options[count];

						var newOption = document.createElement("option");
						newOption.value = option.value;
						newOption.text = option.text;
						newOption.selected = true;
						try {
								 dest.add(newOption, null); //Standard
								 src.remove(count, null);
						 }catch(error) {
								 dest.add(newOption); // IE only
								 src.remove(count);
						 }
						count--;

				}

			}

		}

		function getListboxElement()
		{
			var select1 = document.getElementById('d');
			var hid = document.getElementById('solstore');
			var values = new Array();
			var sols="";
			var prio=1;
			var msg="";
			for(var i=0; i < select1.options.length; i++){
			    values.push(select1.options[i].value);
			    sols=sols+" "+select1.options[i].value;
			    msg=msg+" "+select1.options[i].value+":&emsp; &emsp;"+prio+"<br>";
			    prio = prio+1;
			}
			var m="<h4>NFR &emsp; &emsp;&emsp;Priority</h4>";
			m=m+msg;
			document.getElementById('msg').innerHTML=m;
			//alert(msg);
			hid.value=sols;
		}
</script>
</head>

<body>
<h3>Priority Selection of NFRs </h3>
	<!--<h3>Select the priorities for your Non functional requirements. Different NFRs are listed in the left side box. <br>You can set priority by moving the NFRs in the right side box in ascending order.<font color="#EC1A05"><br> You must save the priorities before proceed to next screen.</h3> -->
<form name="form1" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
	<table>
	<tr valign="top">
	
<td></td>
<td>
<SELECT id="s" size="5" style="width: 120px;" multiple>
	<OPTION value="Confidentiality">Confidentiality</OPTION>
	<OPTION value="Efficiency">Efficiency</OPTION>
	
	
</SELECT>
</td>
<td valign="center">
<a href="#" onclick="if (!window.__cfRLUnblockHandlers) return false; listbox_moveacross('s', 'd')" data-cf-modified-325e9d9aab55b40320d9c6ea-="">&gt;&gt;</a>
<br/>
<a href="#" onclick="if (!window.__cfRLUnblockHandlers) return false; listbox_moveacross('d', 's')" data-cf-modified-325e9d9aab55b40320d9c6ea-="">&lt;&lt;</a>
</td>
<td>
<SELECT id="d" size="5" style="width: 115px;" multiple>
	
</SELECT>
</td>
</tr>
</table>

<input type="hidden" id="solstore" name="solstore" value="">
<br>
                 <div class="row">

                 <input type="button" value="Save" onclick="getListboxElement()">&nbsp;
                    <input type="submit" name="submit" value="Next">
                 </div>
                 <div id="msg"></div>
</form>
<script src="https://ajax.cloudflare.com/cdn-cgi/scripts/95c75768/cloudflare-static/rocket-loader.min.js" data-cf-settings="325e9d9aab55b40320d9c6ea-|49" defer=""></script>


</html>

<?php
	 if($_SERVER["REQUEST_METHOD"]=="POST")
      {
      		if(isset($_POST["submit"]))
          	{
          		$userDetail=array();
          		$solprio=array();
          		
            	$userDetail=$_SESSION['userdetails'];
          		$hid="solstore";
          		$v=$_POST[$hid];
          		$tok = strtok($v, " \n\t");
          		$prio=1;
				while ($tok !== false) {
				    echo "Word=$tok Priority=$prio<br />";

				    //echo "Priority for ".$sgoal[$i]." is $v<br>";
						$sol=$tok;
						$sol = trim($sol);
						$solprio[$sol]=$prio;
						$prio=$prio+1;
				    $tok = strtok(" \n\t");


				}
				$en="Encryption";
				$eff="Efficiency";
				echo"$solprio[$en] and $solprio[$eff]";

				$userDetail["solprio"]=$solprio;
					$userDetail["sgoal"]=$sgoal;
					$_SESSION['userdetails'] = $userDetail;
					$_SESSION['updaterequired']=0;
					//header('Location: ./testui.php'); 
					echo "<script> location.href='./testui.php'; </script>";
        				exit;
          		
          	}

      }

?>