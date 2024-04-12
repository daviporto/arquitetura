import { vi } from "vitest"

vi.mock('components/Input/Input', () => ({
  Input: () => {
    return <div data-testid="Mock Input" />
  },
}))
