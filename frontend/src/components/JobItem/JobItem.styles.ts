import { Skeleton } from 'components/Skeleton/Skeleton';
import styled, { css } from 'styled-components';
import media from 'styled-media-query';

export const Wrapper = styled.div`
	${({ theme }) => css`
		padding: ${theme.space.medium};
		display: grid;
		grid-template-columns: 1fr 0.3fr;
		align-items: center;
		gap: ${theme.space.xxsmall};
		border: ${theme.borderWidth.hairline} solid #d6ddeb;

		${media.lessThan('large')`
    grid-template-columns: 1fr;
    `}
	`}
`;

export const JobInfoLoading = styled(Skeleton)`
	${Wrapper};
	height: 15rem;
`;

export const JobInfoWrapper = styled.div`
	${({ theme }) => css`
		display: grid;
		gap: ${theme.space.medium};
		grid-template-columns: 0.2fr 1fr;
	`}
`;

export const JobInfo = styled.div`
	${({ theme }) => css`
		p {
			color: ${theme.colors.darkBlue};
			font-weight: ${theme.font.weights.medium};
			font-size: ${theme.font.sizes.md};
		}
	`}
`;

export const JobLocationInfo = styled.div`
	${({ theme }) => css`
		color: ${theme.colors.darkTitanium};
		margin: ${theme.space.xxsmall} 0;
	`}
`;

export const PillWrapper = styled.div`
	${({ theme }) => css`
		display: flex;
		align-items: center;
		gap: ${theme.space.xxxsmall};
	`}
`;

export const PillSmallScreen = styled.div`
	${({ theme }) => css`
		display: flex;
		align-items: center;
		gap: ${theme.space.xxxsmall};
		overflow-x: scroll;

		${media.greaterThan('large')`
      display: none;
    `}
	`}
`;

export const JobApplicationInfo = styled.div`
	display: flex;
	flex-direction: column;

	button {
		height: 5rem;
	}
`;

export const JobMaxInfo = styled.div`
	${({ theme }) => css`
		display: flex;
		justify-content: space-between;
		gap: ${theme.space.xxxsmall};
		margin-top: ${theme.space.xxsmall};
		text-wrap: nowrap;

		p {
			font-size: ${theme.font.sizes.xs};
		}
	`}
`;

export const ProgressWrapper = styled.div`
	${({ theme }) => css`
		background: #d6ddeb;
		height: 0.6rem;
		margin-top: ${theme.space.small};
	`}
`;

type ProgressDivProps = {
	$progress: number;
};

export const ProgressDiv = styled.div<ProgressDivProps>`
	${({ theme, $progress }) => css`
		background: ${theme.colors.lightGreen};
		height: 0.6rem;
		width: ${`${$progress}%`};
	`}
`;
