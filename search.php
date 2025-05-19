<?php
session_start();
require_once 'config.php';
$q = isset($_GET['q']) ? trim($_GET['q']) : '';
$results = [];
if ($q !== '') {
    // Improved search query - prioritize name matches over description matches
    // Also search in brand names by joining with the brands table
    $stmt = mysqli_prepare($conn, "
        SELECT p.*, b.name as brandName 
        FROM products p
        LEFT JOIN brands b ON p.brandId = b.id
        WHERE p.name LIKE CONCAT('%', ?, '%') 
        OR b.name LIKE CONCAT('%', ?, '%')
        OR p.description LIKE CONCAT('%', ?, '%')
        ORDER BY 
            CASE 
                WHEN p.name LIKE CONCAT('%', ?, '%') THEN 1
                WHEN b.name LIKE CONCAT('%', ?, '%') THEN 2
                ELSE 3 
            END
    ");
    mysqli_stmt_bind_param($stmt, 'sssss', $q, $q, $q, $q, $q);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_assoc($res)) {
        $results[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results - Scent Haven</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 5%;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            z-index: 1000;
        }
        .logo h1 {
            margin: 0;
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            color: #1e3799;
        }
        .nav-links {
            display: flex;
            gap: 2rem;
        }
        .nav-links a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        .nav-links a:hover {
            color: #1e3799;
        }
        .nav-right {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }
        .nav-icons {
            display: flex;
            gap: 1rem;
        }
        .nav-icons a {
            color: #1e3799;
            font-size: 1.2rem;
        }
        .auth-links {
            display: flex;
            gap: 1rem;
        }
        .auth-links a {
            color: #1e3799;
            text-decoration: none;
            font-weight: 500;
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .user-welcome {
            font-size: 0.9rem;
            color: #333;
        }
        .logout-btn {
            color: #e74c3c;
            text-decoration: none;
        }

        .search-container { 
            max-width: 1100px; 
            margin: 100px auto 40px; 
            background: #fff; 
            border-radius: 12px; 
            box-shadow: 0 2px 10px rgba(30,55,153,0.08); 
            padding: 2rem; 
        }
        .search-title { 
            font-size: 1.8rem; 
            margin-bottom: 1.5rem; 
            color: #1e3799; 
            font-family: 'Playfair Display', serif;
            border-bottom: 1px solid #e6e6e6;
            padding-bottom: 0.8rem;
        }
        .search-form {
            margin-bottom: 2rem;
            display: flex;
            gap: 1rem;
        }
        .search-form input {
            flex: 1;
            padding: 12px 16px;
            border: 1.5px solid #c8d6e5;
            border-radius: 8px;
            font-size: 1rem;
            font-family: 'Poppins', sans-serif;
        }
        .search-form button {
            background: #1e3799;
            color: #fff;
            border: none;
            padding: 0 24px;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        .search-form button:hover {
            background: #4a69bd;
        }
        .product-grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); 
            gap: 2rem; 
        }
        .product-card { 
            background: #fff; 
            border-radius: 10px; 
            padding: 1.2rem; 
            box-shadow: 0 2px 8px rgba(0,0,0,0.07); 
            transition: transform 0.3s ease, box-shadow 0.3s ease; 
            display: flex; 
            flex-direction: column; 
            height: 100%; 
            border: 1px solid #f1f2f6;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.12);
        }
        .product-card img { 
            width: 100%; 
            height: 200px; 
            object-fit: contain;
            border-radius: 5px; 
            margin-bottom: 1rem; 
            background-color: #f9f9f9; 
        }
        .product-card h3 { 
            font-size: 1.2rem; 
            margin: 0.5rem 0; 
            color: #333;
        }
        .product-brand {
            color: #888;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }
        .product-description {
            font-size: 0.9rem;
            color: #666;
            margin: 0.5rem 0;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            flex: 1;
        }
        .price { 
            color: #1e3799; 
            font-size: 1.2rem; 
            font-weight: 600; 
            margin: 0.8rem 0; 
        }
        .add-to-cart { 
            width: 100%; 
            padding: 0.8rem; 
            background: #1e3799; 
            color: #fff; 
            border: none; 
            border-radius: 5px; 
            cursor: pointer; 
            transition: background 0.3s ease; 
            margin-top: auto; 
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
        }
        .add-to-cart:hover { 
            background: #4a69bd; 
        }
        .no-results { 
            text-align: center; 
            color: #888; 
            font-size: 1.1rem; 
            margin: 3rem 0; 
            padding: 2rem;
            background: #f9f9f9;
            border-radius: 8px;
        }
        .back-to-home {
            margin-top: 1.5rem;
            text-align: center;
        }
        .back-to-home a {
            color: #1e3799;
            text-decoration: none;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }
        .back-to-home a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="logo">
                <h1>Scent Haven</h1>
            </div>
            <div class="nav-links">
                <a href="index.php#home">Home</a>
                <a href="index.php#brands">Brands</a>
                <a href="index.php#new-arrivals">New Arrivals</a>
                <a href="index.php#bestsellers">Bestsellers</a>
                <a href="index.php#contact">Contact</a>
            </div>
            <div class="nav-right">
                <div class="nav-icons">
                    <a href="#" title="Search" id="open-search-modal"><i class="fas fa-search"></i></a>
                    <a href="#" title="Shopping Cart" id="open-cart-sidebar"><i class="fas fa-shopping-cart"></i><span id="cart-count" style="background:#e74c3c;color:#fff;border-radius:50%;padding:2px 8px;font-size:0.8em;position:relative;top:-10px;left:-5px;display:none;">0</span></a>
                </div>
                <?php if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true): ?>
                    <div class="user-info">
                        <span class="user-welcome">Welcome, <?php echo htmlspecialchars($_SESSION["username"]); ?></span>
                        <a href="logout.php" class="logout-btn" title="Logout"><i class="fas fa-sign-out-alt"></i></a>
                    </div>
                <?php else: ?>
                    <div class="auth-links">
                        <a href="login.php">Login</a>
                        <a href="register.php">Register</a>
                    </div>
                <?php endif; ?>
            </div>
        </nav>
    </header>

    <div class="search-container">
        <div class="search-title">Search Results for "<?php echo htmlspecialchars($q); ?>"</div>
        
        <form class="search-form" action="search.php" method="get">
            <input type="text" name="q" value="<?php echo htmlspecialchars($q); ?>" placeholder="Search for perfumes, brands...">
            <button type="submit"><i class="fas fa-search"></i> Search</button>
        </form>
        
        <?php if ($q === ''): ?>
            <div class="no-results">
                <i class="fas fa-search" style="font-size:2rem;color:#1e3799;margin-bottom:1rem;"></i>
                <p>Please enter a search term to find products.</p>
            </div>
        <?php elseif (count($results) === 0): ?>
            <div class="no-results">
                <i class="fas fa-exclamation-circle" style="font-size:2rem;color:#1e3799;margin-bottom:1rem;"></i>
                <p>No products found matching "<?php echo htmlspecialchars($q); ?>".</p>
                <p>Try different keywords or browse our categories.</p>
            </div>
        <?php else: ?>
            <div class="product-grid">
                <?php foreach ($results as $product): ?>
                <div class="product-card">
                    <img src="<?php echo htmlspecialchars($product['imageUrl']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                    <div class="product-brand">
                        <?php echo isset($product['brandName']) ? htmlspecialchars($product['brandName']) : ''; ?>
                    </div>
                    <div class="product-description">
                        <?php echo htmlspecialchars($product['description']); ?>
                    </div>
                    <span class="price">$<?php echo number_format($product['price'], 2); ?></span>
                    <button class="add-to-cart" data-product-id="<?php echo $product['id']; ?>">Add to Cart</button>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <div class="back-to-home">
            <a href="index.php"><i class="fas fa-arrow-left"></i> Back to Home</a>
        </div>
    </div>

    <!-- Cart Sidebar -->
    <div id="cart-sidebar" style="display:none;position:fixed;top:0;left:0;width:350px;max-width:90vw;height:100vh;background:#fff;box-shadow:2px 0 16px rgba(30,55,153,0.12);z-index:2100;overflow-y:auto;transition:transform 0.3s cubic-bezier(.4,2,.6,1);transform:translateX(-100%);">
        <div style="padding:1.5rem 1.5rem 1rem 1.5rem;display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid #eaeaea;">
            <span style="font-size:1.3rem;font-weight:600;color:#1e3799;">Your Cart</span>
            <button id="close-cart-sidebar" style="background:none;border:none;font-size:1.5rem;color:#1e3799;cursor:pointer;"><i class="fas fa-times"></i></button>
        </div>
        <div id="cart-sidebar-content" style="padding:1rem 1.5rem;"></div>
    </div>
    <div id="cart-sidebar-overlay" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(30,55,153,0.10);z-index:2099;"></div>

    <!-- Search Modal -->
    <div id="search-modal" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(30,55,153,0.15);z-index:2000;align-items:center;justify-content:center;">
        <div style="background:#fff;padding:2rem 2.5rem;border-radius:16px;box-shadow:0 8px 32px rgba(30,55,153,0.15);min-width:320px;max-width:90vw;position:relative;">
            <button id="close-search-modal" style="position:absolute;top:10px;right:10px;background:none;border:none;font-size:1.5rem;color:#1e3799;cursor:pointer;"><i class="fas fa-times"></i></button>
            <form id="search-form" action="search.php" method="get" style="display:flex;gap:1rem;align-items:center;">
                <input type="text" name="q" placeholder="Search for products..." style="flex:1;padding:10px 16px;border-radius:8px;border:1.5px solid #c8d6e5;font-size:1.1em;">
                <button type="submit" style="background:#1e3799;color:#fff;border:none;padding:10px 18px;border-radius:8px;font-size:1.1em;cursor:pointer;"><i class="fas fa-search"></i></button>
            </form>
        </div>
    </div>

    <script>
    // Add to Cart AJAX
    function updateCartCount(count) {
        const cartCount = document.getElementById('cart-count');
        if (count > 0) {
            cartCount.textContent = count;
            cartCount.style.display = 'inline-block';
        } else {
            cartCount.style.display = 'none';
        }
    }

    function fetchCartCount() {
        fetch('get_cart_count.php')
            .then(res => res.json())
            .then(data => updateCartCount(data.cartCount || 0));
    }

    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            fetch('add_to_cart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'productId=' + productId
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    updateCartCount(data.cartCount);
                    alert('Item added to cart!');
                } else {
                    alert(data.message || 'Failed to add to cart.');
                }
            });
        });
    });

    // Cart Sidebar
    const cartSidebar = document.getElementById('cart-sidebar');
    const cartSidebarOverlay = document.getElementById('cart-sidebar-overlay');
    function loadCartSidebar() {
        fetch('cart_sidebar.php').then(res => res.text()).then(html => {
            document.getElementById('cart-sidebar-content').innerHTML = html;
            // Add event listeners to remove buttons
            document.querySelectorAll('#cart-sidebar-content form').forEach(form => {
                form.onsubmit = function(e) {
                    e.preventDefault();
                    const formData = new FormData(form);
                    fetch('remove_from_cart.php', {
                        method: 'POST',
                        body: formData
                    }).then(() => {
                        loadCartSidebar();
                        fetchCartCount();
                    });
                };
            });
        });
    }
    document.getElementById('open-cart-sidebar').onclick = function(e) {
        e.preventDefault();
        cartSidebar.style.display = 'block';
        cartSidebarOverlay.style.display = 'block';
        setTimeout(() => { cartSidebar.style.transform = 'translateX(0)'; }, 10);
        loadCartSidebar();
    };
    document.getElementById('close-cart-sidebar').onclick = closeCartSidebar;
    cartSidebarOverlay.onclick = closeCartSidebar;
    function closeCartSidebar() {
        cartSidebar.style.transform = 'translateX(-100%)';
        setTimeout(() => {
            cartSidebar.style.display = 'none';
            cartSidebarOverlay.style.display = 'none';
        }, 300);
    }

    // Search Modal
    const searchModal = document.getElementById('search-modal');
    document.getElementById('open-search-modal').onclick = function(e) {
        e.preventDefault();
        searchModal.style.display = 'flex';
        searchModal.querySelector('input[name="q"]').focus();
    };
    document.getElementById('close-search-modal').onclick = function() {
        searchModal.style.display = 'none';
    };
    window.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') searchModal.style.display = 'none';
    });
    searchModal.onclick = function(e) {
        if (e.target === searchModal) searchModal.style.display = 'none';
    };

    fetchCartCount();
    </script>
</body>
</html> 