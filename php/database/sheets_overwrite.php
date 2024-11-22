<?php

// Variables and paths
$autoloadPath = "../Google-Cloud/PHP8.0/vendor/autoload.php";
$spreadsheetId = "16gvMj9wRBS_fTeYz26Un48UiO_WdnH_0nuoSllPtg8w"; // ID of the Google Sheet you want to update (found in the sheet's URL)
$range = "Sheet1!A1"; // Define the sheet and range (e.g., "Sheet1!A1")
$csvFile = "chromebook_checkouts.csv";


// Require autoload
require_once($autoloadPath);


// Function to authenticate and get a Google Sheets API service instance
function getGoogleSheetsService() {
    $ApplicationName = "Chromebook Checkout API";
    $credentialsJSONPath = "../Google-Cloud/riverbendtechteam-d2104cd677eb.json";
    $accessType = "offline";
    
    $client = new Google_Client();
    $client->setApplicationName($ApplicationName);
    $client->setScopes([Google_Service_Sheets::SPREADSHEETS]);
    $client->setAuthConfig($credentialsJSONPath);
    $client->setAccessType($accessType);
    
    $service = new Google_Service_Sheets($client);
    return $service;
}

// Function to overwrite Google Sheet data with CSV content
function updateGoogleSheet($spreadsheetId, $range, $values) {
    $service = getGoogleSheetsService();

    $body = new Google_Service_Sheets_ValueRange([
        "values" => $values
    ]);

    $params = [
        "valueInputOption" => "RAW"
    ];

    $result = $service->spreadsheets_values->update(
        $spreadsheetId,
        $range,
        $body,
        $params
    );

    echo "{$result->getUpdatedCells()} cells updated.";
}

// CSV to array conversion
function csvToArray($filename) {
    $data = [];
    if (($handle = fopen($filename, "r")) !== false) {
        while (($row = fgetcsv($handle, 1000, ",")) !== false) {
            $data[] = $row;
        }
        fclose($handle);
    }
    return $data;
}

// Convert CSV to array
$csvData = csvToArray($csvFile);

// Update Google Sheet with CSV data
updateGoogleSheet($spreadsheetId, $range, $csvData);
