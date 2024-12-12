// Globals
let currentFile = "";
let currentFileNum = 1;
let currentRecordNum = 1;
let totalRecords = 0;
let totalFiles = 0;

$result = $("#upload-result");

$("#import-csv").on("change", async function () {
    // Get files
    let files = $(this)[0].files;

    totalFiles = files.length;
    $(".progress-container").css("display", "flex");
    
    // Iterate over
    for (let file of files) {
        try {
            await processPapaParse(file);
            currentFileNum++;
            currentRecordNum = 1;
        } catch (error) {
            console.error("Error processing file:", error);
            $result.addClass("upload-error").text("Error processing file");
            return;
        }
    };

    $result.addClass("upload-success").removeClass("upload-error").text("Success!");
});

// Helpers
async function processRecord(record) {
    return new Promise((resolveRecord, rejectRecord) => {
        try {
            let sid = record.sid;
            let first = record.first;
            let last = record.last;
            let grade = record.grade;
            let homeroom = record.homeroom?.trim().length ? record.homeroom : null;
            let email = record.email?.trim().length ? record.email : null;
            let asset = record.device_asset?.trim().length ? record.device_asset : null;
            let loaner = record.loaner_asset?.trim().length ? record.loaner_asset : null;

            // Insert data
            let data = {
                sid: sid,
                first: first,
                last: last,
                grade: grade,
                homeroom: homeroom,
                email: email,
                asset: asset,
                loaner: loaner
            };
            $.ajax({
                type: "POST",  
                url: "/php/forms/new_student.php",
                data: data,
                success: function (response) {
                    if (parseInt(response) === 1) {
                        currentRecordNum++;
                        $("#upload-progress").val(currentRecordNum);
                        $("#progress-percent").text(`${Math.round((currentRecordNum / totalRecords) * 100)}% of file ${currentFileNum} / ${totalFiles}`);
                        resolveRecord();
                    } else {
                        console.error(response);
                        currentRecordNum++;
                        $("#upload-progress").val(currentRecordNum);
                        $("#progress-percent").text(`${Math.round((currentRecordNum / totalRecords) * 100)}% of file ${currentFileNum} / ${totalFiles}`);
                        rejectRecord(response);
                    }
                }
            });
        } catch (error) {
            console.error("Error processing record:", error);
            currentRecordNum++;
            $result.addClass("upload-error").text("Error processing record");
            $("#upload-progress").val(currentRecordNum);
            $("#progress-percent").text(`${Math.round((currentRecordNum / totalRecords) * 100)}% of file ${currentFileNum} / ${totalFiles}`);
            rejectRecord(error);
        }
    });
}

async function processPapaParse(file) {
    return new Promise((resolve, reject) => {
        // File contents
        let name = file.name;
        let lastModified = file.lastModified;
        let webkitRelativePath = file.webkitRelativePath;
        let size = file.size;
        let type = file.type;

        // Check if file is valid
        if (!name.endsWith('.csv') || type !== "text/csv") {
            $result.addClass("upload-error").text("Please upload a valid CSV file.");
            return;
        }

        $("#progress-filename").text(`Processing ${name}`);

        // Use PapaParse
        Papa.parse(file, {
            header: true,
            skipEmptyLines: true,
            complete: async (results) => {
                try {
                    // Configure
                    const requiredHeaders = [
                        "sid",
                        "first",
                        "last",
                        "grade",
                        "homeroom",
                        "email",
                        "device_asset"
                    ];
                    const optionalHeaders = [
                        "loaner_asset",
                    ];
                    let data = results.data;
                    let headers = results.meta.fields;

                    // Check headers are present
                    let missingHeaders = requiredHeaders.filter(header => !headers.includes(header));
                    if (missingHeaders.length > 0) {
                        alert(`Missing required headers, continuing to next file: ${missingHeaders.join(', ')}`);
                        $result.addClass("upload-error").text(`Missing required headers, continuing to next file: ${missingHeaders.join(', ')}`);
                        resolve();
                        return;
                    }

                    // Filter the data to include only required columns & optional columns
                    let filteredData = results.data.map(row => {
                        const filteredRow = {};
                
                        // Add required headers
                        requiredHeaders.forEach(header => {
                            filteredRow[header] = row[header];
                        });
                
                        // Add optional headers if they exist
                        optionalHeaders.forEach(header => {
                            if (headers.includes(header)) {
                                filteredRow[header] = row[header];
                            }
                        });
                
                        return filteredRow;
                    });

                    totalRecords = filteredData.length;
                    $("#upload-progress").attr("max", totalRecords);
                    $("#upload-progress").attr("max", filteredData.length);

                    // Iterate
                    for (let record of filteredData) {
                        await processRecord(record);
                    }

                    // Finished uploading all records for this file
                    resolve();
                } catch (error) {
                    console.error("Error during file processing:", error);
                    $result.addClass("upload-error").text("Error during file processing:");
                    reject(error);
                }
                
            },
            error: async (error) => {
                console.error("Error parsing CSV:", error);
                $result.addClass("upload-error").text("Error parsing CSV");
                reject(error);
            }
        });
    });
}