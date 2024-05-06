import { vi } from "vitest"

vi.mock('components/Table/TableHeader/TableHeader', () => ({
  TableHeader: () => {
    return <div data-testid="Mock TableHeader" />
  },
}))
