<?php
/*  Cet exemple vous permet de passer une commande. L'envoi est composé d'informations basiques (expéditeur, destinataire, type) 
 *  et ne contient pas d'options supplémentaires. Il possède uniquement un filtre selon lequel le montant de la commande ne peut 
 *  pas dépasser 50€ ttc.
 */ 
ob_start();
header('Content-Type: text/html; charset=utf-8');
error_reporting(E_ERROR | E_WARNING | E_PARSE);
require_once('../utils/header.php');
require_once $_SERVER['DOCUMENT_ROOT'].'/api/librairie/utils/autoload.php';
$orderPMStyle = 'style="font-weight:bold;"';
// Informations sur l'expéditeur et le destinataire 
$from = array("pays" => "FR", "code_postal" => "75002", "type" => "particulier",
"ville" => "Paris", "adresse" => "41, rue Saint-Augustin | 3e étage", 
"civilite" => "M", "prenom" => "Développeur", "nom" => "Boxtale", "email" => "dev@boxtale.com",
"tel" => "0601010101", "infos" => "Frapper 3 fois");
$to = array("pays" => "FR", "code_postal" => "13005", "type" => "particulier",
"ville" => "Marseille", "adresse" => "1, rue Saint-Thome",
"civilite" => "Mme", "prenom" => "Autre prénom", "nom" => "Nom du destinataire", 
"email" => "dev@boxtale.com", "tel" => "0601010101", "infos" => "");

// Informations sur la cotation
$quotInfo = array("collecte" => date("Y-m-d"), 
"delai" => "aucun",  "code_contenu" => 10120,
 "operateur" => "UPSE", //<= opérateur UPS,vous pouvez décommenter et commenter les lignes suivantes (jusqu'à retrait.pointrelais) pour voir ce que cela donne
 "service" => "Standard",
 "disponibilite.HDE" => "09:00", 
 "disponibilite.HLE" => "19:00", 
 "assurance.selection" => true,
 "assurance.emballage" => "Boîte",
 "assurance.materiau" => "Film opaque",
 "assurance.protection" => "Calage papier",
 "assurance.fermeture" => "Agrafes",
 "valeur" => 300,
// "operateur" => "SOGP",
// "collection_type" => "DROPOFF_POINT",
// "delivery_type" => "PICKUP_POINT",
// "depot.pointrelais" => "SOGP-C3051", 
// "retrait.pointrelais" => "SOGP-Q1117",
//"prix_max_ttc" => 20,
"description" => "Le Monde, années 1990-1992"
);
$cotCl = new Env_Quotation(array("user" => "bartosz", "pass" => "bartOOOSw", "key" => "xx00xxYY__AEZRS"));
$cotCl->setPerson("expediteur", $from);
$cotCl->setPerson("destinataire", $to);
$cotCl->setType("colis", array( "assurance.selection" => true,"poids" => 2, "longueur" => 13, "largeur" => 11, "hauteur" => 12));
$orderPassed = $cotCl->makeOrder($quotInfo, true);
if(!$cotCl->curlError && !$cotCl->respError) { 
  if($orderPassed) {
    echo "L'envoi a été correctement réalisé sous référence ".$cotCl->order['ref'];
  }
  else {
    echo "L'envoi n'a pas été correctement réalisé. Une erreur s'est produite.";
  }
}
elseif($cotCl->respError) {
  echo "La requête n'est pas valide : ";
  foreach($cotCl->respErrorsList as $m => $message) { 
    echo "<br />".$message['message'];
  }
}
else {
  echo "<b>Une erreur pendant l'envoi de la requête </b> : ".$cotCl->curlErrorText; 
}
require_once('../utils/footer.php');?>
 