import { useQuery } from '@tanstack/react-query';
import { JobPageButtonProps } from 'components/JobPageButton';
import { useJobApplication } from 'hooks/useJobApplication';

export type UseJobPageButtonProps = JobPageButtonProps;

export const useJobPageButton = ({ job, user }: UseJobPageButtonProps) => {
	const { findJobApplications } = useJobApplication();

	const { data: response, isLoading } = useQuery({
		queryKey: [
			`/${findJobApplications({
				jobsId: [job.id],
			})}`,
		],
		queryFn: () =>
			findJobApplications({
				jobsId: [job.id],
				candidatesId: [user.id],
        includes: ['candidates', 'job']
			}),
	});

	const data = response?.data;

	return {
		data,
		isLoading,
	};
};
