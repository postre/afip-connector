<?php 
use AfipConnector\Afip\Wsfev1;

require '..\..\AfipConnector\autoload.php';
$wsfe = new Wsfev1();
echo $wsfe->json()->FECompConsultar(4,6,12);
