import { vi } from "vitest"

vi.mock('components/JobItem', () => ({
  JobItem: () => {
    return <div data-testid="Mock JobItem" />
  },
}))
