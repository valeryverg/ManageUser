<?php

namespace UserBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use UserBundle\Entity\Role;
use UserBundle\Entity\User;
use UserBundle\Form\UserType;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="auth")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loginAction(Request $request)
    {
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('get_users');
        }
        $authenticationUtils = $this->get('security.authentication_utils');
        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('@User/Security/login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }

    /**
     * @Route("/logout")
     */
    public function logoutAction()
    {

    }
    /**
     * @Route("/register", name="register")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function registerAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(new UserType(), $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $passwordEncoder = $this->get('security.password_encoder');
            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

            $roleRepository = $this->get('doctrine')
                ->getManager()
                ->getRepository(Role::class);

            $userRole = $roleRepository->findOneBy(array(
                'name' => 'admin'
            ));

            $user->addRole($userRole);
            $doctrine = $this->get('doctrine')->getEntityManager();
            $doctrine->persist($user);
            $doctrine->flush();

            return $this->redirectToRoute('auth');
        }

        return $this->render('@User/Security/register.twig', array(
            'form' => $form->createView()
        ));

    }

}