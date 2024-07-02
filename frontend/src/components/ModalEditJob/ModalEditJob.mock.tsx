import { vi } from 'vitest';

vi.mock('components/ModalEditJob', () => ({
	ModalEditJob: () => {
		return <div data-testid="Mock ModalEditJob" />;
	},
}));
