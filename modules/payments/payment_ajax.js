const buyButtons = document.querySelectorAll('.buyPlanBtn');

buyButtons.forEach(button => {

    button.addEventListener('click', async function () {

        const btn = this;

        const planId = btn.dataset.planId;
        const planName = btn.dataset.planName;
        const price = btn.dataset.price;

        // =========================
        // CHECK PRODUCT ID
        // =========================

        if (!PRODUCT_ID || PRODUCT_ID <= 0) {

            alert('Invalid Product ID');

            return;
        }

        // =========================
        // FREE PLAN DIRECT ACTIVATE
        // =========================

        if (parseFloat(price) <= 0) {

            try {

                btn.innerText = 'Activating...';
                btn.classList.add('loading');

                const response = await fetch(
                    'verify_payment.php',
                    {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: new URLSearchParams({
                            product_id: PRODUCT_ID,
                            plan_id: planId,
                            plan_name: planName,
                            amount: 0,
                            payment_mode: 'free'
                        })
                    }
                );

                const data = await response.json();

                if (data.success) {

                    alert('Free Plan Activated Successfully');

                    window.location.href =
                        '../../public/product.php?id=' + PRODUCT_ID;

                } else {

                    alert(data.message || 'Something went wrong');

                    btn.innerText = 'Activate Free Plan';
                    btn.classList.remove('loading');
                }

            } catch (error) {

                console.error(error);

                alert('Server Error');

                btn.innerText = 'Activate Free Plan';
                btn.classList.remove('loading');
            }

            return;
        }

        // =========================
        // PAID PLANS
        // =========================

        try {

            btn.innerText = 'Processing...';
            btn.classList.add('loading');

            // =========================
            // CREATE ORDER AJAX
            // =========================

            const response = await fetch(
                'create_order.php',
                {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({
                        product_id: PRODUCT_ID,
                        plan_id: planId,
                        plan_name: planName,
                        amount: price
                    })
                }
            );

            const data = await response.json();

            console.log(data);

            if (!data.success) {

                alert(data.message || 'Order Creation Failed');

                btn.innerText = 'Buy Now';
                btn.classList.remove('loading');

                return;
            }

            // =========================
            // RAZORPAY OPTIONS
            // =========================

            const options = {

                key: data.key,

                amount: data.amount,

                currency: 'INR',

                name: 'BikriBazaar',

                description: planName,

                image: '',

                order_id: data.order_id,

                theme: {
                    color: '#1a3fc4'
                },

                handler: async function (response) {

                    try {

                        // =========================
                        // VERIFY PAYMENT AJAX
                        // =========================

                        const verifyResponse = await fetch(
                            'verify_payment.php',
                            {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded'
                                },
                                body: new URLSearchParams({

                                    product_id: PRODUCT_ID,

                                    plan_id: planId,

                                    plan_name: planName,

                                    amount: price,

                                    razorpay_payment_id:
                                        response.razorpay_payment_id,

                                    razorpay_order_id:
                                        response.razorpay_order_id,

                                    razorpay_signature:
                                        response.razorpay_signature
                                })
                            }
                        );

                        const verifyData =
                            await verifyResponse.json();

                        console.log(verifyData);

                        if (verifyData.success) {

                            alert(
                                planName +
                                ' Activated Successfully'
                            );

                            window.location.href =
                                '../../public/product.php?id=' +
                                PRODUCT_ID;

                        } else {

                            alert(
                                verifyData.message ||
                                'Payment Verification Failed'
                            );

                            btn.innerText = 'Buy Now';
                            btn.classList.remove('loading');
                        }

                    } catch (error) {

                        console.error(error);

                        alert('Verification Server Error');

                        btn.innerText = 'Buy Now';
                        btn.classList.remove('loading');
                    }
                },

                modal: {

                    ondismiss: function () {

                        btn.innerText = 'Buy Now';
                        btn.classList.remove('loading');
                    }
                },

                prefill: {

                    name: '',

                    email: '',

                    contact: ''
                }
            };

            // =========================
            // OPEN RAZORPAY
            // =========================

            const razorpay = new Razorpay(options);

            razorpay.open();

        } catch (error) {

            console.error(error);

            alert('Server Error');

            btn.innerText = 'Buy Now';
            btn.classList.remove('loading');
        }
    });
});