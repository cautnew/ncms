const tableDatasetsList = document.getElementById("table-dataset-list");
const theadTableDatasetsList = tableDatasetsList.querySelector("thead");
const tbodyTableDatasetsList = tableDatasetsList.querySelector("tbody");
const startRoute = "/ncms/datasets";

const numColumnsTableDatasetsList = () => {
  return theadTableDatasetsList.querySelectorAll('th').length;
};

const createDropdownActions = (dataset) => {
  const dropdown = createDropdownSplit("primary", [
    {
      text: "Edit",
      default: true,
      action: `${startRoute}/${dataset.var_cid}/edit`,
    },
    {
      text: "View",
      default: false,
      action: `${startRoute}/${dataset.var_cid}/view`,
    },
    {
      text: "Delete",
      default: false,
      action: `${startRoute}/${dataset.var_cid}/delete`,
    },
  ]);
  return dropdown;
};

const createRowTableDatasetsList = (dataset) => {
  const row = $("<tr></tr>");
  const tdId = $('<td class="text-center align-middle"></td>').text(
    dataset.var_cid
  );
  const tdDatasetName = $('<td class="text-center align-middle"></td>').text(
    dataset.var_name
  );
  const tdEnable = $('<td class="text-center align-middle"></td>');
  const tdAdmin = $('<td class="text-center align-middle"></td>');
  const tdSystem = $('<td class="text-center align-middle"></td>');
  const tdDescription = $('<td class="align-middle"></td>').text(
    dataset.txt_description
  );
  const tdActions = $('<td class="text-center align-middle"></td>');
  tdActions.append(createDropdownActions(dataset));

  if (dataset.bol_enabled) {
    tdEnable.addClass("text-success fw-bold");
    tdEnable.append('<i class="fa-solid fa-circle-check me-2"></i>Yes');
  } else {
    tdEnable.addClass("text-danger fw-bold");
    tdEnable.append('<i class="fa-solid fa-circle-xmark me-2"></i>No');
  }

  if (dataset.bol_admin) {
    tdAdmin.addClass("text-success fw-bold");
    tdAdmin.append('<i class="fa-solid fa-circle-check me-2"></i>Yes');
  } else {
    tdAdmin.addClass("text-danger fw-bold");
    tdAdmin.append('<i class="fa-solid fa-circle-xmark me-2"></i>No');
  }

  if (dataset.bol_system) {
    tdSystem.addClass("text-success fw-bold");
    tdSystem.append('<i class="fa-solid fa-circle-check me-2"></i>Yes');
  } else {
    tdSystem.addClass("text-danger fw-bold");
    tdSystem.append('<i class="fa-solid fa-circle-xmark me-2"></i>No');
  }

  row.append(
    tdId,
    tdDatasetName,
    tdEnable,
    tdAdmin,
    tdSystem,
    tdDescription,
    tdActions
  );

  return row;
};

const addRowTableDatasetsList = (dataset) => {
  const row = createRowTableDatasetsList(dataset);
  $(tbodyTableDatasetsList).append(row);
};

const rowWaitingTableDatasetsList = () => {
  const row = $("<tr></tr>");
  const td = $(`<td class="text-center" colspan="${numColumnsTableDatasetsList()}"></td>`).text("Loading...");
  row.append(td);

  return row;
};

const updateDatasetList = () => {
  $(tbodyTableDatasetsList).empty();
  $(tbodyTableDatasetsList).append(rowWaitingTableDatasetsList());

  $.ajax({
    url: `${startRoute}/list/10/1`,
    method: "GET",
    success: (response) => {
      $(tbodyTableDatasetsList).empty();
      response.datasets.forEach((dataset) => {
        addRowTableDatasetsList(dataset);
      });
    },
    error: (error) => {
      $(tbodyTableDatasetsList).empty();
      const row = $("<tr></tr>");
      const td = $('<td colspan="6"></td>');
      td.addClass("bg-danger text-warning text-center");
      td.text("Datasets list could not be loaded.");
      row.append(td);
      $(tbodyTableDatasetsList).append(row);

      createToastShortMessage(
        "It was not possible to load this list. Check the console for more details.",
        "danger"
      );

      console.error(error);
    },
  });
};

updateDatasetList();
