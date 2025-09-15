const PortoTypographyControl = function ( {
	label,
	value,
	options,
	onChange,
	removeHoverLinkClr = false,
	removeFontSetting = false
} ) {
	const __ = wp.i18n.__,
		TextControl = wp.components.TextControl,
		SelectControl = wp.components.SelectControl,
		RangeControl = wp.components.RangeControl,
		PanelColorSettings = wp.blockEditor.PanelColorSettings,
		el = wp.element.createElement;

	if ( !value ) {
		value = {};
	}

	let fonts = [ { label: __( 'Default', 'porto-functionality' ), value: '' } ];
	if ( porto_block_vars.googlefonts ) {
		porto_block_vars.googlefonts.map( function ( font, index ) {
			fonts.push( { label: font, value: font } );
		} );
	}
	return el(
		'div',
		{ className: 'porto-typography-control' },
		el(
			'h3',
			{ className: 'components-base-control', style: { marginBottom: 15 } },
			label
		),
		( !options || false !== options.fontFamily ) && ( removeFontSetting != 'swatch' ) && el( SelectControl, {
			label: __( 'Font Family', 'porto-functionality' ),
			value: value.fontFamily,
			options: fonts,
			help: __( 'If you want to use other font, please add it in Theme Options -> Skin -> Typography -> Custom Font.', 'porto-functionality' ),
			onChange: ( val ) => { value.fontFamily = val; onChange( value ) },
		} ),
		( removeFontSetting != 'swatch' ) && el( TextControl, {
			label: __( 'Font Size', 'porto-functionality' ),
			value: value.fontSize,
			help: __( 'Enter value including any valid CSS unit, ex: 30px.', 'porto-functionality' ),
			onChange: ( val ) => { value.fontSize = val; onChange( value ) },
		} ),
		( removeFontSetting != 'swatch' ) && el( RangeControl, {
			label: __( 'Font Weight', 'porto-functionality' ),
			value: value.fontWeight,
			min: 100,
			max: 900,
			step: 100,
			allowReset: true,
			onChange: ( val ) => { value.fontWeight = val; onChange( value ) },
		} ),
		( removeFontSetting != 'swatch' ) && ( !options || false !== options.textTransform ) && el( SelectControl, {
			label: __( 'Text Transform', 'porto-functionality' ),
			value: value.textTransform,
			options: [ { label: __( 'Default', 'porto-functionality' ), value: '' }, { label: __( 'Inherit', 'porto-functionality' ), value: 'inherit' }, { label: __( 'Uppercase', 'porto-functionality' ), value: 'uppercase' }, { label: __( 'Lowercase', 'porto-functionality' ), value: 'lowercase' }, { label: __( 'Capitalize', 'porto-functionality' ), value: 'capitalize' }, { label: __( 'None', 'porto-functionality' ), value: 'none' } ],
			onChange: ( val ) => { value.textTransform = val; onChange( value ) },
		} ),
		( removeFontSetting != 'swatch' ) && ( !options || false !== options.lineHeight ) && el( TextControl, {
			label: __( 'Line Height', 'porto-functionality' ),
			value: value.lineHeight,
			help: __( 'Enter value including any valid CSS unit, ex: 30px.', 'porto-functionality' ),
			onChange: ( val ) => { value.lineHeight = val; onChange( value ) },
		} ),
		( removeFontSetting != 'swatch' ) && ( !options || false !== options.letterSpacing ) && el( TextControl, {
			label: __( 'Letter Spacing', 'porto-functionality' ),
			value: value.letterSpacing,
			help: __( 'Enter value including any valid CSS unit, ex: 30px.', 'porto-functionality' ),
			onChange: ( val ) => { value.letterSpacing = val; onChange( value ) },
		} ),
		( !options || false !== options.textAlign ) && el( SelectControl, {
			label: __( 'Text Align', 'porto-functionality' ),
			value: value.textAlign,
			options: [ { label: __( 'Default', 'porto-functionality' ), value: '' }, { label: __( 'Inherit', 'porto-functionality' ), value: 'inherit' }, { label: __( 'Left', 'porto-functionality' ), value: 'left' }, { label: __( 'Center', 'porto-functionality' ), value: 'center' }, { label: __( 'Right', 'porto-functionality' ), value: 'right' }, { label: __( 'Justify', 'porto-functionality' ), value: 'justify' } ],
			onChange: ( val ) => { value.textAlign = val; onChange( value ) },
		} ),
		el( PanelColorSettings, {
			title: __( 'Color Settings', 'porto-functionality' ),
			initialOpen: false,
			colorSettings: removeHoverLinkClr ? [
				{
					label: __( 'Font Color', 'porto-functionality' ),
					value: value.color,
					onChange: ( val ) => { value.color = val; onChange( value ); }
				}
			] : [
				{
					label: ( options && options.isRating ) ? __( 'Rating Color', 'porto-functionality' ) : __( 'Font Color', 'porto-functionality' ),
					value: value.color,
					onChange: ( val ) => { value.color = val; onChange( value ); }
				},
				{
					label: ( options && options.isRating ) ? __( 'Unmarked Color', 'porto-functionality' ) : __( 'Link Hover Color', 'porto-functionality' ),
					value: value.h_color,
					onChange: ( val ) => { value.h_color = val; onChange( value ); }
				}
			]
		} ),
	);
};

export default PortoTypographyControl;

export const portoGenerateTypographyCSS = function ( font_settings, selector, type_widget = '' ) {
	var internalStyle = '';
	if ( !font_settings || !selector ) {
		return '';
	}
	if ( font_settings.alignment || font_settings.textAlign || font_settings.fontFamily || font_settings.fontSize || font_settings.fontWeight || font_settings.textTransform || font_settings.lineHeight || font_settings.letterSpacing || font_settings.color ) {
		internalStyle += '.' + selector + '{';
		if ( type_widget == 'woo-rating' ) {
			let align = 'left';
			if ( font_settings.alignment ) {
				align = font_settings.alignment;
			} else if ( font_settings.textAlign ) {
				align = font_settings.textAlign;
			}
			if ( align == 'center' ) {
				internalStyle += 'margin-left: auto; margin-right: auto;';
			} else if( align == 'right' ) {
				internalStyle += 'margin-left: auto;';
			}
		} else {
			if ( font_settings.alignment ) {
				internalStyle += 'text-align:' + font_settings.alignment + ';';
			} else if ( font_settings.textAlign ) {
				internalStyle += 'text-align:' + font_settings.textAlign + ';';
			}
		}
		if ( font_settings.fontFamily ) {
			internalStyle += 'font-family:' + font_settings.fontFamily + ';';
		}
		if ( font_settings.fontSize ) {
			let unitVal = font_settings.fontSize;
			const unit = unitVal.trim().replace( /[0-9.]/g, '' );
			if ( !unit ) {
				unitVal += 'px';
			}
			internalStyle += 'font-size:' + unitVal + ';';
			internalStyle += '--porto-fs:' + unitVal + ';';
		}
		if ( font_settings.fontWeight ) {
			internalStyle += 'font-weight:' + font_settings.fontWeight + ';';
		}
		if ( font_settings.textTransform ) {
			internalStyle += 'text-transform:' + font_settings.textTransform + ';';
		}
		if ( font_settings.lineHeight ) {
			let unitVal = font_settings.lineHeight;
			const unit = unitVal.trim().replace( /[0-9.]/g, '' );
			if ( !unit && Number( unitVal ) > 3 ) {
				unitVal += 'px';
			}
			internalStyle += 'line-height:' + unitVal + ';';
			internalStyle += '--porto-lh:' + unitVal + ';';
		}
		if ( font_settings.letterSpacing ) {
			let unitVal = font_settings.letterSpacing;
			const unit = unitVal.trim().replace( /[0-9.-]/g, '' );
			if ( !unit ) {
				unitVal += 'px';
			}
			internalStyle += 'letter-spacing:' + unitVal + ';';
		}
		if ( type_widget != 'woo-rating' && font_settings.color ) {
			internalStyle += 'color:' + font_settings.color;
		}
		internalStyle += '}';
	}

	if ( type_widget != 'woo-rating' && font_settings.h_color ) {
		internalStyle += '.' + selector + ' a:hover{';
		internalStyle += 'color:' + font_settings.h_color;
		internalStyle += '}';
	}

	if ( type_widget == 'woo-rating' ) {
		if ( font_settings.color ) {
			internalStyle += '.' + selector + ' span:before {';
			internalStyle += 'color:' + font_settings.color;
			internalStyle += '}';
		}
		if ( font_settings.h_color ) { // unmarked color
			internalStyle += '.' + selector + ':before {';
			internalStyle += 'color:' + font_settings.h_color;
			internalStyle += '}';
		}
	}
	return internalStyle;
}
