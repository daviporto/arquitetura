export type CompaniesResponse = {
	data: Company[];
};

export type Company = {
	id: number;
	responsible_id: number;
	name: string;
	description: string;
	phone: string;
	email: string;
	cnpj: number;
	fantasy_name: string;
	location: null;
};
