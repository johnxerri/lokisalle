<?php require_once('inc/init.inc.php');


if(isset($_GET['page']))
{ 
    $page = $_GET['page'].'.php';
}
elseif(!isset($_GET['page']))
{
    $page = 'accueil.php';
} 
else{
        $content .= '<div class="alert alert-danger">La demande n\'a pas pu aboutir</div>';
}


if(file_exists($page))  require_once($page); 
/*

if(empty($_GET['page']) || $_GET['page'] == 'accueil') {
    $_GET['page'] = 'accueil'; 
    require_once($_GET['page'] . '.php');
} else // S'il y a une information dans l'url, c'est donc que nous avons cliqué sur un des liens...
{
      if(file_exists($_GET['page'] . '.php')) // Si le fichier existe
        require_once($_GET['page'] . '.php'); // On le charge (inclusion)
      else // Sinon (on ne trouve pas le fichier ...)
        $content .= '<div class="alert alert-danger">La demande n\'a pas pu aboutir</div>';
}
*/
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!-- <meta name="viewport" content="width=device-width, initial-scale=1"> -->
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Projet Lokisalle</title>

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
                    <a class="navbar-brand" href="<?= URL; ?>">Lokisalle</a>
                </div>
                <div id="navbar" class="collapse navbar-collapse">
                    <ul class="nav navbar-nav">
                        <li>Qui sommes nous</li>
                        <li>Contacts</li>
                    <?php if(isset($_SESSION['membre']) && $_SESSION['membre']['statut'] == 0) : // Je suis connecté donc membre ?>
                        <li><a href="?page=profil">Profil</a></li>
                        <li><a href="?page=connexion&action=deconnexion"><i class="fa fa-power-off" aria-hidden="true"></i></a></li>

                    <?php elseif (isset($_SESSION['membre']) && $_SESSION['membre']['statut'] == 1) : // Je suis admin ?>
                        <li><a href="<?= URL . 'admin'; ?>">BackOffice</a></li>
                        <li><a href="?page=profil"><?= ucfirst($_SESSION['membre']['nom']) . ' - Admin ' ?></a></li>
                        <li><a href="?page=connexion&action=deconnexion"><i class="fa fa-power-off" aria-hidden="true"></i></a></li>

                    <?php else : // Je ne suis pas connecté donc visiteur ?>
                        <li><a href="?page=inscription">Inscription</a></li>
                        <li><a href="?page=connexion">Connexion</a></li>

                    <?php endif; ?>
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