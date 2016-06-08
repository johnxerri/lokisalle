<?php 

//--------------------------------------------------------------------------------------------------------------------------------//
//--------------------------------------------------------- GESTION CRUD ---------------------------------------------------------//
//--------------------------------------------------------------------------------------------------------------------------------//


//=== ENREGISTREMENT D'UN MEMBRE ===//
if($_POST) // Si l'admin valide le formulaire 
{
	// ------------ CONTROLES ET ERREURS :
	$erreur = '';

	// Controle du format
	if( empty($_POST['date_arrivee']) || empty($_POST['date_depart']) || empty($_POST['id_salle']) || empty($_POST['prix']) ) {

		$erreur .= '<div class="alert alert-danger">Tout doit etre remplie sens faute c\'est mieux fradet !! !</div>';

	}

	// ------------ VALIDATION :
	if( empty($erreur) ){ // Si $erreur est vide donc pas d erreur

		execute_requete("REPLACE INTO produit (date_arrivee, date_depart, id_salle, prix) VALUES ('$_POST[date_arrivee]', '$_POST[date_depart]', '$_POST[id_salle]', '$_POST[prix]' ) ");
		$content .= '<div class="alert alert-success">Une salle à été ajouté ;) !</div>';
		// Redirection vers l'url du site (index) > la page connexion
		//header('location:' . URL . 'admin/?page=gestion-produits');
	}

	// ------------ TRANSMISSION DES ERREURS AU CONTENU :
	$content .= $erreur;
}

//=== MODIFICATION D'UN PRODUIT ==//
if(isset($_GET['action']) && $_GET['action'] == 'modification'){

	$r = $pdo->query("
		SELECT id_produit, DATE_FORMAT(date_arrivee, '%d/%m/%Y') AS 'date_arrivee', DATE_FORMAT(date_depart, '%d/%m/%Y') AS 'date_depart', id_salle, prix
		FROM produit
		");
	$membre = $r->fetch(PDO::FETCH_ASSOC);
}

$date_arrivee = (isset($membre['date_arrivee'])) ? $membre['date_arrivee'] : '';
$date_depart = (isset($membre['date_depart'])) ? $membre['date_depart'] : '';
$id_salle = (isset($membre['id_salle'])) ? 'selected' : '';
$prix = (isset($membre['prix'])) ? $membre['prix'] : '';

//=== SUPPRESSION D'UN PRODUIT ===//
if(isset($_GET['action']) && $_GET['action'] == 'suppression'){

	execute_requete("DELETE FROM produit WHERE id_produit = $_GET[id_produit]");
	header('location:' . URL . 'admin/?page=gestion-produits&message=suppr&id_produit=' . $_GET['id_produit']);

}

if(isset($_GET['message']) && $_GET['message'] == 'suppr' ){
	$content .= "<div class='alert alert-success'>Le produit $_GET[id_produit] à bien été supprimé ;) !</div>";
}


//--------------------------------------------------------------------------------------------------------------------------------//
//---------------------------------------------------- Affichage des Membres -----------------------------------------------------//
//--------------------------------------------------------------------------------------------------------------------------------//


	$resultat = execute_requete("
		SELECT p.id_produit, DATE_FORMAT(p.date_arrivee, '%d/%m/%Y') AS 'date d\'arrivée', DATE_FORMAT(p.date_depart, '%d/%m/%Y') AS 'date de départ', p.id_salle, p.prix, p.etat, s.titre, s.photo
		FROM produit p, salle s
		WHERE s.id_salle = p.id_salle
		");
	$content .= "<section><h2>Gestion des produits</h2>";
	$content .= "<article class='tableau'><table class='table'><tr>";
	$content.= "<th>id produit</th><th>date d'arrivée</th><th>date de départ</th><th>id salle</th><th>prix</th><th>etat</th><th>actions</th>";
	$content .= "</tr>";
	while($produits = $resultat->fetch(PDO::FETCH_ASSOC))
	{
		$content .= '<tr>';

		foreach ($produits as $indice => $valeur) 
		{

			if($indice == 'date de départ'){ $content .= "<td>$valeur 19:00</td>"; }
			elseif($indice == 'date d\'arrivée'){ $content .= "<td>$valeur 09:00</td>"; }
			elseif($indice == 'id_salle'){ $content .= '<td>' . $produits['id_salle'] . ' - ' . $produits['titre'] . '<br /><img src="' . $produits['photo'] . '" class="img_backOffice" /></td>'; }
			elseif ($indice == 'titre') { $content .= ""; }
			elseif ($indice == 'photo') { $content .= ""; }
			else { $content .= "<td>$valeur</td>"; }

		}
		//debug($produits);

		// MODIFICATION
		$content .= '<td><a href="' . URL . 'admin/?page=gestion-produits&action=modification&id_produit=' .$produits['id_produit']. '" onClick="return(confirm(\'En etes vous certain ?\'))"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> </a>
		<a href="' . URL . 'admin/?page=gestion-produits&action=suppression&id_produit=' .$produits['id_produit']. '" onClick="return(confirm(\'En etes vous certain ?\'))"> <i class="fa fa-trash-o" aria-hidden="true"></i></a></td>';

		$content .= '</tr>';

	}
	$content .= '</table></article></section>';

	$content .= "<hr /><p>Nombre total de produit(s) <span class='badge'>".$resultat->rowCount()."</span></p><hr />";



//--------------------------------------------------------------------------------------------------------------------------------//
//---------------------------------------------------------- Formulaire ----------------------------------------------------------//
//--------------------------------------------------------------------------------------------------------------------------------//



// $content .= 'Bonjour Gestion des membres.';
$content .= '<form method="post" action="" class="row jumbotron" enctype="multipart/form-data">

				<article class="col-md-offset-1 col-md-5">
					<p>
						<label for="date_arrivee">Date d\'arrivée</label>
						<input type="date" name="date_arrivee" class="form-control" id="date_arrivee" value="' . $date_arrivee . '" />
					</p>

					<p>
						<label for="date_depart">Date de départ</label>
						<input type="date" name="date_depart" class="form-control" id="date_depart" value="' . $date_depart . '" />
					</p>
				</article>

				<article class="col-md-5">
					<p>
						<label for="salle">Salle</label>
						<select name="id_salle" id="salle" class="form-control">';
						$resultat = execute_requete("SELECT * FROM salle");
						while($salles = $resultat->fetch(PDO::FETCH_ASSOC))
						{
							$content .= '<option value="' . $salles['id_salle'] . '" ' . $id_salle . '>
							' . $salles['id_salle'] . ' - ' . $salles['titre'] . ' - ' . $salles['adresse'] . ', ' . $salles['cp'] . ', ' . $salles['ville'] . ' - ' . $salles['capacite'] . ' pers.
							</option>';
							
						}
			$content .= '</select>
					</p>

					<p>
						<label for="prix">Tarif</label>
						<input type="text" name="prix" class="form-control" id="prix" value="' . $prix . '" required />
					</p>

					<p>
						<input type="submit" class="btn btn-primary" name="inscription" value="Enregistrer" />
					</p>
				</article>

			</form>';