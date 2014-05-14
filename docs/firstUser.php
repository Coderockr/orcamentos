<?php
require_once '../app.php';

use Orcamentos\Service\Company as CompanyService;
use Orcamentos\Service\User as UserService;


// php firstUser.php Cacatua Jr. 4936478080 heh@huhu.com marcos 123123 marcos@huhu.com

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
$company = $companyService->save($companyData, null, $app['orm.em']);

$userData = array(
	'name' => $userName,
	'password' => $userPassword,
	'email' => $userEmail,
	'companyId' => $company->getId(),
	'admin' => true
);

$userData = json_encode($userData);
$userService = new UserService();
 $user = $userService->save($userData, $app['orm.em']);
