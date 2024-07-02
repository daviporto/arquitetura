'use client';
import { BackpackIcon, ReaderIcon } from '@radix-ui/react-icons';
import { AddSkills } from 'components/AddSkills';
import { Button } from 'components/Button';
import { ConfigInfoWrapper } from 'components/ConfigInfoWrapper';
import { Input } from 'components/Input';
import { Select } from 'components/Select';
import { Step } from 'components/Step';
import { StepWrapper } from 'components/StepWrapper';
import { Textarea } from 'components/Textarea';
import { Title } from 'components/Title';
import { VerticalRow } from 'components/VerticalRow/VerticalRow';
import { useCreateJob } from 'hooks/useCreateJob/useCreateJob';
import {
	employmentTypeOptions,
	salaryTimeUnitOptions,
	workModelOptions,
} from 'protocols/external/job/job';
import { Controller } from 'react-hook-form';
import { theme } from 'styles/theme';
import { Base } from 'templates/Base/Base';
import {
	REQUIRED_JOB_ACCEPT_APPLICATION,
	REQUIRED_JOB_APPLICATIONS_AMOUNT,
	REQUIRED_JOB_COMPANY,
	REQUIRED_JOB_DESCRIPTION,
	REQUIRED_JOB_EMPLOYMENT_TYPE,
	REQUIRED_JOB_NAME,
	REQUIRED_JOB_SALARY,
	REQUIRED_JOB_SALARY_TIME_UNIT,
	REQUIRED_JOB_WORK_MODEL,
} from 'utils/errors';
import {
	translateEmploymentType,
	translateSalaryTimeUnit,
	translateWorkModel,
} from 'utils/job';
import * as S from './CreateJob.styles';
import { MaxLength } from 'utils/maxLengths';
import { formatToCurrency } from 'utils/money';

export type CreateJobProps = {};

export const CreateJob = ({}: CreateJobProps) => {
	const {
		onSubmit,
		control,
		handleSubmit,
		errors,
		isValid,
		isLoading,
		currentStep,
		setCurrentStep,
		maxStep,
		register,
		setValue,
		companies,
	} = useCreateJob();

	return (
		<Base>
			<S.Wrapper>
				<Title title="Anuncie uma vaga" hasBorder={false} />
				<StepWrapper>
					<Step
						icon={
							<BackpackIcon width={24} height={24} color={theme.colors.white} />
						}
						title="Informações do emprego"
						itemStep={1}
						currentStep={currentStep}
						maxStep={maxStep}
						onClick={() => setCurrentStep(1)}
						isActive={false}
					/>
					<VerticalRow />
					<Step
						icon={
							<ReaderIcon width={24} height={24} color={theme.colors.white} />
						}
						title="Descrição do emprego"
						itemStep={2}
						currentStep={currentStep}
						maxStep={maxStep}
						onClick={() => setCurrentStep(2)}
						isActive={false}
					/>
				</StepWrapper>
			</S.Wrapper>

			<S.Form onSubmit={handleSubmit(onSubmit)}>
				{currentStep === 1 && (
					<>
						<ConfigInfoWrapper
							title="Empresa"
							description="Para qual empresa deseja criar a vaga?"
						>
							<Select
								options={
									companies
										? companies?.map(({ id, name }) => ({
												value: String(id),
												label: name,
											}))
										: []
								}
								defaultValue=""
								register={register}
								requiredMessage={REQUIRED_JOB_COMPANY}
								placeholder="Selecione a empresa"
								name="company_id"
							/>
						</ConfigInfoWrapper>
						<ConfigInfoWrapper
							title="Título do Emprego"
							description="Os títulos de emprego devem descrever uma posição"
						>
							<Controller
								rules={{
									required: REQUIRED_JOB_NAME,
								}}
								control={control}
								name="name"
								render={({ field: { ...field } }) => (
									<Input
										{...field}
										placeholder="e.g Engenheiro de Software"
										error={errors.name}
										maxLength={MaxLength.title}
									/>
								)}
							/>
						</ConfigInfoWrapper>

						<ConfigInfoWrapper title="Tipo de Contratação">
							<S.ContractTypeWrapper>
								<Select
									options={employmentTypeOptions.map((type) => ({
										value: type,
										label: translateEmploymentType[type],
									}))}
									defaultValue=""
									register={register}
									requiredMessage={REQUIRED_JOB_EMPLOYMENT_TYPE}
									placeholder="Selecione o tipo de Contratação"
									name="employment_type"
								/>
							</S.ContractTypeWrapper>
						</ConfigInfoWrapper>

						<ConfigInfoWrapper title="Modelo de Trabalho">
							<S.ContractTypeWrapper>
								<Select
									options={workModelOptions.map((type) => ({
										value: type,
										label: translateWorkModel[type],
									}))}
									defaultValue=""
									register={register}
									requiredMessage={REQUIRED_JOB_WORK_MODEL}
									placeholder="Modelo de Trabalho"
									name="work_model"
								/>
							</S.ContractTypeWrapper>
						</ConfigInfoWrapper>

						<ConfigInfoWrapper
							title="Salário"
							description="Por favor, especifique o salário."
						>
							<Controller
								rules={{
									required: REQUIRED_JOB_SALARY,
								}}
								control={control}
								name="salary"
								render={({ field: { ...field } }) => (
									<Input
										{...field}
										onChange={({ target: { value } }) => {
											field.onChange(formatToCurrency(value));
										}}
										type="text"
										error={errors.salary}
									/>
								)}
							/>
						</ConfigInfoWrapper>

						<ConfigInfoWrapper title="Período de pagamento">
							<S.ContractTypeWrapper>
								<Select
									options={salaryTimeUnitOptions.map((type) => ({
										value: type,
										label: translateSalaryTimeUnit[type],
									}))}
									defaultValue=""
									register={register}
									requiredMessage={REQUIRED_JOB_SALARY_TIME_UNIT}
									placeholder="Selecione o período de pagamento"
									name="salary_time_unit"
								/>
							</S.ContractTypeWrapper>
						</ConfigInfoWrapper>

						<ConfigInfoWrapper title="Localização">
							<Controller
								control={control}
								name="location"
								render={({ field: { ...field } }) => (
									<Input
										{...field}
										error={errors.location}
										maxLength={MaxLength.location}
									/>
								)}
							/>
						</ConfigInfoWrapper>

						<ConfigInfoWrapper title="Número máximo de candidatos">
							<Controller
								rules={{
									required: REQUIRED_JOB_APPLICATIONS_AMOUNT,
								}}
								control={control}
								name="applications_amount"
								render={({ field: { ...field } }) => (
									<Input
										{...field}
										type="number"
										min={1}
										error={errors.applications_amount}
									/>
								)}
							/>
						</ConfigInfoWrapper>

						<ConfigInfoWrapper
							title="Prazo para submissão de candidatos"
							description="Selecione uma data a partir de hoje e não mais que um ano."
						>
							{/* //TODO Ana / Eduardo -> Não permitir datas menor que o dia atual e não permitir ultrapassar 1 ano em relação a data atual */}
							<Controller
								rules={{
									required: REQUIRED_JOB_ACCEPT_APPLICATION,
								}}
								control={control}
								name="accept_application_until"
								render={({ field: { ...field } }) => (
									<Input
										{...field}
										type="date"
										error={errors.accept_application_until}
									/>
								)}
							/>
						</ConfigInfoWrapper>

						<ConfigInfoWrapper
							title="Competências"
							description="Adicione as competências necessárias para essa vaga"
						>
							<AddSkills setValue={setValue} />
						</ConfigInfoWrapper>

						<S.BottomRow>
							<Button
								type="button"
								onClick={() => setCurrentStep(currentStep + 1)}
							>
								Próximo Passo
							</Button>
						</S.BottomRow>
					</>
				)}

				{currentStep === 2 && (
					<>
						<ConfigInfoWrapper title="Descrição do emprego">
							<Controller
								rules={{
									required: REQUIRED_JOB_DESCRIPTION,
								}}
								control={control}
								name="description"
								render={({ field: { ...field } }) => (
									<Textarea
										{...field}
										error={errors.description}
										placeholder="Digite a descrição do emprego"
										style={{
											height: '50rem',
										}}
									/>
								)}
							/>
						</ConfigInfoWrapper>

						<S.BottomRow>
							<Button
								type="button"
								onClick={() => setCurrentStep(currentStep - 1)}
							>
								Voltar
							</Button>
							<Button
								type="submit"
								disabled={!isValid || isLoading}
								isLoading={isLoading}
							>
								Concluir
							</Button>
						</S.BottomRow>
					</>
				)}
			</S.Form>
		</Base>
	);
};
