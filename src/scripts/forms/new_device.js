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
                <option value="RBMS">FES</option>
                <option value="RBMS" selected="selected">RBMS</option>
                <option value="RBMS">FHS</option>
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