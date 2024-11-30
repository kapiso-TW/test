<?php
session_start();
session_destroy(); // 清除所有 Session
header('Location: index.php'); // 返回登入頁面
exit;
