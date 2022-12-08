<?php require 'html.php';
echo HTML::Doctype();
?>
<html lang="en">

<head>
   <meta charset="utf-8" />
   <title>Taxi Invoice</title>
   <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
   <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.js"></script>
   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
   <script src="https://kit.fontawesome.com/4739febd65.js" crossorigin="anonymous"></script>
   <script src="https://rawgit.com/moment/moment/2.2.1/min/moment.min.js"></script>
   <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.js"></script>
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
   <link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.css" />
   <link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid-theme.min.css" />

   <!-- my shit -->
   <link rel="stylesheet" href="data.css">
   <script src="functions.js"></script>
   <script src="data.js"></script>
</head>


<body>
   <?php
   // echo  HTML::Tag(
   //    'div',
   //    ['class' => 'container-fluid', 'id' => 'app'],
   //    HTML::Tag(
   //       'div',
   //       ['class' => 'row'],
   //       HTML::Tag(
   //          'div',
   //          ['class' => 'col-md-12'],
   //          HTML::Anchor("index.php", "Create New Invoice")
   //       )
   //    )
   // );
   ?>

   <div class="container-fluid" id="app">
      <div class="row">
         <div class="col-md-12">
            <?php echo HTML::Anchor("index.php", "Create New Invoice") ?>
         </div>
         <div class="col-md-12">
            <?php echo HTML::Tag('div', ['id' => 'jsGrid']) ?>
         </div>
         <div class="col-md-12">
            <div class="float-right">
               <?php echo HTML::Button('Update Data', ['class' => 'btn btn-primary btn-md', 'v-on:click' => 'updateData']) ?>
            </div>
         </div>
      </div>
   </div>
</body>

</html>