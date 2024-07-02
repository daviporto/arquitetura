export type JobApplicationResponse = {
	data: JobApplication[];
};

export type JobApplication = {
	id: number;
	job_id: number;
	user_id: number;
	status: JobStatus;
	cover_letter: string;
	candidates?: Candidate[];
	created_at?: string;
	updated_at?: string;
	job?: ApplicationJob;
};

export type Candidate = {
	id: number;
	name: string;
	type: string;
	phone: string;
	email: string;
	email_verified_at: null;
	about_me: null | string;
	profile_picture_path: null;
};

export type ApplicationJob = {
	id: number;
	name: string;
	description: string;
	is_available: number;
	applications_amount: number;
	salary: number;
	salary_time_unit: string;
	accept_application_until: string;
	work_model: string;
	employment_type: string;
	week_workload: number;
	location: string;
	company_id: number;
	created_at: string;
	updated_at: string;
	user_id: number;
};

export type JobStatus = 'pending' | 'approved' | 'rejected' | 'canceled' | 'hired' | 'in_progress'

export type JobApplicationIncludeOption = 'job' | 'candidates';

export type JobsIncludeOption = 'company' | 'competences'
