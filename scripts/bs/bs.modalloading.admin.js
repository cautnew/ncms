/**
 * File: bs.helper.admin.js
 * Author: Felipe de Sousa Martins
 * Author E-mail: felipedesmartins@gmail.com
 *
 * Description: This file contains helper functions for Bootstrap components.
 * You can create elements easily using these functions. It's possible to mix
 * these functions with jQuery to create elements dynamically.
 */

let countProcessLoading = 0;

const createModalLoading = () => {
  const modal = $(`<div class="modal" id="modal-loading" tabindex="-1"></div>`);
  modal.modalDialog = createModalDialog();
  modal.modalContent = createModalContent();
  modal.modalBody = createModalBody();
  modal.modalBody.addClass("d-flex justify-content-center align-items-center");
  modal.modalBody.append(
    '<strong class="me-4" role="status">Loading...</strong>'
  );
  modal.modalBody.append(
    '<div class="spinner-border ms-4" style="width: 4rem; height: 4rem;" role="status"></div>'
  );
  modal.modalContent.append(modal.modalBody);
  modal.modalDialog.append(modal.modalContent);
  modal.append(modal.modalDialog);

  return modal;
};

const increaseProcessLoading = function () {
  countProcessLoading++;
  if (countProcessLoading > 0) {
    modalLoading.show();
  }
};

const decreaseProcessLoading = function () {
  countProcessLoading--;
  if (countProcessLoading <= 0) {
    countProcessLoading = 0;
    modalLoading.hide();
  }
};

const modalLoading = new bootstrap.Modal(createModalLoading());
