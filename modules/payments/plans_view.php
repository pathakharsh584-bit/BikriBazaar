<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Choose Promotion Plan - BikriBazaar</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>

    <style>

        :root {
            --primary: #1a3fc4;
            --primary-dark: #1530a0;
            --teal: #0ea5a0;
            --teal-dark: #0b8a86;
            --grad: linear-gradient(135deg, #1a3fc4 0%, #0ea5a0 100%);
            --surface: #f4f7ff;
            --card-bg: #ffffff;
            --text: #1a1a2e;
            --muted: #6b7280;
            --border: #dde4f5;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: var(--surface);
            color: var(--text);
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        .hero {
            background: var(--grad);
            color: white;
            padding: 3rem 1.5rem;
            text-align: center;
        }

        .hero h1 {
            font-size: 2.4rem;
            font-weight: 800;
        }

        .hero p {
            margin-top: .5rem;
            color: rgba(255,255,255,.85);
        }

        .container {
            max-width: 1350px;
            margin: auto;
            padding: 2rem 1rem;
        }

        .product-preview {
            background: white;
            border-radius: 18px;
            overflow: hidden;
            display: flex;
            gap: 1.5rem;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,.08);
            border: 1px solid var(--border);
            align-items: center;
        }

        .product-preview img {
            width: 220px;
            height: 180px;
            object-fit: cover;
            border-radius: 12px;
        }

        .product-info h2 {
            font-size: 1.5rem;
            font-weight: 800;
        }

        .product-info p {
            color: var(--muted);
            margin-top: .6rem;
            line-height: 1.5;
        }

        .product-price {
            margin-top: 1rem;
            color: var(--primary);
            font-size: 1.4rem;
            font-weight: 800;
        }

        .section-title {
            font-size: 1.8rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
        }

        .plans-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit,minmax(290px,1fr));
            gap: 1.5rem;
        }

        .plan-card {
            background: white;
            border-radius: 20px;
            padding: 2rem 1.5rem;
            position: relative;
            overflow: hidden;
            border: 2px solid var(--border);
            transition: .25s ease;
            box-shadow: 0 5px 18px rgba(0,0,0,.06);
        }

        .plan-card:hover {
            transform: translateY(-6px);
            border-color: var(--teal);
            box-shadow: 0 10px 35px rgba(14,165,160,.18);
        }

        .recommended {
            border-color: var(--primary);
            transform: scale(1.02);
        }

        .recommended::before {
            content: 'MOST POPULAR';
            position: absolute;
            top: 15px;
            right: -40px;
            background: var(--grad);
            color: white;
            padding: .35rem 3rem;
            font-size: .7rem;
            font-weight: 700;
            transform: rotate(45deg);
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            background: rgba(26,63,196,.08);
            color: var(--primary);
            padding: .4rem .8rem;
            border-radius: 30px;
            font-size: .75rem;
            font-weight: 700;
        }

        .plan-name {
            margin-top: 1rem;
            font-size: 1.5rem;
            font-weight: 800;
        }

        .plan-price {
            margin-top: 1rem;
            font-size: 2.2rem;
            font-weight: 800;
            color: var(--primary);
        }

        .plan-duration {
            color: var(--muted);
            margin-top: .3rem;
            font-size: .9rem;
        }

        .features {
            margin-top: 1.5rem;
            display: flex;
            flex-direction: column;
            gap: .9rem;
        }

        .feature {
            display: flex;
            align-items: center;
            gap: .7rem;
            font-size: .95rem;
            color: #444;
        }

        .feature i {
            color: var(--teal);
            font-size: .9rem;
        }

        .buy-btn {
            margin-top: 2rem;
            width: 100%;
            border: none;
            background: var(--grad);
            color: white;
            padding: .95rem;
            border-radius: 12px;
            font-size: .95rem;
            font-weight: 700;
            cursor: pointer;
            transition: .2s;
        }

        .buy-btn:hover {
            opacity: .9;
        }

        .loading {
            opacity: .6;
            pointer-events: none;
        }

        @media(max-width:768px){

            .product-preview{
                flex-direction: column;
                align-items: flex-start;
            }

            .product-preview img{
                width: 100%;
                height: 240px;
            }

            .hero h1{
                font-size: 2rem;
            }
        }

    </style>
</head>

<body>

<?php include __DIR__ . '/../../shared/components/navbar.php'; ?>

<div class="hero">
    <h1>Boost Your Product Reach</h1>
    <p>Choose the best promotion plan and get more buyers instantly.</p>
</div>

<div class="container">

    <div class="product-preview">

        <?php
            $display_image = !empty($product['image'])
                ? $product['image']
                : BASE_URL . 'assets/images/default-placeholder.png';
        ?>

        <img src="<?= htmlspecialchars($display_image) ?>">

        <div class="product-info">

            <h2>
                <?= htmlspecialchars($product['title']) ?>
            </h2>

            <p>
                <?= htmlspecialchars(substr($product['description'],0,180)) ?>...
            </p>

            <div class="product-price">
                ₹<?= number_format($product['price']) ?>
            </div>

        </div>

    </div>

    <h2 class="section-title">
        Choose Your Promotion Plan
    </h2>

    <div class="plans-grid">

        <?php foreach($plans as $plan): ?>

            <div class="plan-card <?= $plan['recommended'] ? 'recommended' : '' ?>">

                <div class="badge">
                    <i class="fa-solid fa-crown"></i>
                    <?= htmlspecialchars($plan['badge']) ?>
                </div>

                <div class="plan-name">
                    <?= htmlspecialchars($plan['name']) ?>
                </div>

                <div class="plan-price">
                    ₹<?= number_format($plan['price']) ?>
                </div>

                <div class="plan-duration">
                    Duration: <?= htmlspecialchars($plan['duration']) ?>
                </div>

                <div class="features">

                    <?php foreach($plan['features'] as $feature): ?>

                        <div class="feature">
                            <i class="fa-solid fa-check"></i>
                            <?= htmlspecialchars($feature) ?>
                        </div>

                    <?php endforeach; ?>

                </div>

                <button
                    class="buy-btn buyPlanBtn"
                    data-plan-id="<?= $plan['id'] ?>"
                    data-plan-name="<?= htmlspecialchars($plan['name']) ?>"
                    data-price="<?= $plan['price'] ?>"
                >

                    <?php if($plan['price'] <= 0): ?>
                        Activate Free Plan
                    <?php else: ?>
                        Buy Now
                    <?php endif; ?>

                </button>

            </div>

        <?php endforeach; ?>

    </div>

</div>

<script>
const PRODUCT_ID = <?= (int)$product_id ?>;
</script>

<script src="<?= BASE_URL ?>../modules/payments/payment_ajax.js"></script>

</body>
</html>