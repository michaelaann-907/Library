# Library
<br/>
Program Description: This program is a web-based library catalog system that allows users to add and view books and their associated authors. It ensures that each author is uniquely assigned an authorID.  
<br/>
<br/>
HTML File Description: The HTML file in this program serves as the user interface for interacting with the library catalog system. It provides input fields to add new books and authors, displays the catalog's combined table, and allows users to clear all data from the database.  
<br/>  
<br/>  
<br/>  
<br/>
<br/>  

HTML Form
<br/>
<img width="499" alt="library catalog db" src="https://github.com/michaelaann-907/Library/assets/114198365/2bfa364d-f118-438a-acd5-eb769c58e788">

<br/>  
<br/>  


Each time a new author is added, a distinct `authorId` is assigned to maintain a one-to-one relationship with
the author. This approach avoids complications when dealing with multiple books by the same author within the
database.


As you can see below, the author, William Shakespeare is assigned an authorid. The id number 1 references
William Shakespeare and will be used as the authorid for any books by William Shakespeare.


<br/>
<br/>  

# Set Up / Connecting to your MySQL Account
Edit the following variables in the `process.php` file in the backend folder. Change the variables inside the file to 
your credentials.

```
$host = "localhost"; // Change this to your MySQL server host  
$username = "jdoe"; // Change this to your MySQL username  
$password = "secret"; // Change this to your MySQL password  
$database = "jdoe"; // Change this to your MySQL database name  
```
<br/>


# Server Use 
Move the `process.php` file in the BackEnd folder to the FrontEnd folder. The different directory for that file 
does not work well on the server since the program is operating within the FrontEnd directory. This may be due to
web server configuration, so this is a way to fix this. 

<br/>


# Combined Tables
This table is located only on the `index.html` page and is not a table created/found within your MySQL database. This combines all the tables into one big table on the `index.html` table to make it easier for the user to read all the tables and mimics the database as a whole.



<img width="386" alt="library catalog db first" src="https://github.com/michaelaann-907/Library/assets/114198365/bc8c4465-d86d-4203-b1ed-18e5f2f0dfd0">


<br/> 
<br/>


# Author Table 
This table stores information about authors. There is also a unique constraint on 'firstName' and 'lastName' to ensure there are no duplicate entries.


* `authorId` (PK)

* `firstName`

* `lastName`


<img width="400" alt="author table" src="https://github.com/michaelaann-907/Library/assets/114198365/f3b96289-76a6-44a5-b8cb-1af78d5302cf">

<br/>  
<br/>


# Book Table 
This table stores information about books in the library catalog. 


* `bookId` (PK)

* `title`

* `price`


<img width="399" alt="book table" src="https://github.com/michaelaann-907/Library/assets/114198365/cfbea2e0-d78d-40b3-9113-dd3e8bdbe66f">

<br/>  
<br/>


# BookAuthor Table 
This table establishes a many-to-many relationship between the authors and the books by connecting the 'Author' and 'Book' tables. The primary key is a composite key consisting of both 'bookId' and 'authorId' and the foreign keys reference the 'bookId' from the 'Book' table and 'authorId' from the 'Author' table. 

* `bookId`

* `authorId`


<img width="416" alt="Bookauthor table" src="https://github.com/michaelaann-907/Library/assets/114198365/9d128d1d-8380-429f-8c2d-978826866dc9">


<br/>  
