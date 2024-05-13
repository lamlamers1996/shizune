<!DOCTYPE html>
<html>
<head>
    <title>Quản lý Proxy</title>
</head>
<body>
    <h2>Danh sách Proxy</h2>
    <table>
        <tr>
            <th>IP</th>
            <th>User</th>
            <th>Password</th>
            <th>Thao tác</th>
        </tr>
        <?php
        // Thực hiện đọc tất cả các file .txt trong thư mục ip
        $ipDirectory = '/root/ip/';
        $ipFiles = glob($ipDirectory . '*.txt');
        foreach ($ipFiles as $file) {
            $fileData = file($file);
            list($ip, $user, $pass) = explode('|', trim($fileData[0]));
            echo "<tr>";
            echo "<td>$ip</td>";
            echo "<td>$user</td>";
            echo "<td>$pass</td>";
            echo "<td>";
            echo "<form method='post' action='change_user.php'>";
            echo "<input type='hidden' name='ip' value='$ip'>";
            echo "<input type='text' name='newUser' placeholder='New user'>";
            echo "<input type='text' name='newPass' placeholder='New password'>";
            echo "<input type='submit' value='Thay đổi'>";
            echo "</form>";
            echo "<form method='post' action='delete_user.php'>";
            echo "<input type='hidden' name='ip' value='$ip'>";
            echo "<input type='submit' value='Xóa'>";
            echo "</form>";
            echo "</td>";
            echo "</tr>";
        }
        ?>
    </table>
    <h2>Thêm Proxy mới</h2>
    <form method="post" action="add_user.php">
        <input type="text" name="ip" placeholder="IP">
        <input type="text" name="user" placeholder="User">
        <input type="text" name="pass" placeholder="Password">
        <input type="submit" value="Thêm">
    </form>
</body>
</html>
