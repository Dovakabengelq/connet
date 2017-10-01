
<script src="https://www.gstatic.com/firebasejs/4.3.0/firebase.js"></script>
<script>
  // Initialize Firebase
  var config = {
    apiKey: "AIzaSyC-bfE5naxAsU5Z6CVM95UBPZXy_6DKVUk",
    authDomain: "profiler-9d9d9.firebaseapp.com",
    databaseURL: "https://profiler-9d9d9.firebaseio.com",
    projectId: "profiler-9d9d9",
    storageBucket: "",
    messagingSenderId: "429175849747"
  };
  firebase.initializeApp(config);
</script>

<?php
include("header.php");
include("option.php");
include("upload.php");
//script to 
include("fs.php");
if(isset($_GET["usere"]))
{
	//explodes the text provided by user if it contains more words
	$gete=explode(" ",addslashes($_GET["usere"]));
	//initializing a query for every words in the input
	foreach($gete as $get)
	{
		//preparing the sql query
		$ge="select * from connetmembres where prenom LIKE '%$get%' || nom LIKE '%$get%' OR prenom LIKE '%$get[0]%' && nom LIKE '%$get[1]%'";
		//sending the query and checking if the result is not empty
		if(!empty($qge=mysqli_query($_conn,$ge)))
		{
		//starts printing a modal div
			echo "<div id='users'>";
			//fetching all data from the database
			while($fge=mysqli_fetch_assoc($qge))
			{
			//printing data
				echo "<a href='profil.php?personne=$fge[identifiant]&submit=rechercher'><img src='upload/".$fge['photo']."' width='30px' height='40px' align='bottom'/><ombreu>".$fge["prenom"]." ".$fge["nom"]."</ombreu></a><br/>";
			}
			echo "</div>";
		}
	}
}

/*main page starts*/
?>
	<section>
<?php
	/*left side of the web site*/
	?>
		<div id = left>
		 <?php
			echo "<center>VOUS AVEZ PEUT-ETRE RENCONTRE</center>";
	//preparing a query for users at same address with the current one
			$adresse="select * from adresse where userid ='$_SESSION[userid]'";
			//checks if the result is not empty
			if(! empty($qqq=mysqli_query($_conn,$adresse)))
			{
				$fpays=mysqli_fetch_assoc($qqq);
			}//users the travelled to the same place at a same period
			$lieu ="select * from voyage where userid='$_SESSION[userid]'";
			//checks the result if it is empty
			if(!empty($qqi=mysqli_query($_conn,$lieu)))
			{
				$flieu=mysqli_fetch_assoc($qqi);
			}
			//users the worked for the same enterprise 
			$travail="select * from travail where userid='$_SESSION[userid]'";
			//checks If the result is not empty
			if(! empty($qtravail= mysqli_query($_conn,$travail)))
			{
				$ftravail=mysqli_fetch_assoc($qtravail);
			}
			//users that studied at the same school
			$ecole="select * from formation where userid='$_SESSION[userid]'";
			//checks if not empty
			if(! empty($qecole= mysqli_query($_conn,$ecole)))
			{
				$fecole=mysqli_fetch_assoc($qecole);
			}
			//preparing a query with above fetched data about the work
		 	$query = "select * from connetmembres where membreid IN(
			select userid from travail where entreprise='$ftravail[entreprise]' and anned BETWEEN $ftravail[anned] and $ftravail[annef] || anned = $ftravail[anned] OR annef BETWEEN $ftravail[anned] and $ftravail[annef]) AND identifiant NOT IN(select ami from amitie where identifiant='$_SESSION[identifiant]')"; 
			//checks if the query is not empty
		 	if(!empty($sql = mysqli_query($_conn, $query)))
		 		{
		 		//fetching all data from the query
		 		while($roww = mysqli_fetch_assoc($sql))
		 			{
		 			//checking if the found user is not the connected one
		 				if($roww["identifiant"]!=$_SESSION["identifiant"])
			 			{
		 				//printing data for user other than the current one
			 			//his or her photo
		 				echo "<a href='profil.php?personne=$roww[identifiant]&submit=rechercher'><img src = 'upload/";
		 				echo $roww['photo']."' width = '20px' height = '25px' align = 'bottom'/></b>";
		 				//first name and family name
		 				echo "<db class= ombrecc> ".ucfirst($roww['prenom'])."  ".strtoupper($roww['nom'])."</db><br/></a><br/>";
			 			}
		 			}
		 		}
		 		//querying users who were at the same address
		 		//the following procedures are in the same order and purpose with the above ones for relationships with others users
		 	$ad = "select * from connetmembres where membreid IN(select userid from addresse where pays = '$fpays[pays]' and ville  = '$fpays[ville]' and quartier = '$fpays[quartier]') AND identifiant NOT IN (select ami from amitie where identifiant = '$_SESSION[identifiant]')"; 
		 	if(!empty($squery = mysqli_query($_conn, $ad)))
		 	{
		 		while($fp = mysqli_fetch_assoc($squery))
		 		{
		 			if($fp['identifiant']!=$_SESSION['identifiant'])
		 			{
		 				echo "<a href = 'profil.php?personne=$fp[identifiant]&submit=rechercher'><img src='upload/'";
		 				echo $fp["photo"]."' width = '20px' height = '25px' align = 'bottom'/></b>"; 
		 				echo "<db class = ombrecc>".ucfirst($fp["prenom"])." ".strtoupper($fp["nom"])."</db><br/></a><br/>"; 
		 			} 
		 			
		 		}
		 	}
			$quer= "select * from connetmembres where membreid IN(
			select userid from voyage where lieu='$flieu[lieu]' && anne = '$flieu[anne]' UNION
			select userid from formation where ecole='$fecole[ecole]' && anned=$fecole[anned] || anned BETWEEN $fecole[anned] and $fecole[annef] OR annef BETWEEN $fecole[annef] and $fecole[annef]) AND identifiant NOT IN(select ami from amitie where identifiant='$_SESSION[identifiant]')"; 
		 	if(!empty($sq = mysqli_query($_conn, $quer)))
		 		{
		 		while($ro = mysqli_fetch_assoc($sq))
		 			{
		 			if($ro["identifiant"]!=$_SESSION["identifiant"])
		 			{
		 				echo "<a href='profil.php?personne=$ro[identifiant]&submit=rechercher'><img src = 'upload/";
		 				echo $ro['photo']."' width = '20px' height = '25px' align = 'bottom'/></b>";
		 				echo "<db class= ombrecc> ".ucfirst($ro['prenom'])."  ".strtoupper($ro['nom'])."</db><br/></a><br/>";
		 			}
		 			}
		 		}
		 		
		 	//end of the same procedures 
		 	
		 	//mysqli_free_result();
		 ?>
		</div>
		<div id = right>
		<center>
		<?php 
		//a form for publishing posts and photos
		?>
				<div id=form>
			<form METHOD = "POST" ACTION = "poster.php">
				<textarea cols = "70" rows = "5" name = "messa" class = 'lo' placeholder="Quelles sont les nouvelles <?php echo ucfirst($fqueryh['prenom']); ?> ?"></textarea><br/>
				<label><input type = "submit" name = "submit" class='buttona' value = "POST"/>
				<li class="buttona"><a href = "accueil.php?phto=1" >AJOUTER DES PHOTOS</a></li>
				</label>
			</form>
		</div></center>
			<div  id=style>
		<?php
		//preparing query for the posts of friends
				$uery = "SELECT * FROM posts WHERE posteur IN(SELECT DISTINCT ami FROM amitie WHERE identifiant = '$_SESSION[identifiant]' UNION SELECT DISTINCT ami FROM amitie WHERE ami ='$_SESSION[identifiant]') ORDER BY id DESC";
				$dery=mysqli_query($_conn,$uery);
				if(empty($dery))
				{
				//message printed when the user doesn't have any friend
					echo "<div id=ombrec>vous n'avez encore aucun ami! alors on ne pourra pas afficher même vos propres posts car ils ne sont pas publiques</div>";
				}
				else
				{
				while($wqury=mysqli_fetch_assoc($dery))
				{
				//querying users'infornations for each ppst
				$iquery = "SELECT * FROM connetmembres WHERE identifiant='$wqury[posteur]'";
				if($qiquery = mysqli_query($_conn, $iquery))
				{
					while($wiquery = mysqli_fetch_assoc($qiquery))
						{
						//printing profile informations and posts for each post found
						echo "<div id = '$wqury[id]' class='post'>";
						echo "<a href='profil.php?personne=$wqury[posteur]&submit=rechercher'><img src = 'upload/".$wiquery['photo']."' width = '20px' height = '23px' align = 'bottom'/>  <b class = ombrecc>".ucfirst($wiquery['prenom'])." ".strtoupper($wiquery['nom'])."</b></a><br/><br/>";
						//exploding the post
						$parse=explode(" ",$wqury["message"]);
						//counting words in the post
						$rei=count($parse);
						if($rei>35)
						{
						//if the user wants to read the whole post
							if(isset($_GET["suite"]))
							{
								if($_GET["suite"]==$wqury["id"])
								{
									echo $wqury["message"]."<br/><b><i>".$wqury['date']." à ".$wqury['heure']."</i></b><br/>";
								}
								else
								{
								//printing the part of the message for the first to the page is loaded
									$compat=implode(" ",$fin=array($parse[0],$parse[1],$parse[2],$parse[3],$parse[4],$parse[5],$parse[6],$parse[7],$parse[8],$parse[9],$parse[10],$parse[11],$parse[12],$parse[13],$parse[14],$parse[15],$parse[16],$parse[17],$parse[18],$parse[19],$parse[20],$parse[21],$parse[22],$parse[23],$parse[24],$parse[25],$parse[26],$parse[27],$parse[28],$parse[29],$parse[30],$parse[31],$parse[32],$parse[33],$parse[34]));
									echo $compat."...<a href='accueil.php?suite=$wqury[id]#$wqury[id]'>lire la suite</a><br/><b><i>".$wqury['date']." à ".$wqury['heure']."</i></b><br/>";	
								}
							}
							else
							{
							//same thing for the part of the message
							$compat=implode(" ",$fin=array($parse[0],$parse[1],$parse[2],$parse[3],$parse[4],$parse[5],$parse[6],$parse[7],$parse[8],$parse[9],$parse[10],$parse[11],$parse[12],$parse[13],$parse[14],$parse[15],$parse[16],$parse[17],$parse[18],$parse[19],$parse[20],$parse[21],$parse[22],$parse[23],$parse[24],$parse[25],$parse[26],$parse[27],$parse[28],$parse[29],$parse[30],$parse[31],$parse[32],$parse[33],$parse[34]));
							echo $compat."...<a href='accueil.php?suite=$wqury[id]#$wqury[id]'>lire la suite</a><br/><b><i>".$wqury['date']." à ".$wqury['heure']."</i></b><br/>";
							}
						}
						else 
						{
						//if the number ofbwords I. the post doesn't go beyond 35 print the whole post
							$et=implode(" ",$parse);
							echo $et."<br/><b><i>".$wqury['date']." à ".$wqury['heure']."</i></b><br/>";
						}
						//ifbthere is a photo in the post
						if(!empty($wqury['photo']))
						{
						//if there is more photos
							$imgs = explode(":",$wqury['photo']);
							foreach($imgs as $iml)
							{
							//if the name of the photo is not empty
								if(!empty($iml))
								{
								//printing photo
								echo "<a href='accueil.php?view=$iml'><img src = 'upload/$iml' width = '200px' height = '210px'/></a>";
								}
							}
						}
					echo "</div>";
					//counting comments and shares on the post
					$count = "select count(*) as nombre from comment where postid = '$wqury[id]'";
					$qcount = mysqli_query($_conn, $count);
					$ncount = mysqli_fetch_assoc($qcount);
					if($ncount['nombre']>0)
					{
					echo "<li class='button'><a href =accueil.php?q=".$wqury["id"].">";
					//printing different ages according to the number of comments
					echo "<img src = 'commentblue.png' width = '20px' height = '20px' align = 'center' padding-right = '2px'/>".$ncount['nombre']." votre avis</a></li>";
					$share="select count(*) as compte from partage where postid = '$wqury[id]'";
					$qshare = mysqli_query($_conn,$share);
					$fshare=mysqli_fetch_assoc($qshare);
					if($fshare["compte"]>0)
					{
					//prints different image according to the number of shares
					//same procedures for following tests
						echo " <li class='button'> <a href = 'accueil.php?f=$wqury[id]&p=$wqury[posteur]'><img src = 'shareblue.png' width = '20px' height = '20px' align = 'center' padding-right = '2px'/>".$fshare["compte"]."faire suivre</a></li>";
					}
					else
					{
						echo "<li class='buttong'> <a href = 'accueil.php?f=$wqury[id]&p=$wqury[posteur]'><img src = 'sharegris.png' width = '20px' height = '20px' align = 'center' padding-right = '2px'/>faire suivre</a></li>";
					}
					}
					else
					{
					echo "<li class='buttong'><a href = 'accueil.php?q=".$wqury['id']."'>";
					echo "<img src = 'commentgris.png' width = '20px' height = '20px' align = 'center' padding-right = '2px'/> votre avis</a></li>";
					$share="select count(*) as compte from partage where postid = '$wqury[id]'";
					$qshare = mysqli_query($_conn,$share);
					$fshare=mysqli_fetch_assoc($qshare);
					if($fshare["compte"]>0)
					{
						echo " <li class='button'> <a href = 'accueil.php?f=$wqury[id]&p=$wqury[posteur]'><img src = 'shareblue.png' width = '30px' height = '30px' align = 'center' padding-right = '2px'/> faire suivre</a></li>";
					}
					else
					{
						echo "<li class='buttong'><a href = 'accueil.php?f=$wqury[id]&p=$wqury[posteur]'><img src = 'sharegris.png' width = '30px' height = '30px' align = 'center' padding-right = '2px'/> faire suivre</a></li>";
					}
					}
					}
				}
			}
		}

	?>
			</div>
	</section>
<?php
//a script to visualize images
include("viewer.php");
//the footer of the page
include("footer.php");
if(!empty($dery))
{
	mysqli_free_result($dery);
}
?>
	