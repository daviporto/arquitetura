import { QueryObserverResult, RefetchOptions } from '@tanstack/react-query';
import { AxiosResponse } from 'axios';
import { Button } from 'components/Button';
import { Input } from 'components/Input';
import { Label } from 'components/Label';
import { BaseModal } from 'components/Modals/BaseModal';
import { Pill } from 'components/Pill';
import { Textarea } from 'components/Textarea';
import Link from 'next/link';
import { JobApplicationResponse } from 'protocols/external/job/job-application';
import { UserType } from 'protocols/external/user/user';
import { jobStatusPillVariant, translateJobApplicationStatus } from 'utils/job';
import { MaxLength } from 'utils/maxLengths';
import { ApplicantUrl } from 'utils/urls';
import { useModalApplication } from '.';
import * as S from './ModalApplication.styles';

export type ModalApplicationProps = {
	type: UserType;
	refetch?: (
		options?: RefetchOptions | undefined,
	) => Promise<
		QueryObserverResult<AxiosResponse<JobApplicationResponse, any>, Error>
	>;
};

export const ModalApplication = ({ type, refetch }: ModalApplicationProps) => {
	const { open, isLoading, setOpen, coverLetter, handleResignation } =
  useModalApplication({
    refetch
  });

	if (coverLetter === null) return null;

	const { user, jobApplication } = coverLetter;

	return (
		<BaseModal
			title={`Carta de Apresentação de ${user.name}`}
			open={open}
			setOpen={setOpen}
		>
			<S.Wrapper>
				<div>
					<Label labelText="Status" />
					<Pill
						text={translateJobApplicationStatus[jobApplication.status]}
						variant={jobStatusPillVariant[jobApplication.status]}
					/>
				</div>
				<Input
					label="Nome Completo"
					readOnly
					value={user.name}
					maxLength={MaxLength.name}
				/>
				<Input
					label="Email"
					readOnly
					value={user.email}
					maxLength={MaxLength.email}
				/>
				<Input label="Celular" readOnly value={user.phone} />

				<Textarea
					label="Carta de Apresentação"
					readOnly
					defaultValue={jobApplication.cover_letter}
				/>

				{type === 'employee' && jobApplication.status !== 'canceled' && (
					<Button
						variant="secondary"
						fullWidth
						disabled={isLoading}
						isLoading={isLoading}
						onClick={() => handleResignation(jobApplication.id)}
					>
						Desistir
					</Button>
				)}

				{type === 'recruiter' && (
					<Link
						href={`/${ApplicantUrl(coverLetter.user.user_id)}`}
						target="_blank"
					>
						<Button fullWidth>Visitar Perfil</Button>
					</Link>
				)}
			</S.Wrapper>
		</BaseModal>
	);
};
