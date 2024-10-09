document.addEventListener('DOMContentLoaded', function() {
    // Your JavaScript code here


  // document.getElementById('search-input').addEventListener('input', searchTable);
  var searchInput = document.getElementById('search-input');
  if (searchInput) {
      searchInput.addEventListener('input', searchTable);
  } else {
      console.error("Elemen dengan ID 'search-input' tidak ditemukan.");
  }


  document.getElementById('search-input1').addEventListener('input', searchTable1);
  document.getElementById('search-input2').addEventListener('input', searchTable2);
});
function searchTable() {
  var input = document.getElementById('search-input').value.toLowerCase();
  // var table = document.getElementById('data-table1');
  var target = document.getElementById('table-data');
  var rows = target.getElementsByTagName('tr');

  for (var i = 0; i < rows.length; i++) {
    var cells = rows[i].getElementsByTagName('td');
    var found = false;

    for (var j = 0; j < cells.length; j++) {
      var cellValue = cells[j].textContent || cells[j].innerText;

      if (cellValue.toLowerCase().indexOf(input) > -1) {
        found = true;
        break;
      }
    }

    if (found) {
      rows[i].style.display = '';

    } else {
      rows[i].style.display = 'none';
    }
    console.log('testing')
  }
}

function searchTable1() {
  var input = document.getElementById('search-input1').value.toLowerCase();
  var table = document.getElementById('data-table2');
  var rows = table.getElementsByTagName('tr');

  for (var i = 0; i < rows.length; i++) {
    var cells = rows[i].getElementsByTagName('td');
    var found = false;

    for (var j = 0; j < cells.length; j++) {
      var cellValue = cells[j].textContent || cells[j].innerText;

      if (cellValue.toLowerCase().indexOf(input) > -1) {
        found = true;
        break;
      }
    }

    if (found) {
      rows[i].style.display = '';
    } else {
      rows[i].style.display = 'none';
    }
  }
}
function searchTable2() {
  var input = document.getElementById('search-input2').value.toLowerCase();
  var table = document.getElementById('data-table3');
  var rows = table.getElementsByTagName('tr');

  for (var i = 0; i < rows.length; i++) {
    var cells = rows[i].getElementsByTagName('td');
    var found = false;

    for (var j = 0; j < cells.length; j++) {
      var cellValue = cells[j].textContent || cells[j].innerText;

      if (cellValue.toLowerCase().indexOf(input) > -1) {
        found = true;
        break;
      }
    }

    if (found) {
      rows[i].style.display = '';
    } else {
      rows[i].style.display = 'none';
    }
  }
}
// search item menu di POS


// Tambahkan event listener untuk input pencarian
// document.getElementById("searchInput").addEventListener("input", searchItem);



