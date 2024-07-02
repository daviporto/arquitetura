import { Checkbox } from 'components/Checkbox';
import {
  employmentTypeOptions,
  salaryTimeUnitOptions,
  workModelOptions,
} from 'protocols/external/job/job';
import { Dispatch, SetStateAction, useState } from 'react';
import { JobFilters } from 'templates/Jobs/Jobs';
import {
  MAX_SALARY,
  SalaryRange,
  salaryRanges,
  translateEmploymentType,
  translateSalaryTimeUnit,
  translateWorkModel,
} from 'utils/job';
import * as S from './JobFilter.styles';

export type JobFilterProps = {
	setFilter: Dispatch<SetStateAction<JobFilters>>;
};

export const JobFilter = ({ setFilter }: JobFilterProps) => {
	const [selectedSalaryRanges, setSelectedSalaryRanges] = useState<
		SalaryRange[]
	>([]);

	const toggleItemFromSet = <T,>(key: keyof JobFilters, item: T) => {
		setFilter((currentFilter) => {
			const updatedSet = new Set(currentFilter[key] as Set<T>);

			updatedSet.has(item) ? updatedSet.delete(item) : updatedSet.add(item);

			return {
				...currentFilter,
				[key]: updatedSet,
			};
		});
	};

	const toggleSalaryRange = (range: SalaryRange) => {
		const isSelected = selectedSalaryRanges.some(
			(r) => r.label === range.label,
		);
		let newRange = [];

		if (isSelected) {
			newRange = selectedSalaryRanges.filter((r) => r.label !== range.label);
		} else {
			newRange = [...selectedSalaryRanges, range];
		}

		setSelectedSalaryRanges(newRange);

		if (newRange.length === 0) {
			setFilter((currentFilter) => ({
				...currentFilter,
				salary_from: 0,
				salary_to: MAX_SALARY,
			}));
			return;
		}

		const minSalary = Math.min(...newRange.map((r) => r.from));
		const maxSalary = Math.max(...newRange.map((r) => r.to));

		setFilter((currentFilter) => ({
			...currentFilter,
			salary_from: minSalary,
			salary_to: maxSalary,
		}));
	};

	return (
		<S.Wrapper>
			<S.FilterWrapper>
				<S.Title>Tipo de Contratação</S.Title>
				{employmentTypeOptions.map((type) => (
					<Checkbox
						key={type}
						onChange={() => toggleItemFromSet('employment_types', type)}
						label={translateEmploymentType[type]}
					/>
				))}
			</S.FilterWrapper>

			<S.FilterWrapper>
				<S.Title>Pagamento por</S.Title>
				{salaryTimeUnitOptions.map((timeUnit) => (
					<Checkbox
						key={timeUnit}
						onChange={() => toggleItemFromSet('salary_time_units', timeUnit)}
						label={translateSalaryTimeUnit[timeUnit]}
					/>
				))}
			</S.FilterWrapper>

			<S.FilterWrapper>
				<S.Title>Modelo de trabalho</S.Title>
				{workModelOptions.map((model) => (
					<Checkbox
						key={model}
						onChange={() => toggleItemFromSet('work_models', model)}
						label={translateWorkModel[model]}
					/>
				))}
			</S.FilterWrapper>

			<S.FilterWrapper>
				<S.Title>Faixa Salarial</S.Title>
				{salaryRanges.map((range, index) => (
					<Checkbox
						key={index}
						label={range.label}
						onChange={() => {
							toggleSalaryRange(range);
						}}
					/>
				))}
			</S.FilterWrapper>
		</S.Wrapper>
	);
};
