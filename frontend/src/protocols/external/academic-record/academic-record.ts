export type AcademicRecord = {
	id: number;
	user_id: number;
	institution: string;
	degree: string;
	field_of_study: string;
	start_date: string;
	end_date: string | null;
	is_in_progress: number;
	description: string;
	created_at: string;
	updated_at: string;
};

export type PostAcademicRecordBody = {
	is_in_progress: boolean;
} & Omit<
	AcademicRecord,
	'id' | 'user_id' | 'created_at' | 'updated_at' | 'is_in_progress'
>;
