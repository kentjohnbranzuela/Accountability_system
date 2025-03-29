<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
       body {
        color: black !important;
        background:
        url("{{ asset('21.png') }}") no-repeat center center fixed,
        linear-gradient(135deg, rgb(61, 0, 81), rgb(106, 212, 250));
    background-size: contain, cover; /* 'contain' keeps the logo visible */
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
}
h3, label, .form-control, .btn-primary, .alert, #remember {
    color: black !important; /* Ensures headings, labels, buttons, and alerts are black */
}
.form-control {
    color: black !important;
    background: rgba(255, 255, 255, 0.9); /* Light background for readability */
    border: 2px solid rgba(0, 0, 0, 0.5); /* Darker border */
}

/* Placeholder text inside inputs */
.form-control::placeholder {
    color: rgba(0, 0, 0, 0.6); /* Darker placeholder */
}

/* Checkbox label */
label[for="remember"] {
    color: black !important;
    font-weight: bold;
}

/* Change button text to black */
.btn-primary {
    color: black !important;
}
.login-container {
    position: relative;
    width: 400px; /* Ensure proper width */
    padding: 30px;
    border-radius: 15px;
    background: rgba(255, 255, 255, 0.15); /* Slight transparency */
    backdrop-filter: blur(10px); /* Blurred glass effect */
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
    text-align: center;
    color: white;
}

.login-container::before {
    content: "";
    position: absolute;
    inset: 0;
    border-radius: 15px;
    padding: 2px;
    background: linear-gradient(135deg, rgb(20, 4, 58), rgb(16, 32, 75));
    -webkit-mask: linear-gradient(white 0 0) content-box, linear-gradient(white 0 0);
    -webkit-mask-composite: xor;
    mask-composite: exclude;
    pointer-events: none; /* Allow interactions */
}

.logo {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border-radius: 50%;
    margin-bottom: 15px;
}


.btn-primary:hover {
    background: linear-gradient(135deg, rgb(46, 179, 255), rgb(70, 0, 105));
}

        .form-control:focus {
            border-color: #009ffd;
            box-shadow: 0 0 10px rgba(0, 159, 253, 0.5);
        }
    </style>
</head>
<body>
@if(session('success'))
    <div class="alert alert-success text-center"
         style="position: absolute; top: 20px; left: 50%; transform: translateX(-50%); width: 50%;">
        {{ session('success') }}
    </div>
@endif
    <div class="login-container position-relative">
        <h3 class="mb-3 fw-bold text-light">Login</h3>
        <form method="POST" action="{{ route('login') }}">
    @csrf

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>{{ $errors->first() }}</strong>
        </div>
    @endif

    <div class="mb-3 text-start">
    <label for="email" class="form-label text-light">Email address</label>
    <input type="email"  class="form-control" placeholder="Email address." id="email" name="email" value="{{ old('email', Cookie::get('email')) }}" required>
</div>
<div class="mb-3 text-start position-relative">
    <label for="password" class="form-label">Password</label>
    <div class="input-group">
        <input type="password" class="form-control" id="password" placeholder="Password." name="password" required>
        <span class="input-group-text" id="togglePassword" style="cursor: pointer;">
            👁️
        </span>
    </div>
</div>
<div>
    <input type="checkbox" name="remember" id="remember" {{ Cookie::get('email') ? 'checked' : '' }}>
    <label for="remember">Remember Me</label>
</div>
<button type="submit" class="btn btn-primary w-100">Log in</button>
</form>
    </div>
</body>
</html>
<script>
document.getElementById('togglePassword').addEventListener('click', function () {
    let passwordField = document.getElementById('password');
    let type = passwordField.type === "password" ? "text" : "password";
    passwordField.type = type;
});
</script>
<script>
    setTimeout(function() {
        document.querySelector('.alert-success')?.remove();
    }, 3000); // Message disappears after 3 seconds
</script>
<script>
document.querySelector('form').addEventListener('submit', function(event) {
    if (document.getElementById('email').value === "" || document.getElementById('password').value === "") {
        event.preventDefault(); // Prevent only if fields are empty
        alert("Please fill in all fields!");
    } else {
        this.submit(); // Allow form submission
    }
});
</script>
