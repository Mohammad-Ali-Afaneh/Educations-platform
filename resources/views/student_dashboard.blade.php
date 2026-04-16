<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
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
        h2, h4 {
            font-weight: bold;
            color: #222;
            text-align: center;
        }
        .table th, .table td {
            text-align: center;
            vertical-align: middle;
        }
        .btn-primary, .btn-danger {
            background: linear-gradient(45deg, #6a11cb, #2575fc);
            border: none;
            font-weight: 600;
            border-radius: 12px;
            transition: all 0.3s ease;
        }
        .btn-primary:hover, .btn-danger:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h2 class="mb-4">Student Dashboard - {{ session('user_name') }}</h2>
        
        @if (session('success'))
            <div class="alert alert-success text-center">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger text-center">{{ session('error') }}</div>
        @endif
        
        <h4 class="mt-4">Registered Courses</h4>
        <table class="table table-bordered table-striped">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Course Name</th>
                    <th>Date Added</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($materials as $material)
                    <tr>
                        <td>{{ $material->id }}</td>
                        <td>{{ $material->name }}</td>
                        <td>{{ $material->created_at }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center">No courses registered</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <h4 class="mt-4">Marks</h4>
        <table class="table table-bordered table-striped">
            <thead class="table-light">
                <tr>
                    <th>Course ID</th>
                    <th>Mark</th>
                    <th>Date Added</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($marks as $mark)
                    <tr>
                        <td>{{ $mark->material_id }}</td>
                        <td>{{ $mark->mark ?? 'Not Assigned' }}</td>
                        <td>{{ $mark->mark_created_at ?? '' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center">No marks registered</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <a href="{{ route('courses.all') }}" class="btn btn-primary w-100 mt-3">View Courses</a>
        <a href="{{ route('chat') }}" class="btn btn-primary w-100 mt-3">Chat</a>
        
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-danger w-100 mt-3">Logout</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>