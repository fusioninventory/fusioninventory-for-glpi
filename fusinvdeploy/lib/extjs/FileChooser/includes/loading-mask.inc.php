<div id="loading-container">
	<div id="loading-mask"></div>
	<div id="loading-wrapper">
		<div id="loading-indicator"><img src="images/loading.gif" alt="" /><br />Loading...</div>
	</div>
</div>
<script type="text/javascript">
Ext.get('loading-indicator').center(Ext.getBody());
// Hide the loading indicator
setTimeout(function() {
	Ext.fly('loading-container').fadeOut();
}, 250);
</script>