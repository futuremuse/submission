<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Loan Submission Form</title>
	<link rel="stylesheet" type="text/css" href="loansub.css">
	<?php include 'dbsettings.php'; ?>
	<script>
		function validate() {
			document.getElementById("errormsg").style.display = "none";
			var loan = document.forms["loansubform"]["loan"].value;
			var propvalue = document.forms["loansubform"]["propvalue"].value;
			var ssn = document.forms["loansubform"]["ssn"].value;
			if ((isNaN(loan) || loan == "") || (isNaN(propvalue) || propvalue == "") || (isNaN(ssn) || ssn == "" || ssn.length < 9)) {
				if (isNaN(loan) || loan == "") {
					document.getElementById("loan").style.background = "yellow";
				} else {
					document.getElementById("loan").style.background = "white";
				}
				if (isNaN(propvalue) || propvalue == "") {
					document.getElementById("propvalue").style.background = "yellow";
				} else {
					document.getElementById("propvalue").style.background = "white";
				}
				if (isNaN(ssn) || ssn == "" || ssn.length < 9) {
					document.getElementById("ssn").style.background = "yellow";
				} else {
					document.getElementById("ssn").style.background = "white";
				}
				document.getElementById("errormsg").style.display = "block";
				return false;
			}
		}
	</script>
</head>
<body>
<?php

	$dbconn = mysqli_connect($server,$username,$password,$db);

	if(mysqli_connect_errno()) {
		echo "I cannot connect to the database<br><br>";
		exit;
	}

	if(isset($_POST["loan"]) && $_POST["loan"] != "") {
		$loan = $_POST["loan"];
	}
	if(isset($_POST["propvalue"]) && $_POST["propvalue"] != "") {
		$propvalue = $_POST["propvalue"];
	}
	if((isset($_POST["ssn"]) && $_POST["ssn"] != "")) {
		$ssn = $_POST["ssn"];
	}
	if($loan != "" && $propvalue != "") {
		$ltv = $loan / $propvalue;
		if($ltv > 0.4) {
			$msg = "Rejected";
		} else {
			$msg = "Accepted";
		}
	}

	if(!$loan || !$propvalue || !$ssn || !$msg) {
		echo "<!-- missing data<br><br> -->";
		$loancreated = false;
	} else {
		$loan = (int)$loan;
		$propvalue = (int)$propvalue;
		$loansubmissionsql = "INSERT INTO loanrecords (loanamt,propvalue,ssn,acceptance) VALUES(".$loan.",".$propvalue.",'".$ssn."','".$msg."')";
		if(mysqli_query($dbconn,$loansubmissionsql)) {
			echo "<!-- loan record created<br><br> -->";
			$loancreated = true;
		} else {
			echo "Error: " . $loansubmissionsql . "<br>" . mysqli_error($dbconn) . "<br><br>";
		}
	}
	

	$query = "SELECT * FROM loanrecords";
	$result = mysqli_query($dbconn,$query);
	$num_results = mysqli_num_rows($result);
	echo "<!-- Results: " . $num_results . "<br> -->";

	mysqli_free_result($result);
	mysqli_close($dbconn);

?>
<div id="container">
	<h1>Loan Request Submission</h1>
	<div id="errormsg">Please correct highlighted inputs</div>
<?php if (!$loancreated) { ?>
	<div id="loansub">
		<form name="loansubform" id="loansubform" action="loansub.php" method="POST" onsubmit="return validate()" >
			<div class="wrapinput">
			<div class="label"><label>Loan Amount:</label> </div>
			<div class=""><input type="text" name="loan" id="loan" class="textin"></div>
			</div>
			
			<div class="wrapinput">
			<div class="label"><label>Property Value:</label> </div>
			<div class=""><input type="text" name="propvalue" id="propvalue" class="textin"></div>
			</div>
			
			<div class="wrapinput">
			<div class="label"><label>SSN:</label> </div>
			<div class=""><input type="text" name="ssn" id="ssn" class="textin" maxlength="9"></div>
			</div>
			
			<div class="wrapinput">
			<div class="label">&nbsp;</div>
			<div class=""><input type="submit" value="Request loan" class="submit"></div>
			</div>
		</form>
	</div>
<?php } else { ?>
	<div id="resultmsg">
		<h2>Thank you for your submission</h2>
		<p>Your loan has been <?= $msg ?></p>
<?php
		if ($msg == "Rejected") {
			echo "<p>This is because the Loan to Property Value is more than 40%</p>";
		}
?>
	</div>
<?php } ?>
<!-- Loan: <?= $loan ?><br>
Value: <?= $propvalue ?><br>
SSN: <?= $ssn ?><br>
&nbsp;<br>
LTV: <?= $ltv ?><br>
Message: <?= $msg ?><br>
 -->
</div>
</body>
</html>