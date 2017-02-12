<?php
	session_start();

	// Déclaration des constantes
	define('PREFIX_SALT', '16478931');
	define('SUFFIX_SALT', '489923141');

	// Déclaration des variables
	$nb = 0;
	$posL = 0;
	$posT = 0;

	// On récupère l'id de l'utilisateur
	$id = base64_decode($_SESSION['connect']);
	$id = str_replace(PREFIX_SALT, "", $id);
	$id = str_replace(SUFFIX_SALT, "", $id);

	// On stocke le tableau de position
	$tableJson = $_POST['tableJson'];
	
	// On récupère l'indice
	if(isset($_POST['indice']) && (!empty($_POST['indice']) || $_POST['indice']=="0")){
		$indice = $_POST['indice'];
	}
	// on récupère les données pour enregistrement
    if(isset($_POST['tableJson']) && !empty($_POST['tableJson'])) {
        
        // Connexion à la bdd
		include("../include/bdd.php");

    	foreach ($tableJson[$indice] as $key => $value) {

    		if ($key == "posL") {

    			$posL = $value; // Position Left

    		}elseif ($key == "posT"){

    			$posT = $value; // Position Top
    		}

    		// On met à jour la fiche en cours
			$requete = "UPDATE notes SET posL='$posL',posT='$posT' WHERE user_id='$id' and numN='$indice'";

			// On exécute la requête
			$resultat = $bdd->query($requete) or die(print_r($bdd->errorInfo()));

			// On ferme la requête
			$resultat->closeCursor();

    	}
    }
?>