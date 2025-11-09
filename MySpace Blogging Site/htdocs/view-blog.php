<?php
// view-blog.php - View Single Blog Post
require_once 'config.php';
require_once 'includes/auth.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$blog_id = (int)$_GET['id'];
$conn = getDBConnection();

// Fetch blog post with author information
$stmt = $conn->prepare("SELECT b.*, u.username 
                        FROM blogPost b 
                        JOIN user u ON b.user_id = u.id 
                        WHERE b.id = ?");
$stmt->bind_param("i", $blog_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: index.php");
    exit();
}

$blog = $result->fetch_assoc();
$page_title = $blog['title'];

$can_edit = isLoggedIn() && userOwnsBlog($_SESSION['user_id'], $blog_id);

include 'includes/header.php';
?>

<div class="container">
    <div class="blog-single">
        <h1><?php echo htmlspecialchars($blog['title']); ?></h1>
        
        <div class="blog-meta">
            <span>👤 <?php echo htmlspecialchars($blog['username']); ?></span>
            <span>📅 <?php echo date('F d, Y', strtotime($blog['created_at'])); ?></span>
            <?php if ($blog['updated_at'] != $blog['created_at']): ?>
                <span>✏️ Updated: <?php echo date('F d, Y', strtotime($blog['updated_at'])); ?></span>
            <?php endif; ?>
        </div>
        
        <?php if ($can_edit): ?>
            <div class="blog-actions" style="margin-top: 1rem;">
                <a href="edit-blog.php?id=<?php echo $blog['id']; ?>" class="btn-secondary">Edit</a>
                <a href="delete-blog.php?id=<?php echo $blog['id']; ?>" 
                   class="btn-danger" 
                   onclick="return confirmDelete()">Delete</a>
            </div>
        <?php endif; ?>
        
        <div class="blog-content">
            <?php echo nl2br(htmlspecialchars($blog['content'])); ?>
        </div>
        
        <div style="margin-top: 2rem;">
            <a href="index.php" class="btn-secondary">← Back to Home</a>
        </div>
    </div>
</div>

<?php 
$stmt->close();
$conn->close();
include 'includes/footer.php'; 
?>