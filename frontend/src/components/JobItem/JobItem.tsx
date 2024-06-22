import { Button } from 'components/Button';
import { JobCapacity } from 'components/JobCapacity';
import { MediaMatch } from 'components/MediaMatch';
import Image from 'next/image';
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
  applications_count
}: JobItemProps) => {
	const applicants = Math.floor(Math.random() * applications_amount);

	const jobPillProps: JobPillProps = {
		employment_type,
		work_model,
		salary,
		salary_time_unit,
	};

	return (
		<S.Wrapper>
			<S.JobInfoWrapper>
				<Image
					src={`https://source.unsplash.com/random/?company_logo&${applicants}`}
					width="64"
					height="64"
					alt={`company avatar`}
					style={{
						borderRadius: '50%',
					}}
					loading="lazy"
					quality={100}
				/>
				<S.JobInfo>
					<p>{name}</p>
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
