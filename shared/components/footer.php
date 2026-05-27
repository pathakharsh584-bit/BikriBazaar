<?php
// shared/components/footer.php
?>


<footer class="site-footer">
    <div class="footer-content">
        <div class="footer-logo">
            <img src="assets/images/logo.png" alt="BikriBazaar" class="footer-logo-img">
            <span>Bikri<span>Bazaar</span></span>
        </div>
        <div class="footer-links">
            <a href="index.php">Home</a>
            <a href="about.php">About</a>
            <a href="contact.php">Contact</a>
            <a href="privacy.php">Privacy Policy</a>
            <a href="terms.php">Terms of Use</a>
        </div>
        <div class="footer-social">
            <a href="#"><i class="fab fa-facebook-f"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
            <a href="#"><i class="fab fa-youtube"></i></a>
        </div>
        <div class="footer-copyright">
            &copy; <?php echo date('Y'); ?> BikriBazaar. All rights reserved.
        </div>
    </div>
</footer>

<style>
    .site-footer {
        background: #0d1b5e;
        color: #d1d5db;
        padding: 2rem 0;
        margin-top: 3rem;
        text-align: center;
        font-size: 0.85rem;
    }
    .footer-content {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }
    .footer-logo {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        margin-bottom: 1rem;
    }
    .footer-logo-img {
        height: 40px;
        width: 40px;
        border-radius: 50%;
        object-fit: cover;
    }
    .footer-logo span {
        font-size: 1.3rem;
        font-weight: 800;
        color: #1a3fc4;
    }
    .footer-logo span span {
        color: #0ea5a0;
    }
    .footer-links {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 1rem;
        margin-bottom: 1rem;
    }
    .footer-links a {
        color: #9ca3af;
        text-decoration: none;
        transition: color 0.2s;
    }
    .footer-links a:hover {
        color: #0ea5a0;
    }
    .footer-social {
        display: flex;
        justify-content: center;
        gap: 1rem;
        margin-bottom: 1rem;
    }
    .footer-social a {
        background: rgba(255,255,255,0.08);
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #9ca3af;
        transition: 0.2s;
    }
    .footer-social a:hover {
        background: #0ea5a0;
        color: #fff;
    }
    .footer-copyright {
        font-size: 0.75rem;
        color: #6b7280;
        border-top: 1px solid rgba(255,255,255,0.1);
        padding-top: 1rem;
        margin-top: 0.5rem;
    }
   
</style>