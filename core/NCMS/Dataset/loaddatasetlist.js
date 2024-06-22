const tableDatasetsList = document.getElementById("table-dataset-list");
const tbodyTableDatasetsList = tableDatasetsList.querySelector("tbody");
const startRoute = "/ncms/dataset";

const createDropdownActions = (dataset) => {
  const dropdown = createDropdownSplit("primary", [
    {
      text: "Edit",
      default: true,
      action: `${startRoute}/edit/${dataset.var_cid}`,
    },
    {
      text: "View",
      default: false,
      action: `${startRoute}/view/${dataset.var_cid}`,
    },
    {
      text: "Delete",
      default: false,
      action: `${startRoute}/delete/${dataset.var_cid}`,
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
  const tdFields = $('<td class="text-center align-middle"></td>').text("...");
  const tdActive = $('<td class="text-center align-middle"></td>');
  const tdDescription = $('<td class="align-middle"></td>').text(
    dataset.txt_description
  );
  const tdActions = $('<td class="text-center align-middle"></td>');
  tdActions.append(createDropdownActions(dataset));

  if (dataset.bol_active) {
    tdActive.addClass("text-success fw-bold");
    tdActive.append('<i class="fa-solid fa-circle-check me-2"></i>Yes');
  } else {
    tdActive.addClass("text-danger fw-bold");
    tdActive.append('<i class="fa-solid fa-circle-xmark me-2"></i>No');
  }

  row.append(tdId, tdDatasetName, tdFields, tdActive, tdDescription, tdActions);

  return row;
};

const addRowTableDatasetsList = (dataset) => {
  const row = createRowTableDatasetsList(dataset);
  $(tbodyTableDatasetsList).append(row);
};

const rowWaitingTableDatasetsList = () => {
  const row = $("<tr></tr>");
  const td = $('<td class="text-center" colspan="6"></td>').text("Loading...");
  row.append(td);

  return row;
};

const updateDatasetList = () => {
  $(tbodyTableDatasetsList).empty();
  $(tbodyTableDatasetsList).append(rowWaitingTableDatasetsList());

  $.ajax({
    url: "/ncms/dataset/list",
    method: "GET",
    success: (response) => {
      $(tbodyTableDatasetsList).empty();
      response.datasets.forEach((dataset) => {
        addRowTableDatasetsList(dataset);
      });
    },
    error: (error) => {
      console.error(error);
    },
  });
};

updateDatasetList();
