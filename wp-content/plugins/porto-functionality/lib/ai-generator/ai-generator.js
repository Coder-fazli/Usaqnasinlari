/**
 * Generate text by GPT-3
 * 
 * @since 2.8.0
 */
jQuery( function( $ ) {
    'use strict';

	$( 'body' ).on( 'click', '.porto-ai-dialog:not(.loading) .btn-close', function (e) {
		e.preventDefault();
		$( '.porto-ai-dialog' ).addClass( 'hide' );
	} );

	$( 'body' ).on( 'click', '.porto-ai-dialog .btn-copy', function (e) {
		e.preventDefault();
		$( '#ai-output' ).trigger( 'select' );
        document.execCommand( 'copy' );
		$( this ).html( wp.i18n.__( 'Copied', 'porto-functionality' ) );
	} );

	$( 'body' ).on( 'click', '.ai_generate, .button-plugin-gen', function ( e ) {
		e.preventDefault();
		var $this = $( this ),
			generateType = $( this ).attr( 'name' );
		if ( typeof js_porto_admin_vars != 'undefined' && '' != js_porto_admin_vars.ai_key ) {
			var __ = wp.i18n.__,
				aiSettings = {
					'content': { 'type': 'Content', 'max_tokens': 2048, 'temperature': 0.9, 'prompt': __( 'Please write a %1$s description about the "%2$s". %3$s %4$s', 'porto-functionality' ),'addQuery': __( 'Write at least 5 paragraphs.', 'porto-functionality' ) },
					'excerpt': { 'type': 'Excerpt', 'max_tokens': 64, 'temperature': 0.1, 'prompt': __( 'Please write a %1$s short excerpt about the "%2$s". %3$s %4$s', 'porto-functionality' ),'addQuery': __( 'The excerpt must be between 55 and 75 characters.', 'porto-functionality' ) },
					'meta_desc': { 'type': 'Meta Description for SEO', 'max_tokens': 265, 'temperature': 0.3, 'prompt': __( 'Please write a SEO friendly meta description for the %1$s "%2$s". %3$s %4$s', 'porto-functionality' ),'addQuery': __( 'The description must be between 105 and 140 characters.', 'porto-functionality' ) },
					'meta_title': { 'type': 'Meta Title for SEO', 'max_tokens': 64, 'temperature': 0.6, 'prompt': __( 'Please write a SEO friendly meta title for the %1$s "%2$s". %3$s %4$s', 'porto-functionality' ),'addQuery': __( 'The title must be between 40 and 60 characters.', 'porto-functionality' ) },
					'meta_key': { 'type': 'Meta Keywords for SEO', 'max_tokens': 265, 'temperature': 0.6, 'prompt': __( 'Please write a SEO friendly meta keywords for the %1$s "%2$s". %3$s %4$s', 'porto-functionality' ),'addQuery': __( 'Write at least 10 words.', 'porto-functionality' ) },
					'outline': { 'type': 'Outline', 'max_tokens': 2048, 'temperature': 0.9, 'prompt': __( 'Please write a %1$s outline about the "%2$s". %3$s %4$s', 'porto-functionality' ),'addQuery': __( 'Outline type is a alphanumeric outline.', 'porto-functionality' ) },
				};

			var promptTopic = $( '#ai_topic' ).length ? $( '#ai_topic' ).val() : '' ,
			contentType = $( '#ai_content_type' ).length ? $( '#ai_content_type' ).val() : '',
			writeStyle = ( $( '#ai_write_style' ).length && '' != $( '#ai_write_style' ).val() ) ? 'Writing Style: ' + $( '#ai_write_style' ).val() + '.' : '',
			$dialog = $( '.porto-ai-dialog' ),
			$outText = $dialog.find( '.output' ),
			postType = js_porto_admin_vars.post_type,
			addQuery = '',
			$userWord = $( '#user_word' );

			if ( '' == promptTopic.trim() ) {
				promptTopic = $( 'input#title' ).length ? $( 'input#title' ).val() : $( 'h1.editor-post-title' ).text();
			}

			// Initialize the options for generating Meta Description in Seo plugin
			if ( 'ai_generate' != generateType ) {
				writeStyle = '';
				contentType = 'meta_desc';
			}

			// If the title is empty
			if ( '' == promptTopic.trim() ) {
				window.alert( __( 'Please input the title.', 'porto-functionality' ) );
				return;
			}

			// If the generate type is empty
			if ( '' == contentType ) {
				window.alert( __( 'Please select the Generate Type.', 'porto-functionality' ) );
				return;
			}

			if ( $userWord.length && $userWord.val().trim().length && 'ai_generate' == generateType ) {
				addQuery = $userWord.val().trim();
				if ( '.' != addQuery.slice( -1 ) && '。' != addQuery.slice( -1 ) ) {
					addQuery += '.';
				}
			} else {
				addQuery = aiSettings[ contentType ].addQuery;
			}

			// Edit dialog title
			$dialog.removeClass( 'hide' ).addClass('loading');
			$outText.val( '' );
			$dialog.find( '.btn-copy' ).html( __( 'Copy to Clipboard','porto-functionality' ) );
			$dialog.find( '.porto-dialog-title' ).text( __( '%1$s Generating','porto-functionality' ).replace( '%1$s', aiSettings[ contentType ].type ) );

			var data = {
				model: "gpt-3.5-turbo-instruct",
				prompt: aiSettings[ contentType ].prompt.replace( '%1$s', postType ).replace( '%2$s', promptTopic ).replace( '%3$s', addQuery ).replace( '%4$s', writeStyle ).trim(),
				max_tokens: aiSettings[ contentType ].max_tokens,
				temperature: aiSettings[ contentType ].temperature,
				top_p: 1.0,
			},
			aiHttp = new XMLHttpRequest();
			aiHttp.open( "POST", "https://api.openai.com/v1/completions" );
			aiHttp.setRequestHeader( "Accept", "application/json" );
			aiHttp.setRequestHeader( "Content-Type", "application/json" );
			aiHttp.setRequestHeader( "timeout", "20000" );
			aiHttp.setRequestHeader( "Authorization", "Bearer " + js_porto_admin_vars.ai_key );

			aiHttp.onreadystatechange = function() {
				$dialog.removeClass('loading');
				if ( aiHttp.readyState == 4 && aiHttp.status == 200 ) {
					var response = JSON.parse( aiHttp.response );
					if ( 'undefined' != typeof response[ 'choices' ] && 'undefined' != typeof response[ 'choices' ][0] ) {
						var responseText = response[ 'choices' ][0]['text'].trim();
						if ( '' == responseText ) {
							$outText.val( __( 'Generate Failed!\nThere is a problem with your prompt.\n\nFor more information about creating a prompt, please visit the following URL.\n\nhttps://www.portotheme.com/wordpress/porto/documentation/how-to-use-openai-for-content-creation/#from-outline','porto-functionality') );
						} else {
							$outText.val( responseText );
						}
					}
				} else if ( 'undefined' != typeof aiHttp.response && null !== aiHttp.response.match( 'error' ) ) {
					var response = JSON.parse( aiHttp.response ),
					errorMessage = response['error']['message'];
					if ( errorMessage.match( 'API key provided(: .*)\.' ) ) {
						errorMessage = __( 'Incorrect API key provided.', 'porto-functionality' );
					}
					$outText.val( __( 'Error: %s', 'porto-functionality' ).replace( '%s', errorMessage ) );
				}
			}

			// Timeout
			aiHttp.ontimeout = function() {
				$outText.val( __( 'Request time is out.', 'porto-functionality' ) );
			};
			aiHttp.send( JSON.stringify( data ) );
			
			// Error
			aiHttp.onerror = function() {
				$outText.val( __( 'Request Failed.', 'porto-functionality' ) );
			};
		}
	})


	// Insert Auto Generator Button
	var insertGenerator = function ( plugin, $inputPlace ) {
		if ( $inputPlace.length ) {
			var __ = wp.i18n.__;
			$inputPlace.after( '<div class="button-plugin-gen components-button is-primary" name="' + plugin + '-seo"><svg version="1.2" xmlns="http://www.w3.org/2000/svg" style="margin-right: 5px;" viewBox="0 0 18 18" width="18" height="18"><g><path fill="#ffffffcc" d="m9.3 17.8c-4.9 0-8.8-3.9-8.8-8.7 0-4.9 3.9-8.8 8.8-8.8 4.8 0 8.7 3.9 8.7 8.8 0 4.8-3.9 8.7-8.8 8.7z"/><path fill="#08c" d="m9.4 13.8c-2.6 0-4.6-2.1-4.6-4.6 0-2.5 2-4.6 4.6-4.6 2.5 0 4.5 2.1 4.5 4.6 0 2.5-2 4.6-4.5 4.6z"/><path fill="#ffffffcc" d="m9.4 11.8c-1.5 0-2.6-1.2-2.6-2.6 0-1.4 1.1-2.6 2.6-2.6 1.4 0 2.5 1.2 2.5 2.6 0 1.4-1.1 2.6-2.5 2.6z"/></g></svg>' + __( 'AI Generate', 'porto-functionality' ) + '</div>' );
		}
	};

	/**
	 * Generate Meta Description for Plugins - Yoast Seo
	 * 
	 * @since 2.8.1
	 */
	$( window ).on( 'YoastSEO:ready', function () {
		var $metaWrapper = $( '#yoast-google-preview-description-metabox' ).closest( '.yst-replacevar' );
		if ( $metaWrapper.length ) {
			insertGenerator( 'yoast', $metaWrapper.find( 'button' ) );
		}
		// Collapse Meta Tab
		$( 'body' ).on( 'click', '#yoast-snippet-editor-metabox', function (e) {
			if ( 'true' == $( this ).attr( 'aria-expanded' ) ) {
				setTimeout( function () {
					var $metaWrapper = $( '#yoast-google-preview-description-metabox' ).closest( '.yst-replacevar' );
					insertGenerator( 'yoast', $metaWrapper.find( 'button' ) );	
				}, 3000 );
			}
		} );
	})
	
	/**
	 * Generate Meta Description for Plugins - All In One, RankMath Seo
	 * 
	 * @since 2.8.1
	 */
	$( document ).ready( function ( e ) {
		// All In One Seo Plugin
		if ( window.aioseo ) {
			var $inputPlace = $( 'body' ).find( '.aioseo-post-general #aioseo-post-settings-meta-description-row .add-tags .aioseo-view-all-tags' );
			// Insert AI Button
			insertGenerator( 'aio', $inputPlace );
			$( 'body' ).on( 'click', '.aioseo-app > .aioseo-tabs .md-tabs-navigation > button:first-child', function (e) {
				setTimeout( function () {
					var $inputPlace = $( 'body' ).find( '.aioseo-post-general #aioseo-post-settings-meta-description-row .add-tags .aioseo-view-all-tags' );
					insertGenerator( 'aio', $inputPlace );	
				}, 3000 );
			} );
			$( 'body' ).on( 'click', '#aioseo-post-settings-sidebar .aioseo-post-general .edit-snippet', function (e) {
				setTimeout( function () {
					var $inputPlace = $( 'body' ).find( '.aioseo-post-settings-modal #aioseo-post-settings-meta-description-row .add-tags .aioseo-view-all-tags' );
					insertGenerator( 'aio', $inputPlace );	
				}, 3000 );
			} );
		}
		
		// Rank Math Seo Plugin
		if ( window.rankMath ) {
			$( 'body' ).on('click', '.rank-math-editor > .components-tab-panel__tabs > button:first-child, .rank-math-edit-snippet', function (e) {
				setTimeout( function () {
					var $inputPlace = $( 'body' ).find( '.rank-math-editor-general [for="rank-math-editor-description"]' );
					insertGenerator( 'rank', $inputPlace );	
				}, 3000 );
			} );
		}
	} )
} );
