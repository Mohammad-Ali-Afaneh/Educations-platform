<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Courses</title>
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
        .materials-container {
            width: 100%;
            max-width: 900px;
            padding: 30px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            border-radius: 20px;
            box-shadow: 0 12px 40px rgba(0,0,0,0.25);
            animation: fadeIn 0.7s ease-in-out;
        }
        h2 {
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
            color: #222;
        }
        .alert {
            border-radius: 12px;
            font-weight: 500;
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
        .btn-info {
            background: linear-gradient(45deg, #00c6ff, #0072ff);
            border: none;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        .btn-info:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,114,255,0.4);
        }
        .btn-secondary {
            border-radius: 12px;
            font-weight: 600;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="materials-container">
        <h2>Student Courses</h2>
        <div class="d-flex justify-content-left mb-3 gap-2">
               <a href="{{ route('dashboard') }}" class="btn btn-secondary w-10 mt-3">Home Page</a>
        </div>
        
        @if (session('error'))
            <div class="alert alert-danger text-center">{{ session('error') }}</div>
        @endif
        @if (session('success'))
            <div class="alert alert-success text-center">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Course Name</th>
                        <th>Added Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($materials as $material)
                        <tr>
                            <td>{{ $material->id }}</td>
                            <td>{{ $material->course->name }}</td>
                            <td>{{ $material->created_at }}</td>
                            <td>
                                <a href="{{ route('marks.create', [$material->id, $student_id]) }}" 
                                   class="btn btn-info btn-sm">Add Mark</a>
                                <form action="{{ route('materials.destroy', $material->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center">No courses registered for this student</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <a href="{{ route('students.index') }}" class="btn btn-secondary w-100 mt-3">Back to Students List</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>