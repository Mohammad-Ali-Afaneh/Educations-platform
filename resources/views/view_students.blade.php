<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            margin: 0;
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            display: flex;
            justify-content: center;
            align-items: flex-start;
            font-family: 'Poppins', sans-serif;
            padding: 40px 10px;
        }
        .students-container {
            width: 100%;
            max-width: 1000px;
            padding: 30px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            border-radius: 20px;
            box-shadow: 0 12px 40px rgba(0,0,0,0.25);
            animation: fadeIn 0.7s ease-in-out;
        }
        h2 {
            font-weight: bold;
            color: #222;
            margin-bottom: 25px;
            text-align: center;
        }
        .table th {
            background: linear-gradient(45deg, #6a11cb, #2575fc);
            color: white;
            text-align: center;
        }
        .table td {
            text-align: center;
            vertical-align: middle;
        }
        .btn {
            border-radius: 12px;
            font-weight: 600;
            border: none;
            transition: all 0.3s ease;
        }
        .btn-success, .btn-primary, .btn-secondary {
            background: linear-gradient(45deg, #6a11cb, #2575fc);
        }
        .btn-success:hover, .btn-primary:hover, .btn-secondary:hover {
            opacity: 0.9;
            transform: scale(1.05);
        }
        .alert {
            border-radius: 12px;
            font-weight: 500;
        }
        .search-form {
            max-width: 400px;
            margin: 0 auto 20px;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="students-container">
        <h2>Students List</h2>
        
        <div class="d-flex justify-content-left mb-3 gap-2">
            <a href="{{ route('dashboard') }}" class="btn btn-secondary w-10 mt-3">Back</a>
        </div>
        <form method="GET" action="{{ route('students.index') }}" class="search-form">
            <div class="input-group mb-3">
                <input type="text" name="search" class="form-control" placeholder="Search students..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </form>
        @if (session('success'))
            <div class="alert alert-success text-center">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Gender</th>
                    <th>Birthdate</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                    <tr>
                        <td>{{ $student->id }}</td>
                        <td>{{ $student->first_name }}</td>
                        <td>{{ $student->last_name }}</td>
                        <td>{{ $student->email }}</td>
                        <td>{{ ucfirst($student->gender) }}</td>
                        <td>{{ $student->birthdate }}</td>
                        <td>
                            <a href="{{ route('materials.create', $student->id) }}" class="btn btn-success btn-sm">Add Course</a>
                            <a href="{{ route('materials.index', $student->id) }}" class="btn btn-primary btn-sm">View Courses</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">No students registered yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <a href="{{ route('dashboard') }}" class="btn btn-secondary w-100 mt-3">Back to Dashboard</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>