# SparkTable

Code to be place in your blade file.

$('.traveller-table').DataTable( { 
      "processing": true,
      "serverSide": true,
      "ajax": {
            "url":"<?= route('datatable.users') ?>",
              "dataType":"json",
              "type":"POST",
            "data":{"_token":"<?= csrf_token() ?>"}
      },
      "columns":[
            {"data":"id"},
            {"data":"first_name"},
            {"data":"email"},
            {"data":"phone"},
            {"data":"gender"},
            {"data":"date_of_birth"},
            {"data":"account_type"},
            {"data":"status"},
            {"data":"action","searchable":false,"orderable":false}
      ]
} );