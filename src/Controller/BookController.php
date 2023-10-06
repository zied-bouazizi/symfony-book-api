<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Response;
use App\Service\BookService;
use App\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class BookController extends AbstractController
{
    private $bookService;

    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }

    #[Route('/api/books', name: 'book', methods: ['GET'])]
    public function bookList()
    {
        // Call the getBooks method from the service
        $url = $this->getParameter('app.url');
        $jsonResponse = $this->bookService->getBooks($url);
        return $jsonResponse;
    }

    #[Route('/api/books/{id}', name: 'deleteBook', methods: ['DELETE'])]
    public function deleteBook(Request $request): JsonResponse
    {
        $id = $request->get("id");

        $url = $this->getParameter('app.url') . "/" . $id;

        $jsonResponse = $this->bookService->deleteBook($url);
        return $jsonResponse;
    }
}
