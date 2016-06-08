<?php require_once('../inc/init.inc.php');

if(!isset($_SESSION['membre']) || isset($_SESSION['membre']) && $_SESSION['membre']['statut'] != 1){ 
// Si la session membre n est pas definie.

  header('location:' . URL . '?page=connexion'); 
  exit(); // exit(); arrete l execution RIGHT NOW !!

}


if($_GET)
{
      if(file_exists($_GET['page'] . '.php')) 
        require_once($_GET['page'] . '.php'); 
      else
        $content .= '<div class="alert alert-danger">La demande n\'a pas pu aboutir</div>';
}
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>BackOffice</title>

        <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
        <link href="<?= URL; ?>/css/bootstrap.min.css" rel="stylesheet" />
        <link href="<?= URL; ?>/css/style.css" rel="stylesheet" type="text/css" />
    </head>

    <body>

        <nav class="navbar navbar-inverse navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="<?= URL; ?>admin/">Lokisalle</a>
                </div>
                <div id="navbar" class="collapse navbar-collapse">
                    <ul class="nav navbar-nav">
                        <li><a href="?page=gestion-salles">Gestion des salles</a></li>
                        <li><a href="?page=gestion-produits">Gestion des produits</a></li>
                        <li><a href="?page=gestion-membres">Gestion des membres</a></li>
                        <li><a href="<?= URL . '?page=accueil'; ?>">Voir la boutique</a></li>
                        <li><a href="<?= URL ?>/?page=connexion&action=deconnexion"><i class="fa fa-power-off" aria-hidden="true"></i></a></li>
                        <li><?php search_bar(); ?></li>
                    </ul>
                </div><!--/.nav-collapse -->
            </div>
        </nav>

        <div class="container main">

            <div class="starter-template">
                <p class="lead"><?= $content; ?></p>
            </div>

        </div><!-- /.container -->

        <footer>

            <a href="?page=mentions">mentions légales</a> - <a href="?page=condition">Conditions générales de ventes</a>
            
        </footer>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="<?= URL; ?>/js/bootstrap.min.js"></script>

    </body>
</html>