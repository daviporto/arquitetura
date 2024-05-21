import styled, { css } from 'styled-components';
import media from 'styled-media-query';

export const Wrapper = styled.div``;

export const SearchWrapper = styled.div`
  ${({ theme }) => css`
    margin: ${theme.space.large};
    padding: ${theme.space.medium};
    display: flex;
    align-items: center;
    gap: ${theme.space.xxl};
    border: ${theme.borderWidth.hairline} solid #d6ddeb;

    & > div {
      width: 100%;
    }

    input {
      border-radius: ${theme.borderRadius.none};
      border: none;
      border-bottom: ${theme.borderWidth.hairline} solid #d6ddeb;
    }

    button {
      padding: ${theme.space.xsmall} ${theme.space.medium};
      height: 5rem;
    }
  `}
`;

export const OpportunitiesWrapper = styled.div`
  ${({ theme }) => css`
    display: flex;
    padding: ${theme.space.large};
    gap: ${theme.space.large};

    ${media.lessThan('medium')`
      flex-direction: column;
      align-items: center;
    `}
  `}
`;

export const Opportunities = styled.div`
  ${({ theme }) => css`
    display: flex;
    width: 100%;
    flex-direction: column;

    & > h5 {
      font-size: ${theme.font.sizes.lg};
      font-weight: ${theme.font.weights.bold};
      color: ${theme.colors.darkBlue};
    }

    & > p {
      font-size: ${theme.font.sizes.sm};
      color: ${theme.colors.darkBlue};
      margin-bottom: ${theme.space.large};
    }

    & > div:not(:first-child) {
      margin-top: ${theme.space.medium};
    }
  `}
`;

export const NoJobsMessage = styled.p`
  ${({ theme }) => css`
    text-align: center;
    font-size: ${theme.font.sizes.sm};
  `}
`;
