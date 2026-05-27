<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Success | BikriBazaar</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', 'Inter', system-ui, sans-serif;
            background: linear-gradient(145deg, #eef2ff 0%, #e0e7ff 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        .success-card {
            max-width: 560px;
            width: 100%;
            background: #ffffff;
            border-radius: 2rem;
            padding: 2.5rem 2rem;
            text-align: center;
            box-shadow: 0 25px 45px -12px #afbbcf;
            border: 1px solid #adadad;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .success-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 30px 55px -15px rgba(26, 63, 196, 0.3);
        }

        /* Simple pulse animation (no glitch) */
        .checkmark-circle {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            animation: gentle-pulse 0.4s ease-out;
        }

        .checkmark-circle i {
            font-size: 3rem;
            color: #16a34a;
        }

        @keyframes gentle-pulse {
            0% {
                transform: scale(0.9);
                opacity: 0;
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        h1 {
            font-size: 2rem;
            font-weight: 800;
            background: linear-gradient(135deg, #1a3fc4, #0ea5a0);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin-bottom: 0.75rem;
            letter-spacing: -0.3px;
        }

        .success-message {
            font-size: 1rem;
            color: #4b5563;
            line-height: 1.6;
            margin-bottom: 2rem;
            padding: 0 0.5rem;
        }

        .highlights {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin: 1.5rem 0 2rem;
            flex-wrap: wrap;
        }

        .highlight-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.85rem;
            color: #1f2937;
            background: #f8fafc;
            padding: 0.4rem 1rem;
            border-radius: 40px;
            border: 1px solid #e2e8f0;
        }

        .highlight-item i {
            color: #0ea5a0;
            font-size: 0.9rem;
        }

        .btn-group {
            display: flex;
            justify-content: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.8rem 1.8rem;
            border-radius: 60px;
            font-weight: 700;
            font-size: 0.9rem;
            transition: all 0.2s ease;
            text-decoration: none;
            cursor: pointer;
        }

        .btn-primary {
            background: linear-gradient(95deg, #1a3fc4, #0ea5a0);
            color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 18px -5px rgba(26, 63, 196, 0.4);
        }

        .btn-secondary {
            background: #f1f5f9;
            color: #1e293b;
            border: 1px solid #cbd5e1;
        }

        .btn-secondary:hover {
            background: #e2e8f0;
            transform: translateY(-2px);
        }

        @media (max-width: 550px) {
            .success-card {
                padding: 1.8rem 1.2rem;
            }
            h1 {
                font-size: 1.6rem;
            }
            .checkmark-circle {
                width: 75px;
                height: 75px;
            }
            .checkmark-circle i {
                font-size: 2.2rem;
            }
            .btn {
                padding: 0.6rem 1.2rem;
                font-size: 0.8rem;
            }
            .highlight-item {
                font-size: 0.75rem;
                padding: 0.3rem 0.8rem;
            }
        }
    </style>
</head>

<body>

    <div class="success-card">
        <div class="checkmark-circle">
            <i class="fa-solid fa-check"></i>
        </div>
        <h1>Payment Successful!</h1>
        <div class="success-message">
            Your boost plan is now active. Your ad will get premium visibility and reach more buyers instantly.
        </div>

        <div class="highlights">
            <div class="highlight-item"><i class="fa-solid fa-chart-line"></i> Priority Ranking</div>
            <div class="highlight-item"><i class="fa-solid fa-eye"></i> Higher Visibility</div>
            <div class="highlight-item"><i class="fa-solid fa-bolt"></i> Faster Sales</div>
        </div>

        <div class="btn-group">
            <a href="<?php echo BASE_URL; ?>index.php" class="btn btn-primary">
                <i class="fa-solid fa-house"></i> Go to Home
            </a>
            <a href="javascript:history.back()" class="btn btn-secondary">
                <i class="fa-solid fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

</body>

</html>