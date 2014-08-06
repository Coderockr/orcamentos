<?php

namespace Orcamentos\Service;

use Orcamentos\Test\ApplicationTestCase;
use Orcamentos\Service\Client as ClientService;

class ClientTest extends ApplicationTestCase
{
   
    public function getClientData()
    {
        return array(
        	'name' => 'Apple',
        	'responsable' => 'Steve Jobs',
            'corporateName' => 'Apple Inc',
        	'email' => 'steve@apple.com',
        	'companyId' => 1,
            'telephone' => '(99) 9999-9999'
        );
    }
    
	/**
	 * @expectedException Exception
	 * @expectedExceptionMessage Parâmetros inválidos
	 */
    public function testSaveWithoutData()
    {
        $clientService = new ClientService();
        $data = array();
        $clientService->save(json_encode($data));
    }

    /**
	 * @expectedException Exception
	 * @expectedExceptionMessage Empresa não encontrada
	 */
    public function testSaveWithoutCompany()
    {
        $clientService = new ClientService();
        $clientService->setEm($this->getDefaultEmMock());
        $data = $this->getClientData();
        $data['companyId'] = -1;
        
        $clientService->save(json_encode($data));
    }

    public function testSaveNewClient()
    {
        $clientService = new ClientService();
        $clientService->setEm($this->getDefaultEmMock());
        $data = $this->getClientData();
        
        $saved = $clientService->save(json_encode($data));

        $this->assertEquals('Apple', $saved->getName());
    }

    public function testSaveEditClient()
    {
        $clientService = new ClientService();
        $clientService->setEm($this->getDefaultEmMock());
        
        $saved = $clientService->save(json_encode($this->getClientData()));

        $this->assertEquals('Apple', $saved->getName());

        $data = $this->getClientData();
        $data['name'] = 'Apple Store';
        $data['id'] = 1;
        
        $saved = $clientService->save(json_encode($data));

        $this->assertEquals('Apple Store', $saved->getName());
    }
    
    public function testSaveNewClientWithCompanyProfile()
    {
        $clientService = new ClientService();
        $clientService->setEm($this->getDefaultEmMock());
        
        $data = $this->getClientData();
        $data['cnpj'] = '99.999.999/9999-99';
        
        $saved = $clientService->save(json_encode($data));
        
        $this->assertInternalType('object', $saved);
        $this->assertInstanceOf('Orcamentos\Model\Client', $saved);
    }
}