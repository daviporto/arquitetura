import { render, screen } from 'utils/test/test-utils';
import { JobFilter, JobFilterProps } from '.';
import { vi } from 'vitest';

const textsToMatch = [
	'Tipo de Contratação',
	'Pagamento por',
	'Modelo de trabalho',
	'Faixa Salarial',
];

const props: JobFilterProps = {
	setFilter: vi.fn(),
};

describe('<JobFilter />', () => {
	it('should render the component', () => {
		render(<JobFilter {...props} />);

		textsToMatch.forEach((text) =>
			expect(screen.getByText(text)).toBeInTheDocument(),
		);
	});
});
