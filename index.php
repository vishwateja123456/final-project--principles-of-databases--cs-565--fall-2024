<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passwords Management App</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>Passwords Manager</h1>
    </header>

<?php
// Include necessary PHP files
require_once "includes/php/config.php";
require_once "includes/php/db.php";

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $option = $_POST["submitted"] ?? null;

    switch ($option) {
        case 'SEARCH':
            $search = $_POST["search"] ?? '';
            if (!empty($search)) {
                searchAccounts($search);
            } else {
                echo '<div id="error">Search field is empty. Please try again.</div>';
            }
            break;

            case 'INSERT_ACCOUNT':
                // Collect and validate form data
                $appName = $_POST['app_name'] ?? null;
                $url = $_POST['url'] ?? null;
                $comment = $_POST['comment'] ?? null;
                $firstName = $_POST['first_name'] ?? null;
                $lastName = $_POST['last_name'] ?? null;
                $username = $_POST['username'] ?? null;
                $email = $_POST['email'] ?? null;
                $password = $_POST['password'] ?? null;
            
                $missingFields = [];
                if (!$appName) $missingFields[] = "App Name";
                if (!$firstName) $missingFields[] = "First Name";
                if (!$lastName) $missingFields[] = "Last Name";
                if (!$username) $missingFields[] = "Username";
                if (!$email) $missingFields[] = "Email";
                if (!$password) $missingFields[] = "Password";
            
                if (empty($missingFields)) {
                    try {
                        $pdo = connectDB();
            
                        // Find user_id from users table
                        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
                        $stmt->execute([':email' => $email]);
                        $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
                        if (!$user) {
                            echo "<div id='error'>User with email '$email' does not exist in the database.</div>";
                        } else {
                            $userId = $user['id'];
            
                            // Insert into accounts table
                            $stmt = $pdo->prepare("
                                INSERT INTO accounts (user_id, website_name, url, comment, first_name, last_name, username, email, password, created_at)
                                VALUES (:user_id, :app_name, :url, :comment, :first_name, :last_name, :username, :email, AES_ENCRYPT(:password, 'secret_key'), NOW())
                                ON DUPLICATE KEY UPDATE
                                    url = VALUES(url),
                                    comment = VALUES(comment),
                                    first_name = VALUES(first_name),
                                    last_name = VALUES(last_name),
                                    username = VALUES(username),
                                    password = AES_ENCRYPT(:password, 'secret_key'),
                                    created_at = NOW()
                            ");
                            $stmt->execute([
                                ':user_id' => $userId,
                                ':app_name' => $appName,
                                ':url' => $url ?: null,
                                ':comment' => $comment ?: null,
                                ':first_name' => $firstName,
                                ':last_name' => $lastName,
                                ':username' => $username,
                                ':email' => $email,
                                ':password' => $password
                            ]);
            
                            echo "<div>Account inserted or updated successfully!</div>";
                        }
                    } catch (PDOException $e) {
                        echo "<div id='error'>Operation failed: " . $e->getMessage() . "</div>";
                    }
                } else {
                    echo '<div id="error">Please fill in the following required fields: ' . implode(", ", $missingFields) . '.</div>';
                }
                break;
            
            

        case 'DELETE_ACCOUNT':
            $attribute = $_POST["current-attribute"] ?? '';
            $pattern = $_POST["pattern"] ?? '';
            if ($attribute && $pattern) {
                deleteAccount($attribute, $pattern);
                echo "<div>Account deleted successfully!</div>";
            } else {
                echo '<div id="error">Please provide valid details to delete.</div>';
            }
            break;

        case 'UPDATE_ACCOUNT':
            $attribute = $_POST["current-attribute-name"] ?? '';
            $newValue = $_POST["new-attribute"] ?? '';
            $queryAttribute = $_POST["query-attribute"] ?? '';
            $pattern = $_POST["pattern"] ?? '';
            if ($attribute && $newValue && $queryAttribute && $pattern) {
                updateAccount($attribute, $newValue, $queryAttribute, $pattern);
                echo "<div>Account updated successfully!</div>";
            } else {
                echo '<div id="error">Update form is incomplete.</div>';
            }
            break;

        default:
            echo '<div id="error">Invalid action submitted.</div>';
            break;
    }
}

// Include HTML Forms
require_once "includes/html/search-form.html";
require_once "includes/html/insert-form.html";
require_once "includes/html/update-form.html";
require_once "includes/html/delete-form.html";
?>

</body>
</html>
