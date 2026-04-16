<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            margin: 0;
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Tajawal', sans-serif;
        }
        .form-container {
            width: 100%;
            max-width: 420px;
            padding: 40px 30px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(12px);
            border-radius: 25px;
            box-shadow: 0 12px 40px rgba(0,0,0,0.2);
            animation: fadeIn 0.7s ease-in-out;
            position: relative;
        }
        .form-logo {
            width: 80px;
            height: 80px;
            background: linear-gradient(45deg, #6a11cb, #2575fc);
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
            font-size: 32px;
            font-weight: bold;
            margin: 0 auto 15px auto;
            box-shadow: 0 6px 15px rgba(0,0,0,0.2);
        }
        h2 {
            font-weight: bold;
            color: #222;
            text-align: center;
            margin-bottom: 25px;
        }
        .btn-primary {
            background: linear-gradient(45deg, #6a11cb, #2575fc);
            border: none;
            transition: all 0.3s ease;
            font-size: 1.1rem;
            font-weight: 600;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(37,117,252,0.4);
        }
        .form-control, .form-select {
            border-radius: 12px;
            transition: 0.3s ease;
        }
        .form-control:focus, .form-select:focus {
            border-color: #6a11cb;
            box-shadow: 0 0 0 0.25rem rgba(106, 17, 203, 0.25);
        }
        .alert {
            border-radius: 12px;
        }
        .form-label {
            font-weight: 600;
            color: #444;
        }
        .text-link {
            color: #2575fc;
            font-weight: 600;
            text-decoration: none;
            transition: 0.2s ease;
        }
        .text-link:hover {
            color: #6a11cb;
            text-decoration: underline;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <div class="form-logo">🔒</div>
        <h2>Login</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <p class="mb-0">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <label for="userType" class="form-label">User Type</label>
                <select class="form-select" id="userType" name="userType" required>
                    <option value="">Choose...</option>
                    <option value="teacher" {{ old('userType') == 'teacher' ? 'selected' : '' }}>Teacher</option>
                    <option value="student" {{ old('userType') == 'student' ? 'selected' : '' }}>Student</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
            </div>

            <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2">Login</button>
        </form>

        <div class="mt-4 text-center">
            <span>Don't have an account? </span>
            <a href="{{ route('register') }}" class="text-link">Register now</a>
            <span> | </span>
            <p><a href="{{ route('password.request') }}" class="text-link"> forgot my password </a></p>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
