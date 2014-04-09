<?php

namespace Orcamentos\Service;

use Orcamentos\Model\User as UserModel;
use Zend\Crypt\Password\Bcrypt;
use Exception;

/**
 * User Entity
 *
 * @category Orcamentos
 * @package Service
 * @author  Mateus Guerra<mateus@coderockr.com>
 */
class User
{
    /**
     * Function that saves a new User
     *
     * @return                Function used to save a new User
     */
    public static function save($data, $em)
    {
        $data = json_decode($data);

        if (!isset($data->name) || !isset($data->email) || !isset($data->login) || !isset($data->password)) {
            throw new Exception("Invalid Parameters", 1);
        }

        $user = null;

        if ( isset($data->id) ) {
            $user = $em->getRepository("Orcamentos\Model\User")->find($data->id);
        }

        if (!$user) {
            $user = new UserModel();
        }

        if( !isset($user->password) || $data->password != $user->password) {
            $bcrypt = new Bcrypt;
            $user->setPassword($bcrypt->create($data->password));
        }

        $user->setName($data->name);
        $user->setLogin($data->login); 
        $user->setEmail($data->email); 

        $company = $em->getRepository('Orcamentos\Model\Company')->find($app['session']->get('companyId'));
        
        $user->setCompany($company);

        $user->setAdmin(false);
        
        if (isset($data->admin)) {
            $user->setAdmin(true);
        }
      
        $em->persist($user);
        $em->flush();

        return $user;
    }
}
