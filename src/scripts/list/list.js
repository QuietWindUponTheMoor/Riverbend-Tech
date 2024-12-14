

class List {
    $list = $("#list");

    constructor() {

    }

    async display(recordUnique="", firstIsEditable=false, cols=[
        {identifier: "", placeholder: "", value: ""}
    ]) {
        let firstCol = "";
        if (firstIsEditable) {
            firstCol = `<input class="record-input ${cols[0].identifier}" value="${cols[0].value}" placeholder="${cols[0].placeholder}"/>`;
        } else {
            firstCol = `<p class="record-label item-number ${cols[0].identifier}">${cols[0].value}</p>`;
        }

        let inputs = cols.slice(1).map(col => {
            return `<input class="record-input ${col.identifier}" value="${col.value}" placeholder="${col.placeholder}"/>`;
        }).join("");

        let template = 
        `
        <span class="record-section" id="record-${recordUnique}">
            ${firstCol}
            ${inputs}
        </span>
        `;

        this.$list.append(template);
    }
}

let cols = [
    {identifier: "sid", placeholder: "2030***", value: "2030018"},
    {identifier: "last", placeholder: "Doe", value: "Jane"},
    {identifier: "first", placeholder: "John", value: "Mary"},
    {identifier: "grade", placeholder: "5", value: "12"},
    {identifier: "homeroom", placeholder: "5C", value: "12B"},
    {identifier: "email", placeholder: "johnd@riverbendschools.net", value: "maryj@riverbendschools.net"},
    {identifier: "asset", placeholder: "23HPTG9-0000000001-23", value: "23HPTG9-0000000002-23"},
    {identifier: "", placeholder: "20HP21", value: "126HP21"},
];

const L = new List();
L.display("123", firstIsEditable=false, cols=cols);