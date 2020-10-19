import { InnerBlocks } from '@wordpress/block-editor';
import { useSelect, withSelect } from '@wordpress/data';
import { compose } from '@wordpress/compose';
import { createContext, useEffect } from '@wordpress/element';

import { CourseOutlinePlaceholder } from './placeholder';
import { COURSE_STORE } from '../store';
import { useBlocksCreator } from '../use-block-creator';
import { OutlineBlockSettings } from './settings';
import { __ } from '@wordpress/i18n';
import {
	withColorSettings,
	withDefaultBlockStyle,
} from '../../../shared/blocks/settings';

/**
 * A React context which contains the attributes and the setAttributes callback of the Outline block.
 */
export const OutlineAttributesContext = createContext();

/**
 * Edit course outline block component.
 *
 * @param {Object}   props               Component props.
 * @param {string}   props.clientId      Block client ID.
 * @param {string}   props.className     Custom class name.
 * @param {Object[]} props.structure     Course module and lesson blocks.
 * @param {Object}   props.attributes    Block attributes.
 * @param {Function} props.setAttributes Block setAttributes callback.
 * @param {Object}   props.borderColor   Border color.
 */
const EditCourseOutlineBlock = ( {
	clientId,
	className,
	structure,
	attributes,
	setAttributes,
	borderColor,
} ) => {
	// Toggle legacy metaboxes.
	useEffect( () => {
		if ( attributes.isPreview ) return;
		window.sensei_toggleLegacyMetaboxes( false );

		return () => {
			window.sensei_toggleLegacyMetaboxes( true );
		};
	}, [ attributes.isPreview ] );

	const { setBlocks } = useBlocksCreator( clientId );

	const isEmpty = useSelect(
		( select ) =>
			! select( 'core/block-editor' ).getBlocks( clientId ).length,
		[ clientId, structure ]
	);

	useEffect( () => {
		if ( structure?.length && ! attributes.isPreview ) {
			setBlocks( structure );
		}
	}, [ structure, setBlocks, attributes.isPreview ] );

	if ( isEmpty ) {
		return (
			<CourseOutlinePlaceholder
				addBlock={ ( type ) => setBlocks( [ { type } ], true ) }
			/>
		);
	}

	return (
		<>
			<OutlineAttributesContext.Provider
				value={ {
					outlineAttributes: attributes,
					outlineSetAttributes: setAttributes,
				} }
			>
				<OutlineBlockSettings
					collapsibleModules={ attributes.collapsibleModules }
					setCollapsibleModules={ ( value ) =>
						setAttributes( { collapsibleModules: value } )
					}
				/>

				<section
					className={ className }
					style={ { borderColor: borderColor.color } }
				>
					<InnerBlocks
						allowedBlocks={ [
							'sensei-lms/course-outline-modules',
							'sensei-lms/course-outline-lessons',
						] }
						template={ [
							[ 'sensei-lms/course-outline-modules' ],
							[ 'sensei-lms/course-outline-lessons' ],
						] }
						templateLock="all"
					/>
				</section>
			</OutlineAttributesContext.Provider>
		</>
	);
};

const selectors = ( select ) => ( {
	structure: select( COURSE_STORE ).getStructure(),
} );

export default compose(
	withSelect( selectors ),
	withColorSettings( {
		borderColor: {
			style: 'border-color',
			label: __( 'Border color', 'sensei-lms' ),
		},
	} ),
	withDefaultBlockStyle()
)( EditCourseOutlineBlock );
