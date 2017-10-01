//the script to insert data into the address database
<?php
	SESSION_START();
  	include('ess.php');
if(isset($_POST['submit']))
  	{
  	//geting inputs from the form and affecting them to the variables
  	$pays = addslashes($_POST["pays"]); 
	$province = addslashes($_POST["province"]); 
	$ville = addslashes($_POST["ville"]); 
	$quartier = addslashes($_POST["quartier"]); 
	$avenue = addslashes($_POST["avenue"]); 
	$anned = addslashes($_POST["anned"]); 
	$annef = addslashes($_POST["annef"]); 
	$userid = $_SESSION["userid"];
	//preparing the query to insert data into database
	$requete="INSERT INTO adresse(userid,pays,province,ville,quartier,avennue,anned,annef) VALUES('$_SESSION[userid]','$_POST[pays]','$_POST[province]','$_POST[ville]','$_POST[quartier]','$_POST[avenue]','$_POST[anned]','$_POST[annef]')";
	//connecting to database and inserting data
		if($d=mysqli_query($_conn,$requete))
		{
		//rediretcting user to the main page
		header("location:accueil.php");
		}
	else
		{
			echo "sans succès";
		}
	}
?>