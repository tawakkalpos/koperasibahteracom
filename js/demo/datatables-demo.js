// Call the dataTables jQuery plugin
$(document).ready(function () {
  $('#tableuser').DataTable();
  $('#tablelog').DataTable({
    "pageLength": 25
  });
  $('#searchtooltable').DataTable({
    "pageLength": 25,
    "lengthChange": false,
    "searching": false
  });
  $('#tablemenu').DataTable({
    "pageLength": 10
  });
  $('#tooltable').DataTable({
    "pageLength": 25,
    "searching": false
  });
});
