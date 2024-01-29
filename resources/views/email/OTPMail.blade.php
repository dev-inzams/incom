<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Email</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
        }

        .jumbotron {
            padding: 2rem;
            margin-bottom: 2rem;
            background-color: #e9ecef;
            border-radius: 0.3rem;
        }

        .btn-primary {
            color: #fff;
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            color: #fff;
            background-color: #0056b3;
            border-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="jumbotron">
            <h1 class="display-4">OTP Verification</h1>
            <p class="lead">Use the following OTP to verify your identity:</p>
            <hr class="my-4">
            <p class="lead">
                <strong>Your OTP:</strong>
                <span id="otp" style="font-size: 1.5em; font-weight: bold; color: #007bff;">{{$details}}</span>
            </p>
            <p class="text-muted">This OTP is valid for a short period. Please do not share it with anyone.</p>
            <a class="btn btn-primary btn-lg" href="#" role="button">Verify Now</a>
        </div>
        <p class="text-muted">If you did not request this OTP, you can safely ignore this email.</p>
    </div>
</body>
</html>

