<?php
	session_start(); // On démarre la session

	if($_POST["valideC"]=="Ok"){ /////// Validation du code ////////////

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

					echo "ok";

				}else{ // Code non valide
					echo "pas ok";
				}

			}else{ // Code trop grand ou trop petit
				echo "code tpg";
			}

		}elseif (isset($_POST['codeP']) && empty($_POST['codeP'])) { // Aucun code saisi
			echo "code none";
		}

	}elseif($_POST["valideC"]=="Modifier"){ /////// Mise à jour du code ////////////

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
				$requete = "UPDATE users SET code='$codeX' WHERE id='$user_id'";

				// On exécute la requête
				$resultat = $bdd->query($requete) or die(print_r($bdd->errorInfo()));

				// Nombre de ligne modifiées
				$nbLigne = $resultat->rowCount();

				// On ferme la requête
				$resultat->closeCursor();
				
				if($nbLigne > 0 || $bdd->errorInfo()[0] == "00000"){ // Mise à jour effectuée
					echo "ok";
				}else{ // Erreur
					echo "pas ok";
				}

			}else{ // Code trop grand ou trop petit
				echo "code tpg";
			}

		}elseif (isset($_POST['codeP']) && empty($_POST['codeP'])) { // Aucun code saisi
			echo "code none";
		}
	}
	
?>