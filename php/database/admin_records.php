<?php

// Import required modules
require("../connector.php");
require("../database/queries.php");

// Filter POST data
$data = array_filter($_POST);

try {
    $Query = new Query(
        $conn,
        "sa",
        "SELECT * FROM checkouts;",
        null,
        null,
    );

    $result = $Query->result;
    if ($result->num_rows > 0) {

        // Fetch the data
        while ($r = mysqli_fetch_assoc($result)) {
            $rid = $r["recordID"];
            $sid = $r["studentID"];
            $assignedCB = $r["assignedCB"];
            $loanerCB = $r["loanerCB"];
            $school = $r["school"];
            $grade = $r["grade"];
            $issue = $r["issue"];
        }
    } else {
        // If no records were found
        echo 0;
    }
} catch (mysqli_exception $error) {
    echo "Something went wrong fetching admin data. Error: " + $error;
}

// Close the connection
$conn->close();