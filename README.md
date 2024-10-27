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

## API Endpoints (Backend)

| Method | Endpoint          | Description           |
| ------ | ----------------- | --------------------- |
| GET    | `/api/books`      | List all books        |
| POST   | `/api/books`      | Add a new book        |
| GET    | `/api/books/{id}` | Get details of a book |
| PUT    | `/api/books/{id}` | Update a book         |
| DELETE | `/api/books/{id}` | Delete a book         |

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
