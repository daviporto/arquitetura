import { Button } from 'components/Button';
import { ModalApplication } from 'components/ModalApplication';
import { ModalCoverLetter } from 'components/ModalCoverLetter';
import { SeeApplication } from 'components/SeeApplication';
import { CoverLetterProvider } from 'hooks/contexts/CoverLetter';
import Link from 'next/link';
import { UserEnum, UserType } from 'protocols/external/user/user';
import { JobUrlApplicants } from 'utils/urls';
import { useJobPageButton } from '.';

type JobPageButtonJob = {
	id: number;
	is_available: boolean;
};

type JobPageButtonUser = {
	id: number;
	type: UserType;
};

export type JobPageButtonProps = {
	user: JobPageButtonUser;
	job: JobPageButtonJob;
};

export const JobPageButton = ({ user, job }: JobPageButtonProps) => {
	const { data, isLoading, refetch } = useJobPageButton({
		user,
		job,
	});

	if (isLoading) return <Button isLoading={isLoading}></Button>;

	if (user.type === UserEnum.RECRUITER)
		return (
			<Link href={`/${JobUrlApplicants(job.id)}`}>
				<Button>Visualizar Candidatos</Button>
			</Link>
		);

	const disabled = !job.is_available || !!data?.data.length;
	const application = data?.data[0];
	const applicant = application?.candidates![0];

	if (disabled && user.type === UserEnum.EMPLOYEE && applicant) {
		return (
			<CoverLetterProvider>
				<ModalApplication refetch={refetch} type={user.type}  />
				<SeeApplication
          id={data?.data[0].id!}
					user_id={user.id}
					jobId={job.id}
					name={applicant.name}
					email={applicant.email}
					phone={applicant.phone}
					coverLetter={data?.data[0].cover_letter!}
          status={application.status}
				/>
			</CoverLetterProvider>
		);
	}

	return <ModalCoverLetter refetch={refetch} jobId={job.id} disabled={isLoading} />;
};
