<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../../vendor/autoload.php';

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

$mail = new PHPMailer(true);

try {

    $mail->isSMTP();

    $mail->Host = $_ENV['SMTP_HOST'];

    $mail->SMTPAuth = true;

    $mail->Username = $_ENV['SMTP_USERNAME'];

    $mail->Password = $_ENV['SMTP_PASSWORD'];

    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

    $mail->Port = (int)$_ENV['SMTP_PORT'];

    $mail->setFrom(

        $_ENV['SMTP_USERNAME'],

        'BikriBazaar'

    );

    $mail->addAddress(

        $user_email,

        $user_name

    );

    $mail->isHTML(true);

    if ($plan_key === "demo") {

        $mail->Subject =
            'Demo Boost Plan Expiry Notice';

    } else {

        $mail->Subject =
            'Your Boost Plan Will Expire Soon';
    }

    $mail->Body = "

    <div style='
        max-width:700px;
        margin:auto;
        font-family:Arial,sans-serif;
        background:#f8fafc;
        padding:40px;
        border-radius:20px;
    '>

        <div style='text-align:center;margin-bottom:30px;'>

            <h1 style='color:#2563eb;'>
                BikriBazaar
            </h1>

            <p style='
                color:#64748b;
                font-size:16px;
            '>

                Your boost plan expiry reminder.

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

                Your boosted product plan is nearing expiry.

                Please renew your plan to continue getting
                higher visibility and buyer reach.

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

        </div>

    </div>

    ";

    if($mail->send()){

        echo "EXPIRY MAIL SENT";

    }else{

        echo $mail->ErrorInfo;
    }

} catch (Exception $e) {

    die($mail->ErrorInfo);
}