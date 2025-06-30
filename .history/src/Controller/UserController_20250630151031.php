<?php

namespace App\Controller;

use App\Form\UserPasswordType;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController {
/**
* This controller allow to update the name and the pseudo of user
*
* @param User $user
* @param Request $Request
* @param EntityManagerInterface $manager
* @return Response
*/





   #[Route('/utilisateur/edition/{id}', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(UserRepository $userRepository, Request $request, EntityManagerInterface $manager, int $id , UserPasswordHasherInterface $hasher): Response
    {
        $user = $userRepository->findOneBy(['id' => $id]);

        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        if ($this->getUser() !== $user) {
            return $this->redirectToRoute('app_recipe');
        }

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($hasher->isPasswordValid($user, $form->getData()->getPlainPassword())){

            }
            $user = $form->getData();

            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', 'Les informations de votre compte ont bien été modifiées'
        );

            return $this->redirectToRoute('app_recipe');
        }
        else{
            $this->addFlash(
                'warning',
                'Le mot de passe est incorrect'
            );
        }
    
        return $this->render('pages/user/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }


#[Route('/utilisateur/edition-mot-de-passe/{id}' ,name: 'user_edit_password', methods:['GET','POST'])]
    public function editPassword(UserRepository $userRepository, Request $request, EntityManagerInterface $manager, int $id , UserPasswordHasherInterface $hasher): Response
{
    $user =$userRepository->findOneBy(["id"=>$id]);
$this->User = new ArrayCollection();
    $form = $this->createForm(UserPasswordType::class,$user);
    
     $form->handleRequest($request); 
     if($form->isSubmitted() && $form->isValid()){
        if($hasher->isPasswordValid($user,$form->getData()['plainPassword'])){
            $user->setPassword(
                $hasher->hashPassword(
                    $user,
                    $form->getData()['newPassword']
                )
                ); 
                $manager->persist($user);
                $manager->flush();
            $user->setPlainPassword(
                $form->getData()['newPassword']
            );
            $this->addflash(
                'success',
                'Le mot de passe a été modifié'
            );
            return $this->redirectToRoute('app_recipe');
        }else{
            $this->addFlash(
                'warning',
                "Le mot de passe renseigné est incorrect"

            );
        }
     }

    return $this->render ('pages/user/edit_Password.html.twig',[
    'form'=>$form->createView()
    ]);
}
}
