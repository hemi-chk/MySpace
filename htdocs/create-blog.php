<?php
// create-blog.php - Create New Blog Post
$page_title = "Create Blog";
require_once 'config.php';
require_once 'includes/auth.php';

// Require login
requireLogin();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = sanitizeInput($_POST['title']);
    $content = $_POST['content']; // Don't sanitize content too much to preserve formatting
    
    if (empty($title) || empty($content)) {
        $error = "Title and content are required";
    } else {
        $conn = getDBConnection();
        $user_id = $_SESSION['user_id'];
        
        $stmt = $conn->prepare("INSERT INTO blogPost (user_id, title, content) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $user_id, $title, $content);
        
        if ($stmt->execute()) {
            $blog_id = $stmt->insert_id;
            header("Location: view-blog.php?id=" . $blog_id);
            exit();
        } else {
            $error = "Failed to create blog post. Please try again.";
        }
        
        $stmt->close();
        $conn->close();
    }
}

include 'includes/header.php';
?>

<div class="container">
    <div class="form-container" style="max-width: 900px;">
        <h1>Create New Blog Post</h1>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="" onsubmit="return validateBlogForm()">
            <div class="form-group">
                <label for="title">Blog Title</label>
                <input type="text" id="title" name="title" 
                       value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>" 
                       placeholder="Enter your blog title" 
                       required>
            </div>
            
            <div class="form-group">
                <label for="content">Blog Content</label>
                <textarea id="content" name="content" 
                          placeholder="Write your blog content here... You can use basic formatting."
                          required><?php echo isset($_POST['content']) ? htmlspecialchars($_POST['content']) : ''; ?></textarea>
                <small>Tip: Use line breaks to separate paragraphs</small>
            </div>
            
            <div style="display: flex; gap: 1rem;">
                <button type="submit" class="btn-primary">Publish Blog</button>
                <a href="index.php" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>