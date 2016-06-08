<?php
//===========================================================================================
//===========================================================================================
// ----- Fonctions

function debug($d, $mode = 1)
{
	echo '<div style="background: #45619D; color:white; padding: 10px; z-index:1000; border-bottom: 1px solid #000; position: fixed; right: 0; bottom: 0; ">';

		$trace = debug_backtrace(); 

		($mode == 1)? $debugType = '"var_dump()"' : $debugType = '"print_r()"';

		echo '<p>Debug ' .$debugType. ' demandé dans le fichier <strong>' . $trace[0]['file'] . '</strong> à la ligne <strong>' . $trace[0]['line'] . '</strong>.</p>';

		if($mode == 1)
		{ 
			var_dump($d);
		}
		else 
		{ 
			echo '<pre>'; print_r($d); echo '</pre>'; 
		}
		
	echo '</div>';
}

//===========================================================================================

function execute_requete($req)
{
	global $pdo;
	$result = $pdo->query($req);
	return $result;
}

//===========================================================================================

function search_bar()
{

	echo '<form method="post" action="?page=catalogue">';
	echo '<input type="text" name="recherche" class="form-control" placeholder="Search..." style="margin: 8px;">';
	//echo '<input type="submit" value="valider" style="margin: 8px;">';
	echo '</form>';
	
}

//===========================================================================================
// Phase de test :
