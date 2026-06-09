# BikriBazaar (OLX Replica)

BikriBazaar is a web-based marketplace platform developed to connect buyers and sellers efficiently. It simplifies the traditional buying and selling process by providing a centralized digital platform where users can post advertisements, search for products, communicate directly, and manage transactions securely.

---

## 🚀 Key Features

* **Secure Authentication:** User registration and login with email-based OTP verification and password recovery.
* **Product Management:** Seamless posting, editing, and deletion of classified ads with multiple image uploads.
* **Premium Advertisements:** Integrated payment gateway for users to purchase Featured, Urgent, or Boosted ad visibility plans.
* **Real-Time Chat System:** Direct, built-in communication channel between buyers and sellers.
* **Personalized Experience:** Ability for users to save favorite products and manage their personal profiles.
* **Admin Dashboard:** Centralized control for administrators to manage users, monitor reported ads, and track system activity logs.
* **Security Measures:** Session management, input validation, and password encryption to ensure platform reliability.

---

## 🛠️ Technology Stack

| Category | Technologies Used |
| :--- | :--- |
| **Frontend** | HTML5, CSS3, JavaScript, Bootstrap |
| **Backend** | PHP |
| **Database** | MySQL |
| **Cloud Storage** | Cloudinary (for product image management) |
| **Payment Gateway**| Razorpay (for premium ad subscriptions) |
| **Email Service** | PHPMailer (for OTPs and system notifications) |

---

## ⚙️ System Requirements

* **Operating System:** Windows, macOS, or Linux
* **Local Web Server:** XAMPP, WAMP, or equivalent
* **Hardware (Minimum):** Intel i3 Processor, 4 GB RAM, 20 GB Storage

---

## 📁 Project Structure

```text
OLX-REPLICA/
├── database/          # SQL dump files and database schemas
├── modules/           # Core feature logic (Auth, Products, Messaging, Admin)
├── public/            # Publicly accessible assets (CSS, JS, Images)
├── shared/            # Reusable components (Header, Footer, db.php connection)
├── vendor/            # Composer dependencies
├── .env               # Environment variables (Database credentials, API keys)
├── composer.json      # PHP dependencies configuration
└── README.md          # Project documentation

---

## 📐 System Design
![System Design](./asset/System%20Design.png)

## 🖥️ Landing Page
![BikriBazaar Landing Page](./asset/Landing%20Page.png)



