<?php 

//--------------------------------------------------------------------------------------------------------------------------------//
//--------------------------------------------------------- GESTION CRUD ---------------------------------------------------------//
//--------------------------------------------------------------------------------------------------------------------------------//


//=== ENREGISTREMENT D'UN MEMBRE ===//
if($_POST) // Si l'admin valide le formulaire 
{
	// ------------ CONTROLES ET ERREURS :
	$erreur = '';
	// Controle de la taille
	if(strlen($_POST['pseudo']) <= 3 || strlen($_POST['pseudo']) > 20){
		// si la taille est inferieur (ou egal) a 3 ou superieur a 20

		$erreur .= '<div class="alert alert-danger"><p>Le pseudo fait : ' . strlen($_POST['pseudo']) . ' caracteres</p>';
		$erreur .= '<p>Le pseudo doit etre compris entre 3 caracteres min et 20 caracteres max.</p></div>';

	} 

	// Controle du format
	if( !preg_match('#^[a-zA-Z0-9._-]+$#', $_POST['pseudo']) ) {

		$erreur .= '<div class="alert alert-danger">Erreur caracteres interdit dans le Pseudo !</div>';

	} 

	// Verification du mail complet et correct
	if (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
		$erreur .= '<div class="alert alert-danger">Votre email n\'est pas valide</div>';		
	}

	// Controle du pseudo
	$result = execute_requete("SELECT * FROM membre WHERE pseudo = '$_POST[pseudo]'");
	if($result->rowCount() >= 1){ // s'il y a 1 résultat ou plus ..
		$erreur .= '<div class="alert alert-danger">Insertion indisponible - Pseudo déja présent dans la base !</div>';
	}

	// Controle du mail
	$result = execute_requete("SELECT * FROM membre WHERE email = '$_POST[email]'");
	if($result->rowCount() >= 1){
		$erreur .= '<div class="alert alert-danger">Insertion indisponible - Email déja présent dans la base !</div>';
	}

	// ------------ VALIDATION :
	if( empty($erreur) ){ // Si $erreur est vide donc pas d erreur

		$_POST['mdp'] = password_hash($_POST['mdp'], PASSWORD_DEFAULT); // Cryptage du mdp.

		$id_membre = (isset($_GET['id_membre'])) ? $_GET['id_membre'] : 'NULL';
		execute_requete("REPLACE INTO membre (id_membre, pseudo, mdp, nom, prenom, email, civilite, statut, date_enregistrement) VALUES ('$id_membre', '$_POST[pseudo]', '$_POST[mdp]', '$_POST[nom]', '$_POST[prenom]', '$_POST[email]', '$_POST[civilite]', '$_POST[statut]', NOW() ) ");
		$content .= '<div class="alert alert-success">Un membre à été ajouté ;) !</div>';
		// Redirection vers l'url du site (index) > la page connexion
		header('location:' . URL . 'admin/?page=gestion-membres');
	}

	// ------------ TRANSMISSION DES ERREURS AU CONTENU :
	$content .= $erreur;
}

//=== MODIFICATION D'UN MEMBRE ==//
if(isset($_GET['action']) && $_GET['action'] == 'modification'){

	$r = $pdo->query("SELECT * FROM membre WHERE id_membre = $_GET[id_membre]");
	$membre = $r->fetch(PDO::FETCH_ASSOC);
}

$pseudo = (isset($membre['pseudo'])) ? $membre['pseudo'] : '';
$mdp = (isset($membre['mdp'])) ? $membre['mdp'] : '';
$nom = (isset($membre['nom'])) ? $membre['nom'] : '';
$prenom = (isset($membre['prenom'])) ? $membre['prenom'] : '';
$email = (isset($membre['email'])) ? $membre['email'] : '';

$civilite_m = (!isset($_POST['civilite'])) || (isset($_POST['civilite']) && $_POST['civilite'] == 'm') ? 'selected' : '';
$civilite_f = (isset($_POST['civilite']) && $_POST['civilite'] == 'f') ? 'selected' : '';

$statut_m = (!isset($_POST['statut'])) || (isset($_POST['statut']) && $_POST['statut'] == 0) ? 'selected' : '';
$statut_a = (isset($_POST['statut']) && $_POST['statut'] == 1) ? 'selected' : '';


//=== SUPPRESSION D'UN MEMBRE ===//
if(isset($_GET['action']) && $_GET['action'] == 'suppression'){

	execute_requete("DELETE FROM membre WHERE id_membre = $_GET[id_membre]");
	$content .= "<div class='alert alert-success'>Le membre $_GET[id_membre] à bien été supprimé ;) !</div>";

}


//--------------------------------------------------------------------------------------------------------------------------------//
//---------------------------------------------------- Affichage des Membres -----------------------------------------------------//
//--------------------------------------------------------------------------------------------------------------------------------//


	$resultat = execute_requete("SELECT id_membre, pseudo, nom, prenom, email, civilite, statut, DATE_FORMAT(date_enregistrement, '%d/%m/%Y %H:%i') FROM membre");
	$content .= "<section><h2>Gestion des membres</h2>";
	$content .= "<article class='tableau'><table class='table'><tr>";
	$content .= "<th> id membre </th><th> Pseudo </th><th> nom </th><th> prenom </th><th> email </th><th> civilité </th><th> statut </th><th> date d'enregistrement </th><th> action </th>";
	$content .= "</tr>";
	while($membre = $resultat->fetch(PDO::FETCH_ASSOC))
	{
		$content .= '<tr>';
		$membre['statut'] = str_replace(0 ,'membre', $membre['statut']);
		$membre['statut'] = str_replace(1 ,'admin', $membre['statut']);

		$membre['civilite'] = str_replace('m' ,'Homme &#9794;', $membre['civilite']);
		$membre['civilite'] = str_replace('f' ,'Femme &#9792;', $membre['civilite']);

		$membre['civilite'] = ($membre['civilite'] == 'Femme &#9792;') ? '<font color="pink">'.$membre['civilite'].'</font>' : '<font color="blue">'.$membre['civilite'].'</font>';

		foreach ($membre as $indice => $valeur) 
		{
			if($indice == 'mdp'){
				$content .= "";
			} else {
				$content .= "<td> $valeur </td>";
			}
		}
		//debug($produits);

		// MODIFICATION
		$content .= '<td><a href="' . URL . 'admin/?page=gestion-membres&action=modification&id_membre=' .$membre['id_membre']. '" onClick="return(confirm(\'En etes vous certain ?\'))"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> </a>
		<a href="' . URL . 'admin/?page=gestion-membres&action=suppression&id_membre=' .$membre['id_membre']. '" onClick="return(confirm(\'En etes vous certain ?\'))"> <i class="fa fa-trash-o" aria-hidden="true"></i></a></td>';

		$content .= '</tr>';

	}
	$content .= '</table></article></section>';

	$content .= "<hr /><p>Nombre total de membre(s) <span class='badge'>".$resultat->rowCount()."</span></p><hr />";



//--------------------------------------------------------------------------------------------------------------------------------//
//---------------------------------------------------------- Formulaire ----------------------------------------------------------//
//--------------------------------------------------------------------------------------------------------------------------------//



// $content .= 'Bonjour Gestion des membres.';
$content .= '<form method="post" action="" class="row jumbotron">

				<article class="col-md-offset-1 col-md-5">
					<p>
						<label for="pseudo">Pseudo</label>
						<input type="text" name="pseudo" class="form-control" id="pseudo" value="' . $pseudo . '" />
					</p>

					<p>
						<label for="mdp">Mot de passe</label>
						<input type="password" name="mdp" class="form-control" id="mdp" value="' . $mdp . '" required />
					</p>

					<p>
						<label for="nom">Nom</label>
						<input type="text" name="nom" class="form-control" id="nom" value="' . $nom . '" required />
					</p>

					<p>
						<label for="prenom">Prénom</label>
						<input type="text" name="prenom" class="form-control" id="prenom" value="' . $prenom . '" required />
					</p>
				</article>

				<article class="col-md-5">
					<p>
						<label for="email">@mail</label>
						<input type="email" name="email" class="form-control" id="email" value="' . $email . '" />
					</p>

					<p>
						<label for="civilite">Civilité</label><br>
						<select name="civilite" class="form-control">
							<option value="m" '. $civilite_m .'>Homme</option> 
							<option value="f" '. $civilite_f .'>Femme</option>
						</select>
					</p>

					<p>
						<label for="statut">Statut</label><br>
						<select name="statut" class="form-control">
							<option value="0" '. $statut_m .'>Membre</option> 
							<option value="1" '. $statut_a .'>Admin</option>
						</select>
					</p>

					<p>
						<input type="submit" class="btn btn-primary" name="inscription" value="Valider">
					</p>
				</article>

			</form>';