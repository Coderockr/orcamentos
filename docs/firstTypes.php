<?php 
require_once '../app.php';
use Orcamentos\Model\EquipmentType;
use Orcamentos\Model\ServiceType;
use Orcamentos\Model\HumanType;

$em = $app['orm.em'];

$types = $em->getRepository('Orcamentos\Model\Type')->findAll();

if (count($types)==0){
	$equipmentType = new EquipmentType();
	$equipmentType->setName('Computador');
	$em->persist($equipmentType);
	
	$serviceType = new ServiceType();
	$serviceType->setName('Conta');
	$em->persist($serviceType);

	$humanType = new HumanType();
	$humanType->setName('Funcionário');
	$em->persist($humanType);

	try {
		$em->flush();
	} catch (Exception $e) {
		echo $e->getMessage();
	}
}
