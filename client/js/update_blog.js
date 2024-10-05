import { process } from "./process";

const urlParams = new URLSearchParams(window.location.search);
const postId = urlParams.get('id');

document.addEventListener('DOMContentLoaded', function() {
    fetch(`${process.backend_server}/api/get_blogs.php?id=${postId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('title').value = data.title;
            document.getElementById('content').value = data.content;
            document.getElementById('postId').value = data.id;

            fetch(`${process.backend_server}/api/get_categories.php`)
                .then(response => response.json())
                .then(categoriesData => {
                    const categoriesSelect = document.getElementById('categories');
                    categoriesData.forEach(category => {
                        const option = document.createElement('option');
                        option.value = category.id;
                        option.textContent = category.name;
                        if (data.categories.includes(category.name)) {
                            option.selected = true;
                        }
                        categoriesSelect.appendChild(option);
                    });
                });
        });
});

document.getElementById('updateBlogForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const formData = new FormData(this);

    fetch(`${process.backend_server}/api/update_blog.php`, { 
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Blog post updated successfully');
            window.location.href = 'view_blogs.html';
        } else {
            alert('Failed to update blog post');
        }
    });
});