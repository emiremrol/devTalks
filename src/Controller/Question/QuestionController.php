<?php

namespace App\Controller\Question;


use App\Entity\Answer;
use App\Entity\Category;
use App\Entity\Question;
use App\Entity\User;
use App\Form\AnswearType\AnswerType;
use App\Form\QuestionType\QuestionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class QuestionController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager,
    )
    {
        $this->entityManager = $entityManager;

    }

    #[Route('question/new', 'new_question')]
    public function newQuestion(
        #[CurrentUser] User $user,
        Request $request
    ): Response
    {
        $question = new Question();
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $question->setUser($user);
            $question->setCreatedAt(new \DateTimeImmutable('now', new \DateTimeZone('Europe/Sofia')));
            $this->entityManager->persist($question);
            $this->entityManager->flush();
            return $this->redirectToRoute('index_page');
        }

        return $this->render('question/new.html.twig', [
            'form' => $form
        ]);
    }


    #[Route('question/{id}', 'show_question')]
    public function showQuestion(#[CurrentUser] User $user = null, Request $request, int $id): Response
    {
        $question = $this->entityManager->getRepository(Question::class)->find($id);
        $answer = new Answer();
        $form = $this->createForm(AnswerType::class, $answer);
        $form->handleRequest($request);



        if($form->isSubmitted() && $form->isValid()){

            if($user === null){
                $this->addFlash('notice', 'You have to log in to add a reply');


                return $this->redirectToRoute('show_question', ['id' => $id]);
            }



            $answer->setUser($user);
            $answer->setQuestion($question);
            $answer->setCreatedAt(new \DateTimeImmutable('now', new \DateTimeZone('Europe/Sofia')));
            $this->entityManager->persist($answer);
            $this->entityManager->flush();

            return $this->redirectToRoute('show_question', ['id' => $id]);
        }

        return $this->render('question/show.html.twig', [
            'question' => $question,
            'answer_form' => $form,
            'answers' => $question->getAnswers()
        ]);
    }


    #[Route('/questions', 'show_questions')]
    public function questionsByCategory(Request $request): Response
    {
        $categories = $this->entityManager->getRepository(Category::class)->findAll();
        $categoryId = $request->query->get('category_id');

        if($categoryId === null){
            return $this->redirectToRoute('index_page');
        }

        $questions = $this->entityManager->getRepository(Question::class)->findBy(['category' => $categoryId]);

        return $this->render('home/index.html.twig', [
            'questions' => $questions,
            'categories' => $categories
        ]);
    }


}