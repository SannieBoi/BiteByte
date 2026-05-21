<?php
require_once 'config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$error_msg = "";
$success_msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm-password'] ?? '';

    if (empty($email) || empty($username) || empty($password) || empty($confirm_password)) {
        $error_msg = "Molimo vas da popunite sva polja.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_msg = "Format email adrese nije ispravan.";
    } elseif ($password !== $confirm_password) {
        $error_msg = "Lozinke se ne podudaraju.";
    } else {
        try {
            $check_sql = "SELECT Username, Email FROM users WHERE Username = :username OR Email = :email";
            $check_stmt = $pdo->prepare($check_sql);
            $check_stmt->execute([
                'username' => $username,
                'email'    => $email
            ]);
            
            if ($check_stmt->rowCount() > 0) {
                $error_msg = "Korisničko ime ili email su već u upotrebi.";
            } else {
                $hashed_password = password_hash($password, PASSWORD_BCRYPT);

                $insert_sql = "INSERT INTO users (Username, Email, Pass, RegDate) VALUES (:username, :email, :pass, NOW())";
                $insert_stmt = $pdo->prepare($insert_sql);
                
                $insert_stmt->execute([
                    'username' => $username,
                    'email'    => $email,
                    'pass'     => $hashed_password
                ]);

                header("Location: login.php");
                exit;
            }
        } catch (PDOException $e) {
            $error_msg = "Greška na serveru: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BiteByte - Sign Up</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/login-signup.css">
    
    <script src="../js/signup.js" defer></script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <style>
        .alert { padding: 12px; margin-bottom: 20px; border-radius: 6px; font-size: 14px; text-align: center; font-weight: bold; }
        .alert-danger { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>
    <main>
        <div class="login-signup-container">
            <div class="login-signup-top">
                <button class="back" onclick="history.back()" aria-label="Go back">
                    <i class="fas fa-arrow-left"></i>
                </button>
                <h2>Sign up for BiteByte</h2>
            </div>

            <?php if (!empty($error_msg)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error_msg); ?></div>
            <?php endif; ?>

            <form id="registration-form" action="signup.php" method="POST">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" id="email" name="email" placeholder="your@email.com" value="<?php echo htmlspecialchars($email ?? ''); ?>">
                    <small></small>
                </div>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username ?? ''); ?>">
                    <small></small>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password">
                    <small></small>
                </div>
                <div class="form-group">
                    <label for="confirm-password">Confirm password</label>
                    <input type="password" id="confirm-password" name="confirm-password">
                    <small></small>
                </div>
                <div class="form-group form-group--submit">
                    <button type="submit">Sign up</button>
                </div>
            </form>

            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </div>
    </main>
</body>
</html>