<?php

if(!empty($_SESSION['membre'])) { 
    header('location:' . URL .'?page=profil'); 
} 


if ($_POST) { 

	// ______________CONTROLES ET ERREURS DES SAISIES_____________
	// CONTROLE DU PSEUDO
	if(strlen($_POST['pseudo']) <= 3 || strlen($_POST['pseudo']) > 20 ) { 
        $erreur .= '<p style= "color: #E57373; font-weight: 300; font-size: 1.2rem">Le pseudo doit contenir entre 3 et 20 caractères.</p><br>';
    }
 
    if (!preg_match('#^[a-zA-Z0-9._-]+$#', $_POST['pseudo'])) { // si le format des caractères n'est pas correct. !pregmatch() attend deux paramètres, l'expression régulière toléré et la valeur à tester. 
		$erreur .= '<p style= "color: #E57373; font-weight: 300; font-size: 1.2rem">Erreur dans les caractères du pseudo !</p><br>';
    } 
    // On verifie si le pseudo saisie n'existe pas déjà en base de donnée.
	$result = execute_requete("SELECT * FROM membre WHERE pseudo = '$_POST[pseudo]'"); 
	if($result->rowCount() >=1){ // Si le résultat est supérieur ou égal à 1, alors le pseudo existe déjà en base de donnée et ne peut être utilisé. 
		$erreur .= '<p style= "color: #E57373; font-weight: 300; font-size: 1.2rem">Pseudo indisponible !</p><br>';
	}
	// CONTROLE MOT DE PASSE
	if(empty($_POST['mdp']) || $_POST['mdp'] != $_POST['mdp_confirm']) {
	  $erreur .= '<p style= "color: #E57373; font-weight: 300; font-size: 1.2rem">Erreur de mot de passe</p><br>';
	}
	// CONTROLE EMAIL
	if (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
		$erreur .= '<p style= "color: #E57373; font-weight: 300; font-size: 1.2rem">Votre email n\'est pas valide</p><br>';		
	}
	$result = execute_requete("SELECT * FROM membre WHERE email = '$_POST[email]'");

	if($result->rowCount() >= 1){ // si il y a un 1 resultat ou plus... 
		$erreur .= '<p style= "color: #E57373; font-weight: 300; font-size: 1.2rem">Mail indisponible !</p><br>';
	} 

    // _________________VALIDATION DE L'INSCRIPTION_________________
    if (empty($erreur)) { 
    	$_POST['mdp'] = password_hash($_POST['mdp'], PASSWORD_DEFAULT); // CRYPTAGE DU MOT DE PASSE

    	// Préparation de la requête pour l'insertion de l'utilisateur dans la base de donnée.
	    $result = $pdo->prepare("INSERT INTO membre (pseudo, mdp, nom, prenom, email, civilite, date_enregistrement ) VALUES (:pseudo, :mdp, :nom, :prenom, :email, :civilite, NOW())");

	    // affectation aux paramètres préparés des données saisies par l'internaute via le formulaire, cela executera la requète.
		$status = $result->execute([
			'pseudo' => $_POST['pseudo'],
			'mdp' => $_POST['mdp'],
			'nom' =>$_POST['nom'],
			'prenom' => $_POST['prenom'],
			'email' => $_POST['email'],
			'civilite' => $_POST['civilite'],
				
		]);
		$content .= '<p style= "color: #00BFA5; font-weight: 400; font-size: 1.4rem">Votre inscription est bien prise en compte !</p>';	
		// nous transmettons à l'URL une nouvelle valeur 'ok' et nous redirigeons l'utilisateur à cette adresse:
		header('location:' . URL . '?page=connexion&inscription=ok' ); // header() permet la redirection vers l'URL du site (index) > la page connexion
    } // -----------TRANSMISSION DES ERREURS AU CONTENU     
    $content .= $erreur; 
}

// variable =   (condition)              IF....		   ELSE
$pseudo = (isset($_POST['pseudo'])) ? $_POST['pseudo'] : '';
$mdp= (isset($_POST['mdp'])) ? $_POST['mdp'] : '';
$nom = (isset($_POST['nom'])) ? $_POST['nom'] : '';
$prenom = (isset($_POST['prenom'])) ? $_POST['prenom'] : '';
$email = (isset($_POST['email'])) ? $_POST['email'] : '';
$civilite_m = (!isset($_POST['civilite'])) || (isset($_POST['civilite']) && $_POST['civilite'] == 'm') ? 'checked' : '';
$civilite_f = (isset($_POST['civilite']) && $_POST['civilite'] == 'f') ? 'checked' : '';


/* AFFICHAGE DU FORMULAIRE */
$content .= '
		<section>
			<h2>Inscription</h2>
			<form method="post" action="">

				    <label for="pseudo">Pseudo</label><br>
				    <input type="text" id="pseudo" name="pseudo" maxlength="20" placeholder="votre pseudo" value="'. $pseudo .'"><br>
				          
				    <label for="mdp">Mot de passe</label><br>
				    <input type="password" id="mdp" name="mdp" required value="'. $mdp .'"><br>

				    <label for="mdp_confirm">Confirmez votre mot de passe</label><br>
				    <input type="password" id="mdp" name="mdp_confirm" required value="'. $mdp .'"><br>
				          
				    <label for="nom">Nom</label><br>
				    <input type="text" id="nom" name="nom" placeholder="votre nom" value="'. $nom .'"><br>
				          
				    <label for="prenom">Prénom</label><br>
				    <input type="text" id="prenom" name="prenom" placeholder="votre prénom" value='. $prenom .'><br>
				  
				    <label for="email">Email</label><br>
				    <input type="email" id="email" name="email" placeholder="xxx@gmail.com" value="'. $email .'"><br>
				          
				    <label for="civilite">Civilité</label><br>
				    <input name="civilite" value="m"  type="radio"'. $civilite_m .'>Homme
				    <input name="civilite" value="f" type="radio"'. $civilite_f .'>Femme<br>
				                 			 
				    <input type="submit" name="inscription" value="Valider">

			</form>
		</section>';

 

