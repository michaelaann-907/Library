$(document).ready(function() {
    // Function to fetch and display table data
    function fetchTableData() {
        $.ajax({
            url: 'process.php?action=fetchData',
            type: 'GET',
            dataType: 'html',
            success: function(data) {
                $('#tableData').html(data); // Display combined data in the 'tableData' div
            },
            error: function() {
                console.error('Failed to fetch data from the API.');
            }
        });
    }

    // Function to fetch and display individual tables
    function fetchIndividualTables() {
        $.ajax({
            url: 'process.php?action=fetchIndividualTables',
            type: 'GET',
            dataType: 'html',
            success: function(data) {
                $('#individualTables').html(data); // Display individual tables in the 'individualTables' div
            },
            error: function() {
                console.error('Failed to fetch individual table data.');
            }
        });
    }

    // Handle Clear All button click
    $('#clearAll').click(function() {
        if (confirm("Are you sure you want to clear all data? This action cannot be undone.")) {
            $.ajax({
                url: 'process.php?action=clearAllData',
                type: 'GET',
                success: function() {
                    fetchTableData(); // Clear the table data
                    fetchIndividualTables(); // Clear the individual tables
                },
                error: function() {
                    console.error('Failed to clear all data.');
                }
            });
        }
    });

    // Fetch and display combined table data when the page loads
    fetchTableData();

    // Fetch and display individual tables when the page loads
    fetchIndividualTables();

    // Handle form submission using AJAX
    $('#bookForm').submit(function(event) {
        event.preventDefault(); // Prevent the default form submission

        $.ajax({
            url: 'process.php?action=insertData',
            type: 'POST',
            data: $('#bookForm').serialize(), // Serialize the form data
            success: function() {
                fetchTableData(); // Update the combined table data
                fetchIndividualTables(); // Update the individual tables
                $('#bookForm')[0].reset(); // Clear the form fields
            },
            error: function() {
                console.error('Failed to add a book.');
            }
        });
    });
});
