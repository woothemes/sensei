/**
 * WordPress dependencies
 */
import { DropdownMenu } from '@wordpress/components';
import {
	render,
	useEffect,
	useState,
	useMemo,
	useCallback,
} from '@wordpress/element';
import { moreVertical } from '@wordpress/icons';
import { applyFilters } from '@wordpress/hooks';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import StudentModal from '../student-modal';

/**
 * Student action menu.
 *
 * @param {Object} props
 * @param {string} props.studentId          Student's user id
 * @param {string} props.studentName        Student's user name.
 * @param {string} props.studentDisplayName Student's displayName.
 */
export const StudentActionMenu = ( {
	studentId,
	studentName,
	studentDisplayName,
} ) => {
	const [ action, setAction ] = useState( '' );
	const [ isModalOpen, setModalOpen ] = useState( false );

	const addToCourse = useCallback( () => {
		setAction( 'add' );
		setModalOpen( true );
	}, [ setAction, setModalOpen ] );

	const removeFromCourse = useCallback( () => {
		setAction( 'remove' );
		setModalOpen( true );
	}, [ setAction, setModalOpen ] );

	const resetProgress = useCallback( () => {
		setAction( 'reset-progress' );
		setModalOpen( true );
	}, [ setAction, setModalOpen ] );

	const defaultControls = useMemo(
		() => [
			{
				title: __( 'Add to Course', 'sensei-lms' ),
				onClick: () => addToCourse(),
			},
			{
				title: __( 'Remove from Course', 'sensei-lms' ),
				onClick: () => removeFromCourse(),
			},
			{
				title: __( 'Reset Progress', 'sensei-lms' ),
				onClick: () => resetProgress(),
			},
			{
				title: __( 'Grading', 'sensei-lms' ),
				onClick: () =>
					window.open(
						`admin.php?page=sensei_grading&view=ungraded&s=${ studentName }`,
						'_self'
					),
			},
		],
		[ studentName, addToCourse, removeFromCourse, resetProgress ]
	);

	const [ controls, setControls ] = useState( defaultControls );

	const closeModal = useCallback(
		( needsReload ) => {
			if ( needsReload ) {
				window.location.reload();
			}
			setModalOpen( false );
		},
		[ setModalOpen ]
	);

	useEffect( () => {
		/**
		 * Filters controls for the single student action menu.
		 *
		 * @since 4.11.0
		 *
		 * @param {Array}    controls     Controls for the single student action menu.
		 * @param {Function} setAction    Selected action.
		 * @param {Function} setModalOpen The callback to run when the modal is closed.
		 *
		 * @return {Array} Filtered controls.
		 */
		applyFilters(
			'senseiStudentActionMenuControls',
			[ ...defaultControls ],
			setAction,
			setModalOpen
		).then( ( response ) => {
			setControls( response );
		} );
	}, [ defaultControls ] );

	const defaultStudentModal = (
		<StudentModal
			action={ action }
			onClose={ closeModal }
			students={ [ studentId ] }
			studentDisplayName={ studentDisplayName }
		/>
	);

	/** This filter is documented in ../student-bulk-action-button/index.js */
	const modal = applyFilters(
		'senseiStudentBulkActionModal',
		defaultStudentModal,
		action,
		closeModal,
		[ studentId ],
		studentDisplayName
	);

	return (
		<>
			<DropdownMenu
				icon={ moreVertical }
				label={ __( 'Select an action', 'sensei-lms' ) }
				controls={ controls }
			/>

			{ isModalOpen && modal }
		</>
	);
};

Array.from( document.getElementsByClassName( 'student-action-menu' ) ).forEach(
	( actionMenu ) => {
		render(
			<StudentActionMenu
				studentId={ actionMenu?.dataset?.userId }
				studentName={ actionMenu?.dataset?.userName }
				studentDisplayName={ actionMenu?.dataset?.userDisplayName }
			/>,
			actionMenu
		);
	}
);
