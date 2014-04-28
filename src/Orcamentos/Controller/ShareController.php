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
		
		$shareNotesCollection = array();

		foreach ($shareCollection as $sc) {
			$notes = $sc->getShareNotesCollection();
			foreach ($notes as $note) {
				$shareNotesCollection[] = $note;
			}
		}

		usort($shareNotesCollection, function ($a, $b)
		{
		    if ($a->getCreated() == $b->getCreated()) {
		        return 0;
		    }
		    return ($a->getCreated()  < $b->getCreated() ) ? 1 : -1;
		});

		return $app['twig']->render('share/detail.twig',
			array(
				'share' => $share,
				'resourceCollection' => $resourceCollection,
				'shareNotesCollection' => $shareNotesCollection
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

	public function comment(Request $request, Application $app)
	{
		$data = $request->request->all();

    	$data = json_encode($data);
		$shareService = new ShareService();
		$note = $shareService->comment($data, $app['orm.em']);
		$result = array( 'email' => $note->getShare()->getEmail(), 'comment' => $note->getNote());
		return json_encode($result);
	}

	public function delete(Request $request, Application $app, $shareId)
	{	
		$em = $app['orm.em'];
		$share = $em->getRepository('Orcamentos\Model\Share')->find($shareId);
		$em->remove($share);
		$em->flush();
		return true;
	}
}