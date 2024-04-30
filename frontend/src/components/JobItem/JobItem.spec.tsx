import { render, screen } from 'utils/test/test-utils';
import { JobItem, JobItemProps } from './JobItem';

const props: JobItemProps = {
  id: 4,
  name: 'Industrial-Organizational Psychologist',
  description:
    'Voluptates in quasi officia quis voluptate. Molestias distinctio qui sint aut. Asperiores in sint est ut magnam tempora veritatis.',
  is_available: false,
  applications_amount: 762,
  salary: 19473,
  salary_time_unit: 'hour',
  accept_application_until: new Date('2024-05-10 05:21:46'),
  work_model: 'hybrid',
  employment_type: 'part-time',
  week_workload: 33,
  location: '96765 Candelario Forges\nWardberg, IL 77290',
  company_id: 58,
  created_at: new Date('2024-04-29T21:44:25.000000Z'),
  updated_at: new Date('2024-04-29T21:44:25.000000Z'),
  user_id: 174,
};

describe('<JobItem />', () => {
  it('should render the component', () => {
    render(<JobItem {...props} />);

    expect(screen.getByText(props.name)).toBeInTheDocument();
    expect(screen.getByText(props.name)).toBeInTheDocument();
    expect(screen.getByText('Wardberg, IL 77290')).toBeInTheDocument();
    expect(screen.getAllByText('Híbrido')).toHaveLength(2);
    expect(screen.getAllByText('Meio Período')).toHaveLength(2);
    expect(
      screen.getAllByText('194,73', { exact: false }),
    ).toHaveLength(2);
    expect(
      screen.getAllByText('/hora', { exact: false }),
    ).toHaveLength(2);

    expect(screen.getByText(`Máximo ${props.applications_amount}`))
    expect(screen.getByRole('button', { name: /Aplicar/i })).toBeDisabled();
  });
});
