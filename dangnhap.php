<?php
session_start();

$email_err = $login_err = "";

if (isset($_POST['spck'])) {
    $email = trim($_POST['email']);
    $input_password = trim($_POST['matkhau']);

    if (empty($email) || empty($input_password)) {
        $login_err = "Vui lòng nhập đầy đủ email và mật khẩu.";
    } else {
        include "connect.php"; // Kết nối cơ sở dữ liệu

        // 1. Chuẩn bị truy vấn: Lấy thông tin người dùng dựa trên email
        $sql = "SELECT id, name, password FROM users WHERE email = ?";
        
        // Sử dụng prepared statement để bảo mật
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                // 2. Lấy hàng dữ liệu người dùng
                $user = $result->fetch_assoc();
                $hashed_password_from_db = $user['password'];

                // 3. XÁC MINH MẬT KHẨU bằng password_verify()
                if (password_verify($input_password, $hashed_password_from_db)) {
                    // Mật khẩu KHỚP! Đăng nhập thành công.
                    $_SESSION['loggedin'] = TRUE;
                    $_SESSION['id'] = $user['id'];
                    $_SESSION['name'] = $user['name'];
                    $_SESSION['email'] = $user['email'];

                    // Chuyển hướng người dùng đến trang trang chủ
                    header("Location: trangchu.php"); 
                    exit();
                } else {
                    // Mật khẩu KHÔNG KHỚP
                    $login_err = "Mật khẩu không chính xác.";
                }
            } else {
                // Email không tồn tại
                $login_err = "Email này chưa được đăng ký.";
            }
            $stmt->close();
        } else {
            $login_err = "Đã xảy ra lỗi hệ thống. Vui lòng thử lại sau.";
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
  <title>Đăng Nhập</title>
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

  .error-msg {
    color: red;
    margin-top: 10px;
    text-align: center;
    font-size: 14px;
  }
</style>

</head>
<body>
  <div class="khung">
    <h1 id="tieuDe">Đăng Nhập Tài Khoản</h1>

    <form id="Form" action="" method="post">
      <div class="group">
        <label>Email người dùng <span>*</span></label>
        <input type="email" name="email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>" required>
      </div>

      <div class="group">
        <label>Mật khẩu <span>*</span></label>
        <div class="pass-wrapper">
          <input type="password" id="matkhau" name="matkhau" required>
          <button type="button" class="toggle-btn" id="btnPass1" onclick="togglePassword('matkhau', 'btnPass1')">Hiện</button>
        </div>
      </div>

      <button type="submit" name="spck">Đăng Nhập</button>
      
      <?php if (!empty($login_err)): ?>
        <p class="error-msg"><?php echo $login_err; ?></p>
      <?php endif; ?>
    </form>
    
    <p class="chuyenLk">
      Bạn chưa có tài khoản? <a href="dangki1.php">Đăng ký ngay</a>
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
  </script>

</body>
</html>