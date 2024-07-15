const inputAlias = document.querySelector("input#var_alias");

let searchedInputAlias = "";
let timeoutInputAlias = null;

const checkInputAlias = function () {
  const value = $(inputAlias).val();
  if (value.length < 3 || searchedInputAlias === value) {
    return;
  }

  searchedInputAlias = value;

  $.ajax({
    url: `/ncms/datasets/alias/${value}/check`,
    method: "GET",
    success: (response) => {
      console.log(response);
    },
    error: (error) => {
      console.error(error);
    },
  });
};

inputAlias.addEventListener("keyup", function () {
  if (timeoutInputAlias) {
    clearTimeout(timeoutInputAlias);
  }

  timeoutInputAlias = setTimeout(checkInputAlias, timeoutInputAfterKeyUp);
});

inputAlias.addEventListener("blur", function () {
  if (timeoutInputAlias) {
    clearTimeout(timeoutInputAlias);
  }

  checkInputAlias();
});
