<?php

namespace UserBundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use UserBundle\Entity\Role;

class CreateRolesCommand extends ContainerAwareCommand
{
    private $initialRoles = array(
        array(
            'role' => 'ROLE_USER',
            'name' => 'user'),
        array(
            'role' => 'ROLE_ADMIN',
            'name' => 'admin'
        )

    );

    public function configure()
    {
        $this->setName('roles:create')
            ->setDescription('Create intial users roles');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();

        foreach ($this->initialRoles as $roleItem) {
            $newRole = new Role();
            $newRole->setName($roleItem['name'])
                ->setRole($roleItem['role']);
            $em->persist($newRole);
            $output->writeln(sprintf('Role %s created', $roleItem['role']));
        }
        $em->flush();


    }
}