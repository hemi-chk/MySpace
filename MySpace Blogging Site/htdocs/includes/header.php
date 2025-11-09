<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?>My Space</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <div class="nav-brand">
                <a href="index.php">
                    <img src="image/logo-img.jpg" alt="My Space Logo">
                </a>
            </div>
            
            <div class="hamburger" onclick="toggleMenu()">
                <span></span>
                <span></span>
                <span></span>
            </div>
            
            <ul class="nav-menu" id="navMenu">
                <li><a href="index.php">Home</a></li>
                
                <?php if (isLoggedIn()): ?>
                    <li><a href="my-blogs.php">My Blogs</a></li>
                    <li><a href="create-blog.php">Create Blog</a></li>
               
                    <li><a href="logout.php" class="btn-logout">Logout</a></li>
                <br>
                 </ul>
                <p class="user-info">
                        <span> Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
                    </p>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="register.php">Register</a></li>
                <?php endif; ?>
           
        </div>
    </nav>
    
    <script>
        function toggleMenu() {
            const navMenu = document.getElementById('navMenu');
            navMenu.classList.toggle('active');
        }
    </script>