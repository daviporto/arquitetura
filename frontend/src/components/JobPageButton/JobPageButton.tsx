import { Button } from 'components/Button';
import { UserType } from 'protocols/external/user/user';
import { ModalCoverLetter } from 'components/ModalCoverLetter';
import { useJobPageButton } from 'hooks/useJobPageButton/useJobPageButton';
import Link from 'next/link';
import { JobUrlApplicants } from 'utils/urls';

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
	const { data, isLoading } = useJobPageButton({
		user,
		job,
	});

	if (user.type === 'recruiter')
		return (
			<Link href={`/${JobUrlApplicants(job.id)}`}>
				<Button>Visualizar Candidatos</Button>
			</Link>
		);

	if (isLoading) return <Button isLoading={isLoading}></Button>;

	const disabled = !job.is_available || !!data?.data.length;

	return <ModalCoverLetter jobId={job.id} disabled={disabled || isLoading} />;
};
