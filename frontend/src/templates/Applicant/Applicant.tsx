'use client';
import { Title } from 'components/Title';
import { UserProps } from 'protocols/external/user/user';
import { Base } from 'templates/Base/Base';
import { EnvelopeClosedIcon } from '@radix-ui/react-icons';
import { Breadcrumb, BreadcrumbPath } from 'components/Breadcrumb';
import { Fragment } from 'react';
import { yearsMonthsSinceNow } from 'utils/date';
import { formatCellphone } from 'utils/formatCellphone';
import * as S from './Applicant.styles';
import { ApplicantUrl, ApplicantsUrl } from 'utils/urls';
import { Avatar } from 'components/Avatar';

export type ApplicantProps = {
	user: UserProps;
};

export const Applicant = ({ user }: ApplicantProps) => {
	const currentJob = user.professional_experiences?.find(
		(job) => job.is_current === 1,
	);

	const academicRecords = user.academic_records;

	const paths: BreadcrumbPath[] = [
		{
			name: 'Candidatos',
			url: `/${ApplicantsUrl}`,
		},
		{
			name: user.name,
			url: `/${ApplicantUrl(user.id)}`,
		},
	];

	return (
		<Base>
			<Title title="Detalhes do Candidato" hasBorder={false} />
			<S.Wrapper>
        <Breadcrumb paths={paths} />
				<S.Container>
					<S.LeftContent>
						<S.Avatar>
              <Avatar user={user.name} showUsername={false} size="large" />
							<S.AvatarTextContainer>
								<h5>{user.name}</h5>
								<p>Designer de Produtos</p>
							</S.AvatarTextContainer>
						</S.Avatar>

						<S.Contact>
							<h5>Contato</h5>
							<S.ContactItem>
								<EnvelopeClosedIcon />
								<S.ContactTextContainer>
									<h6>E-mail</h6>
									<p>{user.email}</p>
								</S.ContactTextContainer>
							</S.ContactItem>

							<S.ContactItem>
								<EnvelopeClosedIcon />
								<S.ContactTextContainer>
									<h6>Celular</h6>
									<p>{formatCellphone(user.phone)}</p>
								</S.ContactTextContainer>
							</S.ContactItem>
						</S.Contact>
					</S.LeftContent>
					<S.RightContent>
						<S.PersonalInformationTitle>
							Informações Pessoais
						</S.PersonalInformationTitle>
						<S.PersonalInformationContainer>
							<S.PersonalInformationItem>
								<S.PersonalInformationItemTitle>
									Nome Completo
								</S.PersonalInformationItemTitle>
								<S.PersonalInformationItemText>
									{user.name}
								</S.PersonalInformationItemText>
							</S.PersonalInformationItem>

							<S.PersonalInformationItem>
								<S.PersonalInformationItemTitle>
									Idioma
								</S.PersonalInformationItemTitle>
								<S.PersonalInformationItemText>
									Português
								</S.PersonalInformationItemText>
							</S.PersonalInformationItem>
						</S.PersonalInformationContainer>

						<S.PersonalInformationItem>
							<S.PersonalInformationItemTitle>
								Sobre mim
							</S.PersonalInformationItemTitle>
							<S.PersonalInformationItemText>
								{user.about_me || 'Não informado'}
							</S.PersonalInformationItemText>
						</S.PersonalInformationItem>

						<S.PersonalInformationContainer>
							<S.PersonalInformationItem>
								<S.PersonalInformationItemTitle>
									Emprego Atual
								</S.PersonalInformationItemTitle>
								<S.PersonalInformationItemText>
									{currentJob?.position || 'Desempregado'}
								</S.PersonalInformationItemText>
							</S.PersonalInformationItem>

							{currentJob && (
								<S.PersonalInformationItem>
									<S.PersonalInformationItemTitle>
										Tempo de Experiência
									</S.PersonalInformationItemTitle>
									<S.PersonalInformationItemText>
										{`${
											yearsMonthsSinceNow(currentJob.start_date).years
										} ano(s) e ${
											yearsMonthsSinceNow(currentJob.start_date).months
										} meses`}
									</S.PersonalInformationItemText>
								</S.PersonalInformationItem>
							)}
						</S.PersonalInformationContainer>

						<S.PersonalInformationContainer>
							{academicRecords?.map((acad) => (
								<Fragment key={acad.id}>
									<S.PersonalInformationItem>
										<S.PersonalInformationItemTitle>
											Qualificação Acadêmica
										</S.PersonalInformationItemTitle>
										<S.PersonalInformationItemText>
											{acad.institution}
										</S.PersonalInformationItemText>
									</S.PersonalInformationItem>

									<S.PersonalInformationItem>
										<S.PersonalInformationItemTitle>
											Área de Estudo
										</S.PersonalInformationItemTitle>
										<S.PersonalInformationItemText>
											{acad.field_of_study}
										</S.PersonalInformationItemText>
									</S.PersonalInformationItem>
								</Fragment>
							))}
						</S.PersonalInformationContainer>
					</S.RightContent>
				</S.Container>
			</S.Wrapper>
		</Base>
	);
};
