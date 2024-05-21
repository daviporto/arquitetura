import { Base } from 'templates/Base/Base';
import { useQuery } from '@tanstack/react-query';
import { Input } from 'components/Input/Input';
import { Button } from 'components/Button/Button';
import { JobItem } from 'components/JobItem/JobItem';
import { JobFilter } from 'components/JobFilter/JobFilter';
import { useJob } from 'hooks/useJob/useJob';
import { Children } from 'react';
import { LoadingJobs } from './LoadingJobs';
import { NoJobs } from './NoJobs';

import * as S from './Jobs.styles';
import { JobsUrl } from 'utils/urls';
import { CreateJobHeader } from 'components/CreateJobHeader/CreateJobHeader';

export const Jobs = () => {
	const { findJobs } = useJob();

	const { data: jobsData, isLoading } = useQuery({
		queryKey: [`/${JobsUrl}`],
		queryFn: () => findJobs(),
	});

	const noJobs = !jobsData && !isLoading;
	const jobs = jobsData?.data.data;

	return (
		<Base>
      <CreateJobHeader title="Vagas" />
			<S.SearchWrapper>
				<Input placeholder="Buscar vaga" />
				<Button>Buscar</Button>
			</S.SearchWrapper>
			<S.OpportunitiesWrapper>
				<JobFilter />
				<S.Opportunities>
					<h5>Todas as Vagas</h5>
					{isLoading && <LoadingJobs />}
					{noJobs && <NoJobs />}
					{jobs && <p>{`Mostrando todos os ${jobs.length} resultados`}</p>}
					{Children.toArray(jobs?.map((job) => <JobItem {...job} />))}
				</S.Opportunities>
			</S.OpportunitiesWrapper>
		</Base>
	);
};
