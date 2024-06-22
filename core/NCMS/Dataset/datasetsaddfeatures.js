const tableFields = document.getElementById("table-fields");
const tbodyTableFields = tableFields.querySelector("tbody");
const btnAddField = document.getElementById("btn-add-field");
const startRoute = "";
const spanNumFields = document.querySelector("span#num-fields");
const inputController = document.querySelector("input#var_controller");

let countFields = 0;
let lastCountFields = 0;
let searchedInputController = "";

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

const updateDatasetList = () => {
  tbodyTableFields.innerHTML = "";
};

const checkInputController = function () {
  const value = $(this).val();
  if (value.length < 3 || searchedInputController === value) {
    return;
  }

  searchedInputController = value;

  console.log(value);
};

inputController.addEventListener("keyup", checkInputController);
inputController.addEventListener("blur", checkInputController);

updateDatasetList();
