<?php 

	/* ==================================================================================== */
	/* ==================================== DECONNEXION =================================== */

if(isset($_GET['action'])){ // Si la session existe.

	unset($_SESSION['membre']);

}

	/* ==================================================================================== */
	/* ==================================== SESSION MEMBRE REDIRECTION ==================== */

if(isset($_SESSION['membre'])){ // Si la session existe.

	header('location:' . URL . '?page=profil');

}

	/* ==================================================================================== */
	/* ==================================== GET inscription =============================== */

// si il y a le mot "statut" dans l'url.
if(isset($_GET['inscription']) && $_GET['inscription'] == 'ok'){ 

	$content .= '<div class="alert alert-success">Votre inscription à été validée, vous pouvez désormais vous connecter !</div>';

}

	/* ==================================================================================== */
	/* ==================================== POST connexion ================================ */

if($_POST) {
	// Je selectionne tout depuis ma table membre QUAND le pseudo est egal au pseudo posté par l'internaute
    $result = execute_requete("SELECT * FROM membre WHERE pseudo ='$_POST[pseudo]'"); 
    // Si le résultat n'est pas 0 c'est que celà match et que nous avons trouvé le pseudo de l'internaute, alors nous affichons :
    if($result->rowCount() != 0) { 
    	// Nous faison un fetch() pour récuperer les données (qui ne sont pas accessibles avant)
		$membre = $result->fetch(PDO::FETCH_ASSOC); // fetch :permet l'accès au résultat 

			if(password_verify($_POST['mdp'], $membre['mdp'])) {   // si les mdp correspondent
				// Nous remplissons le dossier temporaire de sessions en y affectant les données enregitrées en base de données afin 	de les conserver sur le serveur.
				$_SESSION['membre']['pseudo'] = $membre['pseudo'];
				$_SESSION['membre']['nom'] = $membre['nom'];
				$_SESSION['membre']['prenom'] = $membre['prenom'];
				$_SESSION['membre']['email'] = $membre['email'];
				$_SESSION['membre']['civilite'] = $membre['civilite'];
				$_SESSION['membre']['statut'] = $membre['statut'];
				
				//redirection vers la page profil
				header('location:' . URL . '?page=profil');

			} else 	{ 

				$content .= '<div class="alert alert-danger">Erreur de mot de passe !</div>';

			}

    } else { // Sinon, aucune correspondance avec ce pseudo dans notre bdd.

		// ERREUR PSEUDO
		$content .= '<div class="alert alert-danger">Erreur de pseudo !</div>';

    } 
}

####### FORMULAIRE DE CONNEXION ########
$content .= '
	<div class="col-md-offset-4 col-md-4">
		<form class="form-signin" method="post" action="">

	        <h2 class="form-signin-heading">Please sign in</h2>

			<input type="name" name="pseudo" class="form-control" placeholder="Votre pseudo" autofocus><br />

	        <input type="password" name="mdp" id="inputPassword" class="form-control" placeholder="Mot de passe"><br />

	        '.
	        //<div class="checkbox">
	        //   <label>
	        //     <input type="checkbox" value="remember-me"> Remember me
	        //   </label>
	        // </div> 
	        ' 

	        <button class="btn btn-lg btn-primary btn-block" type="submit">Se connecter</button>
      	</form>
	</div>';
