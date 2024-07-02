import { useQueryClient } from '@tanstack/react-query';
import { useJob } from 'hooks/useJob';
import { JobResponse, PutJobBody } from 'protocols/external/job/job';
import { Dispatch, SetStateAction, useState } from 'react';
import { SubmitHandler, useForm } from 'react-hook-form';
import { formatCurrencyToNumber } from 'utils/money';
import { GetJobsRouteConst, JobRouteConst } from 'utils/routes';
import { QueryObserverResult, RefetchOptions } from '@tanstack/react-query';
import { AxiosResponse } from 'axios';

export type EditJobInputs = {
	salary: string;
} & Omit<PutJobBody, 'salary'>;

export type UseModalEditJobProps = {
	setOpen: Dispatch<SetStateAction<boolean>>;
	jobId: number;
	companyId: number;
	refetch?: (
		options?: RefetchOptions | undefined,
	) => Promise<QueryObserverResult<AxiosResponse<JobResponse, any>, Error>>;
};

export const useModalEditJob = ({
	setOpen,
	jobId,
	companyId,
  refetch
}: UseModalEditJobProps) => {
	const { updateJob } = useJob();
	const queryClient = useQueryClient();
	const [isLoading, setIsLoading] = useState(false);

	const {
		handleSubmit,
		formState: { errors, isValid },
		control,
		watch,
		register,
	} = useForm<EditJobInputs>({
		mode: 'onBlur',
	});

	const onSubmit: SubmitHandler<EditJobInputs> = async (data, event) => {
		event?.preventDefault();

		setIsLoading(true);

		const formattedSalary = formatCurrencyToNumber(data.salary);

		const mountJobObj: PutJobBody = {
			name: data.name,
			description: data.description,
			salary: formattedSalary,
			location: data.location,
			applications_count: data.applications_count,
			company_id: companyId,
			work_model: data.work_model,
			is_available: true,
			competences: data.competences,
			week_workload: 40,
			employment_type: data.employment_type,
			salary_time_unit: data.salary_time_unit,
			applications_amount: data.applications_amount,
			accept_application_until: `${data.accept_application_until} 23:59:59`,
		};

		const response = await updateJob({
			job: mountJobObj,
			id: jobId,
		});

		if (response && response.error) {
			setIsLoading(false);
			return;
		}

		await queryClient.invalidateQueries({
			queryKey: [`/${GetJobsRouteConst}`, `/${JobRouteConst(jobId)}`],
		});

    !!refetch && await refetch()

		setIsLoading(false);
		setOpen(false);
	};

	return {
		open,
		setOpen,
		onSubmit,
		control,
		handleSubmit,
		errors,
		isValid,
		isLoading,
		watch,
		register,
	};
};
