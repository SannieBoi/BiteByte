<?php
require_once 'config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$error_msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    if (empty($email) || empty($password)) {
        $error_msg = "Molimo vas da popunite sva polja.";
    } else {
        try {
            $sql = "SELECT UsersID, Username, Pass FROM users WHERE Email = :email";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch();
            if ($user && password_verify($password, $user['Pass'])) {
                $_SESSION['UsersID']   = $user['UsersID'];
                $_SESSION['username']  = $user['Username'];
                session_regenerate_id(true);
                header("Location: submit.php");
                exit;
                
            } else {
                $error_msg = "Pogrešan email ili lozinka.";
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
    <title>BiteByte - Login</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/login-signup.css">
    <script src="../js/script.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <style>
        .alert { padding: 10px; margin-bottom: 15px; border-radius: 4px; font-size: 14px; text-align: center; }
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
                <h2>Login to BiteByte</h2>
            </div>

            <?php if (!empty($error_msg)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error_msg); ?></div>
            <?php endif; ?>

            <form action="login.php" method="POST">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required placeholder="your@email.com" value="<?php echo htmlspecialchars($email ?? ''); ?>">
                    <small></small>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                    <small></small>
                </div>
                <div class="form-group form-group--submit">
                    <button type="submit">Login</button>
                </div>
            </form>

            <p>Don't have an account? <a href="signup.php">Sign up here</a>.</p>
        </div>
    </main>
</body>
</html>