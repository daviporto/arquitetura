import { BaseModal } from 'components/Modals/BaseModal';
import { useModalRemoveAcademicRecord } from '.';

export type ModalRemoveAcademicRecordProps = {};

export const ModalRemoveAcademicRecord =
	({}: ModalRemoveAcademicRecordProps) => {
		const { open, loading, setOpen, handleSubmit, academicRecord } =
			useModalRemoveAcademicRecord();

		return (
			<BaseModal
				title="Tem certeza que deseja excluir esse registro acadêmico?"
				open={open}
				setOpen={setOpen}
				confirmButtonText="Excluir"
				confirmHandler={handleSubmit}
				cancelButtonText="Cancelar"
				isConfirmButtonLoading={loading}
			>
				{!!academicRecord && (
					<p>
						Tem certeza que deseja remover o registro acadêmico{' '}
						<b>{academicRecord.name}</b> do seu currículo?
					</p>
				)}
			</BaseModal>
		);
	};
