<?php
require_once '../app.php';

use Orcamentos\Model\Plan;

// php firstPlan.php "Plano beta" 49.90 null

$em = $app['orm.em'];
$alreadyDone = $em->getRepository('Orcamentos\Model\Plan')->findAll();

if ( count($alreadyDone) == 0){
	$planName = $argv[1];
	$planPrice = $argv[2];
	$planQuoteLimit = $argv[3];

	$plan = new Plan();
	$plan->setName($planName);
	$plan->setPrice($planPrice);
	$plan->setQuoteLimit($planQuoteLimit);

	$em->persist($plan);

	$companies = $em->getRepository('Orcamentos\Model\Company')->findAll();

	foreach ($companies as $company) {
		$company->setPlan($plan);
		$em->persist($company);
	}

	$em->flush();
}

return true;