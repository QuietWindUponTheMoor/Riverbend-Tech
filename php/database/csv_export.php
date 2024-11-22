<?php

// Import required modules
require("../connector.php");
require("../database/queries.php");

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

        // Open the CSV file
        $file = fopen("chromebook_checkouts.csv", "w");
        
        // Fetch and write column headers
        $headers = array();
        while ($fieldinfo = $result->fetch_field()) {
            $headers[] = $fieldinfo->name;
        }
        fputcsv($file, $headers);

        // Fetch the data
        while ($r = mysqli_fetch_assoc($result)) {
            $rid = $r["recordID"];
            $sid = $r["studentID"];
            $assignedCB = $r["assignedCB"];
            $loanerCB = $r["loanerCB"];
            $school = $r["school"];
            $grade = $r["grade"];
            $issue = $r["issue"];
    
            // Create the object
            $obj = json_encode([
                "rid" => $rid,
                "sid" => $sid,
                "assignedCB" => $assignedCB,
                "loanerCB" => $loanerCB,
                "school" => $school,
                "grade" => $grade,
                "issue" => $issue,
            ]);

            // Put row in CSV
            fputcsv($file, $r);
        }
        // Close CSV file
        fclose($file);

        // Log success
        echo "Export to file success! Proceeding...";
    } else {
        echo "There are no records, export will try again in an hour.";
    }
} catch (mysqli_exception $error) {
    echo "Something went wrong exporting, please try again or contact an administrator. Export will be tried again in an hour. Error: " + $error;
}

// Close the connection
$conn->close();

// Finally, export the data to Google Sheets
require_once("sheets_overwrite.php");