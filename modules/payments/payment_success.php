<?php

session_start();

require_once __DIR__ . '/../../shared/config.php';
require_once __DIR__ . '/../../shared/db.php';

// =========================
// VALIDATE LOGIN
// =========================

if (!isset($_SESSION['user_id'])) {

    header("Location: " . BASE_URL . "login.php");
    exit;
}

// =========================
// GET PRODUCT ID
// =========================

$product_id = isset($_GET['product_id'])
    ? intval($_GET['product_id'])
    : 0;

// =========================
// FETCH PRODUCT
// =========================

$product = null;

if ($product_id > 0) {

    $sql = "
        SELECT 
            products.*,

            (
                SELECT image_path
                FROM product_images
                WHERE product_id = products.id
                ORDER BY id ASC
                LIMIT 1
            ) AS image

        FROM products
        WHERE id = ?
        LIMIT 1
    ";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param("i", $product_id);

    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {

        $product = $result->fetch_assoc();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        Payment Successful - BikriBazaar
    </title>

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>

        :root {
            --primary: #1a3fc4;
            --primary-dark: #1530a0;
            --teal: #0ea5a0;
            --surface: #f4f7ff;
            --card-bg: #ffffff;
            --text: #1a1a2e;
            --muted: #6b7280;
            --border: #dde4f5;
            --grad: linear-gradient(
                135deg,
                #1a3fc4 0%,
                #0ea5a0 100%
            );
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

        .success-wrapper {

            min-height: 100vh;

            display: flex;

            align-items: center;

            justify-content: center;

            padding: 2rem;
        }

        .success-card {

            width: 100%;

            max-width: 650px;

            background: white;

            border-radius: 25px;

            padding: 2.5rem;

            text-align: center;

            box-shadow: 0 15px 40px rgba(0,0,0,.08);

            border: 1px solid var(--border);

            position: relative;

            overflow: hidden;
        }

        .success-card::before {

            content: '';

            position: absolute;

            top: -120px;

            right: -120px;

            width: 250px;

            height: 250px;

            background: rgba(14,165,160,.08);

            border-radius: 50%;
        }

        .success-icon {

            width: 110px;

            height: 110px;

            margin: auto;

            border-radius: 50%;

            background: rgba(14,165,160,.12);

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 3rem;

            color: var(--teal);

            margin-bottom: 1.5rem;
        }

        .success-title {

            font-size: 2rem;

            font-weight: 800;

            margin-bottom: .8rem;
        }

        .success-text {

            color: var(--muted);

            line-height: 1.7;

            font-size: .98rem;
        }

        .product-card {

            margin-top: 2rem;

            display: flex;

            gap: 1rem;

            align-items: center;

            background: #f9fbff;

            border: 1px solid var(--border);

            border-radius: 16px;

            padding: 1rem;

            text-align: left;
        }

        .product-card img {

            width: 120px;

            height: 100px;

            object-fit: cover;

            border-radius: 12px;
        }

        .product-title {

            font-size: 1.1rem;

            font-weight: 700;
        }

        .product-price {

            margin-top: .5rem;

            color: var(--primary);

            font-size: 1.1rem;

            font-weight: 800;
        }

        .boost-badge {

            margin-top: .7rem;

            display: inline-flex;

            align-items: center;

            gap: .5rem;

            background: var(--grad);

            color: white;

            padding: .45rem .85rem;

            border-radius: 30px;

            font-size: .75rem;

            font-weight: 700;
        }

        .btn-group {

            margin-top: 2rem;

            display: flex;

            gap: 1rem;

            justify-content: center;

            flex-wrap: wrap;
        }

        .btn {

            border: none;

            padding: .95rem 1.5rem;

            border-radius: 12px;

            cursor: pointer;

            font-size: .95rem;

            font-weight: 700;

            text-decoration: none;

            transition: .2s;
        }

        .btn-primary {

            background: var(--grad);

            color: white;
        }

        .btn-secondary {

            background: white;

            border: 2px solid var(--border);

            color: var(--text);
        }

        .btn:hover {

            transform: translateY(-2px);
        }

        @media(max-width:768px){

            .product-card{

                flex-direction: column;

                text-align: center;
            }

            .product-card img{

                width: 100%;

                height: 220px;
            }
        }

    </style>

</head>

<body>

<div class="success-wrapper">

    <div class="success-card">

        <div class="success-icon">
            <i class="fa-solid fa-check"></i>
        </div>

        <div class="success-title">
            Payment Successful
        </div>

        <div class="success-text">

            Your promotion plan has been activated successfully.
            Your product visibility will now increase across
            BikriBazaar.

        </div>

        <?php if($product): ?>

            <?php

                $display_image = !empty($product['image'])
                    ? $product['image']
                    : BASE_URL .
                      'assets/images/default-placeholder.png';

            ?>

            <div class="product-card">

                <img src="<?= htmlspecialchars($display_image) ?>">

                <div>

                    <div class="product-title">
                        <?= htmlspecialchars($product['title']) ?>
                    </div>

                    <div class="product-price">
                        ₹<?= number_format($product['price']) ?>
                    </div>

                    <div class="boost-badge">

                        <i class="fa-solid fa-bolt"></i>

                        BOOST ACTIVATED

                    </div>

                </div>

            </div>

        <?php endif; ?>

        <div class="btn-group">

            <a
                href="<?= BASE_URL ?>product.php?id=<?= $product_id ?>"
                class="btn btn-primary"
            >

                View Product

            </a>

            <a
                href="<?= BASE_URL ?>dashboard.php"
                class="btn btn-secondary"
            >

                Go To Dashboard

            </a>

        </div>

    </div>

</div>

</body>
</html>