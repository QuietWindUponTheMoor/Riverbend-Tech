<?php

// Import required modules
require($_SERVER["DOCUMENT_ROOT"]."/php/connector.php");
require($_SERVER["DOCUMENT_ROOT"]."/php/database/queries.php");

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

            // Object to push
            $record = [
                "rid" => $rid,
                "sid" => $sid,
                "assignedCB" => $assignedCB,
                "loanerCB" => $loanerCB,
                "school" => $school,
                "grade" => $grade,
                "issue" => $issue,
            ];

            // Grade level
            switch ($grade) {
                case 8:
                    array_push($records["8g"], $record);
                    break;
                case 7:
                    array_push($records["7g"], $record);
                    break;
                case 6:
                    array_push($records["6g"], $record);
                    break;
                default:
                    break;
            }
            
            // School/building
            switch ($school) {
                case "FES":
                    array_push($records["FES"], $record);
                    break;
                case "RBMS":
                    array_push($records["RBMS"], $record);
                    break;
                case "FHS":
                    array_push($records["FHS"], $record);
                    break;
                default:
                    break;
            }
        }
    } else {
        // If no records were found
        echo "No records were found.";
    }
} catch (mysqli_exception $error) {
    echo "Something went wrong fetching data. Error: " + $error;
}

// Close the connection
$conn->close();