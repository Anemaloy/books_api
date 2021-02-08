<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\BookTranslation;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\ORM\Query\Expr\Join;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;

class BookController extends AbstractController
{
    /**
     * @Route("/book/", name="books_list", methods={"GET"})
     *
     * @param Request $request
     * @param BookRepository $bookRepository
     *
     * @return JsonResponse
     */
    public function index(Request $request, BookRepository $bookRepository) :JsonResponse
    {
        $count = (int)$request->get('count', 10);

        return $this->response($bookRepository->findBy([], null, $count));
    }

    /**
     * @Route("/{_locale}/book/{book_id}",
     *     requirements={
     *         "_locale": "en|ru",
     *     }, name="book_get", methods={"GET"})
     *
     * @param Request $request
     * @param Book $bookRepository
     *
     * @return JsonResponse
     */
    public function show(Request $request, BookRepository $bookRepository): JsonResponse
    {
        return $this->response($bookRepository->find((int)$request->get('book_id')));
    }

    /**
     * @Route("/book/create", name="book_store", methods={"POST"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {

        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);
        $form->submit($request->request->all());

        if ($form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($book);
            $entityManager->flush();

            return $this->response($book);
        }

        return $this->response([], 422, 'Invalid data');
    }

    /**
     * @Route("/book/search", name="book_search", methods={"GET"})
     *
     * @param Request $request
     * @param BookRepository $bookRepository
     *
     * @return JsonResponse
     */
    public function search(Request $request, BookRepository $bookRepository): JsonResponse
    {
        $results = $bookRepository->createQueryBuilder('t');

        $q = $request->get('query', null);

        if ($q ?? null) {
            $q = mb_strtolower($q);

            $results
                ->leftJoin(BookTranslation::class, 'bt', Join::WITH, 't.id = bt.translatable_id')
                ->andWhere($results->expr()->like('lower(bt.name)', ':name'))
                ->setParameter('name', "%{$q}%")
                ->setMaxResults(100)
                ->orderBy('t.id', 'DESC')
            ;
        }

        return $this->json($results->getQuery()->getResult());
    }

}
