import { render, screen, act, fireEvent } from 'utils/test/test-utils';
import { ModalEditJob, ModalEditJobProps } from './ModalEditJob';
import { jobMock } from 'test/mocks/external/job';
import { vi } from 'vitest';

const props: ModalEditJobProps = {
	job: jobMock,
	open: false,
	setOpen: vi.fn(),
};

describe('<ModalEditJob />', () => {
	it('should render the component', async () => {
    render(<ModalEditJob {...props} />, {
      queryProvider: true
    });

		const applyButton = screen.getByRole('button', { name: /Editar/i });

		await act(async () => {
			fireEvent.click(applyButton);
		});

		expect(props.setOpen).toHaveBeenCalledTimes(1);
	});
});
