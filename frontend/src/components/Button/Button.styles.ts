import styled, { css, DefaultTheme } from 'styled-components';
import { ButtonProps } from './Button';

export type WrapperProps = {
	$hasIcon: boolean;
	$size: ButtonProps['size'];
	$fullWidth: ButtonProps['fullWidth'];
	$minimal: ButtonProps['minimal'];
	$variant: ButtonProps['variant'];
	$isLoading: boolean;
};

const wrapperModifiers = {
	small: (theme: DefaultTheme) => css`
		height: 3rem;
		font-size: ${theme.font.sizes.sm};
	`,

	medium: (theme: DefaultTheme) => css`
		height: 4rem;
		font-size: ${theme.font.sizes.smd};
		padding: ${theme.space.xxsmall} ${theme.space.medium};
	`,

	fullWidth: (theme: DefaultTheme) => css`
		width: 100%;
		height: 5.6rem;
		padding: ${theme.space.xxsmall};
		border-radius: 1.2rem;
	`,

	withIcon: (theme: DefaultTheme) => css`
		svg {
			width: 1.5rem;

			& + span {
				margin-left: ${theme.space.xxsmall};
			}
		}
	`,

	minimal: (theme: DefaultTheme) => css`
		background: none;
		color: ${theme.colors.primary};
	`,

	disabled: () => css`
		&:disabled {
			cursor: not-allowed;
			filter: saturate(30%);
		}
	`,
};

const variantsModifiers = {
	primary: (theme: DefaultTheme) => css`
		background: ${theme.colors.primary};
		color: ${theme.colors.white};
	`,
	secondary: (theme: DefaultTheme) => css`
		color: ${theme.colors.primary};
		background: ${theme.colors.cleanBlue};
		border: 1px solid ${theme.colors.primary};
		span {
			font-size: ${theme.font.sizes.xs};
		}
	`,
};

export const Wrapper = styled.button<WrapperProps>`
	${({
		theme,
		$size,
		$fullWidth,
		$hasIcon,
		$minimal,
		disabled,
		$variant = 'primary',
	}) => css`
		display: inline-flex;
		align-items: center;
		justify-content: center;
		background: ${theme.colors.primary};
		color: ${theme.colors.white};
		border: 0;
		cursor: pointer;
		padding: ${theme.space.xxsmall};
		text-decoration: none;
		font-weight: ${theme.font.weights.medium};
		text-wrap: nowrap;

		&:focus {
			outline: 1px dashed;
		}

		span {
			font-size: ${theme.font.sizes.sm};
		}

		l-ring {
			color: ${theme.colors.white};
		}

		${!!$size && wrapperModifiers[$size](theme)};
		${!!$hasIcon && wrapperModifiers.withIcon(theme)};
		${!!$minimal && wrapperModifiers.minimal(theme)};
		${!!$fullWidth && wrapperModifiers.fullWidth(theme)};
		${disabled && wrapperModifiers.disabled()};
		${variantsModifiers[$variant](theme)};
	`}
`;
