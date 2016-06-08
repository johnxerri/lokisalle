<?php 

//--------------------------------------------------------------------------------------------------------------------------------//
//--------------------------------------------------------- GESTION CRUD ---------------------------------------------------------//
//--------------------------------------------------------------------------------------------------------------------------------//


//=== ENREGISTREMENT D'UN MEMBRE ===//
if($_POST) // Si l'admin valide le formulaire 
{
	// ------------ CONTROLES ET ERREURS :
	$erreur = '';

	$photo_bdd = '';

	if(isset($_GET['action']) && $_GET['action'] == 'modification'){
		$photo_bdd = $_POST['photo_actuelle'];
	}

	if(!empty($_FILES['photo']['name'])) // Si il y a un nom de photo
	{
		// $content .= '<div class="alert alert-success">Ajout d\'un produit avec photo !</div>';

		$photo_bdd = URL . "img/photo/$_POST[titre]_" . $_FILES['photo']['name']; // Cette variable nous permettra de sauvegarder le chemin dans la base
		// $content .= "<div class='alert alert-success'>chemin bdd => $photo_bdd</div>";
		$photo_dossier = RACINE . "img/photo/$_POST[titre]_" . $_FILES['photo']['name']; // Cette variable nous permettra de sauvegarder la photo dans le dossier
		// $content .= "<div class='alert alert-success'>chemin dossier => $photo_dossier</div>";
		copy($_FILES['photo']['tmp_name'], $photo_dossier); // copy() permet de sauvegarder un fichier sur le serveur.
	}

	// Controle du format
	if( empty($_POST['titre']) || empty($_POST['description']) || empty($_FILES['photo']) || empty($_POST['pays']) || empty($_POST['ville']) || empty($_POST['adresse']) || empty($_POST['cp']) || empty($_POST['capacite']) || empty($_POST['categorie']) ) {

		$erreur .= '<div class="alert alert-danger">Tout doit etre remplie sens faute c\'est mieux fradet !! !</div>';

	}

	// ------------ VALIDATION :
	if( empty($erreur) ){ // Si $erreur est vide donc pas d erreur

		$id_salle = (isset($_GET['id_salle'])) ? $_GET['id_salle'] : 'NULL';
		execute_requete("REPLACE INTO salle (id_salle, titre, description, photo, pays, ville, adresse, cp, capacite, categorie) VALUES ('$id_salle', '$_POST[titre]', '$_POST[description]', '$photo_bdd', '$_POST[pays]', '$_POST[ville]', '$_POST[adresse]', '$_POST[cp]', '$_POST[capacite]', '$_POST[categorie]' ) ");
		$content .= '<div class="alert alert-success">Une salle à été ajouté ;) !</div>';
		// Redirection vers l'url du site (index) > la page connexion
		header('location:' . URL . 'admin/?page=gestion-salles');
	}

	// ------------ TRANSMISSION DES ERREURS AU CONTENU :
	$content .= $erreur;
}

//=== MODIFICATION D'UN MEMBRE ==//
if(isset($_GET['action']) && $_GET['action'] == 'modification'){

	$r = $pdo->query("SELECT * FROM salle WHERE id_salle = $_GET[id_salle]");
	$membre = $r->fetch(PDO::FETCH_ASSOC);
}

$titre = (isset($membre['titre'])) ? $membre['titre'] : '';
$description = (isset($membre['description'])) ? $membre['description'] : '';
$photo = (isset($membre['photo'])) ? $membre['photo'] : '';
$capacite = (isset($membre['capacite'])) ? $membre['capacite'] : '';
$categorie = (isset($membre['categorie'])) ? $membre['categorie'] : '';
$pays = (isset($membre['pays'])) ? $membre['pays'] : '';
$ville = (isset($membre['ville'])) ? $membre['ville'] : '';
$adresse = (isset($membre['adresse'])) ? $membre['adresse'] : '';
$cp = (isset($membre['cp'])) ? $membre['cp'] : '';

$categorie1 = (!isset($membre['categorie'])) || (isset($membre['categorie']) && $membre['categorie'] == 'bureau') ? 'selected' : '';
$categorie2 = (isset($membre['categorie']) && $membre['categorie'] == 'formation') ? 'selected' : '';
$categorie3 = (isset($membre['categorie']) && $membre['categorie'] == 'reunion') ? 'selected' : '';


//=== SUPPRESSION D'UN MEMBRE ===//
if(isset($_GET['action']) && $_GET['action'] == 'suppression'){

	execute_requete("DELETE FROM salle WHERE id_salle = $_GET[id_salle]");
	header('location:' . URL . 'admin/?page=gestion-salles&message=suppr&titre=' . $_GET['titre']);

}

if(isset($_GET['message']) && $_GET['message'] == 'suppr' ){
	$content .= "<div class='alert alert-success'>La salle $_GET[titre] à bien été supprimé ;) !</div>";
}


//--------------------------------------------------------------------------------------------------------------------------------//
//---------------------------------------------------- Affichage des Membres -----------------------------------------------------//
//--------------------------------------------------------------------------------------------------------------------------------//


	$resultat = execute_requete("SELECT * FROM salle");
	$content .= "<section><h2>Gestion des salles</h2>";
	$content .= "<article class='tableau'><table class='table'><tr>";
	for($i =0; $i < $resultat-> columnCount(); $i++) {	
			$colonne = $resultat ->getColumnMeta($i);
			$content.= "<th>$colonne[name]</th>";
	}
	$content .= "<th> actions </th>";
	$content .= "</tr>";
	while($salles = $resultat->fetch(PDO::FETCH_ASSOC))
	{
		$content .= '<tr>';

		foreach ($salles as $indice => $valeur) 
		{
			if($indice == 'photo'){ $content .= "<td><img src='$valeur' class='img_backOffice' /></td>"; }
			else { $content .= "<td>$valeur</td>"; }
		}
		//debug($produits);

		// MODIFICATION
		$content .= '<td><a href="' . URL . 'admin/?page=gestion-salles&action=modification&id_salle=' .$salles['id_salle']. '" onClick="return(confirm(\'En etes vous certain ?\'))"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> </a>
		<a href="' . URL . 'admin/?page=gestion-salles&action=suppression&id_salle=' .$salles['id_salle']. '&titre=' . $salles['titre'] . '" onClick="return(confirm(\'En etes vous certain ?\'))"> <i class="fa fa-trash-o" aria-hidden="true"></i></a></td>';

		$content .= '</tr>';

	}
	$content .= '</table></article></section>';

	$content .= "<hr /><p>Nombre total de salle(s) <span class='badge'>".$resultat->rowCount()."</span></p><hr />";



//--------------------------------------------------------------------------------------------------------------------------------//
//---------------------------------------------------------- Formulaire ----------------------------------------------------------//
//--------------------------------------------------------------------------------------------------------------------------------//



// $content .= 'Bonjour Gestion des membres.';
$content .= '<form method="post" action="" class="row jumbotron" enctype="multipart/form-data">

				<article class="col-md-offset-1 col-md-5">
					<p>
						<label for="titre">Titre</label>
						<input type="text" name="titre" class="form-control" id="titre" value="' . $titre . '" />
					</p>

					<p>
						<label for="description">Description</label>
						<textarea name="description" class="form-control" id="description" >' . $description . '</textarea>
					</p>

					<p class="form-group">
					<label for="photo">Photo</label>
					<input type="file" name="photo" id="photo" class="form-control" />';
					if(!empty($photo)){
						$content .= '<input type="hidden" name="photo_actuelle" value="' . $photo . '">';
					}
				$content .= '</p>

					<p>
						<label for="capacite">Capacité</label>
						<input type="text" name="capacite" class="form-control" id="capacite" value="' . $capacite . '" required />
					</p>

					<p>
						<label for="categorie">Catégorie</label>
						<select name="categorie" class="form-control">
							<option value="bureau" '. $categorie1 .'>Bureau</option> 
							<option value="formation" '. $categorie2 .'>Formation</option>
							<option value="reunion" '. $categorie3 .'>Reunion</option>
						</select>
					</p>
				</article>

				<article class="col-md-5">
					<p>
						<label for="pays">Pays</label>
						<input type="pays" name="pays" class="form-control" id="pays" value="' . $pays . '" />
					</p>

					<p>
						<label for="ville">Ville</label>
						<input type="text" name="ville" class="form-control" id="ville" value="' . $ville . '" required />
					</p>

					<p>
						<label for="adresse">Adresse</label>
						<textarea name="adresse" class="form-control" id="adresse" >' . $adresse . '</textarea>
					</p>

					<p>
						<label for="cp">Code Postal</label>
						<input type="text" name="cp" class="form-control" id="cp" value="' . $cp . '" required />
					</p>

					<p>
						<input type="submit" class="btn btn-primary" name="inscription" value="Enregistrer" />
					</p>
				</article>

			</form>';