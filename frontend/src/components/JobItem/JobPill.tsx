import { JobItemProps } from './JobItem';
import { Pill } from 'components/Pill/Pill';
import {
	translateEmploymentType,
	translateSalaryTimeUnit,
	translateWorkModel,
} from 'utils/job';
import { formatToCurrency } from 'utils/money';

export type JobPillProps = Pick<
	JobItemProps,
	'employment_type' | 'work_model' | 'salary' | 'salary_time_unit'
>;

export const JobPill = ({
	salary_time_unit,
	salary,
	work_model,
	employment_type,
}: JobPillProps) => {
	return (
		<>
			<Pill
				text={translateEmploymentType[employment_type]}
				variant={'success'}
			/>
			<Pill text={translateWorkModel[work_model]} variant={'warning'} />
			<Pill
				text={`${formatToCurrency(salary)}/${
					translateSalaryTimeUnit[salary_time_unit]
				}`}
				variant={'info'}
			/>
		</>
	);
};
