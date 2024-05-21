import 'components/Input/Input.mock';
import 'components/JobFilter/JobFilter.mock';
import { render, screen } from 'utils/test/test-utils';
import { Jobs } from './Jobs';

import { vi } from "vitest"

vi.mock('templates/Jobs/LoadingJobs', () => ({
  LoadingJobs: () => {
    return <div data-testid="Mock LoadingJobs" />
  },
}))


describe('<Jobs />', () => {
  it('should render the component', () => {
    render(<Jobs />, {
      queryProvider: true,
    });

    expect(screen.getByTestId('Mock LoadingJobs')).toBeInTheDocument();
    expect(screen.getByTestId('Mock JobFilter')).toBeInTheDocument();
  });
});
