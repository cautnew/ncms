const tableFields = document.getElementById("table-fields");
const tbodyTableFields = tableFields.querySelector("tbody");
const btnAddField = document.getElementById("btn-add-field");
const spanNumFields = document.querySelector("span#num-fields");

let countFields = 0;
let lastCountFields = 0;

btnAddField.addEventListener("click", () => {
  addRowTableFieldsList({});
});

const increaseCountFields = () => {
  countFields++;
  lastCountFields++;
  spanNumFields.textContent = `Number of fields: ${countFields}.`;
};

const decreaseCountFields = () => {
  countFields--;
  spanNumFields.textContent = `Number of fields: ${countFields}.`;
};

const createRowTableFieldsList = () => {
  const row = $(`<tr data-field-num="${lastCountFields}"></tr>`);
  const tdFieldName = $('<td class="text-center align-middle"></td>');
  const tdType = $('<td class="text-center align-middle"></td>');
  const tdReferences = $('<td class="text-center align-middle"></td>').text(
    "..."
  );
  const tdDescription = $('<td class="align-middle"></td>');
  const tdActions = $('<td class="text-center align-middle"></td>');

  const inputFieldName = $(
    '<input type="text" class="form-control" placeholder="Field name" />'
  );
  inputFieldName.attr("name", `fields[var_name_${lastCountFields}]`);
  inputFieldName.attr("id", `var_name_${lastCountFields}`);
  inputFieldName.on("keyup", function () {
    const value = $(this).val();
    console.log(value);
  });
  tdFieldName.append(inputFieldName);

  const btnRemove = createButton(
    '<i class="fa-solid fa-trash" title="Remove field"></i>',
    "danger"
  );
  btnRemove.addClass("btn-sm");
  btnRemove.click(function () {
    removeRowTableFieldsList(row);
  });

  tdActions.append(btnRemove);

  row.append(tdFieldName, tdType, tdReferences, tdDescription, tdActions);

  return row;
};

const addRowTableFieldsList = (fieldoptions) => {
  const row = createRowTableFieldsList(fieldoptions);
  $(tbodyTableFields).append(row);
  increaseCountFields();
};

const removeRowTableFieldsList = (row) => {
  row.remove();
  decreaseCountFields();
};
