jQuery(document.body).on('click', '.porto-sc-image-select li', function(e) {
	var $this = jQuery(this),
		$wrap = $this.closest( '.vc_edit_form_elements' );
	$this.addClass('active').siblings().removeClass('active');
	$this.closest('.porto-sc-image-select').next('.wpb_vc_param_value').val($this.data('id'));
	$wrap.find( 'input' ).trigger( 'change' );
});