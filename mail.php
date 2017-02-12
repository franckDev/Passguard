<?php
        require "mail/PHPMailer/_lib/class.phpmailer.php";

        // Lien du site
        $path = $_SERVER["REQUEST_SCHEME"]."://".$_SERVER["SERVER_NAME"]."/";
        $link =  $path."traitement/confirmation.php";
        $passMail = "Aerfzfz124!";
        $code = "1234";
        $idX = 4;
        $login = "fcourtois";

        $mail = new PHPmailer();
        $mail->IsSMTP();
        $mail->IsHTML(true);
        $mail->IsSendMail(true);
        $mail->Username = "flac5@wanadoo.fr";
        $mail->Password = "sultan";
        $mail->Host='smtp.orange.fr';
        $mail->Port = 25; 
        $mail->SMTPSecure = 'ssl'; 
        $mail->SMTPAuth = true;
        $mail->From='webmaster@passguard.fr';
        $mail->AddAddress('flac5@wanadoo.fr');
        $mail->AddReplyTo('flac5@wanadoo.fr');     
        $mail->Subject='Création de compte sur PassGuard';
        $mail->Body="<html><head><title>mail</title><style>div.content{background-color:#d4d4d4;padding:0px 20px 60px 20px;font-family:monospace;font-size:larger}h3{color:#7d0b0b}div.content{text-shadow:0 3px 2px rgba(150,150,150,1);width:45em;margin-left:auto;margin-right:auto;margin-top:25px;padding:20px}.content{position:relative;width:400px;height:300px;background-color:#fff;box-shadow:0 1px 5px rgba(0,0,0,.25),0 0 50px rgba(0,0,0,.1) inset;border-radius:1%}.content:after,.content:before{position:absolute;content:'';width:130%;height:30%;border-left:1px dashed rgba(0,0,0,.1);border-right:1px dashed rgba(0,0,0,.1);box-shadow:0 0 3px rgba(0,0,0,.15)}.content:before{background:rgba(0,0,0,.1);background:-webkit-gradient(linear,555% 20%,0 92%,from(rgba(0,0,0,.1)),to(rgba(0,0,0,0)),color-stop(.1,rgba(0,0,0,.2)));background:-moz-linear-gradient(555% 0 180deg,rgba(0,0,0,.1),rgba(0,0,0,.2) 10%,rgba(0,0,0,0));background:-ms-linear-gradient(555% 0 180deg,rgba(0,0,0,.1),rgba(0,0,0,.2) 10%,rgba(0,0,0,0));background:-o-linear-gradient(555% 0 180deg,rgba(0,0,0,.1),rgba(0,0,0,.2) 10%,rgba(0,0,0,0));background:linear-gradient(555% 0 180deg,rgba(0,0,0,.1),rgba(0,0,0,.2) 10%,rgba(0,0,0,0));-webkit-transform:translate(44px,-10px) skew(0) rotate(-45deg);-moz-transform:translate(44px,-10px) skew(0) rotate(-45deg);-ms-transform:translate(44px,-10px) skew(0) rotate(-45deg);-o-transform:translate(44px,-10px) skew(0) rotate(-45deg);transform:translate(44px,-10px) skew(0) rotate(-45deg)}.content:after{right:0;left:0;background:rgba(0,0,0,.1);background:-webkit-gradient(linear,555% 20%,0 92%,from(rgba(0,0,0,.1)),to(rgba(0,0,0,0)),color-stop(.1,rgba(0,0,0,.2)));background:-moz-linear-gradient(555% 0 180deg,rgba(0,0,0,.1),rgba(0,0,0,.2) 10%,rgba(0,0,0,0));background:-ms-linear-gradient(555% 0 180deg,rgba(0,0,0,.1),rgba(0,0,0,.2) 10%,rgba(0,0,0,0));background:-o-linear-gradient(555% 0 180deg,rgba(0,0,0,.1),rgba(0,0,0,.2) 10%,rgba(0,0,0,0));background:linear-gradient(555% 0 180deg,rgba(0,0,0,.1),rgba(0,0,0,.2) 10%,rgba(0,0,0,0));-webkit-transform:translate(-44px,10px) skew(0) rotate(-45deg);-moz-transform:translate(-44px,10px) skew(0) rotate(-45deg);-ms-transform:translate(-44px,10px) skew(0) rotate(-45deg);-o-transform:translate(-44px,10px) skew(0) rotate(-45deg);transform:translate(-44px,10px) skew(0) rotate(-45deg)}img{float:right;position:relative;top:-6em;left:2em;}.link{color:blue;}</style></head><body><div class=content><h3>Rappel de vos identifiants</h3></br><img src=http://vignette3.wikia.nocookie.net/kalon/images/6/6d/Cadenas.png/revision/latest?cb=20090605150023 alt=cadenas><p><b>LOGIN</b>: ".$login."</p><p><b>PASSWORD</b>: ".$passMail."</p><p><b>CODE</b>: ".$code."</p></br><a class=link href=".$link."/confirmation.php?id=".$idX.">Cliquez ici pour confirmer votre compte</a><p>Si le lien ne s'affiche pas correctement vous pouvez copier/coller ce lien dans la barre d'adresse : <span class=link>".$link."?id=".$idX."</span></p></div></body></html>";

        if(!$mail->Send()){ //Teste si le return code est ok.
          echo $mail->ErrorInfo; //Affiche le message d'erreur (ATTENTION:voir section 7)
        }
        else{    
          echo 'Mail envoyé avec succès';
        }
        $mail->SmtpClose();
        unset($mail);

?>