function preview_image(event) {

    const output = document.getElementById('photo'),

        FileSize = event.target.files[0].size / 1024 / 1024; // in MB
    if (FileSize > 2) {
        alert('File size exceeds 2 MB');
        document.getElementById('fileToUpload').value = '';
        output.setAttribute('src', 'public/img/you_photo_here.png');
    }
}
