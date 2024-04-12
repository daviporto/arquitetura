import styled, { css } from 'styled-components'

export const Wrapper = styled.div`
    ${({ theme }) => css`
    display: flex;
    padding: ${theme.space.small};
    justify-content: space-between;
`}
`

