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
            <input class="record-input sid" placeholder="2030***"/>
            <input class="record-input first" placeholder="John"/>
            <input class="record-input last" placeholder="Doe"/>
            <select class="record-input grade">
                <option value="K" selected="selected">Kindergarten</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="7">7</option>
                <option value="8">8</option>
                <option value="9">9</option>
                <option value="10">10</option>
                <option value="11">11</option>
                <option value="12">12</option>
            </select>
            <input class="record-input homeroom" placeholder="KA, 7B, etc"/>
            <input class="record-input email" placeholder="johnd@riverbendschools.net"/>
            <input class="record-input asset" placeholder="01HP21"/>
        </span>
    `);
});

$("#submit-records").on("click", async function () {
    let records = [];

    // Collect all records
    for (let i = 1; i <= numRecords; i++) {
        let $record = $(`#record-${i}`);

        // Get values
        let sid = $record.find(".sid").val();
        let first = $record.find(".first").val();
        let last = $record.find(".last").val();
        let grade = $record.find(".grade").val();
        let homeroom = $record.find(".homeroom").val();
        let email = $record.find(".email").val();
        let asset  = $record.find(".asset").val();
        let data = {
            sid: sid,
            first: first,
            last: last,
            grade: grade,
            homeroom: homeroom,
            email: email,
            asset: asset
        };

        records.push(data);
    }

    // Submit devices
    let wasErrorWhenInserting = await createDevice(records);
    if (!wasErrorWhenInserting) {
        modal("success", `Successfully submitted new students! Please wait...`);
    } else {
        modal("error", `Unable to submit one or more students. Please check the console for errors.`);
    }
});

async function createDevice(records) {
    let wasError = false;

    await records.forEach(async data => {
        $.ajax({  
            type: "POST",  
            url: "/php/forms/new_student.php",
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