<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

/**
 * @Route("/author")
 */
class AuthorController extends AbstractController
{
    /**
     * @Route("/", name="authors_list", methods={"GET"})
     *
     * @param Request $request
     * @param AuthorRepository $authorRepository
     *
     * @return JsonResponse
     */
    public function index(Request $request, AuthorRepository $authorRepository): JsonResponse
    {
        $count = (int)$request->get('count', 10);

        return $this->response($authorRepository->findBy([], null, $count));
    }

    /**
     * @Route("/create", name="author_store", methods={"POST"})
     *
     * @param Request $request
     * @param AuthorRepository $authorRepository
     *
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $author = new Author();
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);
        $form->submit($request->request->all());

        if ($form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($author);
            $entityManager->flush();
            return $this->response($author);
        }

        return $this->response([], 422, 'Invalid data');
    }
}