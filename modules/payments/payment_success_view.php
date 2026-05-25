<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Payment Success | BikriBazaar</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:Arial,sans-serif;
        }

        body{
            background:#eef2ff;
            display:flex;
            justify-content:center;
            align-items:center;
            min-height:100vh;
            padding:20px;
        }

        .success-card{
            background:white;
            width:100%;
            max-width:700px;
            border-radius:30px;
            padding:60px 40px;
            text-align:center;
            box-shadow:0 15px 40px rgba(0,0,0,0.08);
        }

        .success-icon{
            width:120px;
            height:120px;
            background:#dcfce7;
            color:#16a34a;
            border-radius:50%;
            display:flex;
            justify-content:center;
            align-items:center;
            margin:0 auto 30px;
            font-size:60px;
        }

        h1{
            font-size:52px;
            color:#0f172a;
            margin-bottom:20px;
            font-weight:800;
        }

        p{
            font-size:20px;
            color:#64748b;
            line-height:1.8;
            margin-bottom:40px;
        }

        .btn-group{
            display:flex;
            justify-content:center;
            gap:20px;
            flex-wrap:wrap;
        }

        .btn{
            padding:15px 30px;
            border-radius:12px;
            text-decoration:none;
            font-size:17px;
            font-weight:700;
            transition:0.3s;
        }

        .home-btn{
            background:linear-gradient(to right,#2563eb,#06b6d4);
            color:white;
        }

        .back-btn{
            background:#f1f5f9;
            color:#0f172a;
        }

        .btn:hover{
            transform:translateY(-3px);
        }

        @media(max-width:768px){

            h1{
                font-size:38px;
            }

            p{
                font-size:17px;
            }

            .success-card{
                padding:45px 25px;
            }

        }

    </style>

</head>

<body>

    <div class="success-card">

        <div class="success-icon">

            <i class="fa-solid fa-check"></i>

        </div>

        <h1>Payment Successful</h1>

        <p>

            Your product boost plan has been activated successfully.
            Your advertisement will now receive better visibility and buyer reach on BikriBazaar.

        </p>

        <div class="btn-group">

            <a href="<?php echo BASE_URL; ?>index.php" class="btn home-btn">

                <i class="fa-solid fa-house"></i>
                Go To Home

            </a>

            <a href="javascript:history.back()" class="btn back-btn">

                <i class="fa-solid fa-arrow-left"></i>
                Back

            </a>

        </div>

    </div>

</body>

</html>