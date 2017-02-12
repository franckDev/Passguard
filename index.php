<?php 
	session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>PassGuard v2.0</title>
        <link rel="shortcut icon" type="image/x-icon" href="assets/img/key.bmp" />
        <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width">
        <!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="assets/css/bootstrap.min.css" crossorigin="anonymous" />
		<!-- Optional theme -->
		<link rel="stylesheet" href="assets/css/bootstrap-theme.min.css" crossorigin="anonymous" />
		<!-- Feuille de style CSS -->
        <link rel="stylesheet" href="assets/css/styles.css" />
	    <!--[if lt IE 9]>
	        <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	    <![endif]-->
    </head>
    
    <body>
   		<?php if(isset($_GET['conf']) && $_GET['conf']='ok'){
   		?>
   		<script>
   			alert("Confirmation effectuée avec succès !");
   			document.location.href="index.php";
   		</script>
   		<?php
   			   }
   		?>
		<div id="block" class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-xs-8 col-xs-offset-1">
			<div id="formContainer" class="col-md-8 col-md-offset-3 col-sm-8 col-sm-offset-4 col-xs-8 col-xs-offset-1">
				<!-- Fond de la pop-up -->
		   		<div id="fond">
		   			<form id="passOub" method="post">
		   				<a href="#" id="close"><span class="glyphicon glyphicon-remove"></span></a>
						<h3 id="titrePass">Récupération de mot de passe</h3>
						<div class="col-lg-9 col-lg-offset-1 col-md-9 col-md-offset-1 col-sm-9 col-sm-offset-1 col-xs-9 col-xs-offset-1">
							<input type="text" name="recoverPass" id="recoverPass" placeholder="Email" value="" />
							<input id="submitPass" type="submit" name="submitPass" value="Envoyer" />
						</div>
		   			</form>
		   		</div> 
				<!-- Formulaire de connexion -->
				<form id="login" method="post">
					<a href="" title="Cliquez ici pour vous inscrire" id="flipToRecover" class="flipLink">
						<span>Inscription</span>
					</a>
					<h2>Connexion</h2>
					<div class="col-lg-9 col-lg-offset-1 col-md-9 col-md-offset-1 col-sm-9 col-sm-offset-1 col-xs-9 col-xs-offset-1">
						<input type="text" name="loginCo" id="loginCo" placeholder="Login" value="" />
						<input type="password" name="passCo" id="passCo" placeholder="Password" value="" />
						<p id="error2"></p>
						<a id="passfor" href="#">Mot de passe oublié</a>
					</div>
					<div class="colb">
						<input type="submit" name="submit" value="Valider" />
						<input type="reset" name="reset" value="Effacer" />
					</div>
				</form>
				<!-- Formulaire d'enregistrement -->
				<form id="recover" method="post">
					<p>
						<a href="#" title="Cliquez ici pour vous connecter" id="flipToLogin" class="flipLink">
							<span>Connexion</span>
						</a>
						<h2>Inscription</h2>
						<div class="col-lg-9 col-lg-offset-1 col-md-9 col-md-offset-1 col-sm-9 col-sm-offset-1 col-xs-9 col-xs-offset-1">
							<input type="text" name="loginIns" id="loginIns" placeholder="Login" value="" />					
							<input type="password" name="PassIns" id="PassIns" class="password" placeholder="Password" value="" />
							<button class="unmask" type="button">unmask</button>
							<span id="eye-open" class="eye-open-close glyphicon glyphicon-eye-open" title="Afficher le contenu"></span>
							<input type="password" name="PassInsCont" id="PassInsCont" class="password2" placeholder="Vérification Password" value="" />
							<button class="unmask2" type="button" title="Afficher le contenu">unmask</button>
							<span id="eye-open2" class="eye-open-close2 glyphicon glyphicon-eye-open" title="Afficher le contenu"></span>
							<input type="text" name="recoverEmail" id="recoverEmail" placeholder="Email" value="" />
							<input type="text" name="codePin" id="codePin" placeholder="Code à 4 chiffres" maxlength="4" value="" />
							<abbr id="question" class="glyphicon glyphicon-question-sign" title="Ce code vous servira pour afficher le contenu des notes"></abbr>
							<div class="capcha">
								<!-- Image dynamique -->
								<img src="captcha/captcha.php" alt="Captcha" id="captcha" />
								<!-- (JavaScript) Recharge l'image car elle n'existe pas dans le cache du navigateur, grâce à l'id généré  -->
								<img src="captcha/images/reload.png" alt="Recharger l'image" title="Recharger l'image" style="cursor:pointer;position:relative;top:-7px;" onclick="document.images.captcha.src='captcha/captcha.php?id='+Math.round(Math.random(0)*1000)" />
								<p id="error"></p>
								<input id="code" class="empty" type="text" name="code" placeholder="Captcha ici .." value="" />
							</div>
							<div class="colb2">
								<input type="submit" name="submitInsc" value="S'inscrire" />
								<input type="reset" name="reset" value="Réinitialiser" />
							</div>
						</div>
					</p>
				</form>
			</div>
		</div>
        <!-- JavaScript & Jquery -->
        <script src="assets/js/jquery-3.1.0.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
		<script src="assets/js/script.js"></script>
    </body>
</html>

