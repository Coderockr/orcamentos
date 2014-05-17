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

		return $app['twig']->render('company/edit.twig', 
			array(
				'company' => $company,
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
		$companyService->setEm($app['orm.em']);
		$company = $companyService->save($data, $logotype);
		$app['session']->set('companyLogotype', $company->getLogotype());
		$app['session']->set('companyName', $company->getName());
		return $app->redirect('/company');
	}
}