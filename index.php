<?php
header('X-Content-Type-Options: nosniff'); // 防止 MIME 類型猜測
header('Cache-Control: no-cache, must-revalidate'); // 禁止緩存但允許重新驗證
header('Content-Type: text/html; charset=utf-8'); // 明確設置內容類型

session_start();

$dataFile = '/tmp/data.json';
$data = file_exists($dataFile) ? file_get_contents($dataFile) : '[]';
$data = json_decode($data, true);

$password = isset($_POST['password']) ? $_POST['password'] : null;
$mes = isset($_POST['messenger']) ? $_POST['messenger'] : null;

date_default_timezone_set('Asia/Taipei');
$currentTime = date('Y-m-d H:i:s'); // time


if (!isset($_SESSION['page'])) {
    $_SESSION['page'] = 0;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_SESSION['page'] == 0 || $_SESSION['page'] == -1) { // login
        if (isset($password)) {
            if ($password == '1030') {
                $_SESSION['name'] = 'Sally';
                $_SESSION['page'] = 1; // success login
            } elseif ($password == 'simple') {
                $_SESSION['name'] = 'XXXXX';
                $_SESSION['page'] = 1; // success login
            } else {
                $_SESSION['page'] = -1; // bad login
            }
        }
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } elseif ($_SESSION['page'] == 1) { // save mes
        if (isset($_SESSION['name']) && isset($mes)) {
            $data[] = ['name' => $_SESSION['name'], 'message' => $mes, 'bool' => 1, 'time' => $currentTime];
            file_put_contents($dataFile, json_encode($data)); // save mes in /tmp/data.json
        }
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}
$page = $_SESSION['page']; // ensure page correct
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>A Page?</title>
</head>
<!--background start-->
<div class="stars"></div>
<!--background end-->

<?php if ($page == 0 || $page == -1) { ?>
    <!--login page start-->
    <div class="container">
        <div class="form-control">
            <form method="POST">
                <input type="password" name="password" required>
                <label>
                    <span></span><span></span>
                    <span style="transition-delay:0ms">P</span><span style="transition-delay:50ms">a</span><span
                        style="transition-delay:100ms">s</span><span style="transition-delay:150ms">s</span><span
                        style="transition-delay:200ms">w</span><span style="transition-delay:250ms">o</span><span
                        style="transition-delay:300ms">r</span><span style="transition-delay:350ms">d</span>
                    <span style="transition-delay:400ms">(</span><span style="transition-delay:400ms">b</span><span
                        style="transition-delay:300ms">i</span><span style="transition-delay:350ms">r</span><span
                        style="transition-delay:200ms">t</span><span style="transition-delay:250ms">h</span><span
                        style="transition-delay:100ms">d</span><span style="transition-delay:150ms">a</span><span
                        style="transition-delay:0ms">y</span><span style="transition-delay:50ms">)</span>
                </label>
        </div>
        <button class="ubt">Unlock</button>
        </form>
        <?php if ($page == -1) { ?>
            <p style="color: red;"><?= "!!! Wrong password !!!" ?></p>
        <?php } ?>
    </div>
    <!--login page end-->
<?php } ?>

<!--------------------------------------------------------------------------------------------------->




<?php if ($page == 1 && isset($_SESSION['name'])) { ?>
<!--unlock page start-->
    <div>
        <div>
            <div class="chat-container" id="chat-container">
                <?php
                foreach ($data as $msgs) {
                    $name = htmlentities($msgs['name']);
                    $msg = isset($msgs['message']) ? htmlentities($msgs['message']) : 'No message'; // check have message or not
            
                    if ($msgs['bool'] === 1) { // unrecalled
                        if ($_SESSION['name'] === $msgs['name']) {
                            // self message
                            echo "<div class='message-self'>";
                            echo "<form method='POST' action='recall_message.php' class='text-form'>";
                            echo "<input type='hidden' name='time' value='" . htmlentities($msgs['time']) . "'>";
                            echo "<div class='name-self'>$name</div>";
                            echo "<div class='text-self'>$msg</div>";
                            echo '<button type="submit">收回</button>';
                            echo "</form>"; // close form
                        } else {
                            // other's message
                            echo "<div class='message-post'>";
                            echo "<div class='name'>$name</div>";
                            echo "<div class='text'>$msg</div>";
                        }
                        echo "</div>"; // close message-self or message-post
                    } else { // recalled
                        if ($_SESSION['name'] === $msgs['name']) {
                            // self message
                            echo "<div class='message-self'>";
                            echo "<div class='name-self'>$name</div>";
                        } else {
                            // other's message
                            echo "<div class='message-post'>";
                            echo "<div class='name'>$name</div>";
                        }

                        echo '<div class="text" style="color: gray;">[訊息已收回]</div>';
                        echo "</div>"; // close message-post
                    }
                }
                ?>
            </div>
            <div class="input-area">
                <form method="POST">
                    <div class="messageBox">
                        <div class="fileUploadWrapper">
                        </div>
                        <input id="message" name="messenger" type="text" placeholder="Message..." required="" />
                        <button id="send-btn" type="submit">
                            <svg viewBox="0 0 664 663" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M646.293 331.888L17.7538 17.6187L155.245 331.888M646.293 331.888L17.753 646.157L155.245 331.888M646.293 331.888L318.735 330.228L155.245 331.888"
                                    fill="none"></path>
                                <path
                                    d="M646.293 331.888L17.7538 17.6187L155.245 331.888M646.293 331.888L17.753 646.157L155.245 331.888M646.293 331.888L318.735 330.228L155.245 331.888"
                                    stroke="#6c6c6c" stroke-width="33.67" stroke-linecap="round" stroke-linejoin="round">
                                </path>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="container">
            <form method="POST" action="logout.php">
                <button class="logout" type="submit">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M4.5 12h15m0 0l-6.75-6.75M19.5 12l-6.75 6.75"></path>
                    </svg>
                    <div class="logout-text">
                        Logout
                    </div>
                </button>
            </form>
        </div>
    </div>
    <!--unlock page end-->
<?php } ?>

<script>
    document.body.addEventListener('touchmove', function (event) {
        if (!chatBox.contains(event.target)) {
            event.preventDefault();
        }
    }, { passive: false });

    const chatBox = document.querySelector('.chat-container');
    chatBox.addEventListener('touchmove', function (event) {
        event.stopPropagation();
    }, { passive: false });
</script>
</body>

</html>