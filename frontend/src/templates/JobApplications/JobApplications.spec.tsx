import 'components/Sidebar/Sidebar.mock';
import { render, screen } from 'utils/test/test-utils';
import { JobApplications, JobApplicationsProps } from './JobApplications';
import { mockJobApplication } from 'test/mocks/external/job-application';

const props: JobApplicationsProps = {
	jobId: 15,
	initialData: {
		data: mockJobApplication,
	},
};

describe('<JobApplications />', () => {
	it('should render the component', () => {
		render(<JobApplications {...props} />, {
			queryProvider: true,
		});

		expect(screen.getByText('Total de Candidatos: 1')).toBeInTheDocument();
		expect(screen.getByText('test')).toBeInTheDocument();
		expect(screen.getByText('Pendente')).toBeInTheDocument();
	});
});
