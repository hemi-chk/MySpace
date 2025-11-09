<?php
// delete-blog.php - Delete Blog Post
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

$conn = getDBConnection();

// Delete the blog post
$stmt = $conn->prepare("DELETE FROM blogPost WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $blog_id, $user_id);

if ($stmt->execute()) {
    $_SESSION['message'] = "Blog post deleted successfully";
} else {
    $_SESSION['error'] = "Failed to delete blog post";
}

$stmt->close();
$conn->close();

header("Location: my-blogs.php");
exit();
?>