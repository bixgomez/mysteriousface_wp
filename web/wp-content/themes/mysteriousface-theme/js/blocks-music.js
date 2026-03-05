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

	var SONG_TEMPLATE = [
		[
			'core/group',
			{
				tagName: 'section',
				className: 'heading',
				layout: { type: 'constrained' },
				lock: { move: true, remove: true }
			},
			[
				[
					'core/post-title',
					{
						level: 1,
						className: 'node-title',
						lock: { move: true, remove: true }
					}
				],
				[
					'mysteriousface/song-authors',
					{
						lock: { move: true, remove: true }
					}
				],
				[
					'mysteriousface/song-personnel',
					{
						lock: { move: true, remove: true }
					}
				]
			]
		],
		[
			'core/group',
			{
				tagName: 'section',
				className: 'body',
				layout: { type: 'constrained' },
				lock: { move: true, remove: true }
			},
			[
				[
					'core/post-content',
					{
						lock: { move: true, remove: true }
					}
				]
			]
		],
		[
			'mysteriousface/song-lyrics',
			{
				lock: { move: true, remove: true }
			}
		],
		[
			'mysteriousface/song-player',
			{
				lock: { move: true, remove: true }
			}
		],
		[
			'mysteriousface/song-related-albums',
			{
				lock: { move: true, remove: true }
			}
		]
	];

	var ALBUM_TEMPLATE = [
		[
			'core/group',
			{
				tagName: 'section',
				className: 'heading',
				layout: { type: 'constrained' },
				lock: { move: true, remove: true }
			},
			[
				[
					'core/post-title',
					{
						level: 1,
						className: 'node-title',
						lock: { move: true, remove: true }
					}
				]
			]
		],
		[
			'core/group',
			{
				tagName: 'section',
				className: 'body',
				layout: { type: 'constrained' },
				lock: { move: true, remove: true }
			},
			[
				[
					'core/post-content',
					{
						lock: { move: true, remove: true }
					}
				]
			]
		],
		[
			'mysteriousface/album-player',
			{
				lock: { move: true, remove: true }
			}
		],
		[
			'mysteriousface/album-songs',
			{
				lock: { move: true, remove: true }
			}
		]
	];

	registerBlockType( 'mysteriousface/song-shell', {
		apiVersion: 3,
		title: __( 'Song Layout', 'mysteriousface-theme' ),
		description: __( 'Locked structural layout for single Song templates.', 'mysteriousface-theme' ),
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
					templateLock: 'all'
				} )
			);
		},
		save: function () {
			return null;
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
			inserter: false,
			reusable: false
		},
		edit: function () {
			return createElement( ServerSideRender, {
				block: 'mysteriousface/song-authors',
				httpMethod: 'POST'
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
			inserter: false,
			reusable: false
		},
		edit: function () {
			return createElement( ServerSideRender, {
				block: 'mysteriousface/song-personnel',
				httpMethod: 'POST'
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
			inserter: false,
			reusable: false
		},
		edit: function () {
			return createElement( ServerSideRender, {
				block: 'mysteriousface/song-lyrics',
				httpMethod: 'POST'
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
			inserter: false,
			reusable: false
		},
		edit: function () {
			return createElement( ServerSideRender, {
				block: 'mysteriousface/song-player',
				httpMethod: 'POST'
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
			inserter: false,
			reusable: false
		},
		edit: function () {
			return createElement( ServerSideRender, {
				block: 'mysteriousface/song-related-albums',
				httpMethod: 'POST'
			} );
		},
		save: function () {
			return null;
		}
	} );

	registerBlockType( 'mysteriousface/album-shell', {
		apiVersion: 3,
		title: __( 'Album Layout', 'mysteriousface-theme' ),
		description: __( 'Locked structural layout for single Album templates.', 'mysteriousface-theme' ),
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
					templateLock: 'all'
				} )
			);
		},
		save: function () {
			return null;
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
			inserter: false,
			reusable: false
		},
		edit: function () {
			return createElement( ServerSideRender, {
				block: 'mysteriousface/album-player',
				httpMethod: 'POST'
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
			inserter: false,
			reusable: false
		},
		edit: function () {
			return createElement( ServerSideRender, {
				block: 'mysteriousface/album-songs',
				httpMethod: 'POST'
			} );
		},
		save: function () {
			return null;
		}
	} );
}( window.wp.blocks, window.wp.element, window.wp.i18n, window.wp.blockEditor || window.wp.editor, window.wp.serverSideRender ) );
