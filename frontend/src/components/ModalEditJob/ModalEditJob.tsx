import { Button } from 'components/Button';
import { Input } from 'components/Input';
import { BaseModal } from 'components/Modals/BaseModal';
import { Select } from 'components/Select';
import {
	employmentTypeOptions,
	workModelOptions,
	salaryTimeUnitOptions,
	Job,
	JobResponse,
} from 'protocols/external/job/job';
import { Dispatch, SetStateAction } from 'react';
import { Controller } from 'react-hook-form';
import {
	REQUIRED_JOB_ACCEPT_APPLICATION,
	REQUIRED_JOB_APPLICATIONS_AMOUNT,
	REQUIRED_JOB_DESCRIPTION,
	REQUIRED_JOB_EMPLOYMENT_TYPE,
	REQUIRED_JOB_NAME,
	REQUIRED_JOB_SALARY,
	REQUIRED_JOB_SALARY_TIME_UNIT,
	REQUIRED_JOB_WORK_MODEL,
} from 'utils/errors';
import {
	translateEmploymentType,
	translateWorkModel,
	translateSalaryTimeUnit,
} from 'utils/job';
import { MaxLength } from 'utils/maxLengths';
import { formatToCurrency } from 'utils/money';
import { useModalEditJob } from './useModalEditJob';
import * as S from './ModalEditJob.styles';
import { Textarea } from 'components/Textarea';
import { QueryObserverResult, RefetchOptions } from '@tanstack/react-query';
import { AxiosResponse } from 'axios';

export type ModalEditJobProps = {
	open: boolean;
	setOpen: Dispatch<SetStateAction<boolean>>;
	job: Job;
	refetch?: (
		options?: RefetchOptions | undefined,
	) => Promise<QueryObserverResult<AxiosResponse<JobResponse, any>, Error>>;
};

export const ModalEditJob = ({ open, setOpen, job, refetch }: ModalEditJobProps) => {
	const {
		id,
		name,
		employment_type,
		work_model,
		salary,
		salary_time_unit,
		location,
		applications_amount,
		accept_application_until,
		description,
		company_id,
	} = job;

	const { control, register, errors, handleSubmit, onSubmit, isLoading } =
		useModalEditJob({
			setOpen,
			jobId: id,
			companyId: company_id,
      refetch,
		});

	return (
		<BaseModal
			title="Editar vaga"
			open={open}
			setOpen={setOpen}
			confirmButtonText="Editar"
			trigger={<Button>Editar</Button>}
			confirmHandler={handleSubmit(onSubmit)}
			isConfirmButtonLoading={isLoading}
			cancelButtonText="Cancelar"
		>
			<S.Wrapper>
				<Controller
					rules={{
						required: REQUIRED_JOB_NAME,
					}}
					control={control}
					name="name"
					defaultValue={name}
					render={({ field: { ...field } }) => (
						<Input
							{...field}
							label="Cargo*"
							placeholder="e.g Engenheiro de Software"
							error={errors.name}
							maxLength={MaxLength.title}
						/>
					)}
				/>

				<Controller
					rules={{
						required: REQUIRED_JOB_SALARY,
					}}
					control={control}
					name="salary"
					defaultValue={formatToCurrency(salary * 100)}
					render={({ field: { ...field } }) => (
						<Input
							{...field}
							onChange={({ target: { value } }) => {
								field.onChange(formatToCurrency(value));
							}}
							type="text"
							error={errors.salary}
							label="Salário*"
						/>
					)}
				/>

				<S.SelectWrapper>
					<Select
						options={employmentTypeOptions.map((type) => ({
							value: type,
							label: translateEmploymentType[type],
						}))}
						defaultValue={employment_type}
						register={register}
						requiredMessage={REQUIRED_JOB_EMPLOYMENT_TYPE}
						placeholder="Selecione o tipo de Contratação"
						name="employment_type"
						label="Tipo de Contratação*"
					/>

					<Select
						options={workModelOptions.map((type) => ({
							value: type,
							label: translateWorkModel[type],
						}))}
						defaultValue={work_model}
						register={register}
						requiredMessage={REQUIRED_JOB_WORK_MODEL}
						placeholder="Modelo de Trabalho"
						name="work_model"
						label="Modelo de Trabalho*"
					/>

					<Select
						options={salaryTimeUnitOptions.map((type) => ({
							value: type,
							label: translateSalaryTimeUnit[type],
						}))}
						defaultValue={salary_time_unit}
						register={register}
						requiredMessage={REQUIRED_JOB_SALARY_TIME_UNIT}
						placeholder="Selecione o período de pagamento"
						name="salary_time_unit"
						label="Período de pagamento*"
					/>
				</S.SelectWrapper>

				<Controller
					control={control}
					name="location"
					defaultValue={location}
					render={({ field: { ...field } }) => (
						<Input
							{...field}
							error={errors.location}
							maxLength={MaxLength.location}
							label="Localização"
						/>
					)}
				/>

				<Controller
					rules={{
						required: REQUIRED_JOB_APPLICATIONS_AMOUNT,
					}}
					control={control}
					name="applications_amount"
					defaultValue={applications_amount}
					render={({ field: { ...field } }) => (
						<Input
							{...field}
							type="number"
							min={1}
							error={errors.applications_amount}
							label="Número máximo de candidaturas"
						/>
					)}
				/>

				<Controller
					rules={{
						required: REQUIRED_JOB_ACCEPT_APPLICATION,
					}}
					control={control}
					name="accept_application_until"
					defaultValue={accept_application_until.split(' ')[0]}
					render={({ field: { ...field } }) => (
						<Input
							{...field}
							type="date"
							error={errors.accept_application_until}
							label="Prazo de candidatura"
						/>
					)}
				/>

				<Controller
					rules={{
						required: REQUIRED_JOB_DESCRIPTION,
					}}
					control={control}
					name="description"
					defaultValue={description}
					render={({ field: { ...field } }) => (
						<Textarea
							{...field}
							label="Descrição*"
							placeholder="Descreva um pouco sobre você"
							error={errors.description}
						/>
					)}
				/>
			</S.Wrapper>
		</BaseModal>
	);
};
