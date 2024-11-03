# Book Manager

**Book Manager** is a full-stack web application for managing a collection of books. The backend is powered by Symfony and provides a RESTful API for data management, while the frontend is a modern React application built with Vite for an optimized user experience.

## Project Overview

- **Backend**: A Symfony API that interacts with a MySQL database to manage books, genres, authors, and reading statuses.
- **Frontend**: A React + Vite application that serves as the user interface for managing book data.

## Features

- CRUD (Create, Read, Update, Delete) operations for book management
- Organize books by genres, authors, and reading status
- RESTful API for seamless frontend-backend integration
- Responsive and intuitive user interface with React

## Prerequisites

- **Backend**:

  - PHP 8.x
  - Composer
  - Symfony CLI
  - MySQL (via XAMPP or a local server)

- **Frontend**:
  - Node.js
  - npm or yarn

---

## Getting Started

### 1. Clone the Repository

```bash
git clone https://github.com/StressDemon/book-manager.git
cd book-manager
```

### 2. Backend Setup (Symfony)

1. **Navigate to the Backend Directory**:

   ```bash
   cd book-manager-backend
   ```

2. **Install PHP Dependencies**:

   ```bash
   composer install
   ```

3. **Configure the Environment Variables**:

   - Rename `.env.example` to `.env`.
   - Update the `DATABASE_URL` with your MySQL credentials:
     ```plaintext
     DATABASE_URL="mysql://root:@127.0.0.1:3306/book_manager"
     ```

4. **Create and Migrate Database**:

   ```bash
   php bin/console doctrine:database:create
   php bin/console doctrine:migrations:migrate
   ```

5. **Start the Symfony Server**:

   ```bash
   symfony serve
   ```

   The backend API will be running on `http://localhost:8000`.

### 3. Frontend Setup (React + Vite)

1. **Navigate to the Frontend Directory**:

   ```bash
   cd ../book-manager-frontend
   ```

2. **Install Node.js Dependencies**:

   ```bash
   npm install
   ```

3. **Start the Development Server**:

   ```bash
   npm run dev
   ```

   The frontend application will be running on `http://localhost:5173`.

4. **Configure the API URL (if needed)**:
   - In your frontend code, ensure that API requests are pointing to the backend URL (e.g., `http://localhost:8000`).

---

## API Entities (Backend)

---
| Entity Name | Attributes                                                                                  | Relationships                                                                                         |
|-------------|---------------------------------------------------------------------------------------------|-------------------------------------------------------------------------------------------------------|
| **Book**    | - `id`: int (Primary Key, Auto-Increment) <br> - `title`: string (255) <br> - `description`: text <br> - `publicationDate`: date <br> - `coverImage`: string (nullable) <br> - `price`: decimal (precision: 10, scale: 2, default: 0) <br> - `isbn`: string (13, unique) <br> - `language`: string (100) <br> - `pageCount`: int <br> - `publisher`: string (100) <br> - `format`: string <br> - `ratingAverage`: decimal (precision: 2, scale: 1, nullable) <br> - `downloadCount`: int (default: 0) <br> - `readCount`: int (default: 0) | - **authors**: ManyToMany with `Author` <br> - **awards**: ManyToMany with `Award` <br> - **genres**: ManyToMany with `Genre` <br> - **reviews**: OneToMany with `Review` |
| **Author**  | - `id`: int (Primary Key, Auto-Increment) <br> - `name`: string (100) <br> - `biography`: text <br> - `dateOfBirth`: date <br> - `nationality`: string (100) <br> - `website`: string (nullable) | - **books**: ManyToMany with `Book`                                                                 |
| **Award**   | - `id`: int (Primary Key, Auto-Increment) <br> - `name`: string (255) <br> - `description`: text <br> - `year`: int         | - **books**: ManyToMany with `Book`                                                                 |
| **User**    | - `id`: int (Primary Key, Auto-Increment) <br> - `username`: string (100, unique) <br> - `email`: string (unique) <br> - `password`: string <br> - `dateJoined`: datetime <br> - `isAdmin`: boolean (default: false) <br> - `role`: `UserRole` (enum) | - **favorites**: ManyToMany with `Book`                                                             |
| **Review**  | - `id`: int (Primary Key, Auto-Increment) <br> - `rating`: int <br> - `comment`: text <br> - `createdAt`: datetime         | - **user**: ManyToOne with `User` <br> - **book**: ManyToOne with `Book`                             |
| **Genre**   | - `id`: int (Primary Key, Auto-Increment) <br> - `name`: string (100)                                                           | - **books**: ManyToMany with `Book`                                                                 |
| **UserRole Enum** | - `READER`: "reader" <br> - `AUTHOR`: "author" <br> - `ADMIN`: "admin"                                                 |                                                                                                       |
## API Endpoints (Backend)

| **Endpoint**                | **Method** | **Description**                                                | **Authentication Required** |
|-----------------------------|------------|----------------------------------------------------------------|-----------------------------|
| **Books**                   |            |                                                                |                             |
| `/api/books`                | GET        | Get a list of all books (with filters for genre, author, etc.) | No                          |
| `/api/books/{id}`           | GET        | Get details of a specific book by ID                           | No                          |
| `/api/books`                | POST       | Add a new book                                                 | Yes (Admin)                 |
| `/api/books/{id}`           | PUT        | Update details of a specific book                              | Yes (Admin)                 |
| `/api/books/{id}`           | DELETE     | Delete a specific book                                         | Yes (Admin)                 |
| `/api/books/{id}/authors`   | GET        | Get all authors for a specific book                            | No                          |
| `/api/books/{id}/genres`    | GET        | Get all genres for a specific book                             | No                          |
| `/api/books/{id}/reviews`   | GET        | Get all reviews for a specific book                            | No                          |
| `/api/books/{id}/download`  | POST       | Download a book (if allowed)                                   | Yes                         |
| `/api/books/search`         | GET        | Search for books by title, genre, author, etc.                 | No                          |
| **Genres**                  |            |                                                                |                             |
| `/api/genres`               | GET        | Get a list of all genres                                       | No                          |
| `/api/genres/{id}`          | GET        | Get details of a specific genre                                | No                          |
| `/api/genres/{id}/books`    | GET        | Get all books in a specific genre                              | No                          |
| `/api/genres`               | POST       | Add a new genre                                                | Yes (Admin)                 |
| `/api/genres/{id}`          | PUT        | Update a specific genre                                        | Yes (Admin)                 |
| `/api/genres/{id}`          | DELETE     | Delete a specific genre                                        | Yes (Admin)                 |
| **Authors**                 |            |                                                                |                             |
| `/api/authors`              | GET        | Get a list of all authors                                      | No                          |
| `/api/authors/{id}`         | GET        | Get details of a specific author                               | No                          |
| `/api/authors/{id}/books`   | GET        | Get all books by a specific author                             | No                          |
| `/api/authors`              | POST       | Add a new author                                               | Yes (Admin)                 |
| `/api/authors/{id}`         | PUT        | Update details of a specific author                            | Yes (Admin)                 |
| `/api/authors/{id}`         | DELETE     | Delete a specific author                                       | Yes (Admin)                 |
| **Users**                   |            |                                                                |                             |
| `/api/users`                | GET        | Get a list of all users                                        | Yes (Admin)                 |
| `/api/users/{id}`           | GET        | Get details of a specific user                                 | Yes (Admin or Self)         |
| `/api/users`                | POST       | Register a new user                                            | No                          |
| `/api/users/{id}`           | PUT        | Update user profile                                            | Yes (Self)                  |
| `/api/users/{id}`           | DELETE     | Delete a user account                                          | Yes (Admin or Self)         |
| `/api/users/{id}/favorites` | GET        | Get a list of a user’s favorite books                          | Yes                         |
| `/api/users/{id}/favorites` | POST       | Add a book to user’s favorites                                 | Yes                         |
| `/api/users/{id}/favorites/{bookId}` | DELETE | Remove a book from user’s favorites             | Yes                         |
| `/api/users/{id}/bookmarks` | GET        | Get a list of a user’s bookmarks                               | Yes                         |
| `/api/users/{id}/highlights`| GET        | Get a list of a user’s highlights                              | Yes                         |
| **Bookmarks**               |            |                                                                |                             |
| `/api/bookmarks`            | POST       | Add a new bookmark to a book                                   | Yes                         |
| `/api/bookmarks/{id}`       | PUT        | Update a bookmark (e.g., change page number)                   | Yes                         |
| `/api/bookmarks/{id}`       | DELETE     | Delete a bookmark                                              | Yes                         |
| **Highlights**              |            |                                                                |                             |
| `/api/highlights`           | POST       | Add a new highlight in a book                                  | Yes                         |
| `/api/highlights/{id}`      | PUT        | Update a highlight                                             | Yes                         |
| `/api/highlights/{id}`      | DELETE     | Delete a highlight                                             | Yes                         |
| **Reviews**                 |            |                                                                |                             |
| `/api/books/{id}/reviews`   | POST       | Add a new review for a book                                    | Yes                         |
| `/api/reviews/{id}`         | PUT        | Update a review                                                | Yes (Author of Review)      |
| `/api/reviews/{id}`         | DELETE     | Delete a review                                                | Yes (Author of Review)      |
| `/api/reviews/{id}/like`    | POST       | Like a review                                                  | Yes                         |
| **Admin**                   |            |                                                                |                             |
| `/api/admin/dashboard`      | GET        | Get admin statistics and analytics                             | Yes (Admin)                 |
| `/api/admin/books/reports`  | GET        | Get report of books (by downloads, read count, etc.)           | Yes (Admin)                 |
| `/api/admin/users/reports`  | GET        | Get report of users (activity, new registrations, etc.)        | Yes (Admin)                 |
| **Tags**                    |            |                                                                |                             |
| `/api/tags`                 | GET        | Get a list of all tags                                         | No                          |
| `/api/tags/{id}`            | GET        | Get details of a specific tag                                  | No                          |
| `/api/tags`                 | POST       | Add a new tag                                                  | Yes (Admin)                 |
| `/api/tags/{id}`            | PUT        | Update details of a specific tag                               | Yes (Admin)                 |
| `/api/tags/{id}`            | DELETE     | Delete a specific tag                                          | Yes (Admin)                 |


### Testing the API

You can test the API endpoints using tools like Postman or `curl`. Ensure the backend server is running on `http://localhost:8000`.

---

## Running the Application

With both the frontend and backend servers running, you can access the full application:

- **Frontend**: Open [http://localhost:5173](http://localhost:5173) to use the application’s interface.
- **Backend**: The backend API is accessible at [http://localhost:8000](http://localhost:8000) (for API calls).

---

## License

This project is licensed under the MIT License.
