<?php

namespace App\Controller\User;

use App\Entity\User;
use App\Form\UserType\ChangePasswordType;
use App\Form\UserType\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/profile')]

class UserController extends AbstractController
{

    #[Route('')]
    public function profile(): Response
    {
        return $this->redirectToRoute('user_edit');
    }


    #[Route('/edit', 'user_edit')]
    #[IsGranted(new Expression('is_granted("ROLE_ADMIN") or is_granted("ROLE_USER")'))]
    public function edit(
        #[CurrentUser] User $user,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $photoFile = $form->get('userImg')->getData();

            if($photoFile) {
                $originalFilename = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = uniqid().'.'.$photoFile->guessExtension();

                try{
                    $photoFile->move(
                        $this->getParameter('photos'),
                        $newFilename
                    );
                } catch (FileException $ex){

                }

                $user->setUserImg($newFilename);
            }
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash("success", "User updated successfully");

            return $this->redirectToRoute('user_edit');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form
        ]);
    }

    #[Route('/change-password', 'user_change_password')]
    #[IsGranted(new Expression('is_granted("ROLE_ADMIN") or is_granted("ROLE_USER")'))]
    public function changePassword(
        #[CurrentUser] User $user,
        Request $request,
        EntityManagerInterface $entityManager,
        Security $security
    ): Response
    {
        $form = $this->createForm(ChangePasswordType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $entityManager->flush();


            return $security->logout(validateCsrfToken: false) ?? $this->redirectToRoute('index_page');
        }

        return $this->render('user/change_password.html.twig', [
            'form' => $form
        ]);
    }



}