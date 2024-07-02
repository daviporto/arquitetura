import {
	QueryObserverResult,
	RefetchOptions,
	useQueryClient,
} from '@tanstack/react-query';
import { AxiosResponse } from 'axios';
import { useJobApplication } from 'hooks/useJobApplication';
import { JobApplicationResponse } from 'protocols/external/job/job-application';
import { useState } from 'react';
import { SubmitHandler, useForm } from 'react-hook-form';
import { JobApplicationRouteConst } from 'utils/routes';
import { JobUrl, JobsUrl } from 'utils/urls';

export type UseModalCoverLetterProps = {
	job_id: number;
	refetch: (
		options?: RefetchOptions | undefined,
	) => Promise<
		QueryObserverResult<AxiosResponse<JobApplicationResponse, any>, Error>
	>;
};

export type JobApplicationInputs = {
	cover_letter: string;
};

export const useModalCoverLetter = ({
	job_id,
	refetch,
}: UseModalCoverLetterProps) => {
	const [isLoading, setIsLoading] = useState(false);
	const [open, setOpen] = useState(false);

	const queryClient = useQueryClient();

	const {
		handleSubmit,
		formState: { errors, isValid },
		control,
	} = useForm<JobApplicationInputs>({
		mode: 'onBlur',
	});

	const { createJobApplication } = useJobApplication();

	const onSubmit: SubmitHandler<JobApplicationInputs> = async (data, event) => {
		event?.preventDefault();

		setIsLoading(true);

		const res = await createJobApplication({
			job_id,
			cover_letter: data.cover_letter,
		});

		if (res && res?.error) {
			setIsLoading(false);
			return;
		}

		await queryClient.invalidateQueries({
			queryKey: [
				`/${JobApplicationRouteConst}`,
				`/${JobUrl(job_id)}`,
				`/${JobsUrl}`,
			],
		});

		await refetch();

		setOpen(false);
		setIsLoading(false);
	};

	return {
		open,
		setOpen,
		onSubmit,
		control,
		handleSubmit,
		errors,
		isValid,
		isLoading,
	};
};
