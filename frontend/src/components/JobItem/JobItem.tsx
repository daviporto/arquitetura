import { Button } from 'components/Button';
import { JobCapacity } from 'components/JobCapacity';
import { MediaMatch } from 'components/MediaMatch';
import Link from 'next/link';
import { Job } from 'protocols/external/job/job';
import { filterJobLocation } from 'utils/job';
import { JobUrl } from 'utils/urls';
import * as S from './JobItem.styles';
import { JobPill, JobPillProps } from '.';

export type JobItemProps = {} & Job;

export const JobItem = ({
	id,
	name,
	applications_amount,
	salary,
	salary_time_unit,
	employment_type,
	work_model,
	location,
  applications_count,
  company,
}: JobItemProps) => {
	const jobPillProps: JobPillProps = {
		employment_type,
		work_model,
    salary: salary * 100,
		salary_time_unit,
	};

	return (
		<S.Wrapper>
			<S.JobInfoWrapper>
				<S.JobInfo>
					<p>{company?.name} &bull; {name}</p>
					{!!location && (
						<S.JobLocationInfo>{filterJobLocation(location)}</S.JobLocationInfo>
					)}
					<MediaMatch $greaterThan="large">
						<S.PillWrapper>
							<JobPill {...jobPillProps} />
						</S.PillWrapper>
					</MediaMatch>
				</S.JobInfo>
			</S.JobInfoWrapper>

			<S.PillSmallScreen>
				<JobPill {...jobPillProps} />
			</S.PillSmallScreen>

			<S.JobApplicationInfo>
				<Link href={`/${JobUrl(id)}`}>
					<Button>Visualizar</Button>
				</Link>
				<JobCapacity
					applicants={applications_count}
					applications_amount={applications_amount}
				/>
			</S.JobApplicationInfo>
		</S.Wrapper>
	);
};
