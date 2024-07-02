import { useQuery } from '@tanstack/react-query';
import { JobPageButtonProps } from 'components/JobPageButton';
import { useJobApplication } from 'hooks/useJobApplication';
import { JobApplicationRouteConst } from 'utils/routes';

export type UseJobPageButtonProps = JobPageButtonProps;

export const useJobPageButton = ({ job, user }: UseJobPageButtonProps) => {
	const { findJobApplications } = useJobApplication();

	const { data: response, isLoading, refetch } = useQuery({
		queryKey: [`/${JobApplicationRouteConst}`],
		queryFn: () =>
			findJobApplications({
				jobsId: [job.id],
				candidatesId: [user.id],
				includes: ['candidates', 'job'],
			}),
	});

	const data = response?.data;

	return {
		data,
		isLoading,
    refetch
	};
};
