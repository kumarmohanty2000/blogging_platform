import {process} from './process.js'

document.addEventListener('DOMContentLoaded', function() {
    const username = localStorage.getItem('username');
    document.getElementById('username').textContent = username;



    document.getElementById('addBlog').addEventListener('click', function() {
      document.getElementById('addBlogForm').style.display = 'block';
      document.getElementById('blogList').style.display = 'none';
    });


    //get all the existing blogs here
    document.getElementById('viewBlogs').addEventListener('click', function() {
      document.getElementById('addBlogForm').style.display = 'none';
      document.getElementById('blogList').style.display = 'block';

      fetch(`${process.backend_server}/api/get_blogs.php`)
      .then(response => response.json())
      .then(data => {
          const blogsContainer = document.getElementById('blogs');
          blogsContainer.innerHTML = '';

          data.forEach(blog => {
              const li = document.createElement('li');
              
              const blogDetails = document.createElement('div');
              blogDetails.innerHTML = `<h3>${blog.title}</h3>
                                       <p>${blog.content}</p>
                                       <p><strong>Published on:</strong> ${blog.publish_date}</p>
                                       <p><strong>Categories:</strong> ${blog.categories.join(', ')}</p>
                                       <p><strong>Tags:</strong> ${blog.tags.join(', ')}</p>`;

              const updateBtn = document.createElement('button');
              updateBtn.textContent = 'Update';
              updateBtn.classList.add('updateBtn');
              updateBtn.setAttribute('data-id', blog.id);

              const deleteBtn = document.createElement('button');
              deleteBtn.textContent = 'Delete';
              deleteBtn.classList.add('deleteBtn');
              deleteBtn.setAttribute('data-id', blog.id);

              li.appendChild(blogDetails);
              li.appendChild(updateBtn);
              li.appendChild(deleteBtn);
              blogsContainer.appendChild(li);
          });
      });
    });
  

  //update the blog
  document.getElementById('updateBtn').addEventListener('click', function() {
      const postId = this.getAttribute('data-id');
      window.location.href = `update_blog.html?id=${postId}`;
  });
  
  //delete the blog
  document.getElementById('deleteBtn').addEventListener('click', function() {
      const postId = this.getAttribute('data-id');
      if (confirm('Are you sure you want to delete this post?')) {
          fetch(`${process.backend_server}/api/delete_blog.php`, {
              method: 'POST',
              headers: {
                  'Content-Type': 'application/json',
              },
              body: JSON.stringify({ post_id: postId })
          })
          .then(response => response.json())
          .then(data => {
              if (data.success) {
                  alert('Post deleted successfully');
                  li.remove();
              } else {
                  alert('Failed to delete post');
              }
          });
      }
  });

  //fetch all the catagories list for concern
  fetch(`${process.backend_server}/api/get_categories.php`)
      .then(response => response.json())
      .then(data => {
        const categorySelect = document.getElementById('categories');
        data.forEach(category => {
          const option = document.createElement('option');
          option.value = category.id;
          option.textContent = category.name;
          categorySelect.appendChild(option);
        });
      });

    //submit the new blog post
    document.getElementById('createPostForm').addEventListener('submit', function(e) {
      e.preventDefault();
      const title = document.getElementById('title').value;
      const content = document.getElementById('content').value;
      const categories = Array.from(document.getElementById('categories').selectedOptions).map(opt => opt.value);
      const tags = document.getElementById('tags').value.split(',').map(tag => tag.trim());

      fetch(`${process.backend_server}/api/add_blog.php`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          title: title,
          content: content,
          categories: categories,
          tags: tags
        })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert('Post created successfully');
        } else {
          alert('Failed to create post');
        }
      });
    });

    //logout the page
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
  });

