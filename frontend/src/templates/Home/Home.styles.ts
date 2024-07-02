import styled, { css } from 'styled-components';

export const Wrapper = styled.div``;

export const WelcomeMessage = styled.h2`
	${({ theme }) => css`
		font-weight: ${theme.font.weights.bold};
		font-size: ${theme.font.sizes.lg};
		color: ${theme.colors.darkBlue};
		padding: ${theme.space.large} ${theme.space.large} 0 ${theme.space.large};
	`}
`;

export const ApplicationsWrapper = styled.div`
	${({ theme }) => css`
		padding: ${theme.space.large};

		a {
			padding-top: ${theme.space.medium};
		}
	`}
`;

export const MoreApplicationsWrapper = styled.div`
	${({ theme }) => css`
		text-align: center;
		padding-top: ${theme.space.medium};
	`}
`;

export const MoreApplicationsLink = styled.a`
	${({ theme }) => css`
		cursor: pointer;
		color: ${theme.colors.primary};
		text-decoration: none;
		font-weight: ${theme.font.weights.bold};
		font-size: ${theme.font.sizes.sm};
	`}
`;

export const MessageRecruiter = styled.h2`
	${({ theme }) => css`
		font-size: ${theme.font.sizes.md};
		color: ${theme.colors.darkBlue};
		padding: ${theme.space.large};
	`}
`;
