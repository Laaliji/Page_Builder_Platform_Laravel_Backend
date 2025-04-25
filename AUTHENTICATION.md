# Authentication System Documentation

This document provides details on the authentication system used in the Page Builder Platform, covering both traditional email/password authentication and GitHub OAuth integration.

## Setup Instructions

### 1. Environment Configuration

Ensure your `.env` file contains the following variables for GitHub OAuth:

```
GITHUB_CLIENT_ID=your_github_client_id
GITHUB_CLIENT_SECRET=your_github_client_secret
GITHUB_CALLBACK_REDIRECTS=https://your-app.com/api/auth/github/callback
```

### 2. Database Migration

The users table includes fields for both traditional and OAuth authentication:

- Standard authentication fields: `email`, `password`, etc.
- OAuth fields: `auth_provider`, `auth_provider_id`
- GitHub-specific fields: `github_id`, `github_token`, `github_refresh_token`, `is_github_connected`

Run migrations to ensure your database is up to date:

```bash
php artisan migrate
```

## Authentication Endpoints

### Traditional Authentication

1. **Registration**:
   - `POST /api/auth/register`
   - Requires: `firstname`, `lastname`, `username`, `email`, `password`, `password_confirmation`
   - Creates a new user with email/password

2. **Login**:
   - `POST /api/auth/login`
   - Requires: `email`, `password`
   - Returns: User data and API token

3. **Logout**:
   - `POST /api/auth/logout`
   - Requires: Authentication token
   - Revokes the current access token

4. **Update Password**:
   - `POST /api/auth/update-password`
   - Requires: `current_password` (if traditional user), `password`, `password_confirmation`
   - Special case: GitHub-only users can set a password by including `is_github_user=true`

### GitHub OAuth Authentication

1. **Direct GitHub Login/Registration**:
   - Redirect user to: `GET /api/auth/github/redirect`
   - Callback: `GET /api/auth/github/callback-direct`
   - Creates a new account or links to existing one based on GitHub email
   - Returns: User data and API token

2. **Link GitHub to Existing Account**:
   - Redirect user to: `GET /api/auth/github/link`
   - Callback: `GET /api/auth/github/callback`
   - Requires: User to be already authenticated
   - Links GitHub account to the authenticated user

3. **Unlink GitHub Account**:
   - `DELETE /api/auth/github/unlink`
   - Removes GitHub credentials from user account
   - Will fail if user has no password (GitHub-only authentication)

4. **Check GitHub Connection Status**:
   - `GET /api/auth/github/status`
   - Returns: Status of GitHub connection and GitHub username if connected

## Security Considerations

1. **Password Storage**: 
   - Passwords are hashed using Laravel's secure hashing mechanism
   - GitHub tokens are stored but are hidden from JSON responses

2. **Token Management**:
   - Uses Laravel Sanctum for API token authentication
   - Tokens can be revoked via logout

3. **OAuth Security**:
   - GitHub tokens are stored securely
   - Uses stateless OAuth for better security
   - Validates that a GitHub account isn't linked to multiple users

## Implementation Details

1. **User Model**:
   - `User.php` handles both authentication methods
   - Includes methods for creating and linking GitHub accounts

2. **Authentication Controller**:
   - Handles registration, login, and OAuth callbacks
   - Includes error handling and validation

3. **Routes**:
   - Public routes for initial authentication
   - Protected routes for account management
   - Separate routes for linking vs. direct authentication

## Special Scenarios

1. **GitHub Account Already Linked**:
   - System prevents linking a GitHub account to multiple users
   - Returns a 409 Conflict error if attempted

2. **Email Already Exists**:
   - If a GitHub user's email matches an existing account, it can be linked automatically

3. **Unlinking Without Password**:
   - Users who only authenticate via GitHub cannot unlink without setting a password
   - Use the update-password endpoint with `is_github_user=true` to set a password 