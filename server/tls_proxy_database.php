<?php

function tls_proxy_update_domain($domain,$torhidenservice)
{
    //check if the torhidenservice or the domain is empty
    if ($torhidenservice=="" or $domain=="")
    {
      return;
    }

    $allready_exists=0;
    //check if domain allready exists in the table.
    include 'global_variables.php';

    $link =  mysqli_connect('localhost', $db_user, $db_passphrase);
    if (!$link) {echo "tls_proxy: conection à la base de donnée impossible\n"; return;}

    $db_selected = mysqli_select_db($link,$db_name);

    $query=sprintf(" SELECT COUNT(ID) AS NB FROM ".mysqli_real_escape_string ($link, strip_tags($table_tls_proxy))." WHERE hostname= '".mysqli_real_escape_string ($link, strip_tags($domain.$domain_post_fix))."'");
    $reponse= mysqli_query($link,$query);

      if (!$reponse) {
	    $message  = 'Invalid query: ' . mysqli_error($link) . "\n";
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

    echo "Error: tls_proxy_database update not yet supported!\n";

    }
    else
    {


    $query=sprintf(" INSERT  INTO ".mysqli_real_escape_string ($link, strip_tags($table_tls_proxy))."(hostname,torservice) VALUES('".mysqli_real_escape_string ($link, strip_tags($domain.$domain_post_fix))."','".mysqli_real_escape_string ($link, strip_tags($torhidenservice))."')");
    $reponse= mysqli_query($link,$query);

    if (!$reponse) {
	    $message  = 'Invalid query: ' . mysqli_error($link) . "\n";
	    $message .= 'Whole query: ' . $query. "\n";
	    echo $message;
	    return;
	  }

    }

}




?>
