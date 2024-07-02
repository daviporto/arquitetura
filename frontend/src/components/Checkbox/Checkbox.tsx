import { ForwardedRef, forwardRef } from 'react';
import * as S from './Checkbox.styles';

export type CheckboxProps = {
	size?: 'small' | 'medium';
	label: string;
	name?: string;
	onChange?: () => void;
};

const Checkbox = forwardRef(
	(
		{ size = 'medium', label, onChange, ...props }: CheckboxProps,
		ref?: ForwardedRef<HTMLInputElement>,
	) => {
		return (
			<S.Label $size={size}>
				<S.Checkbox
					{...props}
					type="checkbox"
					name={props.name}
					ref={ref}
					onChange={onChange}
				/>
				{label}
			</S.Label>
		);
	},
);

Checkbox.displayName = 'Checkbox';

export { Checkbox };
