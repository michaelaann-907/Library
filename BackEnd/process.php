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

// Define the Author, Book and BookAuthor table creation SQL
$createAuthorTableSQL = "CREATE TABLE IF NOT EXISTS Author (
    authorID int UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    firstName varchar(30) NOT NULL,
    lastName varchar(30) NOT NULL
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

// Collect data from the form
$authorFirstName = $_POST['authorFirstName'];
$authorLastName = $_POST['authorLastName'];
$bookTitle = $_POST['bookTitle'];
$bookPrice = $_POST['price']; // Update the variable name to match the HTML form field name

// Insert data into the Author table
$authorSQL = "INSERT INTO Author (firstName, lastName) VALUES ('$authorFirstName', '$authorLastName')";

if ($conn->query($authorSQL) === TRUE) {
    // Get the last inserted author ID
    $lastAuthorID = $conn->insert_id;

    // Insert data into the Book table
    $bookSQL = "INSERT INTO Book (title, price) VALUES ('$bookTitle', $bookPrice)";

    if ($conn->query($bookSQL) === TRUE) {
        // Get the last inserted book ID
        $lastBookID = $conn->insert_id;

        // Insert data into the BookAuthor table
        $bookAuthorSQL = "INSERT INTO BookAuthor (bookId, authorId) VALUES ($lastBookID, $lastAuthorID)";

        if ($conn->query($bookAuthorSQL) === TRUE) {
            echo "Data inserted successfully.";
        } else {
            echo "Error inserting data into BookAuthor table: " . $conn->error;
        }
    } else {
        echo "Error inserting book data: " . $conn->error;
    }
} else {
    echo "Error inserting author data: " . $conn->error;
}

$conn->close();
?>
