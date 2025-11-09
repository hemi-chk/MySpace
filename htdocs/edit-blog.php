<?php
// Edit Blog Post
$page_title = "Edit Blog";
require_once 'config.php';
require_once 'includes/auth.php';

// Require login
requireLogin();

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$blog_id = (int)$_GET['id'];
$user_id = $_SESSION['user_id'];

// Check if user owns this blog
if (!userOwnsBlog($user_id, $blog_id)) {
    header("Location: index.php");
    exit();
}

$error = '';
$conn = getDBConnection();

// Fetch blog post
$stmt = $conn->prepare("SELECT * FROM blogPost WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $blog_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: index.php");
    exit();
}

$blog = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = sanitizeInput($_POST['title']);
    $content = $_POST['content'];
    
    if (empty($title) || empty($content)) {
        $error = "Title and content are required";
    } else {
        $stmt = $conn->prepare("UPDATE blogPost SET title = ?, content = ? WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ssii", $title, $content, $blog_id, $user_id);
        
        if ($stmt->execute()) {
            header("Location: view-blog.php?id=" . $blog_id);
            exit();
        } else {
            $error = "Failed to update blog post. Please try again.";
        }
    }
}

include 'includes/header.php';
?>

<div class="container">
    <div class="form-container" style="max-width: 900px;">
        <h1>Edit Blog Post</h1>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="" onsubmit="return validateBlogForm()">
            <div class="form-group">
                <label for="title">Blog Title</label>
                <input type="text" id="title" name="title" 
                       value="<?php echo htmlspecialchars($blog['title']); ?>" 
                       required>
            </div>
            
            <div class="form-group">
                <label for="content">Blog Content</label>
                <textarea id="content" name="content" 
                          required><?php echo htmlspecialchars($blog['content']); ?></textarea>
            </div>
            
            <div style="display: flex; gap: 1rem;">
                <button type="submit" class="btn-primary">Update Blog</button>
                <a href="view-blog.php?id=<?php echo $blog_id; ?>" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php 
$stmt->close();
$conn->close();
include 'includes/footer.php'; 
?>