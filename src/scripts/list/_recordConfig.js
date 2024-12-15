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
};