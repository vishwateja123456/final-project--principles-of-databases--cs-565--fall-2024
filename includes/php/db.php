<?php 
require_once 'config.php'; // Include configuration file for database credentials

/**
 * Establishes a connection to the database.
 *
 * @return PDO The PDO object for database connection.
 * @throws PDOException If the connection fails.
 */
function connectDB() {
    try {
        // Create a new PDO instance with error mode set to exception
        $dsn = "mysql:host={$GLOBALS['host']};dbname={$GLOBALS['dbname']};charset=utf8";
        $pdo = new PDO($dsn, $GLOBALS['user'], $GLOBALS['db_password']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }
}

/**
 * Inserts a new account into the database.
 *
 * @param string $appName The app name.
 * @param string|null $url The URL (optional).
 * @param string $userId The associated user ID.
 */
function insertAccount($appName, $url, $userId) {
    $pdo = connectDB();
    try {
        $stmt = $pdo->prepare("
            INSERT INTO Accounts (website_name, url, user_id) 
            VALUES (:appName, :url, :userId)
        ");
        $stmt->execute([
            ':appName' => $appName,
            ':url' => $url ?: null,
            ':userId' => $userId
        ]);
        echo "Account inserted successfully!<br>";
    } catch (PDOException $e) {
        die("Insert failed: " . $e->getMessage());
    }
}

/**
 * Searches for accounts in the database.
 *
 * @param string $search The search term.
 */
function searchAccounts($search) {
    $pdo = connectDB();
    try {
        $stmt = $pdo->prepare("
            SELECT 
                id, 
                website_name AS app_name, -- Alias for consistency
                url
            FROM Accounts
            WHERE website_name LIKE :search OR url LIKE :search
        ");
        $stmt->execute([':search' => "%$search%"]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($results) {
            displayTable($results);
        } else {
            echo "<div>No records found.</div>";
        }
    } catch (PDOException $e) {
        die("Search failed: " . $e->getMessage());
    }
}

/**
 * Deletes an account from the database.
 *
 * @param string $attribute The column to match (e.g., website_name).
 * @param string $pattern The value to match.
 */
function deleteAccount($attribute, $pattern) {
    $pdo = connectDB();
    try {
        $stmt = $pdo->prepare("DELETE FROM Accounts WHERE $attribute = :pattern");
        $stmt->execute([':pattern' => $pattern]);
        echo "Account deleted successfully!<br>";
    } catch (PDOException $e) {
        die("Delete failed: " . $e->getMessage());
    }
}

/**
 * Updates an account in the database.
 *
 * @param string $attributeToUpdate The column to update.
 * @param string $newValue The new value for the column.
 * @param string $queryAttribute The column to match for the WHERE clause.
 * @param string $pattern The value to match for the WHERE clause.
 */
function updateAccount($attributeToUpdate, $newValue, $queryAttribute, $pattern) {
    $pdo = connectDB();
    try {
        $stmt = $pdo->prepare("UPDATE Accounts SET $attributeToUpdate = :newValue WHERE $queryAttribute = :pattern");
        $stmt->execute([
            ':newValue' => $newValue,
            ':pattern' => $pattern
        ]);
        echo "Account updated successfully!<br>";
    } catch (PDOException $e) {
        die("Update failed: " . $e->getMessage());
    }
}

/**
 * Displays results in an HTML table.
 *
 * @param array $results The array of results to display.
 */
function displayTable($results) {
    echo "<table border='1' cellpadding='5' cellspacing='0' style='width: 100%; text-align: left;'>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>App Name</th>
                    <th>URL</th>
                </tr>
            </thead>
            <tbody>";
    foreach ($results as $row) {
        echo "<tr>
                <td>" . htmlspecialchars($row['id']) . "</td>
                <td>" . htmlspecialchars($row['app_name']) . "</td>
                <td>" . htmlspecialchars($row['url'] ?? 'NULL') . "</td>
              </tr>";
    }
    echo "  </tbody>
          </table>";
}
?>
