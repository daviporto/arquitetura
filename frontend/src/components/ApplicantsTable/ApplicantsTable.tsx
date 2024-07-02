import { Table } from '@tanstack/react-table';
import { Input } from 'components/Input';
import { ModalApplication } from 'components/ModalApplication';
import { Pagination } from 'components/Pagination';
import { TableData } from 'components/Table/TableData';
import { CoverLetterProvider } from 'hooks/contexts/CoverLetter';
import { JobApplication } from 'protocols/external/job/job-application';
import { Dispatch, SetStateAction } from 'react';
import { useLoggedUserStore } from 'stores/loggedUserStore';
import { MaxLength } from 'utils/maxLengths';
import * as S from './ApplicantsTable.styles';

export type ApplicantsTableProps = {
	applicantsData: JobApplication[] | undefined;
	itemsPerPage: number;
	currentPage: number;
	isLoading: boolean;
	globalFilter: string;
	setGlobalFilter: Dispatch<SetStateAction<string>>;
	table: Table<JobApplication>;
};

export const ApplicantsTable = ({
	table,
	applicantsData,
	itemsPerPage,
	currentPage,
	isLoading,
	globalFilter,
	setGlobalFilter,
}: ApplicantsTableProps) => {
	const { type } = useLoggedUserStore((state) => ({
		type: state.type,
	}));

	return (
		<>
			<S.TableTopContent>
				<h3>{`Total de Candidatos: ${applicantsData ? applicantsData.length : '...'}`}</h3>

				<Input
					placeholder="Pesquisar Candidatos"
					onChange={(e) => {
						setGlobalFilter(e.target.value);
					}}
					value={globalFilter}
					maxLength={MaxLength.name}
				/>
			</S.TableTopContent>

			<CoverLetterProvider>
				<ModalApplication type={type} />
				<TableData
					data={table}
					isLoading={isLoading}
					defaultMessage="Nenhum candidato encontrado"
				/>
			</CoverLetterProvider>

			{applicantsData && (
				<S.PaginationWrapper>
					<Pagination
						hasNextPage={table.getCanNextPage()}
						hasPrevPage={table.getCanPreviousPage()}
						page={currentPage}
						limit={itemsPerPage}
						totalItems={applicantsData.length || 0}
						totalPages={table.getPageCount() || 0}
						onPageChange={table.setPageIndex}
						goNextPage={table.nextPage}
						goPreviousPage={table.previousPage}
						hasDetails={true}
					/>
				</S.PaginationWrapper>
			)}
		</>
	);
};
