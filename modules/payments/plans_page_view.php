<?php
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boost Your Ad | BikriBazaar</title>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
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

        .hero-boost {
            background: var(--grad);
            padding: 3rem 1.5rem;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .hero-boost::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse at 80% 30%, rgba(255,255,255,0.1) 0%, transparent 60%);
            pointer-events: none;
        }

        .hero-boost h1 {
            font-size: 2.2rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            position: relative;
        }

        .hero-boost p {
            font-size: 1rem;
            opacity: 0.9;
        }

        .container-boost {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1.5rem;
        }

        .back-btn-wrapper {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--surface);
            color: var(--primary);
            padding: 0.5rem 1.2rem;
            border-radius: 40px;
            font-weight: 600;
            font-size: 0.85rem;
            text-decoration: none;
            transition: all 0.2s;
            border: 1px solid var(--border);
        }

        .back-btn:hover {
            background: var(--border);
            transform: translateX(-3px);
        }

        .product-preview {
            margin-bottom: 2rem;
        }

        .product-card {
            background: var(--card-bg);
            border-radius: 20px;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 2rem;
            box-shadow: 0 8px 20px  #c2cae9;
            border: 1px solid var(--border);
            transition: transform 0.2s;
        }

        .product-card:hover {
            transform: translateY(-3px);
        }

        .product-image img {
            width: 160px;
            height: 160px;
            object-fit: cover;
            border-radius: 16px;
            background: var(--surface);
        }

        .product-info h2 {
            font-size: 1.5rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
        }

        .product-info p {
            color: var(--muted);
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .product-info h3 {
            font-size: 1.4rem;
            color: var(--primary);
            font-weight: 800;
        }

        .section-title {
            font-size: 1.6rem;
            font-weight: 800;
            margin: 2rem 0 1.5rem;
            text-align: center;
        }

        .plans-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .plan-card {
            background: var(--card-bg);
            border-radius: 20px;
            padding: 1.5rem;
            box-shadow: 0 8px 20px #a2a5ab;
            border: 1px solid #cbcbcb;
            transition: all 0.25s;
        }

        .plan-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 28px rgba(26,63,196,0.1);
            border-color: var(--teal);
        }

        .badge {
            display: inline-block;
            background: var(--grad);
            color: white;
            padding: 0.2rem 0.8rem;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .plan-name {
            font-size: 1.5rem;
            font-weight: 800;
            margin-bottom: 0.3rem;
        }

        .price {
            font-size: 1.8rem;
            font-weight: 800;
            color: #16a34a;
        }

        .duration {
            color: var(--muted);
            font-size: 0.8rem;
            margin-bottom: 1rem;
        }

        .features {
            list-style: none;
            margin: 1rem 0 1.5rem;
        }

        .features li {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.6rem;
            font-size: 0.85rem;
        }

        .features li i {
            color: #16a34a;
            width: 18px;
        }

        .buy-btn {
            width: 100%;
            background: var(--grad);
            border: none;
            padding: 0.7rem;
            border-radius: 40px;
            color: white;
            font-weight: 700;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .buy-btn:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }

        .extra-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin: 2rem 0;
        }

        .extra-card {
            background: var(--card-bg);
            border-radius: 20px;
            padding: 1.5rem;
            text-align: center;
            transition: 0.2s;
             border: 1px solid #cbcbcb;
        }

        .extra-card:hover {
            transform: translateY(-4px);
            border-color: var(--teal);
        }

        .extra-card i {
            font-size: 2rem;
            color: var(--primary);
            margin-bottom: 0.8rem;
        }

        .extra-card h3 {
            font-size: 1.2rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
        }

        .extra-card p {
            color: var(--muted);
            font-size: 0.85rem;
        }

        @media (max-width: 768px) {
            .product-card {
                flex-direction: column;
                text-align: center;
            }
            .hero-boost h1 {
                font-size: 1.8rem;
            }
            .section-title {
                font-size: 1.4rem;
            }
        }
    </style>
</head>

<body>

<!-- SHARED NAVBAR -->
<?php include __DIR__ . '/../../shared/components/navbar.php'; ?>

<!-- HERO -->
<section class="hero-boost">
    <h1>Boost Your Product Reach</h1>
    <p>Choose the right promotion plan and get more buyers instantly.</p>
</section>

<!-- BACK BUTTON -->
<div class="back-btn-wrapper">
    <a href="javascript:history.back()" class="back-btn">
        <i class="fa-solid fa-arrow-left"></i> Back to Product
    </a>
</div>

<div class="container-boost">

    <!-- PRODUCT PREVIEW -->
    <div class="product-preview">
        <div class="product-card">
            <div class="product-image">
                <img src="<?php echo htmlspecialchars($productImage['image_path'] ?? 'assets/images/placeholder.png'); ?>" alt="Product Image">
            </div>
            <div class="product-info">
                <h2><?php echo htmlspecialchars($product['title'] ?? 'Product Title'); ?></h2>
                <p><?php echo substr(htmlspecialchars($product['description'] ?? ''), 0, 120); ?>...</p>
                <h3>₹<?php echo number_format($product['price'] ?? 0); ?></h3>
            </div>
        </div>
    </div>

    <!-- PLANS SECTION -->
    <h2 class="section-title">Choose Your Promotion Plan</h2>
    <div class="plans-grid">
        <?php foreach($plans as $planKey => $plan): ?>
            <div class="plan-card">
                <div class="badge"><?php echo htmlspecialchars($plan['badge']); ?></div>
                <div class="plan-name"><?php echo htmlspecialchars($plan['name']); ?></div>
                <div class="price">₹<?php echo htmlspecialchars($plan['price']); ?></div>
                <div class="duration"><?php echo htmlspecialchars($plan['duration']); ?> Days Access</div>
                <ul class="features">
                    <?php if($planKey == 'demo'): ?>
                        <li><i class="fa-solid fa-check-circle"></i> Testing Plan</li>
                        <li><i class="fa-solid fa-check-circle"></i> Instant Confirmation Mail</li>
                        <li><i class="fa-solid fa-check-circle"></i> Instant Expiry Mail</li>
                        <li><i class="fa-solid fa-check-circle"></i> 1 Day Validity</li>
                    <?php elseif($planKey == 'basic'): ?>
                        <li><i class="fa-solid fa-check-circle"></i> Better Search Visibility</li>
                        <li><i class="fa-solid fa-check-circle"></i> Priority Listing</li>
                        <li><i class="fa-solid fa-check-circle"></i> More Buyer Reach</li>
                        <li><i class="fa-solid fa-check-circle"></i> 30 Days Duration</li>
                    <?php elseif($planKey == 'special'): ?>
                        <li><i class="fa-solid fa-check-circle"></i> Homepage Visibility</li>
                        <li><i class="fa-solid fa-check-circle"></i> Category Priority</li>
                        <li><i class="fa-solid fa-check-circle"></i> Better Buyer Engagement</li>
                        <li><i class="fa-solid fa-check-circle"></i> 30 Days Duration</li>
                    <?php elseif($planKey == 'premium'): ?>
                        <li><i class="fa-solid fa-check-circle"></i> Featured Placement</li>
                        <li><i class="fa-solid fa-check-circle"></i> Top Search Ranking</li>
                        <li><i class="fa-solid fa-check-circle"></i> Premium Exposure</li>
                        <li><i class="fa-solid fa-check-circle"></i> Maximum Visibility</li>
                    <?php endif; ?>
                </ul>
                <button class="buy-btn" onclick="startPayment('<?php echo $planKey; ?>')">Buy Now</button>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- WHY BOOST -->
    <h2 class="section-title">Why Boost Your Ads?</h2>
    <div class="extra-grid">
        <div class="extra-card">
            <i class="fa-solid fa-bolt"></i>
            <h3>Faster Sales</h3>
            <p>Reach more buyers quickly and sell your products faster with boosted visibility.</p>
        </div>
        <div class="extra-card">
            <i class="fa-solid fa-chart-line"></i>
            <h3>More Reach</h3>
            <p>Your ads appear higher in search results and get better impressions.</p>
        </div>
        <div class="extra-card">
            <i class="fa-solid fa-crown"></i>
            <h3>Premium Exposure</h3>
            <p>Stand out from normal sellers and gain more customer trust.</p>
        </div>
        <div class="extra-card">
            <i class="fa-solid fa-store"></i>
            <h3>Business Growth</h3>
            <p>Perfect for frequent sellers and business accounts wanting maximum reach.</p>
        </div>
    </div>

</div>

<!-- SHARED FOOTER -->
<?php include __DIR__ . '/../../shared/components/footer.php'; ?>

<script>
function startPayment(plan){
    fetch('../modules/payments/ajax/create_order_ajax.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'plan=' + encodeURIComponent(plan)
    })
    .then(response => response.json())
    .then(data => {
        if(data.status !== "success"){
            alert(data.message);
            return;
        }
        let options = {
            key: data.key,
            amount: data.amount,
            currency: "INR",
            name: "BikriBazaar",
            description: data.plan_name + " Plan Purchase",
            order_id: data.order_id,
            handler: function(response){
                window.location.href = "../modules/payments/verify_payment.php?" +
                    "plan=" + encodeURIComponent(data.plan_key) +
                    "&product_id=" + encodeURIComponent(data.product_id) +
                    "&razorpay_payment_id=" + encodeURIComponent(response.razorpay_payment_id) +
                    "&razorpay_order_id=" + encodeURIComponent(response.razorpay_order_id) +
                    "&razorpay_signature=" + encodeURIComponent(response.razorpay_signature);
            },
            theme: { color: "#2563eb" }
        };
        let rzp = new Razorpay(options);
        rzp.open();
    })
    .catch(error => {
        console.error(error);
        alert("Something went wrong.");
    });
}
</script>

</body>
</html>