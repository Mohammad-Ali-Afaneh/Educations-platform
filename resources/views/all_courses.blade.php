<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Courses</title>
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
        .courses-container {
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
        .btn-primary {
            background: linear-gradient(45deg, #6a11cb, #2575fc);
            border: none;
            font-weight: 600;
            border-radius: 12px;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(37,117,252,0.4);
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
    <div class="courses-container">
        <h2>All Courses</h2>
        <h3></h3>
        @if (session('success'))
            <div class="alert alert-success text-center">{{ session('success') }}</div>
        @endif
        <form method="GET" action="{{ route('courses.all') }}" ump class="search-form">
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
                    </tr>
                </thead>
                <tbody>
                    @forelse($courses as $course)
                        <tr>
                            <td>{{ $course->name }}</td>
                            <td>{{ $course->description }}</td>
                            <td>{{ $course->base_mark }}</td>
                            <td><img src="{{ $course->image ? asset('storage/' . $course->image) : '' }}" class="course-image" alt="{{ $course->name }}"></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center">No courses available</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <a href="{{ route('student.dashboard') }}" class="btn btn-primary w-100 mt-3">Back to Dashboard</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>