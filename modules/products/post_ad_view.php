<?php
session_start();

require_once __DIR__ . '/../../shared/db.php';

if(!isset($_SESSION['user_id'])){
    header("Location: " . BASE_URL . "login.php");
    exit();
}

$message = "";

if($_SERVER['REQUEST_METHOD'] == "POST"){
    require_once __DIR__ . '/product_actions.php';
    // Remember to update your postProduct function in product_actions.php 
    // to handle loop array processing for $_FILES['images'] instead of single 'image'.
    $message = postProduct($conn);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Ad - BikriBazaar</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --primary: #1a3fc4;
            --teal: #0ea5a0;
            --text: #1a1a2e;
            --muted: #6b7280;
            --border: #dde4f5;
        }

        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #1a3fc4 0%, #0ea5a0 100%);
            color: var(--text);
            min-height: 100vh;
        }

        a { text-decoration: none; color: inherit; }

        /* NAVBAR */
        .navbar {
            background: #fff;
            box-shadow: 0 1px 6px rgba(0,0,0,0.08);
            display: flex;
            align-items: center;
            padding: 0 2rem;
            height: 64px;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .logo {
            display: flex;
            align-items: center;
            gap: 9px;
            font-size: 1.4rem;
            font-weight: 800;
            color: var(--primary);
        }
        .logo span { color: var(--teal); }
        .logo-img {
            height: 40px;
            width: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--primary);
        }
        .nav-links {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-left: auto;
        }

        .nav-links a {
            font-size: 0.88rem;
            font-weight: bold;
            color: var(--text);
            padding: 0.4rem 0.75rem;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            position: relative;
            text-decoration: none;
        }
        .nav-links a::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -5px;
            width: 0;
            height: 3px;
            background: #4338ca;  
            border-radius: 10px;
            transition: width 0.3s ease;
        }
        .nav-links a:hover::after {
            width: 100%;
        }

        .nav-links a:not(.btn-primary):hover {
            background: #f4f7ff;
        }
        .btn-primary {
            background: linear-gradient(135deg, #1a3fc4, #0ea5a0) !important;
            color: #fff !important;
            font-weight: 700;
            border-radius: 8px;
            padding: 0.42rem 1.1rem !important;
            transition: opacity 0.2s, transform 0.15s;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #1a3fc4, #0ea5a0) !important;
            opacity: 0.9;
            transform: translateY(-1px);
        }

        .profile-dropdown {
            position: relative;
        }
        .nav-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1a3fc4, #0ea5a0);
            color: #fff;
            font-weight: 800;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border: 2px solid #dde4f5;
            transition: opacity 0.2s;
        }
        .nav-avatar:hover {
            opacity: 0.9;
        }
        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            margin-top: -4px;          
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 6px 24px rgba(0,0,0,0.10);
            min-width: 200px;
            border: 1px solid #dde4f5;
            overflow: visible;
            z-index: 200;
        }
        .profile-dropdown:hover .dropdown-content {
            display: block;
        }

        .profile-dropdown::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            width: 100%;
            height: 12px;
            background: transparent;
        }
        .dropdown-user-meta { padding: 0.75rem 1rem 0.45rem; font-size: 0.86rem; color: #6b7280; }
        .dropdown-content hr { border: none; border-top: 1px solid #dde4f5; margin: 3px 0; }
        .dropdown-content a {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.8rem 1rem;   
            font-size: 0.86rem;
            color: #1a1a2e;
            transition: background 0.2s;
            position: relative;
        }
        .dropdown-content a i { width: 15px; color: var(--primary); }
        .dropdown-content a:hover { background: #f4f7ff; }
        .dropdown-badge {
            background: #ef4444;
            color: #fff;
            font-size: 0.66rem;
            font-weight: 700;
            padding: 0.1rem 0.4rem;
            border-radius: 20px;
            margin-left: auto;
        }

        .page-wrap {
            max-width: 690px;
            margin: 1rem auto;
            padding: 2rem;
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(26,63,196,0.2);
            /* Removed restrictive max-height to avoid scrollbars with multi-preview */
            min-height: auto;
        }

        .page-title {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--text);
            margin-bottom: 0.5rem;
            text-align: center;
        }
        .page-sub {
            font-size: 0.9rem;
            color: var(--muted);
            margin-bottom: 1.8rem;
            text-align: center;
        }

        /* MESSAGE */
        .message {
            display: flex; align-items: center; gap: 0.55rem;
            padding: 11px 14px; border-radius: 10px;
            margin-bottom: 1.3rem;
            font-size: 0.86rem; font-weight: 600;
        }
        .msg-success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
        .msg-error   { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }

        /* FORM GROUPS */
        .form-group { margin-bottom: 1.2rem; }
        .form-group label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 0.45rem;
        }
        .form-group label i {
            margin-right: 0.5rem;
            color: #6b7280;
            font-size: 0.85rem;
        }
        .input-wrap {
            position: relative;
        }
        .input-wrap i {
            position: absolute; left: 14px; top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
            font-size: 0.9rem;
            pointer-events: none;
            z-index: 2;
        }
        .input-wrap.textarea-wrap i { top: 16px; transform: none; }

        .input-wrap input,
        .input-wrap select,
        .input-wrap textarea {
            width: 100%;
            padding: 12px 12px 12px 42px;
            border: 1.5px solid #999999;
            border-radius: 12px;
            font-size: 0.92rem;
            font-family: inherit;
            color: var(--text);
            background: #fff;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .input-wrap textarea {
            resize: vertical; min-height: 110px;
            padding-top: 12px;
            line-height: 1.5;
        }
        .input-wrap select {
            appearance: none;
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="%236b7280" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>');
            background-repeat: no-repeat;
            background-position: right 14px center;
            padding-right: 36px;
        }
        .input-wrap input:focus,
        .input-wrap select:focus,
        .input-wrap textarea:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(26,63,196,0.15);
        }

        /* TWO COL */
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        /* FILE UPLOAD */
        .upload-zone {
            border: 2px dashed #999999;
            border-radius: 12px;
            padding: 1.8rem 1rem;
            text-align: center;
            cursor: pointer;
            background: #fafcff;
            position: relative;
            transition: border-color 0.2s;
        }
        .upload-zone:hover { border-color: var(--primary); }
        .upload-zone input[type="file"] {
            position: absolute; inset: 0;
            opacity: 0; cursor: pointer;
            width: 100%; height: 100%;
        }
        .upload-zone i { font-size: 1.8rem; color: var(--primary); margin-bottom: 0.5rem; display: block; }
        .upload-zone p { font-size: 0.85rem; color: var(--muted); }
        .upload-zone span { color: var(--primary); font-weight: 600; }

        /* MULTI-IMAGE ADVANCED PREVIEW ARCHITECTURE */
        #preview-container {
            display: none;
            margin-top: 1rem;
            flex-direction: column;
            gap: 0.75rem;
        }
        
        /* Main view display screen box */
        .main-preview-viewport {
            width: 100%;
            height: 240px;
            border-radius: 14px;
            border: 1.5px solid var(--border);
            overflow: hidden;
            position: relative;
            background: #f8fafc;
        }
        .main-preview-viewport img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            background: #111827;
            display: block;
        }
        
        /* Floating Picture Counter Tag Indicator (e.g., 1/5) */
        .preview-counter-tag {
            position: absolute;
            bottom: 12px;
            right: 12px;
            background: rgba(0, 0, 0, 0.75);
            color: #ffffff;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.78rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            z-index: 5;
            pointer-events: none;
            backdrop-filter: blur(4px);
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        /* Clear and reset upload array button */
        .main-preview-viewport .remove-img {
            position: absolute; top: 12px; right: 12px;
            background: rgba(239, 68, 68, 0.9); color: #fff;
            border: none; border-radius: 50%;
            width: 32px; height: 32px; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.9rem; transition: background 0.2s, transform 0.1s;
            z-index: 6;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }
        .main-preview-viewport .remove-img:hover { background: #dc2626; transform: scale(1.05); }

        /* Underneath dynamic thumbnail line deck tracking box */
        .thumbnail-preview-track {
            display: flex;
            gap: 0.6rem;
            overflow-x: auto;
            padding: 4px 2px;
        }
        .thumb-node {
            width: 65px;
            height: 65px;
            border-radius: 8px;
            object-fit: cover;
            cursor: pointer;
            border: 2.5px solid #e2e8f0;
            background: #fff;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        /* Neon-Teal/Cyan Radiant Glow when active/viewed */
        .thumb-node.active-glow {
            border-color: #00f2fe;
            box-shadow: 0 0 12px rgba(0, 242, 254, 0.85), 
                        0 0 4px rgba(14, 165, 160, 0.5);
            transform: translateY(-2px) scale(1.03);
        }

        /* DIVIDER */
        .form-divider {
            border: none; border-top: 1px solid var(--border);
            margin: 1.5rem 0;
        }
        .dropdown-content a.logout-link,
        .dropdown-content a.logout-link i {
            color: #ef4444 !important;
        }
        /* SUBMIT */
        .submit-btn {
            width: 100%; padding: 13px;
            border: none; border-radius: 40px;
            background: var(--primary);
            color: #fff; font-weight: 700;
            font-size: 1rem; cursor: pointer;
            display: flex; align-items: center;
            justify-content: center; gap: 0.6rem;
            transition: background 0.2s, transform 0.15s;
            margin-top: 0.5rem;
        }
        .submit-btn:hover { background: #1530a0; transform: translateY(-2px); }

        .field-hint {
            font-size: 0.75rem; color: var(--muted); margin-top: 0.28rem; display: block;
        }

        /* RESPONSIVE */
        @media (max-width: 620px) {
            .page-wrap { margin: 1rem; padding: 1.5rem; }
            .form-row { grid-template-columns: 1fr; gap: 0; }
        }
        @media (max-width: 520px) {
            .navbar { padding: 0 1rem; }
            .page-title { font-size: 1.5rem; }
        }
    </style>
</head>
<body>

<div class="navbar">
    <div class="logo">
        <img src="assets/images/logo.png" alt="BikriBazaar" class="logo-img"
             onerror="this.style.display='none'">
        Bikri<span>Bazaar</span>
    </div>
    <div class="nav-links">
        <a href="index.php"><i class="fa-solid fa-house"></i> <span style="font-weight: bold;">Home</span></a> 
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="post-ad.php" class="btn-primary">
                <i class="fa-solid fa-plus"></i> SELL
            </a>
            <div class="profile-dropdown">
                <div class="nav-avatar">
                    <?php echo strtoupper(substr($_SESSION['user_name'] ?? 'U', 0, 1)); ?>
                </div>
                <div class="dropdown-content">
                    <div class="dropdown-user-meta">
                        <strong>Hi, <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?></strong>
                    </div>
                    <hr>
                    <a href="my-ads.php"><i class="fa-solid fa-list"></i> My Ads</a>
                    <a href="favorites.php"><i class="fa-solid fa-heart"></i> Favorites</a>
                    <a href="inbox.php"><i class="fa-solid fa-message"></i> Messages</a>
                    <hr>
                    <a href="logout.php" class="logout-link">
                        <i class="fa-solid fa-right-from-bracket"></i> Logout
                    </a>
                </div>
            </div>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php" class="btn-primary">Register</a>
        <?php endif; ?>
    </div>
</div>

<div class="page-wrap">

    <div class="page-title">Post Your Ad</div>
    <div class="page-sub">Fill in the details below to list your product for free.</div>

    <?php if($message != ""): ?>
        <div class="message <?php echo (stripos($message,'success') !== false) ? 'msg-success' : 'msg-error'; ?>">
            <i class="fa-solid <?php echo (stripos($message,'success') !== false) ? 'fa-circle-check' : 'fa-circle-exclamation'; ?>"></i>
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">

        <div class="form-group">
            <label for="title"><i class="fa-solid fa-tag"></i> Product Title</label>
            <div class="input-wrap">
                <input type="text" id="title" name="title"
                       placeholder="e.g. Laptop" required>
            </div>
        </div>

        <div class="form-group">
            <label for="description"><i class="fa-solid fa-align-left"></i> Description</label>
            <div class="input-wrap textarea-wrap">
                <textarea id="description" name="description"
                          placeholder="Describe condition, features, reason for selling..." required></textarea>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="price"><i class="fa-solid fa-indian-rupee-sign"></i> Price</label>
                <div class="input-wrap">
                    <input type="number" id="price" name="price"
                           placeholder="e.g. 25000" min="0" required>
                </div>
            </div>

            <div class="form-group">
                <label for="category"><i class="fa-solid fa-folder-open"></i> Category</label>
                <div class="input-wrap">
                    <select id="category" name="category" required>
                        <option value="">Select category</option>
                        <option value="Mobiles">Mobiles</option>
                        <option value="Cars">Cars</option>
                        <option value="Bikes">Bikes</option>
                        <option value="Electronics">Electronics</option>
                        <option value="Furniture">Furniture</option>
                        <option value="Fashion">Fashion</option>
                        <option value="Books">Books &amp; Sports</option>
                        <option value="Pets">Pets</option>
                        <option value="Services">Services</option>
                        <option value="Others">Others</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="location"><i class="fa-solid fa-location-dot"></i> Location</label>
            <div class="input-wrap">
                <input type="text" id="location" name="location"
                       placeholder="City, Area (e.g. Mumbai, Andheri)" required>
            </div>
        </div>

        <hr class="form-divider">

        <div class="form-group">
            <label for="images"><i class="fa-solid fa-camera"></i> Product Photos (Up to 6 images)</label>
            <div class="upload-zone" id="uploadZone">
                <input type="file" id="images" name="images[]"
                       accept="image/*" multiple required
                       onchange="processImages(this)">
                <i class="fa-solid fa-cloud-arrow-up"></i>
                <p><span>Click to upload</span> or drag &amp; drop</p>
                <p style="margin-top:0.3rem;">Upload up to 6 images &bull; JPG, PNG, GIF &bull; Max 5MB each</p>
            </div>
            
            <div id="preview-container">
                <div class="main-preview-viewport">
                    <img id="viewport-active-img" src="" alt="Main Display View">
                    <div class="preview-counter-tag" id="imageCounter">0 / 0</div>
                    <button type="button" class="remove-img" onclick="clearImageTrack()">
                        <i class="fa-solid fa-trash-can"></i>
                    </button>
                </div>
                <div class="thumbnail-preview-track" id="thumbnailTrack">
                    </div>
            </div>
        </div>

        <button class="submit-btn" type="submit">
            <i class="fa-solid fa-paper-plane"></i> Post Ad
        </button>

    </form>
</div>

<script>
    // Global tracker array to keep base64 strings
    let imagePool = [];

    function processImages(input) {
        const container = document.getElementById('preview-container');
        const track = document.getElementById('thumbnailTrack');
        
        if (!input.files) return;
        
        // Enforce hard block limit of 6 files
        const fileCount = Math.min(input.files.length, 6);
        if(input.files.length > 6) {
            alert("System Alert: You can only select up to 6 photos. The first 6 have been loaded.");
        }
        
        // Reset old local tracking pool contents
        imagePool = [];
        track.innerHTML = "";
        
        let loadedCount = 0;
        
        for (let i = 0; i < fileCount; i++) {
            const reader = new FileReader();
            reader.onload = (e) => {
                imagePool.push(e.target.result);
                loadedCount++;
                
                // Once all targeted files are read successfully, compile the interface view deck
                if (loadedCount === fileCount) {
                    renderPreviewTrack();
                }
            };
            reader.readAsDataURL(input.files[i]);
        }
    }

    function renderPreviewTrack() {
        const container = document.getElementById('preview-container');
        const track = document.getElementById('thumbnailTrack');
        const viewport = document.getElementById('viewport-active-img');
        
        if (imagePool.length === 0) {
            container.style.display = 'none';
            return;
        }
        
        // Display preview module block 
        container.style.display = 'flex';
        
        // Render each thumbnail into track timeline link map
        imagePool.forEach((sourceUrl, index) => {
            const thumb = document.createElement('img');
            thumb.src = sourceUrl;
            thumb.className = 'thumb-node';
            thumb.alt = `Thumbnail ${index + 1}`;
            
            // Onclick handler switch view and adjust active glowing state node
            thumb.onclick = () => switchToImage(index);
            track.appendChild(thumb);
        });
        
        // Auto initialize focusing presentation screen to image position 0
        switchToImage(0);
    }

    function switchToImage(index) {
        if (index < 0 || index >= imagePool.length) return;
        
        // Update viewport display image
        document.getElementById('viewport-active-img').src = imagePool[index];
        
        // Update dynamic 1/X style position indicator count text strings
        document.getElementById('imageCounter').innerText = `${index + 1} / ${imagePool.length}`;
        
        // Clear all active glows out from other track nodes
        const nodes = document.querySelectorAll('.thumb-node');
        nodes.forEach(node => node.classList.remove('active-glow'));
        
        // Append neon-cyan glowing frame focus filter only to selected target thumb index
        if(nodes[index]) {
            nodes[index].classList.add('active-glow');
            // Ensure smooth scroll tracking into view context inside container alignment maps
            nodes[index].scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
        }
    }

    function clearImageTrack() {
        document.getElementById('images').value = '';
        imagePool = [];
        document.getElementById('thumbnailTrack').innerHTML = "";
        document.getElementById('preview-container').style.style.display = 'none';
    }

    // Drag zone border animations bindings
    const zone = document.getElementById('uploadZone');
    zone.addEventListener('dragover',  e => { e.preventDefault(); zone.style.borderColor = 'var(--primary)'; });
    zone.addEventListener('dragleave', () => { zone.style.borderColor = '#999999'; });
    zone.addEventListener('drop',      () => { zone.style.borderColor = '#999999'; });
</script>

</body>
</html>