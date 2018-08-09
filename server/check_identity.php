<?php
    include 'global_variables.php';
    $link =  mysqli_connect('localhost', $db_user, $db_passphrase);
    if (!$link) {die("connection à la base de donnée impossible");}

   $db_selected = mysqli_select_db($link,$db_name);


   $query=sprintf(" SELECT COUNT(ID) AS DB FROM Customers WHERE ID=".mysqli_real_escape_string ($link, strip_tags($_COOKIE['ID']))." AND passphrase='".mysqli_real_escape_string ($link, strip_tags($_COOKIE['passphrase']))."'");
   $reponse= mysqli_query($link,$query);

   if (!$reponse) {
	      $message  = 'Invalid query: ' . mysqli_error($link) . "\n";
	      $message .= 'Whole query: ' . $query;
	      die($message);
	    }


    // On récupère les données
    if ($donnees = mysqli_fetch_assoc($reponse))
	 {
	    if ($donnees['DB']!=1)
	      {
	      echo "Invalid identification cookie.";
	      die();
	      }
	 }

?>
