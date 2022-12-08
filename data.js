function createTable (data) {
  jsGrid = $('#jsGrid').jsGrid({
    width: '100%',
    height: 'auto',
    inserting: true,
    editing: true,
    sorting: true,
    paging: true,
    data: data,
    fields: [
      { name: 'id', title: 'ID', type: 'text' },
      { name: 'invoiceNo', title: 'Invoice #', type: 'text' },
      { name: 'invoiceDate', title: 'Date', type: 'date' },
      { name: 'invoiceType', title: 'Type', type: 'text' },
      { name: 'from', title: 'From', type: 'text' },
      { name: 'to', title: 'To', type: 'text' },
      {
        name: 'isReturn',
        title: 'Is Return',
        type: 'checkbox',
        sorting: false
      },
      { name: 'mileage', title: 'Mileage', type: 'text' },
      { name: 'address', title: 'Address', type: 'text', width: 150 },
      { name: 'postcode', title: 'Postcode', type: 'text' },
      { name: 'customer', title: 'Customer', type: 'text' },
      {
        name: 'customerEmail',
        title: 'Cust. Email',
        type: 'email',
        width: 180
      },
      {
        name: 'emailSent',
        title: 'Email Sent',
        type: 'checkbox',
        sorting: false
      },
      { name: 'emailBody', title: 'Body', type: 'text', width: 300 },
      {
        name: 'pdfCreated',
        title: 'PDF',
        type: 'checkbox',
        sorting: false
      },
      { name: 'timestamp', title: 'Timestamp', type: 'text', width: 190 },
      { type: 'control' }
    ]
  })
}

$.ajaxSetup({ async: false })
var jsGrid
var data
$(document).ready(function () {
  // setCookie("invoiceNo",1)
  var vm = new Vue({
    el: '#app',
    data: {},
    methods: {
      updateData: function () {
        if (!data) return
        $.ajax({
          url: 'ajax.php',
          async: false,
          type: 'POST',
          data: {
            function: 'updateData',
            data: jsGrid.jsGrid('option', 'data')
          },
          success: function (res) {
            console.log(res)
          }
        })
      }
    },
    computed: {
      total () {}
    }
  })

  $.getJSON('data.json', function (json) {
    if (!json.data) return
    data = $.map(json.data, function (v, k) {
      return v
    })
    console.log(data)
    createTable(data)
  })

  console.log(vm)
})
