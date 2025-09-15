jQuery(document).ready(function($) {
	$('a[href*="post-new.php?post_type=porto_builder"]').on('click', function(e) {
		$.magnificPopup.open({
			items: {
				src: '#porto-builders-input'
			},
			type: 'inline',
			mainClass: 'mfp-with-zoom',
			zoom: {
				enabled: true,
				duration: 300
			},
			callbacks: {
				open: function() {
					setTimeout(function() {
						$('#porto-builders-input input[name="builder_name"]').focus();
					}, 100);
				}
			}
		});
		e.preventDefault();
	});

	if ( $( '.postoptions.porto-meta-tab select[name=header_type]' ).length ) {
		return;
	}
	let $builderType = $( 'select[name=builder_type]' );
	let $headerType = $( 'select[name=header_type]' );
	let $valueHeader = $( '[data-value=header]' );
	let $valueSide = $( '[data-value=side]' );

	if ( 'header' == $builderType.val() ) {
		$valueHeader.show();
	} else {
		$valueHeader.hide();
	}

	if ( 'header' == $builderType.val() && 'side' == $headerType.val() ) {
		$valueSide.show();
	} else {
		$valueSide.hide();
	}

	$( 'body' ).on( 'change', 'select[name=builder_type]', function() {
		if ( 'header' == $( this ).val() ) {
			$valueHeader.show();
			if ( 'side' == $headerType.val() ) {
				$valueSide.show();
			} else {
				$valueSide.hide();
			}
		} else {
			$valueHeader.hide();
			$valueSide.hide();
		}
	} );
	$( 'body' ).on( 'change', 'select[name=header_type]', function() {
		if ( 'header' == $builderType.val() && 'side' == $headerType.val() ) {
			$valueSide.show();
		} else {
			$valueSide.hide();
		}
	} );
});