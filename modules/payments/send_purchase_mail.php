<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../../vendor/autoload.php';

// =========================
// USER DETAILS
// =========================

$user_sql = "

    SELECT name, email
    FROM users
    WHERE id = ?

";

$user_stmt = $conn->prepare($user_sql);

$user_stmt->bind_param("i", $user_id);

$user_stmt->execute();

$user_result = $user_stmt->get_result();

$user = $user_result->fetch_assoc();

if (!$user) {

    return;
}

$user_name = $user['name'];

$user_email = $user['email'];

// =========================
// PRODUCT DETAILS
// =========================

$product_sql = "

    SELECT title
    FROM products
    WHERE id = ?

";

$product_stmt = $conn->prepare($product_sql);

$product_stmt->bind_param("i", $product_id);

$product_stmt->execute();

$product_result = $product_stmt->get_result();

$product_data = $product_result->fetch_assoc();

$product_title = $product_data['title'] ?? 'Product';

// =========================
// MAILER
// =========================

$mail = new PHPMailer(true);

try {

    $mail->isSMTP();

    $mail->Host = $_ENV['SMTP_HOST'];

    $mail->SMTPAuth = true;

    $mail->Username = $_ENV['SMTP_USERNAME'];

    $mail->Password = $_ENV['SMTP_PASSWORD'];

    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

    $mail->Port = (int)$_ENV['SMTP_PORT'];

    // =========================
    // SENDER
    // =========================

    $mail->setFrom(

        $_ENV['SMTP_USERNAME'],

        'BikriBazaar'

    );

    // =========================
    // RECEIVER
    // =========================

    $mail->addAddress(

        $user_email,

        $user_name

    );

    // =========================
    // EMAIL
    // =========================

    $mail->isHTML(true);

    $mail->Subject =
        'Your Boost Plan Has Been Activated';

    // =========================
    // BODY
    // =========================
      
$start_date = date(
    'd M Y, h:i A',
    strtotime($start_date)
);

$boost_expiry = date(
    'd M Y, h:i A',
    strtotime($boost_expiry)
);
    $mail->Body = "

    <div style='
        max-width:700px;
        margin:auto;
        font-family:Arial,sans-serif;
        background:#f8fafc;
        padding:40px;
        border-radius:20px;
    '>

        <div style='
            text-align:center;
            margin-bottom:30px;
        '>

            <h1 style='
                color:#2563eb;
                margin-bottom:10px;
            '>
                BikriBazaar
            </h1>

            <p style='
                color:#64748b;
                font-size:16px;
            '>

                Your boost plan has been activated successfully.

            </p>

        </div>

        <div style='
            background:white;
            padding:30px;
            border-radius:16px;
            border:1px solid #dbeafe;
        '>

            <h2 style='
                margin-bottom:20px;
                color:#0f172a;
            '>

                Hello {$user_name},

            </h2>

            <p style='
                color:#475569;
                line-height:1.8;
                margin-bottom:25px;
            '>

                Your product boost payment was completed successfully.
                Your advertisement is now boosted on BikriBazaar.

            </p>

            <table style='
                width:100%;
                border-collapse:collapse;
            '>

                <tr>

                    <td style='
                        padding:14px;
                        border:1px solid #e2e8f0;
                        font-weight:bold;
                    '>

                        Product

                    </td>

                    <td style='
                        padding:14px;
                        border:1px solid #e2e8f0;
                    '>

                        {$product_title}

                    </td>

                </tr>

                <tr>

                    <td style='
                        padding:14px;
                        border:1px solid #e2e8f0;
                        font-weight:bold;
                    '>

                        Plan

                    </td>

                    <td style='
                        padding:14px;
                        border:1px solid #e2e8f0;
                    '>

                        {$plan_name}

                    </td>

                </tr>

                <tr>

                    <td style='
                        padding:14px;
                        border:1px solid #e2e8f0;
                        font-weight:bold;
                    '>

                        Amount Paid

                    </td>

                    <td style='
                        padding:14px;
                        border:1px solid #e2e8f0;
                    '>

                        ₹{$amount}

                    </td>

                </tr>

                <tr>

                    <td style='
                        padding:14px;
                        border:1px solid #e2e8f0;
                        font-weight:bold;
                    '>

                        Start Date

                    </td>

                    <td style='
                        padding:14px;
                        border:1px solid #e2e8f0;
                    '>

                        {$start_date}

                    </td>

                </tr>

                <tr>

                    <td style='
                        padding:14px;
                        border:1px solid #e2e8f0;
                        font-weight:bold;
                    '>

                        Expiry Date

                    </td>

                    <td style='
                        padding:14px;
                        border:1px solid #e2e8f0;
                    '>

                        {$boost_expiry}

                    </td>

                </tr>

            </table>

            <div style='
                text-align:center;
                margin-top:35px;
            '>

                <a href='" . BASE_URL . "index.php'
                   style='
                        display:inline-block;
                        padding:14px 30px;
                        background:#2563eb;
                        color:white;
                        text-decoration:none;
                        border-radius:10px;
                        font-weight:bold;
                   '>

                    Visit BikriBazaar

                </a>

            </div>

        </div>

    </div>

    ";

    if($mail->send()){

        echo "PURCHASE MAIL SENT";

    }else{

        echo $mail->ErrorInfo;
    }

} catch (Exception $e) {

    die($mail->ErrorInfo);
}