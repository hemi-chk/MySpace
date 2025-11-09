// script.js - Main JavaScript File

// Mobile Navigation Toggle
document.addEventListener('DOMContentLoaded', function() {
    const hamburger = document.querySelector('.hamburger');
    const navMenu = document.querySelector('.nav-menu');
    
    if (hamburger) {
        hamburger.addEventListener('click', function() {
            navMenu.classList.toggle('active');
        });
    }
    
    // Close mobile menu when clicking outside
    document.addEventListener('click', function(event) {
        if (navMenu && navMenu.classList.contains('active')) {
            if (!event.target.closest('.navbar')) {
                navMenu.classList.remove('active');
            }
        }
    });
});

// Confirm Delete
function confirmDelete(message) {
    return confirm(message || 'Are you sure you want to delete this blog post? This action cannot be undone.');
}

// Auto-hide alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    
    alerts.forEach(function(alert) {
        setTimeout(function() {
            alert.style.opacity = '0';
            alert.style.transition = 'opacity 0.5s';
            setTimeout(function() {
                alert.remove();
            }, 500);
        }, 5000);
    });
});

// Simple Markdown Preview (Basic Implementation)
function initMarkdownPreview() {
    const contentTextarea = document.getElementById('content');
    const previewDiv = document.getElementById('preview');
    
    if (contentTextarea && previewDiv) {
        contentTextarea.addEventListener('input', function() {
            const markdown = this.value;
            const html = parseMarkdown(markdown);
            previewDiv.innerHTML = html;
        });
    }
}

// Basic Markdown Parser
function parseMarkdown(markdown) {
    let html = markdown;
    
    // Headers
    html = html.replace(/^### (.*$)/gim, '<h3>$1</h3>');
    html = html.replace(/^## (.*$)/gim, '<h2>$1</h2>');
    html = html.replace(/^# (.*$)/gim, '<h1>$1</h1>');
    
    // Bold
    html = html.replace(/\*\*(.*?)\*\*/gim, '<strong>$1</strong>');
    
    // Italic
    html = html.replace(/\*(.*?)\*/gim, '<em>$1</em>');
    
    // Links
    html = html.replace(/\[(.*?)\]\((.*?)\)/gim, '<a href="$2">$1</a>');
    
    // Line breaks
    html = html.replace(/\n/gim, '<br>');
    
    return html;
}

// Form Validation
function validateBlogForm() {
    const title = document.getElementById('title');
    const content = document.getElementById('content');
    
    if (!title || !content) return true;
    
    let isValid = true;
    let errors = [];
    
    if (title.value.trim() === '') {
        errors.push('Title is required');
        isValid = false;
    }
    
    if (content.value.trim() === '') {
        errors.push('Content is required');
        isValid = false;
    }
    
    if (!isValid) {
        alert('Please fix the following errors:\n' + errors.join('\n'));
    }
    
    return isValid;
}

// Character Counter for Textarea
function initCharCounter() {
    const textarea = document.getElementById('content');
    
    if (textarea) {
        const counter = document.createElement('div');
        counter.id = 'char-counter';
        counter.style.textAlign = 'right';
        counter.style.color = '#7f8c8d';
        counter.style.fontSize = '0.9rem';
        counter.style.marginTop = '0.5rem';
        
        textarea.parentNode.appendChild(counter);
        
        function updateCounter() {
            const count = textarea.value.length;
            counter.textContent = `${count} characters`;
        }
        
        textarea.addEventListener('input', updateCounter);
        updateCounter();
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    initMarkdownPreview();
    initCharCounter();
});

// Smooth Scroll
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