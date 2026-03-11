( function ( blocks, element, i18n, blockEditor, serverSideRender ) {
	'use strict';

	if ( ! blocks || ! element || ! i18n || ! blockEditor || ! serverSideRender ) {
		return;
	}

	var registerBlockType = blocks.registerBlockType;
	var createElement = element.createElement;
	var __ = i18n.__;
	var InnerBlocks = blockEditor.InnerBlocks;
	var ServerSideRender = serverSideRender;

	/**
	 * Create a placeholder component for empty block renders.
	 *
	 * @param {string} blockTitle The block title to display.
	 * @param {string} icon       Dashicon name (without 'dashicons-' prefix).
	 * @return {Function} A component function for EmptyResponsePlaceholder.
	 */
	function createEmptyPlaceholder( blockTitle, icon ) {
		return function() {
			return createElement(
				'div',
				{
					className: 'mf-block-placeholder',
					style: {
						padding: '20px',
						backgroundColor: '#f0f0f0',
						border: '1px dashed #ccc',
						textAlign: 'center',
						color: '#666'
					}
				},
				createElement(
					'span',
					{
						className: 'dashicons dashicons-' + icon,
						style: {
							fontSize: '24px',
							width: '24px',
							height: '24px',
							marginRight: '8px',
							verticalAlign: 'middle'
						}
					}
				),
				createElement(
					'span',
					{ style: { verticalAlign: 'middle' } },
					blockTitle
				)
			);
		};
	}

	var SONG_TEMPLATE = [
		[
			'core/group',
			{
				tagName: 'section',
				className: 'heading',
				layout: { type: 'constrained' }
			},
			[
				[
					'core/post-title',
					{
						level: 1,
						className: 'node-title'
					}
				],
				[ 'mysteriousface/song-authors' ],
				[ 'mysteriousface/song-personnel' ]
			]
		],
		[
			'core/group',
			{
				tagName: 'section',
				className: 'body',
				layout: { type: 'constrained' }
			},
			[
				[ 'core/post-content' ]
			]
		],
		[ 'mysteriousface/song-lyrics' ],
		[ 'mysteriousface/song-player' ],
		[ 'mysteriousface/song-related-albums' ]
	];

	var ALBUM_TEMPLATE = [
		[
			'core/group',
			{
				tagName: 'section',
				className: 'heading',
				layout: { type: 'constrained' }
			},
			[
				[
					'core/post-title',
					{
						level: 1,
						className: 'node-title'
					}
				]
			]
		],
		[
			'core/group',
			{
				tagName: 'section',
				className: 'body',
				layout: { type: 'constrained' }
			},
			[
				[ 'core/post-content' ]
			]
		],
		[ 'mysteriousface/album-player' ],
		[ 'mysteriousface/album-songs' ]
	];

	registerBlockType( 'mysteriousface/song-shell', {
		apiVersion: 3,
		title: __( 'Song Layout', 'mysteriousface-theme' ),
		description: __( 'Structural layout shell for single Song templates.', 'mysteriousface-theme' ),
		icon: 'format-audio',
		category: 'widgets',
		supports: {
			html: false,
			inserter: false,
			reusable: false
		},
		edit: function () {
			return createElement(
				'div',
				{ className: 'mysteriousface-song-shell-editor' },
				createElement( InnerBlocks, {
					template: SONG_TEMPLATE,
					templateLock: false
				} )
			);
		},
		save: function () {
			return createElement( InnerBlocks.Content );
		}
	} );

	registerBlockType( 'mysteriousface/song-authors', {
		apiVersion: 3,
		title: __( 'Song Authors', 'mysteriousface-theme' ),
		description: __( 'Renders song author names from custom fields.', 'mysteriousface-theme' ),
		icon: 'admin-users',
		category: 'widgets',
		supports: {
			html: false,
			inserter: true,
			reusable: false
		},
		edit: function () {
			return createElement( ServerSideRender, {
				block: 'mysteriousface/song-authors',
				httpMethod: 'POST',
				EmptyResponsePlaceholder: createEmptyPlaceholder( __( 'Song Authors', 'mysteriousface-theme' ), 'admin-users' )
			} );
		},
		save: function () {
			return null;
		}
	} );

	registerBlockType( 'mysteriousface/song-personnel', {
		apiVersion: 3,
		title: __( 'Song Personnel', 'mysteriousface-theme' ),
		description: __( 'Renders song personnel from custom fields.', 'mysteriousface-theme' ),
		icon: 'groups',
		category: 'widgets',
		supports: {
			html: false,
			inserter: true,
			reusable: false
		},
		edit: function () {
			return createElement( ServerSideRender, {
				block: 'mysteriousface/song-personnel',
				httpMethod: 'POST',
				EmptyResponsePlaceholder: createEmptyPlaceholder( __( 'Song Personnel', 'mysteriousface-theme' ), 'groups' )
			} );
		},
		save: function () {
			return null;
		}
	} );

	registerBlockType( 'mysteriousface/song-lyrics', {
		apiVersion: 3,
		title: __( 'Song Lyrics', 'mysteriousface-theme' ),
		description: __( 'Renders song lyrics from custom fields.', 'mysteriousface-theme' ),
		icon: 'editor-paragraph',
		category: 'widgets',
		supports: {
			html: false,
			inserter: true,
			reusable: false
		},
		edit: function () {
			return createElement( ServerSideRender, {
				block: 'mysteriousface/song-lyrics',
				httpMethod: 'POST',
				EmptyResponsePlaceholder: createEmptyPlaceholder( __( 'Song Lyrics', 'mysteriousface-theme' ), 'editor-paragraph' )
			} );
		},
		save: function () {
			return null;
		}
	} );

	registerBlockType( 'mysteriousface/song-player', {
		apiVersion: 3,
		title: __( 'Song Player', 'mysteriousface-theme' ),
		description: __( 'Renders the Bandcamp song player from custom fields.', 'mysteriousface-theme' ),
		icon: 'controls-play',
		category: 'widgets',
		supports: {
			html: false,
			inserter: true,
			reusable: false
		},
		edit: function () {
			return createElement( ServerSideRender, {
				block: 'mysteriousface/song-player',
				httpMethod: 'POST',
				EmptyResponsePlaceholder: createEmptyPlaceholder( __( 'Song Player', 'mysteriousface-theme' ), 'controls-play' )
			} );
		},
		save: function () {
			return null;
		}
	} );

	registerBlockType( 'mysteriousface/song-related-albums', {
		apiVersion: 3,
		title: __( 'Song Related Albums', 'mysteriousface-theme' ),
		description: __( 'Renders albums containing this song.', 'mysteriousface-theme' ),
		icon: 'playlist-audio',
		category: 'widgets',
		supports: {
			html: false,
			inserter: true,
			reusable: false
		},
		edit: function () {
			return createElement( ServerSideRender, {
				block: 'mysteriousface/song-related-albums',
				httpMethod: 'POST',
				EmptyResponsePlaceholder: createEmptyPlaceholder( __( 'Song Related Albums', 'mysteriousface-theme' ), 'playlist-audio' )
			} );
		},
		save: function () {
			return null;
		}
	} );

	registerBlockType( 'mysteriousface/album-shell', {
		apiVersion: 3,
		title: __( 'Album Layout', 'mysteriousface-theme' ),
		description: __( 'Structural layout shell for single Album templates.', 'mysteriousface-theme' ),
		icon: 'cover-image',
		category: 'widgets',
		supports: {
			html: false,
			inserter: false,
			reusable: false
		},
		edit: function () {
			return createElement(
				'div',
				{ className: 'mysteriousface-album-shell-editor' },
				createElement( InnerBlocks, {
					template: ALBUM_TEMPLATE,
					templateLock: false
				} )
			);
		},
		save: function () {
			return createElement( InnerBlocks.Content );
		}
	} );

	registerBlockType( 'mysteriousface/album-player', {
		apiVersion: 3,
		title: __( 'Album Player', 'mysteriousface-theme' ),
		description: __( 'Renders the Bandcamp player from album custom fields.', 'mysteriousface-theme' ),
		icon: 'controls-play',
		category: 'widgets',
		supports: {
			html: false,
			inserter: true,
			reusable: false
		},
		edit: function () {
			return createElement( ServerSideRender, {
				block: 'mysteriousface/album-player',
				httpMethod: 'POST',
				EmptyResponsePlaceholder: createEmptyPlaceholder( __( 'Album Player', 'mysteriousface-theme' ), 'controls-play' )
			} );
		},
		save: function () {
			return null;
		}
	} );

	registerBlockType( 'mysteriousface/album-songs', {
		apiVersion: 3,
		title: __( 'Album Songs', 'mysteriousface-theme' ),
		description: __( 'Renders the ordered album song list from custom fields.', 'mysteriousface-theme' ),
		icon: 'playlist-audio',
		category: 'widgets',
		supports: {
			html: false,
			inserter: true,
			reusable: false
		},
		edit: function () {
			return createElement( ServerSideRender, {
				block: 'mysteriousface/album-songs',
				httpMethod: 'POST',
				EmptyResponsePlaceholder: createEmptyPlaceholder( __( 'Album Songs', 'mysteriousface-theme' ), 'playlist-audio' )
			} );
		},
		save: function () {
			return null;
		}
	} );
}( window.wp.blocks, window.wp.element, window.wp.i18n, window.wp.blockEditor || window.wp.editor, window.wp.serverSideRender ) );
