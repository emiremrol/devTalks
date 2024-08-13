<?php

namespace App\Controller\User;

use App\Entity\Category;
use App\Entity\User;
use App\Form\UserType\RegistrationFormType;
use App\Security\AppAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{

    #[Route('/register', name: 'user_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, Security $security, EntityManagerInterface $entityManager): Response
    {
        if($this->getUser()){
            return $this->redirectToRoute('user_edit');
        }

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        $email = $form->get('email')->getData();

        $existEmail = $entityManager->getRepository(User::class)->findOneBy(["email" => $email]);
        if($existEmail){
            $this->addFlash('error', 'This email already exist');

        }else{
            if ($form->isSubmitted() && $form->isValid()) {


                // encode the plain password
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('password')->getData()
                    )
                );

                $entityManager->persist($user);
                $entityManager->flush();

                return $security->login($user, AppAuthenticator::class, 'main');
            }

        }

        $categories = $entityManager->getRepository(Category::class)->findBy([], ['name' => 'ASC']);

        return $this->render('registration/register.html.twig', [
            'form' => $form,
            'categories' => $categories
        ]);
    }
}
