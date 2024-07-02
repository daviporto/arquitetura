import * as S from './Pill.styles';

export type PillVariant = 'success' | 'error' | 'info' | 'warning';

export type PillProps = {
	text: string;
	variant?:  PillVariant
};

export const Pill = ({ text, variant = 'success' }: PillProps) => {
	return <S.Wrapper $variant={variant}>{text}</S.Wrapper>;
};
