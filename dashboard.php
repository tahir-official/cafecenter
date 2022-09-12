<?php
   include_once('include/header.php');
         
?>
<div class="container">
   <div class="page-banner">
      <div class="row justify-content-center align-items-center h-100">
         <div class="col-md-6">
            <nav aria-label="Breadcrumb">
               <ul class="breadcrumb justify-content-center py-0 bg-transparent">
                  <li class="breadcrumb-item"><a href="<?=$site_url?>">Home</a></li>
                  <li class="breadcrumb-item active">Login</li>
               </ul>
            </nav>
            <h1 class="text-center">Login</h1>
         </div>
      </div>
   </div>
</div>
</header>

<?php
   print_r($_SESSION);
   include_once('include/footer.php');
?>
