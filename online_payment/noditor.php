<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quill Editor with Image Upload</title>
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
</head>
<body>
    <!-- Create the editor container -->
    <div id="editor-container"></div>

    <!-- Form for saving content -->
    <form id="content-form" action="save.php" method="POST">
        <input type="hidden" name="content" id="editor-content">
        <button type="submit">Save</button>
    </form>

    <!-- Quill JS -->
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script>
        var quill = new Quill('#editor-container', {
            theme: 'snow',
            modules: {
                toolbar: {
                    container: [
                        [{ 'header': '1'}, { 'header': '2' }],
                        ['bold', 'italic', 'underline'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        [{ 'script': 'sub'}, { 'script': 'super' }],
                        [{ 'indent': '-1'}, { 'indent': '+1' }],
                        [{ 'direction': 'rtl' }],
                        ['link', 'image']
                    ],
                    handlers: {
                        image: imageHandler
                    }
                }
            }
        });

        function imageHandler() {
        var range = quill.getSelection();
        var input = document.createElement('input');
        input.setAttribute('type', 'file');
        input.setAttribute('accept', 'image/*');
        input.addEventListener('change', function() {
            var file = input.files[0];
            var reader = new FileReader();
            reader.onload = function() {
                // Upload image
                var formData = new FormData();
                formData.append('image', file);
                
                fetch('upload.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.url) {
                        quill.insertEmbed(range.index, 'image', data.url);
                    } else {
                        console.error('Image upload failed:', data.error);
                    }
                })
                .catch(error => console.error('Error:', error));
            };
            reader.readAsDataURL(file);
        });
        input.click();
    }

        // Get form and editor elements
        const form = document.getElementById('content-form');
        const editorContent = document.getElementById('editor-content');

        form.addEventListener('submit', function(e) {
            // Set the hidden input value to the editor content
            editorContent.value = JSON.stringify(quill.getContents());
        });
    </script>
</body>
</html>
