<?php

// on va orevenir pho qu'on va utiliser les sessions
session_start();
// on va voir si un ccokie est deja cree
require('src/log.php');

//deterter l'(envoie de notre formulaire
if (!empty($_POST['email']) && !empty($_POST['password'])) {
    require('src/connect.php');

    //CREATION DE NOS VARIABLES
    $email 					= htmlspecialchars($_POST['email']);
    $password 			= htmlspecialchars($_POST['password']);
    
    // VERIFICATION DE L'EMAIL VALIDE
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header('location: index.php?error=1&message=Votre adresse email est invalide.');
        exit();
    }

    // CHIFFRAGE DU MOT DE PASSE
    $password = "aq1".sha1($password."123")."25";

    // VERIFICATION EMAIL DEJA UTLISE
    $req = $db->prepare("SELECT count(*) as numberEmail FROM users WHERE email = ?");
    $req->execute(array($email));

    while ($email_verification = $req->fetch()) {
        // cette fois on verifie si c different de un et pas de 0 car il faut qu'il y est un seul compte qui est cet email la
				if ($email_verification['numberEmail'] != 1) {
            // ici il faudra changt que c l'email qui cloche donc mettre plutot vos identifiants st incorrects
						header('location: index.php?error=1&message=Imposssible de vous identifier.');
            exit();
        }
    }
		// CONNEXION
		$req = $db->prepare("SELECT * FROM users WHERE email = ?");
		$req->execute(array($email));

        while ($users = $req->fetch()) {

            if ($password == $users['password'])
						 {
                $_SESSION['connect'] = 1;
                $_SESSION['email']   = $users['email'];

								//creation de notre systeme de cookie pour pas qu'a chaque fermeture on est a le repasser valable
								//un an
								if(isset($_POST['auto'])){
								setcookie('auth', $user['secret'], time() + 364*24*3600, '/', null, false, true);
				}

                header('location: index.php?success=1');
								exit();
            }
						else {
							header('location: index.php?error=1&message=Imposssible de vous identifier.');
							exit();
						}
        }
}

?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Netflix</title>
	<link rel="stylesheet" type="text/css" href="design/default.css">
	<link rel="icon" type="image/pngn" href="img/favicon.png">
</head>
<body>

	<?php include('src/header.php'); ?>
	
	<section>
		<div id="login-body">
			<?php if(isset($_SESSION['connect'])) { ?>
					<!--on va metre en place notre cookies de connection avec la session -->
		
					<h1>Bonjour !</h1>
					<?php

					if(isset($_GET['success'])){
						echo'<div class="alert success">Vous êtes maintenant connecté.</div>';
					} ?>
					<p> C'est parti pour un moment de détente avec Netflix</p>
					<small><a href="logout.php">Déconnexion</a></small>
						
			<?php } else { ?>
				
					<h1>S'identifier</h1>
				<?php // si le message error apparait ds l'URL
					if(isset($_GET['error'])) {
					// si le message message apparait ds l'URL
							if(isset($_GET['message'])) {
								echo'<div class="alert error">'.htmlspecialchars($_GET['message']).'</div>';
							}
				} else if(isset($_GET['success'])){
						echo'<div class="alert success">Vous êtes maintenant connecté.</div>';
						} 
						?>
			

					<form method="post" action="index.php">
						<input type="email" name="email" placeholder="Votre adresse email" required />
						<input type="password" name="password" placeholder="Mot de passe" required />
						<button type="submit">S'identifier</button>
						<label id="option"><input type="checkbox" name="auto" checked />Se souvenir de moi</label>
					</form>
				

					<p class="grey">Première visite sur Netflix ? <a href="inscription.php">Inscrivez-vous</a>.</p>
			<?php } ?>
			</div>
		</section>

	<?php include('src/footer.php'); ?>
</body>
</html>