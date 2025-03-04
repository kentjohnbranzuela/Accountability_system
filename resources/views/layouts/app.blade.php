<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warehouse Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        /* Sidebar Styles */
        body {
            display: flex;
        }
        
        .sidebar {
            width: 250px;
            height: 100vh;
            background-image: linear-gradient(indigo, white);
            color: white;
            padding: 20px;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            transition: width 0.3s ease-in-out;
            overflow: hidden;
        }
        .sidebar.collapsed {
            width: 60px;
        }
        .sidebar h2, .sidebar a {
            white-space: nowrap;
            overflow: hidden;
            transition: opacity 0.3s;
        }
        .sidebar.collapsed h2,
        .sidebar.collapsed a {
            opacity: 0;
        }
        .sidebar a {
            display: block;
            color: white;
            padding: 10px;
            text-decoration: none;
        }
        .sidebar a:hover {
            background: #333;
        }
        .content {
            margin-left: 260px;
            padding: 20px;
            width: 100%;
            transition: margin-left 0.3s ease-in-out;
        }
        .content.expanded {
            margin-left: 80px;
        }
        /* Navbar logo styling */
        .navbar-brand img {
      display: block;
    margin: 0 auto 10px;
    width: 120px; /* Adjust size */
    height: 120px; /* Keep it a square */
    border-radius: 50%; /* Makes it circular */
    object-fit: cover; /* Ensures proper aspect ratio */
        }
        .custom-navbar {
    background: linear-gradient(90deg, #25016d,rgb(44, 48, 47)); /* Blue gradient */
    border-bottom: 2px solid #ffffff;
}

    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
    <a class="navbar-brand d-flex align-items-center" href="">
                    <img src="{{ asset('2.jpg') }}" alt="Logo"> <!-- Make sure '2.png' is in the 'public' folder -->
                </a>
                <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link">
                <i class="fas fa-table"></i> Dashboard
            </a>
        </li>
        
        <li class="nav-item">
            <a href="{{ route('accountability.index') }}" class="nav-link">
                <i class="fas fa-search"></i> BRO
            </a>
        </li>
    </ul>
    </div>

    <!-- Content Section -->
    <div class="content" id="content">
        <!-- Navbar -->
        <nav class="navbar navbar-dark custom-navbar">
    <div class="container-fluid">
        <a class="navbar-brand">BLACKLINE</a>

        <!-- Dropdown Menu for Account -->
        <div class="dropdown ms-auto">
            <button class="navbar-toggler" type="button" id="userMenuButton" data-bs-toggle="dropdown">
                <span class="navbar-toggler-icon"></span>
            </button>
            <ul class="dropdown-menu navbar-dark dropdown-menu-end" aria-labelledby="userMenuButton">
                <li><a class="dropdown-item" href="#">Account Information</a></li>
                <li><a class="dropdown-item text-danger" href="{{ route('logout') }}">Log Out</a></li>
            </ul>
        </div>
    </div>
</nav>


        <!-- Success Message -->
        <div class="container mt-4">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @yield('content')  <!-- Page content will be injected here -->
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sidebar hover effect
        const sidebar = document.getElementById('sidebar');
        const content = document.getElementById('content');

        sidebar.addEventListener('mouseenter', () => {
            sidebar.classList.remove('collapsed');
            content.classList.remove('expanded');
        });

        sidebar.addEventListener('mouseleave', () => {
            sidebar.classList.add('collapsed');
            content.classList.add('expanded');
        });
    </script>

</body>
</html>
