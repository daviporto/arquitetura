import {
	QueryObserverResult,
	RefetchOptions,
	useQueryClient,
} from '@tanstack/react-query';
import { AxiosResponse } from 'axios';
import { useCoverLetter } from 'hooks/contexts/CoverLetter';
import { useJobApplication } from 'hooks/useJobApplication';
import { JobApplicationResponse } from 'protocols/external/job/job-application';
import { useState } from 'react';
import { toast } from 'react-toastify';
import { JobApplicationRouteConst } from 'utils/routes';
import { JobUrl } from 'utils/urls';

export type UseModalApplicationProps = {
	refetch?: (
		options?: RefetchOptions | undefined,
	) => Promise<
		QueryObserverResult<AxiosResponse<JobApplicationResponse, any>, Error>
	>;
};

export const useModalApplication = ({ refetch }: UseModalApplicationProps) => {
	const [isLoading, setIsLoading] = useState(false);
	const { setOpen, open, coverLetter } = useCoverLetter();
	const { resignApplication } = useJobApplication();
	const queryClient = useQueryClient();

	const handleResignation = async (job_application_id: number) => {
		setIsLoading(true);

		const res = await resignApplication({
			job_application_id,
		});

		setIsLoading(false);
		if (res?.error) {
			toast.error(res.error);
		}

		await queryClient.invalidateQueries({
			queryKey: [
				`/${JobApplicationRouteConst}`,
				`/${JobUrl(coverLetter?.jobApplication.jobId!)}`,
			],
		});

		!!refetch && (await refetch());

		setOpen(false);
	};

	return {
		open,
		setOpen,
		isLoading,
		coverLetter,
		handleResignation,
	};
};
