
<script src="<?php echo $dash_assets_url.'js/core/libraries/jquery.min.js'; ?>" type="text/javascript"></script>
<script src="<?php echo $dash_assets_url.'vendors/js/ui/tether.min.js'; ?>" type="text/javascript"></script>
<script src="<?php echo $dash_assets_url.'js/core/libraries/bootstrap.min.js'; ?>" type="text/javascript"></script>

<script src="<?php echo $dash_assets_url.'vendors/js/popper/popper.min.js'; ?>" type="text/javascript"></script>

<script src="<?php echo $dash_assets_url.'vendors/js/ui/perfect-scrollbar.jquery.min.js'; ?>" type="text/javascript"></script>
<script src="<?php echo $dash_assets_url.'vendors/js/ui/unison.min.js'; ?>" type="text/javascript"></script>
<script src="<?php echo $dash_assets_url.'vendors/js/ui/blockUI.min.js'; ?>" type="text/javascript"></script>
<script src="<?php echo $dash_assets_url.'vendors/js/ui/jquery.matchHeight-min.js'; ?>" type="text/javascript"></script>
<script src="<?php echo $dash_assets_url.'vendors/js/ui/screenfull.min.js'; ?>" type="text/javascript"></script>
<script src="<?php echo $dash_assets_url.'vendors/js/extensions/pace.min.js'; ?>" type="text/javascript"></script>
<!-- BEGIN PAGE VENDOR JS-->
<script src="<?php echo plugins_url('emp/assets-profile/vendors/js/charts/chart.min.js'); ?>" type="text/javascript"></script>
<!-- END PAGE VENDOR JS-->
<!-- BEGIN ROBUST JS-->
<script src="<?php echo $dash_assets_url.'js/core/app-menu.js'; ?>" type="text/javascript"></script>
<script src="<?php echo $dash_assets_url.'js/core/app.js'; ?>" type="text/javascript"></script>
<!-- END ROBUST JS-->

<script src="<?php echo $dash_assets_url.'js/bootstrap-notify-master/bootstrap-notify.min.js'; ?>" type="text/javascript"></script>
<script src="<?php echo  $dash_assets_url.'vendors/js/validator/validator.min.js'; ?>" type="text/javascript"></script>
<script src="<?php echo  $dash_assets_url.'vendors/js/validator/lang/fa.min.js'; ?>" type="text/javascript"></script>


<script src="<?php echo $dash_assets_url.'js/bootstrap-select/bootstrap-select.min.js'; ?>" type="text/javascript"></script>
<script src="<?php echo  $dash_assets_url.'js/bootstrap-select/defaults-fa_IR.min.js'; ?>" type="text/javascript"></script>



<!--    data table -->
<script src="<?php echo Assets::vendors('js/data-table/datatables.min.js'); ?>" type="text/javascript"></script>
<!--    end data table -->

<!-- start file upload-->
<script src="<?php echo $dash_assets_url.'js/scripts/image-upload/jquery.ui.widget.js'; ?>" type="text/javascript"></script>
<script src="<?php echo $dash_assets_url.'js/scripts/image-upload/jquery.fileupload.js'; ?>" type="text/javascript"></script>
<script src="<?php echo $dash_assets_url.'js/scripts/image-upload/jquery.iframe-transport.js'; ?>" type="text/javascript"></script>
<script src="<?php echo $dash_assets_url.'js/scripts/image-upload/jquery.fancy-fileupload.js'; ?>" type="text/javascript"></script>



<script src="<?php echo $dash_assets_url.'vendors/js/print/printThis.js'; ?>" type="text/javascript"></script>

<script src="<?php echo $dash_assets_url.'js/persian-date/persian-date.min.js'; ?>" type="text/javascript"></script>
<script src="<?php echo $dash_assets_url.'js/persian-date/persian-datepicker.min.js'; ?>" type="text/javascript"></script>


<script src="<?php echo $dash_assets_url.'vendors/js/tail/js/tail.select-full.min.js'?>"></script>
<script src="<?php echo $dash_assets_url.'vendors/js/tail/langs/tail.select-fa.js'?>"></script>

<script type="text/javascript">
    var myData=<?php echo json_encode(
        array(
            'ajax_url'=>admin_url('admin-ajax.php'),
            'user_id'=>get_current_user_id(),
        )
    );?>
</script>
<script src="<?php echo $dash_assets_url.'js/pages/admin/factory-tender-managment/factory-tender-managment.js'?>"></script>
<?php wp_footer();?>
</body>

