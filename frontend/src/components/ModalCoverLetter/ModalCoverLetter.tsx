import { Button } from 'components/Button';
import { BaseModal } from 'components/Modals/BaseModal';
import { Textarea } from 'components/Textarea';
import { Controller } from 'react-hook-form';
import { REQUIRED_JOB_APP_COVER_LETTER } from 'utils/errors';
import { useModalCoverLetter } from '.';
import { QueryObserverResult, RefetchOptions } from '@tanstack/react-query';
import * as S from './ModalCoverLetter.styles';
import { AxiosResponse } from 'axios';
import { JobApplicationResponse } from 'protocols/external/job/job-application';

export type ModalCoverLetterProps = {
	disabled: boolean;
	jobId: number;
	refetch: (
		options?: RefetchOptions | undefined,
	) => Promise<
		QueryObserverResult<AxiosResponse<JobApplicationResponse, any>, Error>
	>;
};

export const ModalCoverLetter = ({
	disabled,
	jobId,
  refetch
}: ModalCoverLetterProps) => {
	const {
		open,
		isLoading,
		setOpen,
		isValid,
		errors,
		handleSubmit,
		onSubmit,
		control,
	} = useModalCoverLetter({
		job_id: jobId,
    refetch,
	});

	return (
		<BaseModal
			trigger={<Button disabled={disabled}>Aplicar</Button>}
			open={open}
			setOpen={setOpen}
			title="Deseja aplicar para essa vaga?"
			confirmButtonText="Aplicar"
			isConfirmButtonDisabled={!isValid}
			isConfirmButtonLoading={isLoading}
			confirmHandler={handleSubmit(onSubmit)}
		>
			<S.Wrapper>
				<Controller
					rules={{
						required: REQUIRED_JOB_APP_COVER_LETTER,
					}}
					control={control}
					name="cover_letter"
					render={({ field: { ...field } }) => (
						<Textarea
							{...field}
							placeholder="Escreva uma carta de apresentação"
							error={errors.cover_letter}
						/>
					)}
				/>
			</S.Wrapper>
		</BaseModal>
	);
};
