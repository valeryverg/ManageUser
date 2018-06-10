<?php

namespace UserBundle\Service;

use UserBundle\Entity\User;
use Doctrine\ORM\EntityManager;

class UserService
{
    private $em;

    private $postRepository;

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
        $this->userRepository = $entityManager->getRepository('UserBundle:User');
    }

    public function getUsers()
    {
        return $this->userRepository->findAll();
    }

    public function getUserById($id)
    {
        $user = $this->userRepository->find($id);
        if (!$user) {
            throw new \Exception("User not found");
        }
        return $user;
    }

    public function getUserByName($name)
    {
        $user = $this->userRepository->findOneBy(array('username' => $name));
        if (!$user) {
            throw new \Exception("User not found");
        }
        return $user;
    }

    public function updateUser(User $user)
    {
        $userExists = $this->userRepository->findOneBy(array('username' => $user->getUsername()));
        if ($userExists) {
            throw new \Exception("User already exists");
        }

        $this->userRepository->save($user);
        return $user->getId();
    }
}