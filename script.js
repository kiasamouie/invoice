$.ajaxSetup({ async: false })
var prevData
var prevEmails
var allInvoiceTypes = {
  taxi: [
    { service: 'Journey', value: null },
    { service: 'Parking', value: null }
  ],
  home: [{ service: '', value: null }]
}
$.getJSON('data.json', function (json) {
  if (!json.data) return
  prevData = json.data
  prevEmails = unique(
    $.map(prevData, function (v, i) {
      return v.customerEmail
    })
  )
})
$(document).ready(function () {
  // setCookie('invoiceNo', 1)
  let invoiceTypes = $.map(allInvoiceTypes, function (v, i) {
    return i
  })
  var vm = new Vue({
    el: '#app',
    data: {
      edit: true,
      invoiceDate: moment().format('YYYY-MM-DD'),
      invoiceTypes: invoiceTypes,
      invoiceType: null,
      invoiceNo: invoiceNo(),
      name: 'Amir Samouie',
      email: 'samouieservices@gmail.com',
      telephone: '07776274666',
      mileage: null,
      address: '',
      postcode: '',
      from: '',
      to: '',
      table: allInvoiceTypes[invoiceTypes[1]],
      pdfCreated: false,
      emails: prevEmails || [],
      customer: '',
      customerEmail: '',
      response: null,
      isReturn: false
    },
    methods: {
      add: function () {
        this.table.push({ service: '', value: null })
      },
      remove: function () {
        this.table.length > 1 && this.table.pop()
      },
      toggleEdit: function () {
        if (this.edit) {
          if (!this.customerEmail) {
            alert('Please select Customer Email')
            return
          } else if (
            (this.invoiceType == 'taxi' && (!this.from || !this.to)) ||
            (this.invoiceType == 'home' && (!this.address || !this.postcode)) ||
            !this.customer ||
            !this.total ||
            !this.table.every(row => !!row.service && !!row.value)
          ) {
            alert('Please complete invoice')
            return
          }
        }
        this.edit = !this.edit
      },
      capitalizeFirstLetter: function (string) {
        return capitalize(string)
      },
      downloadPDF: function () {
        this.pdfCreated = true
        let data = formatData(this._data, vm)
        data.function = 'add'
        console.log(data)
        $.ajax({
          url: 'ajax.php',
          async: true,
          type: 'POST',
          data: data,
          success: function (res) {
            setCookie('invoiceNo', parseInt(vm.invoiceNo) + 1)
            createPDF(vm.fileName, vm.invoiceNo)
              .save()
              .then(function () {
                vm.response = res
              })
          }
        })
      },
      sendEmail: function () {
        this.pdfCreated = true
        let formData = formatData(this._data, vm)
        formData.function = 'email'
        console.log(formData)
        createPDF(this.fileName, this.invoiceNo)
          .toPdf()
          .output('datauristring')
          .then(function (pdfURI) {
            let data = $.extend({}, formData, {
              pdfURI: pdfURI.split(',')[1]
            })
            $.ajax({
              url: 'ajax.php',
              async: false,
              type: 'POST',
              data: data,
              success: function (res) {
                vm.response = res
              }
            })
          })
      },
      onChangeEmail (event) {
        if (!prevData) return
        let map = prevData
          .filter(x => x.customerEmail === event.target.value)
          .pop()
        if (!map || !event.target.value) {
          resetFields(vm)
          return
        }
        updateFields(vm, map)
      },
      onChangeInvoiceType (event) {
        let type = event.target.value.toLowerCase()
        vm.table =
          type != '' ? allInvoiceTypes[type] : allInvoiceTypes[invoiceTypes[1]]
      },
      resetFields (event) {
        resetFields(vm)
      }
    },
    computed: {
      total () {
        return this.table
          .reduce((acc, curr) => {
            return acc + Number(curr.value)
          }, 0)
          .toFixed(2)
      },
      fileName () {
        return (
          this.customer +
          ' - #' +
          this.invoiceNo +
          ' - ' +
          this.invoiceDateParsed
        )
      },
      invoiceDateParsed () {
        return moment(this.invoiceDate).format('DD/MM/YYYY')
      }
    }
  })
  console.log(vm)
})

function invoiceNo () {
  let cookie = parseInt(getCookie('invoiceNo'))
  return (cookie > 1 && cookie) || 1
}

function resetFields (vm) {
  ;(vm.edit = true),
    (vm.invoiceDate = moment().format('YYYY-MM-DD')),
    (vm.invoiceNo = parseInt(getCookie('invoiceNo'))),
    (vm.invoiceType = null),
    (vm.mileage = null),
    (vm.from = ''),
    (vm.to = ''),
    (vm.postcode = ''),
    (vm.address = ''),
    (vm.table = allInvoiceTypes.home),
    (vm.pdfCreated = false),
    (vm.emails = prevEmails || []),
    (vm.customer = ''),
    (vm.emailBody = 'Thank you for using my service!'),
    (vm.response = null),
    (vm.isReturn = null)
}

function updateFields (vm, map) {
  vm.customer = map.customer
  vm.invoiceType = capitalize(map.invoiceType)
  vm.address = map.address
  vm.postcode = map.postcode
  vm.to = map.to
  vm.from = map.from
  // vm.invoiceNo = parseInt(map.invoiceNo) + 1
  vm.mileage = map.mileage
  vm.isReturn = map.isReturn === 'true'
  vm.table = map.table && map.table
}

function formatData (data, vm) {
  data.fileName = vm.fileName
  data.emailBody = vm.emailBody
  data.invoiceType = vm.invoiceType ? vm.invoiceType.toLowerCase() : ''
  return data
}
