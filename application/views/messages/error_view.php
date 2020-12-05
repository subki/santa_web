
<?php $msg=""; foreach ($messages as $message): ?>
	<?php $msg .= '<p>'.$message.'</p>'; ?>
<?php endforeach ?>

<script type="text/javascript">
	$.messager.show({
		title: 'Error',
		msg: '<?php echo $msg?>'
	});
</script>
