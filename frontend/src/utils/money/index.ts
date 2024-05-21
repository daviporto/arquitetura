export function formatToCurrency(value: string | number) {
  if (value === null || value === undefined) {
    return '';
  }
  const numericValue =
    typeof value === 'string' ? value.replace(/[^0-9]/g, '') : value.toString();

  const number = parseFloat(numericValue) / 100;

  if (isNaN(number)) {
    return '';
  }

  return new Intl.NumberFormat('pt-BR', {
    style: 'currency',
    currency: 'BRL',
  }).format(number);
};

export function formatCurrencyToNumber(value: string): number {
  const floatValue = value
    .replace('R$', '')
    .replaceAll('.', '')
    .replace(',', '.')
    .trim()

  return parseFloat(floatValue)
}

