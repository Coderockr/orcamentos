<?php

namespace Orcamentos\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Zend\Crypt\Password\Bcrypt;

class ResetPasswordCommand extends Command
{

    private $input;
    private $output;

    protected function configure()
    {
        $this->setName('orcamentos:resetpwd')
            ->setDescription('Reset user password');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;

        $userRepo = $this->getEm()->getRepository('Orcamentos\Model\User');

        $users = $userRepo->findAll();

        if (0 === count($users)) {
            $output->writeln("<error>Your database don't have users!</error>");
            $output->writeln("Try: <info>orcamentos:initialize</info>");
            return;
        }

        $options = array();
        foreach ($users as $user) {
            $options[$user->getId()] = $user->getName() . "<" . $user->getEmail() . ">";
        }

        $helper = $this->getHelper('question');
        $question = new ChoiceQuestion(
            'Please select the user',
            $options
        );
        $user = $helper->ask($input, $output, $question);

        $userId = array_search($user, $options);

        $pwd = '';

        while (strlen($pwd) < 6) {
            $pwd = $this->askForPassword();
            if (strlen($pwd) < 6) {
                $output->writeln("<error>The password must have at least 6 characters</error>");
                continue;
            }
        }

        $pwd = (new Bcrypt)->create($pwd);

        $user = $userRepo->find($userId);
        $user->setPassword($pwd);

        $this->getEm()->flush();

        $output->writeln("<info>The password has been successfully changed</info>");
    }

    private function askForPassword()
    {
        $helper = $this->getHelper('question');
        $question = new Question('What is the new user\'s password: ');
        $question->setHidden(true);
        $question->setHiddenFallback(false);
        return $helper->ask($this->input, $this->output, $question);
    }

    private function getEm()
    {
        return $this->getHelper('em')->getEntityManager();
    }
}
