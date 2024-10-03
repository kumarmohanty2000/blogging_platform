import {process} from './process.js'

document.addEventListener('DOMContentLoaded', function() {
    const username = localStorage.getItem('username');
    document.getElementById('username').textContent = username;

    document.getElementById('addBlog').addEventListener('click', function() {
      document.getElementById('addBlogForm').style.display = 'block';
      document.getElementById('blogList').style.display = 'none';
    });

    document.getElementById('viewBlogs').addEventListener('click', function() {
      document.getElementById('addBlogForm').style.display = 'none';
      document.getElementById('blogList').style.display = 'block';

      fetch(`${process.backend_server}/api/get_blogs.php`)
        .then(response => response.json())
        .then(data => {
          const blogs = document.getElementById('blogs');
          blogs.innerHTML = '';
          data.forEach(blog => {
            const li = document.createElement('li');
            li.textContent = `${blog.title} - ${blog.content}`;
            blogs.appendChild(li);
          });
        });
    });

    document.getElementById('submitBlog').addEventListener('click', function() {
      const title = document.getElementById('blogTitle').value;
      const content = document.getElementById('blogContent').value;

      fetch(`${process.backend_server}/api/add_blog.php`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ title: title, content: content })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert('Blog added successfully');
          document.getElementById('blogTitle').value = '';
          document.getElementById('blogContent').value = '';
        } else {
          alert('Failed to add blog');
        }
      });
    });
  });

  document.getElementById('logoutBtn').addEventListener('click', function(e) {
    e.preventDefault();
    
    fetch(`${process.backend_server}/api/logout.php`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            localStorage.removeItem('username');
            window.location.href = 'login.html';
        } else {
            alert('Logout failed');
        }
    });
});

