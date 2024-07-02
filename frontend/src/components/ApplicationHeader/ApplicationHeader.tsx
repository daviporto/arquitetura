import { Button } from 'components/Button';
import Link from 'next/link';
import { useLoggedUserStore } from 'stores/loggedUserStore';
import { CreateJobUrl } from 'utils/urls';
import * as S from './ApplicationHeader.styles';
import { UserEnum } from 'protocols/external/user/user';

export type ApplicationHeaderProps = {};

export const ApplicationHeader = ({}: ApplicationHeaderProps) => {
	const { type } = useLoggedUserStore((state) => ({
		type: state.type,
	}));

	return (
		<S.Header>
			<S.CompanyWrapper>
				<S.CompanyTextsWrapper>
					<p>Empresa</p>
				</S.CompanyTextsWrapper>
			</S.CompanyWrapper>

			{type === UserEnum.RECRUITER && (
				<Link href={`/${CreateJobUrl}`}>
					<Button>Anuncie uma vaga</Button>
				</Link>
			)}
		</S.Header>
	);
};
