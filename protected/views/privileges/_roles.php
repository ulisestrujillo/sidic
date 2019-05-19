<?php 

$i=0;
foreach ($data as $item) {
	$id='role_'.$i++;
	?>
  <div class="row rolerow" id="row<?php echo $id; ?>">
    <div class="item" id="<?php echo $id; ?>">
      <input type="checkbox" id="chkRole<?php echo $item->name; ?>"
             value="<?php echo $item->name; ?>" 
             name="Privileges[role][<?php echo $i; ?>]"
             />
    <?php echo $item->name; ?>
    </div>
  </div>
  <?php
}

?>

