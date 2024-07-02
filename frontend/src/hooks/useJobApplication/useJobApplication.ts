import { AxiosResponse } from 'axios';
import { PatchJobApplicationBody, PostJobApplicationBody } from 'protocols/external/job/application';
import { JobApplicationResponse } from 'protocols/external/job/job-application';
import { toast } from 'react-toastify';
import { GetClient } from 'services/httpClient/get';
import { PatchClient } from 'services/httpClient/patch';
import { PostClient } from 'services/httpClient/post';
import { UNEXPECTED_ERROR } from 'utils/errors';
import {
	GetJobApplicationsRouteConst,
	GetJobApplicationsRouteConstProps,
	PatchJobApplicationRouteConst,
	PostJobApplicationRouteConst,
} from 'utils/routes';

export type UseJobApplicationProps = {};

export type CreateJobApplicationProps = {
	job_id: number;
	cover_letter: string;
};

export type ResignApplicationProps = {
	job_application_id: number;
};

export type FindJobApplicationProps =
	Partial<GetJobApplicationsRouteConstProps>;

export const useJobApplication = () => {
	const resignApplication = async ({
		job_application_id,
	}: ResignApplicationProps) => {
		const patchClient = new PatchClient();

		const body: PatchJobApplicationBody = {
      status: 'canceled'
		};

		try {
			await patchClient.patch({
				url: `/${PatchJobApplicationRouteConst(job_application_id)}`,
				body,
			});

			toast.success('Você desistiu da vaga!');
		} catch (error) {
			if (error instanceof Error) {
				toast.error(error.response.data.message);
				return { error: error.response.data.message };
			}

			toast.error(UNEXPECTED_ERROR);
			return { error: UNEXPECTED_ERROR };
		}
	};

	const createJobApplication = async ({
		job_id,
		cover_letter,
	}: CreateJobApplicationProps) => {
		const postClient = new PostClient();

		const body: PostJobApplicationBody = {
			cover_letter,
		};

		try {
			await postClient.post({
				url: `/${PostJobApplicationRouteConst(job_id)}`,
				body,
			});

			toast.success('Candidatura registrada com sucesso!');
		} catch (error) {
			if (error instanceof Error) {
				toast.error(error.response.data.message);
				return { error: error.response.data.message };
			}

			toast.error(UNEXPECTED_ERROR);
			return { error: UNEXPECTED_ERROR };
		}
	};

	const findJobApplications = async ({
		jobsId = [],
		candidatesId = [],
		includes = [],
	}: FindJobApplicationProps) => {
		const getClient = new GetClient();

		return await getClient.get<AxiosResponse<JobApplicationResponse>>({
			url: `/${GetJobApplicationsRouteConst({
				includes,
				candidatesId,
				jobsId,
			})}`,
		});
	};

	return {
		createJobApplication,
		findJobApplications,
		resignApplication,
	};
};
