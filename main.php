<?php
	// Ouverture de la session
	session_start();

	if(isset($_SESSION["connect"])){

		// Déclaration des constantes
		define('PREFIX_SALT', '16478931');
		define('SUFFIX_SALT', '489923141');

		// On récupère l'id encodé
		$idX = $_SESSION["connect"];

		// On decode l'id en base 64
		$id = base64_decode($idX);
		$id = str_replace(PREFIX_SALT, "", $id);
		$id = str_replace(SUFFIX_SALT, "", $id);

		// On inclut la classe de decryptage
		include("include/parameter.php");

		$decrypte = new endeCrypte(); // On instancie un objet endeCrypte
		
		// Connexion à la bdd
		include("include/bdd.php");

		// On recherche les notes de l'utilisateur  
		$requete = "SELECT * FROM notes WHERE user_id='$id' ORDER BY Id ASC";
		// On exécute la requête
		$resultat = $bdd->query($requete) or die(print_r($bdd->errorInfo()));

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Gestionnaire de mot de passe</title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="shortcut icon" type="image/x-icon" href="assets/img/key.bmp" />
		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="assets/css/bootstrap.min.css" crossorigin="anonymous">
		<!-- Feuille de style CSS -->
        <link rel="stylesheet" href="assets/css/styles2.css" />
        <script>
        	var tableJson = {};
        </script>
	</head>
	<body>
		<div id="size"></div>
		<div class="form-group menu">
			<div class="col-sm-1 col-xs-3">
				<button id="newF" type="submit" class="btn btn-success">Nouvelle note</button>
			</div>
          	<div class="col-sm-6 col-xs-3 col-xs-offset-1">
				<button id="modC" type="submit" class="btn btn-primary">Modifier code</button>
			</div>
			<div class="col-sm-3 col-xs-2 col-xs-offset-1">
				<button id="deco" type="submit" class="btn btn-warning">Deconnexion</button>
			</div>
		</div>
		<div class="container">
			<?php while($donnees = $resultat->fetch(PDO::FETCH_ASSOC)) {
				// A chaque tour de boucle
				$description_decrypte = $decrypte->mc_decrypt($donnees["description"]);
			?>
			<!-- Prototype -->
			<div class="note <?php echo $donnees["numN"]; ?>" style="top:<?php echo $donnees["posT"]; ?>px; left:<?php echo $donnees["posL"]; ?>px;">
				<form class="form-horizontal" method="post" accept-charset="utf-8">
					<div class="form-group title">
					<img class="point" src="assets/img/punaise.png" width="45" height="45" alt="punaise">
					<img class="plus" src="assets/img/loupe.png" width="35" height="35" alt="plus" title="Voir le contenu">
						<h5 class="des"><?php echo $description_decrypte; ?></h5>
					</div>
					<div class="form-group log">
						<label class="control-label col-sm-2 col-xs-2" for="login">Login:</label>
						<div class="col-sm-10 col-xs-10">
							<input id="login" class="form-control" type="text" disabled="disabled" name="login" value="*****" />
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2 col-xs-2" for="pwd">Pass:</label>
						<div class="col-sm-10 col-xs-10"> 
							<input  id="pwd" class="form-control" type="text" disabled="disabled" name="pwd" value="*****" />
						</div>
					</div>
					<div class="grbt <?php echo $donnees["numN"]; ?>" class="form-group"> 
						<div class="col-sm-10 col-sm-offset-2 col-xs-10 col-offset-2">
							<input type="submit" class="btn btn-success modif" name="enr" value="Enregistrer" />
							<input type="hidden" class="hide" name="IdNote" value="<?php echo $donnees["Id"]; ?>" />
							<button class="btn btn-danger sup" title="Supprimer la note"><span class="glyphicon glyphicon-trash"></span></button>
						</div>
					</div>
				</form>
			</div>
			<script>
				tableJson["<?php echo $donnees["numN"]; ?>"]={"posL":<?php echo $donnees["posL"]; ?>,"posT":<?php echo $donnees["posT"]; ?>};
			</script>
			<?php
			}
				// On ferme la requête
				$resultat->closeCursor();
			?>
			<div id="bulle">
				<form id="formB" method="post" accept-charset="utf-8">
					<h3 id="libCode">Validation du code</h3>
					<img id="img2" src="assets/img/croix.png" width="35" height="35" alt="moins" title="Fermer la bulle">
					<img id="img1" src="assets/img/bulle.png" alt="bulle">
					<input type="hidden" name="noteId" value="">
					<input id="chCode" class="form-control" type="password" name="codeP" placeholder="Code pin..." />
					<p id="error"></p>
					<input id="valideCode" class="btn btn-info" type="submit" name="valideC" value="Ok" />
				</form>
			</div>
			
			<!-- Prototype -->
			<div id="blocnote" dataprototype="<div class=&quot;note numM&quot; style=&quot;top:57px; left:42px;&quot;><form id=&quot;formN&quot; method=&quot;post&quot; class=&quot;form-horizontal&quot; accept-charset=&quot;utf-8&quot;><div class=&quot;form-group&quot;><div class=&quot;col-sm-10 col-sm-offset-1&quot;> <input id=&quot;desc&quot; onchange=&quot;this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);&quot; class=&quot;form-control&quot; type=&quot;text&quot; name=&quot;desc&quot; placeholder=&quot;Description&quot; /></div></div><div class=&quot;form-group&quot;> <label class=&quot;control-label col-sm-2 col-xs-2&quot; for=&quot;login&quot;>Login:</label><div class=&quot;col-sm-10 col-xs-10&quot;> <input id=&quot;login&quot; class=&quot;form-control&quot; type=&quot;text&quot; name=&quot;login&quot; /></div></div><div class=&quot;form-group&quot;> <label class=&quot;control-label col-sm-2 col-xs-2&quot; for=&quot;pwd&quot;>Pass:</label><div class=&quot;col-sm-10 col-xs-10&quot;> <input id=&quot;pwd&quot; class=&quot;form-control&quot; type=&quot;text&quot; name=&quot;pwd&quot; /></div></div><p id=&quot;error2&quot;></p><div class=&quot;grbt numM&quot;><div class=&quot;col-sm-10 col-sm-offset-1&quot;> <input id=&quot;numCache&quot; type=&quot;hidden&quot; name=&quot;numCache&quot; /><input type=&quot;submit&quot; class=&quot;col-xs-6 btn btn-success&quot; name=&quot;enr&quot; value=&quot;Enregistrer&quot; /><input id=&quot;reset&quot; type=&quot;reset&quot; class=&quot;col-xs-5 col-xs-offset-1 btn btn-primary&quot; name=&quot;enr&quot; value=&quot;Annuler&quot; /></div></div></form></div>">
			</div>
		</div>
		<!-- JavaScript & Jquery -->
		<script src="assets/js/jquery-1.12.4.js"></script>
  		<script src="assets/js/ui/1.12.0/jquery-ui.js"></script>
		<script src="assets/js/script2.js"></script>
	</body>	
</html>
<?php 

	}else{
		// Chemin racine du site
		$path = "Location: ".$_SERVER["REQUEST_SCHEME"]."://".$_SERVER["SERVER_NAME"]."/"; 
		// Puis on redirige sur le login
		header($path);
		exit;
	}
?>
