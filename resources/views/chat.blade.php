<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>General Chat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/js/app.js'])
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
        .chat-container {
            width: 100%;
            max-width: 800px;
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
            text-align: center;
            margin-bottom: 20px;
        }
        #messages {
            height: 400px;
            overflow-y: auto;
            padding: 15px;
            background: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            border-radius: 12px;
        }
        .message {
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 15px;
        }
        .message.sent {
            background: linear-gradient(45deg, #6a11cb, #2575fc);
            color: white;
            text-align: right;
        }
        .message.received {
            background: #e9ecef;
            color: #333;
        }
        .btn-primary, .btn-secondary {
            background: linear-gradient(45deg, #6a11cb, #2575fc);
            border: none;
            font-weight: 600;
            border-radius: 12px;
            transition: all 0.3s ease;
        }
        .btn-primary:hover, .btn-secondary:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }
        .btn-primary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="chat-container">
        <h2>General Chat - Hello {{ $userName }} ({{ $userType }})</h2>
        
        <div id="messages">
            @forelse($messages as $msg)
                <div class="message {{ $msg->user_id == $userId && $msg->user_type == $userType ? 'sent' : 'received' }}">
                    <strong>{{ $msg->user_name }} ({{ $msg->user_type }}):</strong> {{ $msg->message }}<br>
                    <small>{{ $msg->created_at->format('H:i') }}</small>
                </div>
            @empty
                <p class="text-center text-muted">No messages yet. Start the conversation!</p>
            @endforelse
        </div>
        
        <form id="messageForm" class="p-3">
            <div class="input-group">
                <input type="text" id="messageInput" class="form-control" placeholder="Type your message..." required>
                <button type="submit" class="btn btn-primary" id="submitButton">Send</button>
            </div>
        </form>
        
        <a href="{{ Session::get('user_type') === 'teacher' ? route('dashboard') : route('student.dashboard') }}" class="btn btn-secondary d-block m-3">Back</a>
    </div>

    <script>
        // Ensure Echo is loaded
        if (typeof window.Echo === 'undefined') {
            console.error('Echo is not defined. Ensure app.js is loaded correctly.');
        } else {
            window.Echo.join('general-chat')
                .here((users) => {
                    console.log('Users online:', users);
                })
                .joining((user) => {
                    console.log(`${user.name} joined`);
                })
                .leaving((user) => {
                    console.log(`${user.name} left`);
                })
                .listen('MessageSent', (e) => {
                    const msgDiv = document.createElement('div');
                    msgDiv.classList.add('message', 'received');
                    msgDiv.innerHTML = '<strong>' + e.user_name + ' (' + e.user_type + '):</strong> ' + e.message + '<br><small>' + e.created_at + '</small>';
                    document.getElementById('messages').appendChild(msgDiv);
                    document.getElementById('messages').scrollTop = document.getElementById('messages').scrollHeight;
                });

            document.getElementById('messageForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const messageInput = document.getElementById('messageInput');
                const submitButton = document.getElementById('submitButton');
                const message = messageInput.value.trim();
                if (!message) return;

                submitButton.disabled = true;
                submitButton.textContent = 'Sending...';

                fetch('{{ route('chat.send') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ message: message })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    const msgDiv = document.createElement('div');
                    msgDiv.classList.add('message', 'sent');
                    msgDiv.innerHTML = '<strong>You:</strong> ' + data.message + '<br><small>' + data.created_at + '</small>';
                    document.getElementById('messages').appendChild(msgDiv);
                    document.getElementById('messages').scrollTop = document.getElementById('messages').scrollHeight;
                    messageInput.value = '';
                    submitButton.disabled = false;
                    submitButton.textContent = 'Send';
                })
                .catch(error => {
                    console.error('Error:', error);
                    submitButton.disabled = false;
                    submitButton.textContent = 'Send';
                    alert('Failed to send the message. Please try again.');
                });
            });
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>