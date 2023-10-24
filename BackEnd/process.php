<?php
// Connect to the database
$host = "localhost"; // Change this to your MySQL server host
$username = "your_username"; // Change this to your MySQL username
$password = "your_password"; // Change this to your MySQL password
$database = "your_database"; // Change this to your MySQL database name

// Create a connection to the database
$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to create tables if they don't exist
function createTables($conn) {
    // Define the Author, Book and BookAuthor table creation SQL
    $createAuthorTableSQL = "CREATE TABLE IF NOT EXISTS Author (
        authorID int UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        firstName varchar(30) NOT NULL,
        lastName varchar(30) NOT NULL
        UNIQUE KEY unique_author (firstName, lastName) 
    )";

    $createBookTableSQL = "CREATE TABLE IF NOT EXISTS Book (
        bookID int UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        title varchar(255) NOT NULL,
        price decimal(10,2) NOT NULL
    )";

    $createBookAuthorTableSQL = "CREATE TABLE IF NOT EXISTS BookAuthor (
        bookId int UNSIGNED,
        authorId int UNSIGNED,
        PRIMARY KEY (bookId, authorId),
        FOREIGN KEY (bookId) REFERENCES Book (bookID),
        FOREIGN KEY (authorId) REFERENCES Author (authorID)
    )";

    // Execute the table creation queries
    if ($conn->query($createAuthorTableSQL) === TRUE) {
        echo "Author table created or already exists.<br>";
    } else {
        echo "Error creating Author table: " . $conn->error;
    }

    if ($conn->query($createBookTableSQL) === TRUE) {
        echo "Book table created or already exists.<br>";
    } else {
        echo "Error creating Book table: " . $conn->error;
    }

    if ($conn->query($createBookAuthorTableSQL) === TRUE) {
        echo "BookAuthor table created or already exists.<br>";
    } else {
        echo "Error creating BookAuthor table: " . $conn->error;
    }
}

// Function to insert data into tables
function insertData($conn, $authorFirstName, $authorLastName, $bookTitle, $bookPrice) {
    // Check if the author already exists in the Author table
    $checkAuthorSQL = "SELECT authorID FROM Author WHERE firstName = '$authorFirstName' AND lastName = '$authorLastName'";
    $result = $conn->query($checkAuthorSQL);

    if ($result->num_rows > 0) {
        // Author already exists, get the author ID
        $row = $result->fetch_assoc();
        $authorID = $row['authorID'];
    } else {
        // Author does not exist, insert data into the Author table
        $authorSQL = "INSERT INTO Author (firstName, lastName) VALUES ('$authorFirstName', '$authorLastName')";
        if ($conn->query($authorSQL) === TRUE) {
            $authorID = $conn->insert_id; // Get the last inserted author ID
        } else {
            echo "Error inserting author data: " . $conn->error;
            return;
        }
    }


    // Insert data into the Book table
    $bookSQL = "INSERT INTO Book (title, price) VALUES ('$bookTitle', $bookPrice)";

    if ($conn->query($bookSQL) === TRUE) {
        // Get the last inserted book ID
        $lastBookID = $conn->insert_id;

        // Insert data into the BookAuthor table
        $bookAuthorSQL = "INSERT INTO BookAuthor (bookId, authorId) VALUES ($lastBookID, $authorID)";

        if ($conn->query($bookAuthorSQL) === TRUE) {
            echo "Data inserted successfully.";
        } else {
            echo "Error inserting data into BookAuthor table: " . $conn->error;
        }
    } else {
        echo "Error inserting book data: " . $conn->error;
    }
}


// Function to clear all data from tables
function clearAllData($conn) {
    // Drop the Author, Book, and BookAuthor tables
    $dropAuthorTableSQL = "DROP TABLE IF EXISTS Author";
    $dropBookTableSQL = "DROP TABLE IF EXISTS Book";
    $dropBookAuthorTableSQL = "DROP TABLE IF EXISTS BookAuthor";

    if ($conn->query($dropBookAuthorTableSQL) === TRUE &&
        $conn->query($dropBookTableSQL) === TRUE &&
        $conn->query($dropAuthorTableSQL) === TRUE) {
        echo "All data cleared successfully.";
    } else {
        echo "Error clearing data: " . $conn->error;
    }

    // Recreate the tables
    createTables($conn);
}

// Function to fetch and display combined tables
function displayCombinedTableData($conn) {
    echo "<h1>Library Catalog - Database</h1>"; // Added header
    $sql = "SELECT
        B.bookID AS 'Book ID',
        BA.authorId AS 'Author ID',
        A.lastName AS 'Author Last Name',
        A.firstName AS 'Author First Name',
        B.title AS 'Book Title',
        B.price AS 'Price'
    FROM Book B
    LEFT JOIN BookAuthor BA ON B.bookID = BA.bookId
    LEFT JOIN Author A ON BA.authorId = A.authorID";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table border='1'><tr>";
        // Display table column names as table headers
        while ($fieldinfo = $result->fetch_field()) {
            echo "<th>".$fieldinfo->name."</th>";
        }
        echo "</tr>";

        // Display table data
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            foreach ($row as $key => $value) {
                echo "<td>" . $value . "</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "Table is empty.";
    }
}

// Function to fetch and display individual tables
function displayIndividualTables($conn) {
    echo "<div class='individual-table'>";
    echo "<h2>Author Table</h2>";
    $sql = "SELECT authorID AS 'Author ID', firstName AS 'Author First Name', lastName AS 'Author Last Name' FROM Author ORDER BY authorID ASC";
    displayTable($conn, $sql);

    echo "<h2>Book Table</h2>";
    $sql = "SELECT bookID AS 'Book ID', title AS 'Book Title', price AS 'Price' FROM Book";
    displayTable($conn, $sql);

    echo "<h2>BookAuthor Table</h2>";
    $sql = "SELECT bookId AS 'Book ID', authorId AS 'Author ID' FROM BookAuthor";
    displayTable($conn, $sql);

    echo "</div>";
}

function displayTable($conn, $sql) {
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table border='1'><tr>";
        // Display table column names as table headers
        while ($fieldinfo = $result->fetch_field()) {
            echo "<th>".$fieldinfo->name."</th>";
        }
        echo "</tr>";

        // Display table data
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            foreach ($row as $key => $value) {
                echo "<td>" . $value . "</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "Table is empty.";
    }
}

// Handle actions
if (isset($_GET['action'])) {
    if ($_GET['action'] === 'createTables') {
        createTables($conn);
    } elseif ($_GET['action'] === 'insertData') {
        $authorFirstName = $_POST['authorFirstName'];
        $authorLastName = $_POST['authorLastName'];
        $bookTitle = $_POST['bookTitle'];
        $bookPrice = $_POST['price']; // Update the variable name to match the HTML form field name
        insertData($conn, $authorFirstName, $authorLastName, $bookTitle, $bookPrice);
    } elseif ($_GET['action'] === 'fetchData') {
        displayCombinedTableData($conn);
    } elseif ($_GET['action'] === 'fetchIndividualTables') {
        displayIndividualTables($conn);
    } elseif ($_GET['action'] === 'clearAllData') {
        clearAllData($conn);
    }
}

$conn->close();
?>
