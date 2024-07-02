import {
	EmploymentType,
	JobIncludeOption,
	SalaryTimeUnit,
	WorkModel,
} from 'protocols/external/job/job';
import { JobApplicationIncludeOption, JobsIncludeOption } from 'protocols/external/job/job-application';
import { UserIncludeOption } from 'protocols/external/user/user';

const returnUrlWithQueries = (defaultUrl: string, params: URLSearchParams) => {
	const queryString = params.toString();

	return `${defaultUrl}${queryString ? '?' + queryString : ''}`;
};

export type GetUserRouteConstProps = {
	user_id: number;
	includes?: UserIncludeOption[];
};

export type GetJobRouteConstProps = {
	job_id: number;
	includes?: JobIncludeOption[];
};

export type GetJobApplicationsRouteConstProps = {
	includes?: JobApplicationIncludeOption[];
	jobsId?: number[];
	candidatesId?: number[];
};

export type GetJobsRouteConstProps = {
	name?: string;
	employment_types?: EmploymentType[];
	salary_time_units?: SalaryTimeUnit[];
	work_models?: WorkModel[];
	salary_to: number;
	salary_from: number;
  includes?: JobsIncludeOption[];
};

const appendItems = (
	items: string[] | undefined,
	params: URLSearchParams,
	key: string,
) => {
	if (items?.length) {
		items.forEach((item) => params.append(key, item));
	}
};

export const GetAuthMeRouteConst = 'auth/me';

export const PostAuthLoginRouteConst = 'auth/login';

export const PostAuthLogOutRouteConst = 'auth/logout';

export const PostAuthVerifyRouteConst = 'auth/verify';

export const PostAuthRegisterRouteConst = 'auth/register';

export const PutUserRouteConst = 'user';

export const GetUsersRouteConst = 'user';

export const PostUserCompetenceRouteConst = 'user/competence';

export const PostUserAcademicRecordRouteConst = 'user/academic-records';

export const PostUserProfessionalExperiencesRouteConst =
	'user/professional-experiences';

export const DeleteUserProfessionalExperiencesRouteConst =
	'user/professional-experiences';

export const DeleteUserAcademicRecordRouteConst = 'user/academic-records';

export const DeleteUserCompetenceRouteConst = 'user/competence';

export const GetUserRouteConst = ({
	user_id,
	includes = [],
}: GetUserRouteConstProps) => {
	const params = new URLSearchParams();
	const userRoute = `user/show/${user_id}`;

	appendItems(includes, params, 'includes[]');

	return returnUrlWithQueries(userRoute, params);
};

export const JobsRouteConst = 'job';
export const GetJobsRouteConst = ({
	name,
	employment_types,
	salary_time_units,
	work_models,
	salary_from,
	salary_to,
  includes = ['company'],
}: GetJobsRouteConstProps) => {
	const params = new URLSearchParams();

	!!name && params.append('filters[name]', name);

	params.append('filters[salary_from]', String(salary_from));
	params.append('filters[salary_to]', String(salary_to));

	appendItems(employment_types, params, 'filters[employment_types][]');
	appendItems(salary_time_units, params, 'filters[salary_time_units][]');
	appendItems(work_models, params, 'filters[work_models][]');
  appendItems(includes, params, 'includes[]');

	return returnUrlWithQueries(JobsRouteConst, params);
};

export const JobRouteConst = (job_id: number) => `job/${job_id}`;

export const GetJobRouteConst = ({
	job_id,
	includes = [],
}: GetJobRouteConstProps) => {
	const params = new URLSearchParams();

	appendItems(includes, params, 'includes[]');

	return returnUrlWithQueries(JobRouteConst(job_id), params);
};

export const PostJobRouteConst = 'job';

export const PutJobRouteConst = (job_id: number) => `job/${job_id}`;

export const DeleteJobRouteConst = (job_id: number) => `job/${job_id}`;

export const PostJobApplicationRouteConst = (job_id: number) =>
	`job/${job_id}/application`;

export const PatchJobApplicationRouteConst = (job_application: number) =>
	`job-application/${job_application}/status`;

export const JobApplicationRouteConst = 'job-application';

export const GetJobApplicationsRouteConst = ({
	jobsId = [],
	candidatesId = [],
	includes = [],
}: GetJobApplicationsRouteConstProps) => {
	const params = new URLSearchParams();

	appendItems(includes, params, 'includes[]');

	if (candidatesId.length) {
		candidatesId.forEach((c) =>
			c !== null && c !== 0 && params.append('filters[candidates_id][]', c.toString()),
		);
	}

	if (jobsId.length) {
		jobsId.forEach((j) => params.append('filters[jobs_id][]', j.toString()));
	}

	return returnUrlWithQueries(JobApplicationRouteConst, params);
};

export const GetCompaniesRouteConst = 'company';
