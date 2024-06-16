const tableUsersList = document.getElementById("table-users-list");
const tbodyTableUsersList = tableUsersList.querySelector("tbody");

const createRowTableUsersList = (user) => {
  const row = $("<tr></tr>");
  const tdId = $('<td class="text-center"></td>').text(user.id);
  const tdUsername = $('<td class="text-center"></td>').text(user.username);
  const tdEmail = $('<td class="text-center"></td>').text(user.email);
  const tdActive = $('<td class="text-center"></td>');
  const tdActions = $('<td class="text-center">Edit</td>');

  if (user.active) {
    tdActive.addClass("text-success");
    tdActive.text("Yes");
  } else {
    tdActive.addClass("text-danger");
    tdActive.text("No");
  }

  row.append(tdId, tdUsername, tdEmail, tdActive, tdActions);

  return row;
};

const addRowTableUsersList = (user) => {
  const row = createRowTableUsersList(user);
  $(tbodyTableUsersList).append(row);
};

const users = [
  {
    id: "glksdjfgt23k4jtbn2k34jbtk",
    username: "admin.jose",
    email: "jose@ncms.com",
    active: true,
  },
  {
    id: "k2jb34kjb2k34jjb46k2j34b6",
    username: "admin.maria",
    email: "maria@ncms.com",
    active: false,
  },
];

users.forEach((user) => {
  addRowTableUsersList(user);
});
