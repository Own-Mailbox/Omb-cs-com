<?php

function postfix_update_domain($domain,$torhidenservice)
{
    //check if the torhidenservice or the domain is empty
    if ($torhidenservice=="" or $domain=="")
    {
      //echo "domain or tor hidden service is empty\n";
      return;
    }

    $allready_exists=0;
    //check if domain allready exists in the table.
    include 'global_variables.php';

    $link2 =  mysqli_connect('localhost', $db_user, $db_passphrase);
    if (!$link2) {echo "tls_proxy: conection à la base de donnée impossible\n"; return;}

    $db_selected = mysqli_select_db($link2,$data_base_postfix);

       $query=sprintf(" SELECT COUNT(ID) AS NB FROM ".mysqli_real_escape_string ($link2, strip_tags($table_postfix))." WHERE address= '".mysqli_real_escape_string ($link2, strip_tags($domain.$domain_post_fix.".tor"))."'");
    $reponse= mysqli_query($link2,$query);

      if (!$reponse) {
	    $message  = 'Invalid query: ' . mysqli_error($link2) . "\n";
	    $message .= 'Whole query: ' . $query;
	    echo $message;
	    return ;
	  }

      // On affiche chaque entrée une à une

    if ($donnees = mysqli_fetch_assoc($reponse))
	{
	if($donnees['NB']>0)
	  $allready_exists=1;
	}

    //On réalise une opération différente en fonction
    //de si le domaine existe
    if($allready_exists)
    {

    echo "Error: postfix_database update not yet supported!\n";

    }
    else
    {

    $query=sprintf(" INSERT  INTO ".mysqli_real_escape_string ($link2,strip_tags($table_postfix))."(address,transportation) VALUES('".mysqli_real_escape_string ($link2, strip_tags($domain.$domain_post_fix.".tor"))."','".mysqli_real_escape_string ($link2, strip_tags($postfix_tor_transportation_prefix.":[".$torhidenservice."]"))."')");
    $reponse= mysqli_query($link2,$query);

    if (!$reponse) {
	    $message  = 'Invalid query: ' . mysqli_error($link2) . "\n";
	    $message .= 'Whole query: ' . $query. "\n";
	    echo $message;
	    return;
	  }

    }

}




?>
