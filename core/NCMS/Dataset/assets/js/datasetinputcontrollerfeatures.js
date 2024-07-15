const inputController = document.querySelector("input#var_controller");

let searchedInputController = "";
let timeoutInputController = null;

const checkInputController = function () {
  const value = $(inputController).val();
  if (value.length < 3 || searchedInputController === value) {
    return;
  }

  searchedInputController = value;

  $.ajax({
    url: `/ncms/datasets/controller/${window
      .btoa(value)
      .replace("=", "--")
      .replace("=", "--")}/check`,
    method: "GET",
    success: (response) => {
      console.log(response);
    },
    error: (error) => {
      console.error(error);
    },
  });
};

inputController.addEventListener("keyup", function () {
  if (timeoutInputController) {
    clearTimeout(timeoutInputController);
  }

  timeoutInputController = setTimeout(checkInputController, timeoutInputAfterKeyUp);
});

inputController.addEventListener("blur", function () {
  if (timeoutInputController) {
    clearTimeout(timeoutInputController);
  }

  checkInputController();
});
