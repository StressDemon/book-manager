<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class BookController extends AbstractController
{

    #[Route('/api/books', name: 'api_books', methods: ['GET'])]
    public function getBooks(Request $request, BookRepository $bookRepository): JsonResponse
    {
        // Retrieving optional filter parameters from the query string
        $genre = $request->query->get('genre');
        $author = $request->query->get('author');
        $title = $request->query->get('title');
        
        // Building the query using Doctrine QueryBuilder
        $queryBuilder = $bookRepository->createQueryBuilder('b')
            ->select('b', 'a', 'g')
            ->leftJoin('b.authors', 'a')
            ->leftJoin('b.genres', 'g');

        if ($genre) {
            $queryBuilder->andWhere('g.name = :genre')
                         ->setParameter('genre', $genre);
        }

        if ($author) {
            $queryBuilder->andWhere('a.name = :author')
                         ->setParameter('author', $author);
        }

        if ($title) {
            $queryBuilder->andWhere('b.title LIKE :title')
                         ->setParameter('title', '%' . $title . '%');
        }

        // Executing the query
        $books = $queryBuilder->getQuery()->getResult();

        // Serializing and returning the data as JSON
        $data = [];
        foreach ($books as $book) {
            $data[] = [
                'id' => $book->getId(),
                'title' => $book->getTitle(),
                'description' => $book->getDescription(),
                'publicationDate' => $book->getPublicationDate()->format('Y-m-d'),
                'price' => $book->getPrice(),
                'isbn' => $book->getIsbn(),
                'language' => $book->getLanguage(),
                'pageCount' => $book->getPageCount(),
                'publisher' => $book->getPublisher(),
                'format' => $book->getFormat(),
                'ratingAverage' => $book->getRatingAverage(),
                'downloadCount' => $book->getDownloadCount(),
                'readCount' => $book->getReadCount(),
                'authors' => array_map(fn($author) => $author->getName(), $book->getAuthors()->toArray()),
                'genres' => array_map(fn($genre) => $genre->getName(), $book->getGenres()->toArray())
            ];
        }

        return new JsonResponse($data);
    }

    #[Route('/api/books/{id}', name: 'api_book_details', methods: ['GET'])]
    public function getBookById(int $id, BookRepository $bookRepository): JsonResponse
    {
        // Finding the book by ID
        $book = $bookRepository->find($id);

        // If the book is not found, returns a 404 response
        if (!$book) {
            return new JsonResponse(['error' => 'Book not found'], Response::HTTP_NOT_FOUND);
        }

        // Preparing the response data
        $data = [
            'id' => $book->getId(),
            'title' => $book->getTitle(),
            'description' => $book->getDescription(),
            'publicationDate' => $book->getPublicationDate()->format('Y-m-d'),
            'price' => $book->getPrice(),
            'isbn' => $book->getIsbn(),
            'language' => $book->getLanguage(),
            'pageCount' => $book->getPageCount(),
            'publisher' => $book->getPublisher(),
            'format' => $book->getFormat(),
            'ratingAverage' => $book->getRatingAverage(),
            'downloadCount' => $book->getDownloadCount(),
            'readCount' => $book->getReadCount(),
            'authors' => array_map(fn($author) => $author->getName(), $book->getAuthors()->toArray()),
            'genres' => array_map(fn($genre) => $genre->getName(), $book->getGenres()->toArray())
        ];

        return new JsonResponse($data);
    }   

    #[Route('/api/books', name: 'api_book_add', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')] // Only allow access for users with ADMIN role
    public function addBook(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): JsonResponse
    {
        // Decoding the JSON request
        $data = json_decode($request->getContent(), true);

        // Creating a new Book entity and setting its properties
        $book = new Book();
        $book->setTitle($data['title']);
        $book->setDescription($data['description']);
        $book->setPublicationDate(new \DateTime($data['publicationDate']));
        $book->setPrice($data['price']);
        $book->setIsbn($data['isbn']);
        $book->setLanguage($data['language']);
        $book->setPageCount($data['pageCount']);
        $book->setPublisher($data['publisher']);
        $book->setFormat($data['format']);
        $book->setRatingAverage($data['ratingAverage'] ?? null);
        $book->setDownloadCount($data['downloadCount'] ?? 0);
        $book->setReadCount($data['readCount'] ?? 0);

        // Validating the book entity
        $errors = $validator->validate($book);
        if (count($errors) > 0) {
            return new JsonResponse((string) $errors, Response::HTTP_BAD_REQUEST);
        }

        // Persisting the book to the database
        $entityManager->persist($book);
        $entityManager->flush();

        // Returning a response with the newly created book's ID
        return new JsonResponse(['id' => $book->getId()], Response::HTTP_CREATED);
    }

    #[Route('/api/books/{id}', name: 'api_book_update', methods: ['PUT'])]
    #[IsGranted('ROLE_ADMIN')] // Only allow access for users with ADMIN role
    public function updateBook(int $id, Request $request, BookRepository $bookRepository, EntityManagerInterface $entityManager, ValidatorInterface $validator): JsonResponse
    {
        // Finding the book by ID
        $book = $bookRepository->find($id);

        // If the book is not found, return a 404 response
        if (!$book) {
            return new JsonResponse(['error' => 'Book not found'], Response::HTTP_NOT_FOUND);
        }

        // Decoding the JSON request
        $data = json_decode($request->getContent(), true);

        // Updating the book properties
        if (isset($data['title'])) {
            $book->setTitle($data['title']);
        }
        if (isset($data['description'])) {
            $book->setDescription($data['description']);
        }
        if (isset($data['publicationDate'])) {
            $book->setPublicationDate(new \DateTime($data['publicationDate']));
        }
        if (isset($data['price'])) {
            $book->setPrice($data['price']);
        }
        if (isset($data['isbn'])) {
            $book->setIsbn($data['isbn']);
        }
        if (isset($data['language'])) {
            $book->setLanguage($data['language']);
        }
        if (isset($data['pageCount'])) {
            $book->setPageCount($data['pageCount']);
        }
        if (isset($data['publisher'])) {
            $book->setPublisher($data['publisher']);
        }
        if (isset($data['format'])) {
            $book->setFormat($data['format']);
        }
        if (isset($data['ratingAverage'])) {
            $book->setRatingAverage($data['ratingAverage']);
        }
        if (isset($data['downloadCount'])) {
            $book->setDownloadCount($data['downloadCount']);
        }
        if (isset($data['readCount'])) {
            $book->setReadCount($data['readCount']);
        }

        // Validating the updated book entity
        $errors = $validator->validate($book);
        if (count($errors) > 0) {
            return new JsonResponse((string) $errors, Response::HTTP_BAD_REQUEST);
        }

        // Persisting the updated book to the database
        $entityManager->flush();

        // Returning a response indicating the update was successful
        return new JsonResponse(['message' => 'Book updated successfully'], Response::HTTP_OK);
    }

    #[Route('/api/books/{id}', name: 'api_book_delete', methods: ['DELETE'])]
    #[IsGranted('ROLE_ADMIN')] // Only allow access for users with ADMIN role
    public function deleteBook(int $id, BookRepository $bookRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        // Finding the book by ID
        $book = $bookRepository->find($id);

        // If the book is not found, return a 404 response
        if (!$book) {
            return new JsonResponse(['error' => 'Book not found'], Response::HTTP_NOT_FOUND);
        }

        // Removeingthe book from the database
        $entityManager->remove($book);
        $entityManager->flush();

        // Returning a response indicating the deletion was successful
        return new JsonResponse(['message' => 'Book deleted successfully'], Response::HTTP_OK);
    }

    #[Route('/api/books/{id}/authors', name: 'api_book_authors', methods: ['GET'])]
    public function getAuthorsByBookId(int $id, BookRepository $bookRepository): JsonResponse
    {
        // Finding the book by ID
        $book = $bookRepository->find($id);

        // If the book is not found, return a 404 response
        if (!$book) {
            return new JsonResponse(['error' => 'Book not found'], Response::HTTP_NOT_FOUND);
        }

        // Getting authors from the book
        $authors = $book->getAuthors();

        // Formating authors for the response
        $authorData = [];
        foreach ($authors as $author) {
            $authorData[] = [
                'id' => $author->getId(),
                'name' => $author->getName(),
                'biography' => $author->getBiography(),
                // Add any other author fields here.
            ];
        }

        // Return the list of authors
        return new JsonResponse($authorData, Response::HTTP_OK);
    }

    #[Route('/api/books/{id}/genres', name: 'api_book_genres', methods: ['GET'])]
    public function getGenresByBookId(int $id, BookRepository $bookRepository): JsonResponse
    {
        // Finding the book by ID
        $book = $bookRepository->find($id);

        // If the book is not found, return a 404 response
        if (!$book) {
            return new JsonResponse(['error' => 'Book not found'], Response::HTTP_NOT_FOUND);
        }

        // Getting genres from the book
        $genres = $book->getGenres(); 

        // Format genres for the response
        $genreData = [];
        foreach ($genres as $genre) {
            $genreData[] = [
                'id' => $genre->getId(),
                'name' => $genre->getName(),
                // Add any other genre fields here.
            ];
        }

        // Returning the list of genres
        return new JsonResponse($genreData, Response::HTTP_OK);
    }

    #[Route('/api/books/{id}/reviews', name: 'api_book_reviews', methods: ['GET'])]
    public function getReviewsByBookId(int $id, BookRepository $bookRepository): JsonResponse
    {
        // Find the book by ID
        $book = $bookRepository->find($id);

        // If the book is not found, return a 404 response
        if (!$book) {
            return new JsonResponse(['error' => 'Book not found'], Response::HTTP_NOT_FOUND);
        }

        // Getting reviews from the book
        $reviews = $book->getReviews();

        // Formating reviews for the response
        $reviewData = [];
        foreach ($reviews as $review) {
            $reviewData[] = [
                'id' => $review->getId(),
                'rating' => $review->getRating(),
                'comment' => $review->getComment(),
                'createdAt' => $review->getCreatedAt()->format('Y-m-d H:i:s'), // Formatting date
                // Add any other review fields here.
            ];
        }

        // Returning the list of reviews
        return new JsonResponse($reviewData, Response::HTTP_OK);
    }

    // TODO: Implement /api/books/{id}/download
    // I haven't decided on how the download process should be like so I'm delaying the download until I figure out a proper solution

    #[Route('/api/books/search', name: 'api_books_search', methods: ['GET'])]
    public function searchBooks(Request $request, BookRepository $bookRepository): JsonResponse
    {
        // Getting search parameters
        $title = $request->query->get('title');
        $genre = $request->query->get('genre');
        $author = $request->query->get('author');

        // Building a query to fetch books based on the search parameters
        $criteria = [];
        if ($title) {
            $criteria['title'] = $title;
        }
        if ($genre) {
            $criteria['genres'] = $genre; 
        }
        if ($author) {
            $criteria['authors'] = $author;
        }

        // Fetching books based on the criteria
        $books = $bookRepository->findBy($criteria);

        // Formating books for the response
        $bookData = [];
        foreach ($books as $book) {
            $bookData[] = [
                'id' => $book->getId(),
                'title' => $book->getTitle(),
                'description' => $book->getDescription(),
                'publicationDate' => $book->getPublicationDate()->format('Y-m-d'),
                'price' => $book->getPrice(),
                // Add any other book fields here.
            ];
        }

        // Returning the list of books
        return new JsonResponse($bookData, Response::HTTP_OK);
    }

}
