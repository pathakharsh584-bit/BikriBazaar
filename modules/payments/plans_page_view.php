<?php

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Boost Plans | BikriBazaar</title>

    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:Arial,sans-serif;
        }

        body{
            background:#eef1f8;
            color:#222;
        }

        a{
            text-decoration:none;
        }

        /* NAVBAR */

        .navbar{
            width:100%;
            background:white;
            padding:18px 50px;
            display:flex;
            justify-content:space-between;
            align-items:center;
            box-shadow:0 2px 10px rgba(0,0,0,0.08);
        }

        .logo{
            font-size:48px;
            font-weight:800;
            color:#1e40af;
        }

        .logo span{
            color:#0ea5a4;
        }

        .nav-right{
            display:flex;
            align-items:center;
            gap:18px;
        }

        .home-btn{
            display:flex;
            align-items:center;
            gap:8px;
            color:#111827;
            font-size:16px;
            font-weight:600;
        }

        .sell-btn{
            background:linear-gradient(to right,#2563eb,#06b6d4);
            color:white;
            padding:12px 28px;
            border-radius:10px;
            font-size:16px;
            font-weight:700;
        }

        .avatar{
            width:52px;
            height:52px;
            border-radius:50%;
            background:#2563eb;
            color:white;
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:22px;
            font-weight:bold;
            border:3px solid #dbeafe;
        }

        /* HERO */

        .hero{
            background:linear-gradient(to right,#1d4ed8,#06b6d4);
            color:white;
            text-align:center;
            padding:90px 20px;
        }

        .hero h1{
            font-size:72px;
            margin-bottom:20px;
            font-weight:800;
        }

        .hero p{
            font-size:22px;
            opacity:0.95;
        }

        /* PRODUCT PREVIEW */

        .product-section{
            max-width:1850px;
            margin:50px auto;
            padding:0 25px;
        }

        .product-card{
            background:white;
            border-radius:30px;
            padding:35px;
            display:flex;
            align-items:center;
            gap:35px;
            box-shadow:0 4px 15px rgba(0,0,0,0.06);
            border:1px solid #dbe3f3;
        }

        .product-image img{
            width:330px;
            height:330px;
            object-fit:cover;
            border-radius:20px;
        }

        .product-info{
            flex:1;
        }

        .product-info h2{
            font-size:58px;
            color:#0f172a;
            margin-bottom:18px;
            font-weight:800;
        }

        .product-info p{
            font-size:28px;
            color:#64748b;
            margin-bottom:30px;
        }

        .product-info h3{
            font-size:55px;
            color:#1d4ed8;
            font-weight:800;
        }

        /* PLAN SECTION */

        .plans-title{
            max-width:1850px;
            margin:70px auto 30px;
            padding:0 25px;
        }

        .plans-title h2{
            font-size:65px;
            font-weight:800;
            color:#0f172a;
        }

        .plans-section{
            max-width:1850px;
            margin:auto;
            padding:0 25px;
        }

        .plans-grid{
            display:grid;
            grid-template-columns:repeat(auto-fit,minmax(340px,1fr));
            gap:35px;
        }

        .plan-card{
            background:white;
            border-radius:25px;
            padding:40px 30px;
            box-shadow:0 10px 25px rgba(0,0,0,0.08);
            transition:0.3s;
            border:1px solid #dbe3f3;
        }

        .plan-card:hover{
            transform:translateY(-8px);
        }

        .badge{
            display:inline-block;
            background:#1d4ed8;
            color:white;
            padding:8px 18px;
            border-radius:30px;
            font-size:14px;
            font-weight:700;
            margin-bottom:20px;
        }

        .plan-name{
            font-size:42px;
            font-weight:800;
            margin-bottom:15px;
            color:#0f172a;
        }

        .price{
            font-size:60px;
            color:#16a34a;
            font-weight:800;
            margin-bottom:10px;
        }

        .duration{
            color:#64748b;
            margin-bottom:25px;
            font-size:18px;
        }

        .features{
            list-style:none;
            margin-bottom:30px;
        }

        .features li{
            margin-bottom:16px;
            display:flex;
            align-items:center;
            gap:10px;
            font-size:17px;
            color:#334155;
        }

        .features li i{
            color:#16a34a;
        }

        .buy-btn{
            width:100%;
            padding:16px;
            border:none;
            border-radius:12px;
            background:linear-gradient(to right,#2563eb,#06b6d4);
            color:white;
            font-size:18px;
            font-weight:700;
            cursor:pointer;
            transition:0.3s;
        }

        .buy-btn:hover{
            opacity:0.9;
        }

        /* WHY BOOST */

        .extra-section{
            max-width:1850px;
            margin:90px auto;
            padding:0 25px;
        }

        .extra-title{
            text-align:center;
            font-size:60px;
            margin-bottom:60px;
            font-weight:800;
            color:#0f172a;
        }

        .extra-grid{
            display:grid;
            grid-template-columns:repeat(auto-fit,minmax(300px,1fr));
            gap:30px;
        }

        .extra-card{
            background:white;
            border-radius:25px;
            padding:40px 30px;
            text-align:center;
            box-shadow:0 10px 25px rgba(0,0,0,0.08);
        }

        .extra-card i{
            font-size:55px;
            color:#2563eb;
            margin-bottom:20px;
        }

        .extra-card h3{
            font-size:28px;
            margin-bottom:15px;
            color:#0f172a;
        }

        .extra-card p{
            color:#64748b;
            line-height:1.8;
            font-size:17px;
        }

        /* FOOTER */

        footer{
            background:#0f1f6a;
            color:white;
            padding:70px 20px 40px;
            margin-top:100px;
        }

        .footer-logo{
            text-align:center;
            margin-bottom:35px;
        }

        .footer-logo h2{
            font-size:48px;
            font-weight:800;
        }

        .footer-logo span{
            color:#0ea5a4;
        }

        .footer-links{
            display:flex;
            justify-content:center;
            flex-wrap:wrap;
            gap:35px;
            margin-bottom:40px;
        }

        .footer-links a{
            color:#d1d5db;
            font-size:18px;
        }

        .social-icons{
            display:flex;
            justify-content:center;
            gap:25px;
            margin-bottom:50px;
        }

        .social-icons a{
            width:58px;
            height:58px;
            border-radius:15px;
            background:rgba(255,255,255,0.08);
            display:flex;
            align-items:center;
            justify-content:center;
            color:white;
            font-size:22px;
        }

        .footer-bottom{
            border-top:1px solid rgba(255,255,255,0.1);
            padding-top:30px;
            text-align:center;
            color:#cbd5e1;
            font-size:18px;
        }

        @media(max-width:992px){

            .hero h1{
                font-size:52px;
            }

            .product-card{
                flex-direction:column;
                text-align:center;
            }

            .product-image img{
                width:100%;
                max-width:320px;
                height:320px;
            }

            .product-info h2{
                font-size:42px;
            }

            .plans-title h2{
                font-size:42px;
            }

            .extra-title{
                font-size:42px;
            }

        }

        @media(max-width:768px){

            .navbar{
                padding:18px 20px;
            }

            .logo{
                font-size:34px;
            }

            .hero h1{
                font-size:42px;
            }

            .hero p{
                font-size:18px;
            }

            .product-info h2{
                font-size:32px;
            }

            .product-info p{
                font-size:18px;
            }

            .product-info h3{
                font-size:38px;
            }

            .plans-title h2{
                font-size:34px;
            }

            .extra-title{
                font-size:34px;
            }

        }

    </style>

</head>

<body>

<!-- NAVBAR -->

<nav class="navbar">

    <div class="logo">
        Bikri <span>Bazaar</span>
    </div>

    <div class="nav-right">

        <a href="<?php echo BASE_URL; ?>index.php" class="home-btn">
            <i class="fa-solid fa-house"></i>
            Home
        </a>

        <a href="javascript:history.back()" class="sell-btn">
            <i class="fa-solid fa-arrow-left"></i>
            Back
        </a>

        <div class="avatar">
            <?php echo strtoupper(substr($_SESSION['username'] ?? 'H',0,1)); ?>
        </div>

    </div>

</nav>

<!-- HERO -->

<section class="hero">

    <h1>Boost Your Product Reach</h1>

    <p>
        Choose the best promotion plan and get more buyers instantly.
    </p>

</section>

<!-- PRODUCT PREVIEW -->

<section class="product-section">

    <div class="product-card">

        <div class="product-image">

            <img
              src="<?php echo htmlspecialchars($productImage['image_path']); ?>"
                  alt="Product Image"
>

        </div>

        <div class="product-info">

            <h2>
                <?php echo htmlspecialchars($product['title']); ?>
            </h2>

            <p>
                <?php echo substr(htmlspecialchars($product['description']),0,120); ?>...
            </p>

            <h3>
                ₹<?php echo number_format($product['price']); ?>
            </h3>

        </div>

    </div>

</section>

<!-- PLAN TITLE -->

<section class="plans-title">

    <h2>Choose Your Promotion Plan</h2>

</section>

<!-- PLANS -->

<section class="plans-section">

    <div class="plans-grid">

        <?php foreach($plans as $planKey => $plan): ?>

            <div class="plan-card">

                <div class="badge">
                    <?php echo htmlspecialchars($plan['badge']); ?>
                </div>

                <div class="plan-name">
                    <?php echo htmlspecialchars($plan['name']); ?>
                </div>

                <div class="price">
                    ₹<?php echo htmlspecialchars($plan['price']); ?>
                </div>

                <div class="duration">
                    <?php echo htmlspecialchars($plan['duration']); ?> Days Access
                </div>

                <ul class="features">

                    <?php if($planKey == 'demo'): ?>

                        <li><i class="fa-solid fa-check"></i> Testing Plan</li>
                        <li><i class="fa-solid fa-check"></i> Instant Confirmation Mail</li>
                        <li><i class="fa-solid fa-check"></i> Instant Expiry Mail</li>
                        <li><i class="fa-solid fa-check"></i> 1 Day Validity</li>

                    <?php elseif($planKey == 'basic'): ?>

                        <li><i class="fa-solid fa-check"></i> Better Search Visibility</li>
                        <li><i class="fa-solid fa-check"></i> Priority Listing</li>
                        <li><i class="fa-solid fa-check"></i> More Buyer Reach</li>
                        <li><i class="fa-solid fa-check"></i> 30 Days Duration</li>

                    <?php elseif($planKey == 'special'): ?>

                        <li><i class="fa-solid fa-check"></i> Homepage Visibility</li>
                        <li><i class="fa-solid fa-check"></i> Category Priority</li>
                        <li><i class="fa-solid fa-check"></i> Better Buyer Engagement</li>
                        <li><i class="fa-solid fa-check"></i> 30 Days Duration</li>

                    <?php elseif($planKey == 'premium'): ?>

                        <li><i class="fa-solid fa-check"></i> Featured Placement</li>
                        <li><i class="fa-solid fa-check"></i> Top Search Ranking</li>
                        <li><i class="fa-solid fa-check"></i> Premium Exposure</li>
                        <li><i class="fa-solid fa-check"></i> Maximum Visibility</li>

                    <?php endif; ?>

                </ul>

                <button class="buy-btn" onclick="startPayment('<?php echo $planKey; ?>')">
                    Buy Now
                </button>

            </div>

        <?php endforeach; ?>

    </div>

</section>

<!-- WHY BOOST -->

<section class="extra-section">

    <h2 class="extra-title">Why Boost Your Ads?</h2>

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

</section>

<!-- FOOTER -->

<footer>

    <div class="footer-logo">

        <h2>
            Bikri<span>Bazaar</span>
        </h2>

    </div>

    <div class="footer-links">

        <a href="<?php echo BASE_URL; ?>/public/index.php">Home</a>

        <a href="#">About</a>

        <a href="#">Contact</a>

        <a href="#">Privacy Policy</a>

        <a href="#">Terms of Use</a>

    </div>

    <div class="social-icons">

        <a href="#"><i class="fa-brands fa-facebook-f"></i></a>

        <a href="#"><i class="fa-brands fa-instagram"></i></a>

        <a href="#"><i class="fa-brands fa-twitter"></i></a>

        <a href="#"><i class="fa-brands fa-youtube"></i></a>

    </div>

    <div class="footer-bottom">

        © <?php echo date('Y'); ?> BikriBazaar. All rights reserved.

    </div>

</footer>

<script>

function startPayment(plan){

    fetch('../modules/payments/ajax/create_order_ajax.php', {

        method: 'POST',

        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },

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

            handler: function (response){

                window.location.href =
                "../modules/payments/verify_payment.php?" +

                "plan=" + encodeURIComponent(data.plan_key) +

                "&product_id=" + encodeURIComponent(data.product_id) +

                "&razorpay_payment_id=" + encodeURIComponent(response.razorpay_payment_id) +

                "&razorpay_order_id=" + encodeURIComponent(response.razorpay_order_id) +

                "&razorpay_signature=" + encodeURIComponent(response.razorpay_signature);
            },

            theme: {
                color: "#2563eb"
            }

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