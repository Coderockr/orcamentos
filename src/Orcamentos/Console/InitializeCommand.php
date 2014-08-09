<?php

namespace Orcamentos\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\Console\Question\Question;

use Orcamentos\Service\Company as CompanyService;
use Orcamentos\Service\User as UserService;

use Orcamentos\Model\EquipmentType;
use Orcamentos\Model\ServiceType;
use Orcamentos\Model\HumanType;
use Orcamentos\Model\Plan;

class InitializeCommand extends Command
{

    private $input;
    private $output;

    protected function configure()
    {
        $this
            ->setName('orcamentos:initialize')
            ->setDescription('Initialize the database and insert the initial data');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $command = $this->getApplication()->find('orm:schema-tool:create');
        $returnCode = $command->run($input, $output);

        $this->input = $input;
        $this->output = $output;

        $em = $this->getEm();

        $userRepo = $this->getEm()->getRepository('Orcamentos\Model\User');

        $users = $userRepo->findAll();

        if (count($users)) {
            $output->writeln("<error>Your database alread have users!</error>");
            return;
        }

        $companyData = $userData = array();

        $output->writeln("<info>Company setup:</info>");
        $companyData['name'] = $this->askFor('What is your company name:', 'Demo');
        $companyData['telephone'] = $this->askFor('Company phone number:');
        $companyData['email'] = $this->askFor('Company e-mail address:');
        $companyData['responsable'] = null;
        $company = $this->saveCompany($companyData);

        $output->writeln("\n<info>User setup</info>");
        $userData['name'] = $this->askFor('Full name of the user:');
        $userData['email'] = $this->askFor('User\'s e-mail address:');
        $userData['password'] = $this->askForPassword();
        $userData['admin'] = true;
        $userData['companyId'] = $company->getId();

        $user = $this->saveUser($userData);

        $output->writeln("\n<info>Plan setup:</info>");
        $planName = $this->askFor('Enter a name for the default plan:');
        $planPrice = 1 * $this->askFor('Enter the price for this plan (49.90):');

    	$plan = new Plan();
        $plan->setName($planName);
        $plan->setPrice($planPrice);
        $plan->setQuoteLimit(null);

        $em->persist($plan);

        $company->setPlan($plan);

        $output->writeln("<info>Creating default types: <info>");

        $equipmentType = new EquipmentType();
	    $equipmentType->setName('Computador');
	    $em->persist($equipmentType);
	    $output->writeln("<info>- Equipment<info>");

	    $serviceType = new ServiceType();
	    $serviceType->setName('Conta');
	    $em->persist($serviceType);
	    $output->writeln("<info>- Service<info>");

	    $humanType = new HumanType();
	    $humanType->setName('Funcionário');
	    $em->persist($humanType);
	    $output->writeln("<info>- Human<info>");

        $em->flush();

        $output->writeln("<info>Initial setup complete!<info>");
    }

    private function askForPassword()
    {
        $helper = $this->getHelper('question');
        $question = new Question('<question>What is the user password:</question> ');
        $question->setHidden(true);
        $question->setHiddenFallback(false);
        return $helper->ask($this->input, $this->output, $question);
    }

    private function askFor($question)
    {
        $helper = $this->getHelper('question');
        $question = new Question("<question>$question</question> ");
        return $helper->ask($this->input, $this->output, $question);
    }

    private function saveCompany(array $data)
    {
        $companyData = json_encode($data);
        $companyService = new CompanyService();
        $companyService->setEm($this->getEm());
        return $companyService->save($companyData, null);
    }

    private function saveUser(array $data)
    {
        $userData = json_encode($data);
        $userService = new UserService();
        $userService->setEm($this->getEm());
        return $userService->save($userData);
    }

    private function getEm()
    {
        return $this->getHelper('em')->getEntityManager();
    }
}
