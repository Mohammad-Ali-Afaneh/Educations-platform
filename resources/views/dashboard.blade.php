<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
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
        .dashboard-container {
            width: 100%;
            max-width: 1000px;
            padding: 30px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            border-radius: 20px;
            box-shadow: 0 12px 40px rgba(0,0,0,0.25);
            animation: fadeIn 0.7s ease-in-out;
        }
        h2, h3 {
            font-weight: bold;
            color: #222;
            text-align: center;
        }
        p {
            text-align: center;
            color: #555;
            font-size: 1.05rem;
        }
        .btn-primary, .btn-success {
            background: linear-gradient(45deg, #6a11cb, #2575fc);
            border: none;
            font-weight: 600;
            border-radius: 12px;
            transition: all 0.3s ease;
        }
        .btn-primary:hover, .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(37,117,252,0.4);
        }
        .btn-danger {
            font-weight: 600;
            border-radius: 12px;
        }
        .table {
            background: #fff;
            border-radius: 15px;
            overflow: hidden;
            margin-top: 15px;
        }
        .table th {
            background: linear-gradient(45deg, #6a11cb, #2575fc);
            color: #fff;
            text-align: center;
        }
        .table td {
            vertical-align: middle;
            text-align: center;
        }
        .course-image {
            width: 55px;
            height: 55px;
            border-radius: 12px;
            object-fit: cover;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2);
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .search-form {
            max-width: 400px;
            margin: 0 auto 20px;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        @if (Session::has('user_type') && Session::get('user_type') === 'teacher')
            <h2>Dashboard</h2>
            <h3>Courses List</h3>
            @if (session('success'))
                <div class="alert alert-success text-center">{{ session('success') }}</div>
            @endif
            
            <div class="d-flex justify-content-center mb-3 gap-2">
                <a href="{{ route('students.index') }}" class="btn btn-primary">View Students</a>
                <a href="{{ route('courses.create') }}" class="btn btn-success">Add Course</a>
            </div>
            <form method="GET" action="{{ route('dashboard') }}" class="search-form">
                <div class="input-group mb-3">
                    <input type="text" name="search" class="form-control" placeholder="Search courses..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </form>
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>Course Name</th>
                            <th>Description</th>
                            <th>Base Mark</th>
                            <th>Image</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($courses as $course)
                            <tr>
                                <td>{{ $course->name }}</td>
                                <td>{{ $course->description }}</td>
                                <td>{{ $course->base_mark }}</td>
                                <td><img src="{{ $course->image ? asset('storage/' . $course->image) : '' }}" class="course-image" alt="{{ $course->name }}"></td>
                                <td>
                                    <a href="{{ route('courses.edit', $course) }}" class="btn btn-primary btn-sm">Edit</a>
                                    <form action="{{ route('courses.destroy', $course) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this course?');">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center">No courses available</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-center">Welcome Student! You do not have access to these features.</p>
        @endif
        
        <a href="{{ route('chat') }}" class="btn btn-primary w-100 mt-3">Chat</a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-danger w-100 mt-3">Logout</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>