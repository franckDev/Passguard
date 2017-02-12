$(document).ready(function(){

	// On charge CSS 3D transformation support
	$.support.css3d = supportsCSS3D();
	
	var formContainer = $('#formContainer');
	
	// Au clic sur le lien de rotation
	$('.flipLink').click(function(e){
		
		// On tourne le formulaire
		formContainer.toggleClass('flipped');
		
		// Si le CSS 3D transformation n'existe pas
		if(!$.support.css3d){
			$('#login').toggle();
		}
		e.preventDefault();
	});
	
	// Fonction de gestion du 3D CSS
	function supportsCSS3D() {
		var props = [
			'perspectiveProperty', 'WebkitPerspective', 'MozPerspective'
		], testDom = document.createElement('a');
		  
		for(var i=0; i<props.length; i++){
			if(props[i] in testDom.style){
				return true;
			}
		}
		
		return false;
	}

	function reIniFonc(){
		$("#loginCo").attr('class', '');
		$("#passCo").attr('class', '');
		$("#loginIns").attr('class', '');
		$("#PassIns").attr('class', 'password');
		$("#recoverEmail").attr('class', '');
		$("#codePin").attr('class', '');
		$("#code").attr('class', '');
		// $("#error").css('display', 'none');
		$("#error2").css('display', 'none');
	}

	// Réinitialise le formulaire
	$("input[type='reset']").click(function(event) {
		$("#PassInsCont").attr('class', 'password2');
		$("#PassIns").attr('class', 'password');
		$("#error").css('display', 'none');
		$("#error2").css('display', 'none');
		$("#code").attr('class', 'empty');
	});

	// On réinitialise après saisi d'un champs
	$("input").blur(function(event) {
		reIniFonc();
	});
	
	$("form").submit(function(e){ // Dès que l'on soumet un formulaire
		
		e.preventDefault(); // On stoppe le comportement par défaut

		var choix = $(this).attr("id"); // Selon le choix login ou inscription

		if(choix == "recover") { // Choix inscription

			// Gestion du formulaire inscription
			$.post("traitement/inscription.php",$(this).serialize(),function(texte){ // On créé une variable contenant le formulaire sérialisé
				// On réinitialise tout
				reIniFonc();

				if(texte == "ok"){

					if(!$("#PassInsCont").val() == ""){
						$("#code").attr('class', 'correct');
						$("#error").css('display', 'none');
						setTimeout(function() {
							window.location = "traitement/confirmation.php";
						}, 1000);
					}else{
						$("#error").css('display', 'block');
						$("#PassInsCont").attr('class', 'password incorrect');
						$("#error").html("Le controle du mot de passe est obligatoire.");
					}
					
				}else if (texte == "nologin") {

					$("#code").attr('class', 'empty');
					$("#error").css('display', 'block');
					$("#loginIns").attr('class', 'incorrect');
					$("#error").html("Vous devez saisir un login !");

				}else if (texte == "nopass") {

					$("#code").attr('class', 'empty');
					$("#error").css('display', 'block');
					$("#PassIns").attr('class', 'password incorrect');
					$("#error").html("Vous devez saisir un mot de passe !");

				}else if (texte == "nomail") {

					$("#code").attr('class', 'empty');
					$("#error").css('display', 'block');
					$("#recoverEmail").attr('class', 'incorrect');
					$("#error").html("Vous n'avez pas renseigné d'adresse mail !");

				}else if (texte == "nomailvalide") {

					var mail = $("#recoverEmail").val();
					$("#code").attr('class', 'empty');
					$("#error").css('display', 'block');
					$("#recoverEmail").attr('class', 'incorrect');
					$("#error").html(mail+" n'est pas une adresse mail valide !");
					
				}else if (texte == "nocode") {

					$("#code").attr('class', 'empty');
					$("#error").css('display', 'block');
					$("#codePin").attr('class', 'incorrect');
					$("#error").html("Vous devez saisir un code à quatre chiffres !");

				}else if (texte == "codeless") {
					
					$("#code").attr('class', 'empty');
					$("#error").css('display', 'block');
					$("#codePin").attr('class', 'incorrect');
					$("#error").html("Vous devez saisir un code à quatre chiffres !");

				}else if (texte == "codemore") {

					$("#code").attr('class', 'empty');
					$("#error").css('display', 'block');
					$("#codePin").attr('class', 'incorrect');
					$("#error").html("Vous devez saisir un code à quatre chiffres !");
					
				}else if (texte == "incorrect") {

					$("#code").attr('class', 'incorrect');
					$("#error").css('display', 'block');
					$("#error").html("Votre captcha n'est pas identique !");
					
				}else if (texte == "empty") {
					
					$("#code").attr('class', 'incorrect');
					$("#error").css('display', 'block');
					$("#error").html("Vous devez remplir le captcha pour continuer !");	
					
				}else if (texte == "userbis") {
					
					$("#code").attr('class', 'empty');
					$("#error").css('display', 'block');
					$("#loginIns").attr('class', 'incorrect');
					$("#error").html("Ce login existe déjà !");

				}else if (texte == "mailbis") {

					$("#code").attr('class', 'empty');
					$("#error").css('display', 'block');
					$("#recoverEmail").attr('class', 'incorrect');
					$("#error").html("Cet email existe déjà !");

				}else{
					$("#error").css('display', 'block');
					$("#error").html(texte);
				}

			});

		}else if (choix == "login") { // Choix connexion
			
			// Gestion du formulaire de connexion
			$.post('traitement/connexion.php',$(this).serialize(), function(texte) {

				console.log(texte);
				
				if(texte == "ok"){
					
					setTimeout(function() {
						window.location = "main.php";
					}, 1000);
				
				}else if (texte == "error") {
					
					$("#error2").css('display', 'block');
					$("#loginCo").attr('class', 'incorrect');
					$("#passCo").attr('class', 'incorrect');
					$("#error2").html("Login ou mot de passe invalide !");

				}else if (texte == "pasok") {
					
					$("#error2").css('display', 'block');
					$("#error2").html("Vous n'avez pas validé votre compte !");
					
				}else if (texte == "errorempty") {
					
					$("#error2").css('display', 'block');
					$("#loginCo").attr('class', 'incorrect');
					$("#passCo").attr('class', 'incorrect');
					$("#error2").html("Vous devez saisir un login et un mot de passe !");
				}
			});

		}

		return false;
	});

	// Mot de passe oublié
	$('#passfor').click(function(event) {
		
		event.preventDefault();
		
		// On affiche une pop up afin d'envoyer un mail
		$("#fond").show('slow/400/fast', function() {

			var loginW = $("#login").innerWidth();
			var loginH = $("#login").innerHeight();

			$(this).css({
				width: loginW,
				height: loginH
			});

			$("form").submit(function(e){

				if(e.target.id == "passOub"){
					$.post("traitement/mail.php",$(this).serialize(),function(data){
							alert(data);
					});
				}
			});

		});

		$("#close").click(function(event) {
			$("#fond").hide('slow/400/fast', function() {});
		});
	});

	// On redimensionne le fond 
	$(window).resize(function(event) {
		var loginW = $("#login").innerWidth();
		var loginH = $("#login").innerHeight();

		$("#fond").css({
			width: loginW,
			height: loginH
		});
	});

	// Vérification du mot de passe
	$("#PassInsCont").change(function() {

		if($(this).val() != $("#PassIns").val()){ // Mot de passe différents

			$(this).attr('class', 'password2 incorrect');

			$("#error").css('display', 'block');
			$("#error").html("Les mots de passe ne sont pas identiques.");	

		}else{
			$(this).attr('class', 'password2');

			$("#error").css('display', 'none');
		}
	});


	// Gestion du masquage/démasquage mot de passe
	$('.eye-open-close').on('click', function(){

	  if($(this).prev().prev('input').attr('type') == 'password'){

	  	changeType($(this).prev().prev('input'), 'text');
	    $(this).attr({
	    	id: 'eye-close',
	    	class: 'eye-open-close glyphicon glyphicon-eye-close',
	    	title: 'Masquer le contenu'
	    });

	  }else{

	  	changeType($(this).prev().prev('input'), 'password');
	  	$(this).attr({
	    	id: 'eye-open',
	    	class: 'eye-open-close glyphicon glyphicon-eye-open',
	    	title: 'Afficher le contenu'
	    });
	  }
	  return false;
	});

	// Gestion du masquage/démasquage mot de passe
	$('.eye-open-close2').on('click', function(){

	  if($(this).prev().prev('input').attr('type') == 'password'){

	  	changeType($(this).prev().prev('input'), 'text');
	    $(this).attr({
	    	id: 'eye-close2',
	    	class: 'eye-open-close2 glyphicon glyphicon-eye-close',
	    	title: 'Masquer le contenu'
	    });

	  }else{

	  	changeType($(this).prev().prev('input'), 'password');
	  	$(this).attr({
	    	id: 'eye-open2',
	    	class: 'eye-open-close2 glyphicon glyphicon-eye-open',
	    	title: 'Afficher le contenu'
	    });
	  }
	  return false;
	});


	/* 
	  function from : https://gist.github.com/3559343
	  Thank you bminer!
	*/

	function changeType(x, type) {
	    if(x.prop('type') == type)
	        return x; //That was easy.
	    try {
	        return x.prop('type', type); //Stupid IE security will not allow this
	    } catch(e) {
	        //Try re-creating the element (yep... this sucks)
	        //jQuery has no html() method for the element, so we have to put into a div first
	        var html = $("<div>").append(x.clone()).html();
	        var regex = /type=(\")?([^\"\s]+)(\")?/; //matches type=text or type="text"
	        //If no match, we add the type attribute to the end; otherwise, we replace
	        var tmp = $(html.match(regex) == null ?
	            html.replace(">", ' type="' + type + '">') :
	            html.replace(regex, 'type="' + type + '"') );
	        //Copy data from old element
	        tmp.data('type', x.data('type') );
	        var events = x.data('events');
	        var cb = function(events) {
	            return function() {
	                //Bind all prior events
	                for(i in events)
	                {
	                    var y = events[i];
	                    for(j in y)
	                        tmp.bind(i, y[j].handler);
	                }
	            }
	        }(events);
	        x.replaceWith(tmp);
	        setTimeout(cb, 10); //Wait a bit to call function
	        return tmp;
	    }
	}
});
