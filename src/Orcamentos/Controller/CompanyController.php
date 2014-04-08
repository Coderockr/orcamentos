<?php

namespace Orcamentos\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CompanyController
{
	public function index(Request $request, Application $app)
	{
		$company = $app['orm.em']->getRepository('Orcamentos\Model\Company')->find($app['session']->get("companyId"));

		return $app['twig']->render('company/index.twig', 
			array(
				'active_page' => '',
				'company' => $company
			)
		);
	}

	public function edit(Request $request, Application $app)
	{
		$company = $app['orm.em']->getRepository('Orcamentos\Model\Company')->find($app['session']->get("companyId"));
		$equipmentTypes = $app['orm.em']->getRepository('Orcamentos\Model\EquipmentType')->findAll();
		$serviceTypes = $app['orm.em']->getRepository('Orcamentos\Model\ServiceType')->findAll();
		$humanTypes = $app['orm.em']->getRepository('Orcamentos\Model\HumanType')->findAll();
		
		$resources = $company->getResourceCollection();
		$equipmentResources = array();
		$serviceResources = array();
		$humanResources = array();

		foreach ($resources as $resource) {
			$type = $resource->getType();
			switch (get_class($type)) {

			 	case 'Orcamentos\Model\EquipmentType':
			 		$equipmentResources[] = $resource;
			 		break;

			 	case 'Orcamentos\Model\ServiceType':
			 		$serviceResources[] = $resource;
			 		break;

			 	case 'Orcamentos\Model\HumanType':
			 		$humanResources[] = $resource;
			 		break;
			 };
		}

		return $app['twig']->render('company/edit.twig', 
			array(
				'active_page' => '',
				'company' => $company,
				'equipmentResources' => $equipmentResources,
				'serviceResources' => $serviceResources,
				'humanResources' => $humanResources,
				'serviceTypes' => $serviceTypes,
				'humanTypes' => $humanTypes,
				'equipmentTypes' => $equipmentTypes
			)
		);
	}
}