let recordConfig = {
    students: record => [
        record.sid,
        record.last,
        record.first,
        record.grade,
        record.homeroom,
        record.email,
        record.device_asset,
        record.loaner_asset
    ],
    devices: record => [
        record.serial,
        record.asset,
        record.model,
        record.PO,
        record.building,
        record.assignment,
        record.person
    ],
    checkouts: record => [
        record.studentID,
        record.last,
        record.first,
        record.assignedCB,
        record.loanerCB,
        record.school,
        record.grade,
        record.issue
    ],
};