$(document).ready(function(){
	
	// Fonction d'ajustement
	function ajustResp(){

		// Ajustement pour le responsive
		var largeur_fen = $(window).width()-200; // Largeur de la fenêtre
		var hauteur_fen = $("#size").height();
		var largeur_el = $(".note").width(); // Largeur d'une note
		var hauteur_el = $(".note").height(); // Hauteur d'une note
		var marge_left = 42; // Marge intérieur fixe
		var marge_top = 59; // Menu supérieur + marge fixe
		var nb_col = Math.round((largeur_fen+marge_left)/largeur_el); // Nombre de colonnes affichable
		var nb_noteTotale = Object.keys(tableJson).length; // Nombre de notes totales
		var nb_note_par_ligne = Math.ceil(nb_noteTotale / nb_col)+1;

		reposElement(nb_col,nb_note_par_ligne);

		function reposElement(nbCol,nb_note_par_ligne){

			// Repositionnement des éléments
			var nb = 0;
			var sel = ".note."+nb; // Initialise
			var numCol = 1;
			var nb_ligneEncours = 0;
			var coordLeft = marge_left+"px";
			var marge_top = 276;
			var coordTop = marge_top+"px";
			// Variables maj tableau
			var indice = 0;
			
			while ($(sel).length > 0) { // Tant que l'on a une note

				if(nb >= Object.keys(tableJson).length)
					numCol = "End";

				if(numCol == 1){

					$(sel).css('left', coordLeft); // Première colonne
					$(sel).css('top', coordTop); // Ligne suivante

					// Maj tableau
					indice = $(sel)[0].classList[1];
					posL = $(sel).position().left; // Position Left
					posT = $(sel).position().top; // Position Top
					tableJson[indice]={"posL":posL,"posT":posT};

					marge_top = marge_top+219;
					coordTop = marge_top+"px";

					nb_ligneEncours++;

					if(nbCol == 1){
						if(nb_ligneEncours == nb_note_par_ligne){
							numCol=2;
							nb_ligneEncours = nb_note_par_ligne; // Réinitialise le nombre de ligne
						}
					}else{
						if(nb_ligneEncours == nb_note_par_ligne-1){
							numCol=2;
							nb_ligneEncours = nb_note_par_ligne; // Réinitialise le nombre de ligne
						}
					}

				}else if(numCol == "End"){
					break;
				}else{
					if(nb_ligneEncours == nb_note_par_ligne){
							marge_left = marge_left+304; // Incrémentation
						coordLeft = marge_left+"px";
							marge_top = 57; // Réinitialisation
						coordTop = marge_top+"px";
						nb_ligneEncours=0;
					}else{
						marge_top = marge_top+219;
						coordTop = marge_top+"px";
					}

					$(sel).css('left', coordLeft); // Colonne suivante
					$(sel).css('top', coordTop); // Ligne suivante

					// Maj tableau
					indice = $(sel)[0].classList[1];
					posL = $(sel).position().left; // Position Left
					posT = $(sel).position().top; // Position Top
					tableJson[indice]={"posL":posL,"posT":posT};

					nb_ligneEncours++;
				}

				nb++;
				sel = ".note."+nb; // On maj
			}
		}
	}

	// ajustResp();

	// On calcule le nombre de note affichable
	$(window).resize(function(event) {
		ajustResp();
	});

	$(".note").draggable();

	// // Fonction qui récupére les positions // //
	function majPos(redirect){

		var nb = 0;
		var posL = 0;
		var posT = 0;
		var tableJson = {}; 
		var sel = ".note."+nb; // Initialise
		var redirect = redirect+".php";
		
		while ($(sel).length > 0) { // Tant que l'on a une note
			
			posL = $(sel).position().left; // Position Left
			posT = $(sel).position().top; // Position Top
			tableJson[nb]={"posL":posL,"posT":posT};
			
			nb++; // On icrémente
			sel = ".note."+nb; // On maj
		}

		$.ajax({
				url: 'traitement/saveJson.php',
				type: 'POST',
				dataType: 'text',
				data: {tableJson: tableJson}
			})
			.done(function() {
				window.location = redirect;
			})
			.fail(function() {
				console.log("error");
			})
			.always(function() {
				console.log("complete");
			});
	}

	// Fonction qui sauvegarde la position d'une note
	function savePos(element){
		
		var indice = element[0].classList[1];
			
		$.ajax({
			url: 'traitement/saveOne.php',
			type: 'POST',
			dataType: 'text',
			data: {tableJson: tableJson,
				   indice: indice}
		})
		.done(function() {
			console.log("done");
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
	}

	// Fonction qui sauvegarde la position d'une autre note
	function savePos2(indice){
		
		$.ajax({
			url: 'traitement/saveOne.php',
			type: 'POST',
			dataType: 'text',
			data: {tableJson: tableJson,
				   indice: indice}
		})
		.done(function() {
			console.log("done");
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
	}

	$("#deco").click(function(event) { // deconnexion
		majPos("traitement/deco");
	});

	$('.note').mousedown(function(event) { // Lorsqu'on presse l'élément
		$(this).css('background-color', '#bfb172');
		$(this).css('z-index', 1000);
	});

	// Gestion du positionnement auto
	$('.note').mouseup(function(event) { // Lorsqu'on relâche l'élément

		// Initialisation sauvegarde
		var boolSave = true;

		$(this).css('background-color', '#b5a768');

		// Capture de la position initiale d'un élément
		var str = $(this).attr('class');
		var reg = new RegExp(" ", "g");
		var tableau = str.split(reg);
		var indice = tableau[1];

		iniLeft = tableJson[indice].posL;
		iniTop = tableJson[indice].posT;

		// Position de l'élément relâché
		var leftE = $(this)[0].offsetLeft; 
		var topE = $(this)[0].offsetTop;

		if((iniLeft != leftE || iniTop != topE)) // Si les positions sont différentes de celles initiales
			boolSave = true;
		else
			boolSave = false; 

		// Ligne et colonne les plus proches
		var posX = Math.round(parseFloat(leftE) / 304);
		var posY = Math.round(parseFloat(topE) / 219);
		
		if (posX == 0){
			var xE = 42;
		}else{
			var xE = (304*posX)+42;
		}

		if(posY == 0){
			var yE = 57;
		}else {
			var yE = (219*posY)+57;
		}

		// vérifie la présence d'un élément
		var ok = true;

		for (var i = 0; i < Object.keys(tableJson).length; i++) {
			
			// Si un élément est déjà la
			if(tableJson[i].posL == xE && tableJson[i].posT == yE){
				ok = false;
				break;
			}
		}

	    if(ok){ // Si ok on positionne l'élément et on met à jour ces coordonnées
	   		
	   		tableJson[indice].posL = xE;
			tableJson[indice].posT = yE;

			$(this).css({
				left: xE,
				top: yE
			});

	    }else{ // Si pas ok on inverse la position des éléments et on met à jour leurs coordonnées

	    	boolSave=false;

	    	tableJson[indice].posL = xE;
			tableJson[indice].posT = yE;

			tableJson[i].posL = iniLeft;
			tableJson[i].posT = iniTop;

	    	$(this).css({
	    		left: xE,
	    		top: yE
	    	});
	    	savePos($(this));

			$('.note.'+i).css({
				left: iniLeft,
				top: iniTop
			});
			savePos2(i);
	    }

	    $(this).css('z-index', '');


	    // Enregistrement du positionnement
	    if(boolSave){
	    	savePos($(this));
	    }
	});

	// Gestion de la bulle contenu
	$('.plus').click(function(event) {

		// On récupère le note number
		var str = $(this).parents()[1][2].parentElement.parentNode.className;
		str = str.replace("grbt ", "");
		$('#bulle form input[type="hidden"]').val(str);

		// On efface les éléments parents
		$(this).siblings().css('display', 'none');
		// On affiche la bulle
		var leftE = $(this).parents()[2].offsetLeft; 
		var topE = $(this).parents()[2].offsetTop;

		// On la positionne
		$('#bulle').css({
			left: leftE,
			top: topE,
			display: 'block'
		});

	});

	// Fermeture de la bulle (Lecture note)
	$('#img2').click(function(event) {

		// On réaffiche les éléments parent
		$("h5").css('display', 'block');
		$(".point").css('display', 'block');
		// On la positionne
		$('#bulle').css({
			left: '',
			top: '',
			display: 'none'
		});
	});

	// Gestion du code pin
	$("form").submit(function(e){ // Dès que l'on soumet un formulaire
		
		e.preventDefault(); // On stoppe le comportement par défaut

		if(e.target.id == "formB"){

			$.post("traitement/validation.php",$(this).serialize(),function(data){

				var texte = JSON.parse(data).response;
				
				if(texte == "correct"){

					$("#error").css('display', 'none');
					var description = JSON.parse(data).desc; // On récupère la description
					var login = JSON.parse(data).login; // On récupère le login
					var pass = JSON.parse(data).pass; // Le mot de passe
					var idNote = JSON.parse(data).note; // L'id de la note

					$('#bulle').css({
						left: '',
						top: '',
						display: 'none'
					});

					$('.note .'+idNote)[0].parentElement[0].value = login;
					$('.note .'+idNote)[0].parentElement[0].disabled = "";
					$('.note .'+idNote)[0].parentElement[1].value = pass;
					$('.note .'+idNote)[0].parentElement[1].disabled = "";
					$('.note .'+idNote)[0].parentElement[2].parentElement.parentElement.style.display = "block";
					// On réaffiche les éléments parent
					$("h5").css('display', 'block');
					$(".point").css('display', 'block');
					$('.note .'+idNote)["0"].parentElement.parentElement.childNodes[1].firstElementChild.childNodes[3].style.display = "none";
					$('.'+idNote+' h5').replaceWith("<div class=\"form-group\"><div class=\"col-sm-9 col-sm-offset-1\"> <input id=\"descMod\" onchange=\"this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);\" class=\"form-control\" type=\"text\" name=\"descMod\" value=\""+description+"\" /></div></div></div>");

				}else if (texte == "incorrectNomb") {

					$("#error").css('display', 'block');
					$("#error").html("Le code pin doit être composé de 4 chiffres !");

				}else if (texte == "empty") {

					$("#error").css('display', 'block');
					$("#error").html("Vous devez saisir un code pin !");

				}else if (texte == "incorrectCode") {

					$("#error").css('display', 'block');
					$("#error").html("Le code que vous avez saisi n'est pas valide !");
				}
			});
		}
	});

	// Création d'une nouvelle note
	var nbNote = 0; 

	$('#newF').click(function(event) {

		if(nbNote == 0){

			var num = 0; // On intitialise
			var trouve = false;

			// On recherche le numéro a généré
			for (var i = 0; i < Object.keys(tableJson).length; i++) {

				if (tableJson[i] == undefined) {
					num = i;
					trouve = true;
				}
			}

			if(!trouve) // Dernier numéro
				num = i;

				// On récupère le bloc du proto
				var container = $('#blocnote');

				// On prépare le prototype
				var prototype = $(container.attr('dataprototype').replace('numM', num).replace('grbt numM', 'grbt '+num));

				// On le positionne
				$('body').prepend(prototype);

				// On active le button 
				$('.grbt.'+num).css('display', 'block');

				// On met à jour le tableau
				var leftNE = $('.note .'+num)[0].offsetParent.offsetLeft;
				var topNE = $('.note .'+num)[0].offsetParent.offsetTop;

				// On désactive le bouton new
				$("#newF").prop('disabled', 'true');

				tableJson[num]={"posL":leftNE,"posT":topNE};

				$('#numCache').attr('value', num);

			nbNote++;
		}

		// Enregistrement de la nouvelle note
		$('form').submit(function(event) {
			event.preventDefault(); // On stoppe le comportement habituel

			if(event.target.id == "formN"){
				
				$.post("traitement/enregistrement.php",$(this).serialize(),function(data){

					var texte = data;
					
					if(texte == "ok"){
						
						$("#error2").css('display', 'none');

						majPos("main");

						window.location = "main.php";

						ajustResp();

					}else if (texte == "emptyDes") {

						$("#error2").css('display', 'block');
						$("#error2").html("Vous devez saisir une description !");

					}else if (texte == "emptyLog") {

						$("#error2").css('display', 'block');
						$("#error2").html("Vous devez saisir un login !");

					}else if (texte == "emptyPass") {

						$("#error2").css('display', 'block');
						$("#error2").html("Vous devez saisir un mot de passe !");
						
					}else {

						$("#error2").css('display', 'block');
						$("#error2").html("Les champs sont obligatoires !");
					}
				});
			}
			
		});

		// Annulation de la nouvelle note
		$("#reset").click(function(event) {
			// On réactive le bouton new
			$("#newF").removeProp('disabled');
			$("#formN").parent().remove();
			nbNote=0;
		});
	});

	// Fonction de mise à jour des numéros de notes
	function saveNewIndice(redirect){
		// Lien traitement de redirection
		var redirect = redirect+".php";

		$.ajax({
				url: 'traitement/saveNumNote.php',
				type: 'POST',
				dataType: 'text',
			})
			.done(function(text) {
				window.location = redirect;
			})
			.fail(function() {
				console.log("error");
			})
			.always(function() {
				console.log("complete");
			});
	}

	// Fonction de réinitialisation du tableau json
	function reiniTable(numNote){
 
		var newtableJson = {};

		// On parcourt le tableau 
		$.each(tableJson, function(numCours,objet) {
			// Si le num est différent de celui demandé
			if(numCours != numNote)
				newtableJson[numCours]={"posL":objet.posL,"posT":objet.posT};
		});

		// On reconstruit le tableau à partir du nouveau exempt du num à supprimer
		var newNum = 0;
		tableJson = {};

		$.each(newtableJson, function(ind,objet){
				tableJson[newNum]={"posL":objet.posL,"posT":objet.posT};
				newNum++;
		});
	}

	// Suppression d'une note
	$(".sup").click(function() {
		
		var id = $(this).parent().children()[1].value; // On récupére l'id de la note à supprimer

		var numNote = $(this).parent().parent()[0].className; 
		numNote = numNote.replace("grbt ", ""); // Le numéro de la note

		if(confirm("Confirmer-vous la suppression de cette note ?")){
			$.post("traitement/suppression.php",{IdNote :id}, function(data) {
				if(data == "ok"){
					// On réinitialise le tableau json
					reiniTable(numNote);
					// On sauvegarde puis on recharge la page
					saveNewIndice("traitement/reload"); 
				}else{
					alert("Erreur lors de la suppression");
				}
			});
		}
	});

	// Mise à jour d'une note
	$(".modif").click(function() {
		$('form').submit(function(event) {
			event.preventDefault(); // On stoppe le comportement habituel
			$.post("traitement/modification.php",$(this).serialize(), function(data) {
				if(data == "ok"){
					window.location = "main.php"; 
				}else{
					alert(data);
				}
			});
		});
	});

	// Réinitialisation de la bulle
	function reiniBulle(){
		$('#libCode').html("Validation du code");
		$('.prov').removeAttr('id');
		$('.prov').attr('id', 'img2');
		$('#img2').removeClass('prov');
		$("#formMod").removeAttr('id'); // Supprime l'id actuelle
		$("#bulle form").attr('id','formB'); // On l'a redéfini
		$("#chCode").removeAttr('placeholder');
		$("#chCode").val("");
		$("#chCode").attr('placeholder','Code pin...');
		$("#valideCode").attr('value','Ok');
		$("#bulle").hide();
		$("#error").css('display', 'none');
	}

	// Mise à jour du code
	$("#modC").click(function() {

		// Affichage de la bulle de vérification
		$("#bulle").show('slow/400/fast', function() {
			$(this).css({
				top: '57px',
				left: '42px'
			}); // Correction du positionnement
			$("#formB").removeAttr('id'); // Supprime l'id précédente
			$("#bulle form").attr('id','formNote'); // On l'a redéfini
		});

		// Fermeture de la bulle (Modification code)
		$('#img2Bis').click(function() {
			reiniBulle();
		});

		$("form").submit(function(e){ // Dès que l'on soumet le formulaire

			e.preventDefault(); // On stoppe le comportement habituel

			if(e.target.id == "formNote"){ // Validation du code d'accès

				$.post("traitement/verification.php",$(this).serialize()+"&valideC=Ok",function(data){

					if(data == "ok"){

						$("#error").css('display', 'none');
						// Traitement de la bulle
						$('#libCode').html("Modification du code");
						$('#img2').attr('class', 'prov');
						$('#img2').removeAttr('id');
						$('.prov').attr('id', 'img2Bis');
						$("#formNote").removeAttr('id'); // Supprime l'id actuelle
						$("#bulle form").attr('id','formMod'); // On l'a redéfini
						$("#valideCode").attr('value','Modifier');
						$("#chCode").removeAttr('placeholder');
						$("#chCode").attr('placeholder','Nouveau code pin...');
						$("#chCode").val("");

					}else if(data == "pas ok"){

						$("#error").css('display', 'block');
						$("#error").html("Le code que vous avez saisi n'est pas valide !");

					}else if(data == "code tpg"){

						$("#error").css('display', 'block');
						$("#error").html("Le code pin doit être composé de 4 chiffres !");

					}else{

						$("#error").css('display', 'block');
						$("#error").html("Vous devez saisir un code pin !");

					}
				});
			}else if(e.target.id == "formMod"){ // Enregistrement de la modification

				$.post("traitement/verification.php",$(this).serialize()+"&valideC=Modifier",function(data){

					$("#error").html("");
					$("#error").css('display', 'none');

					if(data == "ok"){

						$("#error").css('display', 'block');
						$("#error").html("Modification effectuée avec succès");

						setTimeout(function(){
							reiniBulle(); // On réinitialise la bulle
						}, 1000);

					}else if(data == "pas ok"){

						$("#error").css('display', 'block');
						$("#error").html("Erreur lors de la mise à jour");

					}else if(data == "code tpg"){

						$("#error").css('display', 'block');
						$("#error").html("Le code pin doit être composé de 4 chiffres !");

					}else{

						$("#error").css('display', 'block');
						$("#error").html("Vous devez saisir un code pin !");

					}
				});
			}
		});
	});
});