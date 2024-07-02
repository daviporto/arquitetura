import { PillVariant } from 'components/Pill/Pill';
import {
	EmploymentType,
	JobCompetence,
	SalaryTimeUnit,
	WorkModel,
} from 'protocols/external/job/job';
import { JobStatus } from 'protocols/external/job/job-application';

export const translateSalaryTimeUnit: Record<SalaryTimeUnit, string> = {
	day: 'dia',
	hour: 'hora',
	week: 'semana',
	month: 'mês',
};

export const translateEmploymentType: Record<EmploymentType, string> = {
	'full-time': 'Tempo integral',
	'part-time': 'Meio Período',
};

export const translateWorkModel: Record<WorkModel, string> = {
	onSite: 'Presencial',
	hybrid: 'Híbrido',
	homeOffice: 'Home Office',
};

export const translateJobApplicationStatus: Record<JobStatus, string> = {
	hired: 'Contratado',
	pending: 'Pendente',
	approved: 'Aprovado',
	canceled: 'Cancelado',
	rejected: 'Rejeitado',
	in_progress: 'Em progresso',
};

export const jobStatusPillVariant : Record<JobStatus, PillVariant> = {
	hired: 'success',
	pending: 'warning',
	approved: 'success',
	canceled: 'error',
	rejected: 'error',
	in_progress: 'info',
}

export const filterJobLocation = (location: string) => {
	if (location.indexOf('\n') === -1) return location;

	const splittedData = location.split('\n');

	return splittedData[1].length > 1 ? splittedData[1] : splittedData[0];
};

export const parseSkillsIntoCompetences = (
	skills: string[],
): JobCompetence[] => {
	return skills.map((skill) => ({
		name: skill,
		description: `Experiência em ${skill}`,
		type: 'other',
	})) as JobCompetence[];
};

export type SalaryRange = {
	label: string;
	from: number;
	to: number;
};

export const MAX_SALARY = 10e8
export const salaryRanges: SalaryRange[] = [
	{ label: 'R$700 ou abaixo', from: 0, to: 700 },
	{ label: 'R$700 - R$1000', from: 700, to: 1000 },
	{ label: 'R$1000 - R$1500', from: 1000, to: 1500 },
	{ label: 'R$1500 - R$2000', from: 1500, to: 2000 },
	{ label: 'R$3000 ou acima', from: 3000, to: MAX_SALARY },
];


export const DEFAULT_JOB_FILTER = {
	name: '',
	employment_types: new Set<EmploymentType>(),
	work_models: new Set<WorkModel>(),
	accept_application_until: '',
	salary_time_units: new Set<SalaryTimeUnit>(),
	salary_from: 0,
	salary_to: MAX_SALARY,
};

