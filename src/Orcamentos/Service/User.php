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
class User extends Service
{
    /**
     * Function that saves a new User
     *
     * @return                Function used to save a new User
     */
    public function save($data)
    {
        $data = json_decode($data);

        if (!isset($data->name) || !isset($data->password) || !isset($data->email) || !isset($data->companyId)) {
            throw new Exception("Invalid Parameters", 1);
        }

        $user = null;
        if ( isset($data->id) ) {
            $user = $this->em->getRepository("Orcamentos\Model\User")->find($data->id);
        }

        if (!$user) {
            $user = new UserModel();
        }

        $user->setName($data->name);
        $user->setEmail($data->email);

        $password = $user->getPassword();

        if( !isset($password) || $password != $data->password ) {
            $bcrypt = new Bcrypt;
            $password = $bcrypt->create($data->password);
        }

        $user->setPassword($password);

        $admin = false;
        if ( isset($data->admin) ){
            $admin = true;
        }

        $user->setAdmin($admin);

        $company = $this->em->getRepository('Orcamentos\Model\Company')->find($data->companyId);

        if (isset($company)) {
            $user->setCompany($company);
        }
        try {
            $this->em->persist($user);
            $this->em->flush();
            return $user;
        } catch (Exception $e) {
          echo $e->getMessage();
        }
    }
}
