let recordConfig = {
    students: record => [
        record.sid,
        record.last,
        record.first,
        record.grade,
        record.homeroom,
        record.email,
        record.device,
        record.loaner
    ],
    devices: record => [
        record.serial,
        record.asset,
        record.model,
        record.PO,
        record.assignment,
        record.isLoanedOut
    ],
    checkouts: record => [
        record.studentID,
        record.last,
        record.first,
        record.loanerCB,
        record.grade,
        record.issue,
    ],
};