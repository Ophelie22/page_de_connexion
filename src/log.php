<?php
// on va verifier si le cookie 'aut' existe
if(isset($_COOKIE['auth']) && !isset($_SESSION['connect'])){

		// VARIABLE
    // je vais stoker le cookie ds une variuable
		$secret = htmlspecialchars($_COOKIE['auth']);

		// VERIFICATION
		require('src/connect.php');
      //RECUP DE LA CLEF SECRETE
      
      // ON fait cette 1re REQUETE pour verifier que l'utilisateur existe bien
		$req = $db->prepare("SELECT count(*) as numberAccount FROM users WHERE secret = ?");
		$req->execute(array($secret));

		while($users = $req->fetch()){

			if($users['numberAccount'] == 1){
        //ET on fait cette 2 eme REQUETE pour recuperer ces informations
				$reqUsers = $db->prepare("SELECT * FROM users WHERE secret = ?");
				$reqUsers->execute(array($secret));

				while($usersAccount = $reqUsers->fetch()){

					$_SESSION['connect'] = 1;
					$_SESSION['email']   = $usersAccount['email'];

				}

			}

		}

	}
if(isset($_SESSION['connect'])){

		require('src/connect.php');

		$reqUsers = $db->prepare("SELECT * FROM users WHERE email = ?");
		$reqUsers->execute(array($_SESSION['email']));

		while($usersAccount = $reqUsers->fetch()){

			if($usersAccount['blocked'] == 1) {
				header('location: ../logout.php');
				exit();
			}

		}

	}

?>