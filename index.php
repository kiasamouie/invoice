<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>Taxi Invoice</title>
    <link rel="icon" type="image/png" href="logo.png" />
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/4739febd65.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://rawgit.com/moment/moment/2.2.1/min/moment.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <!-- my shit -->
    <link rel="stylesheet" href="style.css">
    <script src="functions.js"></script>
    <script src="script.js"></script>
</head>
<div id="app">
    <div class="container mt-1" v-if="!pdfCreated">
        <div class="row">
            <div class="col-md-12">
                <div class="float-left">
                    <h2>Type</h2>
                </div>
                <div class="float-right">
                    <select class="form-control form-control-inline w-100" v-model="invoiceType" @change="onChangeInvoiceType($event)" :disabled="!customerEmail || !edit">
                        <option :value="null" selected></option>
                        <option v-for="type in invoiceTypes">{{ capitalizeFirstLetter(type) }}</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <body style="background: #eee">
        <div class="container mt-1 main">
            <div class="justify-content-center row">
                <div class="col-md-12">
                    <div class="p-3 bg-white rounded">
                        <div class="row">
                            <div class="col-md-7">
                                <div class="billed mt-1">
                                    <!-- invoice # -->
                                    <span class="font-weight-bold text-uppercase">Invoice #</span>
                                    <span v-if="!edit">{{invoiceNo}}</span>
                                    <input :disabled="!customerEmail" type="number" class="form-control form-control-inline" v-else v-model="invoiceNo">
                                </div>
                                <div class="billed mt-1 mb-1">
                                    <!-- customer -->
                                    <span class="font-weight-bold text-uppercase">Customer:</span>
                                    <span v-if="!edit">{{customer}}</span>
                                    <input :disabled="!customerEmail" type="text" v-else class="form-control form-control-inline" v-model="customer" required>
                                </div>
                                <div class="billed mt-1 mb-1">
                                    <!-- date -->
                                    <span class="font-weight-bold text-uppercase pr-2">Date:</span>
                                    <span v-if="!edit">{{invoiceDateParsed}}</span>
                                    <input :disabled="!customerEmail" type="date" v-else class="form-control form-control-inline" v-model="invoiceDate">
                                </div>
                                <div v-if="invoiceType && invoiceType.toLowerCase() == 'taxi'">
                                    <div class="billed">
                                        <!-- from -->
                                        <span class="font-weight-bold text-uppercase">From:</span>
                                        <span v-if="!edit">{{from}}</span>
                                        <input :disabled="!customerEmail" type="text" v-else class="form-control form-control-inline" v-model="from">
                                    </div>
                                    <div class="billed mt-1">
                                        <!-- to -->
                                        <span class="font-weight-bold text-uppercase pr-4">To:</span>
                                        <span v-if="!edit">{{to}}</span>
                                        <input :disabled="!customerEmail" type="text" v-else class="form-control form-control-inline" v-model="to">
                                    </div>
                                    <div class="billed mt-1">
                                        <!-- return -->
                                        <span class="font-weight-bold text-uppercase">Return:</span>
                                        <input :disabled="!customerEmail" type="checkbox" class="form-control form-control-inline" v-model="isReturn">
                                    </div>
                                </div>
                                <div v-if="invoiceType && invoiceType.toLowerCase() == 'home'">
                                    <div class="billed mt-1">
                                        <!-- address -->
                                        <span class="font-weight-bold text-uppercase">Address:</span>
                                        <span v-if="!edit">{{address}}</span>
                                        <input :disabled="!customerEmail" type="text" v-else class="form-control form-control-inline" v-model="address">
                                    </div>
                                    <div class="billed mt-1">
                                        <!-- postcode -->
                                        <span class="font-weight-bold text-uppercase">Postcode:</span>
                                        <span v-if="!edit">{{postcode}}</span>
                                        <input :disabled="!customerEmail" type="text" v-else class="form-control form-control-inline" v-model="postcode">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="float-right" style="line-height: 1.3">
                                    <!-- <div class="billed">
                                        <span class="font-weight-bold">
                                            <?php echo date('d/m/Y') ?>
                                        </span>
                                    </div> -->
                                    <!-- <div class="billed">
                                        <span class="font-weight-bold">{{name}}</span>
                                        <input type="text" v-else class="form-control form-control-inline" v-model="name">
                                    </div> -->
                                    <div class="billed">
                                        <img src="logo.png" alt="Samouie Services" class="float-right" width="175" height="120" style="border-radius: 15px;">
                                    </div>
                                    <div class="billed">
                                        <span class="font-weight-bold">{{email}}</span>
                                    </div>
                                    <div class="billed">
                                        <span class="font-weight-bold float-right">{{telephone}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" v-if="edit">
                            <div class="col-md-12">
                                <div class="float-right buttons">
                                    <i class="fas fa-plus fa-lg" v-on:click="customerEmail && add()"></i>
                                    <i class="fas fa-minus fa-lg" v-on:click="customerEmail && remove()"></i>
                                </div>
                            </div>
                            <br />
                        </div>
                        <div class="mt-3">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Service</th>
                                            <th style="text-align: right;">Price</th>
                                        </tr>
                                    </thead>
                                    <tbody v-for="(item,index) in table">
                                        <tr>
                                            <td v-if="!edit">{{item.service}}</td>
                                            <td v-else><input :disabled="!customerEmail" type="text" class="form-control form-control-inline" v-model="table[index].service"></td>
                                            <td v-if="!edit" style="text-align: right;">£{{item.value}}</td>
                                            <td v-else style="text-align: right;">£<input :disabled="!customerEmail" type="number" class="form-control form-control-inline" v-model="table[index].value"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="text-right mb-1 font-weight-bold">
                            <div class="billed" v-if="invoiceType && invoiceType.toLowerCase() == 'taxi'">
                                <span class="font-weight-bold">Mileage:</span>
                                <span v-if="!edit">{{mileage}}</span>
                                <input :disabled="!customerEmail" type="number" v-else class="form-control form-control-inline" v-model="mileage">
                            </div>
                            <div class="billed"><span class="font-weight-bold text-uppercase">Total:</span><span class="ml-1">£{{total}}</span></div>
                        </div>
                        <div class="text-right mt-2" v-if="!pdfCreated">
                            <button :disabled="!table.length" class="btn btn-info btn-md" type="button" v-on:click="toggleEdit()" v-if="!edit">Edit</button>
                            <button :disabled="!table.length" class="btn btn-success btn-md" type="button" v-on:click="toggleEdit()" v-else>Save</button>
                            <button :disabled="!customerEmail" class="btn btn-danger btn-md" type="button" v-on:click="resetFields()" v-if="edit">Reset</button>
                            <button :disabled="edit" class="btn btn-warning btn-md" type="button" v-on:click="downloadPDF()">PDF</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    <div class="container mt-3" v-if="!pdfCreated">
        <div class="row">
            <div class="col-md-12">
                <!-- <div class="float-left">
                    <h2>Email</h2>
                </div> -->
                <div class="float-right">
                    <input type="text" class="form-control form-control-inline" v-model="customerEmail" placeholder="Enter Email...">
                    <select class="form-control form-control-inline" v-model="customerEmail" @change="onChangeEmail($event)">
                        <option :value="null" selected>Select Email...</option>
                        <option v-for="email in emails">{{ email }}</option>
                    </select>
                </div>
            </div>
        </div>
        <!-- <div class="row">
            <div class="col-md-12">
                <textarea :disabled="!customerEmail" class="form-control" rows="3" cols="50" v-model="emailBody"></textarea>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="float-right">
                    <button :disabled="!customerEmail || !emailBody || edit" class="btn btn-primary btn-md mt-2" type="button" v-on:click="sendEmail()">Send Email</button>
                </div>
            </div>
        </div> -->
        <div class="row">
            <div class="col-md-12">
                <div class="float-left">
                    <a href="data.php">Grid Data</a>
                </div>
            </div>
        </div>
    </div>
    <div class="container mt-3 text-center" v-if="response">
        <!-- <i class="fas fa-thumbs-up thumb"></i>
        <br />
        <br /> -->
        <a href="data.php">Grid Data</a>
    </div>
</div>

</html>