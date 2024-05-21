import { formatToCurrency } from "."

describe('formatInputCurrency()', () => {
  it('should receive a string and convert into BRL format', () => {
    expect(formatToCurrency('10000')).toBe('R$ 100,00')
  })
  it('should receive a number and convert into BRL format', () => {
    expect(formatToCurrency(10000)).toBe('R$ 100,00')
  })
})
