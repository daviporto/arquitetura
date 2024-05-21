import { render } from 'utils/test/test-utils';
import { Skeleton } from './Skeleton';

describe('<Skeleton />', () => {
  it('should render the component', () => {
    const { container } = render(<Skeleton />);

    expect(container).toMatchInlineSnapshot(`
      .c0 {
        width: 100%;
        height: 1.12rem;
        margin-bottom: 0.5rem;
        border-radius: 0.25rem;
        background-image: linear-gradient(     100deg,     rgba(255, 255, 255, 0),     rgba(255, 255, 255, 0.5) 60%,     rgba(255, 255, 255, 0) 80%   );
        animation: fGXKiQ 1s infinite alternate;
      }

      <div>
        <div
          class="Toastify"
        />
        <div
          class="c0"
        />
      </div>
    `);
  });
});
