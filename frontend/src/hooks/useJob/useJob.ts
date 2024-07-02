import { AxiosResponse } from 'axios';
import {
	JobResponse,
	JobsResponse,
	PostJobBody,
	PutJobBody,
} from 'protocols/external/job/job';
import { toast } from 'react-toastify';
import { DeleteClient } from 'services/httpClient/delete';
import { GetClient } from 'services/httpClient/get';
import { PostClient } from 'services/httpClient/post';
import { PutClient } from 'services/httpClient/put';
import { JobFilters } from 'templates/Jobs/Jobs';
import { UNEXPECTED_ERROR } from 'utils/errors';
import {
	DeleteJobRouteConst,
	GetJobRouteConst,
	GetJobRouteConstProps,
	GetJobsRouteConst,
	PostJobRouteConst,
	PutJobRouteConst,
} from 'utils/routes';

export type FindJobsProps = JobFilters;

export type FindJobProps = GetJobRouteConstProps;

export type CreateJobProps = {
	job: PostJobBody;
};

export type UpdateJobProps = {
	job: PutJobBody;
	id: number;
};

export type DeleteJobProps = {
	id: number;
};

export const useJob = () => {
	const findJob = async ({ job_id, includes }: FindJobProps) => {
		const getClient = new GetClient();

		return await getClient.get<AxiosResponse<JobResponse>>({
			url: `/${GetJobRouteConst({ job_id, includes })}`,
		});
	};

	const findJobs = async ({
		name,
		work_models,
		employment_types,
		salary_time_units,
		salary_from,
		salary_to,
	}: FindJobsProps) => {
		const getClient = new GetClient();

		return await getClient.get<AxiosResponse<JobsResponse>>({
			url: `/${GetJobsRouteConst({
				name,
				employment_types: Array.from(employment_types!.values()),
				salary_time_units: Array.from(salary_time_units!.values()),
				work_models: Array.from(work_models!.values()),
				salary_from,
				salary_to,
			})}`,
		});
	};

	const createJob = async ({ job }: CreateJobProps) => {
		const postClient = new PostClient();

		const body: PostJobBody = {
			...job,
		};

		try {
			await postClient.post({
				url: `/${PostJobRouteConst}`,
				body,
			});

			toast.success('Vaga criada com sucesso!');
		} catch (error) {
			if (error instanceof Error) {
				toast.error(error.response.data.message);
				return { error: error.response.data.message };
			}

			toast.error(UNEXPECTED_ERROR);
			return { error: UNEXPECTED_ERROR };
		}
	};

	const updateJob = async ({ job, id }: UpdateJobProps) => {
		const postClient = new PutClient();

		const body: PutJobBody = {
			...job,
		};

		try {
			await postClient.put({
				url: `/${PutJobRouteConst(id)}`,
				body,
			});

			toast.success('Vaga atualizada com sucesso!');
		} catch (error) {
			if (error instanceof Error) {
				toast.error(error.response.data.message);
				return { error: error.response.data.message };
			}

			toast.error(UNEXPECTED_ERROR);
			return { error: UNEXPECTED_ERROR };
		}
	};

	const deleteJob = async ({ id }: DeleteJobProps) => {
		const deleteClient = new DeleteClient();

		try {
			await deleteClient.delete({
				url: `/${DeleteJobRouteConst(id)}`,
			});

			toast.success('Vaga removida com sucesso!');
		} catch (error) {
			if (error instanceof Error) {
				toast.error(error.response.data.message);
				return { error: error.response.data.message };
			}

			toast.error(UNEXPECTED_ERROR);
			return { error: UNEXPECTED_ERROR };
		}
	};

	return {
		findJobs,
		findJob,
		createJob,
		updateJob,
		deleteJob,
	};
};
