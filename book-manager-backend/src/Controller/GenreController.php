<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Repository\GenreRepository;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;


class GenreController extends AbstractController
{
    #[Route('/api/genres', name: 'api_genres', methods: ['GET'])]
    public function getAllGenres(GenreRepository $genreRepository): JsonResponse
    {
        // Fetching all genres from the repository
        $genres = $genreRepository->findAll();

        // Preparing data for JSON response
        $genreData = [];
        foreach ($genres as $genre) {
            $genreData[] = [
                'id' => $genre->getId(),
                'name' => $genre->getName(),
            ];
        }

        // Returning the JSON response with the list of genres
        return new JsonResponse($genreData);
    }

    #[Route('/api/genres/{id}', name: 'api_genre_details', methods: ['GET'])]
    public function getGenreById(int $id, GenreRepository $genreRepository): JsonResponse
    {
        // Finding the genre by ID
        $genre = $genreRepository->find($id);

        // If the genre is not found, return a 404 response
        if (!$genre) {
            return new JsonResponse(['error' => 'Genre not found'], Response::HTTP_NOT_FOUND);
        }

        // Preparing data for JSON response
        $genreData = [
            'id' => $genre->getId(),
            'name' => $genre->getName(),
            // Add any other author fields here.
        ];

        // Returning the JSON response with the genre details
        return new JsonResponse($genreData);
    }

    #[Route('/api/genres/{id}/books', name: 'api_genre_books', methods: ['GET'])]
    public function getBooksByGenreId(int $id, GenreRepository $genreRepository): JsonResponse
    {
        // Finding the genre by ID
        $genre = $genreRepository->find($id);

        // If the genre is not found, return a 404 response
        if (!$genre) {
            return new JsonResponse(['error' => 'Genre not found'], Response::HTTP_NOT_FOUND);
        }

        // Retrieving books associated with this genre
        $books = $genre->getBooks();

        // Formating the books for the response
        $bookData = [];
        foreach ($books as $book) {
            $bookData[] = [
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
                'readCount' => $book->getReadCount()
                // Add additional fields here.
            ];
        }

        // Returning the list of books in JSON format
        return new JsonResponse($bookData, Response::HTTP_OK);
    }

    #[Route('/api/genres', name: 'api_genre_add', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function addGenre(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): JsonResponse
    {
        // Decoding JSON data
        $data = json_decode($request->getContent(), true);

        // Creating a new Genre entity and setting properties
        $genre = new Genre();
        $genre->setName($data['name']);

        // Validating the genre entity
        $errors = $validator->validate($genre);
        if (count($errors) > 0) {
            return new JsonResponse((string) $errors, Response::HTTP_BAD_REQUEST);
        }

        // Persisting the genre to the database
        $entityManager->persist($genre);
        $entityManager->flush();

        // Returning the newly created genre's ID
        return new JsonResponse(['id' => $genre->getId()], Response::HTTP_CREATED);
    }

    #[Route('/api/genres/{id}', name: 'api_genre_update', methods: ['PUT'])]
    #[IsGranted('ROLE_ADMIN')]
    public function updateGenre(int $id, Request $request, GenreRepository $genreRepository, EntityManagerInterface $entityManager, ValidatorInterface $validator): JsonResponse
    {
        // Finding the genre by ID
        $genre = $genreRepository->find($id);

        // If genre is not found, return 404
        if (!$genre) {
            return new JsonResponse(['error' => 'Genre not found'], Response::HTTP_NOT_FOUND);
        }

        // Decoding JSON data
        $data = json_decode($request->getContent(), true);

        // Updating the genre's properties if provided
        if (isset($data['name'])) {
            $genre->setName($data['name']);
        }

        // Validating the updated genre entity
        $errors = $validator->validate($genre);
        if (count($errors) > 0) {
            return new JsonResponse((string) $errors, Response::HTTP_BAD_REQUEST);
        }

        // Persisting the updated genre to the database
        $entityManager->flush();

        // Returning a success message
        return new JsonResponse(['message' => 'Genre updated successfully'], Response::HTTP_OK);
    }

    #[Route('/api/genres/{id}', name: 'api_genre_delete', methods: ['DELETE'])]
    #[IsGranted('ROLE_ADMIN')]
    public function deleteGenre(int $id, GenreRepository $genreRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        // Finding the genre by ID
        $genre = $genreRepository->find($id);

        // If genre is not found, return 404
        if (!$genre) {
            return new JsonResponse(['error' => 'Genre not found'], Response::HTTP_NOT_FOUND);
        }

        // Removing the genre from the database
        $entityManager->remove($genre);
        $entityManager->flush();

        // Returning a success message
        return new JsonResponse(['message' => 'Genre deleted successfully'], Response::HTTP_OK);
    }
}
