import { __ } from '@wordpress/i18n';
import { CourseIcon as icon } from '../../../icons';

import edit from './edit';
import save from './save';

export default {
	name: 'sensei-lms/course-outline',
	category: 'sensei-lms',
	supports: {
		html: false,
		multiple: false,
	},
	attributes: {
		id: {
			type: 'int',
		},
	},
	title: __( 'Course Outline', 'sensei-lms' ),
	description: __( 'Manage your Sensei LMS course outline.', 'sensei-lms' ),
	keywords: [ __( 'Outline', 'sensei-lms' ), __( 'Course', 'sensei-lms' ) ],
	icon,
	edit,
	save,
};
