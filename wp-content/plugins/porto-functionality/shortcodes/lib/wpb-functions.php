<?php

// Add simple line icon font
if ( ! function_exists( 'vc_iconpicker_type_simpleline' ) ) {
	add_filter( 'vc_iconpicker-type-simpleline', 'vc_iconpicker_type_simpleline' );

	function vc_iconpicker_type_simpleline( $icons ) {
		$simpleline_icons = array(
			array( 'Simple-Line-Icons-user' => 'User' ),
			array( 'Simple-Line-Icons-people' => 'People' ),
			array( 'Simple-Line-Icons-user-female' => 'User Female' ),
			array( 'Simple-Line-Icons-user-follow' => 'User Follow' ),
			array( 'Simple-Line-Icons-user-following' => 'User Following' ),
			array( 'Simple-Line-Icons-user-unfollow' => 'User Unfollow' ),
			array( 'Simple-Line-Icons-login' => 'Login' ),
			array( 'Simple-Line-Icons-logout' => 'Logout' ),
			array( 'Simple-Line-Icons-emotsmile' => 'Emotsmile' ),
			array( 'Simple-Line-Icons-phone' => 'Phone' ),
			array( 'Simple-Line-Icons-call-end' => 'Call End' ),
			array( 'Simple-Line-Icons-call-in' => 'Call In' ),
			array( 'Simple-Line-Icons-call-out' => 'Call Out' ),
			array( 'Simple-Line-Icons-map' => 'Map' ),
			array( 'Simple-Line-Icons-location-pin' => 'Location Pin' ),
			array( 'Simple-Line-Icons-direction' => 'Direction' ),
			array( 'Simple-Line-Icons-directions' => 'Directions' ),
			array( 'Simple-Line-Icons-compass' => 'Compass' ),
			array( 'Simple-Line-Icons-layers' => 'Layers' ),
			array( 'Simple-Line-Icons-menu' => 'Menu' ),
			array( 'Simple-Line-Icons-list' => 'List' ),
			array( 'Simple-Line-Icons-options-vertical' => 'Options Vertical' ),
			array( 'Simple-Line-Icons-options' => 'Options' ),
			array( 'Simple-Line-Icons-arrow-down' => 'Arrow Down' ),
			array( 'Simple-Line-Icons-arrow-left' => 'Arrow Left' ),
			array( 'Simple-Line-Icons-arrow-right' => 'Arrow Right' ),
			array( 'Simple-Line-Icons-arrow-up' => 'Arrow Up' ),
			array( 'Simple-Line-Icons-arrow-up-circle' => 'Arrow Up Circle' ),
			array( 'Simple-Line-Icons-arrow-left-circle' => 'Arrow Left Circle' ),
			array( 'Simple-Line-Icons-arrow-right-circle' => 'Arrow Right Circle' ),
			array( 'Simple-Line-Icons-arrow-down-circle' => 'Arrow Down Circle' ),
			array( 'Simple-Line-Icons-check' => 'Check' ),
			array( 'Simple-Line-Icons-clock' => 'Clock' ),
			array( 'Simple-Line-Icons-plus' => 'Plus' ),
			array( 'Simple-Line-Icons-minus' => 'Minus' ),
			array( 'Simple-Line-Icons-close' => 'Close' ),
			array( 'Simple-Line-Icons-event' => 'Event' ),
			array( 'Simple-Line-Icons-exclamation' => 'Exclamation' ),
			array( 'Simple-Line-Icons-organization' => 'Organization' ),
			array( 'Simple-Line-Icons-trophy' => 'Trophy' ),
			array( 'Simple-Line-Icons-screen-smartphone' => 'Smartphone' ),
			array( 'Simple-Line-Icons-screen-desktop' => 'Desktop' ),
			array( 'Simple-Line-Icons-plane' => 'Plane' ),
			array( 'Simple-Line-Icons-notebook' => 'Notebook' ),
			array( 'Simple-Line-Icons-mustache' => 'Mustache' ),
			array( 'Simple-Line-Icons-mouse' => 'Mouse' ),
			array( 'Simple-Line-Icons-magnet' => 'Magnet' ),
			array( 'Simple-Line-Icons-energy' => 'Energy' ),
			array( 'Simple-Line-Icons-disc' => 'Disc' ),
			array( 'Simple-Line-Icons-cursor' => 'Cursor' ),
			array( 'Simple-Line-Icons-cursor-move' => 'Cursor Move' ),
			array( 'Simple-Line-Icons-crop' => 'Crop' ),
			array( 'Simple-Line-Icons-chemistry' => 'Chemistry' ),
			array( 'Simple-Line-Icons-speedometer' => 'Speedometer' ),
			array( 'Simple-Line-Icons-shield' => 'Shield' ),
			array( 'Simple-Line-Icons-screen-tablet' => 'Tablet' ),
			array( 'Simple-Line-Icons-magic-wand' => 'Magic Wand' ),
			array( 'Simple-Line-Icons-hourglass' => 'Hourglass' ),
			array( 'Simple-Line-Icons-graduation' => 'Graduation' ),
			array( 'Simple-Line-Icons-ghost' => 'Ghost' ),
			array( 'Simple-Line-Icons-game-controller' => 'Game Controller' ),
			array( 'Simple-Line-Icons-fire' => 'Fire' ),
			array( 'Simple-Line-Icons-eyeglass' => 'Eyeglass' ),
			array( 'Simple-Line-Icons-envelope-open' => 'Envelope Open' ),
			array( 'Simple-Line-Icons-envelope-letter' => 'Envelope Letter' ),
			array( 'Simple-Line-Icons-bell' => 'Bell' ),
			array( 'Simple-Line-Icons-badge' => 'Badge' ),
			array( 'Simple-Line-Icons-anchor' => 'Anchor' ),
			array( 'Simple-Line-Icons-wallet' => 'Wallet' ),
			array( 'Simple-Line-Icons-vector' => 'Vector' ),
			array( 'Simple-Line-Icons-speech' => 'Speech' ),
			array( 'Simple-Line-Icons-puzzle' => 'Puzzle' ),
			array( 'Simple-Line-Icons-printer' => 'Printer' ),
			array( 'Simple-Line-Icons-present' => 'Present' ),
			array( 'Simple-Line-Icons-playlist' => 'Playlist' ),
			array( 'Simple-Line-Icons-pin' => 'Pin' ),
			array( 'Simple-Line-Icons-picture' => 'Picture' ),
			array( 'Simple-Line-Icons-handbag' => 'Handbag' ),
			array( 'Simple-Line-Icons-globe-alt' => 'Globe Alt' ),
			array( 'Simple-Line-Icons-globe' => 'Globe' ),
			array( 'Simple-Line-Icons-folder-alt' => 'Folder Alt' ),
			array( 'Simple-Line-Icons-folder' => 'Folder' ),
			array( 'Simple-Line-Icons-film' => 'Film' ),
			array( 'Simple-Line-Icons-feed' => 'Feed' ),
			array( 'Simple-Line-Icons-drop' => 'Drop' ),
			array( 'Simple-Line-Icons-drawer' => 'Drawer' ),
			array( 'Simple-Line-Icons-docs' => 'Docs' ),
			array( 'Simple-Line-Icons-doc' => 'Doc' ),
			array( 'Simple-Line-Icons-diamond' => 'Diamond' ),
			array( 'Simple-Line-Icons-cup' => 'Cup' ),
			array( 'Simple-Line-Icons-calculator' => 'Calculator' ),
			array( 'Simple-Line-Icons-bubbles' => 'Bubbles' ),
			array( 'Simple-Line-Icons-briefcase' => 'Briefcase' ),
			array( 'Simple-Line-Icons-book-open' => 'Book Open' ),
			array( 'Simple-Line-Icons-basket-loaded' => 'Basket Loaded' ),
			array( 'Simple-Line-Icons-basket' => 'Basket' ),
			array( 'Simple-Line-Icons-bag' => 'Bag' ),
			array( 'Simple-Line-Icons-action-undo' => 'Action Undo' ),
			array( 'Simple-Line-Icons-action-redo' => 'Action Redo' ),
			array( 'Simple-Line-Icons-wrench' => 'Wrench' ),
			array( 'Simple-Line-Icons-umbrella' => 'Umbrella' ),
			array( 'Simple-Line-Icons-trash' => 'Trash' ),
			array( 'Simple-Line-Icons-tag' => 'Tag' ),
			array( 'Simple-Line-Icons-support' => 'Support' ),
			array( 'Simple-Line-Icons-frame' => 'Frame' ),
			array( 'Simple-Line-Icons-size-fullscreen' => 'Size Fullscreen' ),
			array( 'Simple-Line-Icons-size-actual' => 'Size Actual' ),
			array( 'Simple-Line-Icons-shuffle' => 'Shuffle' ),
			array( 'Simple-Line-Icons-share-alt' => 'Share Alt' ),
			array( 'Simple-Line-Icons-share' => 'Share' ),
			array( 'Simple-Line-Icons-rocket' => 'Rocket' ),
			array( 'Simple-Line-Icons-question' => 'Question' ),
			array( 'Simple-Line-Icons-pie-chart' => 'Pie Chart' ),
			array( 'Simple-Line-Icons-pencil' => 'Pencil' ),
			array( 'Simple-Line-Icons-note' => 'Note' ),
			array( 'Simple-Line-Icons-loop' => 'Loop' ),
			array( 'Simple-Line-Icons-home' => 'Home' ),
			array( 'Simple-Line-Icons-grid' => 'Grid' ),
			array( 'Simple-Line-Icons-graph' => 'Graph' ),
			array( 'Simple-Line-Icons-microphone' => 'Microphone' ),
			array( 'Simple-Line-Icons-music-tone-alt' => 'Music Tone Alt' ),
			array( 'Simple-Line-Icons-music-tone' => 'Music Tone' ),
			array( 'Simple-Line-Icons-earphones-alt' => 'Earphones Alt' ),
			array( 'Simple-Line-Icons-earphones' => 'Earphones' ),
			array( 'Simple-Line-Icons-equalizer' => 'Equalizer' ),
			array( 'Simple-Line-Icons-like' => 'Like' ),
			array( 'Simple-Line-Icons-dislike' => 'Dislike' ),
			array( 'Simple-Line-Icons-control-start' => 'Control Start' ),
			array( 'Simple-Line-Icons-control-rewind' => 'Control Rewind' ),
			array( 'Simple-Line-Icons-control-play' => 'Control Play' ),
			array( 'Simple-Line-Icons-control-pause' => 'Control Pause' ),
			array( 'Simple-Line-Icons-control-forward' => 'Control Forward' ),
			array( 'Simple-Line-Icons-control-end' => 'Control End' ),
			array( 'Simple-Line-Icons-volume-1' => 'Volume 1' ),
			array( 'Simple-Line-Icons-volume-2' => 'Volume 2' ),
			array( 'Simple-Line-Icons-volume-off' => 'Volume Off' ),
			array( 'Simple-Line-Icons-calendar' => 'Calendar' ),
			array( 'Simple-Line-Icons-bulb' => 'Bulb' ),
			array( 'Simple-Line-Icons-chart' => 'Chart' ),
			array( 'Simple-Line-Icons-ban' => 'Ban' ),
			array( 'Simple-Line-Icons-bubble' => 'Bubble' ),
			array( 'Simple-Line-Icons-camcorder' => 'Camcorder' ),
			array( 'Simple-Line-Icons-camera' => 'Camera' ),
			array( 'Simple-Line-Icons-cloud-download' => 'Cloud Download' ),
			array( 'Simple-Line-Icons-cloud-upload' => 'Cloud Upload' ),
			array( 'Simple-Line-Icons-envelope' => 'Envelope' ),
			array( 'Simple-Line-Icons-eye' => 'Eye' ),
			array( 'Simple-Line-Icons-flag' => 'Flag' ),
			array( 'Simple-Line-Icons-heart' => 'Heart' ),
			array( 'Simple-Line-Icons-info' => 'Info' ),
			array( 'Simple-Line-Icons-key' => 'Key' ),
			array( 'Simple-Line-Icons-link' => 'Link' ),
			array( 'Simple-Line-Icons-lock' => 'Lock' ),
			array( 'Simple-Line-Icons-lock-open' => 'Lock Open' ),
			array( 'Simple-Line-Icons-magnifier' => 'Magnifier' ),
			array( 'Simple-Line-Icons-magnifier-add' => 'Magnifier Add' ),
			array( 'Simple-Line-Icons-magnifier-remove' => 'Magnifier Remove' ),
			array( 'Simple-Line-Icons-paper-clip' => 'Paper Clip' ),
			array( 'Simple-Line-Icons-paper-plane' => 'Paper Plane' ),
			array( 'Simple-Line-Icons-power' => 'Power' ),
			array( 'Simple-Line-Icons-refresh' => 'Refresh' ),
			array( 'Simple-Line-Icons-reload' => 'Reload' ),
			array( 'Simple-Line-Icons-settings' => 'Settings' ),
			array( 'Simple-Line-Icons-star' => 'Star' ),
			array( 'Simple-Line-Icons-symbol-female' => 'Symbol Female' ),
			array( 'Simple-Line-Icons-symbol-male' => 'Symbol Male' ),
			array( 'Simple-Line-Icons-target' => 'Target' ),
			array( 'Simple-Line-Icons-credit-card' => 'Credit Card' ),
			array( 'Simple-Line-Icons-paypal' => 'Paypal' ),
			array( 'Simple-Line-Icons-social-tumblr' => 'Tumblr' ),
			array( 'Simple-Line-Icons-social-twitter' => 'Twitter' ),
			array( 'Simple-Line-Icons-social-facebook' => 'Facebook' ),
			array( 'Simple-Line-Icons-social-instagram' => 'Instagram' ),
			array( 'Simple-Line-Icons-social-linkedin' => 'Linkedin' ),
			array( 'Simple-Line-Icons-social-pinterest' => 'Pinterest' ),
			array( 'Simple-Line-Icons-social-github' => 'Github' ),
			array( 'Simple-Line-Icons-social-google' => 'Google' ),
			array( 'Simple-Line-Icons-social-reddit' => 'Reddit' ),
			array( 'Simple-Line-Icons-social-skype' => 'Skype' ),
			array( 'Simple-Line-Icons-social-dribbble' => 'Dribbble' ),
			array( 'Simple-Line-Icons-social-behance' => 'Behance' ),
			array( 'Simple-Line-Icons-social-foursqare' => 'Foursqare' ),
			array( 'Simple-Line-Icons-social-soundcloud' => 'Soundcloud' ),
			array( 'Simple-Line-Icons-social-spotify' => 'Spotify' ),
			array( 'Simple-Line-Icons-social-stumbleupon' => 'Stumbleupon' ),
			array( 'Simple-Line-Icons-social-youtube' => 'Youtube' ),
			array( 'Simple-Line-Icons-social-dropbox' => 'Dropbox' ),
			array( 'Simple-Line-Icons-social-vkontakte' => 'Vkontakte' ),
			array( 'Simple-Line-Icons-social-steam' => 'Steam' ),
			array( 'Simple-Line-Icons-moustache' => 'Moustache' ),
			array( 'Simple-Line-Icons-bar-chart' => 'Bar Chart' ),
			array( 'Simple-Line-Icons-pointer' => 'Pointer' ),
			array( 'Simple-Line-Icons-users' => 'Users' ),
			array( 'Simple-Line-Icons-eyeglasses' => 'Eyeglasses' ),
			array( 'Simple-Line-Icons-symbol-fermale' => 'Symbol Fermale' ),
		);

		return array_merge( $icons, $simpleline_icons );
	}
}

// Add porto icon font
if ( ! function_exists( 'vc_iconpicker_type_porto' ) ) {
	add_filter( 'vc_iconpicker-type-porto', 'vc_iconpicker_type_porto' );

	function vc_iconpicker_type_porto( $icons ) {
		$porto_icons = array(
			array( 'porto-icon-spin1' => 'Spin1' ),
			array( 'porto-icon-spin2' => 'Spin2' ),
			array( 'porto-icon-spin3' => 'Spin3' ),
			array( 'porto-icon-spin4' => 'Spin4' ),
			array( 'porto-icon-spin5' => 'Spin5' ),
			array( 'porto-icon-spin6' => 'Spin6' ),
			array( 'porto-icon-firefox' => 'Firefox' ),
			array( 'porto-icon-chrome' => 'Chrome' ),
			array( 'porto-icon-opera' => 'Opera' ),
			array( 'porto-icon-ie' => 'Ie' ),
			array( 'porto-icon-phone' => 'Phone' ),
			array( 'porto-icon-down-dir' => 'Down Dir' ),
			array( 'porto-icon-cart' => 'Cart' ),
			array( 'porto-icon-up-dir' => 'Up Dir' ),
			array( 'porto-icon-mode-grid' => 'Mode Grid' ),
			array( 'porto-icon-mode-list' => 'Mode List' ),
			array( 'porto-icon-compare' => 'Compare' ),
			array( 'porto-icon-wishlist' => 'Wishlist' ),
			array( 'porto-icon-search' => 'Search' ),
			array( 'porto-icon-left-dir' => 'Left Dir' ),
			array( 'porto-icon-right-dir' => 'Right Dir' ),
			array( 'porto-icon-down-open' => 'Down Open' ),
			array( 'porto-icon-left-open' => 'Left Open' ),
			array( 'porto-icon-right-open' => 'Right Open' ),
			array( 'porto-icon-up-open' => 'Up Open' ),
			array( 'porto-icon-angle-left' => 'Angle Left' ),
			array( 'porto-icon-angle-right' => 'Angle Right' ),
			array( 'porto-icon-angle-up' => 'Angle Up' ),
			array( 'porto-icon-angle-down' => 'Angle Down' ),
			array( 'porto-icon-down' => 'Down' ),
			array( 'porto-icon-left' => 'Left' ),
			array( 'porto-icon-right' => 'Right' ),
			array( 'porto-icon-up' => 'Up' ),
			array( 'porto-icon-angle-double-left' => 'Angle Double Left' ),
			array( 'porto-icon-angle-double-right' => 'Angle Double Right' ),
			array( 'porto-icon-angle-double-up' => 'Angle Double Up' ),
			array( 'porto-icon-angle-double-down' => 'Angle Double Down' ),
			array( 'porto-icon-mail' => 'Mail' ),
			array( 'porto-icon-location' => 'Location' ),
			array( 'porto-icon-skype' => 'Skype' ),
			array( 'porto-icon-right-open-big' => 'Right Open Big' ),
			array( 'porto-icon-left-open-big' => 'Left Open Big' ),
			array( 'porto-icon-down-open-big' => 'Down Open Big' ),
			array( 'porto-icon-up-open-big' => 'Up Open Big' ),
			array( 'porto-icon-cancel' => 'Cancel' ),
			array( 'porto-icon-user' => 'User' ),
			array( 'porto-icon-mail-alt' => 'Mail Alt' ),
			array( 'porto-icon-fax' => 'Fax' ),
			array( 'porto-icon-lock' => 'Lock' ),
			array( 'porto-icon-company' => 'Company' ),
			array( 'porto-icon-city' => 'City' ),
			array( 'porto-icon-post' => 'Post' ),
			array( 'porto-icon-country' => 'Country' ),
			array( 'porto-icon-calendar' => 'Calendar' ),
			array( 'porto-icon-doc' => 'Doc' ),
			array( 'porto-icon-mobile' => 'Mobile' ),
			array( 'porto-icon-clock' => 'Clock' ),
			array( 'porto-icon-chat' => 'Chat' ),
			array( 'porto-icon-tag' => 'Tag' ),
			array( 'porto-icon-folder' => 'Folder' ),
			array( 'porto-icon-folder-open' => 'Folder Open' ),
			array( 'porto-icon-forward' => 'Forward' ),
			array( 'porto-icon-reply' => 'Reply' ),
			array( 'porto-icon-cog' => 'Cog' ),
			array( 'porto-icon-cog-alt' => 'Cog Alt' ),
			array( 'porto-icon-wrench' => 'Wrench' ),
			array( 'porto-icon-quote-left' => 'Quote Left' ),
			array( 'porto-icon-quote-right' => 'Quote Right' ),
			array( 'porto-icon-gift' => 'Gift' ),
			array( 'porto-icon-dollar' => 'Dollar' ),
			array( 'porto-icon-euro' => 'Euro' ),
			array( 'porto-icon-pound' => 'Pound' ),
			array( 'porto-icon-rupee' => 'Rupee' ),
			array( 'porto-icon-yen' => 'Yen' ),
			array( 'porto-icon-rouble' => 'Rouble' ),
			array( 'porto-icon-try' => 'Try' ),
			array( 'porto-icon-won' => 'Won' ),
			array( 'porto-icon-bitcoin' => 'Bitcoin' ),
			array( 'porto-icon-ok' => 'Ok' ),
			array( 'porto-icon-chevron-left' => 'Chevron Left' ),
			array( 'porto-icon-chevron-right' => 'Chevron Right' ),
			array( 'porto-icon-export' => 'Export' ),
			array( 'porto-icon-star' => 'Star' ),
			array( 'porto-icon-star-empty' => 'Star Empty' ),
			array( 'porto-icon-plus-squared' => 'Plus Squared' ),
			array( 'porto-icon-minus-squared' => 'Minus Squared' ),
			array( 'porto-icon-plus-squared-alt' => 'Plus Squared Alt' ),
			array( 'porto-icon-minus-squared-alt' => 'Minus Squared Alt' ),
			array( 'porto-icon-truck' => 'Truck' ),
			array( 'porto-icon-lifebuoy' => 'Lifebuoy' ),
			array( 'porto-icon-pencil' => 'Pencil' ),
			array( 'porto-icon-users' => 'Users' ),
			array( 'porto-icon-video' => 'Video' ),
			array( 'porto-icon-menu' => 'Menu' ),
			array( 'porto-icon-desktop' => 'Desktop' ),
			array( 'porto-icon-doc-inv' => 'Doc Inv' ),
			array( 'porto-icon-circle' => 'Circle' ),
			array( 'porto-icon-circle-empty' => 'Circle Empty' ),
			array( 'porto-icon-circle-thin' => 'Circle Thin' ),
			array( 'porto-icon-mini-cart' => 'Mini Cart' ),
			array( 'porto-icon-paper-plane' => 'Paper Plane' ),
			array( 'porto-icon-attention-alt' => 'Attention Alt' ),
			array( 'porto-icon-info' => 'Info' ),
			array( 'porto-icon-compare-link' => 'Compare Link' ),
			array( 'porto-icon-cat-default' => 'Cat Default' ),
			array( 'porto-icon-cat-computer' => 'Cat Computer' ),
			array( 'porto-icon-cat-couch' => 'Cat Couch' ),
			array( 'porto-icon-cat-garden' => 'Cat Garden' ),
			array( 'porto-icon-cat-gift' => 'Cat Gift' ),
			array( 'porto-icon-cat-shirt' => 'Cat Shirt' ),
			array( 'porto-icon-cat-sport' => 'Cat Sport' ),
			array( 'porto-icon-cat-toys' => 'Cat Toys' ),
			array( 'porto-icon-tag-line' => 'Tag L`ine' ),
			array( 'porto-icon-bird' => 'Bird' ),
			array( 'porto-icon-cat' => 'Cat' ),
			array( 'porto-icon-dog' => 'Dog' ),
			array( 'porto-icon-fish' => 'Fish' ),
			array( 'porto-icon-pet' => 'Pet' ),
			array( 'porto-icon-special-offer' => 'Special Offer' ),
			array( 'porto-icon-bag' => 'Bag' ),
			array( 'porto-icon-search-1' => 'Search-1' ),
			array( 'porto-icon-plus' => 'Plus' ),
			array( 'porto-icon-minus' => 'Minus' ),
			array( 'porto-icon-search-2' => 'Search-2' ),
			array( 'porto-icon-bag-1' => 'Bag-1' ),
			array( 'porto-icon-online-support' => 'Online Support' ),
			array( 'porto-icon-shopping-bag' => 'Shopping Bag' ),
			array( 'porto-icon-us-dollar' => 'Us Dollar' ),
			array( 'porto-icon-shipped' => 'Shipped' ),
			array( 'porto-icon-list' => 'List' ),
			array( 'porto-icon-money' => 'Money' ),
			array( 'porto-icon-shipping' => 'Shipping' ),
			array( 'porto-icon-support' => 'Support' ),
			array( 'porto-icon-bag-2' => 'Bag-2' ),
			array( 'porto-icon-grid' => 'Grid' ),
			array( 'porto-icon-bag-3' => 'Bag-3' ),
			array( 'porto-icon-direction' => 'Direction' ),
			array( 'porto-icon-home' => 'Home' ),
			array( 'porto-icon-magnifier' => 'Magnifier' ),
			array( 'porto-icon-magnifier-add' => 'Magnifier Add' ),
			array( 'porto-icon-magnifier-remove' => 'Magnifier Remove' ),
			array( 'porto-icon-phone-1' => 'Phone-1' ),
			array( 'porto-icon-clock-1' => 'Clock-1' ),
			array( 'porto-icon-heart' => 'Heart' ),
			array( 'porto-icon-heart-1' => 'Heart-1' ),
			array( 'porto-icon-earphones-alt' => 'Earphones Alt' ),
			array( 'porto-icon-credit-card' => 'Credit Card' ),
			array( 'porto-icon-action-undo' => 'Action Undo' ),
			array( 'porto-icon-envolope' => 'Envolope' ),
			array( 'porto-icon-twitter' => 'Twitter' ),
			array( 'porto-icon-facebook' => 'Facebook' ),
			array( 'porto-icon-spinner' => 'Spinner' ),
			array( 'porto-icon-instagram' => 'Instagram' ),
			array( 'porto-icon-check-empty' => 'Check Empty' ),
			array( 'porto-icon-check' => 'Check' ),

			array( 'porto-icon-category-gifts' => 'Ribbon, Category Gifts' ),
			array( 'porto-icon-category-home' => 'Home, Category' ),
			array( 'porto-icon-category-motors' => 'Car, Motors' ),
			array( 'porto-icon-category-music' => 'Music(microphone, sound)' ),
			array( 'porto-icon-category-electronics' => 'Electronics(clock)' ),
			array( 'porto-icon-category-fashion' => 'Fashion(clothes, women, dress)' ),
			array( 'porto-icon-category-furniture' => 'Furniture(chair)' ),
			array( 'porto-icon-category-garden' => 'Garden(flower)' ),
			array( 'porto-icon-category-lanterns-lighting' => 'Lanterns lighting' ),
			array( 'porto-icon-category-mechanics' => 'Mechanics' ),
			array( 'porto-icon-category-motorcycles' => 'Motorcycle(vehicle, bike)' ),
			array( 'porto-icon-category-sound-video' => 'Sound Video(volume)' ),
			array( 'porto-icon-category-steering' => 'Steering' ),
			array( 'porto-icon-category-external-accessories' => 'External Accessories(truck, car)' ),
			array( 'porto-icon-category-fluids' => 'Fluids' ),
			array( 'porto-icon-category-hot-deals' => 'Hot Deals(tagline)' ),
			array( 'porto-icon-category-internal-accessories' => 'Internal Accessories' ),
			array( 'porto-icon-category-chains' => 'Bicycle Chains' ),
			array( 'porto-icon-category-frames' => 'Bicycle Frames' ),
			array( 'porto-icon-category-pedals' => 'Bicycle Pedals' ),
			array( 'porto-icon-category-saddle' => 'Bicycle Saddle' ),
			array( 'porto-icon-category-tools' => 'Spanner Wrench' ),
			array( 'porto-icon-search-3' => 'Search' ),
			array( 'porto-icon-secure-payment' => 'Secure Payment(card, lock)' ),
			array( 'porto-icon-user-2' => 'User' ),
			array( 'porto-icon-wishlist-2' => 'Wishlist' ),
			array( 'porto-icon-gift-2' => 'Gift' ),
			array( 'porto-icon-edit' => 'Edit(pencil)' ),
			array( 'porto-icon-chef' => 'Chef' ),
			array( 'porto-icon-smiling-girl' => 'Smiling Girl' ),
			array( 'porto-icon-tshirt' => 'T-Shirt' ),
			array( 'porto-icon-boy-broad-smile' => 'Boy Broad Smile' ),
			array( 'porto-icon-smiling-baby' => 'Smiling Baby' ),
			array( 'porto-icon-bars' => 'Bars(menu button of three lines of outline)' ),
			array( 'porto-icon-tag-percent' => 'Tag Percent' ),
			array( 'porto-icon-joystick' => 'Joystick(console)' ),
			array( 'porto-icon-shopping-cart' => 'Shopping Cart(bag)' ),
			array( 'porto-icon-phone-2' => 'Phone' ),
			array( 'porto-icon-comida-organica' => 'Hand & Sprout' ),
			array( 'porto-icon-estrela' => 'Shining Star(Estrela)' ),
			array( 'porto-icon-fazer-compras' => 'Fazer Compras' ),
			array( 'porto-icon-gluten' => 'Gluten(Twig, Sprig)' ),
			array( 'porto-icon-arrow-forward-right' => 'Arrow Forward Right' ),
			array( 'porto-icon-percent-circle' => 'Percent Circle' ),
			array( 'porto-icon-pulley' => 'Pulley' ),
			array( 'porto-icon-password-lock' => 'Password Lock' ),
			array( 'porto-icon-pin' => 'Pin, Location' ),
			array( 'porto-icon-rotulo' => 'Routulo, New Label' ),

			array( 'porto-icon-cart-thick' => 'Shopping Cart Thick(bag)' ),
			array( 'porto-icon-check-circle' => 'Check, Circle' ),
			array( 'porto-icon-envelope' => 'Envelope, E-Mail' ),
			array( 'porto-icon-business-book' => 'Book' ),
			array( 'porto-icon-long-arrow-right' => 'Long Arrow Right' ),
			array( 'porto-icon-percent-shape' => 'Percent, Finance' ),
			array( 'porto-icon-sale-label' => 'Sale Label' ),
			array( 'porto-icon-help-circle' => 'Help, Circle, Question' ),
			array( 'porto-icon-sale-discount' => 'Sale, Discount' ),
			array( 'porto-icon-shipping-truck' => 'Shipping Truck' ),
			array( 'porto-icon-user-3' => 'User (Person, Man)' ),
			array( 'porto-icon-long-arrow-alt' => 'Long Arrow Right Alt' ),
			array( 'porto-icon-map-location' => 'Map Location (pin)' ),
			array( 'porto-icon-phone-call' => 'Calling Phone' ),
			array( 'porto-icon-tablet' => 'Tablet' ),
			array( 'porto-icon-callin' => 'Phone, Call in' ),
			array( 'porto-icon-atmark' => 'Email, Address, At' ),
			array( 'porto-icon-paypal' => 'Paypal, Payment' ),
			array( 'porto-icon-verisign' => 'Verisign, Payment' ),
			array( 'porto-icon-visa' => 'Visa, Payment' ),
			array( 'porto-icon-rotate' => 'Rotate, 360' ),
			array( 'porto-icon-package' => 'Package, Box' ),
			array( 'porto-icon-help-2' => 'Help, Circle, Question' ),
		);

		return array_merge( $icons, $porto_icons );
	}
}

/* fontawesome 5 */
add_action( 'init', 'porto_vc_update_font_awesomeicons', 12 );
function porto_vc_update_font_awesomeicons() {
	remove_filter( 'vc_iconpicker-type-fontawesome', 'vc_iconpicker_type_fontawesome' );
	add_filter( 'vc_iconpicker-type-fontawesome', 'porto_vc_iconpicker_type_fontawesome5' );
}

if ( ! function_exists( 'porto_vc_iconpicker_type_fontawesome5' ) ) {
	function porto_vc_iconpicker_type_fontawesome5( $icons ) {
		global $porto_settings_optimize;
		if ( isset( $porto_settings_optimize['optimize_fontawesome'] ) && $porto_settings_optimize['optimize_fontawesome'] ) {
			$fontawesome_icons = array(
				'Solid'   => array(
					array( 'fas fa-search' => 'Search(magnify, zoom, enlarge, bigger)' ),
					array( 'fas fa-heart' => 'Heart(love, like, favorite)' ),
					array( 'fas fa-star' => 'Star(award, achievement, night, rating, score, favorite)' ),
					array( 'fas fa-user' => 'User(person, man, head, profile)' ),
					array( 'fas fa-users' => 'Users(people, profiles, persons)(group)' ),
					array( 'fas fa-film' => 'Film(movie)' ),
					array( 'fas fa-th' => 'th(blocks, squares, boxes, grid)' ),
					array( 'fas fa-check' => 'Check(checkmark, done, todo, agree, accept, confirm, tick, ok)' ),
					array( 'fas fa-times' => 'Times(close, exit, x, cross)(remove, close)' ),
					array( 'fas fa-search-plus' => 'Search Plus(magnify, zoom, enlarge, bigger)' ),
					array( 'fas fa-cog' => 'cog(settings)(gear)' ),
					array( 'fas fa-cogs' => 'cogs(settings)(gears)' ),
					array( 'fas fa-tag' => 'tag(label)' ),
					array( 'fas fa-tags' => 'tags(labels)' ),
					array( 'fas fa-book' => 'Book(read, documentation)' ),
					array( 'fas fa-adjust' => 'adjust(contrast)' ),
					array( 'fas fa-play' => 'play(start, playing, music, sound)' ),
					array( 'fas fa-chevron-down' => 'chevron-down' ),
					array( 'fas fa-chevron-left' => 'chevron-left(bracket, previous, back)' ),
					array( 'fas fa-chevron-right' => 'chevron-right(bracket, next, forward)' ),
					array( 'fas fa-chevron-up' => 'chevron-up' ),
					array( 'fas fa-check-circle' => 'Check Circle(todo, done, agree, accept, confirm, ok)' ),
					array( 'fas fa-info-circle' => 'Info Circle(help, information, more, details)' ),
					array( 'fas fa-arrow-left' => 'arrow-left(previous, back)' ),
					array( 'fas fa-arrow-right' => 'arrow-right(next, forward)' ),
					array( 'fas fa-share' => 'Share(mail-forward)' ),
					array( 'fas fa-plus' => 'plus(add, new, create, expand)' ),
					array( 'fas fa-exclamation-circle' => 'Exclamation Circle(warning, error, problem, notification, alert)' ),
					array( 'fas fa-exclamation-triangle' => 'Exclamation Triangle(warning, error, problem, notification, alert)(warning)' ),
					array( 'fas fa-comment' => 'Comment(speech, notification, note, chat, bubble, feedback, message, texting, sms, conversation)' ),
					array( 'fas fa-comments' => 'Comments(speech, notification, note, chat, bubble, feedback, message, texting, sms, conversation)' ),
					array( 'fas fa-shopping-cart' => 'shopping-cart(checkout, buy, purchase, payment)' ),
					array( 'fas fa-folder-open' => 'Folder Open' ),
					array( 'fas fa-trophy' => 'trophy(award, achievement, cup, winner, game)' ),
					array( 'fas fa-phone' => 'Phone(call, voice, number, support, earphone, telephone)' ),
					array( 'fas fa-rss' => 'rss(blog)(feed)' ),
					array( 'fas fa-globe' => 'Globe(world, planet, map, place, travel, earth, global, translate, all, language, localize, location, coordinates, country)' ),
					array( 'fas fa-link' => 'Link(chain)' ),
					array( 'fas fa-bars' => 'Bars(menu, drag, reorder, settings, list, ul, ol, checklist, todo, list, hamburger)(navicon, reorder)' ),
					array( 'fas fa-truck' => 'truck(shipping)' ),
					array( 'fas fa-caret-left' => 'Caret Left(previous, back, triangle left, arrow)' ),
					array( 'fas fa-caret-right' => 'Caret Right(next, forward, triangle right, arrow)' ),
					array( 'fas fa-envelope' => 'Envelope(email, e-mail, letter, support, mail, message, notification)' ),
					array( 'fas fa-bolt' => 'Lightning Bolt(lightning, weather)(flash)' ),
					array( 'fas fa-coffee' => 'Coffee(morning, mug, breakfast, tea, drink, cafe)' ),
					array( 'fas fa-angle-double-left' => 'Angle Double Left(laquo, quote, previous, back, arrows)' ),
					array( 'fas fa-angle-double-right' => 'Angle Double Right(raquo, quote, next, forward, arrows)' ),
					array( 'fas fa-angle-down' => 'angle-down(arrow)' ),
					array( 'fas fa-angle-left' => 'angle-left(previous, back, arrow)' ),
					array( 'fas fa-angle-right' => 'angle-right(next, forward, arrow)' ),
					array( 'fas fa-angle-up' => 'angle-up(arrow)' ),
					array( 'fas fa-desktop' => 'Desktop(monitor, screen, desktop, computer, demo, device)' ),
					array( 'fas fa-spinner' => 'Spinner(loading, progress)' ),
					array( 'fas fa-code' => 'Code(html, brackets)' ),
					array( 'fas fa-location-arrow' => 'location-arrow(map, coordinates, location, address, place, where)' ),
					array( 'fas fa-exclamation' => 'exclamation(warning, error, problem, notification, notify, alert)' ),
					array( 'fas fa-puzzle-piece' => 'Puzzle Piece(addon, add-on, section)' ),
					array( 'fas fa-rocket' => 'rocket(app)' ),
					array( 'fas fa-bullseye' => 'Bullseye(notification, dot-circle)' ),
					array( 'fas fa-dollar-sign' => 'US Dollar(dollar)' ),
					array( 'fas fa-file' => 'File(new, page, pdf, document)' ),
					array( 'fas fa-thumbs-up' => 'thumbs-up(like, favorite, approve, agree, hand)' ),
					array( 'fas fa-paper-plane' => 'Paper Plane(send)' ),
					array( 'fas fa-share-alt' => 'Share Alt' ),
					array( 'fas fa-paint-brush' => 'Paint Brush' ),
					array( 'fas fa-user-plus' => 'Add User(sign up, signup)' ),
					array( 'fas fa-sync-alt' => 'refresh alt(reload, sync, triangle arrow)' ),
					array( 'fas fa-pencil-alt' => 'Pencil Alt(write, edit, update)' ),
					array( 'fas fa-long-arrow-alt-left' => 'Long Arrow Left(previous, back)' ),
					array( 'fas fa-long-arrow-alt-right' => 'Long Arrow Right' ),
					array( 'fas fa-long-arrow-alt-down' => 'Long Arrow Down' ),
					array( 'fas fa-external-link-alt' => 'External Link(open, new)' ),
					array( 'fas fa-cloud-download-alt' => 'Cloud Download(import)' ),
					array( 'fas fa-map-marker-alt' => 'map-marker-alt(map, pin, location, coordinates, localize, address, travel, where, place)' ),
					array( 'fas fa-mobile-alt' => 'Mobile Phone Alt(cell phone, cellphone, text, call, iphone, number, telephone)(mobile-phone-alt)' ),
					array( 'fas fa-reply' => 'Reply(mail-reply)' ),
					array( 'fas fa-tablet-alt' => 'tablet alt(ipad, device)' ),
					array( 'fas fa-music' => 'Music(note, sound)' ),
					array( 'fas fa-quote-left' => 'quote-left' ),
					array( 'fas fa-camera-retro' => 'camera-retro(photo, picture, record)' ),
					array( 'fas fa-file-alt' => 'File Alt(new, page, pdf, document)' ),
					array( 'fas fa-times-circle' => 'Times Circle(close, exit, x)' ),
					array( 'fas fa-plus-square' => 'Plus Square(add, new, create, expand)' ),
					array( 'fas fa-sign-in-alt' => 'Sign In(enter, join, log in, login, sign up, sign in, signin, signup, arrow)' ),
					array( 'fas fa-check-square' => 'Check Square(checkmark, done, todo, agree, accept, confirm, ok)' ),
					array( 'fas fa-caret-up' => 'Caret Up(triangle up, arrow)' ),
					array( 'fas fa-minus' => 'minus(hide, minify, delete, remove, trash, hide, collapse)' ),
					array( 'fas fa-filter' => 'Filter(funnel, options)' ),
				),
				'Regular' => array(
					array( 'far fa-heart' => 'Heart Outlined(love, like, favorite)' ),
					array( 'far fa-star' => 'Star Outlined(award, achievement, night, rating, score, favorite)' ),
					array( 'far fa-user' => 'User Outlined(person, man, head, profile)' ),
					array( 'far fa-clock' => 'Clock Outlined(watch, timer, late, timestamp)' ),
					array( 'far fa-image' => 'Image Outlined(photo, picture)' ),
					array( 'far fa-edit' => 'Edit Outlined(write, edit, update)(pencil)' ),
					array( 'far fa-calendar-alt' => 'Calendar Alt Outlined(date, time, when, event)' ),
					array( 'far fa-folder' => 'Folder Outlined' ),
					array( 'far fa-folder-open' => 'Folder Open Outlined' ),
					array( 'far fa-chart-bar' => 'Bar Chart Outlined(graph, analytics, statistics)' ),
					array( 'far fa-comments' => 'Comments Outlined(speech, notification, note, chat, bubble, feedback, message, texting, sms, conversation)' ),
					array( 'far fa-envelope' => 'Envelope Outlined(email, e-mail, letter, support, mail, message, notification)' ),
					array( 'far fa-bell' => 'bell outlined(alert, reminder, notification)' ),
					array( 'far fa-circle' => 'Circle Outlined' ),
					array( 'far fa-calendar' => 'Calendar Outlined(date, time, when, event)' ),
					array( 'far fa-file' => 'File Outlined(new, page, pdf, document)' ),
					array( 'far fa-thumbs-up' => 'thumbs-up outlined(like, favorite, approve, agree, hand)' ),
					array( 'far fa-dot-circle' => 'Dot Circle Outlined(target, bullseye, notification)' ),
					array( 'far fa-life-ring' => 'Life Ring Outlined(life-bouy, life-buoy, life-saver, support)' ),
					array( 'far fa-object-group' => 'Object Group Outlined' ),
					array( 'far fa-comment' => 'Comment Outlined(speech, notification, note, chat, bubble, feedback, message, texting, sms, conversation)' ),
				),
				'Brands'  => array(
					array( 'fab fa-twitter-square' => 'Twitter Square(tweet, social network)' ),
					array( 'fab fa-facebook-square' => 'Facebook Square(social network)' ),
					array( 'fab fa-linkedin' => 'LinkedIn' ),
					array( 'fab fa-twitter' => 'Twitter(tweet, social network)' ),
					array( 'fab fa-pinterest' => 'Pinterest' ),
					array( 'fab fa-google-plus-square' => 'Google Plus Square(social network)' ),
					array( 'fab fa-google-plus-g' => 'Google Plus G(social network)' ),
					array( 'fab fa-linkedin-in' => 'LinkedIn In' ),
					array( 'fab fa-youtube' => 'YouTube(video, film)' ),
					array( 'fab fa-xing' => 'Xing' ),
					array( 'fab fa-instagram' => 'Instagram' ),
					array( 'fab fa-flickr' => 'Flickr' ),
					array( 'fab fa-tumblr' => 'Tumblr' ),
					array( 'fab fa-skype' => 'Skype' ),
					array( 'fab fa-vk' => 'VK' ),
					array( 'fab fa-google' => 'Google Logo' ),
					array( 'fab fa-reddit' => 'reddit' ),
					array( 'fab fa-yelp' => 'Yelp' ),
					array( 'fab fa-whatsapp' => 'What\'s App' ),
					array( 'fab fa-vimeo-v' => 'Vimeo V' ),
					array( 'fab fa-facebook-f' => 'Facebook F(social network)' ),
					array( 'fab fa-telegram-plane' => 'Telegram Plane' ),
					array( 'fab fa-youtube-square' => 'YouTube Square(video, film)' ),
					array( 'fab fa-tripadvisor' => 'TripAdvisor' ),
				),
			);
		} else {
			$fontawesome_icons = array(
				'Accessibility'       => array(
					array( 'fas fa-braille' => 'Braille' ),
					array( 'fas fa-deaf' => 'Deaf(deafness, hard-of-hearing)' ),
					array( 'fas fa-low-vision' => 'Low Vision' ),
					array( 'fas fa-sign-language' => 'Sign Language' ),
					array( 'fas fa-universal-access' => 'Universal Access' ),
					array( 'fas fa-wheelchair-move' => 'WheelChair Move' ),
					array( 'fas fa-person-cane' => 'Person Cane' ),
				),
				'Animals'             => array(
					array( 'fas fa-cat' => 'Cat' ),
					array( 'fas fa-crow' => 'Crow' ),
					array( 'fas fa-dog' => 'Dog' ),
					array( 'fas fa-dove' => 'Dove(Pigeon)' ),
					array( 'fas fa-dragon' => 'Dragon' ),
					array( 'fas fa-feather' => 'Feather' ),
					array( 'fas fa-feather-alt' => 'Feather Alt' ),
					array( 'fas fa-fish' => 'Fish' ),
					array( 'fas fa-frog' => 'Frog' ),
					array( 'fas fa-hippo' => 'Hippo' ),
					array( 'fas fa-horse' => 'Horse' ),
					array( 'fas fa-horse-head' => 'Horse Head' ),
					array( 'fas fa-kiwi-bird' => 'Kiwi Bird' ),
					array( 'fas fa-otter' => 'Otter' ),
					array( 'fas fa-paw' => 'Paw' ),
					array( 'fas fa-spider' => 'Spinder' ),
					array( 'fas fa-bugs' => 'Bugs' ),
					array( 'fas fa-fish-fins' => 'Fish Fins' ),
					array( 'fas fa-locust' => 'Locust' ),
					array( 'fas fa-mosquito' => 'Mosquito' ),
					array( 'fas fa-shield-cat' => 'Shield Cat' ),
					array( 'fas fa-shield-dog' => 'Shield Dog' ),
					array( 'fas fa-shrimp' => 'Shrimp' ),
					array( 'fas fa-worm' => 'Worm' ),
				),
				'Arrows'              => array(
					array( 'fas fa-angle-double-down' => 'Angle Double Down(arrows)' ),
					array( 'fas fa-angle-double-left' => 'Angle Double Left(laquo, quote, previous, back, arrows)' ),
					array( 'fas fa-angle-double-right' => 'Angle Double Right(raquo, quote, next, forward, arrows)' ),
					array( 'fas fa-angle-double-up' => 'Angle Double Up(arrows)' ),
					array( 'fas fa-angle-down' => 'angle-down(arrow)' ),
					array( 'fas fa-angle-left' => 'angle-left(previous, back, arrow)' ),
					array( 'fas fa-angle-right' => 'angle-right(next, forward, arrow)' ),
					array( 'fas fa-angle-up' => 'angle-up(arrow)' ),
					array( 'fas fa-arrow-alt-circle-down' => 'Arrow Circle Down(download)' ),
					array( 'far fa-arrow-alt-circle-down' => 'Arrow Circle Down Outlined(download)' ),
					array( 'fas fa-arrow-alt-circle-left' => 'Arrow Circle Left(previous, back)' ),
					array( 'far fa-arrow-alt-circle-left' => 'Arrow Circle Left Outlined(previous, back)' ),
					array( 'fas fa-arrow-alt-circle-right' => 'Arrow Circle Right(next, forward)' ),
					array( 'far fa-arrow-alt-circle-right' => 'Arrow Circle Right Outlined(next, forward)' ),
					array( 'fas fa-arrow-alt-circle-up' => 'Arrow Circle Up' ),
					array( 'far fa-arrow-alt-circle-up' => 'Arrow Circle Up Outlined' ),
					array( 'fas fa-arrow-circle-down' => 'Arrow Circle Down(download)' ),
					array( 'fas fa-arrow-circle-left' => 'Arrow Circle Left(previous, back)' ),
					array( 'fas fa-arrow-circle-right' => 'Arrow Circle Right(next, forward)' ),
					array( 'fas fa-arrow-circle-up' => 'Arrow Circle Up' ),
					array( 'fas fa-arrow-down' => 'arrow-down(download)' ),
					array( 'fas fa-arrow-left' => 'arrow-left(previous, back)' ),
					array( 'fas fa-arrow-right' => 'arrow-right(next, forward)' ),
					array( 'fas fa-arrow-up' => 'arrow-up' ),
					array( 'fas fa-arrows-alt' => 'Arrows Alt(expand, enlarge, fullscreen, bigger, move, reorder, resize, arrow)' ),
					array( 'fas fa-arrows-alt-h' => 'Arrows Horizontal(resize)' ),
					array( 'fas fa-arrows-alt-v' => 'Arrows Vertical(resize)' ),
					array( 'fas fa-caret-down' => 'Caret Down(more, dropdown, menu, triangle down, arrow)' ),
					array( 'fas fa-caret-left' => 'Caret Left(previous, back, triangle left, arrow)' ),
					array( 'fas fa-caret-right' => 'Caret Right(next, forward, triangle right, arrow)' ),
					array( 'fas fa-caret-up' => 'Caret Up(triangle up, arrow)' ),
					array( 'fas fa-caret-square-down' => 'Caret Square Down(more, dropdown, menu)(toggle-down)' ),
					array( 'far fa-caret-square-down' => 'Caret Square Outlined Down(more, dropdown, menu)(toggle-down)' ),
					array( 'fas fa-caret-square-left' => 'Caret Square Left(previous, back)(toggle-left)' ),
					array( 'far fa-caret-square-left' => 'Caret Square Outlined Left(previous, back)(toggle-left)' ),
					array( 'fas fa-caret-square-right' => 'Caret Square Right(next, forward)(toggle-right)' ),
					array( 'far fa-caret-square-right' => 'Caret Square Outlined Right(next, forward)(toggle-right)' ),
					array( 'fas fa-caret-square-up' => 'Caret Square Outlined Up(toggle-up)' ),
					array( 'far fa-caret-square-up' => 'Caret Square Up(toggle-up)' ),
					array( 'fas fa-cart-arrow-down' => 'Shopping Cart Arrow Down(shopping)' ),
					array( 'fas fa-chart-line' => 'Line Chart(graph, analytics, statistics)' ),
					array( 'fas fa-chevron-circle-down' => 'Chevron Circle Down(more, dropdown, menu, arrow)' ),
					array( 'fas fa-chevron-circle-left' => 'Chevron Circle Left(previous, back, arrow)' ),
					array( 'fas fa-chevron-circle-right' => 'Chevron Circle Right(next, forward, arrow)' ),
					array( 'fas fa-chevron-circle-up' => 'Chevron Circle Up(arrow)' ),
					array( 'fas fa-chevron-down' => 'chevron-down' ),
					array( 'fas fa-chevron-left' => 'chevron-left(bracket, previous, back)' ),
					array( 'fas fa-chevron-right' => 'chevron-right(bracket, next, forward)' ),
					array( 'fas fa-chevron-up' => 'chevron-up' ),
					array( 'fas fa-cloud-download-alt' => 'Cloud Download(import)' ),
					array( 'fas fa-cloud-upload-alt' => 'Cloud Upload(import)' ),
					array( 'fas fa-download' => 'Download(import)' ),
					array( 'fas fa-exchange-alt' => 'Exchange(transfer, arrows, arrow)' ),
					array( 'fas fa-expand-arrows-alt' => 'Expand Arrows(enlarge, bigger, resize)' ),
					array( 'fas fa-external-link-alt' => 'External Link(open, new)' ),
					array( 'fas fa-external-link-square-alt' => 'External Link Square(open, new)' ),
					array( 'fas fa-hand-point-down' => 'Hand Point Down(point, finger)' ),
					array( 'far fa-hand-point-down' => 'Hand Outlined Down(point, finger)' ),
					array( 'fas fa-hand-point-left' => 'Hand Point Left(point, left, previous, back, finger)' ),
					array( 'far fa-hand-point-left' => 'Hand Point Outlined Left(point, left, previous, back, finger)' ),
					array( 'fas fa-hand-point-right' => 'Hand Point Right(point, right, next, forward, finger)' ),
					array( 'far fa-hand-point-right' => 'Hand Point Outlined Right(point, right, next, forward, finger)' ),
					array( 'fas fa-hand-point-up' => 'Hand Point Up(point, finger)' ),
					array( 'far fa-hand-point-up' => 'Hand Outlined Up(point, finger)' ),
					array( 'fas fa-hand-pointer' => 'Hand Pointer(point, finger, up)' ),
					array( 'far fa-hand-pointer' => 'Hand Pointer Outlined(point, finger, up)' ),
					array( 'fas fa-history' => 'History' ),
					array( 'fas fa-level-down-alt' => 'Level Down(arrow)' ),
					array( 'fas fa-level-up-alt' => 'Level Up(arrow)' ),
					array( 'fas fa-location-arrow' => 'location-arrow(map, coordinates, location, address, place, where)' ),
					array( 'fas fa-long-arrow-alt-down' => 'Long Arrow Down' ),
					array( 'fas fa-long-arrow-alt-left' => 'Long Arrow Left(previous, back)' ),
					array( 'fas fa-long-arrow-alt-right' => 'Long Arrow Right' ),
					array( 'fas fa-long-arrow-alt-up' => 'Long Arrow Up' ),
					array( 'fas fa-mouse-pointer' => 'Mouse Pointer' ),
					array( 'fas fa-play' => 'play(start, playing, music, sound)' ),
					array( 'fas fa-random' => 'random(sort, shuffle)' ),
					array( 'fas fa-recycle' => 'Recycle' ),
					array( 'fas fa-redo' => 'Repeat(redo, forward)(rotate-right)' ),
					array( 'fas fa-redo-alt' => 'Repeat(redo, forward, triangle arrow)(rotate-right)' ),
					array( 'fas fa-reply' => 'Reply(mail-reply)' ),
					array( 'fas fa-reply-all' => 'reply-all(mail-reply-all)' ),
					array( 'fas fa-retweet' => 'retweet(refresh, reload, share)' ),
					array( 'fas fa-share' => 'Share(mail-forward)' ),
					array( 'fas fa-share-square' => 'Share Square(social, send)' ),
					array( 'far fa-share-square' => 'Share Square Outlined(social, send)' ),
					array( 'fas fa-sign-in-alt' => 'Sign In(enter, join, log in, login, sign up, sign in, signin, signup, arrow)' ),
					array( 'fas fa-sign-out-alt' => 'Sign Out(log out, logout, leave, exit, arrow)' ),
					array( 'fas fa-sort' => 'Sort(order)(unsorted)' ),
					array( 'fas fa-sort-alpha-down' => 'Sort Alpha Down' ),
					array( 'fas fa-sort-alpha-up' => 'Sort Alpha Up' ),
					array( 'fas fa-sort-amount-down' => 'Sort Amount Down' ),
					array( 'fas fa-sort-amount-up' => 'Sort Amount Up' ),
					array( 'fas fa-sort-down' => 'Sort Down(dropdown, more, menu, arrow)(sort-down)' ),
					array( 'fas fa-sort-up' => 'Sort Up(arrow)' ),
					array( 'fas fa-sort-numeric-down' => 'Sort Numeric Down' ),
					array( 'fas fa-sort-numeric-up' => 'Sort Numberic Up' ),
					array( 'fas fa-sync' => 'refresh(reload, sync)' ),
					array( 'fas fa-sync-alt' => 'refresh alt(reload, sync, triangle arrow)' ),
					array( 'fas fa-text-height' => 'text-height' ),
					array( 'fas fa-text-width' => 'text-width' ),
					array( 'fas fa-undo' => 'Undo(back)(rotate-left)' ),
					array( 'fas fa-undo-alt' => 'Undo Alt(back)(rotate-left, triangle-arrow)' ),
					array( 'fas fa-upload' => 'Upload(import)' ),
					array( 'fas fa-arrow-up-from-bracket' => 'Arrow up from bracket' ),
					array( 'fas fa-arrows-up-to-line' => 'Arrows up to Line' ),
					array( 'fas fa-arrows-turn-right' => 'Arrows turn right' ),
					array( 'fas fa-arrows-spin' => 'Arrows Spin' ),
					array( 'fas fa-arrow-trend-up' => 'Arrow trend up' ),
					array( 'fas fa-arrow-trend-down' => 'Arrow trend down' ),
				),
				'Audio & Videos'      => array(
					array( 'fas fa-audio-description' => 'Audio Description' ),
					array( 'fas fa-backward' => 'backward(rewind, previous)' ),
					array( 'fas fa-broadcast-tower' => 'Broadcast Tower' ),
					array( 'fas fa-circle' => 'Circle(dot, notification)' ),
					array( 'far fa-circle' => 'Circle Outlined' ),
					array( 'fas fa-closed-captioning' => 'Closed Captioning' ),
					array( 'far fa-closed-captioning' => 'Closed Captioning Outlined' ),
					array( 'fas fa-eject' => 'eject' ),
					array( 'fas fa-fast-backward' => 'fast-backward(rewind, previous, beginning, start, first)' ),
					array( 'fas fa-fast-forward' => 'fast-forward(next, end, last)' ),
					array( 'fas fa-forward' => 'forward(forward, next)' ),
					array( 'fas fa-pause' => 'pause(wait)' ),
					array( 'fas fa-pause-circle' => 'Pause Circle' ),
					array( 'fas fa-phone-volume' => 'Phone Volumne' ),
					array( 'fas fa-play' => 'play(start, playing, music, sound)' ),
					array( 'fas fa-play-circle' => 'Play Circle(start, playing)' ),
					array( 'far fa-play-circle' => 'Play Circle Outlined(start, playing)' ),
					array( 'fas fa-podcast' => 'Podcast' ),
					array( 'fas fa-random' => 'random(sort, shuffle)' ),
					array( 'fas fa-redo' => 'Repeat(redo, forward)(rotate-right)' ),
					array( 'fas fa-redo-alt' => 'Repeat(redo, forward, triangle arrow)(rotate-right)' ),
					array( 'fas fa-rss' => 'rss(blog)(feed)' ),
					array( 'fas fa-rss-square' => 'RSS Square(feed, blog)' ),
					array( 'fas fa-step-backward' => 'step-backward(rewind, previous, beginning, start, first)' ),
					array( 'fas fa-step-forward' => 'step-forward(next, end, last)' ),
					array( 'fas fa-stop' => 'stop(block, box, square)' ),
					array( 'fas fa-stop-circle' => 'Stop Circle' ),
					array( 'far fa-stop-circle' => 'Stop Circle Outlined' ),
					array( 'fas fa-sync' => 'refresh(reload, sync)' ),
					array( 'fas fa-sync-alt' => 'refresh alt(reload, sync, triangle arrow)' ),
					array( 'fas fa-undo' => 'Undo(back)(rotate-left)' ),
					array( 'fas fa-undo-alt' => 'Undo Alt(back)(rotate-left, triangle-arrow)' ),
					array( 'fas fa-video' => 'Video Camera(film, movie, record)' ),
					array( 'fas fa-volume-down' => 'volume-down(audio, lower, quieter, sound, music)' ),
					array( 'fas fa-volume-mute' => 'volume-mute(audio, mute, sound, music)' ),
					array( 'fas fa-volume-off' => 'volume-off(audio, mute, sound, music)' ),
					array( 'fas fa-volume-up' => 'volume-up(audio, higher, louder, sound, music)' ),
					array( 'fab fa-youtube' => 'YouTube(video, film)' ),
				),
				'Automative'          => array(
					array( 'fas fa-air-freshener' => 'Air Freshener' ),
					array( 'fas fa-ambulance' => 'ambulance(vehicle, support, help)' ),
					array( 'fas fa-bus' => 'Bus(vehicle)' ),
					array( 'fas fa-bus-alt' => 'Bus Alt(vehicle)' ),
					array( 'fas fa-car' => 'Car(vehicle)(automobile)' ),
					array( 'fas fa-car-alt' => 'Car Alt(vehicle)(automobile)' ),
					array( 'fas fa-car-battery' => 'Car Battery' ),
					array( 'fas fa-car-crash' => 'Car Crash' ),
					array( 'fas fa-car-side' => 'Car Side' ),
					array( 'fas fa-charging-station' => 'Charging Station' ),
					array( 'fas fa-gas-pump' => 'Gas Pump' ),
					array( 'fas fa-motorcycle' => 'Motorcycle(vehicle, bike)' ),
					array( 'fas fa-oil-can' => 'Oil Can' ),
					array( 'fas fa-shuttle-van' => 'Shuttle Van' ),
					array( 'fas fa-tachometer-alt' => 'Tachometer(speedometer, fast)(dashboard)' ),
					array( 'fas fa-taxi' => 'Taxi(vehicle)(cab)' ),
					array( 'fas fa-truck' => 'truck(shipping)' ),
					array( 'fas fa-truck-monster' => 'Truck Monster' ),
					array( 'fas fa-truck-pickup' => 'Truck Pickup' ),
					array( 'fas fa-truck-field-un' => 'Truck field UN' ),
					array( 'fas fa-truck-field' => 'Truck field' ),

				),
				'Autumn'              => array(
					array( 'fas fa-apple-alt' => 'Apple(osx, food)' ),
					array( 'fas fa-campground' => 'Campground' ),
					array( 'fas fa-cloud-sun' => 'Cloud Sun' ),
					array( 'fas fa-drumstick-bite' => 'Drumstick Bite' ),
					array( 'fas fa-football-ball' => 'Football ball' ),
					array( 'fas fa-hiking' => 'Hiking(main)' ),
					array( 'fas fa-mountain' => 'Mountain' ),
					array( 'fas fa-tractor' => 'Tractor(vehicle)' ),
					array( 'fas fa-tree' => 'Tree' ),
					array( 'fas fa-wind' => 'Wind' ),
					array( 'fas fa-wine-bottle' => 'Wine Bottle' ),
				),
				'Buildings'           => array(
					array( 'fas fa-building' => 'Building(work, business, apartment, office, company)' ),
					array( 'far fa-building' => 'Building Outlined(work, business, apartment, office, company)' ),
					array( 'fas fa-campground' => 'Campground' ),
					array( 'fas fa-church' => 'Church' ),
					array( 'fas fa-city' => 'City' ),
					array( 'fas fa-dungeon' => 'Dungeon' ),
					array( 'fas fa-gopuram' => 'Gopuram' ),
					array( 'fas fa-home' => 'Home(main, house)' ),
					array( 'fas fa-hospital' => 'hospital(building)' ),
					array( 'far fa-hospital' => 'hospital Outlined(building)' ),
					array( 'fas fa-hospital-alt' => 'hospital Alt(building)' ),
					array( 'fas fa-hotel' => 'Hotel' ),
					array( 'fas fa-house-damage' => 'House Damage' ),
					array( 'fas fa-igloo' => 'Igloo' ),
					array( 'fas fa-industry' => 'Industry(factory)' ),
					array( 'fas fa-landmark' => 'Landmark' ),
					array( 'fas fa-mosque' => 'Mosque' ),
					array( 'fas fa-place-of-worship' => 'Place of Worship' ),
					array( 'fas fa-school' => 'School' ),
					array( 'fas fa-synagogue' => 'Synagogue' ),
					array( 'fas fa-torii-gate' => 'Torii Gate' ),
					array( 'fas fa-university' => 'University(institution, bank)' ),
					array( 'fas fa-vihara' => 'Vihara' ),
					array( 'fas fa-warehouse' => 'Warehouse(main, house)' ),
				),
				'Business'            => array(
					array( 'fas fa-address-book' => 'Address Book' ),
					array( 'far fa-address-book' => 'Address Book Outlined' ),
					array( 'fas fa-address-card' => 'Address Card(vcard)' ),
					array( 'far fa-address-card' => 'Address Card Outlined(vcard)' ),
					array( 'fas fa-archive' => 'Archive(box, storage)' ),
					array( 'fas fa-balance-scale' => 'Balance Scale' ),
					array( 'fas fa-birthday-cake' => 'Birthday Cake' ),
					array( 'fas fa-book' => 'Book(read, documentation)' ),
					array( 'fas fa-briefcase' => 'Briefcase(work, business, office, luggage, bag)' ),
					array( 'fas fa-building' => 'Building(work, business, apartment, office, company)' ),
					array( 'far fa-building' => 'Building Outlined(work, business, apartment, office, company)' ),
					array( 'fas fa-bullhorn' => 'Bullhorn(announcement, share, broadcast, louder, megaphone)' ),
					array( 'fas fa-bullseye' => 'Bullseye(notification, dot-circle)' ),
					array( 'fas fa-business-time' => 'Business Time(suitcase, bag, clock)' ),
					array( 'fas fa-calculator' => 'Calculator' ),
					array( 'fas fa-calendar' => 'Calendar(date, time, when, event)' ),
					array( 'far fa-calendar' => 'Calendar Outlined(date, time, when, event)' ),
					array( 'fas fa-calendar-alt' => 'Calendar Alt(date, time, when, event)' ),
					array( 'far fa-calendar-alt' => 'Calendar Alt Outlined(date, time, when, event)' ),
					array( 'fas fa-certificate' => 'Certificate(badge, star)' ),
					array( 'fas fa-chart-area' => 'Area Chart(graph, analytics, statistics)' ),
					array( 'fas fa-chart-bar' => 'Bar Chart(graph, analytics, statistics)' ),
					array( 'far fa-chart-bar' => 'Bar Chart Outlined(graph, analytics, statistics)' ),
					array( 'fas fa-chart-line' => 'Line Chart(graph, analytics, statistics)' ),
					array( 'fas fa-chart-pie' => 'Pie Chart(graph, analytics, statistics)' ),
					array( 'fas fa-city' => 'City' ),
					array( 'fas fa-clipboard' => 'Clipboard' ),
					array( 'far fa-clipboard' => 'Clipboard Outlined' ),
					array( 'fas fa-coffee' => 'Coffee(morning, mug, breakfast, tea, drink, cafe)' ),
					array( 'fas fa-columns' => 'Columns(split, panes)' ),
					array( 'fas fa-compass' => 'Compass(safari, directory, menu, location)' ),
					array( 'far fa-compass' => 'Compass Outlined(safari, directory, menu, location)' ),
					array( 'fas fa-copy' => 'Copy' ),
					array( 'far fa-copy' => 'Copy Outlined' ),
					array( 'fas fa-copyright' => 'Copyright' ),
					array( 'far fa-copyright' => 'Copyright Outlined' ),
					array( 'fas fa-cut' => 'Scissors(cut)' ),
					array( 'fas fa-edit' => 'Edit(write, edit, update)(pencil)' ),
					array( 'far fa-edit' => 'Edit Outlined(write, edit, update)(pencil)' ),
					array( 'fas fa-envelope' => 'Envelope(email, e-mail, letter, support, mail, message, notification)' ),
					array( 'far fa-envelope' => 'Envelope Outlined(email, e-mail, letter, support, mail, message, notification)' ),
					array( 'fas fa-envelope-open' => 'Envelope Open(email, e-mail, letter, support, mail, message, notification)' ),
					array( 'far fa-envelope-open' => 'Envelope Open Outlined(email, e-mail, letter, support, mail, message, notification)' ),
					array( 'fas fa-envelope-square' => 'Envelope Square(email, e-mail, letter, support, mail, message, notification)' ),
					array( 'fas fa-eraser' => 'eraser(remove, delete)' ),
					array( 'fas fa-fax' => 'Fax' ),
					array( 'fas fa-file' => 'File(new, page, pdf, document)' ),
					array( 'far fa-file' => 'File Outlined(new, page, pdf, document)' ),
					array( 'fas fa-file-alt' => 'File Alt(new, page, pdf, document)' ),
					array( 'far fa-file-alt' => 'File Alt Outlined(new, page, pdf, document)' ),
					array( 'fas fa-folder' => 'Folder' ),
					array( 'far fa-folder' => 'Folder Outlined' ),
					array( 'fas fa-folder-minus' => 'Folder Minus' ),
					array( 'fas fa-folder-open' => 'Folder Open' ),
					array( 'far fa-folder-open' => 'Folder Open Outlined' ),
					array( 'fas fa-folder-plus' => 'Folder Plus' ),
					array( 'fas fa-glasses' => 'Glasses' ),
					array( 'fas fa-globe' => 'Globe(world, planet, map, place, travel, earth, global, translate, all, language, localize, location, coordinates, country)' ),
					array( 'fas fa-highlighter' => 'Highlighter(paint)' ),
					array( 'fas fa-industry' => 'Industry(factory)' ),
					array( 'fas fa-landmark' => 'Landmark' ),
					array( 'fas fa-marker' => 'Marker(pen)' ),
					array( 'fas fa-paperclip' => 'Paperclip(attachment)' ),
					array( 'fas fa-paste' => 'Paste' ),
					array( 'fas fa-pen' => 'Pen(write, edit, update)' ),
					array( 'fas fa-pen-alt' => 'Pen Alt(write, edit, update)' ),
					array( 'fas fa-pen-fancy' => 'Pen fancy(write, edit, update)' ),
					array( 'fas fa-pen-nib' => 'Pen nib(write, edit, update)' ),
					array( 'fas fa-pen-square' => 'Pen Square(write, edit, update)' ),
					array( 'fas fa-pencil-alt' => 'Pencil Alt(write, edit, update)' ),
					array( 'fas fa-percent' => 'Percent' ),
					array( 'fas fa-phone' => 'Phone(call, voice, number, support, earphone, telephone)' ),
					array( 'fas fa-phone-slash' => 'Phone Slash(call, voice, sound, mute)' ),
					array( 'fas fa-phone-square' => 'Phone Square(call, voice, number, support, telephone)' ),
					array( 'fas fa-phone-volume' => 'Phone Volumne' ),
					array( 'fas fa-print' => 'Print' ),
					array( 'fas fa-project-diagram' => 'Project Diagram' ),
					array( 'fas fa-registered' => 'Registered Trademark' ),
					array( 'far fa-registered' => 'Registered Trademark Outlined' ),
					array( 'fas fa-save' => 'Save' ),
					array( 'far fa-save' => 'Save Outlined' ),
					array( 'fas fa-sitemap' => 'Sitemap(directory, hierarchy, organization)' ),
					array( 'fas fa-socks' => 'Socks' ),
					array( 'fas fa-sticky-note' => 'Sticky Note' ),
					array( 'far fa-sticky-note' => 'Sticky Note Outlined' ),
					array( 'fas fa-stream' => 'Stream' ),
					array( 'fas fa-table' => 'table(data, excel, spreadsheet)' ),
					array( 'fas fa-tag' => 'tag(label)' ),
					array( 'fas fa-tags' => 'tags(labels)' ),
					array( 'fas fa-tasks' => 'Tasks(progress, loading, downloading, downloads, settings)' ),
					array( 'fas fa-thumbtack' => 'Thumbtack' ),
					array( 'fas fa-trademark' => 'Trademark' ),
					array( 'fas fa-wallet' => 'Wallet' ),
				),
				'Charity'             => array(
					array( 'fas fa-dollar-sign' => 'US Dollar(dollar)' ),
					array( 'fas fa-donate' => 'Donate' ),
					array( 'fas fa-dove' => 'Dove(pigeon)' ),
					array( 'fas fa-gift' => 'gift(present)' ),
					array( 'fas fa-globe' => 'Globe(world, planet, map, place, travel, earth, global, translate, all, language, localize, location, coordinates, country)' ),
					array( 'fas fa-hand-holding-heart' => 'Hand holding Heart' ),
					array( 'fas fa-hand-holding-usd' => 'Hand holding USD(dollar)' ),
					array( 'fas fa-hands-helping' => 'Hands Helping' ),
					array( 'fas fa-handshake' => 'Handshake' ),
					array( 'far fa-handshake' => 'Handshake Outlined' ),
					array( 'fas fa-heart' => 'Heart(love, like, favorite)' ),
					array( 'far fa-heart' => 'Heart Outlined(love, like, favorite)' ),
					array( 'fas fa-leaf' => 'leaf(eco, nature, plant)' ),
					array( 'fas fa-parachute-box' => 'Parachute Box' ),
					array( 'fas fa-piggy-bank' => 'Piggy Bank(box)' ),
					array( 'fas fa-ribbon' => 'Ribbon' ),
					array( 'fas fa-seedling' => 'Seedling(nature, plant)' ),
				),
				'Chat'                => array(
					array( 'fas fa-comment' => 'Comment(speech, notification, note, chat, bubble, feedback, message, texting, sms, conversation)' ),
					array( 'far fa-comment' => 'Comment Outlined(speech, notification, note, chat, bubble, feedback, message, texting, sms, conversation)' ),
					array( 'fas fa-comment-alt' => 'Comment Alt(speech, notification, note, chat, bubble, feedback, message, texting, sms, conversation)' ),
					array( 'far fa-comment-alt' => 'Comment Alt Outlined(speech, notification, note, chat, bubble, feedback, message, texting, sms, conversation)' ),
					array( 'fas fa-comment-dots' => 'Comment dots(speech, notification, note, chat, bubble, feedback, message, texting, sms, conversation, commenting)' ),
					array( 'far fa-comment-dots' => 'Comment dots Outlined(speech, notification, note, chat, bubble, feedback, message, texting, sms, conversation, commenting)' ),
					array( 'fas fa-comment-medical' => 'Comment medical(speech, notification, note, chat, bubble, feedback, message, texting, sms, conversation, plus)' ),
					array( 'fas fa-comment-slash' => 'Comment slash(speech, notification, note, chat, bubble, feedback, message, texting, sms, conversation, mute)' ),
					array( 'fas fa-comments' => 'Comments(speech, notification, note, chat, bubble, feedback, message, texting, sms, conversation)' ),
					array( 'far fa-comments' => 'Comments Outlined(speech, notification, note, chat, bubble, feedback, message, texting, sms, conversation)' ),
					array( 'fas fa-frown' => 'Frown(face, emoticon, sad, disapprove, rating)' ),
					array( 'far fa-frown' => 'Frown Outlined(face, emoticon, sad, disapprove, rating)' ),
					array( 'fas fa-meh' => 'Meh(face, emoticon, rating, neutral)' ),
					array( 'far fa-meh' => 'Meh Outlined(face, emoticon, rating, neutral)' ),
					array( 'fas fa-phone' => 'Phone(call, voice, number, support, earphone, telephone)' ),
					array( 'fas fa-phone-slash' => 'Phone Slash(call, voice, sound, mute)' ),
					array( 'fas fa-poo' => 'Poo' ),
					array( 'fas fa-quote-left' => 'quote-left' ),
					array( 'fas fa-quote-right' => 'quote-right' ),
					array( 'fas fa-smile' => 'Smile(face, emoticon, happy, approve, satisfied, rating)' ),
					array( 'far fa-smile' => 'Smile Outlined(face, emoticon, happy, approve, satisfied, rating)' ),
					array( 'fas fa-sms' => 'sms(chat, message)' ),
					array( 'fas fa-video' => 'Video(film, movie, record, mute)' ),
					array( 'fas fa-video-slash' => 'Video Slash(film, movie, record, mute)' ),
				),
				'Chess'               => array(
					array( 'fas fa-chess' => 'Chess' ),
					array( 'fas fa-chess-bishop' => 'Chess Bishop' ),
					array( 'fas fa-chess-board' => 'Chess Board' ),
					array( 'fas fa-chess-king' => 'Chess King' ),
					array( 'fas fa-chess-knight' => 'Chess Knight' ),
					array( 'fas fa-chess-pawn' => 'Chess Pawn' ),
					array( 'fas fa-chess-queen' => 'Chess Queen' ),
					array( 'fas fa-chess-rook' => 'Chess Rook' ),
					array( 'fas fa-square-full' => 'Square Full(block, box)' ),
				),
				'Code'                => array(
					array( 'fas fa-archive' => 'Archive(box, storage)' ),
					array( 'fas fa-barcode' => 'barcode(scan)' ),
					array( 'fas fa-bath' => 'Bath(bathtub, s15)' ),
					array( 'fas fa-bug' => 'Bug(report, insect)' ),
					array( 'fas fa-code' => 'Code(html, brackets)' ),
					array( 'fas fa-code-branch' => 'code-branch(git, fork, vcs, svn, github, rebase, version, merge)' ),
					array( 'fas fa-coffee' => 'Coffee(morning, mug, breakfast, tea, drink, cafe)' ),
					array( 'fas fa-file' => 'File(new, page, pdf, document)' ),
					array( 'far fa-file' => 'File Outlined(new, page, pdf, document)' ),
					array( 'fas fa-file-alt' => 'File Alt(new, page, pdf, document)' ),
					array( 'far fa-file-alt' => 'File Alt Outlined(new, page, pdf, document)' ),
					array( 'fas fa-file-code' => 'Code File' ),
					array( 'far fa-file-code' => 'Code File Outlined' ),
					array( 'fas fa-filter' => 'Filter(funnel, options)' ),
					array( 'fas fa-fire-extinguisher' => 'fire-extinguisher' ),
					array( 'fas fa-folder' => 'Folder' ),
					array( 'far fa-folder' => 'Folder Outlined' ),
					array( 'fas fa-folder-open' => 'Folder Open' ),
					array( 'far fa-folder-open' => 'Folder Open Outlined' ),
					array( 'fas fa-keyboard' => 'Keyboard(type, input)' ),
					array( 'far fa-keyboard' => 'Keyboard Outlined(type, input)' ),
					array( 'fas fa-microchip' => 'Microchip' ),
					array( 'fas fa-project-diagram' => 'Project Diagram' ),
					array( 'fas fa-qrcode' => 'qrcode(scan)' ),
					array( 'fas fa-shield-alt' => 'shield alt(award, achievement, security, winner)' ),
					array( 'fas fa-sitemap' => 'Sitemap(directory, hierarchy, organization)' ),
					array( 'fas fa-stream' => 'Stream(bars)' ),
					array( 'fas fa-terminal' => 'Terminal(command, prompt, code)' ),
					array( 'fas fa-user-secret' => 'User Secret(whisper, spy, incognito, privacy)' ),
					array( 'fas fa-window-close' => 'Window Close(times-rectangle)' ),
					array( 'far fa-window-close' => 'Window Close Outlined(times-rectangle)' ),
					array( 'fas fa-window-maximize' => 'Window Maximize' ),
					array( 'far fa-window-maximize' => 'Window Maximize Outlined' ),
					array( 'fas fa-window-minimize' => 'Window Minimize' ),
					array( 'far fa-window-minimize' => 'Window Minimize Outlined' ),
					array( 'fas fa-window-restore' => 'Window Restore' ),
					array( 'far fa-window-restore' => 'Window Restore Outlined' ),
				),
				'Communication'       => array(
					array( 'fas fa-address-book' => 'Address Book' ),
					array( 'far fa-address-book' => 'Address Book Outlined' ),
					array( 'fas fa-address-card' => 'Address Card(vcard)' ),
					array( 'far fa-address-card' => 'Address Card Outlined(vcard)' ),
					array( 'fas fa-american-sign-language-interpreting' => 'American Sign Language Interpreting(asl-interpreting)' ),
					array( 'fas fa-assistive-listening-systems' => 'Assistive Listening Systems' ),
					array( 'fas fa-at' => 'At(email, e-mail)' ),
					array( 'fas fa-bell' => 'bell(alert, reminder, notification)' ),
					array( 'far fa-bell' => 'bell outlined(alert, reminder, notification)' ),
					array( 'fas fa-bell-slash' => 'Bell Slash' ),
					array( 'far fa-bell-slash' => 'Bell Slash Outlined' ),
					array( 'fab fa-bluetooth' => 'Bluetooth' ),
					array( 'fab fa-bluetooth-b' => 'Bluetooth' ),
					array( 'fas fa-broadcast-tower' => 'Broadcast Tower' ),
					array( 'fas fa-bullhorn' => 'bullhorn(announcement, share, broadcast, louder, megaphone)' ),
					array( 'fas fa-chalkboard' => 'Chalkboard' ),
					array( 'fas fa-comment' => 'Comment(speech, notification, note, chat, bubble, feedback, message, texting, sms, conversation)' ),
					array( 'far fa-comment' => 'Comment Outlined(speech, notification, note, chat, bubble, feedback, message, texting, sms, conversation)' ),
					array( 'fas fa-comment-alt' => 'Comment Alt(speech, notification, note, chat, bubble, feedback, message, texting, sms, conversation)' ),
					array( 'far fa-comment-alt' => 'Comment Alt Outlined(speech, notification, note, chat, bubble, feedback, message, texting, sms, conversation)' ),
					array( 'fas fa-comments' => 'Comments(speech, notification, note, chat, bubble, feedback, message, texting, sms, conversation)' ),
					array( 'far fa-comments' => 'Comments Outlined(speech, notification, note, chat, bubble, feedback, message, texting, sms, conversation)' ),
					array( 'fas fa-envelope' => 'Envelope(email, e-mail, letter, support, mail, message, notification)' ),
					array( 'far fa-envelope' => 'Envelope Outlined(email, e-mail, letter, support, mail, message, notification)' ),
					array( 'fas fa-envelope-open' => 'Envelope Open(email, e-mail, letter, support, mail, message, notification)' ),
					array( 'far fa-envelope-open' => 'Envelope Open Outlined(email, e-mail, letter, support, mail, message, notification)' ),
					array( 'fas fa-envelope-square' => 'Envelope Square(email, e-mail, letter, support, mail, message, notification)' ),
					array( 'fas fa-fax' => 'Fax' ),
					array( 'fas fa-inbox' => 'inbox' ),
					array( 'fas fa-language' => 'Language(translate)' ),
					array( 'fas fa-microphone' => 'microphone(record, voice, sound)' ),
					array( 'fas fa-microphone-alt' => 'microphone alt(record, voice, sound)' ),
					array( 'fas fa-microphone-alt-slash' => 'Microphone Alt Slash(record, voice, sound, mute)' ),
					array( 'fas fa-microphone-slash' => 'Microphone Slash(record, voice, sound, mute)' ),
					array( 'fas fa-mobile' => 'Mobile Phone(cell phone, cellphone, text, call, iphone, number, telephone)(mobile-phone)' ),
					array( 'fas fa-mobile-alt' => 'Mobile Phone Alt(cell phone, cellphone, text, call, iphone, number, telephone)(mobile-phone-alt)' ),
					array( 'fas fa-paper-plane' => 'Paper Plane(send)' ),
					array( 'far fa-paper-plane' => 'Paper Plane Outlined(send)' ),
					array( 'fas fa-phone' => 'Phone(call, voice, number, support, earphone, telephone)' ),
					array( 'fas fa-phone-slash' => 'Phone Slash(call, voice, sound, mute)' ),
					array( 'fas fa-phone-square' => 'Phone Square(call, voice, number, support, telephone)' ),
					array( 'fas fa-phone-volume' => 'Phone Volumne' ),
					array( 'fas fa-rss' => 'rss(blog)(feed)' ),
					array( 'fas fa-rss-square' => 'RSS Square(feed, blog)' ),
					array( 'fas fa-tty' => 'TTY' ),
					array( 'fas fa-wifi' => 'Wifi' ),
				),
				'Computers'           => array(
					array( 'fas fa-database' => 'Database' ),
					array( 'fas fa-desktop' => 'Desktop(monitor, screen, desktop, computer, demo, device)' ),
					array( 'fas fa-download' => 'Download(import)' ),
					array( 'fas fa-ethernet' => 'Ethernet' ),
					array( 'fas fa-hdd' => 'HDD(harddrive, hard drive, storage, save)' ),
					array( 'far fa-hdd' => 'HDD Outlined(harddrive, hard drive, storage, save)' ),
					array( 'fas fa-headphones' => 'headphones(sound, listen, music, audio)' ),
					array( 'fas fa-keyboard' => 'Keyboard(type, input)' ),
					array( 'far fa-keyboard' => 'Keyboard Outlined(type, input)' ),
					array( 'fas fa-laptop' => 'Laptop(demo, computer, device)' ),
					array( 'fas fa-memory' => 'Memory' ),
					array( 'fas fa-microchip' => 'Microchip' ),
					array( 'fas fa-mobile' => 'Mobile Phone(cell phone, cellphone, text, call, iphone, number, telephone)(mobile-phone)' ),
					array( 'fas fa-mobile-alt' => 'Mobile Phone Alt(cell phone, cellphone, text, call, iphone, number, telephone)(mobile-phone)' ),
					array( 'fas fa-plug' => 'Plug(power, connect)' ),
					array( 'fas fa-power-off' => 'Power Off(on)' ),
					array( 'fas fa-print' => 'Print' ),
					array( 'fas fa-satellite' => 'Satellite' ),
					array( 'fas fa-satellite-dish' => 'Satellite Dish' ),
					array( 'fas fa-save' => 'Save' ),
					array( 'far fa-save' => 'Save Outlined' ),
					array( 'fas fa-sd-card' => 'SD Card' ),
					array( 'fas fa-server' => 'Server' ),
					array( 'fas fa-sim-card' => 'Sim Card' ),
					array( 'fas fa-stream' => 'Stream(bars)' ),
					array( 'fas fa-tablet' => 'tablet(ipad, device)' ),
					array( 'fas fa-tablet-alt' => 'tablet alt(ipad, device)' ),
					array( 'fas fa-tv' => 'Television(display, computer, monitor)(tv)' ),
					array( 'fas fa-upload' => 'Upload(import)' ),
				),
				'Currency'            => array(
					array( 'fab fa-bitcoin' => 'Bitcoin (BTC)(bitcoin)' ),
					array( 'fab fa-btc' => 'Bitcoin (BTC)(bitcoin)' ),
					array( 'fas fa-dollar-sign' => 'US Dollar(dollar)' ),
					array( 'fab fa-ethereum' => 'Ethereum' ),
					array( 'fas fa-euro-sign' => 'Euro (EUR)(euro)' ),
					array( 'fab fa-gg' => 'GG Currency' ),
					array( 'fab fa-gg-circle' => 'GG Currency Circle' ),
					array( 'fas fa-hryvnia' => 'Hryvnia' ),
					array( 'fas fa-lira-sign' => 'Turkish Lira (TRY)(turkish-lira)' ),
					array( 'fas fa-money-bill' => 'Money Bill' ),
					array( 'fas fa-money-bill-alt' => 'Money Bill Alt' ),
					array( 'far fa-money-bill-alt' => 'Money Bill Alt Outlined' ),
					array( 'fas fa-money-bill-wave' => 'Money Bill Wave' ),
					array( 'fas fa-money-bill-wave-alt' => 'Money Bill Wave Alt' ),
					array( 'fas fa-money-check' => 'Money Check(money, buy, debit, checkout, purchase, payment, credit card)' ),
					array( 'fas fa-money-check-alt' => 'Money Check Alt(money, buy, debit, checkout, purchase, payment, credit card)' ),
					array( 'fas fa-pound-sign' => 'Pound' ),
					array( 'fas fa-ruble-sign' => 'Russian Ruble (RUB)(ruble, rouble)' ),
					array( 'fas fa-rupee-sign' => 'Indian Rupee (INR)(rupee)' ),
					array( 'fas fa-shekel-sign' => 'Shekel (ILS)(shekel, sheqel)' ),
					array( 'fas fa-tenge' => 'Tenge' ),
					array( 'fas fa-won-sign' => 'Korean Won (KRW)(won)' ),
					array( 'fas fa-yen-sign' => 'Japanese Yen (JPY)(cny, rmb, yen)' ),
				),
				'Date & Time'         => array(
					array( 'fas fa-bell' => 'bell(alert, reminder, notification)' ),
					array( 'far fa-bell' => 'bell outlined(alert, reminder, notification)' ),
					array( 'fas fa-bell-slash' => 'Bell Slash' ),
					array( 'far fa-bell-slash' => 'Bell Slash Outlined' ),
					array( 'fas fa-calendar' => 'Calendar(date, time, when, event)' ),
					array( 'far fa-calendar' => 'Calendar Outlined(date, time, when, event)' ),
					array( 'fas fa-calendar-alt' => 'Calendar Alt(date, time, when, event)' ),
					array( 'far fa-calendar-alt' => 'Calendar Alt Outlined(date, time, when, event)' ),
					array( 'fas fa-calendar-check' => 'Calendar Check(ok)' ),
					array( 'far fa-calendar-check' => 'Calendar Check Outlined(ok)' ),
					array( 'fas fa-calendar-minus' => 'Calendar Minus' ),
					array( 'far fa-calendar-minus' => 'Calendar Minus Outlined' ),
					array( 'fas fa-calendar-plus' => 'Calendar Plus' ),
					array( 'far fa-calendar-plus' => 'Calendar Plus Outlined' ),
					array( 'fas fa-calendar-times' => 'Calendar Times' ),
					array( 'fas fa-clock' => 'Clock(watch, timer, late, timestamp)' ),
					array( 'far fa-clock' => 'Clock Outlined(watch, timer, late, timestamp)' ),
					array( 'fas fa-hourglass' => 'Hourglass' ),
					array( 'far fa-hourglass' => 'Hourglass Outlined' ),
					array( 'fas fa-hourglass-end' => 'Hourglass End(hourglass-3)' ),
					array( 'fas fa-hourglass-half' => 'Hourglass Half(hourglass-2)' ),
					array( 'fas fa-hourglass-start' => 'Hourglass Start(hourglass-1)' ),
					array( 'fas fa-stopwatch' => 'Stopwatch' ),
				),
				'Design'              => array(
					array( 'fas fa-adjust' => 'adjust(contrast)' ),
					array( 'fas fa-bezier-curve' => 'Bezier Curve' ),
					array( 'fas fa-brush' => 'Brush' ),
					array( 'fas fa-clone' => 'Clone' ),
					array( 'far fa-clone' => 'Clone Outlined' ),
					array( 'fas fa-copy' => 'Copy' ),
					array( 'far fa-copy' => 'Copy Outlined' ),
					array( 'fas fa-crop' => 'Crop' ),
					array( 'fas fa-crop-alt' => 'Crop Alt' ),
					array( 'fas fa-crosshairs' => 'Crosshairs(picker)' ),
					array( 'fas fa-cut' => 'Scissors(cut)' ),
					array( 'fas fa-drafting-compass' => 'Drafting Compass' ),
					array( 'fas fa-draw-polygon' => 'Draw Polygon' ),
					array( 'fas fa-edit' => 'Edit(write, edit, update)(pencil)' ),
					array( 'far fa-edit' => 'Edit Outlined(write, edit, update)(pencil)' ),
					array( 'fas fa-eraser' => 'eraser(remove, delete)' ),
					array( 'fas fa-eye' => 'Eye(show, visible, views)' ),
					array( 'far fa-eye' => 'Eye Outlined(show, visible, views)' ),
					array( 'fas fa-eye-dropper' => 'Eyedropper' ),
					array( 'fas fa-eye-slash' => 'Eye Slash(toggle, show, hide, visible, visiblity, views)' ),
					array( 'far fa-eye-slash' => 'Eye Slash Outlined(toggle, show, hide, visible, visiblity, views)' ),
					array( 'fas fa-fill' => 'Fill' ),
					array( 'fas fa-fill-drip' => 'Fill Drip' ),
					array( 'fas fa-highlighter' => 'Highlighter(paint)' ),
					array( 'fas fa-layer-group' => 'Layer Group' ),
					array( 'fas fa-magic' => 'magic(wizard, automatic, autocomplete)' ),
					array( 'fas fa-marker' => 'Marker(pen)' ),
					array( 'fas fa-object-group' => 'Object Group' ),
					array( 'far fa-object-group' => 'Object Group Outlined' ),
					array( 'fas fa-object-ungroup' => 'Object Ungroup' ),
					array( 'far fa-object-ungroup' => 'Object Ungroup Outlined' ),
					array( 'fas fa-paint-brush' => 'Paint Brush' ),
					array( 'fas fa-paint-roller' => 'Paint Roller' ),
					array( 'fas fa-palette' => 'Palette' ),
					array( 'fas fa-paste' => 'Paste' ),
					array( 'fas fa-pen' => 'Pen(write, edit, update)' ),
					array( 'fas fa-pen-alt' => 'Pen Alt(write, edit, update)' ),
					array( 'fas fa-pen-fancy' => 'Pen fancy(write, edit, update)' ),
					array( 'fas fa-pen-nib' => 'Pen nib(write, edit, update)' ),
					array( 'fas fa-pencil-alt' => 'Pencil Alt(write, edit, update)' ),
					array( 'fas fa-pencil-ruler' => 'Pencil Ruler' ),
					array( 'fas fa-ruler-combined' => 'Ruler Combined' ),
					array( 'fas fa-ruler-horizontal' => 'Ruler Horizontal' ),
					array( 'fas fa-ruler-vertical' => 'Ruler Vertical' ),
					array( 'fas fa-save' => 'Save' ),
					array( 'far fa-save' => 'Save Outlined' ),
					array( 'fas fa-splotch' => 'Splotch' ),
					array( 'fas fa-spray-can' => 'Spray Can' ),
					array( 'fas fa-stamp' => 'Stamp' ),
					array( 'fas fa-swatchbook' => 'Swatchbook' ),
					array( 'fas fa-tint' => 'tint(raindrop, waterdrop, drop, droplet)' ),
					array( 'fas fa-tint-slash' => 'tint slash(raindrop, waterdrop, drop, droplet, mute)' ),
					array( 'fas fa-vector-square' => 'Vector Square' ),
				),
				'Editors'             => array(
					array( 'fas fa-align-center' => 'align-center(middle, text)' ),
					array( 'fas fa-align-justify' => 'align-justify(text)' ),
					array( 'fas fa-align-left' => 'align-left(text)' ),
					array( 'fas fa-align-right' => 'align-right(text)' ),
					array( 'fas fa-bold' => 'bold' ),
					array( 'fas fa-clipboard' => 'Clipboard' ),
					array( 'far fa-clipboard' => 'Clipboard Outlined' ),
					array( 'fas fa-clone' => 'Clone' ),
					array( 'far fa-clone' => 'Clone Outlined' ),
					array( 'fas fa-columns' => 'Columns(split, panes)' ),
					array( 'fas fa-copy' => 'Copy' ),
					array( 'far fa-copy' => 'Copy Outlined' ),
					array( 'fas fa-cut' => 'Scissors(cut)' ),
					array( 'fas fa-edit' => 'Edit(write, edit, update)(pencil)' ),
					array( 'far fa-edit' => 'Edit Outlined(write, edit, update)(pencil)' ),
					array( 'fas fa-eraser' => 'eraser(remove, delete)' ),
					array( 'fas fa-file' => 'File(new, page, pdf, document)' ),
					array( 'far fa-file' => 'File Outlined(new, page, pdf, document)' ),
					array( 'fas fa-file-alt' => 'File Alt(new, page, pdf, document)' ),
					array( 'far fa-file-alt' => 'File Alt Outlined(new, page, pdf, document)' ),
					array( 'fas fa-font' => 'font(text)' ),
					array( 'fas fa-glasses' => 'Glasses' ),
					array( 'fas fa-heading' => 'Heading(header)' ),
					array( 'fas fa-highlighter' => 'Highlighter(paint)' ),
					array( 'fas fa-i-cursor' => 'I Beam Cursor' ),
					array( 'fas fa-indent' => 'Indent' ),
					array( 'fas fa-italic' => 'italic(italics)' ),
					array( 'fas fa-link' => 'Link(chain)' ),
					array( 'fas fa-list' => 'list(ul, ol, checklist, finished, completed, done, todo)' ),
					array( 'fas fa-list-alt' => 'list-alt(ul, ol, checklist, finished, completed, done, todo)' ),
					array( 'far fa-list-alt' => 'list-alt Outlined(ul, ol, checklist, finished, completed, done, todo)' ),
					array( 'fas fa-list-ol' => 'list-ol(ul, ol, checklist, list, todo, list, numbers)' ),
					array( 'fas fa-list-ul' => 'list-ul(ul, ol, checklist, todo, list)' ),
					array( 'fas fa-marker' => 'Marker(pen)' ),
					array( 'fas fa-outdent' => 'Outdent(dedent)' ),
					array( 'fas fa-paper-plane' => 'Paper Plane(send)' ),
					array( 'far fa-paper-plane' => 'Paper Plane Outlined(send)' ),
					array( 'fas fa-paperclip' => 'Paperclip(attachment)' ),
					array( 'fas fa-paragraph' => 'paragraph' ),
					array( 'fas fa-pen' => 'Pen(write, edit, update)' ),
					array( 'fas fa-pen-alt' => 'Pen Alt(write, edit, update)' ),
					array( 'fas fa-pen-fancy' => 'Pen fancy(write, edit, update)' ),
					array( 'fas fa-pen-nib' => 'Pen nib(write, edit, update)' ),
					array( 'fas fa-pencil-alt' => 'Pencil Alt(write, edit, update)' ),
					array( 'fas fa-print' => 'Print' ),
					array( 'fas fa-quote-left' => 'quote-left' ),
					array( 'fas fa-quote-right' => 'quote-right' ),
					array( 'fas fa-redo' => 'Repeat(redo, forward)(rotate-right)' ),
					array( 'fas fa-redo-alt' => 'Repeat(redo, forward, triangle arrow)(rotate-right)' ),
					array( 'fas fa-reply' => 'Reply(mail-reply)' ),
					array( 'fas fa-reply-all' => 'reply-all(mail-reply-all)' ),
					array( 'fas fa-screwdriver' => 'Screwdriver' ),
					array( 'fas fa-share' => 'Share(mail-forward)' ),
					array( 'fas fa-strikethrough' => 'Strikethrough' ),
					array( 'fas fa-subscript' => 'subscript' ),
					array( 'fas fa-superscript' => 'superscript(exponential)' ),
					array( 'fas fa-sync' => 'refresh(reload, sync)' ),
					array( 'fas fa-sync-alt' => 'refresh alt(reload, sync, triangle arrow)' ),
					array( 'fas fa-table' => 'table(data, excel, spreadsheet)' ),
					array( 'fas fa-tasks' => 'Tasks(progress, loading, downloading, downloads, settings)' ),
					array( 'fas fa-text-height' => 'text-height' ),
					array( 'fas fa-text-width' => 'text-width' ),
					array( 'fas fa-th' => 'th(blocks, squares, boxes, grid)' ),
					array( 'fas fa-th-large' => 'th-large(blocks, squares, boxes, grid)' ),
					array( 'fas fa-th-list' => 'th-list(ul, ol, checklist, finished, completed, done, todo)' ),
					array( 'fas fa-tools' => 'Tools' ),
					array( 'fas fa-trash' => 'Trash' ),
					array( 'fas fa-trash-alt' => 'Trash Alt' ),
					array( 'far fa-trash-alt' => 'Trash Alt Outlined' ),
					array( 'fas fa-trash-restore' => 'Trash Restore' ),
					array( 'fas fa-trash-restore-alt' => 'Trash Restore Alt' ),
					array( 'fas fa-underline' => 'Underline' ),
					array( 'fas fa-undo' => 'Undo(back)(rotate-left)' ),
					array( 'fas fa-undo-alt' => 'Undo Alt(back)(rotate-left, triangle-arrow)' ),
					array( 'fas fa-unlink' => 'Unlink(remove)' ),
					array( 'fas fa-wrench' => 'Wrench(settings, fix, update, spanner)' ),
				),
				'Education'           => array(
					array( 'fas fa-apple-alt' => 'Apple(osx, food)' ),
					array( 'fas fa-atom' => 'Atom' ),
					array( 'fas fa-award' => 'Award' ),
					array( 'fas fa-bell' => 'bell(alert, reminder, notification)' ),
					array( 'far fa-bell' => 'bell outlined(alert, reminder, notification)' ),
					array( 'fas fa-bell-slash' => 'Bell Slash' ),
					array( 'far fa-bell-slash' => 'Bell Slash Outlined' ),
					array( 'fas fa-book-open' => 'Book Open' ),
					array( 'fas fa-book-reader' => 'Book Reader' ),
					array( 'fas fa-chalkboard' => 'Chalkboard' ),
					array( 'fas fa-chalkboard-teacher' => 'Chalkboard Teacher' ),
					array( 'fas fa-graduation-cap' => 'Graduation Cap(learning, school, student)(mortar-board)' ),
					array( 'fas fa-laptop-code' => 'Laptop Code' ),
					array( 'fas fa-microscope' => 'Microscope' ),
					array( 'fas fa-music' => 'Music(note, sound)' ),
					array( 'fas fa-school' => 'School(education, clock, teach)' ),
					array( 'fas fa-shapes' => 'Shapes(circle, triangle, rectangle)' ),
					array( 'fas fa-theater-masks' => 'Theater Masks' ),
					array( 'fas fa-user-graduate' => 'User Graduate' ),
				),
				'Emoji'               => array(
					array( 'fas fa-angry' => 'Angry' ),
					array( 'far fa-angry' => 'Angry Outlined' ),
					array( 'fas fa-dizzy' => 'Dizzy' ),
					array( 'far fa-dizzy' => 'Dizzy Outlined' ),
					array( 'fas fa-flushed' => 'Flushed' ),
					array( 'far fa-flushed' => 'Flushed Outlined' ),
					array( 'fas fa-frown' => 'Frown' ),
					array( 'far fa-frown' => 'Frown Outlined' ),
					array( 'fas fa-frown-open' => 'Frown Open' ),
					array( 'far fa-frown-open' => 'Frown Open Outlined' ),
					array( 'fas fa-grimace' => 'Grimace' ),
					array( 'far fa-grimace' => 'Grimace Outlined' ),
					array( 'fas fa-grin' => 'Grin' ),
					array( 'far fa-grin' => 'Grin Outlined' ),
					array( 'fas fa-grin-alt' => 'Grin Alt' ),
					array( 'far fa-grin-alt' => 'Grin Alt Outlined' ),
					array( 'fas fa-grin-beam' => 'Grin Beam' ),
					array( 'far fa-grin-beam' => 'Grin Beam Outlined' ),
					array( 'fas fa-grin-beam-sweat' => 'Grin Beam Sweat' ),
					array( 'far fa-grin-beam-sweat' => 'Grin Beam Sweat Outlined' ),
					array( 'fas fa-grin-hearts' => 'Grin Hearts' ),
					array( 'far fa-grin-hearts' => 'Grin Hearts Outlined' ),
					array( 'fas fa-grin-squint' => 'Grin Squint' ),
					array( 'far fa-grin-squint' => 'Grin Squint Outlined' ),
					array( 'fas fa-grin-squint-tears' => 'Grin Squint Tears' ),
					array( 'far fa-grin-squint-tears' => 'Grin Squint Tears Outlined' ),
					array( 'fas fa-grin-stars' => 'Grin Stars' ),
					array( 'far fa-grin-stars' => 'Grin Stars Outlined' ),
					array( 'fas fa-grin-tears' => 'Grin Tears' ),
					array( 'far fa-grin-tears' => 'Grin Tears Outlined' ),
					array( 'fas fa-grin-tongue' => 'Grin Tongue' ),
					array( 'far fa-grin-tongue' => 'Grin Tongue Outlined' ),
					array( 'fas fa-grin-tongue-squint' => 'Grin Tongue Squint' ),
					array( 'far fa-grin-tongue-squint' => 'Grin Tongue Squint Outlined' ),
					array( 'fas fa-grin-tongue-wink' => 'Grin Tongue Wink' ),
					array( 'far fa-grin-tongue-wink' => 'Grin Tongue Wink Outlined' ),
					array( 'fas fa-grin-wink' => 'Grin Wink' ),
					array( 'far fa-grin-wink' => 'Grin Wink Outlined' ),
					array( 'fas fa-kiss' => 'Kiss' ),
					array( 'far fa-kiss' => 'Kiss Outlined' ),
					array( 'fas fa-kiss-beam' => 'Kiss Beam' ),
					array( 'far fa-kiss-beam' => 'Kiss Beam Outlined' ),
					array( 'fas fa-kiss-wink-heart' => 'Kiss Wink Heart' ),
					array( 'far fa-kiss-wink-heart' => 'Kiss Wink Heart Outlined' ),
					array( 'fas fa-laugh' => 'Laugh' ),
					array( 'far fa-laugh' => 'Laugh Outlined' ),
					array( 'fas fa-laugh-beam' => 'Laugh Beam' ),
					array( 'far fa-laugh-beam' => 'Laugh Beam Outlined' ),
					array( 'fas fa-laugh-squint' => 'Laugh Squint' ),
					array( 'far fa-laugh-squint' => 'Laugh Squint Outlined' ),
					array( 'fas fa-laugh-wink' => 'Laugh Wink' ),
					array( 'far fa-laugh-wink' => 'Laugh Wink Outlined' ),
					array( 'fas fa-meh' => 'Meh' ),
					array( 'far fa-meh' => 'Meh Outlined' ),
					array( 'fas fa-meh-blank' => 'Meh Blank' ),
					array( 'far fa-meh-blank' => 'Meh Blank Outlined' ),
					array( 'fas fa-meh-rolling-eyes' => 'Meh Rolling Eyes' ),
					array( 'far fa-meh-rolling-eyes' => 'Meh Rolling Eyes Outlined' ),
					array( 'fas fa-sad-cry' => 'Sad Cry' ),
					array( 'far fa-sad-cry' => 'Sad Cry Outlined' ),
					array( 'fas fa-sad-tear' => 'Sad Tear' ),
					array( 'far fa-sad-tear' => 'Sad Tear Outlined' ),
					array( 'fas fa-smile' => 'Smile' ),
					array( 'far fa-smile' => 'Smile Outlined' ),
					array( 'fas fa-smile-beam' => 'Smile Bean' ),
					array( 'far fa-smile-beam' => 'Smile Beam Outlined' ),
					array( 'fas fa-smile-wink' => 'Smile Wink' ),
					array( 'far fa-smile-wink' => 'Smile Wink Outlined' ),
					array( 'fas fa-surprise' => 'Surprise' ),
					array( 'far fa-surprise' => 'Surprise Outlined' ),
					array( 'fas fa-tired' => 'Tired' ),
					array( 'far fa-tired' => 'Tired Outlined' ),
				),
				'Files'               => array(
					array( 'fas fa-archive' => 'Archive(box, storage)' ),
					array( 'fas fa-clone' => 'Clone' ),
					array( 'far fa-clone' => 'Clone Outlined' ),
					array( 'fas fa-copy' => 'Copy' ),
					array( 'far fa-copy' => 'Copy Outlined' ),
					array( 'fas fa-cut' => 'Scissors(cut)' ),
					array( 'fas fa-file' => 'File(new, page, pdf, document)' ),
					array( 'far fa-file' => 'File Outlined(new, page, pdf, document)' ),
					array( 'fas fa-file-alt' => 'File Alt(new, page, pdf, document)' ),
					array( 'far fa-file-alt' => 'File Alt Outlined(new, page, pdf, document)' ),
					array( 'fas fa-file-archive' => 'Archive File' ),
					array( 'far fa-file-archive' => 'Archive File Outlined' ),
					array( 'fas fa-file-audio' => 'Audio File' ),
					array( 'far fa-file-audio' => 'Audio File Outlined' ),
					array( 'fas fa-file-code' => 'Code File' ),
					array( 'far fa-file-code' => 'Code File Outlined' ),
					array( 'fas fa-file-excel' => 'Excel File' ),
					array( 'far fa-file-excel' => 'Excel File Outlined' ),
					array( 'fas fa-file-image' => 'Image File' ),
					array( 'far fa-file-image' => 'Image File Outlined' ),
					array( 'fas fa-file-pdf' => 'Pdf File' ),
					array( 'far fa-file-pdf' => 'Pdf File Outlined' ),
					array( 'fas fa-file-powerpoint' => 'Powerpoint File' ),
					array( 'far fa-file-powerpoint' => 'Powerpoint File Outlined' ),
					array( 'fas fa-file-video' => 'Video File' ),
					array( 'far fa-file-video' => 'Video File Outlined' ),
					array( 'fas fa-file-word' => 'Word File' ),
					array( 'far fa-file-word' => 'Word File Outlined' ),
					array( 'fas fa-folder' => 'Folder' ),
					array( 'far fa-folder' => 'Folder Outlined' ),
					array( 'fas fa-folder-open' => 'Folder Open' ),
					array( 'far fa-folder-open' => 'Folder Open Outlined' ),
					array( 'fas fa-paste' => 'Paste' ),
					array( 'fas fa-save' => 'Save' ),
					array( 'far fa-save' => 'Save Outlined' ),
					array( 'fas fa-sticky-note' => 'Sticky Note' ),
					array( 'far fa-sticky-note' => 'Sticky Note Outlined' ),
				),
				'Food'                => array(
					array( 'fas fa-apple-alt' => 'Apple(osx, food)' ),
					array( 'fas fa-bacon' => 'Bacon' ),
					array( 'fas fa-bone' => 'Bone' ),
					array( 'fas fa-bread-slice' => 'Breadslice' ),
					array( 'fas fa-candy-cane' => 'Candy Cane' ),
					array( 'fas fa-carrot' => 'Carrot' ),
					array( 'fas fa-cheese' => 'Cheese' ),
					array( 'fas fa-cloud-meatball' => 'Cloud Meatball' ),
					array( 'fas fa-cookie' => 'Cookie' ),
					array( 'fas fa-drumstick-bite' => 'Drumstick Bite' ),
					array( 'fas fa-egg' => 'Egg' ),
					array( 'fas fa-fish' => 'Fish' ),
					array( 'fas fa-hamburger' => 'Hamburger' ),
					array( 'fas fa-hotdog' => 'Hotdog' ),
					array( 'fas fa-ice-cream' => 'Ice cream' ),
					array( 'fas fa-lemon' => 'Lemon' ),
					array( 'far fa-lemon' => 'Lemon Outlined' ),
					array( 'fas fa-pepper-hot' => 'Pepper Hot' ),
					array( 'fas fa-pizza-slice' => 'Pizza Slice' ),
					array( 'fas fa-seedling' => 'Seedling' ),
					array( 'fas fa-stroopwafel' => 'Stroopwafel' ),
				),
				'Genders'             => array(
					array( 'fas fa-genderless' => 'Genderless' ),
					array( 'fas fa-mars' => 'Mars(male)' ),
					array( 'fas fa-mars-double' => 'Mars Double' ),
					array( 'fas fa-mars-stroke' => 'Mars Stroke' ),
					array( 'fas fa-mars-stroke-h' => 'Mars Stroke Horizontal' ),
					array( 'fas fa-mars-stroke-v' => 'Mars Stroke Vertical' ),
					array( 'fas fa-mercury' => 'Mercury(transgender)' ),
					array( 'fas fa-neuter' => 'Neuter' ),
					array( 'fas fa-transgender' => 'Transgender(intersex)' ),
					array( 'fas fa-transgender-alt' => 'Transgender Alt' ),
					array( 'fas fa-venus' => 'Venus(female)' ),
					array( 'fas fa-venus-double' => 'Mars Double' ),
					array( 'fas fa-venus-mars' => 'Mars(male)' ),
				),
				'Halloween'           => array(
					array( 'fas fa-book-dead' => 'Book Dead(skull-crossbones)' ),
					array( 'fas fa-broom' => 'Broom' ),
					array( 'fas fa-cat' => 'Cat' ),
					array( 'fas fa-cloud-moon' => 'Cloud Moon' ),
					array( 'fas fa-crow' => 'Crow' ),
					array( 'fas fa-ghost' => 'Ghost' ),
					array( 'fas fa-hat-wizard' => 'Hat Wizard' ),
					array( 'fas fa-mask' => 'Mask' ),
					array( 'fas fa-skull-crossbones' => 'Skull Crossbones' ),
					array( 'fas fa-spider' => 'Spider' ),
					array( 'fas fa-toilet-paper' => 'Toilet Paper' ),
				),
				'Hands'               => array(
					array( 'fas fa-allergies' => 'Allergies (Hand)' ),
					array( 'fas fa-fist-raised' => 'First Raised (Hand)' ),
					array( 'fas fa-hand-holding' => 'Hand Holding' ),
					array( 'fas fa-hand-holding-heart' => 'Hand holding Heart' ),
					array( 'fas fa-hand-holding-usd' => 'Hand holding USD(dollar)' ),
					array( 'fas fa-hand-lizard' => 'Lizard (Hand)' ),
					array( 'far fa-hand-lizard' => 'Lizard Outlined(Hand)' ),
					array( 'fas fa-hand-middle-finger' => 'Hand Middle Finger' ),
					array( 'fas fa-hand-paper' => 'Paper (Hand)(stop)' ),
					array( 'far fa-hand-paper' => 'Paper Outlined(Hand)(stop)' ),
					array( 'fas fa-hand-peace' => 'Hand Peace' ),
					array( 'far fa-hand-peace' => 'Hand Peace Outlined' ),
					array( 'fas fa-hand-point-down' => 'Hand Point Down(point, finger)' ),
					array( 'far fa-hand-point-down' => 'Hand Outlined Down(point, finger)' ),
					array( 'fas fa-hand-point-left' => 'Hand Point Left(point, left, previous, back, finger)' ),
					array( 'far fa-hand-point-left' => 'Hand Point Outlined Left(point, left, previous, back, finger)' ),
					array( 'fas fa-hand-point-right' => 'Hand Point Right(point, right, next, forward, finger)' ),
					array( 'far fa-hand-point-right' => 'Hand Point Outlined Right(point, right, next, forward, finger)' ),
					array( 'fas fa-hand-point-up' => 'Hand Point Up(point, finger)' ),
					array( 'far fa-hand-point-up' => 'Hand Outlined Up(point, finger)' ),
					array( 'fas fa-hand-pointer' => 'Hand Pointer(point, finger, up)' ),
					array( 'far fa-hand-pointer' => 'Hand Pointer Outlined(point, finger, up)' ),
					array( 'fas fa-hand-rock' => 'Rock (Hand)' ),
					array( 'far fa-hand-rock' => 'Rock Outlined(Hand)' ),
					array( 'fas fa-hand-scissors' => 'Scissors (Hand)' ),
					array( 'far fa-hand-scissors' => 'Scissors Outlined (Hand)' ),
					array( 'fas fa-hand-spock' => 'Spock (Hand)' ),
					array( 'far fa-hand-spock' => 'Spock Outlined (Hand)' ),
					array( 'fas fa-hands' => 'Hands' ),
					array( 'fas fa-hands-helping' => 'Hands Helping' ),
					array( 'fas fa-handshake' => 'Handshake' ),
					array( 'far fa-handshake' => 'Handshake Outlined' ),
					array( 'fas fa-praying-hands' => 'Praying hands' ),
					array( 'fas fa-thumbs-down' => 'thumbs-down(dislike, disapprove, disagree, hand)' ),
					array( 'far fa-thumbs-down' => 'thumbs-down outlined(dislike, disapprove, disagree, hand)' ),
					array( 'fas fa-thumbs-up' => 'thumbs-up(like, favorite, approve, agree, hand)' ),
					array( 'far fa-thumbs-up' => 'thumbs-up outlined(like, favorite, approve, agree, hand)' ),
				),
				'Health'              => array(
					array( 'fab fa-accessible-icon' => 'Accessible(handicap, person, going)' ),
					array( 'fas fa-ambulance' => 'ambulance(vehicle, support, help)' ),
					array( 'fas fa-h-square' => 'H Square(hospital, hotel)' ),
					array( 'fas fa-heart' => 'Heart(love, like, favorite)' ),
					array( 'far fa-heart' => 'Heart Outlined(love, like, favorite)' ),
					array( 'fas fa-heartbeat' => 'Heartbeat(ekg)' ),
					array( 'fas fa-hospital' => 'hospital(building)' ),
					array( 'far fa-hospital' => 'hospital Outlined(building)' ),
					array( 'fas fa-medkit' => 'medkit(first aid, firstaid, help, support, health)' ),
					array( 'fas fa-plus-square' => 'Plus Square(add, new, create, expand)' ),
					array( 'far fa-plus-square' => 'Plus Square Outlined(add, new, create, expand)' ),
					array( 'fas fa-prescription' => 'Prescription' ),
					array( 'fas fa-stethoscope' => 'Stethoscope' ),
					array( 'fas fa-user-md' => 'user-md(doctor, profile, medical, nurse)' ),
					array( 'fas fa-wheelchair' => 'Wheelchair(handicap, person)' ),
				),
				'Holiday'             => array(
					array( 'fas fa-candy-cane' => 'Candy Cane' ),
					array( 'fas fa-carrot' => 'Carrot' ),
					array( 'fas fa-cookie-bite' => 'Cookie Bite' ),
					array( 'fas fa-gift' => 'gift(present)' ),
					array( 'fas fa-glass-cheers' => 'Glass Cheers' ),
					array( 'fas fa-holly-berry' => 'Holiday Berry' ),
					array( 'fas fa-mug-hot' => 'Mug Hot' ),
					array( 'fas fa-sleigh' => 'Sleigh' ),
					array( 'fas fa-snowman' => 'Snowman' ),
				),
				'Images'              => array(
					array( 'fas fa-adjust' => 'adjust(contrast)' ),
					array( 'fas fa-bolt' => 'Lightning Bolt(lightning, weather)(flash)' ),
					array( 'fas fa-camera' => 'camera(photo, picture, record)' ),
					array( 'fas fa-camera-retro' => 'camera-retro(photo, picture, record)' ),
					array( 'fas fa-chalkboard' => 'Chalkboard' ),
					array( 'fas fa-clone' => 'Clone' ),
					array( 'far fa-clone' => 'Clone Outlined' ),
					array( 'fas fa-compress' => 'Compress(collapse, combine, contract, merge, smaller)' ),
					array( 'fas fa-compress-arrows-alt' => 'Compress Arrows(collapse, combine, contract, merge, smaller)' ),
					array( 'fas fa-expand' => 'Expand(enlarge, bigger, resize)' ),
					array( 'fas fa-eye' => 'Eye(show, visible, views)' ),
					array( 'far fa-eye' => 'Eye Outlined(show, visible, views)' ),
					array( 'fas fa-eye-dropper' => 'Eyedropper' ),
					array( 'fas fa-eye-slash' => 'Eye Slash(toggle, show, hide, visible, visiblity, views)' ),
					array( 'far fa-eye-slash' => 'Eye Slash Outlined(toggle, show, hide, visible, visiblity, views)' ),
					array( 'fas fa-file-image' => 'Image File' ),
					array( 'far fa-file-image' => 'Image File Outlined' ),
					array( 'fas fa-film' => 'Film(movie)' ),
					array( 'fas fa-id-badge' => 'Identification Badge' ),
					array( 'far fa-id-badge' => 'Identification Badge Outlined' ),
					array( 'fas fa-id-card' => 'Identification Card(drivers-license)' ),
					array( 'far fa-id-card' => 'Identification Card Outlined(drivers-license)' ),
					array( 'fas fa-image' => 'Image(photo, picture)' ),
					array( 'far fa-image' => 'Image Outlined(photo, picture)' ),
					array( 'fas fa-images' => 'Images(photos, pictures)' ),
					array( 'far fa-images' => 'Images Outlined(photos, pictures)' ),
					array( 'fas fa-portrait' => 'Portrait' ),
					array( 'fas fa-sliders-h' => 'Horizontal Sliders(settings)' ),
					array( 'fas fa-tint' => 'tint(raindrop, waterdrop, drop, droplet)' ),
				),
				'Interfaces'          => array(
					array( 'fas fa-award' => 'Award' ),
					array( 'fas fa-ban' => 'ban(delete, remove, trash, hide, block, stop, abort, cancel)' ),
					array( 'fas fa-barcode' => 'barcode(scan)' ),
					array( 'fas fa-bars' => 'Bars(menu, drag, reorder, settings, list, ul, ol, checklist, todo, list, hamburger)(navicon, reorder)' ),
					array( 'fas fa-beer' => 'beer(alcohol, stein, drink, mug, bar, liquor)' ),
					array( 'fas fa-bell' => 'bell(alert, reminder, notification)' ),
					array( 'far fa-bell' => 'bell outlined(alert, reminder, notification)' ),
					array( 'fas fa-bell-slash' => 'Bell Slash' ),
					array( 'far fa-bell-slash' => 'Bell Slash Outlined' ),
					array( 'fas fa-blog' => 'Blog' ),
					array( 'fas fa-bug' => 'Bug(report, insect)' ),
					array( 'fas fa-bullhorn' => 'Bullhorn(announcement, share, broadcast, louder, megaphone)' ),
					array( 'fas fa-bullseye' => 'Bullseye(notification, dot-circle)' ),
					array( 'fas fa-calculator' => 'Calculator' ),
					array( 'fas fa-calendar' => 'Calendar(date, time, when, event)' ),
					array( 'far fa-calendar' => 'Calendar Outlined(date, time, when, event)' ),
					array( 'fas fa-calendar-alt' => 'Calendar Alt(date, time, when, event)' ),
					array( 'far fa-calendar-alt' => 'Calendar Alt Outlined(date, time, when, event)' ),
					array( 'fas fa-calendar-check' => 'Calendar Check(ok)' ),
					array( 'far fa-calendar-check' => 'Calendar Check Outlined(ok)' ),
					array( 'fas fa-calendar-minus' => 'Calendar Minus' ),
					array( 'far fa-calendar-minus' => 'Calendar Minus Outlined' ),
					array( 'fas fa-calendar-plus' => 'Calendar Plus' ),
					array( 'far fa-calendar-plus' => 'Calendar Plus Outlined' ),
					array( 'fas fa-calendar-times' => 'Calendar Times' ),
					array( 'far fa-calendar-times' => 'Calendar Times Outlined' ),
					array( 'fas fa-certificate' => 'Certificate(badge, star)' ),
					array( 'fas fa-check' => 'Check(checkmark, done, todo, agree, accept, confirm, tick, ok)' ),
					array( 'fas fa-check-circle' => 'Check Circle(todo, done, agree, accept, confirm, ok)' ),
					array( 'far fa-check-circle' => 'Check Circle Outlined(todo, done, agree, accept, confirm, ok)' ),
					array( 'fas fa-check-double' => 'Check Double(todo, done, agree, accept, confirm, ok)' ),
					array( 'fas fa-check-square' => 'Check Square(checkmark, done, todo, agree, accept, confirm, ok)' ),
					array( 'far fa-check-square' => 'Check Square Outlined(todo, done, agree, accept, confirm, ok)' ),
					array( 'fas fa-circle' => 'Circle(dot, notification)' ),
					array( 'far fa-circle' => 'Circle Outlined' ),
					array( 'fas fa-clipboard' => 'Clipboard' ),
					array( 'far fa-clipboard' => 'Clipboard Outlined' ),
					array( 'fas fa-clone' => 'Clone' ),
					array( 'far fa-clone' => 'Clone Outlined' ),
					array( 'fas fa-cloud' => 'Cloud(save)' ),
					array( 'fas fa-cloud-download-alt' => 'Cloud Download(import)' ),
					array( 'fas fa-cloud-upload-alt' => 'Cloud Upload(import)' ),
					array( 'fas fa-coffee' => 'Coffee(morning, mug, breakfast, tea, drink, cafe)' ),
					array( 'fas fa-cog' => 'cog(settings)(gear)' ),
					array( 'fas fa-cogs' => 'cogs(settings)(gears)' ),
					array( 'fas fa-copy' => 'Copy' ),
					array( 'far fa-copy' => 'Copy Outlined' ),
					array( 'fas fa-cut' => 'Scissors(cut)' ),
					array( 'fas fa-database' => 'Database' ),
					array( 'fas fa-dot-circle' => 'Dot Circle(target, bullseye, notification)' ),
					array( 'far fa-dot-circle' => 'Dot Circle Outlined(target, bullseye, notification)' ),
					array( 'fas fa-download' => 'Download(import)' ),
					array( 'fas fa-edit' => 'Edit(write, edit, update)(pencil)' ),
					array( 'far fa-edit' => 'Edit Outlined(write, edit, update)(pencil)' ),
					array( 'fas fa-ellipsis-h' => 'Ellipsis Horizontal(dots)' ),
					array( 'fas fa-ellipsis-v' => 'Ellipsis Vertical(dots)' ),
					array( 'fas fa-envelope' => 'Envelope(email, e-mail, letter, support, mail, message, notification)' ),
					array( 'far fa-envelope' => 'Envelope Outlined(email, e-mail, letter, support, mail, message, notification)' ),
					array( 'fas fa-envelope-open' => 'Envelope Open(email, e-mail, letter, support, mail, message, notification)' ),
					array( 'far fa-envelope-open' => 'Envelope Open Outlined(email, e-mail, letter, support, mail, message, notification)' ),
					array( 'fas fa-eraser' => 'eraser(remove, delete)' ),
					array( 'fas fa-exclamation' => 'exclamation(warning, error, problem, notification, notify, alert)' ),
					array( 'fas fa-exclamation-circle' => 'Exclamation Circle(warning, error, problem, notification, alert)' ),
					array( 'fas fa-exclamation-triangle' => 'Exclamation Triangle(warning, error, problem, notification, alert)(warning)' ),
					array( 'fas fa-external-link-alt' => 'External Link(open, new)' ),
					array( 'fas fa-external-link-square-alt' => 'External Link Square(open, new)' ),
					array( 'fas fa-eye' => 'Eye(show, visible, views)' ),
					array( 'far fa-eye' => 'Eye Outlined(show, visible, views)' ),
					array( 'fas fa-eye-slash' => 'Eye Slash(toggle, show, hide, visible, visiblity, views)' ),
					array( 'far fa-eye-slash' => 'Eye Slash Outlined(toggle, show, hide, visible, visiblity, views)' ),
					array( 'fas fa-file' => 'File(new, page, pdf, document)' ),
					array( 'far fa-file' => 'File Outlined(new, page, pdf, document)' ),
					array( 'fas fa-file-alt' => 'File Alt(new, page, pdf, document)' ),
					array( 'far fa-file-alt' => 'File Alt Outlined(new, page, pdf, document)' ),
					array( 'fas fa-file-download' => 'File Download(import)' ),
					array( 'fas fa-file-export' => 'File Export' ),
					array( 'fas fa-file-import' => 'File Import' ),
					array( 'fas fa-file-upload' => 'File Upload' ),
					array( 'fas fa-filter' => 'Filter(funnel, options)' ),
					array( 'fas fa-fingerprint' => 'Fingerprint' ),
					array( 'fas fa-flag' => 'flag(report, notification, notify)' ),
					array( 'far fa-flag' => 'Flag Outlined(report, notification)' ),
					array( 'fas fa-flag-checkered' => 'flag-checkered(report, notification, notify)' ),
					array( 'fas fa-folder' => 'Folder' ),
					array( 'far fa-folder' => 'Folder Outlined' ),
					array( 'fas fa-folder-open' => 'Folder Open' ),
					array( 'far fa-folder-open' => 'Folder Open Outlined' ),
					array( 'fas fa-frown' => 'Frown(face, emoticon, sad, disapprove, rating)' ),
					array( 'far fa-frown' => 'Frown Outlined(face, emoticon, sad, disapprove, rating)' ),
					array( 'fas fa-glasses' => 'Glasses' ),
					array( 'fas fa-grip-horizontal' => 'Grip Horizontal' ),
					array( 'fas fa-grip-lines' => 'Grip Lines' ),
					array( 'fas fa-grip-lines-vertical' => 'Grip Lines Vertical' ),
					array( 'fas fa-grip-vertical' => 'Grip Vertical' ),
					array( 'fas fa-hashtag' => 'Hashtag' ),
					array( 'fas fa-heart' => 'Heart(love, like, favorite)' ),
					array( 'far fa-heart' => 'Heart Outlined(love, like, favorite)' ),
					array( 'fas fa-history' => 'History' ),
					array( 'fas fa-home' => 'Home(main, house)' ),
					array( 'fas fa-i-cursor' => 'I Beam Cursor' ),
					array( 'fas fa-info' => 'Info(help, information, more, details)' ),
					array( 'fas fa-info-circle' => 'Info Circle(help, information, more, details)' ),
					array( 'fas fa-language' => 'Language(translate)' ),
					array( 'fas fa-magic' => 'magic(wizard, automatic, autocomplete)' ),
					array( 'fas fa-marker' => 'Marker(pen)' ),
					array( 'fas fa-medal' => 'Medal' ),
					array( 'fas fa-meh' => 'Meh(face, emoticon, rating, neutral)' ),
					array( 'far fa-meh' => 'Meh Outlined(face, emoticon, rating, neutral)' ),
					array( 'fas fa-microphone' => 'microphone(record, voice, sound)' ),
					array( 'fas fa-microphone-alt' => 'microphone alt(record, voice, sound)' ),
					array( 'fas fa-microphone-slash' => 'Microphone Slash(record, voice, sound, mute)' ),
					array( 'fas fa-minus' => 'minus(hide, minify, delete, remove, trash, hide, collapse)' ),
					array( 'fas fa-minus-circle' => 'Minus Circle(delete, remove, trash, hide)' ),
					array( 'fas fa-minus-square' => 'Minus Square(hide, minify, delete, remove, trash, hide, collapse)' ),
					array( 'far fa-minus-square' => 'Minus Square Outlined(hide, minify, delete, remove, trash, hide, collapse)' ),
					array( 'fas fa-paste' => 'Paste' ),
					array( 'fas fa-pen' => 'Pen(write, edit, update)' ),
					array( 'fas fa-pen-alt' => 'Pen Alt(write, edit, update)' ),
					array( 'fas fa-pen-fancy' => 'Pen fancy(write, edit, update)' ),
					array( 'fas fa-pencil-alt' => 'Pencil Alt(write, edit, update)' ),
					array( 'fas fa-plus' => 'plus(add, new, create, expand)' ),
					array( 'fas fa-plus-circle' => 'Plus Circle(add, new, create, expand)' ),
					array( 'fas fa-plus-square' => 'Plus Square(add, new, create, expand)' ),
					array( 'far fa-plus-square' => 'Plus Square Outlined(add, new, create, expand)' ),
					array( 'fas fa-poo' => 'Poo' ),
					array( 'fas fa-qrcode' => 'qrcode(scan)' ),
					array( 'fas fa-question' => 'Question(help, information, unknown, support)' ),
					array( 'fas fa-question-circle' => 'Question Circle' ),
					array( 'far fa-question-circle' => 'Question Circle Outlined' ),
					array( 'fas fa-quote-left' => 'quote-left' ),
					array( 'fas fa-quote-right' => 'quote-right' ),
					array( 'fas fa-redo' => 'Repeat(redo, forward)(rotate-right)' ),
					array( 'fas fa-redo-alt' => 'Repeat(redo, forward, triangle arrow)(rotate-right)' ),
					array( 'fas fa-reply' => 'Reply(mail-reply)' ),
					array( 'fas fa-reply-all' => 'reply-all(mail-reply-all)' ),
					array( 'fas fa-rss' => 'rss(blog)(feed)' ),
					array( 'fas fa-rss-square' => 'RSS Square(feed, blog)' ),
					array( 'fas fa-save' => 'Save' ),
					array( 'far fa-save' => 'Save Outlined' ),
					array( 'fas fa-screwdriver' => 'Screwdriver' ),
					array( 'fas fa-search' => 'Search(magnify, zoom, enlarge, bigger)' ),
					array( 'fas fa-search-minus' => 'Search Minus(magnify, minify, zoom, smaller)' ),
					array( 'fas fa-search-plus' => 'Search Plus(magnify, zoom, enlarge, bigger)' ),
					array( 'fas fa-share' => 'Share(mail-forward)' ),
					array( 'fas fa-share-alt' => 'Share Alt' ),
					array( 'fas fa-share-alt-square' => 'Share Alt Square' ),
					array( 'fas fa-share-square' => 'Share Square(social, send)' ),
					array( 'far fa-share-square' => 'Share Square Outlined(social, send)' ),
					array( 'fas fa-shield-alt' => 'shield alt(award, achievement, security, winner)' ),
					array( 'fas fa-sign-in-alt' => 'Sign In(enter, join, log in, login, sign up, sign in, signin, signup, arrow)' ),
					array( 'fas fa-sign-out-alt' => 'Sign Out(log out, logout, leave, exit, arrow)' ),
					array( 'fas fa-signal' => 'signal(graph, bars)' ),
					array( 'fas fa-sitemap' => 'Sitemap(directory, hierarchy, organization)' ),
					array( 'fas fa-sliders-h' => 'Horizontal Sliders(settings)' ),
					array( 'fas fa-smile' => 'Smile(face, emoticon, happy, approve, satisfied, rating)' ),
					array( 'far fa-smile' => 'Smile Outlined(face, emoticon, happy, approve, satisfied, rating)' ),
					array( 'fas fa-sort' => 'Sort(order)(unsorted)' ),
					array( 'fas fa-sort-alpha-down' => 'Sort Alpha Down' ),
					array( 'fas fa-sort-alpha-up' => 'Sort Alpha Up' ),
					array( 'fas fa-sort-amount-down' => 'Sort Amount Down' ),
					array( 'fas fa-sort-amount-up' => 'Sort Amount Up' ),
					array( 'fas fa-sort-down' => 'Sort Down(dropdown, more, menu, arrow)(sort-down)' ),
					array( 'fas fa-sort-numeric-down' => 'Sort Numeric Down' ),
					array( 'fas fa-sort-numeric-up' => 'Sort Numberic Up' ),
					array( 'fas fa-sort-up' => 'Sort Up(arrow)' ),
					array( 'fas fa-star' => 'Star(award, achievement, night, rating, score, favorite)' ),
					array( 'far fa-star' => 'Star Outlined(award, achievement, night, rating, score, favorite)' ),
					array( 'fas fa-star-half' => 'star-half(award, achievement, rating, score)' ),
					array( 'far fa-star-half' => 'Star Half Outlined(award, achievement, rating, score)(star-half-empty, star-half-full)' ),
					array( 'fas fa-sync' => 'refresh(reload, sync)' ),
					array( 'fas fa-sync-alt' => 'refresh alt(reload, sync, triangle arrow)' ),
					array( 'fas fa-thumbs-down' => 'thumbs-down(dislike, disapprove, disagree, hand)' ),
					array( 'far fa-thumbs-down' => 'thumbs-down outlined(dislike, disapprove, disagree, hand)' ),
					array( 'fas fa-thumbs-up' => 'thumbs-up(like, favorite, approve, agree, hand)' ),
					array( 'far fa-thumbs-up' => 'thumbs-up outlined(like, favorite, approve, agree, hand)' ),
					array( 'fas fa-times' => 'Times(close, exit, x, cross)(remove, close)' ),
					array( 'fas fa-times-circle' => 'Times Circle(close, exit, x)' ),
					array( 'far fa-times-circle' => 'Times Circle Outlined(close, exit, x)' ),
					array( 'fas fa-toggle-off' => 'Toggle Off' ),
					array( 'fas fa-toggle-on' => 'Toggle On' ),
					array( 'fas fa-tools' => 'Tools' ),
					array( 'fas fa-trash' => 'Trash' ),
					array( 'fas fa-trash-alt' => 'Trash Alt' ),
					array( 'far fa-trash-alt' => 'Trash Alt Outlined' ),
					array( 'fas fa-trash-restore' => 'Trash Restore' ),
					array( 'fas fa-trash-restore-alt' => 'Trash Restore Alt' ),
					array( 'fas fa-trophy' => 'trophy(award, achievement, cup, winner, game)' ),
					array( 'fas fa-undo' => 'Undo(back)(rotate-left)' ),
					array( 'fas fa-undo-alt' => 'Undo Alt(back)(rotate-left, triangle-arrow)' ),
					array( 'fas fa-upload' => 'Upload(import)' ),
					array( 'fas fa-user' => 'User(person, man, head, profile)' ),
					array( 'fas fa-user-alt' => 'User Alt(person, man, head, profile)' ),
					array( 'fas fa-user-circle' => 'User Circle' ),
					array( 'far fa-user-circle' => 'User Circle Outlined' ),
					array( 'fas fa-volume-down' => 'volume-down(audio, lower, quieter, sound, music)' ),
					array( 'fas fa-volume-mute' => 'volume-mute(audio, mute, sound, music)' ),
					array( 'fas fa-volume-off' => 'volume-off(audio, mute, sound, music)' ),
					array( 'fas fa-volume-up' => 'volume-up(audio, higher, louder, sound, music)' ),
					array( 'fas fa-wifi' => 'Wifi' ),
					array( 'fas fa-wrench' => 'Wrench(settings, fix, update, spanner)' ),
				),
				'Logistics'           => array(
					array( 'fas fa-box' => 'Box' ),
					array( 'fas fa-boxes' => 'Boxes' ),
					array( 'fas fa-clipboard-check' => 'Clipboard Check' ),
					array( 'fas fa-clipboard-list' => 'Clipboard List' ),
					array( 'fas fa-dolly' => 'Dolly' ),
					array( 'fas fa-dolly-flatbed' => 'Dolly Flatbed' ),
					array( 'fas fa-hard-hat' => 'Hard Hat' ),
					array( 'fas fa-pallet' => 'Pallet' ),
					array( 'fas fa-shipping-fast' => 'Shipping fast(truck)' ),
					array( 'fas fa-truck' => 'truck(shipping)' ),
					array( 'fas fa-warehouse' => 'Warehouse(main, house)' ),
				),
				'Maps'                => array(
					array( 'fas fa-ambulance' => 'ambulance(vehicle, support, help)' ),
					array( 'fas fa-anchor' => 'Anchor(link)' ),
					array( 'fas fa-balance-scale' => 'Balance Scale' ),
					array( 'fas fa-bath' => 'Bath(bathtub, s15)' ),
					array( 'fas fa-bed' => 'Bed(travel)(hotel)' ),
					array( 'fas fa-beer' => 'beer(alcohol, stein, drink, mug, bar, liquor)' ),
					array( 'fas fa-bell' => 'bell(alert, reminder, notification)' ),
					array( 'far fa-bell' => 'bell outlined(alert, reminder, notification)' ),
					array( 'fas fa-bell-slash' => 'Bell Slash' ),
					array( 'far fa-bell-slash' => 'Bell Slash Outlined' ),
					array( 'fas fa-bicycle' => 'Bicycle(vehicle, bike)' ),
					array( 'fas fa-binoculars' => 'Binoculars' ),
					array( 'fas fa-birthday-cake' => 'Birthday Cake' ),
					array( 'fas fa-blind' => 'Blind' ),
					array( 'fas fa-bomb' => 'Bomb' ),
					array( 'fas fa-book' => 'book(read, documentation)' ),
					array( 'fas fa-bookmark' => 'bookmark(save)' ),
					array( 'far fa-bookmark' => 'Bookmark Outlined(save)' ),
					array( 'fas fa-briefcase' => 'Briefcase(work, business, office, luggage, bag)' ),
					array( 'fas fa-building' => 'Building(work, business, apartment, office, company)' ),
					array( 'far fa-building' => 'Building Outlined(work, business, apartment, office, company)' ),
					array( 'fas fa-car' => 'Car(vehicle)(automobile)' ),
					array( 'fas fa-coffee' => 'Coffee(morning, mug, breakfast, tea, drink, cafe)' ),
					array( 'fas fa-crosshairs' => 'Crosshairs(picker)' ),
					array( 'fas fa-directions' => 'Directions' ),
					array( 'fas fa-dollar-sign' => 'US Dollar(dollar)' ),
					array( 'fas fa-draw-polygon' => 'Draw Polygon' ),
					array( 'fas fa-eye' => 'Eye(show, visible, views)' ),
					array( 'far fa-eye' => 'Eye Outlined(show, visible, views)' ),
					array( 'fas fa-eye-slash' => 'Eye Slash(toggle, show, hide, visible, visiblity, views)' ),
					array( 'far fa-eye-slash' => 'Eye Slash Outlined(toggle, show, hide, visible, visiblity, views)' ),
					array( 'fas fa-fighter-jet' => 'fighter-jet(fly, plane, airplane, quick, fast, travel)' ),
					array( 'fas fa-fire' => 'fire(flame, hot, popular)' ),
					array( 'fas fa-fire-alt' => 'fire alt(flame, hot, popular)' ),
					array( 'fas fa-fire-extinguisher' => 'fire-extinguisher' ),
					array( 'fas fa-flag' => 'flag(report, notification, notify)' ),
					array( 'far fa-flag' => 'Flag Outlined(report, notification)' ),
					array( 'fas fa-flag-checkered' => 'flag-checkered(report, notification, notify)' ),
					array( 'fas fa-flask' => 'Flask(science, beaker, experimental, labs)' ),
					array( 'fas fa-gamepad' => 'Gamepad(controller)' ),
					array( 'fas fa-gavel' => 'Gavel(judge, lawyer, opinion)(legal)' ),
					array( 'fas fa-gift' => 'gift(present)' ),
					array( 'fas fa-glass-martini' => 'Glass Martini' ),
					array( 'fas fa-globe' => 'Globe(world, planet, map, place, travel, earth, global, translate, all, language, localize, location, coordinates, country)' ),
					array( 'fas fa-graduation-cap' => 'Graduation Cap(learning, school, student)(mortar-board)' ),
					array( 'fas fa-h-square' => 'H Square(hospital, hotel)' ),
					array( 'fas fa-heart' => 'Heart(love, like, favorite)' ),
					array( 'far fa-heart' => 'Heart Outlined(love, like, favorite)' ),
					array( 'fas fa-heartbeat' => 'Heartbeat(ekg)' ),
					array( 'fas fa-helicopter' => 'Helicopter' ),
					array( 'fas fa-home' => 'Home(main, house)' ),
					array( 'fas fa-hospital' => 'hospital(building)' ),
					array( 'far fa-hospital' => 'hospital Outlined(building)' ),
					array( 'fas fa-image' => 'Image(photo, picture)' ),
					array( 'far fa-image' => 'Image Outlined(photo, picture)' ),
					array( 'fas fa-images' => 'Images(photos, pictures)' ),
					array( 'far fa-images' => 'Images Outlined(photos, pictures)' ),
					array( 'fas fa-industry' => 'Industry(factory)' ),
					array( 'fas fa-info' => 'Info(help, information, more, details)' ),
					array( 'fas fa-info-circle' => 'Info Circle(help, information, more, details)' ),
					array( 'fas fa-key' => 'key(unlock, password)' ),
					array( 'fas fa-landmark' => 'Landmark' ),
					array( 'fas fa-layer-group' => 'Layer Group' ),
					array( 'fas fa-leaf' => 'leaf(eco, nature, plant)' ),
					array( 'fas fa-lemon' => 'Lemon' ),
					array( 'far fa-lemon' => 'Lemon Outlined' ),
					array( 'fas fa-life-ring' => 'Life Ring(life-bouy, life-buoy, life-saver, support)' ),
					array( 'far fa-life-ring' => 'Life Ring Outlined(life-bouy, life-buoy, life-saver, support)' ),
					array( 'fas fa-lightbulb' => 'Lightbulb(idea, inspiration)' ),
					array( 'far fa-lightbulb' => 'Lightbulb Outlined(idea, inspiration)' ),
					array( 'fas fa-location-arrow' => 'location-arrow(map, coordinates, location, address, place, where)' ),
					array( 'fas fa-low-vision' => 'Low Vision' ),
					array( 'fas fa-magnet' => 'magnet' ),
					array( 'fas fa-male' => 'Male(man, user, person, profile)' ),
					array( 'fas fa-map' => 'Map' ),
					array( 'far fa-map' => 'Map Outlined' ),
					array( 'fas fa-map-marker' => 'map-marker(map, pin, location, coordinates, localize, address, travel, where, place)' ),
					array( 'fas fa-map-marker-alt' => 'map-marker-alt(map, pin, location, coordinates, localize, address, travel, where, place)' ),
					array( 'fas fa-map-pin' => 'Map Pin' ),
					array( 'fas fa-map-signs' => 'Map Signs' ),
					array( 'fas fa-medkit' => 'medkit(first aid, firstaid, help, support, health)' ),
					array( 'fas fa-money-bill' => 'Money Bill' ),
					array( 'fas fa-money-bill-alt' => 'Money Bill Alt' ),
					array( 'far fa-money-bill-alt' => 'Money Bill Alt Outlined' ),
					array( 'fas fa-motorcycle' => 'Motorcycle(vehicle, bike)' ),
					array( 'fas fa-music' => 'Music(note, sound)' ),
					array( 'fas fa-newspaper' => 'Newspaper(press)' ),
					array( 'far fa-newspaper' => 'Newspaper Outlined(press)' ),
					array( 'fas fa-parking' => 'Parking' ),
					array( 'fas fa-paw' => 'Paw' ),
					array( 'fas fa-phone' => 'Phone(call, voice, number, support, earphone, telephone)' ),
					array( 'fas fa-phone-square' => 'Phone Square(call, voice, number, support, telephone)' ),
					array( 'fas fa-phone-volume' => 'Phone Volumne' ),
					array( 'fas fa-plane' => 'plane(travel, trip, location, destination, airplane, fly, mode)' ),
					array( 'fas fa-plug' => 'Plug(power, connect)' ),
					array( 'fas fa-plus' => 'plus(add, new, create, expand)' ),
					array( 'fas fa-plus-square' => 'Plus Square(add, new, create, expand)' ),
					array( 'far fa-plus-square' => 'Plus Square Outlined(add, new, create, expand)' ),
					array( 'fas fa-print' => 'Print' ),
					array( 'fas fa-recycle' => 'Recycle' ),
					array( 'fas fa-restroom' => 'Restroom' ),
					array( 'fas fa-road' => 'road(street)' ),
					array( 'fas fa-rocket' => 'rocket(app)' ),
					array( 'fas fa-route' => 'Search(magnify, zoom, enlarge, bigger)' ),
					array( 'fas fa-search-minus' => 'Search Minus(magnify, minify, zoom, smaller)' ),
					array( 'fas fa-search-plus' => 'Search Plus(magnify, zoom, enlarge, bigger)' ),
					array( 'fas fa-ship' => 'Ship(boat, sea)' ),
					array( 'fas fa-shoe-prints' => 'Shoe Prints' ),
					array( 'fas fa-shopping-bag' => 'Shopping Bag' ),
					array( 'fas fa-shopping-basket' => 'Shopping Basket' ),
					array( 'fas fa-shopping-cart' => 'shopping-cart(checkout, buy, purchase, payment)' ),
					array( 'fas fa-shower' => 'Shower' ),
					array( 'fas fa-snowplow' => 'Snowplow' ),
					array( 'fas fa-street-view' => 'Street View(map)' ),
					array( 'fas fa-subway' => 'Subway' ),
					array( 'fas fa-suitcase' => 'Suitcase(trip, luggage, travel, move, baggage)' ),
					array( 'fas fa-tag' => 'tag(label)' ),
					array( 'fas fa-tags' => 'tags(labels)' ),
					array( 'fas fa-taxi' => 'Taxi(vehicle)(cab)' ),
					array( 'fas fa-thumbtack' => 'Thumbtack' ),
					array( 'fas fa-ticket-alt' => 'Ticket Alt' ),
					array( 'fas fa-tint' => 'tint(raindrop, waterdrop, drop, droplet)' ),
					array( 'fas fa-traffic-light' => 'Traffic Light' ),
					array( 'fas fa-train' => 'Train' ),
					array( 'fas fa-tram' => 'Tram' ),
					array( 'fas fa-tree' => 'Tree' ),
					array( 'fas fa-trophy' => 'trophy(award, achievement, cup, winner, game)' ),
					array( 'fas fa-truck' => 'truck(shipping)' ),
					array( 'fas fa-tty' => 'TTY' ),
					array( 'fas fa-umbrella' => 'Umbrella' ),
					array( 'fas fa-university' => 'University(institution, bank)' ),
					array( 'fas fa-utensil-spoon' => 'Utensil Spoon' ),
					array( 'fas fa-utensils' => 'Utensils' ),
					array( 'fas fa-wheelchair' => 'Wheelchair(handicap, person)' ),
					array( 'fas fa-wifi' => 'Wifi' ),
					array( 'fas fa-wine-glass' => 'Wine Glass' ),
					array( 'fas fa-wrench' => 'Wrench(settings, fix, update, spanner)' ),
				),
				'Marketing'           => array(
					array( 'fas fa-ad' => 'Advertisement' ),
					array( 'fas fa-bullhorn' => 'Bullhorn(announcement, share, broadcast, louder, megaphone)' ),
					array( 'fas fa-bullseye' => 'Bullseye(notification, dot-circle)' ),
					array( 'fas fa-comment-dollar' => 'comment dollar(speech, notification, note, chat, bubble, feedback, message, texting, sms, conversation, usd)' ),
					array( 'fas fa-comments-dollar' => 'comments dollar(speech, notification, note, chat, bubble, feedback, message, texting, sms, conversation, usd)' ),
					array( 'fas fa-envelope-open-text' => 'Envelope Open Text(email, e-mail, letter, support, mail, message, notification)' ),
					array( 'fas fa-funnel-dollar' => 'Funnel Dollar' ),
					array( 'fas fa-lightbulb' => 'Lightbulb(idea, inspiration)' ),
					array( 'far fa-lightbulb' => 'Lightbulb Outlined(idea, inspiration)' ),
					array( 'fas fa-mail-bulk' => 'Mail Bulk' ),
					array( 'fas fa-poll' => 'Poll' ),
					array( 'fas fa-poll-h' => 'Poll Horizontal' ),
					array( 'fas fa-search-dollar' => 'Search Dollar(usd)' ),
					array( 'fas fa-search-location' => 'Search Location' ),
				),
				'Mathematics'         => array(
					array( 'fas fa-calculator' => 'Calculator' ),
					array( 'fas fa-divide' => 'divide' ),
					array( 'fas fa-equals' => 'equals' ),
					array( 'fas fa-greater-than' => 'greater than' ),
					array( 'fas fa-greater-than-equal' => 'greater than equal' ),
					array( 'fas fa-infinity' => 'infinity' ),
					array( 'fas fa-less-than' => 'less than' ),
					array( 'fas fa-less-than-equal' => 'less than equal' ),
					array( 'fas fa-minus' => 'minus(hide, minify, delete, remove, trash, hide, collapse)' ),
					array( 'fas fa-not-equal' => 'not equal' ),
					array( 'fas fa-percentage' => 'percentage' ),
					array( 'fas fa-plus' => 'plus(add, new, create, expand)' ),
					array( 'fas fa-square-root-alt' => 'square root alt' ),
					array( 'fas fa-subscript' => 'subscript' ),
					array( 'fas fa-superscript' => 'superscript(exponential)' ),
					array( 'fas fa-times' => 'Times(close, exit, x, cross)(remove, close)' ),
				),
				'Medical'             => array(
					array( 'fas fa-allergies' => 'Allergies (Hand)' ),
					array( 'fas fa-ambulance' => 'ambulance(vehicle, support, help)' ),
					array( 'fas fa-band-aid' => 'Band-Aid' ),
					array( 'fas fa-biohazard' => 'Biohazard' ),
					array( 'fas fa-bone' => 'Bone' ),
					array( 'fas fa-bong' => 'Bong' ),
					array( 'fas fa-book-medical' => 'Book Medical' ),
					array( 'fas fa-brain' => 'Brain' ),
					array( 'fas fa-briefcase-medical' => 'Briefcase Medical' ),
					array( 'fas fa-burn' => 'Burn(fire)' ),
					array( 'fas fa-cannabis' => 'Cannabis' ),
					array( 'fas fa-capsules' => 'Capsules' ),
					array( 'fas fa-clinic-medical' => 'Clinic Medical' ),
					array( 'fas fa-comment-medical' => 'Comment medical(speech, notification, note, chat, bubble, feedback, message, texting, sms, conversation, plus)' ),
					array( 'fas fa-crutch' => 'Crutch' ),
					array( 'fas fa-diagnoses' => 'Diagnoses' ),
					array( 'fas fa-dna' => 'DNA' ),
					array( 'fas fa-file-medical' => 'File Medical' ),
					array( 'fas fa-file-medical-alt' => 'File Medical Alt' ),
					array( 'fas fa-file-prescription' => 'File Prescription' ),
					array( 'fas fa-first-aid' => 'First Aid' ),
					array( 'fas fa-heart' => 'Heart(love, like, favorite)' ),
					array( 'far fa-heart' => 'Heart Outlined(love, like, favorite)' ),
					array( 'fas fa-heartbeat' => 'Heartbeat(ekg)' ),
					array( 'fas fa-hospital' => 'hospital(building)' ),
					array( 'far fa-hospital' => 'hospital Outlined(building)' ),
					array( 'fas fa-hospital-alt' => 'hospital Alt(building)' ),
					array( 'fas fa-hospital-symbol' => 'Hospital Symbol' ),
					array( 'fas fa-id-card-alt' => 'Identification Card Alt(drivers-license)' ),
					array( 'fas fa-joint' => 'Joint' ),
					array( 'fas fa-laptop-medical' => 'Laptop Medical' ),
					array( 'fas fa-microscope' => 'Microscope' ),
					array( 'fas fa-mortar-pestle' => 'Mortar Pestle' ),
					array( 'fas fa-notes-medical' => 'Notes Medical' ),
					array( 'fas fa-pager' => 'Pager' ),
					array( 'fas fa-pills' => 'Pills' ),
					array( 'fas fa-plus' => 'plus(add, new, create, expand)' ),
					array( 'fas fa-poop' => 'Poop' ),
					array( 'fas fa-prescription' => 'Prescription' ),
					array( 'fas fa-prescription-bottle' => 'Prescription Bottle' ),
					array( 'fas fa-prescription-bottle-alt' => 'Prescription Bottle Alt' ),
					array( 'fas fa-procedures' => 'Procedures' ),
					array( 'fas fa-radiation' => 'Radiation' ),
					array( 'fas fa-radiation-alt' => 'Radiation Alt' ),
					array( 'fas fa-smoking' => 'Smoking' ),
					array( 'fas fa-smoking-ban' => 'Smoking Ban' ),
					array( 'fas fa-star-of-life' => 'Star of Life' ),
					array( 'fas fa-stethoscope' => 'Stethoscope' ),
					array( 'fas fa-syringe' => 'Syringe' ),
					array( 'fas fa-tablets' => 'Tablets' ),
					array( 'fas fa-teeth' => 'Teeth' ),
					array( 'fas fa-teeth-open' => 'Teeth Open' ),
					array( 'fas fa-thermometer' => 'Thermometer' ),
					array( 'fas fa-tooth' => 'Tooth' ),
					array( 'fas fa-user-md' => 'user-md(doctor, profile, medical, nurse)' ),
					array( 'fas fa-user-nurse' => 'User nurse' ),
					array( 'fas fa-vial' => 'Vial' ),
					array( 'fas fa-vials' => 'Vials' ),
					array( 'fas fa-weight' => 'Weight' ),
					array( 'fas fa-x-ray' => 'X ray' ),
				),
				'Moving'              => array(
					array( 'fas fa-archive' => 'Archive(box, storage)' ),
					array( 'fas fa-box-open' => 'Opened Box' ),
					array( 'fas fa-couch' => 'Couch' ),
					array( 'fas fa-dolly' => 'Dolly' ),
					array( 'fas fa-people-carry' => 'Peoples carrying' ),
					array( 'fas fa-route' => 'Search(magnify, zoom, enlarge, bigger)' ),
					array( 'fas fa-sign' => 'Sign' ),
					array( 'fas fa-suitcase' => 'Suitcase(trip, luggage, travel, move, baggage)' ),
					array( 'fas fa-tape' => 'Tape' ),
					array( 'fas fa-truck-loading' => 'Truck Loading' ),
					array( 'fas fa-truck-moving' => 'Moving Truck' ),
					array( 'fas fa-wine-glass' => 'Wine Glass' ),
				),
				'Objects'             => array(
					array( 'fas fa-ambulance' => 'ambulance(vehicle, support, help)' ),
					array( 'fas fa-anchor' => 'Anchor(link)' ),
					array( 'fas fa-archive' => 'Archive(box, storage)' ),
					array( 'fas fa-award' => 'Award' ),
					array( 'fas fa-baby-carriage' => 'Baby Carriage' ),
					array( 'fas fa-balance-scale' => 'Balance Scale' ),
					array( 'fas fa-bath' => 'Bath(bathtub, s15)' ),
					array( 'fas fa-bed' => 'Bed(travel)(hotel)' ),
					array( 'fas fa-beer' => 'beer(alcohol, stein, drink, mug, bar, liquor)' ),
					array( 'fas fa-bell' => 'bell(alert, reminder, notification)' ),
					array( 'far fa-bell' => 'bell outlined(alert, reminder, notification)' ),
					array( 'fas fa-bicycle' => 'Bicycle(vehicle, bike)' ),
					array( 'fas fa-binoculars' => 'Binoculars' ),
					array( 'fas fa-birthday-cake' => 'Birthday Cake' ),
					array( 'fas fa-blender' => 'Blender' ),
					array( 'fas fa-bomb' => 'Bomb' ),
					array( 'fas fa-book' => 'Book(read, documentation)' ),
					array( 'fas fa-book-dead' => 'Book Dead(skull-crossbones)' ),
					array( 'fas fa-bookmark' => 'bookmark(save)' ),
					array( 'far fa-bookmark' => 'Bookmark Outlined(save)' ),
					array( 'fas fa-briefcase' => 'Briefcase(work, business, office, luggage, bag)' ),
					array( 'fas fa-broadcast-tower' => 'Broadcast Tower' ),
					array( 'fas fa-bug' => 'Bug(report, insect)' ),
					array( 'fas fa-building' => 'Building(work, business, apartment, office, company)' ),
					array( 'far fa-building' => 'Building Outlined(work, business, apartment, office, company)' ),
					array( 'fas fa-bullhorn' => 'Bullhorn(announcement, share, broadcast, louder, megaphone)' ),
					array( 'fas fa-bullseye' => 'Bullseye(notification, dot-circle)' ),
					array( 'fas fa-bus' => 'Bus(vehicle)' ),
					array( 'fas fa-calculator' => 'Calculator' ),
					array( 'fas fa-calendar' => 'Calendar(date, time, when, event)' ),
					array( 'far fa-calendar' => 'Calendar Outlined(date, time, when, event)' ),
					array( 'fas fa-calendar-alt' => 'Calendar Alt(date, time, when, event)' ),
					array( 'far fa-calendar-alt' => 'Calendar Alt Outlined(date, time, when, event)' ),
					array( 'fas fa-camera' => 'camera(photo, picture, record)' ),
					array( 'fas fa-camera-retro' => 'camera-retro(photo, picture, record)' ),
					array( 'fas fa-candy-cane' => 'Candy Cane' ),
					array( 'fas fa-car' => 'Car(vehicle)(automobile)' ),
					array( 'fas fa-carrot' => 'Carrot' ),
					array( 'fas fa-church' => 'Church' ),
					array( 'fas fa-clipboard' => 'Clipboard' ),
					array( 'far fa-clipboard' => 'Clipboard Outlined' ),
					array( 'fas fa-cloud' => 'Cloud(save)' ),
					array( 'fas fa-coffee' => 'Coffee(morning, mug, breakfast, tea, drink, cafe)' ),
					array( 'fas fa-cog' => 'cog(settings)(gear)' ),
					array( 'fas fa-cogs' => 'cogs(settings)(gears)' ),
					array( 'fas fa-compass' => 'Compass(safari, directory, menu, location)' ),
					array( 'far fa-compass' => 'Compass Outlined(safari, directory, menu, location)' ),
					array( 'fas fa-cookie' => 'Cookie' ),
					array( 'fas fa-cookie-bite' => 'Cookie Bite' ),
					array( 'fas fa-copy' => 'Copy' ),
					array( 'far fa-copy' => 'Copy Outlined' ),
					array( 'fas fa-cube' => 'Cube' ),
					array( 'fas fa-cubes' => 'Cubes' ),
					array( 'fas fa-cut' => 'Scissors(cut)' ),
					array( 'fas fa-dice' => 'Dice' ),
					array( 'fas fa-dice-d20' => 'dice d20' ),
					array( 'fas fa-dice-d6' => 'dice d6' ),
					array( 'fas fa-dice-five' => 'Dice Five' ),
					array( 'fas fa-dice-four' => 'Dice Four' ),
					array( 'fas fa-dice-one' => 'Dice One' ),
					array( 'fas fa-dice-six' => 'Dice Six' ),
					array( 'fas fa-dice-three' => 'Dice Three' ),
					array( 'fas fa-dice-two' => 'Dice Two' ),
					array( 'fas fa-digital-tachograph' => 'Digital Tachograph' ),
					array( 'fas fa-door-closed' => 'Door closed' ),
					array( 'fas fa-door-open' => 'Door open' ),
					array( 'fas fa-drum' => 'Drum' ),
					array( 'fas fa-drum-steelpan' => 'Drum Steelpan' ),
					array( 'fas fa-envelope' => 'Envelope(email, e-mail, letter, support, mail, message, notification)' ),
					array( 'far fa-envelope' => 'Envelope Outlined(email, e-mail, letter, support, mail, message, notification)' ),
					array( 'fas fa-envelope-open' => 'Envelope Open(email, e-mail, letter, support, mail, message, notification)' ),
					array( 'far fa-envelope-open' => 'Envelope Open Outlined(email, e-mail, letter, support, mail, message, notification)' ),
					array( 'fas fa-eraser' => 'eraser(remove, delete)' ),
					array( 'fas fa-eye' => 'Eye(show, visible, views)' ),
					array( 'far fa-eye' => 'Eye Outlined(show, visible, views)' ),
					array( 'fas fa-eye-dropper' => 'Eyedropper' ),
					array( 'fas fa-fax' => 'Fax' ),
					array( 'fas fa-feather' => 'Feather' ),
					array( 'fas fa-feather-alt' => 'Feather Alt' ),
					array( 'fas fa-fighter-jet' => 'fighter-jet(fly, plane, airplane, quick, fast, travel)' ),
					array( 'fas fa-file' => 'File(new, page, pdf, document)' ),
					array( 'far fa-file' => 'File Outlined(new, page, pdf, document)' ),
					array( 'fas fa-file-alt' => 'File Alt(new, page, pdf, document)' ),
					array( 'far fa-file-alt' => 'File Alt Outlined(new, page, pdf, document)' ),
					array( 'fas fa-file-prescription' => 'File Prescription' ),
					array( 'fas fa-film' => 'Film(movie)' ),
					array( 'fas fa-fire' => 'fire(flame, hot, popular)' ),
					array( 'fas fa-fire-alt' => 'fire alt(flame, hot, popular)' ),
					array( 'fas fa-fire-extinguisher' => 'fire-extinguisher' ),
					array( 'fas fa-flag' => 'flag(report, notification, notify)' ),
					array( 'far fa-flag' => 'Flag Outlined(report, notification)' ),
					array( 'fas fa-flag-checkered' => 'flag-checkered(report, notification, notify)' ),
					array( 'fas fa-flask' => 'Flask(science, beaker, experimental, labs)' ),
					array( 'fas fa-futbol' => 'Futbol' ),
					array( 'far fa-futbol' => 'Futbol Outlined' ),
					array( 'fas fa-gamepad' => 'Gamepad(controller)' ),
					array( 'fas fa-gavel' => 'Gavel(judge, lawyer, opinion)(legal)' ),
					array( 'fas fa-gem' => 'Gem' ),
					array( 'far fa-gem' => 'Gem Outlined' ),
					array( 'fas fa-gift' => 'gift(present)' ),
					array( 'fas fa-gifts' => 'Gifts' ),
					array( 'fas fa-glass-cheers' => 'Glass Cheers' ),
					array( 'fas fa-glass-martini' => 'Glass Martini' ),
					array( 'fas fa-glass-whiskey' => 'Glass Whiskey' ),
					array( 'fas fa-glasses' => 'Glasses' ),
					array( 'fas fa-globe' => 'Globe(world, planet, map, place, travel, earth, global, translate, all, language, localize, location, coordinates, country)' ),
					array( 'fas fa-graduation-cap' => 'Graduation Cap(learning, school, student)(mortar-board)' ),
					array( 'fas fa-guitar' => 'Guitar' ),
					array( 'fas fa-hat-wizard' => 'Hat Wizard' ),
					array( 'fas fa-hdd' => 'HDD(harddrive, hard drive, storage, save)' ),
					array( 'far fa-hdd' => 'HDD Outlined(harddrive, hard drive, storage, save)' ),
					array( 'fas fa-headphones' => 'headphones(sound, listen, music, audio)' ),
					array( 'fas fa-headphones-alt' => 'headphones alt(sound, listen, music, audio)' ),
					array( 'fas fa-headset' => 'Headset(headphone)' ),
					array( 'fas fa-heart' => 'Heart(love, like, favorite)' ),
					array( 'far fa-heart' => 'Heart Outlined(love, like, favorite)' ),
					array( 'fas fa-heart-broken' => 'Heart Broken' ),
					array( 'fas fa-helicopter' => 'Helicopter' ),
					array( 'fas fa-highlighter' => 'Highlighter(paint)' ),
					array( 'fas fa-holly-berry' => 'Holiday Berry' ),
					array( 'fas fa-home' => 'Home(main, house)' ),
					array( 'fas fa-hospital' => 'hospital(building)' ),
					array( 'far fa-hospital' => 'hospital Outlined(building)' ),
					array( 'fas fa-hourglass' => 'Hourglass' ),
					array( 'far fa-hourglass' => 'Hourglass Outlined' ),
					array( 'fas fa-igloo' => 'Igloo' ),
					array( 'fas fa-image' => 'Image(photo, picture)' ),
					array( 'far fa-image' => 'Image Outlined(photo, picture)' ),
					array( 'fas fa-images' => 'Images(photos, pictures)' ),
					array( 'far fa-images' => 'Images Outlined(photos, pictures)' ),
					array( 'fas fa-industry' => 'Industry(factory)' ),
					array( 'fas fa-key' => 'key(unlock, password)' ),
					array( 'fas fa-keyboard' => 'Keyboard(type, input)' ),
					array( 'far fa-keyboard' => 'Keyboard Outlined(type, input)' ),
					array( 'fas fa-laptop' => 'Laptop(demo, computer, device)' ),
					array( 'fas fa-leaf' => 'leaf(eco, nature, plant)' ),
					array( 'fas fa-lemon' => 'Lemon' ),
					array( 'far fa-lemon' => 'Lemon Outlined' ),
					array( 'fas fa-life-ring' => 'Life Ring(life-bouy, life-buoy, life-saver, support)' ),
					array( 'far fa-life-ring' => 'Life Ring Outlined(life-bouy, life-buoy, life-saver, support)' ),
					array( 'fas fa-lightbulb' => 'Lightbulb(idea, inspiration)' ),
					array( 'far fa-lightbulb' => 'Lightbulb Outlined(idea, inspiration)' ),
					array( 'fas fa-lock' => 'lock(protect, admin, security)' ),
					array( 'fas fa-lock-open' => 'lock open(protect, admin, security)' ),
					array( 'fas fa-magic' => 'magic(wizard, automatic, autocomplete)' ),
					array( 'fas fa-magnet' => 'magnet' ),
					array( 'fas fa-map' => 'Map' ),
					array( 'far fa-map' => 'Map Outlined' ),
					array( 'fas fa-map-marker' => 'map-marker(map, pin, location, coordinates, localize, address, travel, where, place)' ),
					array( 'fas fa-map-marker-alt' => 'map-marker-alt(map, pin, location, coordinates, localize, address, travel, where, place)' ),
					array( 'fas fa-map-pin' => 'Map Pin' ),
					array( 'fas fa-map-signs' => 'Map Signs' ),
					array( 'fas fa-marker' => 'Marker(pen)' ),
					array( 'fas fa-medal' => 'Medal' ),
					array( 'fas fa-medkit' => 'medkit(first aid, firstaid, help, support, health)' ),
					array( 'fas fa-memory' => 'Memory' ),
					array( 'fas fa-microchip' => 'Microchip' ),
					array( 'fas fa-microphone' => 'microphone(record, voice, sound)' ),
					array( 'fas fa-microphone-alt' => 'microphone alt(record, voice, sound)' ),
					array( 'fas fa-mitten' => 'Mitten' ),
					array( 'fas fa-mobile' => 'Mobile Phone(cell phone, cellphone, text, call, iphone, number, telephone)(mobile-phone)' ),
					array( 'fas fa-mobile-alt' => 'Mobile Phone Alt(cell phone, cellphone, text, call, iphone, number, telephone)(mobile-phone-alt)' ),
					array( 'fas fa-money-bill' => 'Money Bill' ),
					array( 'fas fa-money-bill-alt' => 'Money Bill Alt' ),
					array( 'far fa-money-bill-alt' => 'Money Bill Alt Outlined' ),
					array( 'fas fa-money-check' => 'Money Check(money, buy, debit, checkout, purchase, payment, credit card)' ),
					array( 'fas fa-money-check-alt' => 'Money Check Alt(money, buy, debit, checkout, purchase, payment, credit card)' ),
					array( 'fas fa-moon' => 'Moon(night, darker, contrast)' ),
					array( 'far fa-moon' => 'Moon Outlined(night, darker, contrast)' ),
					array( 'fas fa-motorcycle' => 'Motorcycle(vehicle, bike)' ),
					array( 'fas fa-mug-hot' => 'Mug Hot' ),
					array( 'fas fa-newspaper' => 'Newspaper(press)' ),
					array( 'far fa-newspaper' => 'Newspaper Outlined(press)' ),
					array( 'fas fa-paint-brush' => 'Paint Brush' ),
					array( 'fas fa-paper-plane' => 'Paper Plane(send)' ),
					array( 'far fa-paper-plane' => 'Paper Plane Outlined(send)' ),
					array( 'fas fa-paperclip' => 'Paperclip(attachment)' ),
					array( 'fas fa-paste' => 'Paste' ),
					array( 'fas fa-paw' => 'Paw' ),
					array( 'fas fa-pen' => 'Pen(write, edit, update)' ),
					array( 'fas fa-pen-alt' => 'Pen Alt(write, edit, update)' ),
					array( 'fas fa-pen-fancy' => 'Pen fancy(write, edit, update)' ),
					array( 'fas fa-pen-nib' => 'Pen nib(write, edit, update)' ),
					array( 'fas fa-pencil-alt' => 'Pencil Alt(write, edit, update)' ),
					array( 'fas fa-phone' => 'Phone(call, voice, number, support, earphone, telephone)' ),
					array( 'fas fa-plane' => 'plane(travel, trip, location, destination, airplane, fly, mode)' ),
					array( 'fas fa-plug' => 'Plug(power, connect)' ),
					array( 'fas fa-print' => 'Print' ),
					array( 'fas fa-puzzle-piece' => 'Puzzle Piece(addon, add-on, section)' ),
					array( 'fas fa-ring' => 'Ring' ),
					array( 'fas fa-road' => 'road(street)' ),
					array( 'fas fa-rocket' => 'rocket(app)' ),
					array( 'fas fa-ruler-combined' => 'Ruler Combined' ),
					array( 'fas fa-ruler-horizontal' => 'Ruler Horizontal' ),
					array( 'fas fa-ruler-vertical' => 'Ruler Vertical' ),
					array( 'fas fa-satellite' => 'Satellite' ),
					array( 'fas fa-satellite-dish' => 'Satellite Dish' ),
					array( 'fas fa-save' => 'Save' ),
					array( 'far fa-save' => 'Save Outlined' ),
					array( 'fas fa-school' => 'School' ),
					array( 'fas fa-screwdriver' => 'Screwdriver' ),
					array( 'fas fa-scroll' => 'Scroll' ),
					array( 'fas fa-sd-card' => 'SD Card' ),
					array( 'fas fa-search' => 'Search(magnify, zoom, enlarge, bigger)' ),
					array( 'fas fa-shield-alt' => 'shield alt(award, achievement, security, winner)' ),
					array( 'fas fa-shopping-bag' => 'Shopping Bag' ),
					array( 'fas fa-shopping-basket' => 'Shopping Basket' ),
					array( 'fas fa-shopping-cart' => 'shopping-cart(checkout, buy, purchase, payment)' ),
					array( 'fas fa-shower' => 'Shower' ),
					array( 'fas fa-sim-card' => 'Sim Card' ),
					array( 'fas fa-skull-crossbones' => 'Skull Crossbones' ),
					array( 'fas fa-sleigh' => 'Sleigh' ),
					array( 'fas fa-snowflake' => 'Snowflake' ),
					array( 'far fa-snowflake' => 'Snowflake Outlined' ),
					array( 'fas fa-snowplow' => 'Snowplow' ),
					array( 'fas fa-space-shuttle' => 'Space Shuttle' ),
					array( 'fas fa-star' => 'Star(award, achievement, night, rating, score, favorite)' ),
					array( 'far fa-star' => 'Star Outlined(award, achievement, night, rating, score, favorite)' ),
					array( 'fas fa-sticky-note' => 'Sticky Note' ),
					array( 'far fa-sticky-note' => 'Sticky Note Outlined' ),
					array( 'fas fa-stopwatch' => 'Stopwatch' ),
					array( 'fas fa-subway' => 'Subway' ),
					array( 'fas fa-suitcase' => 'Suitcase(trip, luggage, travel, move, baggage)' ),
					array( 'fas fa-sun' => 'Sun(weather, contrast, lighter, brighten, day)' ),
					array( 'far fa-sun' => 'Sun Outlined(weather, contrast, lighter, brighten, day)' ),
					array( 'fas fa-tablet' => 'tablet(ipad, device)' ),
					array( 'fas fa-tablet-alt' => 'tablet alt(ipad, device)' ),
					array( 'fas fa-tachometer-alt' => 'Tachometer(speedometer, fast)(dashboard)' ),
					array( 'fas fa-tag' => 'tag(label)' ),
					array( 'fas fa-tags' => 'tags(labels)' ),
					array( 'fas fa-taxi' => 'Taxi(vehicle)(cab)' ),
					array( 'fas fa-thumbtack' => 'Thumbtack' ),
					array( 'fas fa-ticket-alt' => 'Ticket Alt' ),
					array( 'fas fa-toilet' => 'Toilet' ),
					array( 'fas fa-toolbox' => 'Toolbox' ),
					array( 'fas fa-tools' => 'Tools' ),
					array( 'fas fa-train' => 'Train' ),
					array( 'fas fa-tram' => 'Tram' ),
					array( 'fas fa-trash' => 'Trash' ),
					array( 'fas fa-trash-alt' => 'Trash Alt' ),
					array( 'far fa-trash-alt' => 'Trash Alt Outlined' ),
					array( 'fas fa-tree' => 'Tree' ),
					array( 'fas fa-trophy' => 'trophy(award, achievement, cup, winner, game)' ),
					array( 'fas fa-truck' => 'truck(shipping)' ),
					array( 'fas fa-tv' => 'Television(display, computer, monitor)(tv)' ),
					array( 'fas fa-umbrella' => 'Umbrella' ),
					array( 'fas fa-university' => 'University(institution, bank)' ),
					array( 'fas fa-unlock' => 'unlock(protect, admin, password, lock)' ),
					array( 'fas fa-unlock-alt' => 'Unlock Alt(protect, admin, password, lock)' ),
					array( 'fas fa-utensil-spoon' => 'Utensil Spoon' ),
					array( 'fas fa-utensils' => 'Utensils' ),
					array( 'fas fa-wallet' => 'Wallet' ),
					array( 'fas fa-weight' => 'Weight' ),
					array( 'fas fa-wheelchair' => 'Wheelchair(handicap, person)' ),
					array( 'fas fa-wine-glass' => 'Wine Glass' ),
					array( 'fas fa-wrench' => 'Wrench(settings, fix, update, spanner)' ),
				),
				'Payments & Shopping' => array(
					array( 'fab fa-alipay' => 'Alipay' ),
					array( 'fab fa-amazon-pay' => 'Amazon Pay' ),
					array( 'fab fa-apple-pay' => 'Apple Pay' ),
					array( 'fas fa-bell' => 'bell(alert, reminder, notification)' ),
					array( 'far fa-bell' => 'bell outlined(alert, reminder, notification)' ),
					array( 'fab fa-bitcoin' => 'Bitcoin (BTC)(bitcoin)' ),
					array( 'fas fa-bookmark' => 'bookmark(save)' ),
					array( 'far fa-bookmark' => 'Bookmark Outlined(save)' ),
					array( 'fab fa-btc' => 'Bitcoin (BTC)(bitcoin)' ),
					array( 'fas fa-bullhorn' => 'Bullhorn(announcement, share, broadcast, louder, megaphone)' ),
					array( 'fas fa-camera' => 'camera(photo, picture, record)' ),
					array( 'fas fa-camera-retro' => 'camera-retro(photo, picture, record)' ),
					array( 'fas fa-cart-arrow-down' => 'Shopping Cart Arrow Down(shopping)' ),
					array( 'fas fa-cart-plus' => 'Add to Shopping Cart(add, shopping)' ),
					array( 'fab fa-cc-amazon-pay' => 'Amazon Pay Credit Card' ),
					array( 'fab fa-cc-amex' => 'American Express Credit Card(amex)' ),
					array( 'fab fa-cc-apple-pay' => 'Apple Pay Credit Card' ),
					array( 'fab fa-cc-diners-club' => 'Diner\'s Club Credit Card' ),
					array( 'fab fa-cc-discover' => 'Discover Credit Card' ),
					array( 'fab fa-cc-jcb' => 'JCB Credit Card' ),
					array( 'fab fa-cc-mastercard' => 'MasterCard Credit Card' ),
					array( 'fab fa-cc-paypal' => 'Paypal Credit Card' ),
					array( 'fab fa-cc-stripe' => 'Stripe Credit Card' ),
					array( 'fab fa-cc-visa' => 'Visa Credit Card' ),
					array( 'fas fa-certificate' => 'Certificate(badge, star)' ),
					array( 'fas fa-credit-card' => 'credit-card(money, buy, debit, checkout, purchase, payment)' ),
					array( 'far fa-credit-card' => 'credit-card outlined(money, buy, debit, checkout, purchase, payment)' ),
					array( 'fab fa-ethereum' => 'Ethereum' ),
					array( 'fas fa-gem' => 'Gem' ),
					array( 'far fa-gem' => 'Gem Outlined' ),
					array( 'fas fa-gift' => 'gift(present)' ),
					array( 'fab fa-google-wallet' => 'Google Wallet' ),
					array( 'fas fa-handshake' => 'Handshake' ),
					array( 'far fa-handshake' => 'Handshake Outlined' ),
					array( 'fas fa-heart' => 'Heart(love, like, favorite)' ),
					array( 'far fa-heart' => 'Heart Outlined(love, like, favorite)' ),
					array( 'fas fa-key' => 'key(unlock, password)' ),
					array( 'fas fa-money-check' => 'Money Check(money, buy, debit, checkout, purchase, payment, credit card)' ),
					array( 'fas fa-money-check-alt' => 'Money Check Alt(money, buy, debit, checkout, purchase, payment, credit card)' ),
					array( 'fab fa-paypal' => 'Paypal' ),
					array( 'fas fa-shopping-bag' => 'Shopping Bag' ),
					array( 'fas fa-shopping-basket' => 'Shopping Basket' ),
					array( 'fas fa-shopping-cart' => 'shopping-cart(checkout, buy, purchase, payment)' ),
					array( 'fas fa-star' => 'Star(award, achievement, night, rating, score, favorite)' ),
					array( 'far fa-star' => 'Star Outlined(award, achievement, night, rating, score, favorite)' ),
					array( 'fab fa-stripe' => 'Stripe' ),
					array( 'fab fa-stripe-s' => 'Stripe S' ),
					array( 'fas fa-tag' => 'tag(label)' ),
					array( 'fas fa-tags' => 'tags(labels)' ),
					array( 'fas fa-thumbs-down' => 'thumbs-down(dislike, disapprove, disagree, hand)' ),
					array( 'far fa-thumbs-down' => 'thumbs-down outlined(dislike, disapprove, disagree, hand)' ),
					array( 'fas fa-thumbs-up' => 'thumbs-up(like, favorite, approve, agree, hand)' ),
					array( 'far fa-thumbs-up' => 'thumbs-up outlined(like, favorite, approve, agree, hand)' ),
					array( 'fas fa-trophy' => 'trophy(award, achievement, cup, winner, game)' ),
				),
				'Political'           => array(
					array( 'fas fa-award' => 'Award' ),
					array( 'fas fa-balance-scale' => 'Balance Scale' ),
					array( 'fas fa-bullhorn' => 'Bullhorn(announcement, share, broadcast, louder, megaphone)' ),
					array( 'fas fa-check-double' => 'Check Double(todo, done, agree, accept, confirm, ok)' ),
					array( 'fas fa-democrat' => 'Democrat' ),
					array( 'fas fa-donate' => 'Donate' ),
					array( 'fas fa-dove' => 'Dove(Pigeon)' ),
					array( 'fas fa-fist-raised' => 'First Raised (Hand)' ),
					array( 'fas fa-flag-usa' => 'USA Flag' ),
					array( 'fas fa-handshake' => 'Handshake' ),
					array( 'far fa-handshake' => 'Handshake Outlined' ),
					array( 'fas fa-person-booth' => 'Person Booth' ),
					array( 'fas fa-piggy-bank' => 'Piggy Bank(box)' ),
					array( 'fas fa-republican' => 'Republican' ),
					array( 'fas fa-vote-yea' => 'Vote Yea' ),
				),
				'Religion'            => array(
					array( 'fas fa-ankh' => 'Ankh' ),
					array( 'fas fa-atom' => 'Atom' ),
					array( 'fas fa-bible' => 'Bible' ),
					array( 'fas fa-church' => 'Church(cross)' ),
					array( 'fas fa-cross' => 'Cross' ),
					array( 'fas fa-dharmachakra' => 'Dharmachakra' ),
					array( 'fas fa-dove' => 'Dove(Pigeon)' ),
					array( 'fas fa-gopuram' => 'Gopuram' ),
					array( 'fas fa-hamsa' => 'Hamsa' ),
					array( 'fas fa-hanukiah' => 'Menorah(Hanukkah)' ),
					array( 'fas fa-haykal' => 'Haykal' ),
					array( 'fas fa-jedi' => 'Jedi' ),
					array( 'fas fa-journal-whills' => 'Journal Whills' ),
					array( 'fas fa-kaaba' => 'Kaaba' ),
					array( 'fas fa-khanda' => 'khanda' ),
					array( 'fas fa-menorah' => 'Menorah(temple)' ),
					array( 'fas fa-mosque' => 'Mosque' ),
					array( 'fas fa-om' => 'Om(Aum, hinduism)' ),
					array( 'fas fa-pastafarianism' => 'Pastafarianism' ),
					array( 'fas fa-peace' => 'Peace' ),
					array( 'fas fa-place-of-worship' => 'Place of Worship' ),
					array( 'fas fa-pray' => 'Pray(person, man)' ),
					array( 'fas fa-praying-hands' => 'Praying hands' ),
					array( 'fas fa-quran' => 'Quran(Koran)' ),
					array( 'fas fa-star-and-crescent' => 'Star and Crescent' ),
					array( 'fas fa-star-of-david' => 'Star of David' ),
					array( 'fas fa-synagogue' => 'Synagogue' ),
					array( 'fas fa-torah' => 'Torah' ),
					array( 'fas fa-torii-gate' => 'Torii Gate' ),
					array( 'fas fa-vihara' => 'Vihara' ),
					array( 'fas fa-yin-yang' => 'Yin and Yang(inseparable, contradictory)' ),
				),
				'Shapes'              => array(
					array( 'fas fa-bookmark' => 'Bookmark(save)' ),
					array( 'far fa-bookmark' => 'Bookmark Outlined(save)' ),
					array( 'fas fa-calendar' => 'Calendar(date, time, when, event)' ),
					array( 'far fa-calendar' => 'Calendar Outlined(date, time, when, event)' ),
					array( 'fas fa-certificate' => 'Certificate(badge, star)' ),
					array( 'fas fa-circle' => 'Circle(dot, notification)' ),
					array( 'far fa-circle' => 'Circle Outlined' ),
					array( 'fas fa-cloud' => 'Cloud(save)' ),
					array( 'fas fa-comment' => 'Comment(speech, notification, note, chat, bubble, feedback, message, texting, sms, conversation)' ),
					array( 'far fa-comment' => 'Comment Outlined(speech, notification, note, chat, bubble, feedback, message, texting, sms, conversation)' ),
					array( 'fas fa-file' => 'File(new, page, pdf, document)' ),
					array( 'far fa-file' => 'File Outlined(new, page, pdf, document)' ),
					array( 'fas fa-folder' => 'Folder' ),
					array( 'far fa-folder' => 'Folder Outlined' ),
					array( 'fas fa-heart' => 'Heart(love, like, favorite)' ),
					array( 'far fa-heart' => 'Heart Outlined(love, like, favorite)' ),
					array( 'fas fa-heart-broken' => 'Heart Broken' ),
					array( 'fas fa-map-marker' => 'map-marker(map, pin, location, coordinates, localize, address, travel, where, place)' ),
					array( 'fas fa-play' => 'play(start, playing, music, sound, triangle)' ),
					array( 'fas fa-shapes' => 'Shapes(circle, triangle, rectangle)' ),
					array( 'fas fa-square' => 'Square(block, box)' ),
					array( 'far fa-square' => 'Square Outlined(block, box)' ),
					array( 'fas fa-star' => 'Star(award, achievement, night, rating, score, favorite)' ),
					array( 'far fa-star' => 'Star Outlined(award, achievement, night, rating, score, favorite)' ),
				),
				'Spinners'            => array(
					array( 'fas fa-asterisk' => 'asterisk(details)' ),
					array( 'fas fa-atom' => 'Atom' ),
					array( 'fas fa-certificate' => 'Certificate(badge, star)' ),
					array( 'fas fa-circle-notch' => 'Circle Notch' ),
					array( 'fas fa-cog' => 'cog(settings)(gear)' ),
					array( 'fas fa-compact-disc' => 'Compact Disc' ),
					array( 'fas fa-compass' => 'Compass(safari, directory, menu, location)' ),
					array( 'far fa-compass' => 'Compass Outlined(safari, directory, menu, location)' ),
					array( 'fas fa-crosshairs' => 'Crosshairs(picker)' ),
					array( 'fas fa-dharmachakra' => 'Dharmachakra' ),
					array( 'fas fa-haykal' => 'Haykal' ),
					array( 'fas fa-life-ring' => 'Life Ring(life-bouy, life-buoy, life-saver, support)' ),
					array( 'far fa-life-ring' => 'Life Ring Outlined(life-bouy, life-buoy, life-saver, support)' ),
					array( 'fas fa-palette' => 'Palette' ),
					array( 'fas fa-ring' => 'Ring' ),
					array( 'fas fa-slash' => 'Slash' ),
					array( 'fas fa-snowflake' => 'Snowflake' ),
					array( 'far fa-snowflake' => 'Snowflake Outlined' ),
					array( 'fas fa-spinner' => 'Spinner(loading, progress)' ),
					array( 'fas fa-stroopwafel' => 'Stroopwafel' ),
					array( 'fas fa-sun' => 'Sun(weather, contrast, lighter, brighten, day)' ),
					array( 'far fa-sun' => 'Sun Outlined(weather, contrast, lighter, brighten, day)' ),
					array( 'fas fa-sync' => 'refresh(reload, sync)' ),
					array( 'fas fa-sync-alt' => 'refresh alt(reload, sync, triangle arrow)' ),
					array( 'fas fa-yin-yang' => 'Yin and Yang(inseparable, contradictory)' ),
				),
				'Sports'              => array(
					array( 'fas fa-baseball-ball' => 'Baseball ball' ),
					array( 'fas fa-basketball-ball' => 'Basketball ball' ),
					array( 'fas fa-bowling-ball' => 'Bowling ball' ),
					array( 'fas fa-dumbbell' => 'Dumbbell' ),
					array( 'fas fa-football-ball' => 'Football ball' ),
					array( 'fas fa-futbol' => 'Futbol' ),
					array( 'far fa-futbol' => 'Futbol Outlined' ),
					array( 'fas fa-golf-ball' => 'Golf ball' ),
					array( 'fas fa-hockey-puck' => 'Hockey puck' ),
					array( 'fas fa-quidditch' => 'Quidditch' ),
					array( 'fas fa-skating' => 'Skating' ),
					array( 'fas fa-skiing' => 'Skiing' ),
					array( 'fas fa-skiing-nordic' => 'Nordic skiing' ),
					array( 'fas fa-snowboarding' => 'Snowboarding' ),
					array( 'fas fa-table-tennis' => 'Table Tennis(ping-pong)' ),
					array( 'fas fa-volleyball-ball' => 'Volleyball ball' ),
				),
				'Status'              => array(
					array( 'fas fa-ban' => 'ban(delete, remove, trash, hide, block, stop, abort, cancel)' ),
					array( 'fas fa-battery-empty' => 'Battery Empty(power)(battery-0)' ),
					array( 'fas fa-battery-full' => 'Battery Full(power)(battery-4, battery)' ),
					array( 'fas fa-battery-half' => 'Battery 1/2 Full(power)(battery-2)' ),
					array( 'fas fa-battery-quarter' => 'Battery 1/4 Full(power)(battery-1)' ),
					array( 'fas fa-battery-three-quarters' => 'Battery 3/4 Full(power)(battery-3)' ),
					array( 'fas fa-bell' => 'bell(alert, reminder, notification)' ),
					array( 'far fa-bell' => 'bell outlined(alert, reminder, notification)' ),
					array( 'fas fa-bell-slash' => 'Bell Slash' ),
					array( 'far fa-bell-slash' => 'Bell Slash Outlined' ),
					array( 'fas fa-calendar' => 'Calendar(date, time, when, event)' ),
					array( 'far fa-calendar' => 'Calendar Outlined(date, time, when, event)' ),
					array( 'fas fa-calendar-alt' => 'Calendar Alt(date, time, when, event)' ),
					array( 'far fa-calendar-alt' => 'Calendar Alt Outlined(date, time, when, event)' ),
					array( 'fas fa-calendar-check' => 'Calendar Check(ok)' ),
					array( 'far fa-calendar-check' => 'Calendar Check Outlined(ok)' ),
					array( 'fas fa-calendar-day' => 'Calendar Day' ),
					array( 'fas fa-calendar-minus' => 'Calendar Minus' ),
					array( 'far fa-calendar-minus' => 'Calendar Minus Outlined' ),
					array( 'fas fa-calendar-plus' => 'Calendar Plus' ),
					array( 'far fa-calendar-plus' => 'Calendar Plus Outlined' ),
					array( 'fas fa-calendar-times' => 'Calendar Times' ),
					array( 'far fa-calendar-times' => 'Calendar Times Outlined' ),
					array( 'fas fa-calendar-week' => 'Calendar Week' ),
					array( 'fas fa-cart-arrow-down' => 'Shopping Cart Arrow Down(shopping)' ),
					array( 'fas fa-cart-plus' => 'Add to Shopping Cart(add, shopping)' ),
					array( 'fas fa-comment' => 'Comment(speech, notification, note, chat, bubble, feedback, message, texting, sms, conversation)' ),
					array( 'far fa-comment' => 'Comment Outlined(speech, notification, note, chat, bubble, feedback, message, texting, sms, conversation)' ),
					array( 'fas fa-comment-alt' => 'Comment Alt(speech, notification, note, chat, bubble, feedback, message, texting, sms, conversation)' ),
					array( 'far fa-comment-alt' => 'Comment Alt Outlined(speech, notification, note, chat, bubble, feedback, message, texting, sms, conversation)' ),
					array( 'fas fa-comment-slash' => 'Comment slash(speech, notification, note, chat, bubble, feedback, message, texting, sms, conversation, mute)' ),
					array( 'fas fa-compass' => 'Compass(safari, directory, menu, location)' ),
					array( 'far fa-compass' => 'Compass Outlined(safari, directory, menu, location)' ),
					array( 'fas fa-door-closed' => 'Door closed' ),
					array( 'fas fa-door-open' => 'Door open' ),
					array( 'fas fa-exclamation' => 'exclamation(warning, error, problem, notification, notify, alert)' ),
					array( 'fas fa-exclamation-circle' => 'Exclamation Circle(warning, error, problem, notification, alert)' ),
					array( 'fas fa-exclamation-triangle' => 'Exclamation Triangle(warning, error, problem, notification, alert)(warning)' ),
					array( 'fas fa-eye' => 'Eye(show, visible, views)' ),
					array( 'far fa-eye' => 'Eye Outlined(show, visible, views)' ),
					array( 'fas fa-eye-slash' => 'Eye Slash(toggle, show, hide, visible, visiblity, views)' ),
					array( 'far fa-eye-slash' => 'Eye Slash Outlined(toggle, show, hide, visible, visiblity, views)' ),
					array( 'fas fa-file' => 'File(new, page, pdf, document)' ),
					array( 'far fa-file' => 'File Outlined(new, page, pdf, document)' ),
					array( 'fas fa-file-alt' => 'File Alt(new, page, pdf, document)' ),
					array( 'far fa-file-alt' => 'File Alt Outlined(new, page, pdf, document)' ),
					array( 'fas fa-folder' => 'Folder' ),
					array( 'far fa-folder' => 'Folder Outlined' ),
					array( 'fas fa-folder-open' => 'Folder Open' ),
					array( 'far fa-folder-open' => 'Folder Open Outlined' ),
					array( 'fas fa-gas-pump' => 'Gas Pump' ),
					array( 'fas fa-info' => 'Info(help, information, more, details)' ),
					array( 'fas fa-info-circle' => 'Info Circle(help, information, more, details)' ),
					array( 'fas fa-lightbulb' => 'Lightbulb(idea, inspiration)' ),
					array( 'far fa-lightbulb' => 'Lightbulb Outlined(idea, inspiration)' ),
					array( 'fas fa-lock' => 'lock(protect, admin, security)' ),
					array( 'fas fa-lock-open' => 'lock open(protect, admin, security)' ),
					array( 'fas fa-map-marker' => 'map-marker(map, pin, location, coordinates, localize, address, travel, where, place)' ),
					array( 'fas fa-map-marker-alt' => 'map-marker-alt(map, pin, location, coordinates, localize, address, travel, where, place)' ),
					array( 'fas fa-microphone' => 'microphone(record, voice, sound)' ),
					array( 'fas fa-microphone-alt' => 'microphone alt(record, voice, sound)' ),
					array( 'fas fa-microphone-alt-slash' => 'Microphone Alt Slash(record, voice, sound, mute)' ),
					array( 'fas fa-microphone-slash' => 'Microphone Slash(record, voice, sound, mute)' ),
					array( 'fas fa-minus' => 'minus(hide, minify, delete, remove, trash, hide, collapse)' ),
					array( 'fas fa-minus-circle' => 'Minus Circle(delete, remove, trash, hide)' ),
					array( 'fas fa-minus-square' => 'Minus Square(hide, minify, delete, remove, trash, hide, collapse)' ),
					array( 'far fa-minus-square' => 'Minus Square Outlined(hide, minify, delete, remove, trash, hide, collapse)' ),
					array( 'fas fa-parking' => 'Parking' ),
					array( 'fas fa-phone' => 'Phone(call, voice, number, support, earphone, telephone)' ),
					array( 'fas fa-phone-slash' => 'Phone Slash(call, voice, sound, mute)' ),
					array( 'fas fa-plus' => 'plus(add, new, create, expand)' ),
					array( 'fas fa-plus-circle' => 'Plus Circle(add, new, create, expand)' ),
					array( 'fas fa-plus-square' => 'Plus Square(add, new, create, expand)' ),
					array( 'far fa-plus-square' => 'Plus Square Outlined(add, new, create, expand)' ),
					array( 'fas fa-print' => 'Print' ),
					array( 'fas fa-question' => 'Question(help, information, unknown, support)' ),
					array( 'fas fa-question-circle' => 'Question Circle' ),
					array( 'far fa-question-circle' => 'Question Circle Outlined' ),
					array( 'fas fa-shield-alt' => 'shield alt(award, achievement, security, winner)' ),
					array( 'fas fa-shopping-cart' => 'shopping-cart(checkout, buy, purchase, payment)' ),
					array( 'fas fa-sign-in-alt' => 'Sign In(enter, join, log in, login, sign up, sign in, signin, signup, arrow)' ),
					array( 'fas fa-sign-out-alt' => 'Sign Out(log out, logout, leave, exit, arrow)' ),
					array( 'fas fa-signal' => 'signal(graph, bars)' ),
					array( 'fas fa-smoking-ban' => 'Smoking Ban' ),
					array( 'fas fa-star' => 'Star(award, achievement, night, rating, score, favorite)' ),
					array( 'far fa-star' => 'Star Outlined(award, achievement, night, rating, score, favorite)' ),
					array( 'fas fa-star-half' => 'star-half(award, achievement, rating, score)' ),
					array( 'far fa-star-half' => 'Star Half Outlined(award, achievement, rating, score)(star-half-empty, star-half-full)' ),
					array( 'fas fa-star-half-alt' => 'star-half alt(award, achievement, rating, score)' ),
					array( 'fas fa-stream' => 'Stream' ),
					array( 'fas fa-thermometer-empty' => 'Thermometer Empty(thermometer-0)' ),
					array( 'fas fa-thermometer-full' => 'Thermometer Full(thermometer-4, thermometer)' ),
					array( 'fas fa-thermometer-half' => 'Thermometer 1/2 Full(thermometer-2)' ),
					array( 'fas fa-thermometer-quarter' => 'Thermometer 1/4 Full(thermometer-1)' ),
					array( 'fas fa-thermometer-three-quarters' => 'Thermometer 3/4 Full(thermometer-3)' ),
					array( 'fas fa-thumbs-down' => 'thumbs-down(dislike, disapprove, disagree, hand)' ),
					array( 'far fa-thumbs-down' => 'thumbs-down outlined(dislike, disapprove, disagree, hand)' ),
					array( 'fas fa-thumbs-up' => 'thumbs-up(like, favorite, approve, agree, hand)' ),
					array( 'far fa-thumbs-up' => 'thumbs-up outlined(like, favorite, approve, agree, hand)' ),
					array( 'fas fa-tint' => 'tint(raindrop, waterdrop, drop, droplet)' ),
					array( 'fas fa-tint-slash' => 'tint slash(raindrop, waterdrop, drop, droplet, mute)' ),
					array( 'fas fa-toggle-off' => 'Toggle Off' ),
					array( 'fas fa-toggle-on' => 'Toggle On' ),
					array( 'fas fa-unlock' => 'unlock(protect, admin, password, lock)' ),
					array( 'fas fa-unlock-alt' => 'Unlock Alt(protect, admin, password, lock)' ),
					array( 'fas fa-user' => 'User(person, man, head, profile)' ),
					array( 'fas fa-user-alt' => 'User Alt(person, man, head, profile)' ),
					array( 'fas fa-user-alt-slash' => 'User Alt slash(person, man)' ),
					array( 'fas fa-user-slash' => 'User slash(person, man)' ),
					array( 'fas fa-video' => 'Video(film, movie, record, mute)' ),
					array( 'fas fa-video-slash' => 'Video Slash(film, movie, record, mute)' ),
					array( 'fas fa-volume-down' => 'volume-down(audio, lower, quieter, sound, music)' ),
					array( 'fas fa-volume-mute' => 'volume-mute(audio, mute, sound, music)' ),
					array( 'fas fa-volume-off' => 'volume-off(audio, mute, sound, music)' ),
					array( 'fas fa-volume-up' => 'volume-up(audio, higher, louder, sound, music)' ),
					array( 'fas fa-wifi' => 'Wifi' ),
				),
				'Tabletop Gaming'     => array(
					array( 'fab fa-acquisitions-incorporated' => 'Acquisitions Incorporated' ),
					array( 'fas fa-book-dead' => 'Book Dead(skull-crossbones)' ),
					array( 'fab fa-critical-role' => 'Critical Role' ),
					array( 'fab fa-d-and-d' => 'D and D' ),
					array( 'fab fa-d-and-d-beyond' => 'D and D Beyond' ),
					array( 'fas fa-dice-d20' => 'dice d20' ),
					array( 'fas fa-dice-d6' => 'dice d6' ),
					array( 'fas fa-dragon' => 'Dragon' ),
					array( 'fas fa-dungeon' => 'Dungeon' ),
					array( 'fab fa-fantasy-flight-games' => 'Fantasy Flight Games' ),
					array( 'fas fa-fist-raised' => 'First Raised (Hand)' ),
					array( 'fas fa-hat-wizard' => 'Hat Wizard' ),
					array( 'fab fa-penny-arcade' => 'Penny Arcade' ),
					array( 'fas fa-ring' => 'Ring' ),
					array( 'fas fa-scroll' => 'Scroll' ),
					array( 'fas fa-skull-crossbones' => 'Skull Crossbones' ),
					array( 'fab fa-wizards-of-the-coast' => 'Wizards of the coast' ),
				),
				'Travel'              => array(
					array( 'fas fa-archway' => 'Archway' ),
					array( 'fas fa-atlas' => 'Atlas' ),
					array( 'fas fa-bed' => 'Bed(travel)(hotel)' ),
					array( 'fas fa-bus' => 'Bus(vehicle)' ),
					array( 'fas fa-bus-alt' => 'Bus Alt(vehicle)' ),
					array( 'fas fa-cocktail' => 'Cocktail' ),
					array( 'fas fa-concierge-bell' => 'Concierge Bell' ),
					array( 'fas fa-dumbbell' => 'Dumbbell' ),
					array( 'fas fa-glass-martini' => 'Glass Martini' ),
					array( 'fas fa-glass-martini-alt' => 'Glass Martini Alt' ),
					array( 'fas fa-globe-africa' => 'Globe Africa(map, earth, global, place, country, continent)' ),
					array( 'fas fa-globe-americas' => 'Globe Americas(map, earth, global, place, country, continent)' ),
					array( 'fas fa-globe-asia' => 'Globe Asia(map, earth, global, place, country, continent)' ),
					array( 'fas fa-globe-europe' => 'Globe Eruope(map, earth, global, place, country, continent)' ),
					array( 'fas fa-hot-tub' => 'Hot Tub' ),
					array( 'fas fa-hotel' => 'Hotel' ),
					array( 'fas fa-luggage-cart' => 'Luggage cart' ),
					array( 'fas fa-map' => 'Map' ),
					array( 'far fa-map' => 'Map Outlined' ),
					array( 'fas fa-map-marker' => 'map-marker(map, pin, location, coordinates, localize, address, travel, where, place)' ),
					array( 'fas fa-map-marker-alt' => 'map-marker-alt(map, pin, location, coordinates, localize, address, travel, where, place)' ),
					array( 'fas fa-monument' => 'Monument' ),
					array( 'fas fa-passport' => 'Passport' ),
					array( 'fas fa-plane' => 'plane(travel, trip, location, destination, airplane, fly, mode)' ),
					array( 'fas fa-plane-arrival' => 'Plane Arrival' ),
					array( 'fas fa-plane-departure' => 'Plane Departure' ),
					array( 'fas fa-shuttle-van' => 'Shuttle Van' ),
					array( 'fas fa-spa' => 'Spa' ),
					array( 'fas fa-suitcase' => 'Suitcase(trip, luggage, travel, move, baggage)' ),
					array( 'fas fa-suitcase-rolling' => 'Suitcase Rolling' ),
					array( 'fas fa-swimmer' => 'Swimmer' ),
					array( 'fas fa-swimming-pool' => 'Swimming Pool' ),
					array( 'fas fa-taxi' => 'Taxi(vehicle)(cab)' ),
					array( 'fas fa-tram' => 'Tram' ),
					array( 'fas fa-umbrella-beach' => 'Umbrella Beach' ),
					array( 'fas fa-wine-glass' => 'Wine Glass' ),
					array( 'fas fa-wine-glass-alt' => 'Wine Glass Alt' ),
				),
				'Users & People'      => array(
					array( 'fab fa-accessible-icon' => 'Accessible(handicap, person, going)' ),
					array( 'fas fa-address-book' => 'Address Book' ),
					array( 'far fa-address-book' => 'Address Book Outlined' ),
					array( 'fas fa-address-card' => 'Address Card(vcard)' ),
					array( 'far fa-address-card' => 'Address Card Outlined(vcard)' ),
					array( 'fas fa-baby' => 'Baby' ),
					array( 'fas fa-bed' => 'Bed(travel)(hotel)' ),
					array( 'fas fa-blind' => 'Blind' ),
					array( 'fas fa-chalkboard-teacher' => 'Chalkboard Teacher' ),
					array( 'fas fa-child' => 'Child' ),
					array( 'fas fa-female' => 'Female(woman, user, person, profile)' ),
					array( 'fas fa-frown' => 'Frown(face, emoticon, sad, disapprove, rating)' ),
					array( 'far fa-frown' => 'Frown Outlined(face, emoticon, sad, disapprove, rating)' ),
					array( 'fas fa-hiking' => 'Hiking(main)' ),
					array( 'fas fa-id-badge' => 'Identification Badge' ),
					array( 'fas fa-id-card' => 'Identification Card(drivers-license)' ),
					array( 'far fa-id-card' => 'Identification Card Outlined(drivers-license)' ),
					array( 'fas fa-id-card-alt' => 'Identification Card Alt(drivers-license)' ),
					array( 'fas fa-male' => 'Male(man, user, person, profile)' ),
					array( 'fas fa-meh' => 'Meh(face, emoticon, rating, neutral)' ),
					array( 'far fa-meh' => 'Meh Outlined(face, emoticon, rating, neutral)' ),
					array( 'fas fa-people-carry' => 'Peoples carrying' ),
					array( 'fas fa-person-booth' => 'Person Booth' ),
					array( 'fas fa-poo' => 'Poo' ),
					array( 'fas fa-portrait' => 'Portrait' ),
					array( 'fas fa-power-off' => 'Power Off(on)' ),
					array( 'fas fa-pray' => 'Pray(person, man)' ),
					array( 'fas fa-restroom' => 'Restroom' ),
					array( 'fas fa-running' => 'Running' ),
					array( 'fas fa-skating' => 'Skating' ),
					array( 'fas fa-skiing' => 'Skiing' ),
					array( 'fas fa-skiing-nordic' => 'Nordic skiing' ),
					array( 'fas fa-smile' => 'Smile(face, emoticon, happy, approve, satisfied, rating)' ),
					array( 'far fa-smile' => 'Smile Outlined(face, emoticon, happy, approve, satisfied, rating)' ),
					array( 'fas fa-snowboarding' => 'Snowboarding' ),
					array( 'fas fa-street-view' => 'Street View(map)' ),
					array( 'fas fa-swimmer' => 'Swimmer' ),
					array( 'fas fa-user' => 'User(person, man, head, profile)' ),
					array( 'far fa-user' => 'User Outlined(person, man, head, profile)' ),
					array( 'fas fa-user-alt' => 'User Alt(person, man, head, profile)' ),
					array( 'fas fa-user-alt-slash' => 'User Alt slash(person, man)' ),
					array( 'fas fa-user-astronaut' => 'User Astronaut' ),
					array( 'fas fa-user-check' => 'User Check' ),
					array( 'fas fa-user-circle' => 'User Circle' ),
					array( 'far fa-user-circle' => 'User Circle Outlined' ),
					array( 'fas fa-user-clock' => 'User Clock' ),
					array( 'fas fa-user-cog' => 'User Settings(cog)' ),
					array( 'fas fa-user-edit' => 'Edit User' ),
					array( 'fas fa-user-friends' => 'User Friends' ),
					array( 'fas fa-user-graduate' => 'User Graduate' ),
					array( 'fas fa-user-injured' => 'Injured User' ),
					array( 'fas fa-user-lock' => 'User Lock' ),
					array( 'fas fa-user-md' => 'user-md(doctor, profile, medical, nurse)' ),
					array( 'fas fa-user-minus' => 'User Minus' ),
					array( 'fas fa-user-ninja' => 'User Ninja' ),
					array( 'fas fa-user-nurse' => 'User nurse' ),
					array( 'fas fa-user-plus' => 'Add User(sign up, signup)' ),
					array( 'fas fa-user-secret' => 'User Secret(whisper, spy, incognito, privacy)' ),
					array( 'fas fa-user-shield' => 'User Shield' ),
					array( 'fas fa-user-slash' => 'User Slash(person, man)' ),
					array( 'fas fa-user-tag' => 'User Tag' ),
					array( 'fas fa-user-tie' => 'User tie' ),
					array( 'fas fa-user-times' => 'Remove User' ),
					array( 'fas fa-users' => 'Users(people, profiles, persons)(group)' ),
					array( 'fas fa-users-cog' => 'Users Cog(people, profiles, persons)(group)' ),
					array( 'fas fa-walking' => 'Walking(person, man)' ),
					array( 'fas fa-wheelchair' => 'Wheelchair(handicap, person)' ),
				),
				'Vehicles'            => array(
					array( 'fab fa-accessible-icon' => 'Accessible(handicap, person, going)' ),
					array( 'fas fa-ambulance' => 'ambulance(vehicle, support, help)' ),
					array( 'fas fa-baby-carriage' => 'Baby Carriage' ),
					array( 'fas fa-bicycle' => 'Bicycle(vehicle, bike)' ),
					array( 'fas fa-bus' => 'Bus(vehicle)' ),
					array( 'fas fa-bus-alt' => 'Bus Alt(vehicle)' ),
					array( 'fas fa-car' => 'Car(vehicle)(automobile)' ),
					array( 'fas fa-car-alt' => 'Car Alt(vehicle)(automobile)' ),
					array( 'fas fa-car-crash' => 'Car Crash' ),
					array( 'fas fa-car-side' => 'Car Side' ),
					array( 'fas fa-fighter-jet' => 'fighter-jet(fly, plane, airplane, quick, fast, travel)' ),
					array( 'fas fa-helicopter' => 'Helicopter' ),
					array( 'fas fa-horse' => 'Horse' ),
					array( 'fas fa-motorcycle' => 'Motorcycle(vehicle, bike)' ),
					array( 'fas fa-paper-plane' => 'Paper Plane(send)' ),
					array( 'far fa-paper-plane' => 'Paper Plane Outlined(send)' ),
					array( 'fas fa-plane' => 'plane(travel, trip, location, destination, airplane, fly, mode)' ),
					array( 'fas fa-rocket' => 'rocket(app)' ),
					array( 'fas fa-ship' => 'Ship(boat, sea)' ),
					array( 'fas fa-shopping-cart' => 'shopping-cart(checkout, buy, purchase, payment)' ),
					array( 'fas fa-shuttle-van' => 'Shuttle Van' ),
					array( 'fas fa-sleigh' => 'Sleigh' ),
					array( 'fas fa-snowplow' => 'Snowplow' ),
					array( 'fas fa-space-shuttle' => 'Space Shuttle' ),
					array( 'fas fa-subway' => 'Subway' ),
					array( 'fas fa-taxi' => 'Taxi(vehicle)(cab)' ),
					array( 'fas fa-tractor' => 'Tractor(vehicle)' ),
					array( 'fas fa-train' => 'Train' ),
					array( 'fas fa-tram' => 'Tram' ),
					array( 'fas fa-truck' => 'truck(shipping)' ),
					array( 'fas fa-truck-monster' => 'Truck Monster' ),
					array( 'fas fa-truck-pickup' => 'Truck Pickup' ),
					array( 'fas fa-wheelchair' => 'Wheelchair(handicap, person)' ),
				),
				'Weather'             => array(
					array( 'fas fa-bolt' => 'Lightning Bolt(lightning, weather)(flash)' ),
					array( 'fas fa-cloud' => 'Cloud(save)' ),
					array( 'fas fa-cloud-meatball' => 'Cloud Meatball' ),
					array( 'fas fa-cloud-moon' => 'Cloud Moon' ),
					array( 'fas fa-cloud-moon-rain' => 'Cloud Moon Rain' ),
					array( 'fas fa-cloud-rain' => 'Cloud Rain' ),
					array( 'fas fa-cloud-showers-heavy' => 'Cloud Showers Heavy' ),
					array( 'fas fa-cloud-sun' => 'Cloud Sun' ),
					array( 'fas fa-cloud-sun-rain' => 'Cloud Sun Rain' ),
					array( 'fas fa-meteor' => 'Meteor' ),
					array( 'fas fa-moon' => 'Moon(night, darker, contrast)' ),
					array( 'far fa-moon' => 'Moon Outlined(night, darker, contrast)' ),
					array( 'fas fa-poo-storm' => 'Poo Storm' ),
					array( 'fas fa-rainbow' => 'Rainbow' ),
					array( 'fas fa-snowflake' => 'Snowflake' ),
					array( 'far fa-snowflake' => 'Snowflake Outlined' ),
					array( 'fas fa-sun' => 'Sun(weather, contrast, lighter, brighten, day)' ),
					array( 'far fa-sun' => 'Sun Outlined(weather, contrast, lighter, brighten, day)' ),
					array( 'fas fa-temperature-high' => 'High Temperature' ),
					array( 'fas fa-temperature-low' => 'Low Temperature' ),
					array( 'fas fa-umbrella' => 'Umbrella' ),
					array( 'fas fa-water' => 'Water' ),
					array( 'fas fa-wind' => 'Wind' ),
				),
				'Winter'              => array(
					array( 'fas fa-glass-whiskey' => 'Glass Whiskey' ),
					array( 'fas fa-icicles' => 'Icicles' ),
					array( 'fas fa-igloo' => 'Igloo' ),
					array( 'fas fa-mitten' => 'Mitten' ),
					array( 'fas fa-skating' => 'Skating' ),
					array( 'fas fa-skiing' => 'Skiing' ),
					array( 'fas fa-skiing-nordic' => 'Nordic skiing' ),
					array( 'fas fa-snowboarding' => 'Snowboarding' ),
					array( 'fas fa-snowplow' => 'Snowplow' ),
					array( 'fas fa-tram' => 'Tram' ),
				),
				'Writing'             => array(
					array( 'fas fa-archive' => 'Archive(box, storage)' ),
					array( 'fas fa-blog' => 'Blog' ),
					array( 'fas fa-book' => 'Book(read, documentation)' ),
					array( 'fas fa-bookmark' => 'bookmark(save)' ),
					array( 'far fa-bookmark' => 'Bookmark Outlined(save)' ),
					array( 'fas fa-edit' => 'Edit(write, edit, update)(pencil)' ),
					array( 'far fa-edit' => 'Edit Outlined(write, edit, update)(pencil)' ),
					array( 'fas fa-envelope' => 'Envelope(email, e-mail, letter, support, mail, message, notification)' ),
					array( 'far fa-envelope' => 'Envelope Outlined(email, e-mail, letter, support, mail, message, notification)' ),
					array( 'fas fa-envelope-open' => 'Envelope Open(email, e-mail, letter, support, mail, message, notification)' ),
					array( 'far fa-envelope-open' => 'Envelope Open Outlined(email, e-mail, letter, support, mail, message, notification)' ),
					array( 'fas fa-eraser' => 'eraser(remove, delete)' ),
					array( 'fas fa-file' => 'File(new, page, pdf, document)' ),
					array( 'far fa-file' => 'File Outlined(new, page, pdf, document)' ),
					array( 'fas fa-file-alt' => 'File Alt(new, page, pdf, document)' ),
					array( 'far fa-file-alt' => 'File Alt Outlined(new, page, pdf, document)' ),
					array( 'fas fa-folder' => 'Folder' ),
					array( 'far fa-folder' => 'Folder Outlined' ),
					array( 'fas fa-folder-open' => 'Folder Open' ),
					array( 'far fa-folder-open' => 'Folder Open Outlined' ),
					array( 'fas fa-keyboard' => 'Keyboard(type, input)' ),
					array( 'far fa-keyboard' => 'Keyboard Outlined(type, input)' ),
					array( 'fas fa-newspaper' => 'Newspaper(press)' ),
					array( 'far fa-newspaper' => 'Newspaper Outlined(press)' ),
					array( 'fas fa-paper-plane' => 'Paper Plane(send)' ),
					array( 'far fa-paper-plane' => 'Paper Plane Outlined(send)' ),
					array( 'fas fa-paperclip' => 'Paperclip(attachment)' ),
					array( 'fas fa-paragraph' => 'paragraph' ),
					array( 'fas fa-pen' => 'Pen(write, edit, update)' ),
					array( 'fas fa-pen-alt' => 'Pen Alt(write, edit, update)' ),
					array( 'fas fa-pen-square' => 'Pen Square(write, edit, update)' ),
					array( 'fas fa-pencil-alt' => 'Pencil Alt(write, edit, update)' ),
					array( 'fas fa-quote-left' => 'quote-left' ),
					array( 'fas fa-quote-right' => 'quote-right' ),
					array( 'fas fa-sticky-note' => 'Sticky Note' ),
					array( 'far fa-sticky-note' => 'Sticky Note Outlined' ),
					array( 'fas fa-thumbtack' => 'Thumbtack' ),
				),
				'Brands'              => array(
					array( 'fab fa-500px' => '500px' ),
					array( 'fab fa-accessible-icon' => 'Accessible(handicap, person, going)' ),
					array( 'fab fa-accusoft' => 'Accusoft' ),
					array( 'fab fa-acquisitions-incorporated' => 'Acquisitions Incorporated' ),
					array( 'fab fa-adn' => 'App.net' ),
					array( 'fab fa-adobe' => 'Adobe' ),
					array( 'fab fa-adversal' => 'Adversarial ' ),
					array( 'fab fa-affiliatetheme' => 'Affiliate Theme' ),
					array( 'fab fa-algolia' => 'Algolia' ),
					array( 'fab fa-alipay' => 'Alipay' ),
					array( 'fab fa-amazon' => 'Amazon' ),
					array( 'fab fa-amazon-pay' => 'Amazon Pay' ),
					array( 'fab fa-amilia' => 'Amilia' ),
					array( 'fab fa-android' => 'Android(robot)' ),
					array( 'fab fa-angellist' => 'AngelList' ),
					array( 'fab fa-angrycreative' => 'Angry Creative' ),
					array( 'fab fa-angular' => 'Angular' ),
					array( 'fab fa-app-store' => 'App Store' ),
					array( 'fab fa-app-store-ios' => 'iOS App Store' ),
					array( 'fab fa-apper' => 'Apper' ),
					array( 'fab fa-apple' => 'Apple(osx, food)' ),
					array( 'fab fa-apple-pay' => 'Apple Pay' ),
					array( 'fab fa-artstation' => 'ArtStation' ),
					array( 'fab fa-asymmetrik' => 'Asymmetrik' ),
					array( 'fab fa-atlassian' => 'Atlassian' ),
					array( 'fab fa-audible' => 'Audible' ),
					array( 'fab fa-autoprefixer' => 'Autoprefixer' ),
					array( 'fab fa-avianex' => 'avianex' ),
					array( 'fab fa-aviato' => 'Aviato' ),
					array( 'fab fa-aws' => 'Amazon Web Services(AWS)' ),
					array( 'fab fa-bandcamp' => 'Bandcamp' ),
					array( 'fab fa-behance' => 'Behance' ),
					array( 'fab fa-behance-square' => 'Behance Square' ),
					array( 'fab fa-bimobject' => 'BIMobject' ),
					array( 'fab fa-bitbucket' => 'Bitbucket(git)' ),
					array( 'fab fa-bitcoin' => 'Bitcoin (BTC)(bitcoin)' ),
					array( 'fab fa-bity' => 'Bity' ),
					array( 'fab fa-black-tie' => 'Font Awesome Black Tie' ),
					array( 'fab fa-blackberry' => 'BlackBerry' ),
					array( 'fab fa-blogger' => 'Blogger' ),
					array( 'fab fa-blogger-b' => 'Blogger B' ),
					array( 'fab fa-bluetooth' => 'Bluetooth' ),
					array( 'fab fa-bluetooth-b' => 'Bluetooth' ),
					array( 'fab fa-btc' => 'Bitcoin (BTC)(bitcoin)' ),
					array( 'fab fa-buromobelexperte' => 'Buromobel-Experte' ),
					array( 'fab fa-buysellads' => 'BuySellAds' ),
					array( 'fab fa-canadian-maple-leaf' => 'Canadian Maple Leaf' ),
					array( 'fab fa-cc-amazon-pay' => 'Amazon Pay Credit Card' ),
					array( 'fab fa-cc-amex' => 'American Express Credit Card(amex)' ),
					array( 'fab fa-cc-apple-pay' => 'Apple Pay Credit Card' ),
					array( 'fab fa-cc-diners-club' => 'Diner\'s Club Credit Card' ),
					array( 'fab fa-cc-discover' => 'Discover Credit Card' ),
					array( 'fab fa-cc-jcb' => 'JCB Credit Card' ),
					array( 'fab fa-cc-mastercard' => 'MasterCard Credit Card' ),
					array( 'fab fa-cc-paypal' => 'Paypal Credit Card' ),
					array( 'fab fa-cc-stripe' => 'Stripe Credit Card' ),
					array( 'fab fa-cc-visa' => 'Visa Credit Card' ),
					array( 'fab fa-centercode' => 'Centercode' ),
					array( 'fab fa-centos' => 'CentOS' ),
					array( 'fab fa-chrome' => 'Chrome(browser)' ),
					array( 'fab fa-cloudscale' => 'Cloudscale' ),
					array( 'fab fa-cloudsmith' => 'Cloudsmith' ),
					array( 'fab fa-cloudversify' => 'Cloudversify' ),
					array( 'fab fa-codepen' => 'Codepen' ),
					array( 'fab fa-codiepie' => 'Codie Pie' ),
					array( 'fab fa-confluence' => 'Confluence' ),
					array( 'fab fa-connectdevelop' => 'Connect Develop' ),
					array( 'fab fa-contao' => 'Contao' ),
					array( 'fab fa-cpanel' => 'cPanel(Control Panel)' ),
					array( 'fab fa-creative-commons' => 'Creative Commons' ),
					array( 'fab fa-creative-commons-by' => 'Creative Commons By' ),
					array( 'fab fa-creative-commons-nc' => 'Creative Commons NC' ),
					array( 'fab fa-creative-commons-nc-eu' => 'Creative Commons NC EU' ),
					array( 'fab fa-creative-commons-nc-jp' => 'Creative Commons NC JP' ),
					array( 'fab fa-creative-commons-nd' => 'Creative Commons ND' ),
					array( 'fab fa-creative-commons-pd' => 'Creative Commons PD' ),
					array( 'fab fa-creative-commons-pd-alt' => 'Creative Commons PD Alt' ),
					array( 'fab fa-creative-commons-remix' => 'Creative Commons Remix' ),
					array( 'fab fa-creative-commons-sa' => 'Creative Commons SA' ),
					array( 'fab fa-creative-commons-sampling' => 'Creative Commons Sampling' ),
					array( 'fab fa-creative-commons-sampling-plus' => 'Creative Commons Sampling Plus' ),
					array( 'fab fa-creative-commons-share' => 'Creative Commons Share' ),
					array( 'fab fa-creative-commons-zero' => 'Creative Commons Zero' ),
					array( 'fab fa-critical-role' => 'Critical Role' ),
					array( 'fab fa-css3' => 'CSS 3 Logo(code)' ),
					array( 'fab fa-css3-alt' => 'CSS 3 Alt(code)' ),
					array( 'fab fa-cuttlefish' => 'Cuttlefish' ),
					array( 'fab fa-d-and-d' => 'D and D' ),
					array( 'fab fa-d-and-d-beyond' => 'D and D Beyond' ),
					array( 'fab fa-dashcube' => 'DashCube' ),
					array( 'fab fa-delicious' => 'Delicious Logo' ),
					array( 'fab fa-deploydog' => 'DeployDog' ),
					array( 'fab fa-deskpro' => 'Deskpro' ),
					array( 'fab fa-dev' => 'Dev' ),
					array( 'fab fa-deviantart' => 'deviantART' ),
					array( 'fab fa-dhl' => 'DHL Express' ),
					array( 'fab fa-diaspora' => 'Diaspora' ),
					array( 'fab fa-digg' => 'Digg Logo' ),
					array( 'fab fa-digital-ocean' => 'Digital Ocean' ),
					array( 'fab fa-discord' => 'Discord' ),
					array( 'fab fa-discourse' => 'Discourse' ),
					array( 'fab fa-dochub' => 'Dochub' ),
					array( 'fab fa-docker' => 'Docker' ),
					array( 'fab fa-draft2digital' => 'Draft2Digital' ),
					array( 'fab fa-dribbble' => 'Dribbble' ),
					array( 'fab fa-dribbble-square' => 'Dribbble Square' ),
					array( 'fab fa-dropbox' => 'Dropbox' ),
					array( 'fab fa-drupal' => 'Drupal Logo' ),
					array( 'fab fa-dyalog' => 'Dyalog' ),
					array( 'fab fa-earlybirds' => 'EarlyBirds' ),
					array( 'fab fa-ebay' => 'eBay' ),
					array( 'fab fa-edge' => 'Edge Browser(browser, ie)' ),
					array( 'fab fa-elementor' => 'Elementor' ),
					array( 'fab fa-ello' => 'Ello' ),
					array( 'fab fa-ember' => 'Ember' ),
					array( 'fab fa-empire' => 'Galactic Empire(ge)' ),
					array( 'fab fa-envira' => 'Envira Gallery(leaf)' ),
					array( 'fab fa-erlang' => 'Erland' ),
					array( 'fab fa-ethereum' => 'Ethereum' ),
					array( 'fab fa-etsy' => 'Etsy' ),
					array( 'fab fa-expeditedssl' => 'ExpeditedSSL' ),
					array( 'fab fa-meta' => 'Meta (Facebook)' ),
					array( 'fab fa-facebook' => 'Facebook(social network)' ),
					array( 'fab fa-facebook-f' => 'Facebook F(social network)' ),
					array( 'fab fa-facebook-messenger' => 'Facebook Messenger' ),
					array( 'fab fa-facebook-square' => 'Facebook Square(social network)' ),
					array( 'fab fa-fantasy-flight-games' => 'Fantasy Flight Games' ),
					array( 'fab fa-fedex' => 'Fedex' ),
					array( 'fab fa-fedora' => 'Fedora' ),
					array( 'fab fa-figma' => 'Figma' ),
					array( 'fab fa-firefox' => 'Firefox(browser)' ),
					array( 'fab fa-first-order' => 'First Order' ),
					array( 'fab fa-first-order-alt' => 'First Order Alt' ),
					array( 'fab fa-firstdraft' => 'First Draft' ),
					array( 'fab fa-flickr' => 'Flickr' ),
					array( 'fab fa-flipboard' => 'Flipboard' ),
					array( 'fab fa-fly' => 'Fly' ),
					array( 'fab fa-font-awesome' => 'Font Awesome(fa)' ),
					array( 'fab fa-font-awesome-alt' => 'Font Awesome Alt(fa)' ),
					array( 'fab fa-font-awesome-flag' => 'Font Awesome Flag(fa)' ),
					array( 'fab fa-fonticons' => 'Fonticons' ),
					array( 'fab fa-fonticons-fi' => 'Fonticons FI' ),
					array( 'fab fa-fort-awesome' => 'Fort Awesome' ),
					array( 'fab fa-fort-awesome-alt' => 'Fort Awesome Alt' ),
					array( 'fab fa-forumbee' => 'Forumbee' ),
					array( 'fab fa-foursquare' => 'Foursquare' ),
					array( 'fab fa-free-code-camp' => 'Free Code Camp' ),
					array( 'fab fa-freebsd' => 'FreeBSD' ),
					array( 'fab fa-fulcrum' => 'Fulcrum' ),
					array( 'fab fa-galactic-republic' => 'Galactic Republic' ),
					array( 'fab fa-galactic-senate' => 'Galactic Senate' ),
					array( 'fab fa-get-pocket' => 'Get Pocket' ),
					array( 'fab fa-gg' => 'GG Currency' ),
					array( 'fab fa-gg-circle' => 'GG Currency Circle' ),
					array( 'fab fa-git' => 'Git' ),
					array( 'fab fa-git-square' => 'Git Square' ),
					array( 'fab fa-github' => 'GitHub(octocat)' ),
					array( 'fab fa-github-alt' => 'GitHub Alt(octocat)' ),
					array( 'fab fa-github-square' => 'GitHub Square(octocat)' ),
					array( 'fab fa-gitkraken' => 'Gitkraken' ),
					array( 'fab fa-gitlab' => 'GitLab' ),
					array( 'fab fa-gitter' => 'Gitter' ),
					array( 'fab fa-glide' => 'Glide' ),
					array( 'fab fa-glide-g' => 'Glide G' ),
					array( 'fab fa-gofore' => 'Gofore' ),
					array( 'fab fa-goodreads' => 'GoogleReads' ),
					array( 'fab fa-goodreads-g' => 'GoogleReads G' ),
					array( 'fab fa-google' => 'Google Logo' ),
					array( 'fab fa-google-drive' => 'Google Drive' ),
					array( 'fab fa-google-play' => 'Google Play' ),
					array( 'fab fa-google-plus' => 'Google Plus(social network)' ),
					array( 'fab fa-google-plus-g' => 'Google Plus G(social network)' ),
					array( 'fab fa-google-plus-square' => 'Google Plus Square(social network)' ),
					array( 'fab fa-google-wallet' => 'Google Wallet' ),
					array( 'fab fa-gratipay' => 'Gratipay (Gittip)(heart, like, favorite, love)(gittip)' ),
					array( 'fab fa-grav' => 'Grav' ),
					array( 'fab fa-gripfire' => 'Gripfire' ),
					array( 'fab fa-grunt' => 'Grunt' ),
					array( 'fab fa-gulp' => 'Gulp' ),
					array( 'fab fa-hacker-news' => 'Hacker News(y-combinator-square, yc-square)' ),
					array( 'fab fa-hacker-news-square' => 'Hacker News Square(y-combinator-square, yc-square)' ),
					array( 'fab fa-hackerrank' => 'HackerRank' ),
					array( 'fab fa-hips' => 'Hips' ),
					array( 'fab fa-hire-a-helper' => 'Hire a Helper' ),
					array( 'fab fa-hooli' => 'Hooli' ),
					array( 'fab fa-hornbill' => 'Hornbill' ),
					array( 'fab fa-hotjar' => 'Hotjar' ),
					array( 'fab fa-houzz' => 'Houzz' ),
					array( 'fab fa-html5' => 'HTML 5 Logo' ),
					array( 'fab fa-hubspot' => 'Hubspot' ),
					array( 'fab fa-imdb' => 'IMDB' ),
					array( 'fab fa-instagram' => 'Instagram' ),
					array( 'fab fa-intercom' => 'Intercom' ),
					array( 'fab fa-internet-explorer' => 'Internet-explorer(browser, ie)' ),
					array( 'fab fa-invision' => 'Invision' ),
					array( 'fab fa-ioxhost' => 'ioxhost' ),
					array( 'fab fa-itunes' => 'iTunes' ),
					array( 'fab fa-itunes-note' => 'iTunes Note' ),
					array( 'fab fa-java' => 'Java' ),
					array( 'fab fa-jedi-order' => 'Jedi Order' ),
					array( 'fab fa-jenkins' => 'Jenkins' ),
					array( 'fab fa-jira' => 'Jira' ),
					array( 'fab fa-joget' => 'Joget' ),
					array( 'fab fa-joomla' => 'Joomla Logo' ),
					array( 'fab fa-js' => 'Javascript' ),
					array( 'fab fa-js-square' => 'Javascript Square' ),
					array( 'fab fa-jsfiddle' => 'jsFiddle' ),
					array( 'fab fa-kaggle' => 'Kaggle' ),
					array( 'fab fa-keybase' => 'Keybase' ),
					array( 'fab fa-keycdn' => 'Keycdn' ),
					array( 'fab fa-kickstarter' => 'Kickstarter' ),
					array( 'fab fa-kickstarter-k' => 'Kickstarter K' ),
					array( 'fab fa-korvue' => 'Korvue' ),
					array( 'fab fa-laravel' => 'Laravel' ),
					array( 'fab fa-lastfm' => 'last.fm' ),
					array( 'fab fa-lastfm-square' => 'last.fm Square' ),
					array( 'fab fa-leanpub' => 'Leanpub' ),
					array( 'fab fa-less' => 'LESS' ),
					array( 'fab fa-line' => 'Line' ),
					array( 'fab fa-linkedin' => 'LinkedIn' ),
					array( 'fab fa-linkedin-in' => 'LinkedIn In' ),
					array( 'fab fa-linode' => 'Linode' ),
					array( 'fab fa-linux' => 'Linux(tux)' ),
					array( 'fab fa-lyft' => 'Lyft' ),
					array( 'fab fa-magento' => 'Magento' ),
					array( 'fab fa-mailchimp' => 'Mailchimp' ),
					array( 'fab fa-mandalorian' => 'Mandalorian' ),
					array( 'fab fa-markdown' => 'Markdown' ),
					array( 'fab fa-mastodon' => 'Mastodon' ),
					array( 'fab fa-maxcdn' => 'MaxCDN' ),
					array( 'fab fa-medapps' => 'Medapps' ),
					array( 'fab fa-medium' => 'Medium' ),
					array( 'fab fa-medium-m' => 'Medium M' ),
					array( 'fab fa-medrt' => 'Medrt' ),
					array( 'fab fa-meetup' => 'Meetup' ),
					array( 'fab fa-megaport' => 'MegaPort' ),
					array( 'fab fa-mendeley' => 'Mendeley' ),
					array( 'fab fa-microsoft' => 'Microsoft' ),
					array( 'fab fa-mix' => 'Mix' ),
					array( 'fab fa-mixcloud' => 'Mixcloud' ),
					array( 'fab fa-mizuni' => 'Mizuni' ),
					array( 'fab fa-modx' => 'MODX' ),
					array( 'fab fa-monero' => 'Monero' ),
					array( 'fab fa-napster' => 'Napster' ),
					array( 'fab fa-neos' => 'Neos' ),
					array( 'fab fa-nimblr' => 'Nimblr' ),
					array( 'fab fa-nintendo-switch' => 'Nintendo Switch' ),
					array( 'fab fa-node' => 'Node' ),
					array( 'fab fa-node-js' => 'NoeJS' ),
					array( 'fab fa-npm' => 'Npm' ),
					array( 'fab fa-ns8' => 'NS8' ),
					array( 'fab fa-nutritionix' => 'Nutritionix' ),
					array( 'fab fa-odnoklassniki' => 'Odnoklassniki' ),
					array( 'fab fa-odnoklassniki-square' => 'Odnoklassniki Square' ),
					array( 'fab fa-old-republic' => 'Old Republic' ),
					array( 'fab fa-opencart' => 'OpenCart' ),
					array( 'fab fa-openid' => 'OpenID' ),
					array( 'fab fa-opera' => 'Opera' ),
					array( 'fab fa-optin-monster' => 'Optin Monster' ),
					array( 'fab fa-osi' => 'Osi' ),
					array( 'fab fa-page4' => 'Page4' ),
					array( 'fab fa-pagelines' => 'Pagelines(leaf, leaves, tree, plant, eco, nature)' ),
					array( 'fab fa-palfed' => 'Palfed' ),
					array( 'fab fa-patreon' => 'Patreon' ),
					array( 'fab fa-paypal' => 'Paypal' ),
					array( 'fab fa-penny-arcade' => 'Penny Arcade' ),
					array( 'fab fa-periscope' => 'Periscope' ),
					array( 'fab fa-phabricator' => 'Phabricator' ),
					array( 'fab fa-phoenix-framework' => 'Phoenix Framework' ),
					array( 'fab fa-phoenix-squadron' => 'Phoenix Squadron' ),
					array( 'fab fa-php' => 'Php' ),
					array( 'fab fa-pied-piper' => 'Pied Piper Logo' ),
					array( 'fab fa-pied-piper-alt' => 'Pied Piper Alternate Logo' ),
					array( 'fab fa-pied-piper-hat' => 'Pied Piper Hat' ),
					array( 'fab fa-pied-piper-pp' => 'Pied Piper PP Logo (Old)' ),
					array( 'fab fa-pinterest' => 'Pinterest' ),
					array( 'fab fa-pinterest-p' => 'Pinterest P' ),
					array( 'fab fa-pinterest-square' => 'Pinterest Square' ),
					array( 'fab fa-playstation' => 'PlayStation' ),
					array( 'fab fa-product-hunt' => 'Product Hunt' ),
					array( 'fab fa-pushed' => 'Pushed' ),
					array( 'fab fa-python' => 'Python' ),
					array( 'fab fa-qq' => 'QQ' ),
					array( 'fab fa-quinscape' => 'Quinscape' ),
					array( 'fab fa-quora' => 'Quora' ),
					array( 'fab fa-r-project' => 'r-project' ),
					array( 'fab fa-raspberry-pi' => 'Raspberry pi' ),
					array( 'fab fa-ravelry' => 'Ravelry' ),
					array( 'fab fa-react' => 'React' ),
					array( 'fab fa-reacteurope' => 'ReactEurope' ),
					array( 'fab fa-readme' => 'Readme' ),
					array( 'fab fa-rebel' => 'Rebel Alliance(ra, resistance)' ),
					array( 'fab fa-red-river' => 'Red river' ),
					array( 'fab fa-reddit' => 'reddit' ),
					array( 'fab fa-reddit-alien' => 'reddit alien' ),
					array( 'fab fa-reddit-square' => 'reddit Square' ),
					array( 'fab fa-redhat' => 'Redhat' ),
					array( 'fab fa-renren' => 'Renren' ),
					array( 'fab fa-replyd' => 'Replyd' ),
					array( 'fab fa-researchgate' => 'ResearchGate' ),
					array( 'fab fa-resolving' => 'Resolving' ),
					array( 'fab fa-rev' => 'Rev' ),
					array( 'fab fa-rocketchat' => 'Rocketchat' ),
					array( 'fab fa-rockrms' => 'Rockrms' ),
					array( 'fab fa-safari' => 'Safari(browser)' ),
					array( 'fab fa-sass' => 'Sass' ),
					array( 'fab fa-schlix' => 'Schlix' ),
					array( 'fab fa-scribd' => 'Scribd' ),
					array( 'fab fa-searchengin' => 'SearchEngin' ),
					array( 'fab fa-sellcast' => 'Sellcast' ),
					array( 'fab fa-sellsy' => 'Sellsy' ),
					array( 'fab fa-servicestack' => 'ServiceStack' ),
					array( 'fab fa-shirtsinbulk' => 'Shirts in Bulk' ),
					array( 'fab fa-shopware' => 'Shopware' ),
					array( 'fab fa-simplybuilt' => 'SimplyBuilt' ),
					array( 'fab fa-sistrix' => 'Sistrix' ),
					array( 'fab fa-sith' => 'Sith' ),
					array( 'fab fa-sketch' => 'Sketch' ),
					array( 'fab fa-skyatlas' => 'skyatlas' ),
					array( 'fab fa-skype' => 'Skype' ),
					array( 'fab fa-slack' => 'Slack Logo(hashtag, anchor, hash)' ),
					array( 'fab fa-slack-hash' => 'Slack Hash' ),
					array( 'fab fa-slideshare' => 'Slideshare' ),
					array( 'fab fa-snapchat' => 'Snapchat' ),
					array( 'fab fa-snapchat-ghost' => 'Snapchat Ghost' ),
					array( 'fab fa-snapchat-square' => 'Snapchat Square' ),
					array( 'fab fa-soundcloud' => 'SoundCloud' ),
					array( 'fab fa-sourcetree' => 'SourceTree' ),
					array( 'fab fa-speakap' => 'Speakap' ),
					array( 'fab fa-spotify' => 'Spotify' ),
					array( 'fab fa-squarespace' => 'SquareSpace' ),
					array( 'fab fa-stack-exchange' => 'Stack Exchange' ),
					array( 'fab fa-stack-overflow' => 'Stack Overflow' ),
					array( 'fab fa-staylinked' => 'StayLinked' ),
					array( 'fab fa-steam' => 'Steam' ),
					array( 'fab fa-steam-square' => 'Steam Square' ),
					array( 'fab fa-steam-symbol' => 'Steam Symbol' ),
					array( 'fab fa-sticker-mule' => 'Sticker Mule' ),
					array( 'fab fa-strava' => 'Strava' ),
					array( 'fab fa-stripe' => 'Stripe' ),
					array( 'fab fa-stripe-s' => 'Stripe S' ),
					array( 'fab fa-studiovinari' => 'StudioVinari' ),
					array( 'fab fa-stumbleupon' => 'StumbleUpon' ),
					array( 'fab fa-stumbleupon-circle' => 'StumbleUpon Circle' ),
					array( 'fab fa-superpowers' => 'Superpowers' ),
					array( 'fab fa-supple' => 'Supple' ),
					array( 'fab fa-suse' => 'Suse' ),
					array( 'fab fa-teamspeak' => 'TeamSpeak' ),
					array( 'fab fa-telegram' => 'Telegram' ),
					array( 'fab fa-telegram-plane' => 'Telegram Plane' ),
					array( 'fab fa-tencent-weibo' => 'Tencent Weibo' ),
					array( 'fab fa-the-red-yeti' => 'The Red Yeti' ),
					array( 'fab fa-themeco' => 'Themeco' ),
					array( 'fab fa-themeisle' => 'ThemeIsle' ),
					array( 'fab fa-think-peaks' => 'Think Peaks' ),
					array( 'fab fa-trade-federation' => 'Trade Federation' ),
					array( 'fab fa-trello' => 'Trello' ),
					array( 'fab fa-tripadvisor' => 'TripAdvisor' ),
					array( 'fab fa-tumblr' => 'Tumblr' ),
					array( 'fab fa-tumblr-square' => 'Tumblr Square' ),
					array( 'fab fa-twitch' => 'Twitch' ),
					array( 'fab fa-twitter' => 'Twitter(tweet, social network)' ),
					array( 'fab fa-twitter-square' => 'Twitter Square(tweet, social network)' ),
					array( 'fab fa-x-twitter' => 'X Twitter' ),
					array( 'fab fa-square-x-twitter' => 'Square X Twitter' ),
					array( 'fab fa-typo3' => 'Typo3' ),
					array( 'fab fa-uber' => 'Uber' ),
					array( 'fab fa-ubuntu' => 'Ubuntu' ),
					array( 'fab fa-uikit' => 'Uikit' ),
					array( 'fab fa-uniregistry' => 'Uniregistry' ),
					array( 'fab fa-untappd' => 'Untappd' ),
					array( 'fab fa-ups' => 'Ups' ),
					array( 'fab fa-usb' => 'USB' ),
					array( 'fab fa-usps' => 'Usps' ),
					array( 'fab fa-ussunnah' => 'Ussunnah' ),
					array( 'fab fa-vaadin' => 'Vaadin' ),
					array( 'fab fa-viacoin' => 'Viacoin' ),
					array( 'fab fa-viadeo' => 'Viadeo' ),
					array( 'fab fa-viadeo-square' => 'Viadeo Square' ),
					array( 'fab fa-viber' => 'Viber' ),
					array( 'fab fa-vimeo' => 'Vimeo' ),
					array( 'fab fa-vimeo-square' => 'Vimeo Square' ),
					array( 'fab fa-vimeo-v' => 'Vimeo V' ),
					array( 'fab fa-vine' => 'Vine' ),
					array( 'fab fa-vk' => 'VK' ),
					array( 'fab fa-vnv' => 'Vnv' ),
					array( 'fab fa-vuejs' => 'Vuejs' ),
					array( 'fab fa-weebly' => 'Weebly' ),
					array( 'fab fa-weibo' => 'Weibo' ),
					array( 'fab fa-weixin' => 'Weixin (WeChat)(wechat)' ),
					array( 'fab fa-whatsapp' => 'What\'s App' ),
					array( 'fab fa-whatsapp-square' => 'What\'s App Square' ),
					array( 'fab fa-whmcs' => 'Whmcs' ),
					array( 'fab fa-wikipedia-w' => 'Wikipedia W' ),
					array( 'fab fa-windows' => 'Windows(microsoft)' ),
					array( 'fab fa-wix' => 'Wix' ),
					array( 'fab fa-wizards-of-the-coast' => 'Wizards of the coast' ),
					array( 'fab fa-wolf-pack-battalion' => 'Wolf Pack Battalion' ),
					array( 'fab fa-wordpress' => 'WordPress Logo' ),
					array( 'fab fa-wordpress-simple' => 'WordPress Simple Logo' ),
					array( 'fab fa-wpbeginner' => 'WPBeginner' ),
					array( 'fab fa-wpexplorer' => 'WPExplorer' ),
					array( 'fab fa-wpforms' => 'WPForms' ),
					array( 'fab fa-wpressr' => 'Wpressr' ),
					array( 'fab fa-xbox' => 'Xbox' ),
					array( 'fab fa-xing' => 'Xing' ),
					array( 'fab fa-xing-square' => 'Xing Square' ),
					array( 'fab fa-y-combinator' => 'Y Combinator(yc)' ),
					array( 'fab fa-yahoo' => 'Yahoo Logo' ),
					array( 'fab fa-yandex' => 'Yandex' ),
					array( 'fab fa-yandex-international' => 'Yandex International' ),
					array( 'fab fa-yarn' => 'Yarn' ),
					array( 'fab fa-yelp' => 'Yelp' ),
					array( 'fab fa-yoast' => 'Yoast' ),
					array( 'fab fa-youtube' => 'YouTube(video, film)' ),
					array( 'fab fa-youtube-square' => 'YouTube Square(video, film)' ),
					array( 'fab fa-zhihu' => 'Zhihu' ),
				),
			);
		}

		return array_merge( $icons, $fontawesome_icons );
	}
}


/* 4.0 */
// extra shortcodes added in 4.0
if ( function_exists( 'vc_add_shortcode_param' ) ) {
	add_action( 'vc_load_default_params', function() {
		vc_add_shortcode_param( 'porto_param_heading', 'porto_param_heading_callback' );
		if ( ! class_exists( 'Ultimate_VC_Addons' ) ) {
			vc_add_shortcode_param( 'number', 'porto_number_settings_field' );
			vc_add_shortcode_param( 'datetimepicker', 'porto_datetimepicker', plugins_url( '../assets/js/bootstrap-datetimepicker.min.js', __FILE__ ) );
		}
		vc_add_shortcode_param( 'porto_boxshadow', 'porto_boxshadow_callback', plugins_url( '../assets/js/box-shadow-param.js', __FILE__ ) );
		vc_add_shortcode_param( 'porto_image_select', 'porto_image_select_callback', plugins_url( '../assets/js/porto-image-select-param.js', __FILE__ ) );
		/**
		 * Adds WPB typography param
		 *
		 * @since 6.1.0
		 *
		 */
		vc_add_shortcode_param( 'porto_typography', 'porto_typography_callback', plugins_url( '../assets/js/typography-param.js', __FILE__ ) );
		vc_add_shortcode_param( 'porto_dimension', 'porto_dimension_callback', plugins_url( '../assets/js/dimension-param.js', __FILE__ ) );
		vc_add_shortcode_param( 'porto_button_group', 'porto_button_group_callback', plugins_url( '../assets/js/button-group-param.js', __FILE__ ) );
		vc_add_shortcode_param( 'porto_number', 'porto_number_callback', plugins_url( '../assets/js/number-param.js', __FILE__ ) );
		vc_add_shortcode_param( 'porto_multiselect', 'porto_multiselect_callback' );
	}, 20 );
}

/**
 * Porto Multi Select
 *
 * adds multi select control for element option
 * follow below example of porto_multiselect control
 *
 * array(
 *      'type'       => 'porto_multiselect',
 *      'heading'    => esc_html__( 'Show Information', 'porto-functionality' ),
 *      'param_name' => 'show_info',
 *      'value'      => array(
 *          esc_html__( 'Category', 'porto-functionality' ) => 'category',
 *          esc_html__( 'Label', 'porto-functionality' )    => 'label',
 *          esc_html__( 'Price', 'porto-functionality' )    => 'price',
 *          esc_html__( 'Rating', 'porto-functionality' )   => 'rating',
 *          esc_html__( 'Attribute', 'porto-functionality' ) => 'attribute',
 *          esc_html__( 'Add to cart', 'porto-functionality' ) => 'addtocart',
 *          esc_html__( 'Compare', 'porto-functionality' )  => 'compare',
 *          esc_html__( 'Quickview', 'porto-functionality' ) => 'quickview',
 *          esc_html__( 'Wishlist', 'porto-functionality' ) => 'wishlist',
 *          esc_html__( 'Short Description', 'porto-functionality' ) => 'short_desc',
 *      ),
 *      'dependency' => array(
 *          'element'            => 'follow_theme_option',
 *          'value_not_equal_to' => 'yes',
 *      ),
 * ),
 *
 *
 * @since 6.1.0
 *
 * @param object $settings
 * @param string $value
 *
 * @return string
 */
function porto_multiselect_callback( $settings, $value ) {
	$param_name = isset( $settings['param_name'] ) ? $settings['param_name'] : '';
	$type       = isset( $settings['type'] ) ? $settings['type'] : '';
	$class      = 'porto-wpb-multiselect-container';

	if ( empty( $value ) ) {
		$value = array();
	} elseif ( ! is_array( $value ) ) {
		$value = explode( ',', $value );
	}

	$output = '<select name="' . esc_attr( $settings['param_name'] ) . '" class="porto-multiselect-container wpb_vc_param_value wpb-input wpb-select ' . esc_attr( $settings['param_name'] ) . ' ' . esc_attr( $type ) . '" value="' . esc_attr( $value ) . '"  multiple="true">';

	if ( ! empty( $settings['value'] ) ) {
		foreach ( $settings['value'] as $option_label => $option_value ) {
			$selected            = '';
			$option_value_string = (string) $option_value;
			if ( ! empty( $value ) && in_array( $option_value_string, $value ) ) {
				$selected = 'selected="selected"';
			}
			$option_class = str_replace( '#', 'hash-', $option_value );
			$output      .= '<option class="' . esc_attr( $option_class ) . '" value="' . esc_attr( $option_value ) . '" ' . $selected . '>' . htmlspecialchars( $option_label ) . '</option>';
		}
	}
	$output .= '</select>';

	return $output;
}

/**
 * Porto WPBakery Number Callback
 *
 * follow below example of porto_number control
 *
 * array(
 *      'type'        => 'porto_number',
 *      'heading'     => __( 'Icon Spacing', 'porto-functionality' ),
 *      'param_name'  => 'icon_space',
 *      'responsive'  => false,
 *
 *      ================================
 *      'units'       => array(
 *          'px',
 *          'rem',
 *          'em',
 *          '%',
 *      ),
 *      ============= OR ===============
 *      'with_units'  => true / false, // Check values including valid CSS unit.
 *      ================================
 *
 *      'dependency'  => array(
 *          'element' => 'show_icon',
 *          'not_empty'   => true,
 *      ),
 *      'selectors'   => array(
 *          '{{WRAPPER}}.btn' => 'font-size: {{VALUE}}{{UNIT}};',
 *      ),
 *      'group'       => 'Icon',
 * ),
 *
 * @since 6.1.0
 *
 * @param object $settings
 * @param string $value
 *
 * @return string
 */
function porto_number_callback( $settings, $value ) {
	$param_name    = isset( $settings['param_name'] ) ? $settings['param_name'] : '';
	$type          = isset( $settings['type'] ) ? $settings['type'] : '';
	$is_responsive = isset( $settings['responsive'] ) ? $settings['responsive'] : false;
	$units         = isset( $settings['units'] ) ? $settings['units'] : array();
	$with_unit     = isset( $settings['with_units'] ) ? $settings['with_units'] : false;
	$class         = 'porto-wpb-number-container';

	if ( $is_responsive ) {
		$class .= ' porto-responsive-control';
	}

	$responsive_value = array(
		'xl' => '',
	);
	if ( ! empty( $value ) ) {
		$responsive_value = json_decode( $value, true );
	} else if( 'items_responsive' == $param_name ) {
		if ( isset( $_REQUEST['params']['items'] ) ) {
			$responsive_value['xl'] = $_REQUEST['params']['items'];
		}
		if ( isset( $_REQUEST['params']['items_lg'] ) ) {
			$responsive_value['lg'] = $_REQUEST['params']['items_lg'];
		}
		if ( isset( $_REQUEST['params']['items_md'] ) ) {
			$responsive_value['md'] = $_REQUEST['params']['items_md'];
		}
		if ( isset( $_REQUEST['params']['items_sm'] ) ) {
			$responsive_value['sm'] = $_REQUEST['params']['items_sm'];
		}
		if ( isset( $_REQUEST['params']['items_xs'] ) ) {
			$responsive_value['xs'] = $_REQUEST['params']['items_xs'];
		}
	}
	$saved_unit = ! empty( $responsive_value['unit'] ) ? $responsive_value['unit'] : '';
	$output     = '<div class="' . esc_attr( $class ) . '">';

	if ( ! empty( $units ) ) {
		ob_start();
		?>
		<input type="number"
			class="porto-wpb-number"
			value="<?php echo esc_attr( $responsive_value['xl'] ); ?>"
			data-xl="<?php echo ( isset( $responsive_value['xl'] ) ? esc_attr( $responsive_value['xl'] ) : '' ); ?>"
			data-lg="<?php echo ( isset( $responsive_value['lg'] ) ? esc_attr( $responsive_value['lg'] ) : '' ); ?>"
			data-md="<?php echo ( isset( $responsive_value['md'] ) ? esc_attr( $responsive_value['md'] ) : '' ); ?>"
			data-sm="<?php echo ( isset( $responsive_value['sm'] ) ? esc_attr( $responsive_value['sm'] ) : '' ); ?>"
			data-xs="<?php echo ( isset( $responsive_value['xs'] ) ? esc_attr( $responsive_value['xs'] ) : '' ); ?>"
			data-unit="<?php echo ( isset( $responsive_value['unit'] ) ? esc_attr( $responsive_value['unit'] ) : '' ); ?>"
			/>
		<select class="porto-wpb-units">
			<?php foreach ( $units as $unit ) { ?>
				<option value="<?php echo esc_attr( $unit ); ?>" <?php echo esc_attr( $unit == $saved_unit ? 'selected' : '' ); ?>><?php echo esc_html( $unit ); ?></option>
			<?php } ?>
		</select>
		<?php
		$output .= ob_get_clean();
	} else {
		ob_start();
		if ( $is_responsive ) {
			?>
		<input type="<?php echo esc_attr( $with_unit ? 'text' : 'number' ); ?>"
			class="porto-wpb-number"
			value="<?php echo esc_attr( $responsive_value['xl'] ); ?>"
			data-xl="<?php echo ( isset( $responsive_value['xl'] ) ? esc_attr( $responsive_value['xl'] ) : '' ); ?>"
			data-lg="<?php echo ( isset( $responsive_value['lg'] ) ? esc_attr( $responsive_value['lg'] ) : '' ); ?>"
			data-md="<?php echo ( isset( $responsive_value['md'] ) ? esc_attr( $responsive_value['md'] ) : '' ); ?>"
			data-sm="<?php echo ( isset( $responsive_value['sm'] ) ? esc_attr( $responsive_value['sm'] ) : '' ); ?>"
			data-xs="<?php echo ( isset( $responsive_value['xs'] ) ? esc_attr( $responsive_value['xs'] ) : '' ); ?>"
			data-unit="<?php echo ( isset( $responsive_value['unit'] ) ? esc_attr( $responsive_value['unit'] ) : '' ); ?>"
			/>
			<?php
		} else {
			?>
			<input type="<?php echo esc_attr( $with_unit ? 'text' : 'number' ); ?>"
			class="porto-wpb-number simple-value"
			value="<?php echo esc_attr( $value ); ?>"
			/>
			<?php
		}
		$output .= ob_get_clean();
	}

	if ( $is_responsive ) {
		ob_start();
		?>
		<div class="porto-responsive-dropdown">
			<a class="porto-responsive-toggle" title="Toggle Responsive Option"><i class="vc-composer-icon vc-c-icon-layout_default"></i></a>
			<ul class="porto-responsive-span">
				<li data-width="xl" title=">= <?php echo porto_get_xl_width(); ?>px" class="active" data-size="100%"><i class="vc-composer-icon vc-c-icon-layout_default"></i></li>
				<li data-width="lg" title=">= 992px" data-size="1024px"><i class="vc-composer-icon vc-c-icon-layout_landscape-tablets"></i></li>
				<li data-width="md" title=">= 768px" data-size="768px"><i class="vc-composer-icon vc-c-icon-layout_portrait-tablets"></i></li>
				<li data-width="sm" title=">= 576px" data-size="480px"><i class="vc-composer-icon vc-c-icon-layout_landscape-smartphones"></i></li>
				<li data-width="xs" title="< 576px" data-size="320px"><i class="vc-composer-icon vc-c-icon-layout_portrait-smartphones"></i></li>
			</ul>
		</div>
		<?php
		$output .= ob_get_clean();
	}

	$output .= '</div>';
	$output .= '<input type="hidden" name="' . esc_attr( $param_name ) . '" class="wpb_vc_param_value ' . esc_attr( $settings['param_name'] ) . ' ' . esc_attr( $type ) . '_field" value="' . esc_attr( $value ) . '" ' . ' />';
	return $output;
}

/**
 * Porto WPBakery Dimension Callback
 *
 * adds dimension control for element option
 * follow below example of porto_dimension control
 *
 * array(
 *      'type'        => 'porto_dimension',
 *      'heading'     => __( 'Buttton Padding', 'porto-functionality' ),
 *      'param_name'  => 'btn_padding',
 *      'responsive'  => true,
 *      'value'       => '',
 *      'group'       => 'General',
 *      'selectors'   => array(
 *          '{{WRAPPER}}.btn' => 'padding-top: {{TOP}};padding-right: {{RIGHT}};padding-bottom: {{BOTTOM}};padding-left: {{LEFT}};',
 *      )
 * )
 *
 * @since 6.1.0
 *
 * @param object $settings
 * @param string $value
 *
 * @return string
 */
function porto_dimension_callback( $settings, $value ) {
	$param_name    = isset( $settings['param_name'] ) ? $settings['param_name'] : '';
	$type          = isset( $settings['type'] ) ? $settings['type'] : '';
	$is_responsive = isset( $settings['responsive'] ) ? $settings['responsive'] : false;
	$units         = isset( $settings['units'] ) ? $settings['units'] : array();
	$class         = 'porto-wpb-dimension-container';

	if ( $is_responsive ) {
		$class .= ' porto-responsive-control';
	}

	$responsive_value = array();
	if ( $value ) {
		$responsive_value = json_decode( $value, true );
	}
	$saved_unit       = ! empty( $responsive_value['unit'] ) ? $responsive_value['unit'] : '';
	$output           = '<div class="' . esc_attr( $class ) . '">';
	$dimensions       = array(
		'top'    => esc_html__( 'Top', 'porto-functionality' ),
		'right'  => esc_html__( 'Right', 'porto-functionality' ),
		'bottom' => esc_html__( 'Bottom', 'porto-functionality' ),
		'left'   => esc_html__( 'Left', 'porto-functionality' ),
	);

	foreach ( $dimensions as $dimension => $label ) {
		ob_start();
		$dimension_class = 'porto-wpb-dimension-wrap ' . $dimension;
		?>
		<div class="<?php echo esc_attr( $dimension_class ); ?>">
		<input type="text"
			class="porto-wpb-dimension"
			value="<?php echo ! empty( $responsive_value ) ? esc_attr( $responsive_value[ $dimension ]['xl'] ) : ''; ?>"
			data-xl="<?php echo ( ! empty( $responsive_value ) && isset( $responsive_value[ $dimension ]['xl'] ) ? esc_attr( $responsive_value[ $dimension ]['xl'] ) : '' ); ?>"
			data-lg="<?php echo ( ! empty( $responsive_value ) && isset( $responsive_value[ $dimension ]['lg'] ) ? esc_attr( $responsive_value[ $dimension ]['lg'] ) : '' ); ?>"
			data-md="<?php echo ( ! empty( $responsive_value ) && isset( $responsive_value[ $dimension ]['md'] ) ? esc_attr( $responsive_value[ $dimension ]['md'] ) : '' ); ?>"
			data-sm="<?php echo ( ! empty( $responsive_value ) && isset( $responsive_value[ $dimension ]['sm'] ) ? esc_attr( $responsive_value[ $dimension ]['sm'] ) : '' ); ?>"
			data-xs="<?php echo ( ! empty( $responsive_value ) && isset( $responsive_value[ $dimension ]['xs'] ) ? esc_attr( $responsive_value[ $dimension ]['xs'] ) : '' ); ?>"
			/>
		<label><?php echo esc_html( $label ); ?></label>
		</div>
		<?php
		$output .= ob_get_clean();
	}

	if ( $is_responsive ) {
		ob_start();
		?>
		<div class="porto-responsive-dropdown">
			<a class="porto-responsive-toggle" title="Toggle Responsive Option"><i class="vc-composer-icon vc-c-icon-layout_default"></i></a>
			<ul class="porto-responsive-span">
				<li data-width="xl" title=">= <?php echo porto_get_xl_width(); ?>px" class="active" data-size="100%"><i class="vc-composer-icon vc-c-icon-layout_default"></i></li>
				<li data-width="lg" title=">= 992px" data-size="1024px"><i class="vc-composer-icon vc-c-icon-layout_landscape-tablets"></i></li>
				<li data-width="md" title=">= 768px" data-size="768px"><i class="vc-composer-icon vc-c-icon-layout_portrait-tablets"></i></li>
				<li data-width="sm" title=">= 576px" data-size="480px"><i class="vc-composer-icon vc-c-icon-layout_landscape-smartphones"></i></li>
				<li data-width="xs" title="< 576px" data-size="320px"><i class="vc-composer-icon vc-c-icon-layout_portrait-smartphones"></i></li>
			</ul>
		</div>
		<?php
		$output .= ob_get_clean();
	}

	$output .= '</div>';
	$output .= '<input type="hidden" name="' . esc_attr( $param_name ) . '" class="wpb_vc_param_value ' . esc_attr( $settings['param_name'] ) . ' ' . esc_attr( $type ) . '_field" value="' . esc_attr( $value ) . '" ' . ' />';
	return $output;
}

/**
 * Porto WPBakery ButtonGroup Callback
 *
 * adds button-choose control supporting label, icon and image
 * follow below example of porto_button_group control
 * order of options' priority is 'image' > 'color' > 'icon' > 'label'
 *
 * if all type options are omited, it will automatically change to label type
 * and 'title' option will be worked as 'lable' option
 *
 * array(
 *      'type'        => 'porto_button_group',
 *      'heading'     => __( 'Alignment', 'porto-functionality' ),
 *      'param_name'  => 'button_align',
 *      'value'       => array(
 *          'left' => array(
 *              'title' => esc_html__( 'Left', 'porto-functionality' ), // tooltip text
 *              'color' => '#26c',
 *              'icon'  => 'fas fa-align-left',
 *              'label' => esc_html__( 'Left', 'porto-functionality' ),
 *          ),
 *          'center' => array(
 *              'title' => esc_html__( 'Center', 'porto-functionality' ), // tooltip text
 *              'color' => '#d26e4b',
 *              'icon'  => 'fas fa-align-center',
 *              'label' => esc_html__( 'Center', 'porto-functionality' ),
 *          ),
 *          'right' => array(
 *              'title' => esc_html__( 'Right', 'porto-functionality' ), // tooltip text
 *              'color' => '#a8c26e',
 *              'icon'  => 'fas fa-align-right',
 *              'label' => esc_html__( 'Right', 'porto-functionality' ),
 *          ),
 *          'inline' => array(
 *              'title' => esc_html__( 'Inline', 'porto-functionality' ), // tooltip text
 *              'color' => '#fff',
 *              'icon'  => 'fas fa-arrows-alt-h',
 *              'label' => esc_html__( 'Inline', 'porto-functionality' ),
 *          )
 *      ),
 *      'std' => 'left',
 *      'description' => '',
 *      'group'       => 'General',
 *  ),
 *
 * @since 6.1.0
 *
 * @param object $settings
 * @param string $value
 *
 * @return string
 */
function porto_button_group_callback( $settings, $value ) {
	$param_name    = isset( $settings['param_name'] ) ? $settings['param_name'] : '';
	$type          = isset( $settings['type'] ) ? $settings['type'] : '';
	$values        = isset( $settings['value'] ) ? $settings['value'] : array();
	$is_responsive = isset( $settings['responsive'] ) ? $settings['responsive'] : false;
	$button_width  = isset( $settings['button_width'] ) ? $settings['button_width'] : '';

	$class = 'porto-wpb-button-group';
	$attr  = '';

	if ( empty( $values ) ) {
		return;
	}

	if ( is_array( $value ) ) { // if std value does not exist
		$value = array_keys( $values )[0];
	}

	if ( '/' == $value || '{' == $value ) {
		$value = '';
	}

	if ( $is_responsive ) {
		$class .= ' porto-responsive-control';
		$attr  .= "data-width='xl'";

		if ( null == json_decode( $value, true ) ) {
			$value = array( 'xl' => $value );
		} else {
			$value = json_decode( $value, true );
		}
	}

	$keys = array_keys( $values[ array_keys( $values )[0] ] );
	if ( in_array( 'image', $keys ) ) {
		$class .= ' image-button';
	} elseif ( in_array( 'color', $keys ) ) {
		$class .= ' color-button';
	} elseif ( in_array( 'icon', $keys ) ) {
		$class .= ' icon-button';
	} elseif ( in_array( 'label', $keys ) ) {
		$class .= ' label-button';
	}

	if ( ! is_array( $value ) ) {
		$class .= ' ' . $value;
	}

	$output  = '';
	$output .= '<div class="' . esc_attr( $class ) . '"' . ( $attr ? ' ' . $attr : '' ) . '>';

	$output .= '<ul class="options-wrapper">';
	foreach ( $values as $key => $options ) {
		$label  = '';
		$style  = '';
		$o_keys = array_keys( $options );

		if ( in_array( 'image', $o_keys ) ) {
			$label = '<img src="' . esc_url( $options['image'] ) . '" />';
		} elseif ( in_array( 'color', $o_keys ) ) {
			$style = 'background-color: ' . esc_attr( $options['color'] );
		} elseif ( in_array( 'icon', $o_keys ) ) {
			$label = '<i class="' . esc_attr( $options['icon'] ) . '"></i>';
		} elseif ( in_array( 'label', $o_keys ) ) {
			$label = esc_html( $options['label'] );
		} else {
			$label = esc_html( $options['title'] );
		}

		if ( $button_width ) {
			$style .= 'width: ' . $button_width . 'px;';
		}

		$output .= '<li attr-value="' . esc_attr( $key ) . '"' . ( ( is_array( $value ) ? $value['xl'] : $value ) == $key ? ' class="active"' : '' ) . ' title="' . esc_attr( $options['title'] ) . '"' . ( $style ? ' style="' . $style . '"' : '' ) . '>' . $label . '</li>';
	}
	$output .= '</ul>';

	if ( $is_responsive ) {
		ob_start();
		?>
		<div class="porto-responsive-dropdown">
			<a class="porto-responsive-toggle" title="Toggle Responsive Option"><i class="vc-composer-icon vc-c-icon-layout_default"></i></a>
			<ul class="porto-responsive-span">
				<li data-width="xl" title=">= <?php echo porto_get_xl_width(); ?>px" class="active" data-size="100%"><i class="vc-composer-icon vc-c-icon-layout_default"></i></li>
				<li data-width="lg" title=">= 992px" data-size="1024px"><i class="vc-composer-icon vc-c-icon-layout_landscape-tablets"></i></li>
				<li data-width="md" title=">= 768px" data-size="768px"><i class="vc-composer-icon vc-c-icon-layout_portrait-tablets"></i></li>
				<li data-width="sm" title=">= 576px" data-size="480px"><i class="vc-composer-icon vc-c-icon-layout_landscape-smartphones"></i></li>
				<li data-width="xs" title="< 576px" data-size="320px"><i class="vc-composer-icon vc-c-icon-layout_portrait-smartphones"></i></li>
			</ul>
		</div>
		<?php
		$output .= ob_get_clean();
	}

	$output .= '</div>';

	$output .= '<input type="hidden" name="' . esc_attr( $param_name ) . '" class="wpb_vc_param_value ' . esc_attr( $settings['param_name'] ) . ' ' . esc_attr( $type ) . '_field" value=' . ( is_array( $value ) ? json_encode( $value ) : $value ) . ' />';
	return $output;
}

/**
 * WPB Typography Param Callback
 *
 * array(
 *      'type'       => 'porto_typography',
 *      'heading'    => __( 'Button Typography', 'porto-functionality' ),
 *      'param_name' => 'btn_font',
 *      'group'      => __( 'Style', 'porto-functionality' ),
 *      'selectors'  => array(
 *          '{{WRAPPER}}.btn'
 *      )
 * ),
 *
 *
 * @since 6.1.0
 * @param array             $settings Settings for typography param
 * @param array|string|bool $value    Value of param
 * @return string
 */
function porto_typography_callback( $settings, $value ) {
	$param_name = isset( $settings['param_name'] ) ? $settings['param_name'] : '';
	$type       = isset( $settings['type'] ) ? $settings['type'] : '';
	$prefix     = substr( $param_name, 0, strrpos( $param_name, 'porto_typography' ) );
	$is_responsive = isset( $settings['responsive'] ) ? $settings['responsive'] : false;
	if ( empty( $prefix ) ) {
		$prefix = '';
	}
	$class      = 'porto-wpb-typography-container';
	$typography = array(
		'family'  => 'Default',
		'variant' => 'Default',
	);
	if ( ! empty( $value ) ) {
		$typography = json_decode( $value, true );
	} else {
		if ( ! empty( $_REQUEST['params'][ $prefix . 'size' ] ) ) {
			$typography['font_size'] = $_REQUEST['params'][ $prefix . 'size' ];
		}
		if ( ! empty( $_REQUEST['params'][ $prefix . 'font_size' ] ) ) {
			$typography['font_size'] = $_REQUEST['params'][ $prefix . 'font_size' ];
		}
		if ( ! empty( $_REQUEST['params'][ $prefix . 'letter_spacing' ] ) ) {
			$typography['letter_spacing'] = $_REQUEST['params'][ $prefix . 'letter_spacing' ];
		}
		if ( ! empty( $_REQUEST['params'][ $prefix . 'line_height' ] ) ) {
			$typography['line_height'] = $_REQUEST['params'][ $prefix . 'line_height' ];
		}
		if ( ! empty( $_REQUEST['params'][ substr( $prefix, 0, strlen( $prefix ) - 5 ) . 'line_height' ] ) ) {
			$typography['line_height'] = $_REQUEST['params'][ substr( $prefix, 0, strlen( $prefix ) - 5 ) . 'line_height' ];
		}
		if ( ! empty( $_REQUEST['params'][ $prefix . 'style' ] ) ) {
			$typography['variant'] = $_REQUEST['params'][ $prefix . 'style' ];
		}
		if ( ! empty( $_REQUEST['params'][ $prefix . 'font_style' ] ) ) {
			$typography['variant'] = $_REQUEST['params'][ $prefix . 'font_style' ];
		}
		if ( ! empty( $_REQUEST['params'][ $prefix . 'font_weight' ] ) ) {
			$typography['variant'] = $_REQUEST['params'][ $prefix . 'font_weight' ];
		}
		if ( ! empty( $typography['variant'] ) && 400 == (int) $typography['variant'] ) {
			$typography['variant'] = 'regular';
		}
		if ( isset( $_REQUEST['params'][ $prefix . 'font' ] ) ) {
			if ( empty( $typography['variant'] ) ) {
				$typography['variant'] = 'Default';
			}
			$family                 = $_REQUEST['params'][ $prefix . 'font' ];
			$fonts_data             = porto_sc_parse_google_font( $family );
			$typography['family']   = $fonts_data['values']['font_family'];
			$typography['variant'] .= $fonts_data['values']['font_style'];
		}
		if ( isset( $_REQUEST['params'][ substr( $prefix, 0, strlen( $prefix ) - 5 ) . 'google_font' ] ) ) {
			if ( empty( $typography['variant'] ) ) {
				$typography['variant'] = 'Default';
			}
			$family                 = $_REQUEST['params'][ substr( $prefix, 0, strlen( $prefix ) - 5 ) . 'google_font' ];
			$fonts_data             = porto_sc_parse_google_font( $family );
			$typography['family']   = $fonts_data['values']['font_family'];
			$typography['variant'] .= $fonts_data['values']['font_style'];
		}
	}

	$text_transform = array(
		''           => esc_html__( 'Default', 'porto-functionality' ),
		'none'       => esc_html__( 'None', 'porto-functionality' ),
		'lowercase'  => esc_html__( 'Lowercase', 'porto-functionality' ),
		'uppercase'  => esc_html__( 'Uppercase', 'porto-functionality' ),
		'capitalize' => esc_html__( 'Capitalize', 'porto-functionality' ),
		'inherit'    => esc_html__( 'Inherit', 'porto-functionality' ),
	);
	if ( function_exists( 'porto_include_google_font' ) ) {
		$fonts        = array_merge( porto_include_google_font(), array( 'Inherit', 'Default' ) );
		$custom_fonts = get_option( 'porto_custom_fonts', array() );
		if ( ! empty( $custom_fonts ) ) {
			foreach ( $custom_fonts as $c_fonts ) {
				if ( ! empty( $c_fonts ) ) {
					foreach ( $c_fonts as $c_font_name => $font_fields ) {
						$_font = str_replace( '+', ' ', $c_font_name );
						if ( ! in_array( $_font, $fonts ) ) {
							$fonts[] = $_font;
						}
						
					}
				}
			}
		}
		$font_variants = array(
			'100',
			'100italic',
			'200',
			'200italic',
			'300',
			'300italic',
			'500',
			'500italic',
			'600',
			'600italic',
			'700',
			'700italic',
			'800',
			'800italic',
			'900',
			'900italic',
			'italic',
			'regular',
			'Default',
		);
	}

	$output = '<div class="' . esc_attr( $class ) . '">';
	ob_start();
	?>

	<div class="porto-wpb-typography-toggle">
		<p><?php echo esc_html__( ! empty( $typography ) ? 'Family: ' . $typography['family'] . ' | Variant: ' . $typography['variant'] : 'Default' ); ?></p>
	</div>
	<div class="porto-wpb-typography-controls" style="display: none;">
		<div class="porto-wpb-typoraphy-form">
			<div class="wpb_element_label"><?php esc_html_e( 'Font Family', 'porto-functionality' ); ?></div>
			<div class="porto-vc-font-family-container">
				<select class="porto-vc-font-family">
					<?php
					if ( ! empty( $fonts ) ) {
						foreach ( $fonts as $font_data ) :
							$is_active = false;
							$font_data = str_replace( '%2C', ',', $font_data );
							$font_data = str_replace( '%27', '\'', $font_data );	
							if ( $font_data == $typography['family'] ) {
								$is_active = true;
							}
							?>
							<option value="<?php echo esc_attr( $font_data ); ?>"
								<?php echo esc_attr( $is_active ? 'selected' : '' ); ?>><?php echo esc_html( $font_data ); ?></option>
							<?php
						endforeach;
					}
					?>
				</select>
			</div>
			<p style="padding: 0 .5em;">If you want to use other font, please add it in Theme Options -> Skin -> Typography -> Custom Font.</p>
		</div>
		<div class="porto-wpb-typoraphy-form">
			<div class="wpb_element_label"><?php esc_html_e( 'Font Variants', 'porto-functionality' ); ?></div>
			<div class="porto-vc-font-variants-container">
				<select class="porto-vc-font-variants">
					<?php
					if ( ! empty( $font_variants ) ) {
						foreach ( $font_variants as $variant ) :
							?>
							<option value="<?php echo esc_attr( $variant ); ?>"
							<?php echo esc_attr( $variant == $typography['variant'] ? 'selected' : '' ); ?>><?php echo esc_html( $variant ); ?></option>
							<?php
						endforeach;
					}
					?>
				</select>
			</div>
		</div>
		<div class="porto-wpb-typoraphy-form<?php echo isset( $settings['line_height'] ) && ! $settings['line_height'] ? '' : ' cols-2'; echo ( ! empty( $is_responsive ) ? ' porto-responsive-control' : '' ); ?>">
			<div class="wpb_element_label"><?php esc_html_e( 'Font Size', 'porto-functionality' ); ?></div>
			<div class="porto-vc-font-size-container">
				<?php
				if ( $is_responsive ) {
					$typography_fs = array();
					if ( isset( $typography['font_size'] ) ) {
						if ( is_array( $typography['font_size'] ) ) {
							$typography_fs = $typography['font_size'];
						} else {
							$typography_fs['xl'] = $typography['font_size'];
						}
					} else {
						$typography_fs['xl'] = '';
					}
					?>
					<input type="string" name="font-size" class="porto-vc-font-size" 
						value="<?php echo esc_attr( $typography_fs['xl'] ); ?>"
						data-xl="<?php echo ( isset( $typography_fs['xl'] ) ? esc_attr( $typography_fs['xl'] ) : '' ); ?>"
						data-lg="<?php echo ( isset( $typography_fs['lg'] ) ? esc_attr( $typography_fs['lg'] ) : '' ); ?>"
						data-md="<?php echo ( isset( $typography_fs['md'] ) ? esc_attr( $typography_fs['md'] ) : '' ); ?>"
						data-sm="<?php echo ( isset( $typography_fs['sm'] ) ? esc_attr( $typography_fs['sm'] ) : '' ); ?>"
						data-xs="<?php echo ( isset( $typography_fs['xs'] ) ? esc_attr( $typography_fs['xs'] ) : '' ); ?>" />
				<?php } else { ?>
					<input type="string" name="font-size" class="porto-vc-font-size" value="<?php echo isset( $typography['font_size'] ) ? esc_attr( $typography['font_size'] ) : ''; ?>" />

				<?php } ?>
			</div>
			<?php
				if ( $is_responsive ) {
					?>
					<div class="porto-responsive-dropdown">
						<a class="porto-responsive-toggle" title="Toggle Responsive Option"><i class="vc-composer-icon vc-c-icon-layout_default"></i></a>
						<ul class="porto-responsive-span">
							<li data-width="xl" title=">= <?php echo porto_get_xl_width(); ?>px" class="active" data-size="100%"><i class="vc-composer-icon vc-c-icon-layout_default"></i></li>
							<li data-width="lg" title=">= 992px" data-size="1024px"><i class="vc-composer-icon vc-c-icon-layout_landscape-tablets"></i></li>
							<li data-width="md" title=">= 768px" data-size="768px"><i class="vc-composer-icon vc-c-icon-layout_portrait-tablets"></i></li>
							<li data-width="sm" title=">= 576px" data-size="480px"><i class="vc-composer-icon vc-c-icon-layout_landscape-smartphones"></i></li>
							<li data-width="xs" title="< 576px" data-size="320px"><i class="vc-composer-icon vc-c-icon-layout_portrait-smartphones"></i></li>
						</ul>
					</div>
					<?php
				}
			?>
			
		</div>
		<div class="porto-wpb-typoraphy-form cols-2<?php echo isset( $settings['line_height'] ) && ! $settings['line_height'] ? ' d-none' : ''; ?>">
			<div class="wpb_element_label"><?php esc_html_e( 'Line Height', 'porto-functionality' ); ?></div>
			<div class="porto-vc-line-height-container">
				<input type="string" name="line-height" class="porto-vc-line-height" value="<?php echo isset( $typography['line_height'] ) ? esc_attr( $typography['line_height'] ) : ''; ?>"  />
			</div>
		</div>
		<div class="porto-wpb-typoraphy-form cols-2">
			<div class="wpb_element_label"><?php esc_html_e( 'Letter Spacing', 'porto-functionality' ); ?></div>
			<div class="porto-vc-letter-spacing-container">
				<input type="string" name="letter-spacing" class="porto-vc-letter-spacing" value="<?php echo isset( $typography['letter_spacing'] ) ? esc_attr( $typography['letter_spacing'] ) : ''; ?>"  />
			</div>
		</div>
		<div class="porto-wpb-typoraphy-form cols-2">
			<div class="wpb_element_label"><?php esc_html_e( 'Text Transform', 'porto-functionality' ); ?></div>
			<div class="porto-vc-text-transform-container">
				<select type="string" name="text-transform" class="porto-vc-text-transform">
					<?php
					foreach ( $text_transform as $key => $label ) {
						?>
						<option value="<?php echo esc_attr( $key ); ?>" <?php echo isset( $typography['text_transform'] ) && $key == $typography['text_transform'] ? 'selected' : ''; ?>><?php echo esc_html( $label ); ?></option>
						<?php
					}
					?>
				</select>
			</div>
		</div>
	</div>

	<?php
	$output .= ob_get_clean();
	$output .= '</div>';
	$output .= '<input type="hidden" name="' . esc_attr( $param_name ) . '" class="wpb_vc_param_value ' . esc_attr( $settings['param_name'] ) . ' ' . esc_attr( $type ) . '_field" value="' . esc_attr( $value ) . '" ' . ' />';

	return $output;

}
function porto_param_heading_callback( $settings, $value ) {
	$dependency = '';
	$param_name = isset( $settings['param_name'] ) ? $settings['param_name'] : '';
	$class      = isset( $settings['class'] ) ? ' ' . $settings['class'] : '';
	$text       = isset( $settings['text'] ) ? $settings['text'] : '';
	if ( ! empty( $settings['with_group'] ) ) {
		$class .= ' with-group';
	}
	$output     = '<h4 ' . $dependency . ' class="porto-admin-shortcodes-heading' . esc_attr( $class ) . '">' . porto_strip_script_tags( $text ) . '</h4>';
	$output    .= '<input type="hidden" name="' . esc_attr( $settings['param_name'] ) . '" class="wpb_vc_param_value porto-param-heading ' . esc_attr( $settings['param_name'] ) . ' ' . esc_attr( $settings['type'] ) . '_field" value="' . esc_attr( $value ) . '" ' . $dependency . '/>';
	return $output;
}
function porto_number_settings_field( $settings, $value ) {
	$dependency = '';
	$param_name = isset( $settings['param_name'] ) ? $settings['param_name'] : '';
	$type       = isset( $settings['type'] ) ? $settings['type'] : '';
	$min        = isset( $settings['min'] ) ? $settings['min'] : '';
	$max        = isset( $settings['max'] ) ? $settings['max'] : '';
	$step       = isset( $settings['step'] ) ? $settings['step'] : '';
	$suffix     = isset( $settings['suffix'] ) ? $settings['suffix'] : '';
	$class      = isset( $settings['class'] ) ? $settings['class'] : '';
	$output     = '<input type="number" min="' . esc_attr( $min ) . '" max="' . esc_attr( $max ) . '" step="' . esc_attr( $step ) . '" class="wpb_vc_param_value ' . esc_attr( $param_name ) . ' ' . esc_attr( $type ) . ' ' . esc_attr( $class ) . '" name="' . esc_attr( $param_name ) . '" value="' . esc_attr( $value ) . '" style="max-width:100px; margin-right: 10px;" />' . esc_html( $suffix );
	return $output;
}
function porto_boxshadow_callback( $settings, $value ) {
	$dependency   = '';
	$positions    = $settings['positions'];
	$enable_color = isset( $settings['enable_color'] ) ? $settings['enable_color'] : true;
	$unit         = isset( $settings['unit'] ) ? $settings['unit'] : 'px';

	$uid  = 'porto-boxshadow-' . rand( 1000, 9999 );
	$html = '<div class="porto-boxshadow" id="' . esc_attr( $uid ) . '" data-unit="' . esc_attr( $unit ) . '" >';

	$label = 'Shadow Style';
	if ( isset( $settings['label_style'] ) && $settings['label_style'] ) {
		$label = $settings['label_style'];
	}
	$html             .= '<div class="porto-bs-select-block">';
		$html         .= '<div class="porto-bs-select-wrap">';
			$html     .= '<select class="porto-bs-select" >';
				$html .= '<option value="">' . esc_html__( 'Default', 'porto-functionality' ) . '</option>';
				$html .= '<option value="none">' . esc_html__( 'None', 'porto-functionality' ) . '</option>';
				$html .= '<option value="inherit"' . ( isset( $settings['default_style'] ) && 'inherit' == $settings['default_style'] ? ' selected="selected"' : '' ) . '>' . esc_html__( 'Inherit', 'porto-functionality' ) . '</option>';
				$html .= '<option value="inset"' . ( isset( $settings['default_style'] ) && 'inset' == $settings['default_style'] ? ' selected="selected"' : '' ) . '>' . esc_html__( 'Inset', 'porto-functionality' ) . '</option>';
				$html .= '<option value="outset"' . ( isset( $settings['default_style'] ) && 'outset' == $settings['default_style'] ? ' selected="selected"' : '' ) . '>' . esc_html__( 'Outset', 'porto-functionality' ) . '</option>';
			$html     .= '</select>';
		$html         .= '</div>';
	$html             .= '</div>';

	$html .= '<div class="porto-bs-input-block" >';
	foreach ( $positions as $key => $default_value ) {
		switch ( $key ) {
			case 'Horizontal':
				$dashicon = 'dashicons dashicons-leftright';
				$html    .= porto_boxshadow_param_item( $dashicon, $unit, $default_value, $key );
				break;
			case 'Vertical':
				$dashicon = 'dashicons dashicons-sort';
				$html    .= porto_boxshadow_param_item( $dashicon, $unit, $default_value, $key );
				break;
			case 'Blur':
				$dashicon = 'dashicons dashicons-visibility';
				$html    .= porto_boxshadow_param_item( $dashicon, $unit, $default_value, $key );
				break;
			case 'Spread':
				$dashicon = 'dashicons dashicons-location';
				$html    .= porto_boxshadow_param_item( $dashicon, $unit, $default_value, $key );
				break;
		}
	}
	$html .= porto_bs_get_units( $unit );
	$html .= '</div>';

	if ( $enable_color ) {
		$label = __( 'Box Shadow Color', 'porto-functionality' );
		if ( isset( $settings['label_color'] ) && $settings['label_color'] ) {
			$label = $settings['label_color'];
		}
		$html         .= '<div class="porto-bs-colorpicker-block">';
			$html     .= '<div class="label wpb_element_label">';
				$html .= esc_html( $label );
			$html     .= '</div>';
			$html     .= '<div class="porto-bs-colorpicker-wrap">';
				$html .= '<input name="" class="porto-bs-colorpicker cs-wp-color-picker" type="text" value="" />';
			$html     .= '</div>';
		$html         .= '</div>';
	}

	$html .= '  <input type="hidden" data-unit="' . esc_attr( $unit ) . '" name="' . esc_attr( $settings['param_name'] ) . '" class="wpb_vc_param_value porto-bs-result-value ' . esc_attr( $settings['param_name'] ) . ' ' . esc_attr( $settings['type'] ) . '_field" value="' . esc_attr( $value ) . '" ' . $dependency . ' />';
	$html .= '</div>';
	return $html;
}
function porto_boxshadow_param_item( $dashicon, $unit, $default_value, $key ) {
	$html          = '<div class="porto-bs-input-wrap">';
		$html     .= '<span class="porto-bs-icon">';
			$html .= '<span class="porto-bs-tooltip">' . esc_html( $key ) . '</span>';
			$html .= '<i class="' . esc_attr( $dashicon ) . '"></i>';
		$html     .= '</span>';
		$html     .= '<input type="number" class="porto-bs-input" data-unit="' . esc_attr( $unit ) . '" data-id="' . strtolower( esc_attr( $key ) ) . '" data-default="' . esc_attr( $default_value ) . '" placeholder="' . esc_attr( $key ) . '" />';
	$html         .= '</div>';
	return $html;
}
function porto_bs_get_units( $unit ) {
	$html      = '<div class="porto-bs-unit">';
		$html .= '<label>' . esc_html( $unit ) . '</label>';
	$html     .= '</div>';
	return $html;
}

function porto_datetimepicker( $settings, $value ) {
	$dependency = '';
	$param_name = isset( $settings['param_name'] ) ? $settings['param_name'] : '';
	$type       = isset( $settings['type'] ) ? $settings['type'] : '';
	$class      = isset( $settings['class'] ) ? $settings['class'] : '';
	$uni        = uniqid( 'datetimepicker-' . rand() );
	$output     = '<div id="porto-date-time' . esc_attr( $uni ) . '" class="porto-datetime"><input data-format="yyyy/MM/dd hh:mm:ss" readonly class="wpb_vc_param_value ' . esc_attr( $param_name ) . ' ' . esc_attr( $type ) . ' ' . esc_attr( $class ) . '" name="' . esc_attr( $param_name ) . '" style="width:258px;" value="' . esc_attr( $value ) . '" ' . $dependency . '/><div class="add-on" > <i data-time-icon="far fa-calendar" data-date-icon="far fa-calendar"></i></div></div>';
	$output    .= '<script type="text/javascript"></script>';
	return $output;
}

function porto_image_select_callback( $settings, $value ) {
	$html  = '';
	$html .= '<ul class="porto-sc-image-select">';
	foreach ( $settings['value'] as $img => $key ) {
		$html .= '<li data-id="' . esc_attr( $key ) . '"' . ( $key === $value ? ' class="active"' : '' ) . '>';
		$html .= '<img src="' . esc_url( PORTO_SHORTCODES_URL . 'assets/images/' . $img ) . '" alt="" />';
		if ( ! empty( $settings['display_label'] ) ) {
			$html .= '<span class="porto-image-select-label">' . esc_html( str_replace( '-', ' ', $key ) ) . '</span>';
		}
		$html .= '</li>';
	}
	$html .= '</ul>';
	$html .= '<input type="hidden" name="' . esc_attr( $settings['param_name'] ) . '" class="wpb-input wpb-select wpb_vc_param_value ' . esc_attr( $settings['param_name'] ) . ' ' . esc_attr( $settings['type'] ) . '_field" value="' . esc_attr( $value ) . '" ' . ' />';
	return $html;
}

function porto_shortcode_product_id_callback( $query ) {
	if ( class_exists( 'Vc_Vendor_Woocommerce' ) ) {
		$vc_vendor_wc = new Vc_Vendor_Woocommerce();
		return $vc_vendor_wc->productIdAutocompleteSuggester( $query );
	}
	return '';
}

function porto_shortcode_product_id_render( $query ) {
	if ( class_exists( 'Vc_Vendor_Woocommerce' ) ) {
		$vc_vendor_wc = new Vc_Vendor_Woocommerce();
		return $vc_vendor_wc->productIdAutocompleteRender( $query );
	}
	return '';
}

function porto_shortcode_product_id_param_value( $current_value, $param_settings, $map_settings, $atts ) {
	if ( class_exists( 'Vc_Vendor_Woocommerce' ) ) {
		$vc_vendor_wc = new Vc_Vendor_Woocommerce();
		return $vc_vendor_wc->productIdDefaultValue( $current_value, $param_settings, $map_settings, $atts );
	}
	return '';
}
