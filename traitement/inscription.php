<?php
	// Ouverture de la session
	session_start();

	//Si le formulaire a été soumis
	if(isset($_POST['code'])) {
		// Si l'utilisateur a bien entré un code
		if (!empty($_POST['code'])) {
			// Conversion en majuscules
			$code = strtoupper($_POST['code']);
			$cap = $_SESSION['captcha'];

			// Cryptage et comparaison avec la valeur stockée dans $_SESSION['captcha']
			if( md5($code) == $cap ) {
				$class = executeEnreg(); // Le code est bon
			} else {
				$class="incorrect"; // Le code est erroné
			}
		} else { 
			$class="empty"; // Aucun code
		}
	}
	
	echo $class;

	function executeEnreg(){

		// Salt aléatoire
		$salt = sha1(uniqid(rand())); 

		$path = $_SERVER["REQUEST_SCHEME"]."://".$_SERVER["SERVER_NAME"]."/";
		
		$verification = true;

		// // Enregistrement du nouvel utilisateur // //

		// Si aucun champs n'est vide
		if(!empty($_POST["loginIns"]) && !empty($_POST["PassIns"]) && !empty($_POST["recoverEmail"]) && !empty($_POST["codePin"])) {

			// Contrôle sur les données
			$login = htmlspecialchars(addslashes($_POST["loginIns"]));
			
			$passMail = htmlspecialchars(addslashes($_POST["PassIns"])); // Pass clair
			$mdp =  hash('sha512', $salt.$passMail); // Hashage du mot de passe

			$email = $_POST["recoverEmail"];

			$code = $_POST["codePin"]; // Code clair
			$codeH = hash('sha512', $salt.$code); // Hashage du code

			if(!filter_var($email, FILTER_VALIDATE_EMAIL)){ // Controle de la validité de l'adresse mail
				$verification = false; 
				$class="nomailvalide";
			}

			if(strlen($code) < 4 || strlen($code) > 4){ // Controle sur le nombre de caractères du code
				if(strlen($code) < 4){
					$verification = false;
					$class="codeless";
				}
				if(strlen($code) > 4){
					$verification = false; 
					$class="codemore";
				}
			}

			// On enregistre seulement si la vérification est correct
			if($verification){

				// Connexion à la bdd
				include("../include/bdd.php");

				// On vérifie si le login existe déjà  
				$requete = "SELECT count(*) FROM users WHERE login='$login'";
				// On exécute la requête
				$resultat = $bdd->query($requete) or die(print_r($bdd->errorInfo()));
				// On lit le résultat
				$response = $resultat->fetch();

				if($response[0] == 0){

					// On vérifie si l'email existe déjà 
					$requete = "SELECT count(*) FROM users WHERE email='$email'";
					// On exécute la requête
					$resultat = $bdd->query($requete) or die(print_r($bdd->errorInfo()));
					// On lit le résultat
					$response = $resultat->fetch();

					if($response[0] == 0){

						// Requête d'enregistrement d'un nouvel utilisateur
						$requete = "INSERT INTO users (id , login , pass , salt , code , email , confirmation)
								    VALUES ('' , '$login' , '$mdp' , '$salt' , '$codeH' , '$email' , '0')";

						// On exécute la requête
						$resultat = $bdd->query($requete) or die(print_r($bdd->errorInfo()));

						// On récupère l'id
						$id = $bdd->lastInsertId();
						// On l'a crypte en base 64
						$idX = base64_encode($id);

						// On ferme la requête
						$resultat->closeCursor();

						if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $email)) // On filtre les serveurs qui rencontrent des bogues.
						{
							$passage_ligne = "\r\n";
						}
						else
						{
							$passage_ligne = "\n";
						}

						// CSS correctif laposte top: 0em;left: 30em;position: absolute;
						
						// On inclut phpmailer
						require "../mail/PHPMailer/class.phpmailer.php";

						// Lien du site
						$link =  $path."traitement/confirmation.php";

						$mailer = new PHPmailer();
				        $mailer->IsSMTP();
				        $mailer->IsHTML(true);
				        $mailer->IsSendMail(true);
				        $mailer->CharSet = 'UTF-8';
				        $mailer->Username = "mail";
				        $mailer->Password = "password";
				        $mailer->Host='smtp.serveur';
				        $mailer->Port = 587; 
				        $mailer->SMTPSecure = 'ssl'; 
				        $mailer->SMTPAuth = true;
				        $mailer->From='webmaster@passguard.fr';
				        $mailer->AddAddress($email);
				        $mailer->AddReplyTo('mail reply');     
				        // $mailer->Subject=mb_encode_mimeheader("Création de compte sur PassGuard","UTF-8");
				        $mailer->Subject="Création de compte sur PassGuard";
				        // On prépare le message
				        $mailer->Body="<html><head><title>mail</title><style>div.content{background-color:#d4d4d4;padding:0px 20px 60px 20px;font-family:monospace;font-size:larger}h3{color:#7d0b0b}div.content{width:45em;height:24em;margin-left:auto;margin-right:auto;margin-top:25px;padding:20px}.content{position:relative;width:400px;height:300px;background-color:#fff;box-shadow:0 1px 5px rgba(0,0,0,.25),0 0 50px rgba(0,0,0,.1) inset;border-radius:1%}.content:after,.content:before{position:absolute;content:'';width:130%;height:30%;border-left:1px dashed rgba(0,0,0,.1);border-right:1px dashed rgba(0,0,0,.1);box-shadow:0 0 3px rgba(0,0,0,.15)}.content:before{background:rgba(0,0,0,.1);background:-webkit-gradient(linear,555% 20%,0 92%,from(rgba(0,0,0,.1)),to(rgba(0,0,0,0)),color-stop(.1,rgba(0,0,0,.2)));background:-moz-linear-gradient(555% 0 180deg,rgba(0,0,0,.1),rgba(0,0,0,.2) 10%,rgba(0,0,0,0));background:-ms-linear-gradient(555% 0 180deg,rgba(0,0,0,.1),rgba(0,0,0,.2) 10%,rgba(0,0,0,0));background:-o-linear-gradient(555% 0 180deg,rgba(0,0,0,.1),rgba(0,0,0,.2) 10%,rgba(0,0,0,0));background:linear-gradient(555% 0 180deg,rgba(0,0,0,.1),rgba(0,0,0,.2) 10%,rgba(0,0,0,0));-webkit-transform:translate(44px,-10px) skew(0) rotate(-45deg);-moz-transform:translate(44px,-10px) skew(0) rotate(-45deg);-ms-transform:translate(44px,-10px) skew(0) rotate(-45deg);-o-transform:translate(44px,-10px) skew(0) rotate(-45deg);transform:translate(44px,-10px) skew(0) rotate(-45deg)}.content:after{right:0;left:0;background:rgba(0,0,0,.1);background:-webkit-gradient(linear,555% 20%,0 92%,from(rgba(0,0,0,.1)),to(rgba(0,0,0,0)),color-stop(.1,rgba(0,0,0,.2)));background:-moz-linear-gradient(555% 0 180deg,rgba(0,0,0,.1),rgba(0,0,0,.2) 10%,rgba(0,0,0,0));background:-ms-linear-gradient(555% 0 180deg,rgba(0,0,0,.1),rgba(0,0,0,.2) 10%,rgba(0,0,0,0));background:-o-linear-gradient(555% 0 180deg,rgba(0,0,0,.1),rgba(0,0,0,.2) 10%,rgba(0,0,0,0));background:linear-gradient(555% 0 180deg,rgba(0,0,0,.1),rgba(0,0,0,.2) 10%,rgba(0,0,0,0));-webkit-transform:translate(-44px,10px) skew(0) rotate(-45deg);-moz-transform:translate(-44px,10px) skew(0) rotate(-45deg);-ms-transform:translate(-44px,10px) skew(0) rotate(-45deg);-o-transform:translate(-44px,10px) skew(0) rotate(-45deg);transform:translate(-44px,10px) skew(0) rotate(-45deg)}img{float:right;}.link{color:blue;}</style></head><body><div class=content><h3>Rappel de vos identifiants</h3></br><img src=http://vignette3.wikia.nocookie.net/kalon/images/6/6d/Cadenas.png/revision/latest?cb=20090605150023 alt=cadenas><p><b>LOGIN</b>: ".$login."</p><p><b>PASSWORD</b>: ".$passMail."</p><p><b>CODE</b>: ".$code."</p></br><a class=link href=".$link."/confirmation.php?id=".$idX.">Cliquez ici pour confirmer votre compte</a><p>Si le lien ne s'affiche pas correctement vous pouvez copier/coller ce lien dans la barre d'adresse : <span class=link>".$link."?id=".$idX."</span></p></div></body></html>";

				        if(!$mailer->Send()){ //Teste si le return code est ok.
				        	// on supprime l'enregistrement
							$requete = "DELETE FROM users WHERE id='$id'";
							// On exécute la requête
							$resultat = $bdd->query($requete) or die(print_r($bdd->errorInfo()));
							// On ferme la requête
							$resultat->closeCursor();

							$class=$mailer->ErrorInfo; //Affiche le message d'erreur (ATTENTION:voir section 7)

							if(!$class != "")
								$class="Impossible d'envoyer le mail de confirmation !";
				        }
				        else{
    						// Mail envoyé avec succès
							$class="ok";    
				        }
				        // On ferme la connexion SMTP
				        $mailer->SmtpClose();
				        // On efface l'adresse mail
				        unset($mailer);

					}else{

						// On ferme la requête
						$resultat->closeCursor(); 
						$class="mailbis";
					}

				}else{

					// On ferme la requête
					$resultat->closeCursor(); 
					$class="userbis";
				}
			}
		}elseif(empty($_POST["loginIns"])) { // Si le login est vide
			$class="nologin";
		}elseif (empty($_POST["PassIns"])) { // Si le mot de passe est vide 
			$class="nopass";
		}elseif (empty($_POST["recoverEmail"])) { // Si l'adresse mail est vide 
			$class="nomail";
		}elseif (empty($_POST["codePin"])) { // Si le code est vide 
			$class="nocode";
		}

		return $class;
	}
?>
