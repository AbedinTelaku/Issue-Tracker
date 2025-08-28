# Issue Tracker - Laravel Technical Task

A comprehensive Issue Tracker application built with Laravel for managing projects, issues, tags, and comments.

## Features

### Core Functionality
- **Projects**: Create, read, update, delete projects with start dates and deadlines
- **Issues**: Full CRUD operations with status tracking, priority levels, and due dates
- **Tags**: Color-coded tags with many-to-many relationships to issues
- **Comments**: Add comments to issues with author tracking

### Advanced Features
- **AJAX Operations**: Attach/detach tags and add comments without page reload
- **Filtering**: Filter issues by status, priority, and tags
- **Search**: Text search across issue titles and descriptions with debounce
- **Responsive UI**: Bootstrap-based responsive design
- **Data Relationships**: Proper Eloquent relationships with eager loading
- **Form Validation**: Server-side validation using Form Request classes

## Database Schema

### Tables
- `projects` - Project information with start_date and deadline columns
- `issues` - Issues belonging to projects with status, priority, and due dates
- `tags` - Tags with unique names and optional colors
- `comments` - Comments belonging to issues with author information
- `issue_tag` - Pivot table for many-to-many relationship between issues and tags

### Relationships
- Project has many Issues (1:N)
- Issue belongs to Project (N:1)
- Issue has many Comments (1:N)
- Issue belongs to many Tags (N:M)
- Tag belongs to many Issues (N:M)
- Comment belongs to Issue (N:1)

## Installation & Setup

### Prerequisites
- PHP 8.1 or higher
- Composer
- Node.js & NPM
- MySQL database
- XAMPP (for local development)

### Step 1: Database Setup
1. Start XAMPP and ensure MySQL is running
2. Open phpMyAdmin (http://localhost/phpmyadmin)
3. Create a new database named `issue_tracker`

### Step 2: Environment Configuration
Create a `.env` file in the `issue-tracker` directory with the following content:

```env
APP_NAME="Issue Tracker"
APP_ENV=local
APP_KEY=base64:YourAppKeyHere
APP_DEBUG=true
APP_URL=http://localhost

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=issue_tracker
DB_USERNAME=root
DB_PASSWORD=

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

VITE_APP_NAME="${APP_NAME}"
```

### Step 3: Install Dependencies & Setup
Run these commands in the `issue-tracker` directory:

```bash
# Install PHP dependencies
composer install

# Generate application key
php artisan key:generate

# Run database migrations
php artisan migrate

# Seed the database with sample data
php artisan db:seed

# Install and build frontend assets
npm install
npm run build
```

### Step 4: Start the Application
```bash
# Start the Laravel development server
php artisan serve
```

The application will be available at http://localhost:8000

## Usage Guide

### Projects
- Navigate to Projects section to view all projects
- Create new projects with name, description, start date, and deadline
- View project details to see associated issues and statistics
- Edit or delete existing projects

### Issues
- Access Issues section to view all issues with filtering options
- Filter by status (Open, In Progress, Closed), priority (Low, Medium, High), or tags
- Search issues by title or description with real-time debounce
- Create new issues and assign them to projects
- Add tags and set due dates

### Tags
- Manage tags in the Tags section
- Create color-coded tags with unique names
- View tag usage statistics
- Edit tag colors and names

### Issue Management
- View issue details with project information, tags, and comments
- Attach/detach tags using AJAX (no page reload)
- Add comments with author name and message
- Comments load with pagination via AJAX

## Technical Implementation

### Architecture
- **MVC Pattern**: Controllers handle business logic, Models define relationships, Views present data
- **Resource Controllers**: RESTful controllers for CRUD operations
- **Form Requests**: Centralized validation logic
- **Eloquent ORM**: Database relationships and query optimization
- **AJAX Integration**: Seamless user experience for dynamic operations

### Key Features
- **Eager Loading**: Prevents N+1 query problems
- **Query Scopes**: Reusable query logic in models
- **Validation**: Server-side validation with error handling
- **CSRF Protection**: Built-in security for forms and AJAX requests
- **Responsive Design**: Bootstrap 5 for mobile-friendly interface

### File Structure
```
issue-tracker/
├── app/
│   ├── Http/
│   │   ├── Controllers/     # Resource controllers
│   │   └── Requests/        # Form validation
│   └── Models/              # Eloquent models
├── database/
│   ├── factories/           # Model factories for testing
│   ├── migrations/          # Database schema
│   └── seeders/             # Sample data
├── resources/
│   └── views/               # Blade templates
├── routes/
│   └── web.php              # Application routes
└── public/                  # Public assets
```

## API Endpoints

### RESTful Routes
- `GET /projects` - List projects
- `POST /projects` - Create project
- `GET /projects/{id}` - Show project
- `PUT /projects/{id}` - Update project
- `DELETE /projects/{id}` - Delete project

Similar patterns for issues, tags, and comments.

### AJAX Endpoints
- `POST /issues/{issue}/tags/attach` - Attach tag to issue
- `DELETE /issues/{issue}/tags/detach` - Detach tag from issue
- `GET /issues/{issue}/comments` - Load comments with pagination
- `POST /comments` - Add new comment

## Sample Data

The seeder creates:
- 5 sample projects with realistic names and descriptions
- 10 predefined tags with various colors
- 15-40 issues distributed across projects
- Random comments on issues
- Many-to-many relationships between issues and tags

## Development Notes

### Code Quality
- PSR-4 autoloading
- Proper error handling and validation
- Clean, readable code with comments
- Consistent naming conventions

### Security
- CSRF token protection
- Input validation and sanitization
- SQL injection prevention via Eloquent ORM
- XSS protection in Blade templates

### Performance
- Database indexing on foreign keys
- Eager loading to prevent N+1 queries
- Efficient pagination
- Optimized AJAX requests

## Browser Support
- Modern browsers (Chrome, Firefox, Safari, Edge)
- Responsive design for mobile devices
- Progressive enhancement with JavaScript

## Future Enhancements
- User authentication and authorization
- Email notifications
- File attachments
- Time tracking
- Advanced reporting
- API for mobile applications

---

This Issue Tracker demonstrates modern Laravel development practices with clean code, proper relationships, AJAX integration, and responsive design.