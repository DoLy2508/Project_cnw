<?php
$nameerr = $emailerr = $passerr = $confirmerr = "";
$name_value = $email_value = "";

if (isset($_POST['spck'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $pass = trim($_POST['matkhau']);
    $confirm = trim($_POST['xacNhan']);
    
    // Giữ giá trị để hiển thị lại
    $name_value = $name;
    $email_value = $email;

    // Kiểm tra họ tên
    if (empty($name)) {
        $nameerr = "Vui lòng nhập họ và tên của bạn.";
    }

    // Kiểm tra email
    if (empty($email)) {
        $emailerr = "Vui lòng nhập email.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $emailerr = "Email không hợp lệ.";
    }

    // Kiểm tra mật khẩu
    if (empty($pass)) {
        $passerr = "Vui lòng nhập mật khẩu.";
    } elseif (strlen($pass) < 6) {
        $passerr = "Mật khẩu phải có ít nhất 6 ký tự.";
    } elseif (!preg_match('/[A-Za-z]/', $pass) || !preg_match('/[0-9]/', $pass)) {
        $passerr = "Mật khẩu phải bao gồm cả chữ và số.";
    }

    // Kiểm tra nhập lại mật khẩu
    if (empty($confirm)) {
        $confirmerr = "Vui lòng nhập lại mật khẩu.";
    } elseif ($pass !== $confirm) {
        $confirmerr = "Mật khẩu nhập lại không khớp.";
    }

    // Nếu không có lỗi
    if (empty($nameerr) && empty($emailerr) && empty($passerr) && empty($confirmerr)) {
        include "connect.php";

        // Kiểm tra email đã tồn tại chưa
        $check_sql = "SELECT id FROM users WHERE email = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            $emailerr = "Email này đã tồn tại, vui lòng dùng email khác.";
            $check_stmt->close();
        } else {
            $check_stmt->close();
            
            // MÃ HÓA MẬT KHẨU
            $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);

            // Lưu vào database
            $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $name, $email, $hashed_pass);

            if ($stmt->execute()) {
                $stmt->close();
                $conn->close();
                
                // Chuyển hướng đến trang đăng nhập
                header("Location: dangnhap.php");
                exit();
            } else {
                $emailerr = "Có lỗi xảy ra: " . $conn->error;
                $stmt->close();
            }
        }
        
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Đăng Ký</title>
<style>
  body {
    font-family: Arial, sans-serif;
    background-image: url('Image/noel.jpg');
    background-size: cover;
    margin: 0;
    padding: 0;
  }

  .khung {
    width: 420px;
    margin: 70px auto;
    background-color: white;
    padding: 25px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  }

  h1 {
    text-align: center;
    margin-bottom: 20px;
  }

  .group {
    margin-bottom: 18px;
  }

  label {
    font-weight: bold;
    display: block;
    margin-bottom: 6px;
  }

  label span {
    color: red;
  }

  input {
    width: 100%;
    padding: 10px;
    border-radius: 5px;
    border: 1px solid rgb(170,170,170);
    font-size: 15px;
    box-sizing: border-box;
  }

  small {
    font-size: 12px;
    color: gray;
  }

  button {
    background-color: #bc2626;
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    width: 100%;
    margin-top: 10px;
  }

  .chuyenLk {
    color: #007BFF;
    cursor: pointer;
    text-align: center;
    display: block;
    margin-top: 10px;
  }
  
  .pass-wrapper {
    position: relative;
  }

  .toggle-btn {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    cursor: pointer;
    color: gray;
    font-size: 14px;
    display: none;
    padding: 0;
    width: auto;
  }
  
  .error-text {
    color: red;
    margin: 5px 0 0;
    font-size: 14px;
  }
</style>

</head>
<body>
  <div class="khung">
    <h1 id="tieuDe">Đăng Ký Tài Khoản</h1>

    <form id="Form" action="" method="post">
      <div class="group">
        <label>Họ và tên <span>*</span></label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($name_value); ?>">
        <?php if (!empty($nameerr)): ?>
          <p class="error-text"><?php echo $nameerr; ?></p>
        <?php endif; ?>
      </div>

      <div class="group">
        <label>Email người dùng <span>*</span></label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($email_value); ?>">
        <?php if (!empty($emailerr)): ?>
          <p class="error-text"><?php echo $emailerr; ?></p>
        <?php endif; ?>
      </div>

      <div class="group">
        <label>Mật khẩu <span>*</span></label>
        <div class="pass-wrapper">
          <input type="password" id="matkhau" name="matkhau">
          <button type="button" class="toggle-btn" id="btnPass1" onclick="togglePassword('matkhau', 'btnPass1')">Hiện</button>
        </div>
        <?php if (!empty($passerr)): ?>
          <p class="error-text"><?php echo $passerr; ?></p>
        <?php endif; ?>
        <small>Độ dài mật khẩu tối thiểu 6 ký tự, gồm cả chữ và số.</small>
      </div>

      <div class="group">
        <label>Nhập lại mật khẩu <span>*</span></label>
        <div class="pass-wrapper">
          <input type="password" id="xacnhan" name="xacNhan">
          <button type="button" class="toggle-btn" id="btnPass2" onclick="togglePassword('xacnhan', 'btnPass2')">Hiện</button>
        </div>
        <?php if (!empty($confirmerr)): ?>
          <p class="error-text"><?php echo $confirmerr; ?></p>
        <?php endif; ?>
      </div>

      <button type="submit" name="spck">Đăng Ký</button>  
    </form>
    
    <p class="chuyenLk">
      Bạn đã có tài khoản? <a href="dangnhap.php">Đăng nhập ngay</a>
    </p>
  </div>

  <script>
    function togglePassword(inputId, btnId) {
      const input = document.getElementById(inputId);
      const btn = document.getElementById(btnId);

      if (input.type === "password") {
        input.type = "text";
        btn.textContent = "Ẩn";
      } else {
        input.type = "password";
        btn.textContent = "Hiện";
      }
    }

    document.getElementById("matkhau").addEventListener("input", function() {
      document.getElementById("btnPass1").style.display = this.value ? "block" : "none";
    });

    document.getElementById("xacnhan").addEventListener("input", function() {
      document.getElementById("btnPass2").style.display = this.value ? "block" : "none";
    });
  </script>

</body>
</html>