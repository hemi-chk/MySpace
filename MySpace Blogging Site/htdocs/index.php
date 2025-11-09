<?php
// index.php - Home Page (Blog List)
$page_title = "Home";
require_once 'config.php';
require_once 'includes/auth.php';
// Fetch all blog posts
$conn = getDBConnection();
$query = "SELECT b.*, u.username 
          FROM blogPost b 
          JOIN user u ON b.user_id = u.id 
          ORDER BY b.created_at DESC";
$result = $conn->query($query);
include 'includes/header.php';
?>
<div class="main-content index-page">
    <div class="container">
        <div class="page-header">
            <h1>Welcome to MySpace</h1>
            <p>Where your creativity meets space...</p>
        </div>
        
        <?php if ($result->num_rows > 0): ?>
            <div class="blog-grid">
                <?php while ($blog = $result->fetch_assoc()): ?>
                    <div class="blog-card">
                        <div class="blog-card-content">
                            <h2><a href="view-blog.php?id=<?php echo $blog['id']; ?>">
                                <?php echo htmlspecialchars($blog['title']); ?>
                            </a></h2>
                            
                            <div class="blog-meta">
                                <span>👤 <?php echo htmlspecialchars($blog['username']); ?></span>
                                <span>📅 <?php echo date('M d, Y', strtotime($blog['created_at'])); ?></span>
                            </div>
                            
                            <div class="blog-excerpt">
                                <?php 
                                $excerpt = strip_tags($blog['content']);
                                echo htmlspecialchars(substr($excerpt, 0, 150)) . (strlen($excerpt) > 150 ? '...' : '');
                                ?>
                            </div>
                            
                            <a href="view-blog.php?id=<?php echo $blog['id']; ?>" class="btn-primary">
                                Read More
                            </a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <h2>No blogs yet</h2>
                <p>Be the first to create a blog post!</p>
                <?php if (isLoggedIn()): ?>
                    <a href="create-blog.php" class="btn-primary">Create Your First Blog</a>
                <?php else: ?>
                    <a href="register.php" class="btn-primary">Register to Get Started</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php 
$conn->close();
include 'includes/footer.php'; 
?>