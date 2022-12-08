function setCookie (name, value, days) {
  var expires = '; expires=Fri, 31 Dec 9999 23:59:59 GMT'
  document.cookie = name + '=' + (value || '') + expires + '; path=/'
}
function getCookie (name) {
  var nameEQ = name + '='
  var ca = document.cookie.split(';')
  for (var i = 0; i < ca.length; i++) {
    var c = ca[i]
    while (c.charAt(0) == ' ') c = c.substring(1, c.length)
    if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length)
  }
  return null
}
function eraseCookie (name) {
  document.cookie = name + '=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;'
}

function unique (array) {
  return $.grep(array, function (el, index) {
    return index === $.inArray(el, array)
  })
}

function convertTitle (text) {
  const result = text.replace(/([A-Z])/g, ' $1')
  return result.charAt(0).toUpperCase() + result.slice(1)
}

function createPDF (fileName, invoiceNo) {
  const element = document.querySelector('body')
  const opt = {
    filename: fileName,
    margin: 2,
    image: { type: 'jpeg' },
    jsPDF: { format: 'letter', orientation: 'landscape' }
  }
  setCookie('invoiceNo', parseInt(invoiceNo) + 1)
  return html2pdf()
    .from(element)
    .set(opt)
}

function capitalize (string) {
  return string.charAt(0).toUpperCase() + string.slice(1)
}
