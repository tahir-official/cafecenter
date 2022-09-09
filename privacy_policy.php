<?php
   include_once('include/header.php');
   $get_page_detail_by_id=$commonFunction->get_page_detail_by_id(202);
   $page_detail=$get_page_detail_by_id->data;
?>
<main id="main">
<section class="other_page">

    <div class="container">
        <div class="row">
        <?php echo $page_detail;?>
        </div>
    </div>
</section>

</main>
<?php
   include_once('include/footer.php');
?>