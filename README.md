# Blog API

## Installation

To install this project, follow these steps:

1. **Clone the repository:**
    ```bash
    git clone https://github.com/your-username/your-repo.git
    cd your-repo
    ```

2. **Install dependencies:**
    ```bash
    composer install
    npm install
    ```

3. **Create a copy of the `.env` file:**
    ```bash
    cp .env.example .env
    ```

4. **Generate an application key:**
    ```bash
    php artisan key:generate
    ```

5. **Run the migrations:**
    ```bash
    php artisan migrate
    ```

6. **Serve the application:**
    ```bash
    php artisan serve
    ```

7. **Start the queue worker:**
    ```bash
    php artisan queue:work
    ```

8. **Run the tests:**
    ```bash
    php artisan test
    ```

## .env File

You need to create a `.env` file in the root directory of your project. This file should contain all the configuration settings that are present in the `.env.example` file. 

## Notes
- All the endpoints start with /api for example "http://localhost:8000/api/channel".
- apiResource means all the crud operations for example the channel routes: 
  - `GET  http://localhost:8000/api/channel`: Read all the channels
  - `GET  http://localhost:8000/api/channel/235`: Read the channel with id 235
  - `POST  http://localhost:8000/api/channel`: Create a new channel
  - `PATCH  http://localhost:8000/api/channel/235`: Update the channel with id 235
  - `DELETE  http://localhost:8000/api/channel/235`: Delete the channel with id 235


## Routes

### Admin Routes

Defined in [admin.php]:

- `POST /admin/login`: Admin login route. Requires guest middleware and throttling.
- `GET /admin`: Welcome route for authenticated admins.
- `POST /admin/logout`: Admin logout route.
- `apiResource /admin/catagory`: CRUD operations for categories, accessible only to authenticated admins.

### Authentication Routes

Defined in [auth.php]:

- `POST /register`: Register a new user. Requires guest middleware and throttling.
- `POST /login`: User login route. Requires guest middleware and throttling.
- `POST /forgot-password`: Request a password reset link. Requires guest middleware.
- `POST /reset-password`: Reset the password using a valid token. Requires guest middleware.
- `GET /verify-email/{id}/{hash}`: Verify the user's email address. Requires authentication, signed URL, and throttling.
- `POST /email/verification-notification`: Resend the email verification notification. Requires authentication and throttling.
- `POST /logout`: User logout route. Requires authentication.
- `GET /user`: Get the authenticated user's details. Requires authentication.

### Main Routes

Defined in [main.php]:

- **User Dashboard Routes:**
  - `GET /user/dashboard/own-channels`: Retrieve the channels owned by the user. Requires `auth:sanctum` middleware.
  - `GET /user/dashboard/subscribed-channels`: Retrieve the channels the user is subscribed to. Requires `auth:sanctum` middleware.
  - `GET /user/dashboard/blogs`: Retrieve the blogs created by the user. Requires `auth:sanctum` middleware.

- **User Profile Routes:**
  - `GET /user/profile`: Retrieve the user's profile. Requires `auth:user` middleware.
  - `POST /user/profile/image`: Upload a new profile image. Requires `auth:user` middleware.
  - `PUT /user/profile/image/{image}`: Change the user's profile image. Requires `auth:user` middleware.
  - `DELETE /user/profile/image/{image}`: Delete the user's profile image. Requires `auth:user` middleware.

- **Channel Routes:**
  - `apiResource /channel`: CRUD operations for channels require authentication on some endpoints.

- **Channel Subscription Routes:**
  - `POST /channel/{channel}/subscribe`: Subscribe to a channel. Requires authentication, verification, and authorization to subscribe to the channel.
  - `POST /channel/{channel}/unsubscribe`: Unsubscribe from a channel. Requires authentication, verification, and authorization to unsubscribe from the channel.

- **Blog Routes:**
  - `apiResource /channel.blog`: CRUD operations for blogs within a channel. Requires authentication and verification.
  - `PUT /channel/{channel}/blog/{blog}/image/{image}`: Update the image of a specific blog within a channel. Requires authentication and verification.
  - `DELETE /channel/{channel}/blog/{blog}/image/{image}`: Delete the image of a specific blog within a channel. Requires authentication and verification.

- **Filter Routes:**
  - `GET /channel/{channel}/blog/trending`: To get the tending blog

### Fallback Route

Defined in [api.php]:

- `Fallback`: Returns a 404 JSON response if a route is not found.
