if (!user || user.perm_level === 0) {
    window.location.assign("/");
}
else {
    console.log("User has proper permissions to visit this page")
}

let numRecords = 1;

const $submissions = $(".submissions");
const $newBtn = $("#new-record");

$newBtn.on("click", function () {
    // Increment number of records and display new one
    numRecords++;
    $submissions.append(`
        <span class="record-section" id="record-${numRecords}">
            <p class="record-label item-number">${numRecords}</p>
            <input class="record-input asset" placeholder="01HP21"/>
            <input class="record-input serial" placeholder="5CD113****"/>
            <input class="record-input PO" placeholder="2304000***"/>
            <select class="record-input model">
                <option value="HPG320">HPG320</option>
                <option value="HPG720">HPG720</option>
                <option value="HP19">HP19</option>
                <option value="HPT19">HPT19</option>
                <option value="G8" selected="selected">G8/HP21/HPT21</option>
                <option value="HPTG9">HPTG9</option>
                <option value="23HPTG9">23HPTG9</option>
                <option value="G9">G9</option>
            </select>
            <select class="record-input building">
                <option value="FES">FES</option>
                <option value="RBMS" selected="selected">RBMS</option>
                <option value="FHS">FHS</option>
            </select>
            <select class="record-input assignment">
                <option value="STUDENT" selected="selected">Student Assigned</option>
                <option value="LOANER">Loaner Device</option>
                <option value="STAFF">Staff Device</option>
                <option value="DEPROVISIONED">Deprovisioned/Donor/Dead</option> <!-- Results of deprovisioned is the same as donor -->
            </select>
            <input class="record-input person" placeholder="2030***"/>
        </span>
    `);
});

$("#submit-records").on("click", async function () {
    let records = [];

    // Collect all records
    for (let i = 1; i <= numRecords; i++) {
        let $record = $(`#record-${i}`);

        // Get values
        let asset = $record.find(".asset").val();
        let serial = $record.find(".serial").val();
        let PO = $record.find(".PO").val();
        let model = $record.find(".model").val();
        let building = $record.find(".building").val();
        let assignment = $record.find(".assignment").val();
        let person = $record.find(".person").val();
        let data = {
            asset: asset,
            serial: serial,
            PurchaseOrder: PO,
            model: model,
            building: building,
            assignment: assignment,
            person: person
        };

        records.push(data);
    }

    // Submit devices
    let wasErrorWhenInserting = await createDevice(records);
    if (!wasErrorWhenInserting) {
        modal("success", `Successfully submitted new devices! Please wait...`);
    } else {
        modal("error", `Unable to submit one or more devices. Please check the console for errors.`);
    }
});

async function createDevice(records) {
    let wasError = false;

    await records.forEach(async data => {
        $.ajax({  
            type: "POST",  
            url: "/php/forms/new_device.php",
            data: data,
            success: function (response) {
                if (parseInt(response) === 1) {
                    // Reload page
                    setTimeout(() => {window.location.reload();}, 10000);
                } else {
                    wasError = true;
                    console.error(response);
                }
            }
        });
    });
}