<?php 

foreach ($data as $item) {
	$id='user_'.$item->id;
	?>
  <div class="row userrow" id="<?php echo $id; ?>">
    <div class="col-md-8 col-sm-8 col-xs-8 col-lg-8">
      <div class="item"><?php echo $item->username.'<br/>'; ?></div>
    </div>
    <div class="col-md-4 col-sm-4 col-xs-4 col-lg-4 text-right">
      <a class="userrowver" href="#" onclick=
                  "getPrivileges(<?php echo $item->id; ?>, 
                  '<?php echo trim($item->username); ?>', '<?php echo $id; ?>'); return false;">
        ver
      </a>
    </div>
  </div>
  <?php
}
?>

<script type="text/javascript">


</script>


