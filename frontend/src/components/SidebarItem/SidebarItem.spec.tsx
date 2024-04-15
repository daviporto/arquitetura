import 'icons/HomeIcon/HomeIcon.mock';
import { render, screen } from 'utils/test/test-utils';
import { SidebarItem } from './SidebarItem';
import { HomeIcon } from 'icons/HomeIcon/HomeIcon';

describe('<SidebarItem />', () => {
  it('should render the component', () => {
    render(
      <SidebarItem href="/new-page" icon={<HomeIcon />} text="Minhas vagas" />,
    );

    expect(screen.getByTestId('Mock HomeIcon')).toBeInTheDocument();
    expect(
      screen.getByRole('link', { name: 'Minhas vagas' }),
    ).toBeInTheDocument();
  });
});