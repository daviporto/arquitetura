import { SidebarItem } from 'components/SidebarItem/SidebarItem';
import * as S from './Sidebar.styles';
import { HomeIcon } from 'icons/HomeIcon/HomeIcon';
import { useLoggedUserStore } from 'stores/loggedUserStore';
import { Button } from 'components/Button/Button';
import { useSignOut } from 'hooks/useSignOut/useSignOut';

export type SidebarProps = {};

export const Sidebar = ({}: SidebarProps) => {
  const { email } = useLoggedUserStore((state) => ({
    email: state.email,
  }));

  const { signOut } = useSignOut();

  return (
    <S.Wrapper>
      {/* Colocar componente de logo aqui */}
      <S.Items>
        <SidebarItem href="/home" icon={<HomeIcon />} text="Início" />
        <SidebarItem href="/" icon={<HomeIcon />} text="Candidaturas" />
        <SidebarItem href="/jobs" icon={<HomeIcon />} text="Vagas" />
        {/* <SidebarItem href="/candidates" icon={<HomeIcon />} text="Candidatos" /> */}
        {/* <SidebarItem icon={<HomeIcon />} text="Pesquisar Empresas" /> */}
        {/* <SidebarItem icon={<HomeIcon />} text="Meu Perfil Público" /> */}
      </S.Items>

      <div>
        <S.Avatar>{email}</S.Avatar>
        <Button onClick={async () => await signOut()}>Sair</Button>
      </div>
    </S.Wrapper>
  );
};