/**
 * File: bs.helper.admin.js
 * Author: Felipe de Sousa Martins
 * Author E-mail: felipedesmartins@gmail.com
 *
 * Description: This file contains helper functions for Bootstrap components.
 * You can create elements easily using these functions. It's possible to mix
 * these functions with jQuery to create elements dynamically.
 */

/**
 * Creates a new bootstrap button element with the given text.
 * Class: btn
 * @param {string} text
 * @param {string} classColor primary, secondary, success, danger, warning, info, light, dark
 * @returns jQuery object
 */
const createButton = (text, classColor = "") => {
  const button = $(`<button type="button" class="btn">${text}</button>`);

  if (classColor != "" && classColor != undefined) {
    button.addClass(`btn-${classColor}`);
  }

  return button;
};

const createButtonPrimary = (text) => {
  return createButton(text, "primary");
};

const createButtonSecondary = (text) => {
  return createButton(text, "secondary");
};

const createButtonSuccess = (text) => {
  return createButton(text, "success");
};

const createButtonDanger = (text) => {
  return createButton(text, "danger");
};

const createButtonWarning = (text) => {
  return createButton(text, "warning");
};

/**
 * Creates a new bootstrap dropdown element with the given list of options.
 * @param {string} textButton
 * @param {string} classColor
 * primary, secondary, success, danger, warning, info, light, dark
 * @param {Array} listOptions
 * Array of objects with text and action
 * @returns jQuery object
 */
const createDropdown = (textButton, classColor, listOptions) => {
  const dropdown = $(`<div class="dropdown"></div>`);
  const btnDropdown = createButton(textButton, classColor);
  const dropdownList = $(`<ul class="dropdown-menu"></ul>`);

  btnDropdown.addClass("dropdown-toggle");
  btnDropdown.attr("data-bs-toggle", "dropdown");
  dropdown.listDropdownItems = [];

  listOptions.forEach((option) => {
    const dropDownItem = createDropdownItem(option.text, option.action);
    dropdownList.append(dropDownItem);
    dropdown.listDropdownItems.push(dropDownItem);
  });

  dropdown.list = dropdownList;
  dropdown.listOptions = listOptions;

  dropdown.append(btnDropdown, dropdownList);

  return dropdown;
};

/**
 * Creates a new bootstrap dropdown element with the given list of options.
 * @param {string} classColor
 * primary, secondary, success, danger, warning, info, light, dark
 * @param {Array} listOptions
 * Array of objects with text, bool for default option ('default' optional,
 * default false) and action
 * @returns jQuery object
 */
const createDropdownSplit = (classColor, listOptions) => {
  const dropdown = $(`<div class="btn-group"></div>`);
  const btnDropdown = createButton("", classColor);
  const dropdownList = $(`<ul class="dropdown-menu"></ul>`);

  btnDropdown.addClass("dropdown-toggle");
  btnDropdown.attr("data-bs-toggle", "dropdown");
  dropdown.listDropdownItems = [];

  listOptions.forEach((option) => {
    const dropdownItem = createDropdownItem(option.text, option.action);
    dropdownList.append(dropdownItem);
    dropdown.listDropdownItems.push(dropdownItem);
    if (option.default === true) {
      btnDropdown.addClass("dropdown-toggle-split");
      dropdown.defaultOption = option;
      dropdown.defaultItem = dropdownItem;
      if (option.action != undefined && typeof option.action === "function") {
        const optPrincBtn = createButton(option.text, classColor);
        optPrincBtn.click(option.action);
        dropdown.append(optPrincBtn);
      } else if (
        option.action != undefined &&
        typeof option.action === "string"
      ) {
        const optPrincItn = $(`<a class="btn"></a>`);
        optPrincItn.addClass(`btn-${classColor}`);
        optPrincItn.attr("href", option.action);
        optPrincItn.text(option.text);
        dropdown.append(optPrincItn);
      }
    }
  });

  dropdown.list = dropdownList;
  dropdown.listOptions = listOptions;

  dropdown.append(btnDropdown, dropdownList);

  return dropdown;
};

const createDropdownItem = (text, action) => {
  const li = $(`<li></li>`);
  const a = $(`<a class="dropdown-item" href="#">${text}</a>`);

  li.append(a);

  if (typeof action === "function") {
    a.click(action);
  } else if (typeof action === "string") {
    a.attr("href", action);
  }

  return li;
};

const createModalDialog = () => {
  const modalDialog = $(`<div class="modal-dialog"></div>`);
  return modalDialog;
};

const createModalContent = () => {
  const modalContent = $(`<div class="modal-content"></div>`);
  return modalContent;
};

const createModalHeader = (title) => {
  const modalHeader = $(`<div class="modal-header"></div>`);
  modalHeader.modalTitle = $(`<h5 class="modal-title">${title}</h5>`);
  modalHeader.append(modalHeader.modalTitle);
  return modalHeader;
};

const createModalBody = (content) => {
  const modalBody = $(`<div class="modal-body"></div>`);
  modalBody.append(content);
  return modalBody;
};

const createModalFooter = () => {
  const modalFooter = $(`<div class="modal-footer"></div>`);
  modalFooter.closeButton = createButtonSecondary();
  modalFooter.closeButton.text("Close");
  modalFooter.closeButton.attr("data-bd-dismiss", "modal");
  modalFooter.append(modalFooter.closeButton);
  return modalFooter;
};

const createModal = (id, title, bodyContent) => {
  if (id == null || id == undefined || id == "") {
    id = "modal" + Math.floor(Math.random() * 100);
  }

  const modal = $(`<div class="modal fade" id="${id}" tabindex="-1"></div>`);

  modal.modalDialog = createModalDialog();
  modal.modalContent = createModalContent();
  modal.modalHeader = createModalHeader(title);
  modal.modalBody = createModalBody(bodyContent);
  modal.modalFooter = createModalFooter();

  modal.modalContent.append(modal.modalHeader);
  modal.modalContent.append(modal.modalBody);
  modal.modalContent.append(modal.modalFooter);

  modal.modalFooter.append(modal.closeButton);

  modal.modalDialog.append(modal.modalContent);

  modal.append(modal.modalDialog);

  return modal;
};

const createToastHeader = (title) => {
  const toastHeader = $(`<div class="toast-header"></div>`);
  toastHeader.title = $(`<strong class="me-auto">${title}</strong>`);
  toastHeader.button = $(
    `<button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>`
  );
  toastHeader.append(toastHeader.title);
  toastHeader.append(toastHeader.button);
  return toastHeader;
};

const createToast = function () {
  const toast = $(
    `<div class="toast" role="alert" aria-live="assertive" aria-atomic="true"></div>`
  );
  return toast;
};

const createToastShortMessage = (message, color) => {
  const toast = createToast();
  toast.addClass(`align-items-center bg-${color} text-bg-${color} border-0`);
  const toastBodyArea = $(`<div class="d-flex"></div>`);
  toast.append(toastBodyArea);
  const toastBody = $(`<div class="toast-body">${message}</div>`);
  const toastCloseButton = $(
    `<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>`
  );

  toastBodyArea.append(toastBody);
  toastBodyArea.append(toastCloseButton);

  $(".toast-container").prepend(toast);
  const bsToast = new bootstrap.Toast(toast);
  bsToast.show();
};

window.addEventListener("hidden.bs.toast", function (evt) {
  $(evt.target).remove();
});

window.addEventListener("load", function () {
  // Create an example popover
  document.querySelectorAll('[data-bs-toggle="popover"]').forEach((popover) => {
    new bootstrap.Popover(popover);
  });
});
