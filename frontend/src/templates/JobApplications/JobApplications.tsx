'use client';
import { ApplicantsTable } from 'components/ApplicantsTable';
import { ApplicationHeader } from 'components/ApplicationHeader';
import { Breadcrumb, BreadcrumbPath } from 'components/Breadcrumb';
import { useApplicantsTable } from 'hooks/useApplicantsTable';
import { JobApplicationResponse } from 'protocols/external/job/job-application';
import { useEffect } from 'react';
import { Base } from 'templates/Base/Base';
import { HomeUrl, JobUrl, JobUrlApplicants, JobsUrl } from 'utils/urls';
import * as S from './JobApplications.styles';

export type JobApplicationsProps = {
	jobId: number;
	initialData: JobApplicationResponse;
};

export const JobApplications = ({
	jobId,
	initialData,
}: JobApplicationsProps) => {
	const {
		table,
		applicantsData,
		currentPage,
		itemsPerPage,
		isLoading,
		globalFilter,
		setGlobalFilter,
	} = useApplicantsTable({
		jobsId: [jobId],
		initialData,
	});

	useEffect(() => {
		return () => {
			setGlobalFilter('');
		};
	}, []);

	const paths: BreadcrumbPath[] = [
		{
			name: 'Home',
			url: `/${HomeUrl}`,
		},
		{
			name: 'Vagas',
			url: `/${JobsUrl}`,
		},
	];

	if (applicantsData && applicantsData[0]) {
		paths.push(
			{
				name: applicantsData![0].job!.name,
				url: `/${JobUrl(jobId)}`,
			},
			{
				name: 'Candidatos',
				url: `/${JobUrlApplicants(jobId)}`,
			},
		);
	} else {
		paths.push({
			name: 'Candidatos',
			url: `/${JobUrlApplicants(jobId)}`,
		});
	}

	return (
		<Base>
			<S.Wrapper>
				<ApplicationHeader />

				{!!applicantsData && (
					<S.BreadcrumbWrapper>
						<Breadcrumb paths={paths} />
					</S.BreadcrumbWrapper>
				)}

				<ApplicantsTable
					table={table}
					applicantsData={applicantsData}
					isLoading={isLoading}
					globalFilter={globalFilter}
					setGlobalFilter={setGlobalFilter}
					currentPage={currentPage}
					itemsPerPage={itemsPerPage}
				/>
			</S.Wrapper>
		</Base>
	);
};
