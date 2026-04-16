<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Course</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            margin: 0;
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Poppins', sans-serif;
            padding: 30px 10px;
        }
        .form-container {
            width: 100%;
            max-width: 600px;
            padding: 30px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            border-radius: 20px;
            box-shadow: 0 12px 40px rgba(0,0,0,0.25);
            animation: fadeIn 0.6s ease-in-out;
        }
        h2 {
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
            color: #222;
        }
        label {
            font-weight: 600;
            color: #444;
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
        .btn-secondary {
            border-radius: 12px;
            font-weight: 600;
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
        <h2>Edit Course</h2>
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('courses.update', $course) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label for="name" class="form-label">Course Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                       id="name" name="name" value="{{ old('name', $course->name) }}" required>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control @error('description') is-invalid @enderror" 
                          id="description" name="description" rows="4">{{ old('description', $course->description) }}</textarea>
                @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            
            <div class="mb-3">
                <label for="base_mark" class="form-label">Base Mark</label>
                <input type="number" class="form-control @error('base_mark') is-invalid @enderror" 
                       id="base_mark" name="base_mark" value="{{ old('base_mark', $course->base_mark) }}" required>
                @error('base_mark') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            
            <div class="mb-3">
                <label for="image" class="form-label">Image</label>
                <input type="file" class="form-control @error('image') is-invalid @enderror" 
                       id="image" name="image">
                @if($course->image)
                    <img src="{{ asset('storage/' . $course->image) }}" alt="Current Image" style="max-width: 200px; margin-top: 10px;">
                @endif
                @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            
            <button type="submit" class="btn btn-primary w-100">Save Changes</button>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary w-100 mt-2">Back</a>
        </form>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>