// Call the dataTables jQuery plugin
$(document).ready(function() {
  $('.dataTable').DataTable({ aLengthMenu:[[5,10,15,20,50,100,200, 500,-1], [5,10,15,20,50,100,200,500, "All"]],
    iDisplayLenghth: 5});

    $('#dataTable').DataTable({ aLengthMenu:[[10,15,20,50,100,200, 500,-1], [10,15,20,50,100,200,500, "All"]],
      iDisplayLenghth: 5});

    $('#small-table').DataTable({ aLengthMenu:[[2,15,20,50,100,200, 500,-1], [2,15,20,50,100,200,500, "All"]],
      iDisplayLenghth: 2});
});
