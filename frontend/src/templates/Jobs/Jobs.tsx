import { useQuery } from '@tanstack/react-query';
import { JobFilter } from 'components/JobFilter';
import { JobItem } from 'components/JobItem';
import { useJob } from 'hooks/useJob';
import { Children, useEffect, useState } from 'react';
import { JobsRouteConst } from 'utils/routes';
import * as S from './Jobs.styles';
import { LoadingJobs } from './LoadingJobs';
import { NoJobs } from './NoJobs';
import {
	EmploymentType,
	SalaryTimeUnit,
	WorkModel,
} from 'protocols/external/job/job';
import { JobQuery } from 'components/JobQuery/JobQuery';
import { DEFAULT_JOB_FILTER } from 'utils/job';

export type JobFilters = {
	name?: string;
	salary_time_units?: Set<SalaryTimeUnit>;
	accept_application_until?: string;
	work_models?: Set<WorkModel>;
	employment_types?: Set<EmploymentType>;
	salary_to: number;
	salary_from: number;
};

export const Jobs = () => {
	const [filter, setFilter] = useState<JobFilters>(DEFAULT_JOB_FILTER);

	const { findJobs } = useJob();

	const {
		data: jobsData,
		isLoading,
		refetch,
		isFetching,
	} = useQuery({
		queryKey: [`/${JobsRouteConst}`],
		queryFn: () => findJobs({ ...filter }),
	});

	const noJobs = !jobsData && !isLoading;
	const jobs = jobsData?.data.data;

	useEffect(() => {
		refetch();
	}, [filter]);

	return (
		<>
			<S.SearchWrapper>
				<JobQuery filter={filter} setFilter={setFilter} />
			</S.SearchWrapper>
			<S.OpportunitiesWrapper>
				<JobFilter setFilter={setFilter} />
				<S.Opportunities>
					<h5>Todas as Vagas</h5>
					{(isLoading || isFetching) && <LoadingJobs />}
					{noJobs && <NoJobs />}
					{jobs && <p>{`Mostrando todos os ${jobs.length} resultados`}</p>}
					{Children.toArray(jobs?.map((job) => <JobItem {...job} />))}
				</S.Opportunities>
			</S.OpportunitiesWrapper>
		</>
	);
};
