<?php
require_once '../app.php';

use Orcamentos\Service\Company as CompanyService;
use Orcamentos\Service\User as UserService;

// php firstUser.php "Coderockr" Elton 4933218080 exemplo@empresa.com Mateus 123456 mateus@coderockr.com

$em = $app['orm.em'];
$companyName = $argv[1];
$companyResponsable = $argv[2];
$companyTelephone = $argv[3];
$companyEmail = $argv[4];

$userName = $argv[5];
$userPassword = $argv[6];
$userEmail = $argv[7];

$companyData = array(
	'name' => $companyName,
	'responsable' => $companyResponsable,
	'telephone' => $companyTelephone,
	'email' => $companyEmail
);

$companyData = json_encode($companyData);
$companyService = new CompanyService();
$companyService->setEm($em);
$company = $companyService->save($companyData, null, $em);


$userData = array(
	'name' => $userName,
	'password' => $userPassword,
	'email' => $userEmail,
	'companyId' => $company->getId(),
	'admin' => true
);

$userData = json_encode($userData);
$userService = new UserService();
$userService->setEm($em);
$user = $userService->save($userData, $em);

return true;