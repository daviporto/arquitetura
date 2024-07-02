import { render, screen } from 'utils/test/test-utils';
import { Textarea } from './Textarea';
import { MaxLength } from 'utils/maxLengths';

describe('<Textarea />', () => {
	it('should render the component', () => {
		render(<Textarea defaultValue="test" />);

		expect(screen.getByRole('textbox')).toBeInTheDocument();
	});
	it('should render additional information when maxLength is provided', () => {
		const props = {
			maxLength: 10,
		};
		render(<Textarea defaultValue="test" {...props} />);

		expect(screen.getByText(`4/${MaxLength.description}`)).toBeInTheDocument();
	});
});
