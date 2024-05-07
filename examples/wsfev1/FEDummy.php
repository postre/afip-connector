<?php 
use AfipConnector\Afip\Wsfev1;

require '..\..\AfipConnector\autoload.php';
$wsfe = new Wsfev1();
echo $wsfe->json()->FEDummy();
