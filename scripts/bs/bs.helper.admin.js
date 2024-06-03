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
 * @returns jQuery object
 */
const createButton = (text) => {
  const button = $(`<button>${text}</button>`);
  button.addClass("btn");
  return button;
};

const createButtonPrimary = (text) => {
  const button = createButton(text);
  button.addClass("btn-primary");
  return button;
};

const createButtonSecondary = (text) => {
  const button = createButton(text);
  button.addClass("btn-secondary");
  return button;
};

const createButtonSuccess = (text) => {
  const button = createButton(text);
  button.addClass("btn-success");
  return button;
};

const createButtonDanger = (text) => {
  const button = createButton(text);
  button.addClass("btn-danger");
  return button;
}

const createButtonWarning = (text) => {
  const button = createButton(text);
  button.addClass("btn-warning");
  return button;
}

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
  modalFooter.closeButton.text('Close');
  modalFooter.closeButton.attr('data-bd-dismiss', 'modal');
  modalFooter.append(modalFooter.closeButton);
  return modalFooter;
};

const createModal = (id, title, bodyContent) => {
  if (id == null || id == undefined || id == '') {
    id = 'modal' + Math.floor(Math.random() * 100);
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
  toastHeader.button = $(`<button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>`);
  toastHeader.append(toastHeader.title);
  toastHeader.append(toastHeader.button);
  return toastHeader;
};

const createToast = function () {
  const toast = $(`<div class="toast" role="alert" aria-live="assertive" aria-atomic="true"></div>`);
  return toast;
};

const createToastShortMessage = (message, color) => {
  const toast = createToast();
  toast.addClass(`align-items-center bg-${color} text-bg-${color} border-0`);
  const toastBodyArea = $(`<div class="d-flex"></div>`);
  toast.append(toastBodyArea);
  const toastBody = $(`<div class="toast-body">${message}</div>`);
  const toastCloseButton = $(`<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>`);
  
  toastBodyArea.append(toastBody);
  toastBodyArea.append(toastCloseButton);

  $('.toast-container').prepend(toast);
  const bsToast = new bootstrap.Toast(toast);
  bsToast.show();
};

window.addEventListener('hidden.bs.toast', function(evt){
  $(evt.target).remove();
});

window.addEventListener("load", function() {
  // Create an example popover
  document.querySelectorAll('[data-bs-toggle="popover"]')
  .forEach(popover => {
    new bootstrap.Popover(popover)
  })
});
