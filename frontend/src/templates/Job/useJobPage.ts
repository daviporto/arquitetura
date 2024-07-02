import { useQuery } from '@tanstack/react-query';
import { BreadcrumbPath } from 'components/Breadcrumb/Breadcrumb';
import { JobToBeRemoved } from 'components/ModalRemoveJob';
import { useJob } from 'hooks/useJob';
import { Job, JobResponse } from 'protocols/external/job/job';
import { useState } from 'react';
import { useLoggedUserStore } from 'stores/loggedUserStore';
import { getInitialData } from 'utils/initialData';
import { JobRouteConst } from 'utils/routes';
import { HomeUrl, JobUrl, JobsUrl } from 'utils/urls';

export type UseJobPageProps = {
	initialData: Job;
};

export const useJobPage = ({ initialData }: UseJobPageProps) => {
	const [editModalOpen, setEditModalOpen] = useState(false);
	const [removeModalOpen, setRemoveModalOpen] = useState(false);
	const [, setJobToBeRemoved] = useState<JobToBeRemoved | null>(null);
	const { type, loggedUserId } = useLoggedUserStore((state) => ({
		type: state.type,
		loggedUserId: state.id,
	}));
	const { findJob } = useJob();

	const { data: response, isLoading, refetch } = useQuery({
		queryKey: [`/${JobRouteConst(initialData.id)}`],
		queryFn: () =>
			findJob({
				job_id: initialData.id,
				includes: ['company', 'competences'],
			}),
		initialData: getInitialData<JobResponse>({
			initialData: {
				data: {
					...initialData,
				},
			},
		}),
	}, );

	const job = response?.data.data;

	const paths: BreadcrumbPath[] = [
		{
			name: 'Home',
			url: `/${HomeUrl}`,
		},
		{
			name: 'Vagas',
			url: `/${JobsUrl}`,
		},
		{
			name: job?.name || initialData.name,
			url: `/${JobUrl(initialData.id)}`,
		},
	];

	const onRemoveJobClick = () => {
		setJobToBeRemoved({
			id: initialData.id,
			name: job.name || initialData.name,
			companyName: initialData.company!.name,
		});

		setRemoveModalOpen(true);
	};

	return {
		job,
		isLoading,
		editModalOpen,
		removeModalOpen,
		setRemoveModalOpen,
		setEditModalOpen,
		type,
		setJob: setJobToBeRemoved,
		loggedUserId,
		paths,
		onRemoveJobClick,
    refetch
	};
};
