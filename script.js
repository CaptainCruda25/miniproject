document.getElementById('openModal').onclick = function() {
    document.getElementById('registrationModal').style.display = 'block';
};

document.querySelector('.close').onclick = function() {
    document.getElementById('registrationModal').style.display = 'none';
};

document.getElementById('registrationForm').onsubmit = function(e) {
    e.preventDefault(); // Prevent form submission

    // Get values from the form
    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;

    // Insert the new user into the table
    const userTable = document.getElementById('userTable');
    const newRow = userTable.insertRow();
    const nameCell = newRow.insertCell(0);
    const emailCell = newRow.insertCell(1);
    const actionCell = newRow.insertCell(2);

    nameCell.textContent = name;
    emailCell.textContent = email;
    actionCell.innerHTML = '<button onclick="removeUser(this)">Remove</button>';

    // Clear the form
    document.getElementById('registrationForm').reset();
    // Close the modal
    document.getElementById('registrationModal').style.display = 'none';
};

function removeUser(button) {
    const row = button.parentElement.parentElement;
    row.parentElement.removeChild(row);
}
