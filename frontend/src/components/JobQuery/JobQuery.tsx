import { Input } from 'components/Input';
import { Button } from 'components/Button';
import { Dispatch, KeyboardEvent, SetStateAction, useState } from 'react';
import { JobFilters } from 'templates/Jobs/Jobs';

export type JobQueryProps = {
	filter: JobFilters;
	setFilter: Dispatch<SetStateAction<JobFilters>>;
};

export const JobQuery = ({ setFilter, filter }: JobQueryProps) => {
	const [value, setValue] = useState('');

	const onClick = () => {
		const sanitizedValue = value.trim();

		if (filter.name === sanitizedValue) return;

		setFilter((currentFilter) => {
			return {
				...currentFilter,
				name: sanitizedValue,
			};
		});
	};

	const handleKeyDown = (event: KeyboardEvent<HTMLInputElement>) => {
		if (event.key === 'Enter') {
			onClick();
		}
	};

	return (
		<>
			<Input
				placeholder="Buscar vaga"
				value={value}
				onChange={(e) => setValue(e.target.value)}
				onKeyDown={handleKeyDown}
        onBlur={onClick}
			/>
			<Button onClick={onClick}>Buscar</Button>
		</>
	);
};
