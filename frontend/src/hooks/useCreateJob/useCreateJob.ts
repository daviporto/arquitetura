import { useQuery, useQueryClient } from '@tanstack/react-query';
import { useJob } from 'hooks/useJob';
import { useRouter } from 'next/navigation';
import { PostJobBody } from 'protocols/external/job/job';
import { useState } from 'react';
import { SubmitHandler, useForm } from 'react-hook-form';
import { GetJobsRouteConst } from 'utils/routes';
import { JobsUrl } from 'utils/urls';
import { formatCurrencyToNumber } from 'utils/money';
import { useCompany } from 'hooks/useCompany/useCompany';

export type UseCreateJobProps = {};

export type CreateJobInputs = PostJobBody;

export const useCreateJob = () => {
	const [isLoading, setIsLoading] = useState(false);
	const [currentStep, setCurrentStep] = useState(1);
	const maxStep = 2;

	const queryClient = useQueryClient();

	const {
		register,
		handleSubmit,
		formState: { errors, isValid },
		control,
		setValue,
		reset,
	} = useForm<CreateJobInputs>({
		mode: 'onBlur',
	});

	const { createJob } = useJob();
	const { findCompanies } = useCompany();
	const { push } = useRouter();

	const onSubmit: SubmitHandler<CreateJobInputs> = async (data, event) => {
		event?.preventDefault();

		const salaryString = data.salary.toString();
		const formattedSalary = formatCurrencyToNumber(salaryString);

		const mountJobObj: PostJobBody = {
			name: data.name,
			description: data.description,
			salary: formattedSalary,
			location: data.location,
			company_id: data.company_id,
			work_model: data.work_model,
			is_available: true,
			competences: data.competences,
			week_workload: 40,
			employment_type: data.employment_type,
			salary_time_unit: data.salary_time_unit,
			applications_amount: data.applications_amount,
			accept_application_until: `${data.accept_application_until} 23:59:59`,
		};

		setIsLoading(true);

		const response = await createJob({
			job: mountJobObj,
		});

		if (response && response.error) {
			setIsLoading(false);
			return;
		}

		reset({
			name: '',
			description: '',
			salary: undefined,
			location: '',
			work_model: undefined,
			is_available: true,
			competences: [],
			week_workload: 0,
			employment_type: undefined,
			salary_time_unit: undefined,
			applications_amount: -Infinity,
			accept_application_until: '',
		});

		await queryClient.invalidateQueries({
			queryKey: [`/${GetJobsRouteConst}`],
		});

		setIsLoading(false);

		push(`/${JobsUrl}`);
	};

	const { data: response } = useQuery({
		queryKey: [],
		queryFn: () => findCompanies(),
	});

	const companies = response?.data.data;

	return {
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
	};
};
