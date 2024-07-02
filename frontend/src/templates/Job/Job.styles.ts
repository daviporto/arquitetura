import styled, { css } from 'styled-components';
import media from 'styled-media-query'

export const Wrapper = styled.div`
	${({ theme }) => css`
		background: ${theme.colors.white};
		padding: ${theme.space.medium};
		color: ${theme.colors.darkTitanium};
	`}
`;

export const Title = styled.h3`
	${({ theme }) => css`
		font-size: ${theme.font.sizes.xl};
		font-weight: ${theme.font.weights.medium};
		color: ${theme.colors.darkBlue};
		font-family: 'Inter';
	`}
`;

export const JobHeaderWrapper = styled.div`
	${({ theme }) => css`
		padding: ${theme.space.xxxlarge} 0;
		background: ${theme.colors.secondWhite};

		button {
			padding: 1.4rem 5.6rem;
		}

		& > *:not(:first-child) {
			margin-top: ${theme.space.xxxlarge};
		}
	`}
`;

export const JobHeader = styled.header`
	${({ theme }) => css`
		display: flex;
		align-items: center;
		justify-content: space-between;
		background: ${theme.colors.white};
		padding: ${theme.space.medium};
    
    ${media.lessThan('medium')`
      flex-direction: column;
      gap: ${theme.space.medium};
      button {
        width: 100%;
      }
    `}
	`}
`;

export const TextWrapper = styled.div`
	${({ theme }) => css`
		display: flex;
		flex-direction: column;
		gap: ${theme.space.xxsmall};
	`}
`;

export const JobSubtitle = styled.span`
	${({ theme }) => css`
		color: ${theme.colors.darkTitanium};
	`}
`;

export const InfoWrapper = styled.div`
	${({ theme }) => css`
		display: grid;
		grid-template-columns: repeat(2, 1fr);
		margin-bottom: ${theme.space.large};

    ${media.lessThan('medium')`
      display: flex;
      flex-direction: column;
    `}
	`}
`;

export const Section = styled.section`
	${({ theme }) => css`
		padding: ${theme.space.medium};

		${Title} {
			margin-bottom: ${theme.space.small};
		}
	`}
`;

export const JobCapacityWrapper = styled.div`
	${({ theme }) => css`
		display: flex;
		flex-direction: column;
		gap: ${theme.space.xxsmall};
		background: ${theme.colors.secondWhite};
		padding: ${theme.space.small};
		margin-bottom: ${theme.space.medium};
		border-radius: ${theme.borderRadius.s};
	`}
`;

export const Description = styled.p`
	${({ theme }) => css`
		color: ${theme.colors.darkTitanium};
		font-size: ${theme.font.sizes.sm};
		white-space: break-spaces;
	`}
`;

export const JobAboutThisRole = styled.div`
	${({ theme }) => css`
		display: flex;
		flex-direction: column;

		& > *:not(:first-child) {
			margin-top: ${theme.space.medium};
		}
	`}
`;

export const JobRequiredSkills = styled.div``;

export const JobCompanyInfo = styled.div`
	${({ theme }) => css`
		margin-top: ${theme.space.xxxlarge};

		h3 {
			margin-bottom: ${theme.space.medium};
		}
	`}
`;

export const RemoveJob = styled.div`
	${({ theme }) => css`
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: end;
    position: relative;
    bottom: 0;
    gap: ${theme.space.xxsmall};
	`}
`;
