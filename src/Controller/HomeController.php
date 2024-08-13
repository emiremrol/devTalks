<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Question;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    #[Route('/', 'index_page')]
    public function index(EntityManagerInterface $entityManager, PaginatorInterface $paginator, Request $request): Response
    {
        $categories = $entityManager->getRepository(Category::class)->findBy([], ['name' => 'ASC']);
        $allQuestions = $entityManager->getRepository(Question::class)->findBy([], ['createdAt' => 'DESC']);

        $questions = $paginator->paginate(
            $allQuestions,
            $request->query->getInt('page', 1),
            6
        );

        return $this->render('home/index.html.twig', [
            'categories' => $categories,
            'questions' => $questions,
            'category' => []
        ]);
    }
}