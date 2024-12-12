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
            <input class="record-input email" placeholder="johndoe@riverbendschools.net"/>
        </span>
    `);
});

$("#submit-records").on("click", async function () {
    let records = [];

    // Collect all records
    for (let i = 1; i <= numRecords; i++) {
        let $record = $(`#record-${i}`);

        // Get values
        let email = $record.find(".email").val();
        let data = {
            email: email
        };

        records.push(data);
    }

    // Submit devices
    let wasErrorWhenInserting = await createDevice(records);
    if (!wasErrorWhenInserting) {
        modal("success", `Successfully submitted new allowlist users! Please wait...`);
    } else {
        modal("error", `Unable to submit one or more allowlist users. Please check the console for errors.`);
    }
});

async function createDevice(records) {
    let wasError = false;

    await records.forEach(async data => {
        $.ajax({  
            type: "POST",  
            url: "/php/forms/new_allowlist_user.php",
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