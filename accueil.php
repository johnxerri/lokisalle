<?php
$content .= '
    <!-- Page Content -->
        <div class="container">

            <div class="row">

                <div class="col-md-3">
                    <p class="lead">Catégorie</p>
                    <div class="list-group">';
                        
    $resultat = $pdo->query('SELECT DISTINCT(categorie) FROM salle');
    while($categorie = $resultat->fetch(PDO::FETCH_ASSOC))
    {
       $content .= '<a href="?page=accueil&categorie='.$categorie['categorie'].'" class="list-group-item">'. $categorie['categorie'] . '</a>';
    }
                        
    $content .= '
                    </div>

                    <p class="lead">Ville</p>
                    <div class="list-group">';
                        
    $resultat = $pdo->query('SELECT DISTINCT(ville) FROM salle');

    $content .= '<select class="list-group-item">';
    while($ville = $resultat->fetch(PDO::FETCH_ASSOC))
    {
       $content .= '<option>'. $ville['ville'] . '</option>';
    }
    $content .= '</select>';
                        
    $content .= '
                    </div>

                </div>

                <div class="col-md-9">

                    <div class="row">';
    if(isset($_GET['categorie'])){

        if(isset($_GET['categorie'])){
            $resultat = $pdo->query("SELECT * FROM salle WHERE categorie = '$_GET[categorie]'");
            $content .= '   <div class="col-md-offset-2 col-md-8"><h3>Vous etes dans la catégorie ' . $_GET["categorie"] . ' : ' . $resultat->rowCount() . ' article trouvé(s)</h3></div>';
        }


        //$content .= '   <div class="col-md-offset-2 col-md-8"><h3>Vous etes dans la catégorie ' . $_GET["categorie"] . ' : ' . $resultat->rowCount() . ' article trouvé(s)</h3></div>';

        while($articleFront = $resultat->fetch(PDO::FETCH_ASSOC))
        {
        // $content .= '   <div class="col-sm-4 col-lg-4 col-md-4">
        //                     <div class="thumbnail">';
        // $content .= "               <a href='?page=page_produit&produit=$articleFront[titre]'><img src='$articleFront[photo]' alt='' class='img-responsive'></a>";
        // $content .= "               <div class='caption'>";
        // $content .= "                   <h4 class='pull-right'>\$$articleFront[prix]</h4>";
        // $content .= "                   <h4><a href='?page=page_produit&produit=$articleFront[titre]'>$articleFront[titre]</a>";
        // $content .= "                   </h4>";
        // $content .= "                   <p>$articleFront[description]</p>";
        // $content .= '               </div>
        //                             <div class="ratings">
        //                                 <p class="pull-right">15 reviews</p>
        //                                 <p>
        //                                     <span class="glyphicon glyphicon-star"></span>
        //                                     <span class="glyphicon glyphicon-star"></span>
        //                                     <span class="glyphicon glyphicon-star"></span>
        //                                     <span class="glyphicon glyphicon-star"></span>
        //                                     <span class="glyphicon glyphicon-star"></span>
        //                                 </p>
        //                             </div>
        //                     </div>
        //                 </div>';
        }

    }
    $content .= '   </div>
                </div>

            </div>

        </div>
        <!-- /.container -->';