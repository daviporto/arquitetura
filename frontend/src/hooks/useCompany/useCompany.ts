import { AxiosResponse } from 'axios';
import { CompaniesResponse } from 'protocols/external/company/company';
import { GetClient } from 'services/httpClient/get';
import { GetCompaniesRouteConst } from 'utils/routes';

export type UseCompanyProps = {};

export const useCompany = () => {
	const findCompanies = async () => {
		const getClient = new GetClient();

		return await getClient.get<AxiosResponse<CompaniesResponse>>({
			url: `/${GetCompaniesRouteConst}`,
		});
	};

	return {
		findCompanies,
	};
};
