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
     * @param                 array $data
     * @return                Orcamentos\Model\User $user
     */
    public function save($data)
    {
        $data = json_decode($data);

        if (!isset($data->name) || !isset($data->password) || !isset($data->email) || !isset($data->companyId)) {
            throw new Exception("Invalid Parameters", 1);
        }

        $user = $this->getUser($data);

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

        if (!isset($company)) {
            throw new Exception("Empresa não encontrada", 1);
        }
        
        $user->setCompany($company);

        try {
            $this->em->persist($user);
            $this->em->flush();
            return $user;
        } catch (Exception $e) {
          echo $e->getMessage();
        }
    }

    /**
     * Function used to get a already saved or a new User Object
     * @param                 array $data
     * @return                Orcamentos\Model\User $user
     */
    private function getUser($data){

        $user = null;

        $user = $this->em->getRepository("Orcamentos\Model\User")->findOneBy(array('email' => $data->email));

        if ($user){
            throw new Exception("Usuário com este email já cadastrado", 1);
        }

        if ( isset($data->id) ) {
            $user = $this->em->getRepository("Orcamentos\Model\User")->find($data->id);
        }

        if (!$user) {
            $user = new UserModel();
        }

        return $user;
    }
}
