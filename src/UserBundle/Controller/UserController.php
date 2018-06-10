<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends Controller
{
    /**
     * @Route("/users", name="get_users")
     */
    public function indexAction()
    {
        $users = $this->get('user.service')->getUsers();
        return $this->render('@User/User/index.html.twig', array(
            'users' => $users
        ));
    }

    /**
     * @Route("/user/{id}", name="get_user")
     * @param $id
     */
    public function getUserIdAction($id)
    {
        try {
            $user = $this->get('user.service')->getUserById($id);
            return $this->render('@User/User/get_user.html.twig', array(
                'user' => $user
            ));
        } catch (\Exception $e) {
            return $this->render('@User/Default/error.html.twig', array('error' => $e->getMessage()));
        }

    }

    /**
     * @Route("/username/{name}", name="get_user_name")
     * @param $name
     */
    public function getUserNameAction($name)
    {
        try {

            $user = $this->get('user.service')->getUserByName($name);
            return $this->render('@User/User/get_user.html.twig', array(
                'user' => $user
            ));
        } catch (\Exception $e) {
            return $this->render('@User/Default/error.html.twig', array('error' => $e->getMessage()));
        }
    }

    /**
     * @Route("/update/{id}/{name}", name="update_user")
     */
    public function updateAction($id, $name)
    {
        $user = $this->get('user.service')->getUserById($id);
        $user->setUsername($name);
        $this->get('user.service')->updateUser($user);
        return $this->redirectToRoute('get_users');
    }
}
