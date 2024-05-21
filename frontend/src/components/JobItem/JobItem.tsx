import Image from 'next/image';
import { Button } from 'components/Button/Button';
import { MediaMatch } from 'components/MediaMatch/MediaMatch';
import { Job } from 'protocols/external/job/job';
import { filterJobLocation } from 'utils/job';
import { JobPill, JobPillProps } from './JobPill';

import * as S from './JobItem.styles';

export type JobItemProps = {} & Job;

export const JobItem = ({
	name,
	is_available,
	applications_amount,
	salary,
	salary_time_unit,
	employment_type,
	work_model,
	location,
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
				<Button disabled={!is_available}>Aplicar</Button>
				<S.ProgressWrapper>
					<S.ProgressDiv $progress={(applicants / applications_amount) * 100} />
				</S.ProgressWrapper>
				<S.JobMaxInfo>
					<p>{`${applicants} aplicados`}</p>
					<p>{`-`}</p>
					<p>{`Máximo ${applications_amount}`}</p>
				</S.JobMaxInfo>
			</S.JobApplicationInfo>
		</S.Wrapper>
	);
};
