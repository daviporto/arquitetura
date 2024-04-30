import styled, { css } from 'styled-components';
import { TextareaProps } from './Textarea';

export const Wrapper = styled.div`
  ${({ theme }) => css``}
`;

export const Textarea = styled.textarea`
  ${({ theme }) => css`
    padding: ${theme.space.small};
    width: 100%;
    resize: none;
    border: 1px solid #d6ddeb;

    &::placeholder {
      color: #a8adb7;
      font-size: ${theme.font.sizes.sm};
      font-weight: ${theme.font.weights.regular};
    }
  `}
`;

export const LengthInformation = styled.div`
  ${({ theme }) => css`
    color: #a8adb7;
    font-size: ${theme.font.sizes.sm};
    font-weight: ${theme.font.weights.regular};
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: ${theme.space.xxxsmall};
  `}
`;