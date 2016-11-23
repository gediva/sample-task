<?php

namespace AppBundle\Controller;

use AppBundle\Form\UserType;
use AppBundle\Form\LoginForm;
use AppBundle\Form\AdForm;
use AppBundle\Entity\Users;
use AppBundle\Entity\Ad;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

class DefaultController extends Controller
{
    public function indexAction(Request $request) {
        $form = $this->createForm(LoginForm::class);

        $repository = $this->getDoctrine()->getRepository('AppBundle:Ad');

        return $this->render('ads/index.html.twig', [
            'form' => $form->createView(),
            'ads' => $repository->findBy([], ['postedAt' => 'DESC']),
            'my_ads' => $repository->findBy(['userId' => $this->getUser()], ['postedAt' => 'DESC'])
        ]);
    }

    public function registerAction(Request $request) {
        $user = new Users();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('registerSuccess');
        }

        return $this->render('ads/register.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function loginAction(Request $request) {
        $authenticationUtils = $this->get('security.authentication_utils');

        $error = $authenticationUtils->getLastAuthenticationError();

        if (isset($error)) {
            $form = $this->createForm(LoginForm::class);

            return $this->render('ads/index.html.twig', [
                'form' => $form->createView(),
                'error' => $error,
            ]);
        }

        return $this->redirectToRoute('ads');
    }

    public function registerSuccessAction(Request $request) {
        return $this->render('ads/register-success.html.twig');
    }

    public function createNewAction(Request $request) {
        $ad = new Ad();

        $form = $this->createForm(AdForm::class, $ad);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ad->setUserId($this->getUser());

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($ad);
            $manager->flush();

            return $this->redirectToRoute('ads');
        }

        return $this->render('ads/createNew.html.twig', [
            'form' => $form->createView()
        ]);
    }

}
