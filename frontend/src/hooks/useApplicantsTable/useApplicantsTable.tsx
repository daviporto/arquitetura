import { useQuery } from '@tanstack/react-query';
import {
	createColumnHelper,
	getCoreRowModel,
	getFilteredRowModel,
	getPaginationRowModel,
	getSortedRowModel,
	SortingState,
	useReactTable,
} from '@tanstack/react-table';
import { Avatar } from 'components/Avatar';
import { Pill } from 'components/Pill';
import { SeeApplication } from 'components/SeeApplication';
import * as S from 'components/Table/TableData/TableData.styles';
import { format } from 'date-fns';
import { ptBR } from 'date-fns/locale';
import { useJobApplication } from 'hooks/useJobApplication';
import {
	JobApplication,
	JobApplicationResponse,
	JobStatus,
} from 'protocols/external/job/job-application';
import { useMemo, useState } from 'react';
import { getInitialData } from 'utils/initialData';
import { jobStatusPillVariant, translateJobApplicationStatus } from 'utils/job';
import { GetUsersRouteConst } from 'utils/routes';
import { JobUrl } from 'utils/urls';

const columnHelper = createColumnHelper<JobApplication>();

export type UseApplicantsTableProps = {
	jobsId?: number[];
	initialData?: JobApplicationResponse;
};

export const useApplicantsTable = ({
	jobsId = [],
	initialData,
}: UseApplicantsTableProps) => {
	const [isSorting, setSorting] = useState<SortingState>([]);
	const [globalFilter, setGlobalFilter] = useState('');

	const { findJobApplications } = useJobApplication();

	const { data, isLoading } = useQuery({
		queryKey: [`/${GetUsersRouteConst}`],
		queryFn: () =>
			findJobApplications({
				includes: ['candidates', 'job'],
				jobsId,
			}),
		initialData: initialData
			? getInitialData<JobApplicationResponse>({
					initialData,
				})
			: undefined,
	});

	const tableData = useMemo(() => {
		return data?.data.data || [];
	}, [data?.data.data]);

	const columns = [
		columnHelper.accessor((row) => row.candidates![0].name, {
			id: 'applicant_full_name',
			header: () => <S.TableData>Nome completo</S.TableData>,
			cell: (info) => (
				<S.UserWrapperColumn>
					<Avatar user={info.getValue()} showUsername={false} />
					<p title={info.getValue()}>{info.getValue()}</p>
				</S.UserWrapperColumn>
			),
		}),
		columnHelper.accessor((row) => row.status, {
			id: 'applicant_state',
			header: () => <S.TableData>Status</S.TableData>,
			cell: (info) => {
				const status = info.getValue() as unknown as JobStatus;

				return (
					<Pill
						text={translateJobApplicationStatus[status]}
						variant={jobStatusPillVariant[status]}
					/>
				);
			},
		}),
		columnHelper.accessor((row) => row, {
			id: 'applicant_application_date',
			header: () => <S.TableData>Data</S.TableData>,
			cell: (info) => {
				const date = format(
					new Date(info.getValue().job!.updated_at),
					'dd MMMM yyyy',
					{
						locale: ptBR,
					},
				);

				return <S.TableData>{date}</S.TableData>;
			},
		}),
		columnHelper.accessor((row) => row.job, {
			id: 'applicant_job_function',
			header: () => <S.TableData>Vaga</S.TableData>,
			cell: (info) => (
				<S.TableDataLink
					href={`/${JobUrl(info.getValue()!.id)}`}
					target="_blank"
				>
					{info.getValue()!.name}
				</S.TableDataLink>
			),
		}),
		columnHelper.accessor((row) => row, {
			id: 'applicant_application_button',
			enableSorting: false,
			header: () => null,
			cell: (info) => {
				if (!info.getValue() || !info.getValue().candidates![0]) return null;

				const { id, name, email, phone } = info.getValue().candidates![0];
				const { id: jobId, cover_letter, status, job_id } = info.getValue();

				return (
					<SeeApplication
            id={id}
						user_id={id}
						jobId={job_id}
						name={name}
						email={email}
						phone={phone}
						coverLetter={cover_letter}
						status={status}
					/>
				);
			},
		}),
	];

	const table = useReactTable({
		data: tableData,
		columns,
		state: {
			sorting: isSorting,
			globalFilter,
		},
		onGlobalFilterChange: setGlobalFilter,
		getPaginationRowModel: getPaginationRowModel(),
		getFilteredRowModel: getFilteredRowModel(),
		onSortingChange: setSorting,
		getCoreRowModel: getCoreRowModel(),
		getSortedRowModel: getSortedRowModel(),
	});

	const applicantsData = data?.data.data;

	const currentPage = table.getState().pagination.pageIndex + 1;
	const itemsPerPage = 10;

	return {
		data,
		table,
		applicantsData,
		currentPage,
		itemsPerPage,
		isLoading,
		globalFilter,
		setGlobalFilter,
	};
};
