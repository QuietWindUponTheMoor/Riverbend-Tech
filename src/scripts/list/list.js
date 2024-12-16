class List {
    $list = $("#list");
    firstColIsEditable=false;
    headers = [
        {identifier: "", value: "", placeholder: ""}
    ];
    records = [
        [
            // List of values
            "value1",
            "value2", // Etc
        ]
    ]

    constructor(firstColIsEditable=false, headers=[]) {
        this.firstColIsEditable = firstColIsEditable;
        this.headers = headers;
        this.records = [];
    }

    async addRecord(record=[]) {
        if (record.length !== this.headers.length) {
            console.error(`Mismatch: The number of record indices (${record.length}) does not match the number of header indices (${this.headers.length}).`);
        }

        this.records.push(record);
    }

    async resetList() {
        return new Promise((resolve, reject) => {
            this.$list.empty();
            resolve();
        });
    }

    async display() {
        let headerCols = this.headers.map(col => {
            return `<p class="record-label ${col.identifier}" onclick="groupBy = '${col.identifier}'; displayAllRecords(selectFileURL, headers, sortBy, groupBy, hardFilterBy, searchFilterBy, recordType, firstColIsEditable);">${col.value}</p>`;
        }).join("");

        let header =
        `
        <span class="record-section" id="label-section">
            ${headerCols}
        </span>
        `;

        this.$list.append(header);

        // Manage record rows
        for (let i = 0; i < this.records.length; i++) {
            let record = this.records[i]; // Array

            let firstCol = "";
            if (this.firstColIsEditable) {
                firstCol = `<input class="record-input ${this.headers[0].identifier}" value="${record[0]}" placeholder="${this.headers[0].placeholder}"/>`;
            } else {
                firstCol = `<p class="record-label ${this.headers[0].identifier}">${record[0]}</p>`;
            }

            // Iterate over the record values
            let inputs = "";
            for (let ci = 1; ci < record.length; ci++) { // ci = column index
                let value = "";
                if (record[ci] === undefined || record[ci] === null || record[ci].toString().trim().length === 0) {
                    value = "";
                } else {
                    value = record[ci];
                }
                inputs += `<input class="record-input ${this.headers[ci].identifier}" value="${value}" placeholder="${this.headers[ci].placeholder}"/>`;
            }

            let recordElement = 
            `
            <span class="record-section list-section" id="record-${record[0]}">
                ${firstCol}
                ${inputs}
            </span>
            `;

            this.$list.append(recordElement);
        }
    }
}

$(async () => {
    await displayAllRecords(selectFileURL, headers, sortBy, groupBy, hardFilterBy, searchFilterBy, recordType, firstColIsEditable);
});

$("#list-search").on("keyup", async function () {
    let value = $(this).val();
    if (value.trim().length === 0 || !value) {
        return;
    }
    
    let validColumnNames = [];
    headers.forEach(header => {
        validColumnNames.push(header.identifier);
    });

    // Match the pattern :<column>:<content>
    let match = value.match(/^:([^:]+):(.+)$/);
    
    if (match) {
        let column = match[1];
        let content = match[2];
        
        if (validColumnNames.includes(column)) {
            await displayAllRecords(selectFileURL, headers, sortBy, groupBy, hardSortBy={[column]: content}, searchFilterBy=null, recordType, firstColIsEditable);
        } else {
            console.error(`Error: "${column}" is not a valid column name.`);
        }
    } else {
        // Treat the entire input as content
        await displayAllRecords(selectFileURL, headers, sortBy, groupBy, hardFilterBy=null, searchFilterBy=value, recordType, firstColIsEditable);
    }
});

// Helpers
async function displayAllRecords(selectFileURL, headers, sortBy, groupBy, hardFilterBy, searchFilterBy, recordType, firstColIsEditable) {
    return new Promise(async (resolve, reject) => {
        // FirstSort
        if (firstSort === true) {
            sortBy = "ASC";
            firstSort = false;
        } else {
            firstSort = true;
            sortBy = "DESC";
        }

        // Configure List
        let _List = new List(firstIsEditable=firstColIsEditable, headers=headers);

        // Reset any previous
        await _List.resetList();

        // Process data
        let data = await fetchRecords(selectFileURL);

        // Sort data
        data = await sortAndFilterArray(data, sortBy, groupBy, hardFilterBy, searchFilterBy);

        // Add records & display
        await addRecords(_List, data, recordType);
        await _List.display();
        resolve();
    });
}

async function sortAndFilterArray(originalArray, sortBy, groupBy, hardFilterBy, searchFilterBy) {
    return new Promise((resolve, reject) => {
        try {
            let filteredArray = originalArray;

            // Apply hardFilterBy if provided
            if (hardFilterBy && Object.keys(hardFilterBy).length > 0) {
                filteredArray = filteredArray.filter(item =>
                    Object.entries(hardFilterBy).every(([key, value]) =>
                        item[key] !== undefined && item[key].toString().toLowerCase() === value.toString().toLowerCase()
                    )
                );
            }

            // Apply searchFilterBy if provided
            if (searchFilterBy) {
                filteredArray = filteredArray.filter(item =>
                    Object.values(item).some(value =>
                        value !== null && value !== undefined &&
                        value.toString().toLowerCase().includes(searchFilterBy.toLowerCase())
                    )
                );
            }

            // Sort the filtered array
            let sortedArray = filteredArray.sort((a, b) => {
                let valA = a[groupBy];
                let valB = b[groupBy];

                // Handle null or undefined values
                if (valA === null || valA === undefined) valA = "";
                if (valB === null || valB === undefined) valB = "";

                // Compare values (case insensitive for strings)
                if (typeof valA === "string") valA = valA.toLowerCase();
                if (typeof valB === "string") valB = valB.toLowerCase();

                if (valA < valB) return sortBy === "ASC" ? -1 : 1;
                if (valA > valB) return sortBy === "ASC" ? 1 : -1;
                return 0;
            });

            resolve(sortedArray);
        } catch (error) {
            console.error("Issue sorting and filtering array:", error);
            reject(error);
        }
    });
}

async function addRecords(_List, data, recordExtractor) {
    return new Promise((resolve, reject) => {
        data.forEach(async record => {
            await _List.addRecord(recordExtractor(record));
            resolve();
        });
    });
}

async function fetchRecords(selectFileURL) {
    return new Promise((resolve, reject) => {
        $.ajax({  
            type: "POST",  
            url: selectFileURL,
            data: null,
            success: function (response) {
                resolve(JSON.parse(response));
            }
        });
    });
}