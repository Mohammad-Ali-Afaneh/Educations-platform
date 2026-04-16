<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Course to Student</title>
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
        .form-container {
            width: 100%;
            max-width: 600px;
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
        label {
            font-weight: 600;
        }
        .btn {
            border-radius: 12px;
            font-weight: 600;
            border: none;
            transition: all 0.3s ease;
        }
        .btn-success, .btn-secondary {
            background: linear-gradient(45deg, #6a11cb, #2575fc);
        }
        .btn-success:hover, .btn-secondary:hover {
            opacity: 0.9;
            transform: scale(1.05);
        }
        .alert {
            border-radius: 12px;
            font-weight: 500;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Add Course to Student</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success text-center">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger text-center">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('materials.store', $student_id) }}">
            @csrf
            <input type="hidden" name="student_id" value="{{ $student_id }}">

            <div class="mb-3">
                <label for="course_id" class="form-label">Select Course</label>
                <select class="form-control @error('course_id') is-invalid @enderror" id="course_id" name="course_id" required>
                    <option value="">-- Choose a Course --</option>
                    @foreach ($courses as $course)
                        <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                            {{ $course->name }}
                        </option>
                    @endforeach
                </select>
                @error('course_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <button type="submit" class="btn btn-success w-100">Add Course</button>
            <a href="{{ route('students.index') }}" class="btn btn-secondary w-100 mt-2">Back</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>