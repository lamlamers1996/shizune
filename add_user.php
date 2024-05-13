<?php

// Kiểm tra xem có dữ liệu được gửi từ form không
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy thông tin từ form
    $ip = $_POST['ip'];
    $newUser = $_POST['user'];
    $newPass = $_POST['pass'];

    // Thực hiện đọc tệp để lấy thông tin SSH
    $filePath = "/root/ip/$ip.txt";
    if (file_exists($filePath)) {
        $fileData = file($filePath);
        list($sshIp, $sshUser, $sshPass) = explode('|', trim($fileData[0]));

        // Thực hiện kết nối SSH
        $connection = ssh2_connect($sshIp, 22);
        if ($connection) {
            // Đăng nhập bằng SSH key hoặc tên người dùng và mật khẩu
            if (ssh2_auth_password($connection, $sshUser, $sshPass)) {
                // Thực hiện việc thêm người dùng mới vào tệp secrets.txt
                $command = "echo \"$newUser $newPass\" >> /root/secrets.txt";
                ssh2_exec($connection, $command);

                // Đóng kết nối SSH
                ssh2_disconnect($connection);

                // Chạy lại systemctl để áp dụng thay đổi
                shell_exec("sudo systemctl daemon-reload");
                shell_exec("sudo systemctl restart gost");
                shell_exec("sudo systemctl status gost");

                // Chuyển hướng người dùng về trang index.php sau khi thêm người dùng mới
                header("Location: index.php");
                exit;
            } else {
                echo "Đăng nhập SSH không thành công.";
            }
        } else {
            echo "Không thể kết nối đến máy chủ SSH.";
        }
    } else {
        echo "Không tìm thấy tệp chứa thông tin SSH.";
    }
}

?>
