<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login', methods:['GET','POST'])]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();

    $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('pages/login/index.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }

    #[route('/deconnexion', name: 'app_logout')]
    public function logout(): never 
    {
        throw new Exception('don\'t forget to activate logout in security.yaml');

    } 
    #[Route('/inscription', name: 'app_registration', methods:['GET','POST'])]
    public function registration(Request $request, EntityManagerInterface $manager):response
    {
        $user = new User();
        $user->setRoles(['ROLE_USER']);
        $form = $this->createForm(RegistrationType::class,$user);

        $form->handleRequest($request); 
        if ($form->isSubmitted() && $form->isValid()) {
            $user=$form->getData();

            $this->addFlash(
                'success',
                'Votre compte a bien été créé .'
            );
            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('app_login');
        }
        
        return $this->render('pages/login/registration.html.twig',[
            'form' => $form->createView()
        ]);
    } 

    }
