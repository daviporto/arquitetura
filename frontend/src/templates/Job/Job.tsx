'use client';
import { Breadcrumb } from 'components/Breadcrumb';
import { Button } from 'components/Button';
import { Hr } from 'components/Hr';
import { Info } from 'components/Info';
import { JobCapacity } from 'components/JobCapacity';
import { JobPageButton } from 'components/JobPageButton';
import { MediaMatch } from 'components/MediaMatch';
import { ModalEditJob } from 'components/ModalEditJob';
import { ModalRemoveJob } from 'components/ModalRemoveJob';
import { Skill } from 'components/Skill';
import { VerticalRow } from 'components/VerticalRow/VerticalRow';
import { Job as JobResponse } from 'protocols/external/job/job';
import { UserEnum } from 'protocols/external/user/user';
import { Base } from 'templates/Base/Base';
import {
  filterJobLocation,
  translateEmploymentType,
  translateSalaryTimeUnit,
  translateWorkModel,
} from 'utils/job';
import { formatToCurrency } from 'utils/money';
import { LoadingJob, useJobPage } from '.';
import * as S from './Job.styles';

export type JobProps = {} & JobResponse;

export const Job = (initialData: JobProps) => {
	const {
		job,
		editModalOpen,
		removeModalOpen,
		setRemoveModalOpen,
		setEditModalOpen,
		paths,
		onRemoveJobClick,
		type,
		loggedUserId,
    isLoading,
    refetch
	} = useJobPage({
		initialData
	});

  if (isLoading || !job) {
    return <LoadingJob />
  }

	const {
		id,
		name,
		company,
		location,
		description,
		employment_type,
		applications_amount,
		work_model,
		salary,
		salary_time_unit,
		is_available,
		competences,
		user_id,
		applications_count,
	} = job;


	return (
		<Base>
			<title>{name}</title>
			<S.JobHeaderWrapper>
				<Breadcrumb paths={paths} />

				<S.JobHeader>
					<S.TextWrapper>
						<S.Title>{name}</S.Title>
						<S.JobSubtitle>
							{company!.name}{' '}
							{location && `• ${filterJobLocation(location)} `}
							&bull; {translateEmploymentType[employment_type]}
						</S.JobSubtitle>
					</S.TextWrapper>

					<MediaMatch $greaterThan="medium">
						<VerticalRow />
					</MediaMatch>

					<JobPageButton
						user={{
							type,
							id: loggedUserId,
						}}
						job={{
							id,
							is_available,
						}}
					/>
				</S.JobHeader>
			</S.JobHeaderWrapper>

			<S.Wrapper>
				<S.InfoWrapper>
					<S.Section>
						<S.Title>Descrição</S.Title>
						<S.Description>{description}</S.Description>
					</S.Section>

					<div>
						<S.Section>
							<S.Title>Sobre esse cargo</S.Title>

							<S.JobCapacityWrapper>
								<div>
									<span>
										<b>{applications_count} de</b> no máximo{' '}
										{applications_amount}
									</span>
								</div>
								<JobCapacity
									applicants={applications_count}
									applications_amount={applications_amount}
									showBottomInformation={false}
								/>
							</S.JobCapacityWrapper>

							<S.JobAboutThisRole>
								<Info title="Modelo" text={translateWorkModel[work_model]} />
								<Info
									title="Contratação"
									text={translateEmploymentType[employment_type]}
								/>
								<Info title="Salário" text={formatToCurrency(salary * 100)} />
								<Info
									title="Freq.Pagamento"
									text={translateSalaryTimeUnit[salary_time_unit]}
								/>
							</S.JobAboutThisRole>
						</S.Section>

						<S.Section>
							<S.Title>Competências</S.Title>
							{!!competences?.length ? (
								competences.map((c) => <Skill key={c.id} name={c.name} />)
							) : (
								<p>Não há competências</p>
							)}
						</S.Section>
					</div>
				</S.InfoWrapper>

				<Hr />

				<S.JobCompanyInfo>
					<S.Title>{company?.name}</S.Title>
					<p>{company?.description}</p>
				</S.JobCompanyInfo>

				{type === UserEnum.RECRUITER && user_id === loggedUserId && (
					<S.RemoveJob>
						<ModalEditJob
							job={job}
							setOpen={setEditModalOpen}
							open={editModalOpen}
              refetch={refetch}
						/>
						<ModalRemoveJob
							setOpen={setRemoveModalOpen}
							open={removeModalOpen}
							job={{
								id,
								name,
								companyName: company!.name,
							}}
						/>
						<Button onClick={onRemoveJobClick}>Excluir Vaga</Button>
					</S.RemoveJob>
				)}
			</S.Wrapper>
		</Base>
	);
};
