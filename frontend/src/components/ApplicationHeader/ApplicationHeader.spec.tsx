import { render, renderHook, screen, act } from 'utils/test/test-utils';
import { ApplicationHeader } from '.';
import { useLoggedUserStore } from 'stores/loggedUserStore';

describe('<ApplicationHeader />', () => {
	it('should render the component', () => {
		const { result } = renderHook(() => useLoggedUserStore());

		act(() =>
			result.current.setUser({
				name: 'recruiter',
				type: 'recruiter',
				email: 'recruiter@onfly.com',
			}),
		);
		render(<ApplicationHeader />);

		expect(screen.getByText('Empresa')).toBeInTheDocument();

		expect(
			screen.getByRole('button', { name: /Anuncie uma vaga/i }),
		).toBeInTheDocument();
	});
});
