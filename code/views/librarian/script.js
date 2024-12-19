const hamBurger = document.querySelector(".toggle-btn");

hamBurger.addEventListener("click", function () {
  document.querySelector("#sidebar").classList.toggle("expand");
});
// Function to filter table rows
function searchTable(inputId, tableId) {
  const input = document.getElementById(inputId);
  const table = document.getElementById(tableId);
  const rows = table.getElementsByTagName('tr');
  
  input.addEventListener('keyup', function () {
      const filter = input.value.toLowerCase();

      // Loop through rows and hide those that don't match
      for (let i = 1; i < rows.length; i++) { // Start from 1 to skip table header
          let cells = rows[i].getElementsByTagName('td');
          let match = false;

          for (let j = 0; j < cells.length; j++) {
              if (cells[j].textContent.toLowerCase().includes(filter)) {
                  match = true;
                  break;
              }
          }
          rows[i].style.display = match ? '' : 'none';
      }
  });
}

// Attach search functionality
document.addEventListener('DOMContentLoaded', () => {
  searchTable('memberSearch', 'membersTable');
  searchTable('mediaSearch', 'mediaTable');
});
