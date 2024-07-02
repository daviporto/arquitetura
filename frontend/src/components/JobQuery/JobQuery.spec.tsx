import { fireEvent, render, screen } from 'utils/test/test-utils';
import { JobQuery, JobQueryProps } from './JobQuery';
import { vi } from 'vitest';
import { DEFAULT_JOB_FILTER } from 'utils/job';

const props: JobQueryProps = {
	filter: DEFAULT_JOB_FILTER,
	setFilter: vi.fn(),
};

describe('<JobQuery />', () => {
	it('should render the component', () => {
		render(<JobQuery {...props} />);

		const input: HTMLInputElement = screen.getByPlaceholderText('Buscar vaga');

		fireEvent.change(input, { target: { value: 'New Job' } });

		fireEvent.click(screen.getByRole('button', { name: /Buscar/i }));

		expect(props.setFilter).toHaveBeenCalled();
	});

	it('should call setFilter onBlur', () => {
		render(<JobQuery {...props} />);

		const input: HTMLInputElement = screen.getByPlaceholderText('Buscar vaga');

		fireEvent.change(input, { target: { value: 'New Job' } });

		fireEvent.blur(input);

		expect(props.setFilter).toHaveBeenCalled();
	});

	it('should call setFilter on enter', () => {
		render(<JobQuery {...props} />);

		const input: HTMLInputElement = screen.getByPlaceholderText('Buscar vaga');

		fireEvent.change(input, { target: { value: 'New Job' } });

		fireEvent.keyDown(input, { key: 'Enter', code: 'Enter', charCode: 13 });

		expect(props.setFilter).toHaveBeenCalled();
	});
});
