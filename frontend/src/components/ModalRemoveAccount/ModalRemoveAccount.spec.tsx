import { render, screen } from 'utils/test/test-utils'
import { ModalRemoveAccount } from './ModalRemoveAccount'

describe('<ModalRemoveAccount />', () => {
  it('should render the component', () => {
    render(
      <ModalRemoveAccount />
    )

    expect(screen.getByText('ModalRemoveAccount')).toBeInTheDocument()
  })
})
