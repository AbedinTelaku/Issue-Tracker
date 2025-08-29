<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Issue Tracker')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        .tag {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            font-weight: 500;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 0.375rem;
        }
        .priority-high { color: #dc3545; }
        .priority-medium { color: #fd7e14; }
        .priority-low { color: #198754; }
        .status-open { color: #0d6efd; }
        .status-in_progress { color: #fd7e14; }
        .status-closed { color: #198754; }
        .sidebar {
            background-color: #f8f9fa;
            min-height: calc(100vh - 56px);
        }
        
        /* Custom Pagination Styles */
        .pagination {
            gap: 0.25rem;
            margin: 2rem 0;
        }
        
        .pagination .page-link {
            border: 1px solid #dee2e6;
            color: #495057;
            background-color: #fff;
            padding: 0.5rem 0.75rem;
            font-weight: 500;
            border-radius: 0.375rem;
            transition: all 0.2s ease-in-out;
            min-width: 40px;
            text-align: center;
        }
        
        .pagination .page-link:hover {
            background-color: #e9ecef;
            border-color: #adb5bd;
            color: #212529;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .pagination .page-item.active .page-link {
            background-color: #0d6efd;
            border-color: #0d6efd;
            color: #fff;
            box-shadow: 0 2px 4px rgba(13, 110, 253, 0.3);
        }
        
        .pagination .page-item.disabled .page-link {
            color: #6c757d;
            background-color: #f8f9fa;
            border-color: #dee2e6;
            cursor: not-allowed;
        }
        
        .pagination .page-item:first-child .page-link,
        .pagination .page-item:last-child .page-link {
            border-radius: 0.375rem;
        }
        
        /* Style page numbers only */
        .pagination .page-item:not(:first-child):not(:last-child) .page-link {
            font-weight: 600;
            min-width: 45px;
        }
        
        /* Center pagination better */
        .pagination {
            justify-content: center;
            flex-wrap: wrap;
        }
        
        /* Better spacing between page numbers */
        .pagination .page-item:not(:first-child):not(:last-child) {
            margin: 0 2px;
        }
        
        /* Active page styling */
        .pagination .page-item.active .page-link {
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
            border-color: #0a58ca;
            font-weight: 700;
        }
        
        /* Hover effect for page numbers */
        .pagination .page-item:not(:first-child):not(:last-child) .page-link:hover {
            background: linear-gradient(135deg, #e9ecef 0%, #dee2e6 100%);
            border-color: #adb5bd;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        
        /* Pagination Info */
        .pagination-info {
            text-align: center;
            margin: 1rem 0;
            color: #6c757d;
            font-size: 0.9rem;
        }
        
        .pagination-info strong {
            color: #495057;
        }
        
        /* Responsive pagination */
        @media (max-width: 576px) {
            .pagination {
                flex-wrap: wrap;
                justify-content: center;
            }
            
            .pagination .page-link {
                padding: 0.375rem 0.5rem;
                font-size: 0.875rem;
                min-width: 35px;
            }
        }
        
        /* Enhanced pagination animations */
        .pagination .page-link {
            position: relative;
            overflow: hidden;
        }
        
        .pagination .page-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .pagination .page-link:hover::before {
            left: 100%;
        }
        
        /* Pagination container styling */
        .pagination-container {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 1rem;
            padding: 1.5rem;
            margin: 2rem 0;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }
        
        /* Quick navigation styling */
        .quick-nav {
            background: #fff;
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            margin-top: 1rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            border: 1px solid #e9ecef;
        }
        
        .quick-nav a {
            color: #0d6efd;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        
        .quick-nav a:hover {
            color: #0a58ca;
            text-decoration: none;
            transform: translateY(-1px);
        }
    </style>

    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('projects.index') }}">
                <i class="bi bi-bug"></i> Issue Tracker
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('projects.*') ? 'active' : '' }}" 
                           href="{{ route('projects.index') }}">
                            <i class="bi bi-folder"></i> Projects
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('issues.*') ? 'active' : '' }}" 
                           href="{{ route('issues.index') }}">
                            <i class="bi bi-exclamation-triangle"></i> Issues
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('tags.*') ? 'active' : '' }}" 
                           href="{{ route('tags.index') }}">
                            <i class="bi bi-tags"></i> Tags
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <main class="col-12">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        // CSRF token setup for AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
