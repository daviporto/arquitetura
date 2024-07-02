import { act, fireEvent, render, screen } from 'utils/test/test-utils';
import { ModalCoverLetter, ModalCoverLetterProps } from '.';
import { vi } from 'vitest';

const props: ModalCoverLetterProps = {
	jobId: 10,
	disabled: false,
	refetch: vi.fn(),
};

describe('<ModalCoverLetter />', () => {
	it('should render the component', async () => {
		render(<ModalCoverLetter {...props} />, {
			queryProvider: true,
		});

		const applyButton = screen.getByRole('button', { name: /Aplicar/i });

		await act(async () => {
			fireEvent.click(applyButton);
		});

		expect(
			screen.getByText('Deseja aplicar para essa vaga?'),
		).toBeInTheDocument();
	});

	it('should not show the modal if the button is disabled', async () => {
		render(<ModalCoverLetter {...props} disabled />, {
			queryProvider: true,
		});

		const applyButton = screen.getByRole('button', { name: /Aplicar/i });

		await act(async () => {
			fireEvent.click(applyButton);
		});

		expect(
			screen.queryByText('Deseja aplicar para essa vaga?'),
		).not.toBeInTheDocument();
	});
});
