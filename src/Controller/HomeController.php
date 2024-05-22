<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Question;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    #[Route('/', 'index_page')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $categories = $entityManager->getRepository(Category::class)->findAll();
        $allQuestions = $entityManager->getRepository(Question::class)->findBy([], ['createdAt' => 'DESC']);

        return $this->render('home/index.html.twig', [
            'categories' => $categories,
            'questions' => $allQuestions
        ]);
    }


}