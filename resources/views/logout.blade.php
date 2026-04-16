<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>logout</title>
</head>
<body>
    <script>
        // إنهاء الجلسة وإعادة التوجيه تلقائيًا
        window.location.href = "{{ route('login') }}";
    </script>
</body>
</html>