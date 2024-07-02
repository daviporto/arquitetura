export type EmploymentType = 'part-time' | 'full-time';

export type SalaryTimeUnit = 'month' | 'hour' | 'week' | 'day';

export type WorkModel = 'onSite' | 'hybrid' | 'homeOffice';

export type JobCompetence = {
	id: number;
	name: string;
	description: string;
	type: string;
};

export type Job = {
	id: number;
	name: string;
	description: string;
	is_available: boolean;
	applications_amount: number;
	salary: number;
	salary_time_unit: SalaryTimeUnit;
	accept_application_until: string;
	work_model: WorkModel;
	employment_type: EmploymentType;
	week_workload: number;
	location: string;
	company_id: number;
	user_id: number;
	competences?: JobCompetence[];
	company?: JobCompany;
	applications_count: number;
};

export type JobResponse = {
	data: Job;
};

export type JobsResponse = {
	data: Job[];
};

export type JobCompany = {
	id: number;
	name: string;
	description: string;
	phone: string;
	email: string;
	cnpj: string;
	fantasy_name: string;
	location: string | null;
};

export type PostJobBody = Omit<
	Job,
	'user_id' | 'id' | 'created_at' | 'applications_count' | 'updated_at'
>;

export type PutJobBody =  Omit<
	Job,
	'user_id' | 'id' | 'created_at'  | 'updated_at'
>;

export type JobIncludeOption = 'competences' | 'company';

export const workModelOptions: WorkModel[] = ['onSite', 'hybrid', 'homeOffice'];

export const employmentTypeOptions: EmploymentType[] = [
	'part-time',
	'full-time',
];

export const salaryTimeUnitOptions: SalaryTimeUnit[] = [
	'day',
	'hour',
	'week',
	'month',
];
