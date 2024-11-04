<?php

namespace App\Controller;

use App\Entity\Author;
use App\Repository\AuthorRepository;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AuthorController extends AbstractController
{
    #[Route('/api/authors', name: 'api_authors_list', methods: ['GET'])]
    public function listAuthors(AuthorRepository $authorRepository): JsonResponse
    {
        // Fetching all authors
        $authors = $authorRepository->findAll();

        // Formating data for JSON response
        $data = array_map(function (Author $author) {
            return [
                'id' => $author->getId(),
                'name' => $author->getName(),
                'nationality' => $author->getNationality(),
                'website' => $author->getWebsite(),
            ];
        }, $authors);

        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/api/authors/{id}', name: 'api_author_details', methods: ['GET'])]
    public function getAuthor(int $id, AuthorRepository $authorRepository): JsonResponse
    {
        // Finding the author by ID
        $author = $authorRepository->find($id);

        // If the author is not found, return 404
        if (!$author) {
            return new JsonResponse(['error' => 'Author not found'], Response::HTTP_NOT_FOUND);
        }

        // Formating data for JSON response
        $data = [
            'id' => $author->getId(),
            'name' => $author->getName(),
            'biography' => $author->getBiography(),
            'dateOfBirth' => $author->getDateOfBirth()->format('Y-m-d'),
            'nationality' => $author->getNationality(),
            'website' => $author->getWebsite(),
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/api/authors/{id}/books', name: 'api_author_books', methods: ['GET'])]
    public function getAuthorBooks(int $id, AuthorRepository $authorRepository): JsonResponse
    {
        // Finding the author by ID
        $author = $authorRepository->find($id);

        // If the author is not found, return 404
        if (!$author) {
            return new JsonResponse(['error' => 'Author not found'], Response::HTTP_NOT_FOUND);
        }

        // Retrieving and formating books by the author
        $books = $author->getBooks()->map(function ($book) {
            return [
                'id' => $book->getId(),
                'title' => $book->getTitle(),
                'publicationDate' => $book->getPublicationDate()->format('Y-m-d'),
                'summary' => $book->getSummary(),
            ];
        });

        return new JsonResponse($books->toArray(), Response::HTTP_OK);
    }

        #[Route('/api/authors', name: 'api_add_author', methods: ['POST'])]
        #[IsGranted('ROLE_ADMIN')]
        public function addAuthor(Request $request, EntityManagerInterface $em): JsonResponse
        {
            // Decoding JSON payload
            $data = json_decode($request->getContent(), true);
    
            // Validating required fields
            if (!isset($data['name'], $data['biography'], $data['dateOfBirth'], $data['nationality'])) {
                return new JsonResponse(['error' => 'Missing required fields'], Response::HTTP_BAD_REQUEST);
            }
    
            // Creating new author and setting properties
            $author = new Author();
            $author->setName($data['name'])
                   ->setBiography($data['biography'])
                   ->setDateOfBirth(new \DateTime($data['dateOfBirth']))
                   ->setNationality($data['nationality'])
                   ->setWebsite($data['website'] ?? null);
    
            // Persisting to the database
            $em->persist($author);
            $em->flush();
    
            // Returning response
            return new JsonResponse([
                'message' => 'Author created successfully',
                'id' => $author->getId()
            ], Response::HTTP_CREATED);
        }
    
        #[Route('/api/authors/{id}', name: 'api_update_author', methods: ['PUT'])]
        #[IsGranted('ROLE_ADMIN')]
        public function updateAuthor(int $id, Request $request, AuthorRepository $authorRepository, EntityManagerInterface $em): JsonResponse
        {
            // Finding the author
            $author = $authorRepository->find($id);
    
            // If not found, return error
            if (!$author) {
                return new JsonResponse(['error' => 'Author not found'], Response::HTTP_NOT_FOUND);
            }
    
            // Decoding JSON payload
            $data = json_decode($request->getContent(), true);
    
            // Updating author properties if provided
            if (isset($data['name'])) $author->setName($data['name']);
            if (isset($data['biography'])) $author->setBiography($data['biography']);
            if (isset($data['dateOfBirth'])) $author->setDateOfBirth(new \DateTime($data['dateOfBirth']));
            if (isset($data['nationality'])) $author->setNationality($data['nationality']);
            if (isset($data['website'])) $author->setWebsite($data['website']);
    
            // Persisting changes to the database
            $em->flush();
    
            return new JsonResponse(['message' => 'Author updated successfully'], Response::HTTP_OK);
        }
    
        #[Route('/api/authors/{id}', name: 'api_delete_author', methods: ['DELETE'])]
        #[IsGranted('ROLE_ADMIN')]
        public function deleteAuthor(int $id, AuthorRepository $authorRepository, EntityManagerInterface $em): JsonResponse
        {
            // Finding the author
            $author = $authorRepository->find($id);
    
            // If not found, return error
            if (!$author) {
                return new JsonResponse(['error' => 'Author not found'], Response::HTTP_NOT_FOUND);
            }
    
            // Removing author from database
            $em->remove($author);
            $em->flush();
    
            return new JsonResponse(['message' => 'Author deleted successfully'], Response::HTTP_OK);
        }
}
