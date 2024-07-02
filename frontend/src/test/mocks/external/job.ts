import { Job } from 'protocols/external/job/job';

export const jobMock: Job = {
	id: 1,
	name: 'Mathematical Scientist',
	description:
		'Non eum odit labore mollitia quis doloribus. Molestiae et veritatis similique et iste animi possimus. Quis eius doloribus ea non voluptatum repudiandae et. Esse voluptatibus vel dolor qui exercitationem perferendis esse tempora.',
	is_available: true,
	applications_amount: 719,
	salary: 21849,
	salary_time_unit: 'day',
	accept_application_until: '2025-04-26 08:05:04',
	work_model: 'hybrid',
	employment_type: 'part-time',
	week_workload: 6,
	location: '365 Hudson Hills Apt. 214\nYosttown, WI 61832',
	company_id: 1,
	user_id: 16,
	competences: [],
	company: {
		id: 1,
		name: 'Wilderman, Gleichner and Carter',
		description: 'Aut est nesciunt sed et deleniti vel.',
		phone: '+17326249069',
		email: 'floyd.oreilly@jacobson.org',
		cnpj: '077568098014',
		fantasy_name: 'Itzel Crist Ltd',
		location: null,
	},
	applications_count: 15,
};
