<?php

namespace Orcamentos\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Input\ArrayInput;

use Zend\Crypt\Password\Bcrypt;

use Orcamentos\Model\EquipmentType;
use Orcamentos\Model\ServiceType;
use Orcamentos\Model\HumanType;
use Orcamentos\Model\Plan;
use Orcamentos\Model\Company;
use Orcamentos\Model\User;

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
        try {
            $command = $this->getApplication()->find('orm:schema-tool:create');
            $returnCode = $command->run($input, $output);
        } catch (\Exception $e) {
            $output->writeln("<error>Your database alread have the schema!</error>");
        }


        $this->input = $input;
        $this->output = $output;

        $em = $this->getEm();

        $userRepo = $this->getEm()->getRepository('Orcamentos\Model\User');

        $users = $userRepo->findAll();

        if (count($users)) {
            $output->writeln("<error>Your database alread have users!</error>");
            return;
        }

        // Inserting types
        $equipmentType = new EquipmentType();
        $equipmentType->setName('Computador');
        $em->persist($equipmentType);

        $serviceType = new ServiceType();
        $serviceType->setName('Conta');
        $em->persist($serviceType);

        $humanType = new HumanType();
        $humanType->setName('Funcionário');
        $em->persist($humanType);

        // Company Questions
        $output->writeln("<info>Company setup:</info>");

        $companyName = $this->askFor('What is your company name', 'Demo');
        $companyTelephone = $this->askFor('Company phone number', '99 9999999');
        $companyEmail = $this->askFor('Company e-mail address', 'admin@orcamentos.com');

        $company = new Company();
        $company->setName($companyName);
        $company->setTelephone($companyTelephone);
        $company->setEmail($companyEmail);
        $company->setTaxes(6);

        $em->persist($company);

        // User questions
        $output->writeln("\n<info>User setup</info>");
        $userName = $this->askFor('Full name of the user', 'Administrator');
        $userEmail = $this->askFor('User\'s e-mail address', $companyEmail);
        $userPassword = $this->askForPassword();
        $userAdmin = true;
        $userCompany = $company->getId();

        $user = new User();
        $user->setName($userName);
        $user->setEmail($userEmail);
        $user->setPassword((new Bcrypt)->create($userPassword));
        $user->setAdmin(true);
        $user->setCompany($company);

        $em->persist($user);

        // Plan questions
        $output->writeln("\n<info>Plan setup:</info>");
        $planName = $this->askFor('Enter a name for the default plan', 'Default');
        $planPrice = 0 + $this->askFor('Enter the price for this plan', '0');

        $plan = new Plan();
        $plan->setName($planName);
        $plan->setPrice($planPrice);
        $plan->setQuoteLimit(null);

        $em->persist($plan);

        $company->setPlan($plan);

        try {
            $em->flush();
            $output->writeln("<info>Initial setup complete!<info>");
        } catch (\Exception $e) {
            $output->writeln("<error>Failed to initialize the database</error>");
        }

    }

    private function createQuestion($question, $default)
    {
        $helper = $this->getHelper('question');
        $q = "<question>$question</question> ";
        if (!is_null($default)) {
            $q .= "({$default}): ";
        } else {
            $q .= ': ';
        }

        return new Question($q);
    }

    private function getValue(Question $question, $default = null)
    {
        $value = $this->getHelper('question')->ask($this->input, $this->output, $question);

        if (empty($value)) {
            $value = $default;
        }

        return $value;
    }

    private function askForPassword($default = '123456')
    {
        $question = $this->createQuestion('What is the user password', '123456');
        $question->setHidden(true);
        $question->setHiddenFallback(false);
        return $this->getValue($question, $default);
    }

    private function askFor($question, $default = null)
    {
        $question = $this->createQuestion($question, $default);
        return $this->getValue($question, $default);
    }

    private function getEm()
    {
        return $this->getHelper('em')->getEntityManager();
    }
}
