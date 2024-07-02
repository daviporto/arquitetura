import styled, { css } from 'styled-components';

export const Wrapper = styled.div`
	${({ theme }) => css`
		& > *:not(:first-child) {
			margin-top: ${theme.space.medium};
		}

    textarea {
      min-height: 15rem;
    }
	`}
`;

export const SelectWrapper = styled.div`
	${({ theme }) => css`
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: ${theme.space.small};

      select {
        width: 100%;
      }
	`}
`

