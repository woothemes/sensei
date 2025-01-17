/**
 * WordPress dependencies
 */
import { addFilter } from '@wordpress/hooks';

/**
 * External dependencies
 */
import { has } from 'lodash';

export default function handleEmailBlocksEditor() {
	addFilter(
		'blocks.registerBlockType',
		'sensei-lms/email-blocks',
		removeIrrelevantSettings,
		10
	);

	/**
	 * Update the blocks to remove extra settings when used in email editor.
	 *
	 * @param {Object} settings Block settings.
	 * @param {string} name     Block name.
	 */
	function removeIrrelevantSettings( settings, name ) {
		const supports = { ...( settings.supports ? settings.supports : {} ) };

		// Remove font family setting.
		if (
			has( settings, 'supports.typography.fontFamily' ) ||
			has( settings, 'supports.typography.__experimentalFontFamily' )
		) {
			supports.typography = {
				...supports.typography,
				__experimentalFontFamily: false,
				fontFamily: false,
			};
		}

		// Remove alignWide setting.
		if ( has( settings, 'supports.alignWide' ) ) {
			supports.alignWide = false;
		}

		// Remove wide from align options.
		if ( has( settings, 'supports.align.length' ) ) {
			supports.align = supports.align.filter( ( item ) => {
				return item !== 'wide';
			} );
		}

		// Alignment is not supported for buttons block in emails.
		if ( name === 'core/buttons' ) {
			if ( has( supports, 'layout' ) ) {
				supports.layout = false;
			}
		}

		// Alingment is not supported for image block in emails.
		if ( name === 'core/image' ) {
			if ( has( supports, 'align' ) ) {
				supports.align = false;
			}
		}

		return {
			...settings,
			supports,
		};
	}
}

handleEmailBlocksEditor();
