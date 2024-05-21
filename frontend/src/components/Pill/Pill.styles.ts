import styled, { DefaultTheme, css } from 'styled-components';

export type WrapperProps = {
	$variant: 'success' | 'error' | 'info' | 'warning';
};

const wrapperModifiers = {
	success: (theme: DefaultTheme) => css`
		color: ${theme.colors.lightGreen};
		border: 1px solid ${theme.colors.lightGreen};
	`,

	error: (theme: DefaultTheme) => css`
		color: ${theme.colors.lightRed};
		border: 1px solid ${theme.colors.lightRed};
	`,

	info: (theme: DefaultTheme) => css`
		color: ${theme.colors.primary};
		border: 1px solid ${theme.colors.primary};
	`,

	warning: (theme: DefaultTheme) => css`
		color: ${theme.colors.mustYellow};
		border: 1px solid ${theme.colors.mustYellow};
	`,
};

export const Wrapper = styled.div<WrapperProps>`
	${({ theme, $variant }) => css`
		padding: ${theme.borderRadius.s} 1rem;
		border-radius: 8rem;
		background: ${theme.colors.white};
		color: ${theme.colors.lightGreen};
		font-size: ${theme.font.sizes.xs};
		font-weight: ${theme.font.weights.medium};
		font-family: arial;
		width: min-content;
		text-wrap: nowrap;
		${wrapperModifiers[$variant](theme)};
	`}
`;
