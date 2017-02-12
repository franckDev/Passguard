<?php 
	session_start(); // On démarre la session

	// On inclut la classe d'encryptage
	include("../include/parameter.php");

	// On instancie un objet endeCrypte
	$decrypte = new endeCrypte(); 

	if(isset($_POST['codeP']) && !empty($_POST['codeP'])){

		$code = htmlspecialchars(addslashes($_POST['codeP'])); // On sécurise la chaine

		$code = str_replace(' ', '', $code); // On supprime les éventuels espaces

		if(strlen($code) == 4){ // On vérifie le nombre de chiffres du code pin

			// Connexion à la bdd
			include("../include/bdd.php");

			// Déclaration des constantes
			define('PREFIX_SALT', '16478931');
			define('SUFFIX_SALT', '489923141');

			// On récupère l'id encodé
			$idX = $_SESSION["connect"];

			// On decode l'id en base 64
			$user_id = base64_decode($idX);
			$user_id = str_replace(PREFIX_SALT, "", $user_id);
			$user_id = str_replace(SUFFIX_SALT, "", $user_id);

			// On récupère la clé de salage de l'utilisateur
			$requete = "SELECT salt FROM users WHERE id='$user_id'";

			// On exécute la requête
			$resultat = $bdd->query($requete) or die(print_r($bdd->errorInfo()));

			// On lit le résultat
			$response = $resultat->fetch();

			// On stocke le salage
			$salt = $response[0];

			// Hashage du code
			$codeX = hash('sha512',$salt.$code);

			// On vérifie le code pin
			$requete = "SELECT count(*) FROM users WHERE id='$user_id' AND code='$codeX'";

			// On exécute la requête
			$resultat = $bdd->query($requete) or die(print_r($bdd->errorInfo()));

			// On lit le résultat
			$response = $resultat->fetch();

			// On ferme la requête
			$resultat->closeCursor();

			if($response[0] == 1){

				$id = $_POST['noteId']; // Id de la note

				// Requête de sélection des infos concernant la note
				$requete = "SELECT  login, pass, description FROM notes WHERE numN='$id' AND user_id='$user_id'";

				// On exécute la requête
				$resultat = $bdd->query($requete) or die(print_r($bdd->errorInfo()));

				// On lit le résultat
				$response = $resultat->fetch();

				$login = $decrypte->mc_decrypt($response[0]); // Décryptage du login
				$pass = $decrypte->mc_decrypt($response[1]); // Décryptage du mot de passe
				$description = $decrypte->mc_decrypt($response[2]); // Décryptage de la description
				
				// On ferme la requête
				$resultat->closeCursor();

				$class = array("response" => "correct","login" => $login,"pass" => $pass, "note" => $id, "desc" => $description);

			}else{ // Code non valide
				$class = array("response" => "incorrectCode");
			}

		}else{ // Code trop grand ou trop petit
			$class = array("response" => "incorrectNomb");
		}

	}elseif (isset($_POST['codeP']) && empty($_POST['codeP'])) { // Aucun code saisi
		$class = array("response" => "empty");
	}

	echo json_encode($class);
?>