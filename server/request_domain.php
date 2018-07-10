<?php

$domain= $_GET["domain"];

include 'check_identity.php';

include 'global_variables.php';

//**********************************************
//Check that the customer has no omb domain yet.
//**********************************************

$link =  mysqli_connect('localhost', $db_user, $db_passphrase);
  if (!$link) {die("conection à la base de donnée impossible");}

  $db_selected = mysqli_select_db($link,$db_name);


  $query=sprintf(" SELECT LENGTH(domain_omb) FROM Customers WHERE ID=".mysqli_real_escape_string ($link, strip_tags($_COOKIE['ID'])));
  $reponse= mysqli_query($link,$query);

  if (!$reponse) {
	    $message  = 'Invalid query: ' . mysqli_error($link) . "\n";
	    $message .= 'Whole query: ' . $query;
	    die($message);
	  }

 // On affiche chaque entrée une à une

 if ($donnees = mysqli_fetch_assoc($reponse))
	{
	  if ($donnees['LENGTH(domain_omb)']>0)
	    {
	    echo "This account allready has a domain.\n";
	    die();
	    }
	}


if (strlen($domain)<3)
  die("The requested domain is too short");
if (strlen($domain)>30)
  die("The requested domain is too long");
if (preg_match('/[^a-z0-9]/', $domain))
  die("Requested forbiden domain: please only use lower case lettres and digits.");

//*********************************************
//Check that the domain is not allready in use.
//*********************************************

  $query=sprintf(" SELECT COUNT(ID) as NB FROM Customers WHERE domain_omb=\"".mysqli_real_escape_string ($link, strip_tags($domain))."\"");
  $reponse= mysqli_query($link,$query);

  if (!$reponse) {
	    $message  = 'Invalid query: ' . mysqli_error($link) . "\n";
	    $message .= 'Whole query: ' . $query;
	    die($message);
	  }

 // On affiche chaque entrée une à une

 if ($donnees = mysqli_fetch_assoc($reponse))
	{
	  if ($donnees['NB']>0)
	    {
	    echo "This domain is allready used by another user.\n";
	    die();
	    }
	}


//*********************************************
//Get corresponding tor hidden service
//*********************************************

$torhidenservice="";

  $query=sprintf(" SELECT tor_hidden FROM Customers WHERE ID=".mysqli_real_escape_string ($link, strip_tags($_COOKIE['ID'])));
  $reponse= mysqli_query($link,$query);

  if (!$reponse) {
	    $message  = 'Invalid query: ' . mysqli_error($link) . "\n";
	    $message .= 'Whole query: ' . $query;
	    die($message);
	  }

 // On affiche chaque entrée une à une

 if ($donnees = mysqli_fetch_assoc($reponse))
	{
	$torhidenservice=$donnees['tor_hidden'];
	}


//*********************************************
//Update entry for TLS proxy
//*********************************************
 include 'tls_proxy_database.php';
tls_proxy_update_domain($domain,$torhidenservice);


//*********************************************
//Update entry for SMTP proxy
//*********************************************
include 'postfix_database.php';
postfix_update_domain($domain,$torhidenservice);

//*********************************************
//Update entry in bind DNS server
//*********************************************
 include 'setup_dns.php';
dns_update_domain($domain,$torhidenservice);

//*********************************************
//Add domain in
//*********************************************

$torhidenservice="";

  $link =  mysqli_connect('localhost', $db_user, $db_passphrase);
  if (!$link) {die("conection à la base de donnée impossible");}

  $db_selected = mysqli_select_db($link,$db_name);

  $query=sprintf(" UPDATE Customers set domain_omb =\"".$domain."\" WHERE ID=".mysqli_real_escape_string ($link, strip_tags($_COOKIE['ID'])));
  $reponse= mysqli_query($link,$query);
      
  if (!$reponse) {
	    $message  = 'Invalid query: ' . mysqli_error($link) . "\n";
	    $message .= 'Whole query: ' . $query;
	    die($message);
	  }
  echo "OK\n";
?>
