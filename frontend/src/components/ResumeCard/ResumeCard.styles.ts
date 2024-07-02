import styled, { css } from 'styled-components';

export const Wrapper = styled.section`
	${({ theme }) => css`
		border-radius: ${theme.borderRadius.md};
		background-color: ${theme.colors.secondWhite};
    margin: ${theme.space.xsmall} ${theme.space.large};
	`}
`;

export const ChildrenWrapper = styled.div`
	${({ theme }) => css`
		& > div:not(:last-child) {
			border-bottom: 1px solid ${theme.colors.cleanBlue};
		}
		padding: 0 ${theme.space.medium};
	`}
`;

export const TopInfo = styled.div`
	${({ theme }) => css`
		display: flex;
		align-items: center;
		justify-content: space-between;
		padding: ${theme.space.small} ${theme.space.large};

		span {
			font-size: ${theme.font.sizes.lg};
			font-weight: ${theme.font.weights.medium};
		}
	`}
`;

export const IconsWrapper = styled.div`
	${({ theme }) => css`
		display: flex;
		justify-content: space-between;
		gap: ${theme.space.xsmall};

		svg {
			width: 2.4rem;
			height: 2.4rem;
			cursor: pointer;
		}
	`}
`;
