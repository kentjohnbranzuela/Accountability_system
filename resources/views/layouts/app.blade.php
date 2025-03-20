<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blackline</title>
    <!-- Loading Overlay -->
<div id="loading-overlay">
    <div class="spinner"></div>
    <p>Loading...</p>
</div>
    <link rel="icon" type="image/jpeg" href="{{ asset('2.jpg') }}">
 <!-- Change filename if needed -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        /* Sidebar Styles */
        body {
            display: flex;
        }


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
            background: #d0bcf6;
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
            background: linear-gradient(90deg, #25016d, rgb(44, 48, 47)); /* Blue gradient */
            border-bottom: 2px solidrgb(255, 255, 255);
        }
        .sticky-navbar-brand {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    background: linear-gradient(to right, #2e0052, #1a1a1a); /* Adjust to match your design */
    z-index: 1000;
    padding: 15px 20px;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1); /* Optional: Adds shadow */
}

        /* Add Borders for Dashboard and BRO LIST */
        .nav-item a {
    background: linear-gradient(135deg,rgba(227, 136, 255, 0.27),rgba(60, 0, 117, 0.39)); /* Smoother blue */
    color: black;
    font-weight: bold;
    padding: 8px 5px;
    border-radius: 8px;
    box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2); /* Subtle shadow */
    transition: all 0.3s ease-in-out;
}
.nav-item {
    margin-bottom: 12px; /* Adds spacing between BRO LIST, TECH LIST, etc. */
}

        #accountabilityMenu {
    display: none;
    overflow: hidden;
    max-height: 0;
    transition: max-height 0.3s ease-in-out;
}
#accountabilityMenu.show {
    display: block;
    max-height: 500px; /* Adjust if needed */
}
.sidebar {
    height: 100vh; /* Full height of the viewport */
    overflow-y: auto; /* Enables vertical scrolling */
    overflow-x: hidden; /* Hides horizontal scrollbar */
    position: fixed; /* Keeps the sidebar fixed */
    top: 0;
    left: 0;
    width: 250px; /* Adjust width as needed */
    background-color: #2c3e50; /* Example background */
    color: white; /* Example text color */
    padding-bottom: 20px; /* Prevents content from getting cut off */
}

/* Customize scrollbar (optional) */
.sidebar::-webkit-scrollbar {
    width: 8px;
}

.sidebar::-webkit-scrollbar-thumb {
    background-color: rgba(255, 255, 255, 0.3);
    border-radius: 4px;
}

.sidebar::-webkit-scrollbar-thumb:hover {
    background-color: rgba(255, 255, 255, 0.5);
}

 /* Full-screen overlay */
 #loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        color: white;
        font-size: 20px;
        z-index: 9999;
    }

    /* Spinner animation */
    .spinner {
        width: 50px;
        height: 50px;
        border: 5px solid rgba(255, 255, 255, 0.3);
        border-top: 5px solid #fff;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    </style>
</head>
<body>
<style>
    .dropdown-item {
    display: flex;
    align-items: center;
    gap: 10px; /* Space between icon and text */
    padding: 10px 15px; /* Adjust padding inside the dropdown */
    font-size: 16px; /* Ensure good readability */
}

.dropdown-item i {
    font-size: 20px; /* Adjust icon size */
}
#techListMenu li, #broListMenu li, #gingoogsListMenu li, #bcGingoogMenu li, #bcCdoMenu li, #turnOverMenu li, #awolMenu li, #resignMenu li {
    padding: 2px 2px; /* Adjust padding around list items */
    margin-bottom: 5px; /* Adds spacing between list items */
}

/* Styling for dropdown links */
#techListMenu li a, #broListMenu li a, #gingoogsListMenu li a, #bcGingoogMenu li a, #bcCdoMenu li a, #turnOverMenu li a, #awolMenu li a, #resignMenu li a {
    display: flex;
    align-items: center;
    gap: 8px; /* Space between icon and text */
    font-size: 16px; /* Ensures readability */
    padding: 3px 15px; /* Adjust padding inside links */
    border-radius: 6px; /* Optional: rounded corners */
    background: #f0f0f0; /* Optional: light background */
    text-decoration: none; /* Removes underline */
}

/* Hover effect for dropdown links */
#techListMenu li a:hover, #broListMenu li a:hover, #gingoogsListMenu li a:hover, #bcGingoogMenu li a:hover, #bcCdoMenu li a:hover, #turnOverMenu li a:hover, #awolMenu li a:hover, #resignMenu li a:hover {
    background: #ddd; /* Light hover effect */
}
</style>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <a class="navbar-brand d-flex align-items-center" href="">
            <img src="{{ asset('2.jpg') }}" alt="Logo"> <!-- Make sure '2.jpg' is in the 'public' folder -->
        </a>
        <ul class="nav flex-column">
            <li class="nav-item">
    <a href="{{ route('dashboard') }}" class="nav-link">
        <i class="fas fa-chart-line me-2"></i> Dashboard
    </a>
</li>

            <li class="nav-item">
    <a href="#" class="nav-link dropdown-toggle" id="broListToggle">
        <i class="fas fa-folder"></i> BRO LIST
    </a>
    <ul class="collapse list-unstyled" id="broListMenu">
        <li><a href="{{ route('accountability.accountability_records') }}" class="nav-link"><i class="fa-solid fa-table"></i>BRO Records</a></li>
        <li><a href="{{ url('/accountability') }}" class="nav-link"><i class="fa-solid fa-plus"></i>ADD Records</a></li>
    </ul>
</li>

<li class="nav-item">
    <a href="#" class="nav-link dropdown-toggle" id="techListToggle">
        <i class="fa-solid fa-screwdriver-wrench"></i> TECH LIST
    </a>
    <ul class="collapse list-unstyled" id="techListMenu">
        <li><a href="{{ route('technician.records') }}" class="nav-link"><i class="fa-solid fa-table"></i>TECH Records</a></li>
        <li><a href="{{ route('technician.create') }}" class="nav-link"><i class="fa-solid fa-plus"></i>ADD Records</a></li>
    </ul>
</li>
<li class="nav-item">
    <a href="#" class="nav-link dropdown-toggle" id="bcGingoogToggle" data-toggle="collapse" data-target="#bcGingoogMenu">
        <i class="fa-solid fa-location-dot"></i>  BC-GINGOOG LIST
    </a>
    <ul class="collapse list-unstyled" id="bcGingoogMenu">
        <li>
        <a href="{{ route('gingoogs.records') }}" class="nav-link"><i class="fa-solid fa-table"></i>GINGOOG Records</a>
        </li>
        <li>
            <a href="{{ route('gingoogs.create') }}" class="nav-link"><i class="fa-solid fa-plus"></i>ADD Records</a>
        </li>
    </ul>
</li>

<li class="nav-item">
    <a href="#" class="nav-link dropdown-toggle" id="bcCdoToggle">
        <i class="fa-solid fa-location-dot"></i> BC-CDO LIST
    </a>
    <ul class="collapse list-unstyled" id="bcCdoMenu">
        <li>
        <a href="{{ route('cdos.records') }}" class="nav-link"><i class="fa-solid fa-table"></i>CDO Records</a>
        </li>
        <li>
            <a href="{{ route('cdos.create') }}" class="nav-link"><i class="fa-solid fa-plus"></i>ADD Records</a>
        </li>
    </ul>
</li>

<li class="nav-item">
    <a href="#" class="nav-link dropdown-toggle" id="turnOverToggle">
        <i class="fa-solid fa-rotate"></i> TURN-OVER LIST
    </a>
    <ul class="collapse list-unstyled" id="turnOverMenu">
        <li>
            <a href="{{ route('turnover.records') }}" class="nav-link"><i class="fa-solid fa-rotate"></i>T-O Records</a>
        </li>
        <li>
            <a href="{{ route('turnover.create') }}" class="nav-link"><i class="fa-solid fa-plus"></i>ADD Records</a>
        </li>
    </ul>
</li>

<li class="nav-item">
    <a href="#" class="nav-link dropdown-toggle" id="awolToggle">
        <i class="fas fa-user"></i> AWOL LIST
    </a>
    <ul class="collapse list-unstyled" id="awolMenu">
        <li>
            <a href="{#" class="nav-link"><i class="fa-solid fa-table"></i>AWOL Records</a>
        </li>
        <li>
            <a href="#" class="nav-link"><i class="fa-solid fa-plus"></i>ADD Records</a>
        </li>
    </ul>
</li>

<li class="nav-item">
    <a href="#" class="nav-link dropdown-toggle" id="resignToggle">
        <i class="fas fa-user-minus"></i> RESIGN LIST
    </a>
    <ul class="collapse list-unstyled" id="resignMenu">
        <li>
            <a href="#" class="nav-link"><i class="fa-solid fa-table"></i>RESIGN Records</a>
        </li>
        <li>
            <a href="#" class="nav-link"><i class="fa-solid fa-plus"></i>ADD Records</a>
        </li>
    </ul>
</li>

    </div>
    <script>
<script>
document.addEventListener("DOMContentLoaded", function () {
        document.getElementById("bcGingoogToggle").addEventListener("click", function () {
            document.getElementById("bcGingoogMenu").classList.toggle("show");
        });
    });
document.addEventListener("DOMContentLoaded", function () {
    function setupDropdown(toggleId, menuId) {
        let dropdownToggle = document.getElementById(toggleId);
        let dropdownMenu = document.getElementById(menuId);

        dropdownToggle.addEventListener("click", function (event) {
            event.preventDefault();

            // Toggle Bootstrap's collapse class
            if (dropdownMenu.classList.contains("show")) {
                dropdownMenu.classList.remove("show");
                dropdownMenu.style.maxHeight = "0";
            } else {
                // Close other open dropdowns
                document.querySelectorAll(".list-unstyled.show").forEach(menu => {
                    menu.classList.remove("show");
                    menu.style.maxHeight = "0";
                });

                dropdownMenu.classList.add("show");
                dropdownMenu.style.maxHeight = dropdownMenu.scrollHeight + "px";
            }
        });
    }

    // Initialize dropdowns for BRO LIST and TECH LIST
    setupDropdown("broListToggle", "broListMenu");
    setupDropdown("techListToggle", "techListMenu");
});
</script>






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
                <li> <a class="dropdown-item" href="{{ route('account.info') }}"><i class="fa-regular fa-user"></i>Account Information</a></li>
                <li>
                <form action="{{ route('logout') }}" method="POST" id="logoutForm">
                    @csrf
                    <button type="button" class="dropdown-item text-danger" id="logoutButton"><i class="fa-solid fa-arrow-right-from-bracket"></i>Log Out</button>
                </form>
                </li>
            </ul>
        </div>
    </div>
</nav>

        <footer class="text-center py-3 text-muted">
    © 2025 Converge. All Rights Reserved. | <a href="#">Privacy Policy</a> | <a href="#">Terms & Conditions</a> | <a href="#">Site Map</a>
</footer>
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
    document.addEventListener("DOMContentLoaded", function() {
    const sidebar = document.getElementById('sidebar');
    const content = document.getElementById('content');

    // Load the sidebar state from localStorage
    const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';

    if (isCollapsed) {
        sidebar.classList.add('collapsed');
        content.classList.add('expanded');
    } else {
        sidebar.classList.remove('collapsed');
        content.classList.remove('expanded');
    }

    // Sidebar hover effect
    sidebar.addEventListener('mouseenter', () => {
        sidebar.classList.remove('collapsed');
        content.classList.remove('expanded');
        localStorage.setItem('sidebarCollapsed', 'false'); // Save state
    });

    sidebar.addEventListener('mouseleave', () => {
        sidebar.classList.add('collapsed');
        content.classList.add('expanded');
        localStorage.setItem('sidebarCollapsed', 'true'); // Save state
    });

    // Auto-remove success messages after 3 seconds
    setTimeout(function() {
        document.querySelector('.alert-success')?.remove();
    }, 3000);

    // Dropdown toggle functionality
    document.querySelectorAll(".nav-link.dropdown-toggle").forEach(function(toggle) {
        toggle.addEventListener("click", function(event) {
            event.preventDefault(); // Prevent page reload

            let menu = this.nextElementSibling; // Get the next UL menu

            if (menu && menu.classList.contains("collapse")) {
                // Close all other open dropdowns
                document.querySelectorAll(".collapse.show").forEach(m => {
                    if (m !== menu) {
                        m.classList.remove("show");
                    }
                });

                // Toggle the clicked menu
                menu.classList.toggle("show");
            }
        });
    });
});
document.addEventListener("DOMContentLoaded", function () {
    let logoutButton = document.getElementById("logoutButton");

    if (logoutButton) {
        logoutButton.addEventListener("click", function (event) {
            event.preventDefault(); // Prevent default form submission

            Swal.fire({
                title: "Are you sure you want to log out?",
                text: "You will need to log in again to access your account.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, Log Out"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById("logoutForm").submit(); // Submit the form if confirmed
                }
            });
        });
    }
});
// Hide loading overlay when page fully loads
    window.addEventListener("load", function() {
        document.getElementById("loading-overlay").style.display = "none";
    });
</script>
</body>
</html>
