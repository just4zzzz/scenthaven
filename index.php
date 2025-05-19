<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scent Haven - Premium Fragrance Store</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="logo">
                <h1>Scent Haven</h1>
            </div>
            <div class="nav-links">
                <a href="#home">Home</a>
                <a href="#brands">Brands</a>
                <a href="#new-arrivals">New Arrivals</a>
                <a href="#bestsellers">Bestsellers</a>
                <a href="#contact">Contact</a>
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

    <section id="home" class="hero">
        <div class="hero-content">
            <h1>Discover Your Signature Scent</h1>
            <p>Explore our curated collection of luxury fragrances</p>
            <a href="#shop-now" class="cta-button">Shop Now</a>
        </div>
    </section>

    <section id="brands" class="featured-brands">
        <h2>Featured Brands</h2>
        <div class="brand-grid">
            <div class="brand-card">
                <img src="images/chanel.jpg" alt="Chanel">
                <h3>Chanel</h3>
            </div>
            <div class="brand-card">
                <img src="images/dior.jpg" alt="Dior">
                <h3>Dior</h3>
            </div>
            <div class="brand-card">
                <img src="images/tom-ford.jpg" alt="Tom Ford">
                <h3>Tom Ford</h3>
            </div>
            <div class="brand-card">
                <img src="images/jo-malone.jpg" alt="Jo Malone">
                <h3>Jo Malone</h3>
            </div>
        </div>
    </section>

    <section id="new-arrivals" class="new-arrivals">
        <h2>New Arrivals</h2>
        <div class="product-grid">
            <div class="product-card">
                <img src="images/product1.jpg" alt="Chanel N°5">
                <h3>Chanel N°5</h3>
                <p>Eau de Parfum</p>
                <span class="price">$135.00</span>
                <button class="add-to-cart" data-product-id="1">Add to Cart</button>
            </div>
            <div class="product-card">
                <img src="images/product2.jpg" alt="Miss Dior">
                <h3>Miss Dior</h3>
                <p>Eau de Parfum</p>
                <span class="price">$120.00</span>
                <button class="add-to-cart" data-product-id="2">Add to Cart</button>
            </div>
            <div class="product-card">
                <img src="images/product3.jpg" alt="Black Orchid">
                <h3>Black Orchid</h3>
                <p>Tom Ford</p>
                <span class="price">$150.00</span>
                <button class="add-to-cart" data-product-id="3">Add to Cart</button>
            </div>
        </div>
    </section>

    <section id="bestsellers" class="new-arrivals">
        <h2>Bestsellers</h2>
        <div class="product-grid">
            <div class="product-card">
                <img src="images/product4.jpg" alt="Sauvage">
                <h3>Sauvage</h3>
                <p>Dior</p>
                <p class="description">A powerful fresh and spicy fragrance with notes of Bergamot and Ambroxan</p>
                <span class="price">$155.00</span>
                <button class="add-to-cart" data-product-id="4">Add to Cart</button>
            </div>
            <div class="product-card">
                <img src="images/product5.jpg" alt="Bleu de Chanel">
                <h3>Bleu de Chanel</h3>
                <p>Chanel</p>
                <p class="description">An elegant woody aromatic fragrance with citrus and amber notes</p>
                <span class="price">$165.00</span>
                <button class="add-to-cart" data-product-id="5">Add to Cart</button>
            </div>
            <div class="product-card">
                <img src="images/product6.jpg" alt="Neroli Portofino">
                <h3>Neroli Portofino</h3>
                <p>Tom Ford</p>
                <p class="description">A fresh citrus aromatic blend capturing the essence of the Italian Riviera</p>
                <span class="price">$295.00</span>
                <button class="add-to-cart" data-product-id="6">Add to Cart</button>
            </div>
        </div>
    </section>

    <section id="contact" class="newsletter">
        <div class="newsletter-content">
            <h2>Contact Us</h2>
            <p>Get in touch with us for any inquiries</p>
            <div class="contact-info">
                <p>Email: info@scenthaven.com</p>
                <p>Phone: (555) 123-4567</p>
                <p>Address: 123 Fragrance Lane, Perfume City</p>
            </div>
        </div>
    </section>

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
    <!-- Cart Sidebar -->
    <div id="cart-sidebar" style="display:none;position:fixed;top:0;left:0;width:350px;max-width:90vw;height:100vh;background:#fff;box-shadow:2px 0 16px rgba(30,55,153,0.12);z-index:2100;overflow-y:auto;transition:transform 0.3s cubic-bezier(.4,2,.6,1);transform:translateX(-100%);">
        <div style="padding:1.5rem 1.5rem 1rem 1.5rem;display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid #eaeaea;">
            <span style="font-size:1.3rem;font-weight:600;color:#1e3799;">Your Cart</span>
            <button id="close-cart-sidebar" style="background:none;border:none;font-size:1.5rem;color:#1e3799;cursor:pointer;"><i class="fas fa-times"></i></button>
        </div>
        <div id="cart-sidebar-content" style="padding:1rem 1.5rem;"></div>
    </div>
    <div id="cart-sidebar-overlay" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(30,55,153,0.10);z-index:2099;"></div>

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
                const productCard = this.closest('.product-card');
                let productId = 0;
                // Find productId based on product name (since static HTML)
                const name = productCard.querySelector('h3').textContent.trim();
                switch(name) {
                    case 'Chanel N°5': productId = 1; break;
                    case 'Miss Dior': productId = 2; break;
                    case 'Black Orchid': productId = 3; break;
                    case 'Sauvage': productId = 4; break;
                    case 'Bleu de Chanel': productId = 5; break;
                    case 'Neroli Portofino': productId = 6; break;
                }
                if (!productId) { alert('Product not found.'); return; }
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

        fetchCartCount();

        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });

        window.addEventListener('scroll', () => {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.style.backgroundColor = 'rgba(255, 255, 255, 0.95)';
                navbar.style.boxShadow = '0 2px 10px rgba(0,0,0,0.1)';
            } else {
                navbar.style.backgroundColor = '#fff';
                navbar.style.boxShadow = '0 2px 5px rgba(0,0,0,0.1)';
            }
        });

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
    </script>
</body>
</html> 