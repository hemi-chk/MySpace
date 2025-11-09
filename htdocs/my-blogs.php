<?php
//my account 
$page_title = "My Blogs";
require_once 'config.php';
require_once 'includes/auth.php';

// Require login
requireLogin();

$user_id = $_SESSION['user_id'];
$conn = getDBConnection();

// Fetch user's blog posts
$stmt = $conn->prepare("SELECT * FROM blogPost WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

include 'includes/header.php';
?>

<div class="container">
    <div class="page-header">
        <h1>My Blog Posts</h1>
        <p>Manage your blog posts</p>
    </div>
    
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success">
            <?php 
            echo $_SESSION['message']; 
            unset($_SESSION['message']);
            ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error">
            <?php 
            echo $_SESSION['error']; 
            unset($_SESSION['error']);
            ?>
        </div>
    <?php endif; ?>
    
    <?php if ($result->num_rows > 0): ?>
        <div class="blog-grid">
            <?php while ($blog = $result->fetch_assoc()): ?>
                <div class="blog-card">
                    <div class="blog-card-content">
                        <h2><a href="view-blog.php?id=<?php echo $blog['id']; ?>">
                            <?php echo htmlspecialchars($blog['title']); ?>
                        </a></h2>
                        
                        <div class="blog-meta">
                            <span>📅 <?php echo date('M d, Y', strtotime($blog['created_at'])); ?></span>
                            <?php if ($blog['updated_at'] != $blog['created_at']): ?>
                                <span>✏️ Updated</span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="blog-excerpt">
                            <?php 
                            $excerpt = strip_tags($blog['content']);
                            echo htmlspecialchars(substr($excerpt, 0, 150)) . (strlen($excerpt) > 150 ? '...' : '');
                            ?>
                        </div>
                        
                        <div class="blog-actions">
                            <a href="view-blog.php?id=<?php echo $blog['id']; ?>" class="btn-primary">View</a>
                            <a href="edit-blog.php?id=<?php echo $blog['id']; ?>" class="btn-secondary">Edit</a>
                            <a href="delete-blog.php?id=<?php echo $blog['id']; ?>" 
                               class="btn-danger" 
                               onclick="return confirmDelete()">Delete</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <h2>You haven't created any blog posts yet</h2>
            <p>Start sharing your thoughts with the world!</p>
            <a href="create-blog.php" class="btn-primary">Create Your First Blog</a>
        </div>
    <?php endif; ?>
</div>

<?php 
$stmt->close();
$conn->close();
include 'includes/footer.php'; 
?>