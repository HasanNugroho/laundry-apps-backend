<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <style>
        body{
            background-color: #f4f4f4;
        }
        .card{
            background-color: white;
            padding: 24px;
            border-radius: 12px;
            margin: 16px;
            text-align: center;
        }
        .button{
            padding: 12px 24px;
            background-color: #06b6d4;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            width: 100%;
            margin-top: 24px;
        }
        .footer p{
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="card">
        <h2>{{$title}}</h2>
    <p>{{$deskripsi}}</p>
    <a href={{ $url }} class="button" >Verify Email Address</a>
    </div>
    <div class="footer">
        <p>2021 Laundrynotes. All right reserved.</p>
    </div>
</body>
</html>