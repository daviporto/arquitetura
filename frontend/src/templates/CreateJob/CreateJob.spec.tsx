import 'components/Sidebar/Sidebar.mock';
import { fireEvent, render, screen } from 'utils/test/test-utils';
import { CreateJob } from './CreateJob';

const textsToMatch = [
  'Empresa',
	'Informações do emprego',
	'Título do Emprego',
	'Tipo de Contratação',
	'Salário',
];

const textsToMatchNextSection = [
	'Descrição do emprego',
];

describe('<CreateJob />', () => {
	it('should render the component', () => {
		render(<CreateJob />, {
			queryProvider: true,
		});

		textsToMatch.forEach((text) =>
			expect(screen.getByText(text)).toBeInTheDocument(),
		);
	});

	it('should render the second content in component', () => {
		render(<CreateJob />, {
			queryProvider: true,
		});

		fireEvent.click(screen.getByText('Descrição do emprego'));

		textsToMatchNextSection.forEach((text) =>
			expect(screen.getAllByText(text).length).toBeGreaterThanOrEqual(1),
		);
	});
});
