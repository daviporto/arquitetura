import { Button } from 'components/Button';
import { useCoverLetter } from 'hooks/contexts/CoverLetter';
import { JobStatus } from 'protocols/external/job/job-application';

export type SeeApplicationProps = {
	id: number;
	user_id: number;
	jobId: number;
	name: string;
	email: string;
	phone: string;
	coverLetter: string;
	status: JobStatus;
};

export const SeeApplication = ({
	id,
	user_id,
	jobId,
	name,
	status,
	email,
	phone,
	coverLetter,
}: SeeApplicationProps) => {
	const { setOpen, setCoverLetter } = useCoverLetter();

	const user = {
		user_id,
		name,
		email,
		phone,
	};

	const jobApplication = {
		id,
		cover_letter: coverLetter,
		status,
		jobId,
	};

	const handleClick = () => {
		setOpen(true);

		setCoverLetter({
			user,
			jobApplication,
		});
	};

	return (
		<Button variant="secondary" onClick={handleClick}>
			Ver Candidatura
		</Button>
	);
};
