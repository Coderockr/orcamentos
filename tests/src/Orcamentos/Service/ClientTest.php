<?php

namespace Orcamentos\Service;

use PHPUnit_Framework_TestCase;
use Orcamentos\Model\Client as ClientModel;
use Orcamentos\Service\Client as ClientService;

class ClientTest extends PHPUnit_Framework_TestCase
{
    public function testListClients()
    {
        $client = new ClientModel();
        $clientService = new ClientService();
        $clientList = $clientService->listClients();
        $this->assertEquals($clientList, 1);
        $this->assertEquals($clientList[0]->name, 'Darth');

    }

}