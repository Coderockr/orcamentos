<?php

namespace Orcamentos\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Orcamentos\Service\Company as CompanyService;

class CompanyController
{

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

	public function create(Request $request, Application $app)
	{
		$data = $request->request->all();
		$logotype = $request->files->get('logotype');

		$data['companyId'] = $app['session']->get('companyId');
    	$data = json_encode($data);
		$companyService = new CompanyService();
		$company = $companyService->save($data, $logotype, $app['orm.em']);

		return $app->redirect('/company');
	}
}