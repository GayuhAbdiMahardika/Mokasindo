<!DOCTYPE html>
<html>
<head>
    <title>Upload ke Facebook</title>
</head>
<body>
    <h1>POST GAMBAR VIA URL</h1>

    <form method="POST" action="/fb/post-url">
        @csrf
        <input type="text" name="image_url" placeholder="Masukkan URL Gambar" required>
        <br><br>

        <input type="text" name="caption" placeholder="Tulis Caption">
        <br><br>

        <button type="submit">POST URL KE FACEBOOK</button>
    </form>

    <hr>

    <h1>UPLOAD GAMBAR DARI FILE</h1>

    <form method="POST" action="/fb/post-upload" enctype="multipart/form-data">
        @csrf
        <input type="file" name="image" required>
        <br><br>

        <input type="text" name="caption" placeholder="Tulis Caption">
        <br><br>

        <button type="submit">UPLOAD FILE KE FACEBOOK</button>
    </form>

</body>
</html>
