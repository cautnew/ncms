const inputVarName = document.querySelector("input#var_name");
const textareaTxtDescription = document.querySelector(
  "textarea#txt_description"
);
const inputBolEnabled = document.querySelector("input#bol_enabled");
const inputBolAdmin = document.querySelector("input#bol_admin");
const inputBolSystem = document.querySelector("input#bol_system");
const btnsSubmit = document.querySelectorAll("button.btn-submit");
const form = document.querySelector("#form-edit-dataset");

const updateDatasetList = () => {
  increaseProcessLoading();
  $.ajax({
    url: `/ncms/datasets/${datasetId}/info`,
    method: "GET",
    success: (response) => {
      inputVarName.value = response.dataset.var_name;
      inputController.value = response.dataset.var_controller || "";
      textareaTxtDescription.value = response.dataset.txt_description || "";
      $(inputBolEnabled).prop("checked", response.dataset.bol_enabled);
      $(inputBolAdmin).prop("checked", response.dataset.bol_admin);
      $(inputBolSystem).prop("checked", response.dataset.bol_system);
      decreaseProcessLoading();
    },
    error: (error) => {
      createToastShortMessage(
        "It was not possible to load this data. Check the console for more details.",
        "danger"
      );
      console.error(error);
      decreaseProcessLoading();
    },
  });
};

const submitEvent = () => {
  increaseProcessLoading();
  const data = $(form).serialize();
  $.ajax({
    method: "POST",
    data: data,
    success: onSuccessFormSubmit,
    error: onErrorFormSubmit,
  });
};

form.addEventListener("submit", function (evt) {
  evt.preventDefault();
  submitEvent();
});

const onSuccessFormSubmit = (response) => {
  decreaseProcessLoading();
  createToastShortMessage("Data saved successfully.", "success");
};

const onErrorFormSubmit = (error) => {
  decreaseProcessLoading();
  createToastShortMessage(
    "It was not possible to save this data. Check the console for more details.",
    "danger"
  );
  console.error(error);
};

btnsSubmit.forEach((btn) => {
  btn.addEventListener("click", submitEvent);
});

updateDatasetList();
