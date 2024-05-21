/* eslint-disable react/display-name */
import { ForwardedRef, TextareaHTMLAttributes, forwardRef } from 'react';
import * as S from './Textarea.styles';

export type TextareaProps = {} & TextareaHTMLAttributes<HTMLTextAreaElement>;

const Textarea = forwardRef(
  (
    { maxLength, ...props }: TextareaProps,
    ref?: ForwardedRef<HTMLTextAreaElement>,
  ) => {
    return (
      <S.Wrapper>
        <S.Textarea ref={ref} {...props} />
        {!!maxLength && (
          <S.LengthInformation>
            <p>{`Máximo: ${maxLength} caracteres`}</p>
            <p>{`0/${maxLength}`}</p>
          </S.LengthInformation>
        )}
      </S.Wrapper>
    );
  },
);

export { Textarea };
