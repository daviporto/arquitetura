import { Breadcrumb, BreadcrumbPath } from 'components/Breadcrumb';
import { Skeleton } from 'components/Skeleton';
import { Base } from 'templates/Base/Base';
import { HomeUrl, JobsUrl } from 'utils/urls';
import * as S from './Job.styles';

const paths: BreadcrumbPath[] = [
	{
		name: 'Home',
		url: `/${HomeUrl}`,
	},
	{
		name: 'Vagas',
		url: `/${JobsUrl}`,
	},
	{
		name: '...',
		url: ``,
	},
];

export const LoadingJob = () => {
	return (
		<Base>
			<S.JobHeaderWrapper>
				<Breadcrumb paths={paths} />

				<Skeleton
					style={{
						height: '9rem',
					}}
				/>
			</S.JobHeaderWrapper>

			<S.Wrapper>
				<S.InfoWrapper>
					<S.Section>
						<S.Title>Descrição</S.Title>
						<Skeleton
							style={{
								height: '5rem',
							}}
						/>
					</S.Section>

					<div>
						<S.Section>
							<S.Title>Sobre esse cargo</S.Title>

							<S.JobCapacityWrapper>
								<Skeleton
									style={{
										height: '3rem',
									}}
								/>
							</S.JobCapacityWrapper>

							<S.JobAboutThisRole>
								<Skeleton
									style={{
										height: '1rem',
									}}
								/>
								<Skeleton
									style={{
										height: '1rem',
									}}
								/>
								<Skeleton
									style={{
										height: '1rem',
									}}
								/>
							</S.JobAboutThisRole>
						</S.Section>
					</div>
				</S.InfoWrapper>

			</S.Wrapper>
		</Base>
	);
};
