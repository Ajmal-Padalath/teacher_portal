<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login Page</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f2f2f2;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            background-color: #fff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .login-container h1 {
            margin-bottom: 1.5rem;
            text-align: center;
            color: #333;
        }

        .input-group {
            margin-bottom: 1rem;
        }

        .input-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
        }

        .input-group input[type="email"],
        .input-group input[type="password"] {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
            color: #333;
        }

        button {
            width: 100%;
            padding: 0.75rem;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Login</h1>
        <form>
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <p style="color: red" id="login-error"></p>
            <button type="button" id="login-btn">Login</button>
            
        </form>
    </div>
    <script>
        $("#login-btn").click(function(){
            var email = $('#email').val();
            var password = $('#password').val();
            $.ajax({
                type: 'POST',
                url: "{{url('check-log-in')}}",
                data: {email: email, password: password, _token: '{{csrf_token()}}'},
                success: function (data) {
                   if (data.status == 1) {
                        $('#login-error').html('');
                        window.location.href = "{{url('/teacher-dashboard')}}";
                   } else {
                        $('#login-error').html(data.messge);
                   }
                },
            });
        });
    </script>
</body>
</html>