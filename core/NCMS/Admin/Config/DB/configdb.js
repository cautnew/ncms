const form = document.getElementById('form-config-db');
const btnSubmit = document.getElementById('btn-submit');
const btnTestConnection = document.getElementById('btn-testconnection');
const selectConnName = document.getElementById('connname');
const inputDBName = document.getElementById('dbname');
const inputHostName = document.getElementById('host');
const inputPort = document.getElementById('port');
const inputUsername = document.getElementById('us');
const inputPassword = document.getElementById('pw');
const elmntTxtStatus = document.getElementById('status-txt');

const updateConfigDataValues = function() {
  const connName = selectConnName.value || 'local';
  const configDBData = JSON.parse(window.atob(tokenConfigDBData));

  if (configDBData[connName] === undefined) {
    inputDBName.value = '';
    inputHostName.value = '';
    inputPort.value = '';
    inputUsername.value = '';
    inputPassword.value = '';
    return;
  }

  inputDBName.value = configDBData[connName].dbname || '';
  inputHostName.value = configDBData[connName].host || '';
  inputPort.value = parseInt(configDBData[connName].port) || '';
  inputUsername.value = configDBData[connName].us || '';
  inputPassword.value = configDBData[connName].pw || '';
};

selectConnName.addEventListener('change', function() {
  updateConfigDataValues();
});

form.addEventListener('submit', function(evt) {
  evt.preventDefault();

  createToastShortMessage('Updating settings...', 'primary');

  $.ajax({
    method: 'POST',
    data: $(form).serializeArray(),
    success: function(response) {
      $('.toast').remove();
      tokenConfigDBData = response.token;
      createToastShortMessage(response.message, response.status);
    },
  });
});

btnTestConnection.addEventListener('click', function() {
  createToastShortMessage('Testing connection...', 'primary');
  $(elmntTxtStatus).text('Testing connection...').addClass('text-danger');
  $(btnTestConnection).prop('disabled', true);

  $.ajax({
    url: '/ncms/admin/config/db/testconnection',
    method: 'POST',
    data: $(form).serializeArray(),
    success: function(response) {
      $('.toast').remove();
      createToastShortMessage(response.message, response.status);
      $(elmntTxtStatus).text('Connection tested').removeClass('text-danger').addClass('text-success');
      $(btnTestConnection).prop('disabled', false);
    },
  });
});

window.addEventListener('load', function() {
  updateConfigDataValues();
});