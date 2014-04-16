<?php

namespace Orcamentos\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Orcamentos\Service\Share as ShareService;

use Orcamentos\Model\View as ViewModel;

class ShareController
{
	public function detail(Request $request, Application $app, $shareId)
	{	
		if ( !isset($shareId) ) {
			throw new Exception("Parâmetros inválidos", 1);
		}
		
		$share = $app['orm.em']->getRepository('Orcamentos\Model\Share')->find($shareId);
		
		$view = new ViewModel();
		$view->setShare($share);

		$app['orm.em']->persist($view);
		$app['orm.em']->flush();

		$quote = $share->getQuote();

		$resourceCollection = $quote->getResourceQuoteCollection();
		
		$shareCollection = $quote->getShareCollection();

		return $app['twig']->render('share/detail.twig',
			array(
				'quote' => $quote,
				'shareCollection' => $shareCollection,
				'resourceCollection' => $resourceCollection
			)
		);
	}

	public function create(Request $request, Application $app)
	{
		$data = $request->request->all();
		$data['companyId'] = $app['session']->get('companyId');

		if ( count($data['email']) > 0 ){
	    	$data = json_encode($data);
			$shareService = new ShareService();
			$emails = $shareService->save($data, $app['orm.em']);
		}    	

		return json_encode($emails);
	}
}