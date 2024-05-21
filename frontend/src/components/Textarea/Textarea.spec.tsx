import { render, screen } from 'utils/test/test-utils'
import { Textarea } from './Textarea'

describe('<Textarea />', () => {
  it('should render the component', () => {
    render(
      <Textarea defaultValue="test" />
    )

    expect(screen.getByRole('textbox')).toBeInTheDocument()
  })
  it('should render additional information when maxLength is provided', () => {
    const props = {
      maxLength: 10
    }
    render(
      <Textarea defaultValue="test" {...props} />
    )

    expect(screen.getByText(`Máximo: ${props.maxLength} caracteres`)).toBeInTheDocument()
    expect(screen.getByText(`0/${props.maxLength}`)).toBeInTheDocument()
  })
})
